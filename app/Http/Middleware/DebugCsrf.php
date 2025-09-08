<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebugCsrf
{
    public function handle(Request $request, Closure $next)
    {
        // Log informações da requisição antes do processamento
        Log::info('DebugCsrf - Requisição recebida', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'csrf_token_header' => $request->header('X-CSRF-TOKEN'),
            'csrf_token_input' => $request->input('_token'),
            'csrf_token_session' => session()->token(),
            'has_csrf_meta' => $request->hasHeader('X-CSRF-TOKEN'),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip()
        ]);

        $response = $next($request);

        // Log se a resposta for 403
        if ($response->getStatusCode() === 403) {
            Log::error('DebugCsrf - Erro 403 detectado', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => auth()->id(),
                'session_id' => session()->getId(),
                'csrf_token_header' => $request->header('X-CSRF-TOKEN'),
                'csrf_token_input' => $request->input('_token'),
                'csrf_token_session' => session()->token(),
                'response_content' => $response->getContent()
            ]);
        }

        return $response;
    }
}