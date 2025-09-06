<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Buscar uma transação recorrente
$transaction = App\Models\Transaction::where('is_recurring', true)->first();

if ($transaction) {
    echo "Transação ID: " . $transaction->id . "\n";
    echo "is_recurring: " . ($transaction->is_recurring ? 'true' : 'false') . "\n";
    echo "Type: " . gettype($transaction->is_recurring) . "\n";
    echo "Raw value: " . var_export($transaction->is_recurring, true) . "\n";
} else {
    echo "Nenhuma transação recorrente encontrada\n";
    
    // Vamos buscar qualquer transação e verificar o campo
    $anyTransaction = App\Models\Transaction::first();
    if ($anyTransaction) {
        echo "Primeira transação ID: " . $anyTransaction->id . "\n";
        echo "is_recurring: " . ($anyTransaction->is_recurring ? 'true' : 'false') . "\n";
        echo "Type: " . gettype($anyTransaction->is_recurring) . "\n";
        echo "Raw value: " . var_export($anyTransaction->is_recurring, true) . "\n";
    }
}