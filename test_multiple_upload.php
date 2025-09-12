<?php

// Teste simples para verificar se múltiplas imagens são processadas
echo "=== Simulação de Upload de Múltiplas Imagens ===\n";

// Simular estrutura $_FILES com múltiplas imagens
$_FILES = [
    'images' => [
        'name' => ['image1.jpg', 'image2.jpg', 'image3.jpg'],
        'type' => ['image/jpeg', 'image/jpeg', 'image/jpeg'],
        'tmp_name' => ['/tmp/image1', '/tmp/image2', '/tmp/image3'],
        'error' => [0, 0, 0],
        'size' => [1024, 2048, 1536]
    ]
];

echo "Estrutura \$_FILES simulada:\n";
print_r($_FILES);

// Verificar se é um array de múltiplos arquivos
if (isset($_FILES['images']['name']) && is_array($_FILES['images']['name'])) {
    $fileCount = count($_FILES['images']['name']);
    echo "\nDetectado upload de múltiplas imagens: {$fileCount} arquivos\n";
    
    for ($i = 0; $i < $fileCount; $i++) {
        echo "Arquivo {$i}: {$_FILES['images']['name'][$i]} ({$_FILES['images']['size'][$i]} bytes)\n";
    }
} else {
    echo "\nApenas um arquivo detectado\n";
}

echo "\n=== Teste de Processamento no Laravel ===\n";

// Simular como o Laravel processa
class FakeRequest {
    public function file($key) {
        if (!isset($_FILES[$key])) return null;
        
        $files = [];
        if (is_array($_FILES[$key]['name'])) {
            $fileCount = count($_FILES[$key]['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                $files[] = (object) [
                    'name' => $_FILES[$key]['name'][$i],
                    'size' => $_FILES[$key]['size'][$i],
                    'type' => $_FILES[$key]['type'][$i]
                ];
            }
        }
        return $files;
    }
    
    public function hasFile($key) {
        return isset($_FILES[$key]) && !empty($_FILES[$key]['name']);
    }
}

$request = new FakeRequest();

if ($request->hasFile('images')) {
    $images = $request->file('images');
    echo "Request->file('images') retornou: " . count($images) . " arquivos\n";
    
    foreach ($images as $index => $image) {
        echo "Processando imagem {$index}: {$image->name}\n";
    }
} else {
    echo "Nenhum arquivo encontrado no request\n";
}

echo "\n=== Teste concluído ===\n";