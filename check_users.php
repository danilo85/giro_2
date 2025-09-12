<?php

require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

try {
    $user = User::first();
    if ($user) {
        echo "User ID: {$user->id}\n";
        echo "Name: {$user->name}\n";
        echo "Email: {$user->email}\n";
    } else {
        echo "No users found\n";
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}