<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Controllers\SettingsController;

class ConditionalEmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $redirectToRoute = null)
    {
        // Check if email verification is required in system settings
        $emailVerificationRequired = SettingsController::get('system', 'email_verification', false);
        
        // If email verification is not required, allow access
        if (!$emailVerificationRequired) {
            return $next($request);
        }
        
        // If email verification is required, check if user has verified email
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
            ! $request->user()->hasVerifiedEmail())) {
            return $request->expectsJson()
                    ? response()->json([
                        'success' => false,
                        'message' => 'Your email address is not verified.',
                        'error' => 'email_not_verified',
                        'redirect' => route('verification.notice')
                    ], 409)
                    : redirect()->guest($redirectToRoute ?: route('verification.notice'));
        }

        return $next($request);
    }
}