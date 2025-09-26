<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\File;
use App\Models\FileCategory;
use App\Models\FileActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the file dashboard
     */
    public function index(): View
    {
        $files = Auth::user()->files()
            ->with(['category'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $categories = FileCategory::select('id', 'name', 'color')
            ->orderBy('name')
            ->get();
        
        return view('files.index', compact('files', 'categories'));
    }

    /**
     * Show the file upload form
     */
    public function create(): View
    {
        $categories = FileCategory::all();
        return view('files.create', compact('categories'));
    }

    /**
     * Handle file upload
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required|array',
            'files.*' => 'required|file|max:1048576', // 1GB max per file
            'category_id' => 'nullable|exists:file_categories,id',
            'description' => 'nullable|string|max:500',
            'is_temporary' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $uploadedFiles = [];
            $files = $request->file('files');
            
            foreach ($files as $uploadedFile) {
                $originalName = $uploadedFile->getClientOriginalName();
                $extension = $uploadedFile->getClientOriginalExtension();
                $storedName = Str::uuid() . '.' . $extension;
                
                // Store file
                $path = $uploadedFile->storeAs('files', $storedName, 'public');
                
                // Create file record
                $file = File::create([
                    'user_id' => Auth::id(),
                    'category_id' => $request->category_id,
                    'original_name' => $originalName,
                    'stored_name' => $storedName,
                    'mime_type' => $uploadedFile->getMimeType(),
                    'file_size' => $uploadedFile->getSize(),
                    'is_temporary' => $request->boolean('is_temporary', false),
                    'description' => $request->description
                ]);

                // Log activity
                FileActivityLog::log(
                    Auth::id(),
                    $file->id,
                    'upload',
                    "Uploaded file: {$originalName}"
                );
                
                $uploadedFiles[] = $file->load('category');
            }

            return response()->json([
                'success' => true,
                'message' => count($uploadedFiles) === 1 ? 'File uploaded successfully' : count($uploadedFiles) . ' files uploaded successfully',
                'files' => $uploadedFiles
            ]);

        } catch (\Exception $e) {
            \Log::error('File upload error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->except(['files']),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display file details
     */
    public function show(File $file): View
    {
        $this->authorize('view', $file);
        
        $file->load(['category', 'sharedLinks' => function($query) {
            $query->where('is_active', true)->orderBy('created_at', 'desc');
        }]);
        
        // Get shared links as separate variable for the view
        $sharedLinks = $file->sharedLinks;
        
        // Get activity logs for this file
        $activityLogs = FileActivityLog::where('file_id', $file->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('files.show', compact('file', 'sharedLinks', 'activityLogs'));
    }

    /**
     * Download file
     */
    public function download(File $file)
    {
        $this->authorize('view', $file);
        
        $filePath = 'files/' . $file->stored_name;
        
        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }

        // Log activity
        FileActivityLog::log(
            Auth::id(),
            $file->id,
            'download',
            "Downloaded file: {$file->original_name}"
        );

        return Storage::disk('public')->download($filePath, $file->original_name);
    }

    /**
     * Update file details
     */
    public function update(Request $request, File $file): RedirectResponse
    {
        $this->authorize('update', $file);
        
        $request->validate([
            'category_id' => 'nullable|exists:file_categories,id',
            'description' => 'nullable|string|max:500'
        ]);

        $file->update([
            'category_id' => $request->category_id,
            'description' => $request->description
        ]);

        // Log activity
        FileActivityLog::log(
            Auth::id(),
            $file->id,
            'update',
            "Updated file details: {$file->original_name}"
        );

        return redirect()->route('files.show', $file)
            ->with('success', 'File updated successfully');
    }

    /**
     * Delete file
     */
    public function destroy(File $file): JsonResponse
    {
        $this->authorize('delete', $file);
        
        try {
            // Delete physical file
            $filePath = 'files/' . $file->stored_name;
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            // Log activity before deletion
            FileActivityLog::log(
                Auth::id(),
                $file->id,
                'delete',
                "Deleted file: {$file->original_name}"
            );

            // Delete database record
            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete file: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get files by category (AJAX)
     */
    public function getByCategory(Request $request): JsonResponse
    {
        $categoryId = $request->get('category_id');
        
        $query = Auth::user()->files()->with('category');
        
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        $files = $query->orderBy('created_at', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'files' => $files
        ]);
    }

    /**
     * Get file upload progress (for large files)
     */
    public function uploadProgress(Request $request): JsonResponse
    {
        $sessionId = $request->get('session_id');
        
        // This would typically integrate with a progress tracking system
        // For now, return a simple response
        return response()->json([
            'success' => true,
            'progress' => 100 // Placeholder
        ]);
    }
}
