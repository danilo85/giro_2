<?php

// Script para testar os endpoints AJAX do extrato

// Simular uma requisição AJAX para gerar token
$baseUrl = 'http://127.0.0.1:8000';
$clienteId = 1; // ID de teste

// Headers para simular requisição AJAX
$headers = [
    'Content-Type: application/json',
    'X-Requested-With: XMLHttpRequest',
    'Accept: application/json'
];

echo "Testando endpoints do extrato...\n\n";

// Teste 1: Verificar status do extrato
echo "1. Testando verificação de status do extrato:\n";
$url = "$baseUrl/clientes/$clienteId/check-extract-status";
echo "URL: $url\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
if ($error) {
    echo "Erro cURL: $error\n";
}
echo "Resposta: $response\n\n";

// Teste 2: Gerar token do extrato
echo "2. Testando geração de token do extrato:\n";
$url = "$baseUrl/clientes/$clienteId/gerar-token-extrato";
echo "URL: $url\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
if ($error) {
    echo "Erro cURL: $error\n";
}
echo "Resposta: $response\n\n";

// Teste 3: Desativar token do extrato
echo "3. Testando desativação de token do extrato:\n";
$url = "$baseUrl/clientes/$clienteId/desativar-token-extrato";
echo "URL: $url\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
if ($error) {
    echo "Erro cURL: $error\n";
}
echo "Resposta: $response\n\n";

echo "Teste concluído!\n";