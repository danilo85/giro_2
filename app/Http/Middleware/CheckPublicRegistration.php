<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class CheckPublicRegistration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se o registro público está habilitado
        if (!Setting::isPublicRegistrationEnabled()) {
            // Se não estiver habilitado, redirecionar para login com mensagem
            return redirect()->route('login')
                ->with('error', 'O registro de novos usuários está temporariamente desabilitado.');
        }

        return $next($request);
    }
}
