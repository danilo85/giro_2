<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simular uma requisição POST para duplicar
$request = Illuminate\Http\Request::create(
    '/modelos-propostas/1/duplicate',
    'POST',
    [],
    [],
    [],
    ['HTTP_X_CSRF_TOKEN' => 'test-token']
);

// Simular usuário autenticado
$user = App\Models\User::first();
if ($user) {
    $request->setUserResolver(function () use ($user) {
        return $user;
    });
    
    echo "Usuário autenticado: {$user->email}\n";
    
    // Verificar se o modelo existe
    $modelo = App\Models\ModeloProposta::find(1);
    if ($modelo) {
        echo "Modelo encontrado: {$modelo->nome}\n";
        echo "Proprietário: {$modelo->user_id}\n";
        echo "Usuário atual: {$user->id}\n";
        
        if ($modelo->user_id == $user->id) {
            echo "✓ Usuário tem permissão para duplicar\n";
        } else {
            echo "✗ Usuário NÃO tem permissão para duplicar\n";
        }
    } else {
        echo "✗ Modelo não encontrado\n";
    }
} else {
    echo "✗ Nenhum usuário encontrado\n";
}

echo "\nTeste de duplicação concluído.\n";