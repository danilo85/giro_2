<?php

// Carregar o Laravel
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Utils\MimeTypeDetector;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

echo "=== TESTE LARAVEL MIME TYPE DETECTOR ===\n\n";

// Teste 1: Verificar se a classe está disponível no Laravel
echo "1. Testando classe no contexto Laravel...\n";
try {
    $detector = new MimeTypeDetector();
    echo "✓ Classe MimeTypeDetector instanciada com sucesso\n";
} catch (Exception $e) {
    echo "✗ Erro ao instanciar MimeTypeDetector: " . $e->getMessage() . "\n";
    exit(1);
}

// Teste 2: Testar método estático detect
echo "\n2. Testando método detect com arquivo real...\n";
try {
    // Testar com composer.json
    $mimeType = MimeTypeDetector::detect('composer.json');
    echo "✓ MIME type detectado para composer.json: {$mimeType}\n";
} catch (Exception $e) {
    echo "✗ Erro no método detect: " . $e->getMessage() . "\n";
}

// Teste 3: Testar métodos auxiliares
echo "\n3. Testando métodos auxiliares...\n";
try {
    $isImage = MimeTypeDetector::isImage('image/jpeg');
    $isDocument = MimeTypeDetector::isDocument('application/pdf');
    $description = MimeTypeDetector::getTypeDescription('application/pdf');
    
    echo "✓ isImage('image/jpeg'): " . ($isImage ? 'true' : 'false') . "\n";
    echo "✓ isDocument('application/pdf'): " . ($isDocument ? 'true' : 'false') . "\n";
    echo "✓ getTypeDescription('application/pdf'): {$description}\n";
} catch (Exception $e) {
    echo "✗ Erro nos métodos auxiliares: " . $e->getMessage() . "\n";
}

// Teste 4: Simular upload de arquivo
echo "\n4. Testando simulação de upload...\n";
try {
    // Criar um arquivo temporário para teste
    $tempFile = tempnam(sys_get_temp_dir(), 'test_pdf');
    file_put_contents($tempFile, '%PDF-1.4\n%âãÏÓ\n'); // Assinatura PDF básica
    
    echo "✓ Arquivo temporário criado: {$tempFile}\n";
    
    // Testar detecção
    $mimeType = MimeTypeDetector::detect($tempFile);
    echo "✓ MIME type detectado para arquivo PDF simulado: {$mimeType}\n";
    
    // Limpar arquivo temporário
    unlink($tempFile);
    echo "✓ Arquivo temporário removido\n";
    
} catch (Exception $e) {
    echo "✗ Erro na simulação de upload: " . $e->getMessage() . "\n";
}

// Teste 5: Verificar logs
echo "\n5. Testando sistema de logs...\n";
try {
    Log::info('Teste de log do MimeTypeDetector', [
        'timestamp' => now(),
        'teste' => 'funcionando'
    ]);
    echo "✓ Log de teste enviado com sucesso\n";
} catch (Exception $e) {
    echo "✗ Erro no sistema de logs: " . $e->getMessage() . "\n";
}

echo "\n=== TESTE LARAVEL CONCLUÍDO ===\n";
echo "\nSe todos os testes passaram, a implementação está funcionando!\n";
echo "Agora você pode testar o upload real através da interface web.\n";