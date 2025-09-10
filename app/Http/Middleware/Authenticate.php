<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Se for uma requisição AJAX ou espera JSON, não redirecionar
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return null;
        }
        
        return route('login');
    }
    
    /**
     * Handle an unauthenticated user.
     */
    protected function unauthenticated($request, array $guards)
    {
        // Se for uma requisição AJAX ou espera JSON, retornar resposta JSON
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Não autenticado. Faça login para continuar.',
                'redirect' => route('login')
            ], 401);
        }
        
        // Caso contrário, usar o comportamento padrão (redirecionamento)
        throw new \Illuminate\Auth\AuthenticationException(
            'Unauthenticated.', $guards, $this->redirectTo($request)
        );
    }
}
