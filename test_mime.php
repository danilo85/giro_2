<?php

// Teste de detecção de MIME type
echo "=== Teste de Detecção de MIME Type ===\n";

// Verificar se fileinfo está disponível
if (function_exists('finfo_open')) {
    echo "✓ finfo_open está disponível\n";
    
    // Tentar criar resource finfo
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if ($finfo) {
        echo "✓ finfo resource criado com sucesso\n";
        
        // Testar com um arquivo existente
        $testFile = __DIR__ . '/composer.json';
        if (file_exists($testFile)) {
            $mimeType = finfo_file($finfo, $testFile);
            echo "MIME type de composer.json: " . ($mimeType ?: 'FALHOU') . "\n";
        }
        
        finfo_close($finfo);
    } else {
        echo "✗ Falha ao criar finfo resource\n";
        echo "Erro: " . error_get_last()['message'] . "\n";
    }
} else {
    echo "✗ finfo_open não está disponível\n";
}

// Testar mime_content_type como alternativa
echo "\n=== Teste mime_content_type ===\n";
if (function_exists('mime_content_type')) {
    echo "✓ mime_content_type está disponível\n";
    $testFile = __DIR__ . '/composer.json';
    if (file_exists($testFile)) {
        $mimeType = mime_content_type($testFile);
        echo "MIME type de composer.json: " . ($mimeType ?: 'FALHOU') . "\n";
    }
} else {
    echo "✗ mime_content_type não está disponível\n";
}

// Mostrar configurações relevantes
echo "\n=== Configurações PHP ===\n";
echo "file_uploads: " . (ini_get('file_uploads') ? 'ON' : 'OFF') . "\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "\n";