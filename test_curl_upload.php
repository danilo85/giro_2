<?php

// Primeiro, obter o token CSRF
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/debug/csrf');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
$csrfResponse = curl_exec($ch);
curl_close($ch);

$csrfData = json_decode($csrfResponse, true);
$csrfToken = $csrfData['csrf_token'] ?? null;

echo "Token CSRF obtido: $csrfToken\n";

// Criar um arquivo temporário simulando uma imagem
$tempFile = tempnam(sys_get_temp_dir(), 'test_image');
file_put_contents($tempFile, 'FAKE_JPEG_CONTENT_FOR_TESTING');

echo "Arquivo temporário criado: $tempFile\n";

// Configurar cURL com token CSRF
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/debug-form');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    '_token' => $csrfToken,
    'images[]' => new CURLFile($tempFile, 'image/jpeg', 'test.jpg')
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Test-Upload-Script/1.0');
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');

// Executar requisição
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Remover arquivo temporário e cookies
unlink($tempFile);
if (file_exists('cookies.txt')) {
    unlink('cookies.txt');
}
echo "Arquivo temporário removido.\n";

// Mostrar resultado
if ($error) {
    echo "Erro cURL: $error\n";
} else {
    echo "Código HTTP: $httpCode\n";
    echo "Resposta: $response\n";
}

if ($httpCode !== 200) {
    echo "Erro HTTP: $httpCode\n";
}

echo "\n=== INSTRUÇÕES ===\n";
echo "1. Verifique os logs do Laravel em storage/logs/laravel.log\n";
echo "2. Procure por '=== DEBUG FORM SUBMISSION ===' nos logs\n";
echo "3. Verifique se as imagens foram detectadas corretamente\n";