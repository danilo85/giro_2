<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PortfolioCategory;
use App\Models\PortfolioWork;
use App\Models\PortfolioWorkImage;
use App\Http\Controllers\PortfolioController;

// Inicializar Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTE SIMPLES DE UPLOAD MÚLTIPLO ===\n\n";

// 1. Verificar usuário e categoria
$user = User::first();
$category = PortfolioCategory::first();

if (!$user || !$category) {
    echo "✗ Usuário ou categoria não encontrados\n";
    exit(1);
}

echo "✓ Usuário: {$user->name} (ID: {$user->id})\n";
echo "✓ Categoria: {$category->name} (ID: {$category->id})\n\n";

// 2. Criar imagens de teste válidas
echo "2. CRIANDO IMAGENS DE TESTE:\n";
$testImages = [];

for ($i = 1; $i <= 3; $i++) {
    $imageName = "test_upload_{$i}.jpg";
    $imagePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $imageName;
    
    // Criar um arquivo JPEG válido mínimo
    $jpegData = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/8A');
    file_put_contents($imagePath, $jpegData);
    
    $testImages[] = new UploadedFile(
        $imagePath,
        $imageName,
        'image/jpeg',
        null,
        true
    );
    
    echo "   ✓ Criada: {$imageName} (" . filesize($imagePath) . " bytes)\n";
}

// 3. Simular autenticação
echo "\n3. SIMULANDO AUTENTICAÇÃO:\n";
$request = Request::create('/', 'POST');
app()->instance('request', $request);
Auth::guard('web')->setRequest($request);
Auth::loginUsingId($user->id);
echo "   ✓ Usuário autenticado: " . Auth::user()->name . " (ID: " . Auth::id() . ")\n";

// 4. Teste com UMA imagem
echo "\n4. TESTE COM UMA IMAGEM:\n";
$singleRequest = new Request();
$singleRequest->merge([
    'title' => 'Teste Uma Imagem - ' . date('Y-m-d H:i:s'),
    'description' => 'Teste com apenas uma imagem',
    'portfolio_category_id' => $category->id,
    'status' => 'published'
]);
$singleRequest->files->set('images', [$testImages[0]]);

try {
    $controller = new PortfolioController();
    $response = $controller->store($singleRequest);
    
    if ($response->getStatusCode() == 200) {
        $data = json_decode($response->getContent(), true);
        $workId = $data['work']['id'] ?? null;
        echo "   ✓ Upload com 1 imagem: SUCESSO (Work ID: {$workId})\n";
        
        if ($workId) {
            $work = PortfolioWork::with('images')->find($workId);
            echo "   ✓ Imagens salvas no banco: " . $work->images->count() . "\n";
        }
    } else {
        echo "   ✗ Erro no upload: Status " . $response->getStatusCode() . "\n";
        echo "   Resposta: " . $response->getContent() . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ Exceção: " . $e->getMessage() . "\n";
}

// 5. Teste com MÚLTIPLAS imagens
echo "\n5. TESTE COM MÚLTIPLAS IMAGENS (3):\n";
$multiRequest = new Request();
$multiRequest->merge([
    'title' => 'Teste Múltiplas Imagens - ' . date('Y-m-d H:i:s'),
    'description' => 'Teste com três imagens',
    'portfolio_category_id' => $category->id,
    'status' => 'published'
]);
$multiRequest->files->set('images', $testImages);

try {
    $controller = new PortfolioController();
    $response = $controller->store($multiRequest);
    
    if ($response->getStatusCode() == 200) {
        $data = json_decode($response->getContent(), true);
        $workId = $data['work']['id'] ?? null;
        echo "   ✓ Upload com 3 imagens: SUCESSO (Work ID: {$workId})\n";
        
        if ($workId) {
            $work = PortfolioWork::with('images')->find($workId);
            echo "   ✓ Imagens salvas no banco: " . $work->images->count() . "/3\n";
            
            foreach ($work->images as $index => $img) {
                echo "     ✓ Imagem " . ($index + 1) . ": {$img->path}\n";
            }
            
            if ($work->images->count() == 3) {
                echo "   ✓ TODAS as 3 imagens foram salvas corretamente!\n";
            } else {
                echo "   ✗ PROBLEMA: Esperadas 3 imagens, encontradas " . $work->images->count() . "\n";
            }
        }
    } else {
        echo "   ✗ Erro no upload: Status " . $response->getStatusCode() . "\n";
        echo "   Resposta: " . $response->getContent() . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ Exceção: " . $e->getMessage() . "\n";
}

// 6. Verificar estatísticas
echo "\n6. ESTATÍSTICAS FINAIS:\n";
$totalWorks = PortfolioWork::count();
$totalImages = PortfolioWorkImage::count();
echo "   Total de trabalhos: {$totalWorks}\n";
echo "   Total de imagens: {$totalImages}\n";

// Verificar trabalhos com múltiplas imagens
$worksWithMultipleImages = PortfolioWork::withCount('images')
    ->having('images_count', '>', 1)
    ->count();
echo "   Trabalhos com múltiplas imagens: {$worksWithMultipleImages}\n";

if ($worksWithMultipleImages > 0) {
    echo "   ✓ Upload múltiplo está funcionando!\n";
} else {
    echo "   ⚠ Nenhum trabalho tem múltiplas imagens\n";
}

// Limpeza
echo "\n7. LIMPEZA:\n";
foreach ($testImages as $img) {
    if (file_exists($img->getPathname())) {
        unlink($img->getPathname());
        echo "   ✓ Removido: " . basename($img->getPathname()) . "\n";
    }
}

echo "\n=== TESTE CONCLUÍDO ===\n";