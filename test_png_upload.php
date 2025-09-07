<?php

require_once 'vendor/autoload.php';

use App\Utils\MimeTypeDetector;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

// Simular o arquivo PNG mencionado no erro
echo "=== TESTE ESPECÍFICO PARA PNG ===\n\n";

// Teste 1: Verificar se nossa classe funciona sem finfo
echo "1. Testando MimeTypeDetector sem finfo...\n";

// Verificar se finfo está disponível
if (class_exists('finfo')) {
    echo "⚠️  Classe finfo está disponível (mas não deveria ser usada)\n";
} else {
    echo "✓ Classe finfo não está disponível (como esperado)\n";
}

if (function_exists('finfo_open')) {
    echo "⚠️  Função finfo_open está disponível (mas não deveria ser usada)\n";
} else {
    echo "✓ Função finfo_open não está disponível (como esperado)\n";
}

// Teste 2: Simular arquivo PNG
echo "\n2. Simulando arquivo PNG 'ChatGPT Image 4 de abr. de 2025, 09_13_32.png'...\n";

// Criar um arquivo PNG temporário para teste
$tempPngFile = tempnam(sys_get_temp_dir(), 'test_png_');

// Escrever assinatura PNG no arquivo temporário
$pngSignature = hex2bin('89504e470d0a1a0a'); // Assinatura PNG
$pngData = $pngSignature . str_repeat('\x00', 100); // Dados fictícios
file_put_contents($tempPngFile, $pngData);

echo "Arquivo PNG temporário criado: {$tempPngFile}\n";

// Teste 3: Testar detecção por extensão
echo "\n3. Testando detecção por extensão...\n";
$testFileName = 'ChatGPT Image 4 de abr. de 2025, 09_13_32.png';
$extension = strtolower(pathinfo($testFileName, PATHINFO_EXTENSION));
echo "Extensão detectada: {$extension}\n";

// Teste 4: Testar nossa classe MimeTypeDetector
echo "\n4. Testando MimeTypeDetector::detect()...\n";
try {
    $detectedMime = MimeTypeDetector::detect($tempPngFile);
    echo "✓ MIME type detectado: {$detectedMime}\n";
    
    if ($detectedMime === 'image/png') {
        echo "✅ SUCESSO: PNG detectado corretamente!\n";
    } else {
        echo "❌ ERRO: Esperado 'image/png', obtido '{$detectedMime}'\n";
    }
} catch (Exception $e) {
    echo "❌ ERRO na detecção: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

// Teste 5: Testar métodos individuais
echo "\n5. Testando métodos individuais...\n";

// Testar detecção por assinatura
echo "- Testando detecção por assinatura...\n";
$handle = fopen($tempPngFile, 'rb');
$bytes = fread($handle, 16);
fclose($handle);
$hex = bin2hex($bytes);
echo "  Assinatura hex: {$hex}\n";
if (strpos($hex, '89504e470d0a1a0a') === 0) {
    echo "  ✓ Assinatura PNG detectada corretamente\n";
} else {
    echo "  ❌ Assinatura PNG não detectada\n";
}

// Testar mime_content_type se disponível
echo "- Testando mime_content_type...\n";
if (function_exists('mime_content_type')) {
    try {
        $mimeFromFunction = mime_content_type($tempPngFile);
        echo "  mime_content_type resultado: " . ($mimeFromFunction ?: 'null') . "\n";
    } catch (Exception $e) {
        echo "  mime_content_type falhou: " . $e->getMessage() . "\n";
    }
} else {
    echo "  mime_content_type não está disponível\n";
}

// Limpeza
unlink($tempPngFile);
echo "\n6. Arquivo temporário removido.\n";

echo "\n=== TESTE CONCLUÍDO ===\n";