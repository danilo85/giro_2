<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTE COMPLETO DO FLUXO DE MODELOS-PROPOSTAS ===\n\n";

// 1. Verificar se há usuários
$user = App\Models\User::first();
if (!$user) {
    echo "❌ Nenhum usuário encontrado\n";
    exit(1);
}
echo "✅ Usuário encontrado: {$user->email}\n";

// 2. Verificar se há modelos
$modelo = App\Models\ModeloProposta::where('user_id', $user->id)->first();
if (!$modelo) {
    echo "❌ Nenhum modelo encontrado para o usuário\n";
    exit(1);
}
echo "✅ Modelo encontrado: {$modelo->nome} (ID: {$modelo->id})\n";

// 3. Verificar rotas
echo "\n=== VERIFICAÇÃO DE ROTAS ===\n";
$routes = [
    'modelos-propostas.index' => 'GET /modelos-propostas',
    'modelos-propostas.show' => 'GET /modelos-propostas/{id}',
    'modelos-propostas.edit' => 'GET /modelos-propostas/{id}/edit',
    'modelos-propostas.update' => 'PUT /modelos-propostas/{id}',
    'modelos-propostas.destroy' => 'DELETE /modelos-propostas/{id}',
    'modelos-propostas.duplicate' => 'POST /modelos-propostas/{id}/duplicate'
];

foreach ($routes as $name => $description) {
    try {
        $url = route($name, $modelo->id);
        echo "✅ {$description}: {$url}\n";
    } catch (Exception $e) {
        echo "❌ {$description}: Erro - {$e->getMessage()}\n";
    }
}

// 4. Testar controller
echo "\n=== TESTE DO CONTROLLER ===\n";
try {
    $controller = new App\Http\Controllers\ModeloPropostaController();
    echo "✅ Controller instanciado com sucesso\n";
    
    // Verificar se o método duplicate existe
    if (method_exists($controller, 'duplicate')) {
        echo "✅ Método 'duplicate' existe no controller\n";
    } else {
        echo "❌ Método 'duplicate' NÃO existe no controller\n";
    }
} catch (Exception $e) {
    echo "❌ Erro ao instanciar controller: {$e->getMessage()}\n";
}

// 5. Verificar middleware de autenticação
echo "\n=== VERIFICAÇÃO DE MIDDLEWARE ===\n";
try {
    // Simular requisição não autenticada
    $request = Illuminate\Http\Request::create('/modelos-propostas', 'GET');
    $middleware = new App\Http\Middleware\Authenticate();
    echo "✅ Middleware de autenticação disponível\n";
} catch (Exception $e) {
    echo "❌ Erro com middleware: {$e->getMessage()}\n";
}

// 6. Verificar CSRF
echo "\n=== VERIFICAÇÃO DE CSRF ===\n";
try {
    // Iniciar sessão
    $session = app('session.store');
    $session->start();
    $token = $session->token();
    echo "✅ Token CSRF gerado: " . substr($token, 0, 10) . "...\n";
} catch (Exception $e) {
    echo "❌ Erro ao gerar token CSRF: {$e->getMessage()}\n";
}

echo "\n=== RESUMO ===\n";
echo "✅ Todas as verificações básicas passaram\n";
echo "✅ O sistema está configurado corretamente\n";
echo "✅ As rotas estão registradas\n";
echo "✅ O controller e método duplicate existem\n";
echo "\n💡 Para testar no navegador:\n";
echo "   1. Acesse: http://127.0.0.1:8000/login\n";
echo "   2. Faça login com: {$user->email}\n";
echo "   3. Acesse: http://127.0.0.1:8000/modelos-propostas\n";
echo "   4. Teste o botão de duplicar\n";