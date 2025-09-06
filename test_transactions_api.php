<?php

require_once 'vendor/autoload.php';

// Configurar o ambiente Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simular uma requisição HTTP
$request = Illuminate\Http\Request::create('/');
$response = $kernel->handle($request);

echo "=== Teste das APIs de Transações ===\n\n";

// Testar autenticação com usuário admin
try {
    // Buscar usuário admin
    $user = App\Models\User::where('email', 'admin@giro.com')->first();
    
    if (!$user) {
        echo "❌ Usuário admin não encontrado\n";
        exit(1);
    }
    
    echo "✅ Usuário admin encontrado: {$user->email}\n";
    echo "   ID: {$user->id}\n";
    echo "   Nome: {$user->name}\n";
    echo "   Admin: " . ($user->is_admin ? 'Sim' : 'Não') . "\n\n";
    
    // Autenticar o usuário
    Auth::login($user);
    echo "✅ Usuário autenticado com sucesso\n\n";
    
    // Testar API de cartões de crédito
    echo "=== Testando API de Cartões de Crédito ===\n";
    
    $creditCardController = new App\Http\Controllers\CreditCardController();
    
    // Criar uma requisição simulada
    $apiRequest = Illuminate\Http\Request::create('/api/financial/credit-cards', 'GET');
    $apiRequest->setUserResolver(function () use ($user) {
        return $user;
    });
    
    try {
        $response = $creditCardController->apiIndex($apiRequest);
        $data = $response->getData(true);
        
        echo "✅ API de cartões de crédito funcionando\n";
        echo "   Status: {$response->getStatusCode()}\n";
        echo "   Cartões encontrados: " . count($data) . "\n\n";
        
        if (count($data) > 0) {
            echo "   Primeiro cartão: " . json_encode($data[0], JSON_PRETTY_PRINT) . "\n\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erro na API de cartões de crédito: {$e->getMessage()}\n";
        echo "   Arquivo: {$e->getFile()}:{$e->getLine()}\n\n";
    }
    
    // Testar API de transações
    echo "=== Testando API de Transações ===\n";
    
    $transactionController = new App\Http\Controllers\TransactionController();
    
    // Criar uma requisição simulada com parâmetros
    $apiRequest = Illuminate\Http\Request::create('/api/financial/transactions', 'GET', [
        'mes' => 9,
        'ano' => 2025
    ]);
    $apiRequest->setUserResolver(function () use ($user) {
        return $user;
    });
    
    try {
        $response = $transactionController->apiIndex($apiRequest);
        $data = $response->getData(true);
        
        echo "✅ API de transações funcionando\n";
        echo "   Status: {$response->getStatusCode()}\n";
        echo "   Transações encontradas: " . count($data) . "\n\n";
        
        if (count($data) > 0) {
            echo "   Primeira transação: " . json_encode($data[0], JSON_PRETTY_PRINT) . "\n\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erro na API de transações: {$e->getMessage()}\n";
        echo "   Arquivo: {$e->getFile()}:{$e->getLine()}\n\n";
    }
    
    // Testar API de bancos
    echo "=== Testando API de Bancos ===\n";
    
    $bankController = new App\Http\Controllers\BankController();
    
    $apiRequest = Illuminate\Http\Request::create('/api/financial/banks', 'GET');
    $apiRequest->setUserResolver(function () use ($user) {
        return $user;
    });
    
    try {
        $response = $bankController->apiIndex($apiRequest);
        $data = $response->getData(true);
        
        echo "✅ API de bancos funcionando\n";
        echo "   Status: {$response->getStatusCode()}\n";
        echo "   Bancos encontrados: " . count($data) . "\n\n";
        
        if (count($data) > 0) {
            echo "   Primeiro banco: " . json_encode($data[0], JSON_PRETTY_PRINT) . "\n\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erro na API de bancos: {$e->getMessage()}\n";
        echo "   Arquivo: {$e->getFile()}:{$e->getLine()}\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro geral: {$e->getMessage()}\n";
    echo "   Arquivo: {$e->getFile()}:{$e->getLine()}\n";
}

echo "\n=== Teste concluído ===\n";