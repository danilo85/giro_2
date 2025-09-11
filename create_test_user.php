<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    // Verificar se já existe um usuário admin
    $existingUser = User::where('email', 'admin@test.com')->first();
    
    if ($existingUser) {
        echo "Usuário admin já existe: {$existingUser->email}\n";
    } else {
        // Criar usuário de teste
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('123456'),
            'email_verified_at' => now()
        ]);
        
        echo "Usuário criado com sucesso!\n";
        echo "Email: {$user->email}\n";
        echo "Senha: 123456\n";
    }
    
    echo "Total de usuários no banco: " . User::count() . "\n";
    
} catch (Exception $e) {
    echo "Erro ao criar usuário: " . $e->getMessage() . "\n";
}