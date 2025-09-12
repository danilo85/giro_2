<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\PortfolioWork;
use App\Models\PortfolioWorkImage;
use App\Models\PortfolioCategory;
use App\Models\Cliente;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

echo "=== TESTE DE UPLOAD DE MÚLTIPLAS IMAGENS ===\n\n";

// 1. Verificar estrutura de pastas
echo "1. Verificando estrutura de pastas...\n";
$portfolioPath = storage_path('app/public/portfolio');
$worksPath = storage_path('app/public/portfolio/works');
$featuredPath = storage_path('app/public/portfolio/featured');

echo "   - Portfolio: " . (is_dir($portfolioPath) ? '✅ Existe' : '❌ Não existe') . "\n";
echo "   - Works: " . (is_dir($worksPath) ? '✅ Existe' : '❌ Não existe') . "\n";
echo "   - Featured: " . (is_dir($featuredPath) ? '✅ Existe' : '❌ Não existe') . "\n";

// Criar pastas se não existirem
if (!is_dir($portfolioPath)) {
    mkdir($portfolioPath, 0755, true);
    echo "   ✅ Pasta portfolio criada\n";
}
if (!is_dir($worksPath)) {
    mkdir($worksPath, 0755, true);
    echo "   ✅ Pasta works criada\n";
}
if (!is_dir($featuredPath)) {
    mkdir($featuredPath, 0755, true);
    echo "   ✅ Pasta featured criada\n";
}

// 2. Verificar link simbólico
echo "\n2. Verificando link simbólico...\n";
$publicStoragePath = public_path('storage');
echo "   - Link público: " . (is_link($publicStoragePath) ? '✅ Existe' : '❌ Não existe') . "\n";

// 3. Simular upload de múltiplas imagens
echo "\n3. Simulando upload de múltiplas imagens...\n";

// Criar imagens de teste simples (arquivos de texto simulando imagens)
$testImages = [];
for ($i = 1; $i <= 3; $i++) {
    $imagePath = sys_get_temp_dir() . "/test_image_{$i}.jpg";
    
    // Criar um arquivo simples simulando uma imagem JPEG
    $jpegHeader = "\xFF\xD8\xFF\xE0\x00\x10JFIF\x00\x01\x01\x01\x00H\x00H\x00\x00\xFF\xDB\x00C\x00";
    $jpegData = $jpegHeader . str_repeat("\x00", 1000); // Simular dados JPEG
    file_put_contents($imagePath, $jpegData);
    
    $testImages[] = new UploadedFile(
        $imagePath,
        "test_image_{$i}.jpg",
        'image/jpeg',
        null,
        true // test mode
    );
    
    echo "   ✅ Imagem de teste {$i} criada: {$imagePath}\n";
}

// 4. Buscar ou criar categoria e cliente
echo "\n4. Preparando dados de teste...\n";

$category = PortfolioCategory::first();
if (!$category) {
    echo "   ❌ Nenhuma categoria encontrada. Criando uma...\n";
    $category = PortfolioCategory::create([
        'name' => 'Teste',
        'slug' => 'teste',
        'description' => 'Categoria de teste',
        'is_active' => true,
        'user_id' => 1
    ]);
}
echo "   ✅ Categoria: {$category->name} (ID: {$category->id})\n";

$client = Cliente::first();
if ($client) {
    echo "   ✅ Cliente: {$client->nome} (ID: {$client->id})\n";
} else {
    echo "   ⚠️ Nenhum cliente encontrado\n";
}

// 5. Criar trabalho de teste
echo "\n5. Criando trabalho de teste...\n";

$workData = [
    'title' => 'Teste Upload Múltiplas Imagens - ' . date('Y-m-d H:i:s'),
    'slug' => 'teste-upload-multiplas-' . time(),
    'description' => 'Teste para verificar upload de múltiplas imagens',
    'content' => 'Conteúdo de teste',
    'portfolio_category_id' => $category->id,
    'client_id' => $client ? $client->id : null,
    'status' => 'published',
    'user_id' => 1,
    'technologies' => ['PHP', 'Laravel', 'JavaScript']
];

$work = PortfolioWork::create($workData);
echo "   ✅ Trabalho criado: {$work->title} (ID: {$work->id})\n";

// 6. Testar upload das imagens
echo "\n6. Testando upload das imagens...\n";

try {
    foreach ($testImages as $index => $image) {
        echo "   Processando imagem " . ($index + 1) . "...\n";
        
        if ($image->isValid()) {
            $filename = time() . '_' . $index . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('portfolio/works', $filename, 'public');
            
            if ($path) {
                // Obter dimensões da imagem (pode falhar com imagem simulada)
                $fullPath = storage_path('app/public/' . $path);
                $imageSize = @getimagesize($fullPath); // @ para suprimir warnings
                
                // Criar registro no banco
                $imageRecord = PortfolioWorkImage::create([
                    'portfolio_work_id' => $work->id,
                    'filename' => $filename,
                    'original_name' => $image->getClientOriginalName(),
                    'path' => $path,
                    'alt_text' => "Imagem " . ($index + 1),
                    'file_size' => $image->getSize(),
                    'mime_type' => $image->getMimeType(),
                    'width' => $imageSize[0] ?? null,
                    'height' => $imageSize[1] ?? null,
                    'sort_order' => $index + 1
                ]);
                
                echo "     ✅ Imagem salva: {$path}\n";
                echo "     ✅ Registro criado no banco: ID {$imageRecord->id}\n";
                echo "     ✅ URL: {$imageRecord->url}\n";
            } else {
                echo "     ❌ Erro ao salvar arquivo\n";
            }
        } else {
            echo "     ❌ Arquivo inválido\n";
        }
    }
} catch (Exception $e) {
    echo "   ❌ Erro durante upload: " . $e->getMessage() . "\n";
}

// 7. Verificar resultados
echo "\n7. Verificando resultados...\n";

$work->load('images');
echo "   - Total de imagens no banco: " . $work->images->count() . "\n";

foreach ($work->images as $image) {
    $filePath = storage_path('app/public/' . $image->path);
    $fileExists = file_exists($filePath);
    $urlWorks = str_replace(url('/'), '', $image->url);
    
    echo "     * {$image->filename}\n";
    echo "       - Path: {$image->path}\n";
    echo "       - Arquivo existe: " . ($fileExists ? '✅ Sim' : '❌ Não') . "\n";
    echo "       - URL: {$image->url}\n";
    echo "       - URL funcional: {$urlWorks}\n";
}

// 8. Testar URLs das imagens
echo "\n8. Testando URLs das imagens...\n";

foreach ($work->images as $image) {
    $publicPath = public_path('storage/' . $image->path);
    $publicExists = file_exists($publicPath);
    
    echo "   - {$image->filename}:\n";
    echo "     * Caminho público: " . ($publicExists ? '✅ Acessível' : '❌ Não acessível') . "\n";
    echo "     * URL: {$image->url}\n";
}

// 9. Limpar arquivos de teste
echo "\n9. Limpando arquivos de teste...\n";

foreach ($testImages as $image) {
    $tempPath = $image->getRealPath();
    if (file_exists($tempPath)) {
        unlink($tempPath);
        echo "   ✅ Arquivo temporário removido\n";
    }
}

echo "\n=== TESTE CONCLUÍDO ===\n";
echo "\nPara testar no navegador, acesse:\n";
echo "- Listagem: http://localhost/giro_2/public/portfolio/works\n";
echo "- Trabalho criado: http://localhost/giro_2/public/portfolio/works/{$work->slug}\n";