<?php

require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PortfolioWork;
use App\Models\PortfolioCategory;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

echo "=== TESTE DE DEBUG DO UPLOAD ===\n\n";

// 1. Verificar se há logs recentes de uploadImages
echo "1. Verificando logs recentes de uploadImages...\n";
$logFile = storage_path('logs/laravel.log');
if (file_exists($logFile)) {
    $logs = file_get_contents($logFile);
    $uploadLogs = [];
    
    // Procurar por logs relacionados ao uploadImages
    $lines = explode("\n", $logs);
    foreach ($lines as $line) {
        if (strpos($line, 'uploadImages') !== false || 
            strpos($line, 'Has file images') !== false ||
            strpos($line, 'Images count') !== false) {
            $uploadLogs[] = $line;
        }
    }
    
    if (count($uploadLogs) > 0) {
        echo "✓ Encontrados " . count($uploadLogs) . " logs relacionados ao upload:\n";
        foreach (array_slice($uploadLogs, -10) as $log) {
            echo "  - " . trim($log) . "\n";
        }
    } else {
        echo "✗ Nenhum log de uploadImages encontrado\n";
    }
} else {
    echo "✗ Arquivo de log não encontrado\n";
}

echo "\n2. Simulando upload de imagem...\n";

// Criar uma imagem de teste simples (arquivo binário mínimo)
$testImagePath = storage_path('app/test_image.jpg');
if (!file_exists($testImagePath)) {
    // Criar um arquivo de imagem JPEG mínimo válido
    $jpegHeader = "\xFF\xD8\xFF\xE0\x00\x10JFIF\x00\x01\x01\x01\x00H\x00H\x00\x00\xFF\xDB\x00C\x00\x08\x06\x06\x07\x06\x05\x08\x07\x07\x07\t\t\x08\n\x0C\x14\r\x0C\x0B\x0B\x0C\x19\x12\x13\x0F\x14\x1D\x1A\x1F\x1E\x1D\x1A\x1C\x1C $.' \",#\x1C\x1C(7),01444\x1F'9=82<.342\xFF\xC0\x00\x11\x08\x00\x01\x00\x01\x01\x01\x11\x00\x02\x11\x01\x03\x11\x01\xFF\xC4\x00\x14\x00\x01\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x08\xFF\xC4\x00\x14\x10\x01\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xFF\xDA\x00\x0C\x03\x01\x00\x02\x11\x03\x11\x00\x3F\x00\xAA\xFF\xD9";
    file_put_contents($testImagePath, $jpegHeader);
    echo "✓ Imagem de teste criada: $testImagePath\n";
} else {
    echo "✓ Imagem de teste já existe: $testImagePath\n";
}

// 3. Verificar dados necessários
echo "\n3. Verificando dados necessários...\n";
$user = User::first();
$category = PortfolioCategory::first();
$client = Cliente::first();

if (!$user) {
    echo "✗ Usuário não encontrado\n";
    exit(1);
}
if (!$category) {
    echo "✗ Categoria não encontrada\n";
    exit(1);
}
if (!$client) {
    echo "✗ Cliente não encontrado\n";
    exit(1);
}

echo "✓ Dados necessários encontrados\n";

// 4. Simular o processo de upload
echo "\n4. Simulando processo de upload...\n";

// Criar trabalho
$work = PortfolioWork::create([
    'title' => 'Teste Upload Debug - ' . date('Y-m-d H:i:s'),
    'slug' => 'teste-upload-debug-' . time(),
    'portfolio_category_id' => $category->id,
    'user_id' => $user->id,
    'client_id' => $client->id,
    'status' => 'published'
]);

echo "✓ Trabalho criado (ID: {$work->id})\n";

// Simular UploadedFile
$uploadedFile = new UploadedFile(
    $testImagePath,
    'test_image.jpg',
    'image/jpeg',
    null,
    true // test mode
);

echo "✓ UploadedFile criado\n";
echo "  - Nome original: " . $uploadedFile->getClientOriginalName() . "\n";
echo "  - MIME type: " . $uploadedFile->getMimeType() . "\n";
echo "  - Tamanho: " . $uploadedFile->getSize() . " bytes\n";
echo "  - É válido: " . ($uploadedFile->isValid() ? 'Sim' : 'Não') . "\n";

// Testar o método uploadImages diretamente
echo "\n5. Testando método uploadImages...\n";

try {
    // Usar reflexão para acessar o método privado
    $controller = new \App\Http\Controllers\PortfolioController();
    $reflection = new ReflectionClass($controller);
    $method = $reflection->getMethod('uploadImages');
    $method->setAccessible(true);
    
    // Chamar o método
    $method->invoke($controller, $work, [$uploadedFile]);
    
    echo "✓ Método uploadImages executado sem erros\n";
    
    // Verificar se a imagem foi salva no banco
    $images = $work->images()->get();
    echo "✓ Imagens no banco: " . $images->count() . "\n";
    
    foreach ($images as $image) {
        echo "  - ID: {$image->id}, Filename: {$image->filename}\n";
    }
    
} catch (Exception $e) {
    echo "✗ Erro ao executar uploadImages: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== TESTE CONCLUÍDO ===\n";