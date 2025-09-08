<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Obter token CSRF
$csrfToken = csrf_token();
echo "CSRF Token: {$csrfToken}\n";

// Simular login do usuário
$user = App\Models\User::first();
if ($user) {
    Auth::login($user);
    echo "Usuário logado: {$user->email}\n";
    
    // Verificar se há modelos
    $modelo = App\Models\ModeloProposta::first();
    if ($modelo) {
        echo "Modelo para teste: {$modelo->nome} (ID: {$modelo->id})\n";
        
        // Testar a rota de duplicação
        try {
            $request = Illuminate\Http\Request::create(
                "/modelos-propostas/{$modelo->id}/duplicate",
                'POST',
                ['_token' => $csrfToken]
            );
            
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
            
            // Simular sessão
            $request->setLaravelSession(app('session.store'));
            
            echo "\nTestando rota de duplicação...\n";
            echo "URL: /modelos-propostas/{$modelo->id}/duplicate\n";
            echo "Método: POST\n";
            echo "Token CSRF: {$csrfToken}\n";
            
        } catch (Exception $e) {
            echo "Erro: {$e->getMessage()}\n";
        }
    } else {
        echo "Nenhum modelo encontrado para teste\n";
    }
} else {
    echo "Nenhum usuário encontrado\n";
}