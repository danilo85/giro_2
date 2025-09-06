<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Transaction;

echo "Buscando transações parceladas (sistema novo):\n";

// Buscar transações com installment_id (sistema novo)
$installmentTransactions = Transaction::whereNotNull('installment_id')->take(5)->get();
echo "\nTransações com installment_id encontradas: " . $installmentTransactions->count() . "\n";

foreach ($installmentTransactions as $transaction) {
    echo "ID: {$transaction->id}, Installment ID: {$transaction->installment_id}, User ID: {$transaction->user_id}\n";
    echo "Descrição: {$transaction->descricao}\n";
    echo "Parcela: {$transaction->installment_number}/{$transaction->installment_count}\n";
    echo "---\n";
}

if ($installmentTransactions->count() == 0) {
    echo "\nNenhuma transação parcelada encontrada. Vou criar uma para teste:\n";
    
    $installmentId = uniqid();
    
    // Criar 3 parcelas de teste
    for ($i = 1; $i <= 3; $i++) {
        $transaction = new Transaction();
        $transaction->user_id = 1;
        $transaction->category_id = 1;
        $transaction->descricao = "Compra Parcelada Teste";
        $transaction->valor = 100.00;
        $transaction->tipo = 'despesa';
        $transaction->data = now()->addMonths($i-1);
        $transaction->status = 'pendente';
        $transaction->installment_id = $installmentId;
        $transaction->installment_count = 3;
        $transaction->installment_number = $i;
        $transaction->save();
        
        echo "Criada parcela {$i}/3 - ID: {$transaction->id}\n";
    }
    
    echo "\nTransações de teste criadas com installment_id: {$installmentId}\n";
    echo "Use o ID da primeira parcela para testar: " . Transaction::where('installment_id', $installmentId)->first()->id . "\n";
}