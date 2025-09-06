<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\BankController;

echo "=== Teste de Login e APIs ===\n";

// Encontrar o usuário admin
$user = User::where('email', 'admin@giro.com')->first();

if (!$user) {
    echo "Usuário admin não encontrado!\n";
    exit(1);
}

echo "Usuário encontrado: {$user->name} ({$user->email})\n";

// Fazer login do usuário
Auth::login($user);
echo "Usuário logado com sucesso!\n";

// Verificar se está autenticado
if (Auth::check()) {
    echo "Usuário está autenticado: " . Auth::user()->name . "\n";
} else {
    echo "Falha na autenticação!\n";
    exit(1);
}

// Criar uma requisição simulada
$request = Request::create('/api/financial/transactions', 'GET', [
    'mes' => date('n'),
    'ano' => date('Y')
]);

// Definir o usuário autenticado na requisição
$request->setUserResolver(function () use ($user) {
    return $user;
});

echo "\n=== Testando API de Transações ===\n";
try {
    $controller = new TransactionController();
    $response = $controller->apiIndex($request);
    
    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getContent(), true);
        echo "✓ API de Transações funcionando - Status: 200\n";
        echo "Dados retornados: " . count($data['data'] ?? []) . " transações\n";
        
        if (isset($data['summary'])) {
            echo "Resumo disponível: Receitas: R$ " . number_format($data['summary']['total_income'] ?? 0, 2, ',', '.') . "\n";
        }
    } else {
        echo "✗ API de Transações falhou - Status: " . $response->getStatusCode() . "\n";
        echo "Resposta: " . $response->getContent() . "\n";
    }
} catch (Exception $e) {
    echo "✗ Erro na API de Transações: " . $e->getMessage() . "\n";
    echo "Linha: " . $e->getLine() . " Arquivo: " . $e->getFile() . "\n";
}

echo "\n=== Testando API de Cartões de Crédito ===\n";
try {
    $controller = new CreditCardController();
    $response = $controller->apiIndex($request);
    
    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getContent(), true);
        echo "✓ API de Cartões funcionando - Status: 200\n";
        echo "Cartões encontrados: " . count($data) . "\n";
    } else {
        echo "✗ API de Cartões falhou - Status: " . $response->getStatusCode() . "\n";
    }
} catch (Exception $e) {
    echo "✗ Erro na API de Cartões: " . $e->getMessage() . "\n";
}

echo "\n=== Testando API de Bancos ===\n";
try {
    $controller = new BankController();
    $response = $controller->apiIndex($request);
    
    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getContent(), true);
        echo "✓ API de Bancos funcionando - Status: 200\n";
        echo "Bancos encontrados: " . count($data) . "\n";
    } else {
        echo "✗ API de Bancos falhou - Status: " . $response->getStatusCode() . "\n";
    }
} catch (Exception $e) {
    echo "✗ Erro na API de Bancos: " . $e->getMessage() . "\n";
}

echo "\n=== Teste concluído ===\n";