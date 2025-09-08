<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VERIFICAÇÃO DO USUÁRIO ADMIN ===\n\n";

// Verificar usuário atual
$user = App\Models\User::find(1);
if ($user) {
    echo "Usuário: {$user->name} (ID: {$user->id})\n";
    echo "Email: {$user->email}\n";
    
    // Verificar se tem campo is_admin
    if (isset($user->is_admin)) {
        echo "Campo is_admin: " . ($user->is_admin ? 'true' : 'false') . "\n";
    } else {
        echo "Campo is_admin: NÃO EXISTE\n";
    }
    
    // Verificar todos os campos do usuário
    echo "\nTodos os campos do usuário:\n";
    foreach ($user->getAttributes() as $key => $value) {
        echo "- {$key}: {$value}\n";
    }
} else {
    echo "Usuário com ID 1 não encontrado\n";
}

// Verificar estrutura da tabela users
echo "\n=== ESTRUTURA DA TABELA USERS ===\n";
try {
    $columns = DB::select('DESCRIBE users');
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
} catch (Exception $e) {
    echo "Erro ao verificar estrutura: {$e->getMessage()}\n";
}