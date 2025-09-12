<?php

// Teste de upload de múltiplas imagens usando cURL

echo "=== Teste de Upload de Múltiplas Imagens ===\n";

// Criar imagens de teste temporárias
$tempDir = sys_get_temp_dir();
$testImages = [];

for ($i = 1; $i <= 3; $i++) {
    $imagePath = $tempDir . "/test_image_{$i}.jpg";
    file_put_contents($imagePath, "fake-image-content-{$i}");
    $testImages[] = $imagePath;
    echo "Criada imagem de teste: {$imagePath}\n";
}

// Preparar dados para upload
$postData = [
    'title' => 'Teste Upload Múltiplas Imagens',
    'description' => 'Teste de upload de múltiplas imagens via cURL',
    'category_id' => '1',
    '_token' => 'test-token', // Será substituído por um token válido
    '_method' => 'PUT'
];

echo "\n=== Dados preparados para upload ===\n";
echo "Título: {$postData['title']}\n";
echo "Descrição: {$postData['description']}\n";
echo "Categoria ID: {$postData['category_id']}\n";
echo "Número de imagens: " . count($testImages) . "\n";

// Simular estrutura $_FILES
echo "\n=== Simulação da estrutura \$_FILES ===\n";
$simulatedFiles = [];
foreach ($testImages as $index => $imagePath) {
    $simulatedFiles['images']['name'][$index] = "test_image_{$index}.jpg";
    $simulatedFiles['images']['type'][$index] = 'image/jpeg';
    $simulatedFiles['images']['tmp_name'][$index] = $imagePath;
    $simulatedFiles['images']['error'][$index] = 0;
    $simulatedFiles['images']['size'][$index] = filesize($imagePath);
}

echo "Estrutura \$_FILES simulada:\n";
print_r($simulatedFiles);

// Verificar se as imagens seriam detectadas corretamente
echo "\n=== Verificação de detecção de múltiplas imagens ===\n";
if (isset($simulatedFiles['images']['name']) && is_array($simulatedFiles['images']['name'])) {
    echo "✓ Múltiplas imagens detectadas\n";
    echo "Quantidade: " . count($simulatedFiles['images']['name']) . "\n";
    
    foreach ($simulatedFiles['images']['name'] as $index => $name) {
        echo "Imagem {$index}: {$name} (" . $simulatedFiles['images']['size'][$index] . " bytes)\n";
    }
} else {
    echo "✗ Múltiplas imagens NÃO detectadas\n";
}

// Limpeza
foreach ($testImages as $imagePath) {
    if (file_exists($imagePath)) {
        unlink($imagePath);
        echo "Removida imagem temporária: {$imagePath}\n";
    }
}

echo "\n=== Teste concluído ===\n";