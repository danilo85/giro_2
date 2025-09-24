<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\SettingsController;
use App\Notifications\NewUserRegistered;
use Illuminate\Support\Facades\Notification;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Sua conta está desativada. Entre em contato com o administrador.'],
            ]);
        }

        // Check if admin approval is required and user is not approved
        $adminApprovalRequired = SettingsController::get('system', 'admin_approval', false);

        if ($adminApprovalRequired && !$user->admin_approved) {
            throw ValidationException::withMessages([
                'email' => ['Sua conta está aguardando aprovação do administrador. Você receberá um email quando for aprovada.'],
            ]);
        }

        // Update user online status
        $user->update([
            'is_online' => true,
            'last_activity_at' => now(),
        ]);

        Auth::login($user, $request->boolean('remember'));

        return redirect()->intended('/dashboard');
    }

    /**
     * Handle a logout request.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            $user->update([
                'is_online' => false,
                'last_activity_at' => now(),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ser um endereço válido.',
            'email.unique' => 'Este email já está em uso.',
            'password.required' => 'A senha é obrigatória.',
            'password.confirmed' => 'A confirmação da senha não confere.',
        ]);

        // Check if admin approval is required
        $adminApprovalRequired = SettingsController::get('system', 'admin_approval', false);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
            'is_online' => false,
            'admin_approved' => false, // Always start as not approved, will be set to true if admin approval is not required
        ]);

        // If admin approval is not required, auto-approve the user
        if (!$adminApprovalRequired) {
            $user->update(['admin_approved' => true]);
        }

        // Send notification to administrators about new user registration
        $notificationSettings = SettingsController::get('notifications', 'email_new_users', false);
        if ($notificationSettings) {
            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new NewUserRegistered($user));
        }

        // Check if email verification is required
        $emailVerificationRequired = SettingsController::get('system', 'email_verification', false);

        if ($adminApprovalRequired) {
            // User needs admin approval - don't log them in
            if ($emailVerificationRequired) {
                // Send email verification notification
                $user->sendEmailVerificationNotification();
                return redirect('/login')->with('info', 'Conta criada com sucesso! Verifique seu email e aguarde aprovação do administrador.');
            } else {
                return redirect('/login')->with('info', 'Conta criada com sucesso! Sua conta está aguardando aprovação do administrador. Você receberá um email quando for aprovada.');
            }
        } else {
            // Auto-approve and log in
            $user->update([
                'is_online' => true,
                'last_activity_at' => now(),
            ]);

            Auth::login($user);

            if ($emailVerificationRequired && !$user->hasVerifiedEmail()) {
                // Send email verification notification
                $user->sendEmailVerificationNotification();
                return redirect()->route('verification.notice')->with('success', 'Conta criada com sucesso! Verifique seu email para continuar.');
            }

            return redirect('/dashboard')->with('success', 'Conta criada com sucesso! Bem-vindo ao Giro.');
        }
    }
}
