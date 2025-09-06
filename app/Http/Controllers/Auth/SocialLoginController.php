<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    /**
     * Redirect to social provider.
     */
    public function redirectToProvider($provider)
    {
        $allowedProviders = ['google', 'facebook', 'github'];
        
        if (!in_array($provider, $allowedProviders)) {
            return redirect('/login')->with('error', 'Provedor não suportado.');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle social provider callback.
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Erro ao autenticar com ' . ucfirst($provider) . '.');
        }

        // Check if social account exists
        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($socialAccount) {
            // Login existing user
            $user = $socialAccount->user;
            
            if (!$user->is_active) {
                return redirect('/login')->with('error', 'Sua conta está desativada. Entre em contato com o administrador.');
            }
            
            $user->update([
                'is_online' => true,
                'last_seen' => now(),
            ]);
            
            Auth::login($user);
            return redirect('/dashboard');
        }

        // Check if user exists by email
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Link social account to existing user
            $user->socialAccounts()->create([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'provider_token' => $socialUser->token,
                'provider_refresh_token' => $socialUser->refreshToken,
            ]);
        } else {
            // Create new user
            $user = User::create([
                'name' => $socialUser->getName() ?: $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'email_verified_at' => now(),
                'password' => bcrypt(Str::random(16)), // Random password
                'is_admin' => false,
                'is_active' => true,
                'is_online' => true,
                'last_seen' => now(),
                'avatar_path' => $socialUser->getAvatar(),
                'preferences' => json_encode([
                    'theme' => 'light',
                    'sidebar_expanded' => true,
                    'notifications' => true
                ])
            ]);

            // Create social account
            $user->socialAccounts()->create([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'provider_token' => $socialUser->token,
                'provider_refresh_token' => $socialUser->refreshToken,
            ]);
        }

        Auth::login($user);
        return redirect('/dashboard');
    }
}
