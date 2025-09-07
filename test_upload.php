<?php

require_once 'vendor/autoload.php';

use App\Utils\MimeTypeDetector;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

// Simular um arquivo PDF para teste
echo "=== TESTE DE DETECÇÃO DE MIME TYPE ===\n\n";

// Teste 1: Verificar se a classe existe e funciona
echo "1. Testando classe MimeTypeDetector...\n";
if (class_exists('App\\Utils\\MimeTypeDetector')) {
    echo "✓ Classe MimeTypeDetector encontrada\n";
} else {
    echo "✗ Classe MimeTypeDetector NÃO encontrada\n";
    exit(1);
}

// Teste 2: Testar detecção por extensão
echo "\n2. Testando detecção por extensão...\n";
$testFiles = [
    'documento.pdf',
    'imagem.jpg',
    'planilha.xlsx',
    'texto.txt'
];

foreach ($testFiles as $fileName) {
    // Simular detecção por extensão
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    echo "Arquivo: {$fileName} -> Extensão: {$extension}\n";
}

// Teste 3: Verificar se composer.json existe para teste real
echo "\n3. Testando arquivo real (composer.json)...\n";
if (file_exists('composer.json')) {
    echo "✓ Arquivo composer.json encontrado\n";
    
    // Testar métodos de detecção individuais
    echo "\nTestando métodos de detecção:\n";
    
    // Método finfo
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo) {
            $mimeType = finfo_file($finfo, 'composer.json');
            echo "- finfo_file: {$mimeType}\n";
            finfo_close($finfo);
        }
    }
    
    // Método mime_content_type
    if (function_exists('mime_content_type')) {
        $mimeType = mime_content_type('composer.json');
        echo "- mime_content_type: {$mimeType}\n";
    }
    
    // Método por extensão
    $extension = strtolower(pathinfo('composer.json', PATHINFO_EXTENSION));
    echo "- Por extensão (.json): application/json\n";
    
} else {
    echo "✗ Arquivo composer.json NÃO encontrado\n";
}

// Teste 4: Verificar assinaturas de arquivo
echo "\n4. Testando detecção por assinatura...\n";
if (file_exists('composer.json')) {
    $handle = fopen('composer.json', 'rb');
    if ($handle) {
        $bytes = fread($handle, 8);
        fclose($handle);
        
        echo "Primeiros 8 bytes: ";
        for ($i = 0; $i < strlen($bytes) && $i < 8; $i++) {
            echo sprintf('%02X ', ord($bytes[$i]));
        }
        echo "\n";
        
        // Verificar se começa com '{' (JSON)
        if (substr($bytes, 0, 1) === '{') {
            echo "✓ Arquivo parece ser JSON (começa com '{')\n";
        }
    }
}

echo "\n=== TESTE CONCLUÍDO ===\n";
echo "\nPróximos passos:\n";
echo "1. Verificar logs do Laravel em storage/logs/laravel.log\n";
echo "2. Testar upload real através da interface web\n";
echo "3. Monitorar logs durante o upload\n";