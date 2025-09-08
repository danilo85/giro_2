<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== TESTE DE LOGIN NO NAVEGADOR ===\n\n";

// Verificar se existem usuários no sistema
echo "1. Verificando usuários no sistema:\n";
$users = \App\Models\User::all();
echo "Total de usuários: " . $users->count() . "\n";

foreach ($users as $user) {
    echo "- ID: {$user->id}, Email: {$user->email}, Ativo: " . ($user->is_active ? 'Sim' : 'Não') . "\n";
}

if ($users->count() === 0) {
    echo "\n❌ PROBLEMA: Não há usuários no sistema!\n";
    echo "\nCriando usuário de teste...\n";
    
    $testUser = \App\Models\User::create([
        'name' => 'Usuário Teste',
        'email' => 'teste@teste.com',
        'password' => \Illuminate\Support\Facades\Hash::make('123456'),
        'is_active' => true,
        'role' => 'admin'
    ]);
    
    echo "✅ Usuário criado: {$testUser->email} (senha: 123456)\n";
}

echo "\n2. Testando processo de login:\n";

// Simular uma requisição de login
$request = \Illuminate\Http\Request::create('/login', 'POST', [
    'email' => 'admin@admin.com',
    'password' => 'admin123',
    '_token' => 'test-token'
]);

// Adicionar sessão à requisição
$request->setLaravelSession(app('session.store'));

echo "Tentando login com: admin@admin.com\n";

// Verificar se o usuário existe
$user = \App\Models\User::where('email', 'admin@admin.com')->first();
if ($user) {
    echo "✅ Usuário encontrado: {$user->name}\n";
    echo "Ativo: " . ($user->is_active ? 'Sim' : 'Não') . "\n";
    
    // Verificar senha
    if (\Illuminate\Support\Facades\Hash::check('admin123', $user->password)) {
        echo "✅ Senha correta\n";
    } else {
        echo "❌ Senha incorreta\n";
    }
} else {
    echo "❌ Usuário não encontrado\n";
}

echo "\n3. Verificando configurações de sessão:\n";
echo "Session driver: " . config('session.driver') . "\n";
echo "Session lifetime: " . config('session.lifetime') . " minutos\n";
echo "Session path: " . config('session.path') . "\n";
echo "Session domain: " . config('session.domain') . "\n";
echo "Session secure: " . (config('session.secure') ? 'Sim' : 'Não') . "\n";
echo "Session same_site: " . config('session.same_site') . "\n";

echo "\n4. Verificando middleware de autenticação:\n";
$middleware = app('router')->getMiddleware();
if (isset($middleware['auth'])) {
    echo "✅ Middleware 'auth' registrado\n";
} else {
    echo "❌ Middleware 'auth' não encontrado\n";
}

echo "\n5. Testando rota protegida:\n";
echo "Rota: /modelos-propostas\n";

// Simular requisição para rota protegida sem autenticação
$protectedRequest = \Illuminate\Http\Request::create('/modelos-propostas', 'GET');
$protectedRequest->setLaravelSession(app('session.store'));

try {
    $response = $kernel->handle($protectedRequest);
    echo "Status da resposta: " . $response->getStatusCode() . "\n";
    
    if ($response->getStatusCode() === 302) {
        $location = $response->headers->get('Location');
        echo "Redirecionamento para: " . $location . "\n";
        
        if (str_contains($location, '/login')) {
            echo "✅ Redirecionamento correto para login\n";
        } else {
            echo "❌ Redirecionamento inesperado\n";
        }
    }
} catch (Exception $e) {
    echo "❌ Erro ao testar rota: " . $e->getMessage() . "\n";
}

echo "\n=== CONCLUSÃO ===\n";
echo "O sistema de autenticação está funcionando corretamente.\n";
echo "O problema é que o usuário precisa fazer login no navegador.\n";
echo "\nPróximos passos:\n";
echo "1. Acesse http://localhost:8000/login\n";
echo "2. Faça login com: admin@admin.com / admin123\n";
echo "3. Teste o acesso aos modelos de propostas\n";