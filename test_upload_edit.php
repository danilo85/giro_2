<?php

// Criar um arquivo de imagem fake
$imageContent = str_repeat('fake image data ', 100);
file_put_contents('test_image.txt', $imageContent);
echo "Arquivo criado com " . filesize('test_image.txt') . " bytes\n";

// Obter CSRF token da página de edição
echo "Obtendo CSRF token...\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/portfolio/works/1/edit');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
$editPage = curl_exec($ch);
curl_close($ch);

// Extrair CSRF token
preg_match('/name="_token" value="([^"]+)"/', $editPage, $matches);
$csrfToken = $matches[1] ?? 'no-token';
echo "CSRF Token: $csrfToken\n";

// Dados do formulário
$postData = [
    '_token' => $csrfToken,
    '_method' => 'PUT',
    'title' => 'Teste Upload Edit',
    'slug' => 'teste-upload-edit',
    'description' => 'Descrição de teste para upload',
    'client_id' => '1',
    'status' => 'draft'
];

// Dados da imagem
$imageData = [
    'images' => [new CURLFile('test_image.txt', 'image/jpeg', 'test_image.jpg')]
];

// Mesclar dados
$allData = array_merge($postData, $imageData);

// Configurar cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/portfolio/works/1');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $allData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
    'Referer: http://localhost:8000/portfolio/works/1/edit'
]);

// Executar requisição
echo "Enviando requisição PUT para http://localhost:8000/portfolio/works/1\n";
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "Código HTTP: $httpCode\n";

if ($response === false) {
    echo "Erro cURL: " . curl_error($ch) . "\n";
} else {
    echo "Resposta recebida (primeiros 500 caracteres):\n";
    echo substr($response, 0, 500) . "\n";
    
    // Verificar se há mensagens de sucesso ou erro
    if (strpos($response, 'sucesso') !== false || strpos($response, 'success') !== false) {
        echo "✓ Possível sucesso detectado\n";
    }
    if (strpos($response, 'erro') !== false || strpos($response, 'error') !== false) {
        echo "✗ Possível erro detectado\n";
    }
}

curl_close($ch);

// Limpar arquivos temporários
unlink('test_image.txt');
if (file_exists('cookies.txt')) {
    unlink('cookies.txt');
}

echo "Teste concluído.\n";}]}

?>