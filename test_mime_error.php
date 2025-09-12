<?php

require_once 'vendor/autoload.php';

// Carregar o Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Utils\MimeTypeDetector;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

echo "=== TESTE DE REPRODUÇÃO DO ERRO MIME TYPE ===\n\n";

// Criar um arquivo de teste
$testImagePath = 'test_image.png';
$pngData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==');
file_put_contents($testImagePath, $pngData);

echo "1. Arquivo de teste criado: {$testImagePath}\n";
echo "2. Tamanho do arquivo: " . filesize($testImagePath) . " bytes\n\n";

// Criar um UploadedFile real usando o método do Laravel
$mockFile = new UploadedFile(
    $testImagePath,
    'test.png',
    'image/png',
    null,
    true // test mode
);

echo "3. Testando MimeTypeDetector::detect()...\n";
try {
    $detectedMime = MimeTypeDetector::detect($mockFile);
    echo "✅ MIME type detectado: {$detectedMime}\n";
} catch (Exception $e) {
    echo "❌ Erro no MimeTypeDetector: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n4. Testando validação ImageWithoutFileinfo...\n";
try {
    $validator = new App\Rules\ImageWithoutFileinfo(2048);
    $validator->validate('test_image', $mockFile, function($message) {
        echo "❌ Validação falhou: {$message}\n";
    });
    echo "✅ Validação passou\n";
} catch (Exception $e) {
    echo "❌ Erro na validação: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

// Limpar arquivo de teste
unlink($testImagePath);
echo "\n5. Arquivo de teste removido.\n";
echo "\n=== TESTE FINALIZADO ===\n";