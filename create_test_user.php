<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::first();

if (!$user) {
    $user = App\Models\User::create([
        'name' => 'Teste',
        'email' => 'teste@teste.com',
        'password' => bcrypt('123456')
    ]);
    echo "User created: {$user->email}\n";
} else {
    echo "User found: {$user->email}\n";
}

// Criar um modelo de proposta para teste
$modelo = App\Models\ModeloProposta::where('user_id', $user->id)->first();

if (!$modelo) {
    $modelo = App\Models\ModeloProposta::create([
        'user_id' => $user->id,
        'nome' => 'Modelo de Teste',
        'conteudo' => 'Este é um modelo de proposta de teste.',
        'ativo' => true
    ]);
    echo "Modelo created: {$modelo->nome}\n";
} else {
    echo "Modelo found: {$modelo->nome}\n";
}

echo "User ID: {$user->id}\n";
echo "Modelo ID: {$modelo->id}\n";