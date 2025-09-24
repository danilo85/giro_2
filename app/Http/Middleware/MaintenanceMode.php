<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SettingsController;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        $maintenanceMode = SettingsController::get('system', 'maintenance_mode', false);
        
        if ($maintenanceMode) {
            // Allow access for authenticated admin users
            if (Auth::check() && Auth::user()->is_admin) {
                return $next($request);
            }
            
            // Allow access to login and logout routes
            $allowedRoutes = [
                'login',
                'logout',
                'maintenance'
            ];
            
            if (in_array($request->route()?->getName(), $allowedRoutes)) {
                return $next($request);
            }
            
            // Redirect non-admin users to maintenance page
            return redirect()->route('maintenance');
        }
        
        return $next($request);
    }
}