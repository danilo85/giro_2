<?php

// Teste simples usando file_get_contents
echo "Testando endpoints com file_get_contents...\n\n";

$base_url = 'http://localhost:8000';
$cliente_id = 1;

// Configurar contexto para requisições HTTP
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => [
            'X-Requested-With: XMLHttpRequest',
            'Accept: application/json',
            'User-Agent: Mozilla/5.0 (Test)'
        ],
        'timeout' => 10
    ]
]);

// Teste 1: Check Extract Status
echo "1. Testando checkExtractStatus...\n";
$url1 = "$base_url/clientes/$cliente_id/check-extract-status";

try {
    $response1 = file_get_contents($url1, false, $context);
    echo "   URL: $url1\n";
    echo "   Sucesso: SIM\n";
    echo "   Resposta: $response1\n";
} catch (Exception $e) {
    echo "   URL: $url1\n";
    echo "   Sucesso: NÃO\n";
    echo "   Erro: " . $e->getMessage() . "\n";
    
    // Verificar headers de resposta
    if (isset($http_response_header)) {
        echo "   Headers: " . implode(', ', $http_response_header) . "\n";
    }
}

echo "\n";

// Teste 2: Verificar se a página principal carrega
echo "2. Testando página principal...\n";
$url2 = "$base_url";

try {
    $response2 = file_get_contents($url2, false, $context);
    echo "   URL: $url2\n";
    echo "   Sucesso: SIM\n";
    echo "   Tamanho da resposta: " . strlen($response2) . " bytes\n";
} catch (Exception $e) {
    echo "   URL: $url2\n";
    echo "   Sucesso: NÃO\n";
    echo "   Erro: " . $e->getMessage() . "\n";
}

echo "\nTeste concluído!\n";