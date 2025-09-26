<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\File;
use App\Models\SharedLink;
use App\Models\AccessLog;
use App\Models\FileActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class SharedLinkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'download']);
    }

    /**
     * Create a new shared link
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file_id' => 'required|exists:files,id',
            'expires_at' => 'nullable|date|after:now',
            'download_limit' => 'nullable|integer|min:1|max:1000',
            'password' => 'nullable|string|min:4|max:50'
        ]);

        $file = File::findOrFail($request->file_id);
        
        // Check if user owns the file
        if ($file->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access to file'
            ], 403);
        }

        try {
            $sharedLink = SharedLink::create([
                'file_id' => $file->id,
                'token' => SharedLink::generateUniqueToken(),
                'expires_at' => $request->expires_at ? Carbon::parse($request->expires_at) : null,
                'download_limit' => $request->download_limit,
                'download_count' => 0,
                'password_hash' => $request->password ? Hash::make($request->password) : null,
                'is_active' => true
            ]);

            // Log activity
            FileActivityLog::log(
                Auth::id(),
                $file->id,
                'share',
                "Created shared link for: {$file->original_name}"
            );

            $shareUrl = route('public.shared.show', $sharedLink->token);

            return response()->json([
                'success' => true,
                'message' => 'Shared link created successfully',
                'shared_link' => $sharedLink,
                'share_url' => $shareUrl
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create shared link: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show public shared file page
     */
    public function show(string $token): View
    {
        $sharedLink = SharedLink::where('token', $token)
            ->where('is_active', true)
            ->with('file')
            ->firstOrFail();

        // Check if link is expired
        if ($sharedLink->isExpired()) {
            return view('shared.expired');
        }

        // Check if download limit reached
        if ($sharedLink->isDownloadLimitReached()) {
            return view('shared.expired');
        }

        // Get the file from the shared link
        $file = $sharedLink->file;

        // Log access
        AccessLog::create([
            'shared_link_id' => $sharedLink->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'action' => 'view',
            'accessed_at' => now()
        ]);

        return view('shared.show', compact('sharedLink', 'file'));
    }

    /**
     * Download file via shared link
     */
    public function download(Request $request, string $token)
    {
        $sharedLink = SharedLink::where('token', $token)
            ->where('is_active', true)
            ->with('file')
            ->firstOrFail();

        // Check if link is expired
        if ($sharedLink->isExpired()) {
            return view('shared.expired');
        }

        // Check if download limit reached
        if ($sharedLink->isDownloadLimitReached()) {
            return view('shared.expired');
        }

        // Check password if required
        if ($sharedLink->password_hash) {
            // Check if already authenticated in session
            $sessionKey = 'shared_link_' . $sharedLink->id . '_authenticated';
            
            if (!session($sessionKey)) {
                $request->validate([
                    'password' => 'required|string'
                ]);

                if (!Hash::check($request->password, $sharedLink->password_hash)) {
                    if ($request->expectsJson() || $request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Senha incorreta'
                        ], 422);
                    }
                    return back()->withErrors(['password' => 'Senha incorreta']);
                }
                
                // Store authentication in session
                session([$sessionKey => true]);
            }
        }

        $file = $sharedLink->file;
        $filePath = 'files/' . $file->stored_name;
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }

        // Increment download count
        $sharedLink->incrementDownloadCount();

        // Log download
        AccessLog::create([
            'shared_link_id' => $sharedLink->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'action' => 'download',
            'accessed_at' => now()
        ]);

        // Log activity for file owner
        FileActivityLog::log(
            $file->user_id,
            $file->id,
            'shared_download',
            "File downloaded via shared link: {$file->original_name}"
        );

        return Storage::disk('public')->download($filePath, $file->original_name);
    }

    /**
     * Deactivate shared link
     */
    public function destroy(SharedLink $sharedLink): JsonResponse
    {
        
        // Check if user owns the file
        if ($sharedLink->file->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $sharedLink->update(['is_active' => false]);

        // Log activity
        FileActivityLog::log(
            Auth::id(),
            $sharedLink->file->id,
            'unshare',
            "Deactivated shared link for: {$sharedLink->file->original_name}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Shared link deactivated successfully'
        ]);
    }

    /**
     * Get shared links for a file
     */
    public function getFileLinks(File $file): JsonResponse
    {
        
        // Check if user owns the file
        if ($file->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $sharedLinks = $file->sharedLinks()
            ->where('is_active', true)
            ->with('accessLogs')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($link) {
                return [
                    'id' => $link->id,
                    'token' => $link->token,
                    'share_url' => route('public.shared.show', $link->token),
                    'expires_at' => $link->expires_at,
                    'download_limit' => $link->download_limit,
                    'download_count' => $link->download_count,
                    'has_password' => !is_null($link->password_hash),
                    'is_expired' => $link->isExpired(),
                    'is_download_limit_reached' => $link->isDownloadLimitReached(),
                    'access_count' => $link->accessLogs->count(),
                    'created_at' => $link->created_at
                ];
            });

        return response()->json([
            'success' => true,
            'shared_links' => $sharedLinks
        ]);
    }

    /**
     * Get access logs for a shared link
     */
    public function getAccessLogs(SharedLink $sharedLink): JsonResponse
    {
        
        // Check if user owns the file
        if ($sharedLink->file->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $accessLogs = $sharedLink->accessLogs()
            ->orderBy('accessed_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'logs' => $accessLogs
        ]);
    }
}
