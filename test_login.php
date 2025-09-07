<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== TESTE DE LOGIN ===\n\n";

// Verificar usuários no banco
$users = User::all(['id', 'name', 'email', 'is_active']);
echo "Usuários encontrados: " . $users->count() . "\n\n";

foreach ($users as $user) {
    echo "ID: {$user->id} | Nome: {$user->name} | Email: {$user->email} | Ativo: " . ($user->is_active ? 'Sim' : 'Não') . "\n";
}

echo "\n=== TESTE DE SENHAS ===\n\n";

// Testar senhas dos usuários principais
$testCredentials = [
    ['email' => 'admin@giro.com', 'password' => 'admin123'],
    ['email' => 'user@giro.com', 'password' => 'user123'],
];

foreach ($testCredentials as $cred) {
    $user = User::where('email', $cred['email'])->first();
    if ($user) {
        $passwordMatch = Hash::check($cred['password'], $user->password);
        echo "Email: {$cred['email']} | Senha: {$cred['password']} | Match: " . ($passwordMatch ? 'SIM' : 'NÃO') . " | Ativo: " . ($user->is_active ? 'SIM' : 'NÃO') . "\n";
    } else {
        echo "Email: {$cred['email']} | Usuário não encontrado\n";
    }
}

echo "\n=== FIM DO TESTE ===\n";