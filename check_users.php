<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

try {
    $userCount = User::count();
    echo "Total de usuários no sistema: $userCount\n";
    
    if ($userCount > 0) {
        echo "\nPrimeiros 3 usuários:\n";
        $users = User::take(3)->get(['id', 'name', 'email']);
        
        foreach ($users as $user) {
            echo "ID: {$user->id}, Nome: {$user->name}, Email: {$user->email}\n";
        }
    } else {
        echo "\nNenhum usuário encontrado. Criando usuário de teste...\n";
        
        $user = User::create([
            'name' => 'Usuário Teste',
            'email' => 'teste@exemplo.com',
            'password' => bcrypt('123456'),
            'email_verified_at' => now()
        ]);
        
        echo "Usuário criado: {$user->email} (senha: 123456)\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}