<?php

// Teste simples dos endpoints AJAX
echo "Testando endpoints AJAX...\n\n";

// Configurações
$base_url = 'http://localhost:8000';
$cliente_id = 1;

// Headers para simular uma requisição AJAX
$headers = [
    'X-Requested-With: XMLHttpRequest',
    'Content-Type: application/json',
    'Accept: application/json'
];

// Função para fazer requisições
function makeRequest($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    
    curl_close($ch);
    
    return [
        'response' => $response,
        'http_code' => $httpCode,
        'error' => $error
    ];
}

// Teste 1: Check Extract Status
echo "1. Testando checkExtractStatus...\n";
$url1 = "$base_url/clientes/$cliente_id/check-extract-status";
$result1 = makeRequest($url1, 'GET', null, $headers);

echo "   URL: $url1\n";
echo "   Status HTTP: {$result1['http_code']}\n";
echo "   Resposta: {$result1['response']}\n";
echo "   Erro cURL: {$result1['error']}\n\n";

// Teste 2: Gerar Token Extrato
echo "2. Testando gerarTokenExtrato...\n";
$url2 = "$base_url/clientes/$cliente_id/gerar-token-extrato";
$result2 = makeRequest($url2, 'POST', null, $headers);

echo "   URL: $url2\n";
echo "   Status HTTP: {$result2['http_code']}\n";
echo "   Resposta: {$result2['response']}\n";
echo "   Erro cURL: {$result2['error']}\n\n";

// Teste 3: Desativar Token Extrato
echo "3. Testando desativarTokenExtrato...\n";
$url3 = "$base_url/clientes/$cliente_id/desativar-token-extrato";
$result3 = makeRequest($url3, 'POST', null, $headers);

echo "   URL: $url3\n";
echo "   Status HTTP: {$result3['http_code']}\n";
echo "   Resposta: {$result3['response']}\n";
echo "   Erro cURL: {$result3['error']}\n\n";

echo "Teste concluído!\n";