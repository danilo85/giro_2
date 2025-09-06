<?php

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Transaction;

echo "=== TESTE DE DADOS ===\n";
echo "Total de usuários: " . User::count() . "\n";
echo "Total de transações: " . Transaction::count() . "\n\n";

echo "=== TRANSAÇÕES POR USUÁRIO ===\n";
foreach(User::all() as $user) {
    $count = Transaction::where('user_id', $user->id)->count();
    echo "{$user->email}: {$count} transações\n";
}

echo "\n=== PRIMEIRA TRANSAÇÃO ===\n";
$firstTransaction = Transaction::with(['category', 'bank', 'creditCard'])->first();
if($firstTransaction) {
    echo "ID: {$firstTransaction->id}\n";
    echo "Usuário: {$firstTransaction->user_id}\n";
    echo "Descrição: {$firstTransaction->descricao}\n";
    echo "Valor: R$ {$firstTransaction->valor}\n";
    echo "Tipo: {$firstTransaction->tipo}\n";
    echo "Status: {$firstTransaction->status}\n";
    echo "Data: {$firstTransaction->data}\n";
    echo "Categoria: " . ($firstTransaction->category ? $firstTransaction->category->nome : 'N/A') . "\n";
}

echo "\n=== TESTE DO CONTROLLER ===\n";
try {
    $user = User::first();
    $request = new Illuminate\Http\Request();
    $request->merge([
        'mes' => date('n'),
        'ano' => date('Y')
    ]);
    
    // Simular autenticação
    auth()->login($user);
    
    $controller = new App\Http\Controllers\Financial\TransactionController();
    $response = $controller->apiIndex($request);
    
    echo "Status da resposta: " . $response->getStatusCode() . "\n";
    $data = $response->getData(true);
    echo "Dados retornados: " . (isset($data['data']) ? count($data['data']) : 0) . " transações\n";
    
    if(isset($data['data']) && count($data['data']) > 0) {
        echo "Primeira transação da API: {$data['data'][0]['descricao']}\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
}