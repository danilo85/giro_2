<?php

require_once __DIR__ . '/vendor/autoload.php';

// Simular dados do formulário
$formData = [
    'title' => 'Teste de Trabalho',
    'description' => 'Descrição de teste',
    'content' => 'Conteúdo completo do teste',
    'portfolio_category_id' => 1,
    'client_id' => 1, // Verificar se este campo está sendo enviado
    'project_url' => 'https://exemplo.com',
    'completion_date' => '2024-01-15',
    'is_published' => 1,
    'is_featured' => 0,
    'authors' => [1],
    'author_roles' => ['Desenvolvedor']
];

echo "=== TESTE DE ENVIO DO FORMULÁRIO ===\n";
echo "Dados que seriam enviados:\n";
print_r($formData);

echo "\n=== VERIFICANDO CAMPO CLIENT_ID ===\n";
if (isset($formData['client_id']) && !empty($formData['client_id'])) {
    echo "✓ Campo client_id está presente: " . $formData['client_id'] . "\n";
} else {
    echo "✗ Campo client_id está ausente ou vazio\n";
}

echo "\n=== SIMULANDO UPLOAD DE IMAGENS ===\n";
echo "Simulando upload de 2 imagens...\n";

// Simular dados de imagem
$imageData = [
    [
        'name' => 'test1.jpg',
        'type' => 'image/jpeg',
        'size' => 1024000,
        'tmp_name' => '/tmp/test1.jpg'
    ],
    [
        'name' => 'test2.png',
        'type' => 'image/png', 
        'size' => 2048000,
        'tmp_name' => '/tmp/test2.png'
    ]
];

foreach ($imageData as $index => $image) {
    echo "Imagem " . ($index + 1) . ":\n";
    echo "  - Nome: " . $image['name'] . "\n";
    echo "  - Tipo: " . $image['type'] . "\n";
    echo "  - Tamanho: " . number_format($image['size'] / 1024, 2) . " KB\n";
}

echo "\n=== TESTE CONCLUÍDO ===\n";
echo "Próximos passos:\n";
echo "1. Verificar logs do Laravel após submissão real\n";
echo "2. Confirmar se client_id chega ao controller\n";
echo "3. Verificar se uploadImages() é chamado\n";
echo "4. Confirmar salvamento no banco de dados\n";