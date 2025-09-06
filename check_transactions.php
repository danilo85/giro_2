<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Transaction;

echo "Usuários: " . User::count() . "\n";
echo "Transações: " . Transaction::count() . "\n\n";

foreach (User::all() as $user) {
    echo "Usuário: {$user->name} ({$user->email}) - Transações: " . $user->transactions()->count() . "\n";
}

echo "\nPrimeiras 3 transações:\n";
foreach (Transaction::with('user')->take(3)->get() as $transaction) {
    echo "ID: {$transaction->id} - Usuário: {$transaction->user->name} - Descrição: {$transaction->descricao} - Valor: R$ {$transaction->valor}\n";
}