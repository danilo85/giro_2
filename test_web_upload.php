<?php

echo "=== TESTE DE UPLOAD VIA WEB ===\n\n";

// Criar arquivo de teste simples (JPEG mínimo válido)
$testImagePath = __DIR__ . '/test_upload_image.jpg';
if (!file_exists($testImagePath)) {
    // JPEG mínimo válido (1x1 pixel)
    $jpegData = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/wA==');
    file_put_contents($testImagePath, $jpegData);
    echo "✅ Imagem de teste criada: $testImagePath (" . filesize($testImagePath) . " bytes)\n";
}

// Dados do formulário
$postData = [
    'title' => 'Teste Upload Web ' . date('H:i:s'),
    'slug' => 'teste-upload-web-' . time(),
    'description' => 'Teste de upload via requisição HTTP',
    'portfolio_category_id' => '1',
    'status' => 'draft',
    'is_featured' => '0',
    '_token' => '' // Será obtido da página
];

echo "📋 Dados do formulário preparados\n";

// Primeiro, obter o token CSRF
echo "🔑 Obtendo token CSRF...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/portfolio/works/create');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . '/cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt');
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo "❌ Erro ao acessar página de criação: HTTP $httpCode\n";
    exit;
}

// Extrair token CSRF
preg_match('/<meta name="csrf-token" content="([^"]+)"/', $response, $matches);
if (!isset($matches[1])) {
    preg_match('/<input[^>]*name="_token"[^>]*value="([^"]+)"/', $response, $matches);
}

if (!isset($matches[1])) {
    echo "❌ Token CSRF não encontrado\n";
    exit;
}

$csrfToken = $matches[1];
$postData['_token'] = $csrfToken;
echo "✅ Token CSRF obtido: " . substr($csrfToken, 0, 10) . "...\n";

// Preparar dados para upload
echo "📤 Preparando upload...\n";

$boundary = '----WebKitFormBoundary' . uniqid();
$postFields = '';

// Adicionar campos de texto
foreach ($postData as $key => $value) {
    $postFields .= "--$boundary\r\n";
    $postFields .= "Content-Disposition: form-data; name=\"$key\"\r\n\r\n";
    $postFields .= "$value\r\n";
}

// Adicionar arquivo de imagem
$postFields .= "--$boundary\r\n";
$postFields .= "Content-Disposition: form-data; name=\"images[]\"; filename=\"test_upload.jpg\"\r\n";
$postFields .= "Content-Type: image/jpeg\r\n\r\n";
$postFields .= file_get_contents($testImagePath) . "\r\n";
$postFields .= "--$boundary--\r\n";

echo "📋 Dados preparados (" . strlen($postFields) . " bytes)\n";

// Fazer upload
echo "🚀 Enviando requisição...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8000/portfolio/works');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . '/cookies.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: multipart/form-data; boundary=' . $boundary,
    'X-CSRF-TOKEN: ' . $csrfToken
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$redirectUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
curl_close($ch);

echo "📊 Resposta recebida:\n";
echo "   - Código HTTP: $httpCode\n";
echo "   - URL final: $redirectUrl\n";

if ($httpCode >= 200 && $httpCode < 400) {
    echo "✅ Upload realizado com sucesso!\n";
    
    // Verificar se foi redirecionado para a lista
    if (strpos($redirectUrl, '/portfolio/works') !== false) {
        echo "✅ Redirecionado para lista de trabalhos\n";
    }
    
    // Verificar mensagem de sucesso na resposta
    if (strpos($response, 'sucesso') !== false || strpos($response, 'success') !== false) {
        echo "✅ Mensagem de sucesso encontrada na resposta\n";
    }
} else {
    echo "❌ Erro no upload: HTTP $httpCode\n";
    echo "📄 Resposta (primeiros 500 chars):\n";
    echo substr($response, 0, 500) . "...\n";
}

// Limpar arquivos temporários
if (file_exists(__DIR__ . '/cookies.txt')) {
    unlink(__DIR__ . '/cookies.txt');
}
if (file_exists($testImagePath)) {
    unlink($testImagePath);
}

echo "\n=== TESTE FINALIZADO ===\n";