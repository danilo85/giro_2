<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Notifications\UserApproved;
use App\Notifications\UserRejected;
use App\Http\Controllers\SettingsController;

class UserApprovalController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->isAdmin()) {
                abort(403, 'Acesso negado. Apenas administradores podem acessar esta área.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of pending users.
     */
    public function index()
    {
        $pendingUsers = User::where('admin_approved', false)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $approvedUsers = User::where('admin_approved', true)
            ->whereNotNull('approved_by')
            ->with('approvedBy')
            ->orderBy('approved_at', 'desc')
            ->paginate(20);

        return view('admin.user-approvals.index', compact('pendingUsers', 'approvedUsers'));
    }

    /**
     * Approve a user.
     */
    public function approve(Request $request, User $user)
    {
        if ($user->admin_approved) {
            return back()->with('warning', 'Este usuário já foi aprovado.');
        }

        $user->update([
            'admin_approved' => true,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Send approval email to user if notifications are enabled
        $notificationSettings = SettingsController::get('notifications', 'email_user_approval', false);
        if ($notificationSettings) {
            $user->notify(new UserApproved());
        }

        return back()->with('success', "Usuário {$user->name} foi aprovado com sucesso.");
    }

    /**
     * Reject/Unapprove a user.
     */
    public function reject(Request $request, User $user)
    {
        if (!$user->admin_approved) {
            return back()->with('warning', 'Este usuário já está rejeitado/não aprovado.');
        }

        $user->update([
            'admin_approved' => false,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        // Send rejection email to user if notifications are enabled
        $notificationSettings = SettingsController::get('notifications', 'email_user_approval', false);
        if ($notificationSettings) {
            $user->notify(new UserRejected());
        }

        return back()->with('success', "Aprovação do usuário {$user->name} foi removida.");
    }

    /**
     * Remove approval from a user.
     */
    public function removeApproval(Request $request, User $user)
    {
        if (!$user->admin_approved) {
            return back()->with('warning', 'Este usuário não está aprovado.');
        }

        $user->update([
            'admin_approved' => false,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        return back()->with('success', "Aprovação do usuário {$user->name} foi removida com sucesso.");
    }

    /**
     * Delete a pending user.
     */
    public function delete(Request $request, User $user)
    {
        if ($user->admin_approved) {
            return back()->with('error', 'Não é possível excluir um usuário aprovado.');
        }

        $userName = $user->name;
        $user->delete();

        return back()->with('success', "Usuário {$userName} foi excluído com sucesso.");
    }

    /**
     * Bulk approve users.
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $request->user_ids)
            ->where('admin_approved', false)
            ->get();

        $approvedCount = 0;
        foreach ($users as $user) {
            $user->update([
                'admin_approved' => true,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
            $approvedCount++;

            // Send approval email to user if notifications are enabled
            $notificationSettings = SettingsController::get('notifications', 'email_user_approval', false);
            if ($notificationSettings) {
                $user->notify(new UserApproved());
            }
        }

        return back()->with('success', "{$approvedCount} usuário(s) foram aprovados com sucesso.");
    }

    /**
     * Bulk reject users.
     */
    public function bulkReject(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $request->user_ids)
            ->where('admin_approved', true)
            ->get();

        $rejectedCount = 0;
        foreach ($users as $user) {
            $user->update([
                'admin_approved' => false,
                'approved_by' => null,
                'approved_at' => null,
            ]);
            $rejectedCount++;

            // Send rejection email to user if notifications are enabled
            $notificationSettings = SettingsController::get('notifications', 'email_user_approval', false);
            if ($notificationSettings) {
                $user->notify(new UserRejected());
            }
        }

        return back()->with('success', "{$rejectedCount} usuário(s) tiveram a aprovação removida.");
    }

    /**
     * Bulk delete users.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $request->user_ids)
            ->where('admin_approved', false)
            ->get();

        $deletedCount = 0;
        foreach ($users as $user) {
            $user->delete();
            $deletedCount++;
        }

        return back()->with('success', "{$deletedCount} usuário(s) foram excluídos com sucesso.");
    }
}