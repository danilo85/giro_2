<?php

// Simular uma requisição AJAX para testar o comportamento do admin
require_once 'vendor/autoload.php';

// Configurar o ambiente Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Criar uma requisição simulada
$request = Illuminate\Http\Request::create(
    '/portfolio/categories',
    'POST',
    [
        'name' => 'Test Category Admin',
        'description' => 'Test description for admin',
        '_token' => 'test-token'
    ],
    [], // cookies
    [], // files
    [
        'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
        'HTTP_ACCEPT' => 'application/json, text/javascript, */*; q=0.01',
        'HTTP_CONTENT_TYPE' => 'application/x-www-form-urlencoded; charset=UTF-8'
    ]
);

// Simular autenticação como admin
$user = App\Models\User::where('is_admin', true)->first();
if ($user) {
    Auth::login($user);
    echo "Usuário admin logado: {$user->name} (ID: {$user->id})\n";
} else {
    echo "Nenhum usuário admin encontrado\n";
    exit;
}

echo "\n=== TESTE DE REQUISIÇÃO AJAX PARA ADMIN ===\n";
echo "Método: {$request->method()}\n";
echo "URL: {$request->url()}\n";
echo "Expects JSON: " . ($request->expectsJson() ? 'SIM' : 'NÃO') . "\n";
echo "Accept Header: {$request->header('Accept')}\n";
echo "X-Requested-With: {$request->header('X-Requested-With')}\n";
echo "Content-Type: {$request->header('Content-Type')}\n";

try {
    // Processar a requisição
    $response = $kernel->handle($request);
    
    echo "\n=== RESPOSTA ===\n";
    echo "Status Code: {$response->getStatusCode()}\n";
    echo "Content-Type: {$response->headers->get('Content-Type')}\n";
    
    $content = $response->getContent();
    echo "\nConteúdo da resposta (primeiros 500 caracteres):\n";
    echo substr($content, 0, 500) . "\n";
    
    // Verificar se é JSON válido
    $jsonData = json_decode($content, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "\n✅ Resposta é JSON válido\n";
        echo "Dados JSON: " . print_r($jsonData, true) . "\n";
    } else {
        echo "\n❌ Resposta NÃO é JSON válido\n";
        echo "Erro JSON: " . json_last_error_msg() . "\n";
        
        // Verificar se é HTML
        if (strpos($content, '<!DOCTYPE') !== false || strpos($content, '<html') !== false) {
            echo "🔍 Resposta parece ser HTML\n";
        }
    }
    
} catch (Exception $e) {
    echo "\n❌ ERRO: {$e->getMessage()}\n";
    echo "Arquivo: {$e->getFile()}:{$e->getLine()}\n";
}

$kernel->terminate($request, $response ?? null);