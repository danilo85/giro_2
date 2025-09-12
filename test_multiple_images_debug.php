<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PortfolioController;
use App\Models\PortfolioWork;
use App\Models\PortfolioWorkImage;
use App\Models\PortfolioCategory;
use App\Models\User;

echo "=== TESTE DE DEBUG PARA MÚLTIPLAS IMAGENS ===\n\n";

// 1. Verificar usuários e categorias disponíveis
echo "1. VERIFICANDO DADOS BÁSICOS:\n";
$user = User::first();
echo "   Usuário: {$user->name} (ID: {$user->id})\n";

$category = PortfolioCategory::first();
echo "   Categoria: {$category->name} (ID: {$category->id})\n";

// 2. Verificar link simbólico
echo "\n2. VERIFICANDO LINK SIMBÓLICO:\n";
$storageLink = public_path('storage');
if (is_link($storageLink)) {
    echo "   ✓ Link simbólico existe: {$storageLink}\n";
    echo "   ✓ Aponta para: " . readlink($storageLink) . "\n";
} else {
    echo "   ✗ Link simbólico não existe: {$storageLink}\n";
    echo "   ⚠ Execute: php artisan storage:link\n";
}

// 3. Buscar imagens existentes para teste
echo "\n3. BUSCANDO IMAGENS EXISTENTES PARA TESTE:\n";
$existingImages = PortfolioWorkImage::take(5)->get();
$testImages = [];

if ($existingImages->count() > 0) {
    foreach ($existingImages as $index => $img) {
        $fullPath = storage_path('app/public/' . $img->path);
        if (file_exists($fullPath)) {
            $testImages[] = new UploadedFile(
                $fullPath,
                "test_copy_{$index}.jpg",
                'image/jpeg',
                null,
                true
            );
            echo "   ✓ Usando: {$img->path} (" . filesize($fullPath) . " bytes)\n";
        }
    }
} else {
    echo "   ⚠ Nenhuma imagem existente encontrada. Criando arquivos de teste simples...\n";
    // Fallback: criar arquivos de teste mínimos
    for ($i = 1; $i <= 3; $i++) {
        $imageName = "test_{$i}.jpg";
        $imagePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $imageName;
        
        // Criar um arquivo JPEG mínimo válido (header básico)
        $jpegHeader = "\xFF\xD8\xFF\xE0\x00\x10JFIF\x00\x01\x01\x01\x00H\x00H\x00\x00\xFF\xDB";
        $content = $jpegHeader . str_repeat('\x00', 100) . "\xFF\xD9"; // End of Image
        file_put_contents($imagePath, $content);
        
        $testImages[] = new UploadedFile(
            $imagePath,
            $imageName,
            'image/jpeg',
            null,
            true
        );
        
        echo "   ✓ Criada: {$imageName} (" . filesize($imagePath) . " bytes)\n";
    }
}

echo "   Total de imagens para teste: " . count($testImages) . "\n";

// 4. Simular autenticação
echo "\n4. SIMULANDO AUTENTICAÇÃO:\n";
// Criar uma sessão fake para CLI
$request = Request::create('/', 'POST');
app()->instance('request', $request);
Auth::guard('web')->setRequest($request);
Auth::loginUsingId($user->id);
echo "   ✓ Usuário autenticado: " . Auth::user()->name . " (ID: " . Auth::id() . ")\n";

// 5. Teste com UMA imagem
echo "\n5. TESTE COM UMA IMAGEM:\n";
if (count($testImages) > 0) {
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
            
            foreach ($work->images as $img) {
                $fullPath = storage_path('app/public/' . $img->path);
                $exists = file_exists($fullPath) ? '✓' : '✗';
                echo "     {$exists} {$img->path} (existe: " . ($exists == '✓' ? 'SIM' : 'NÃO') . ")\n";
                
                // Testar URL de acesso
                $url = asset('storage/' . $img->path);
                echo "     URL: {$url}\n";
            }
        }
    } else {
        echo "   ✗ Erro no upload: Status " . $response->getStatusCode() . "\n";
        echo "   Resposta: " . $response->getContent() . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ Exceção: " . $e->getMessage() . "\n";
}
} else {
    echo "   ✗ Nenhuma imagem disponível para teste\n";
}

// 6. Teste com MÚLTIPLAS imagens
echo "\n6. TESTE COM MÚLTIPLAS IMAGENS (" . count($testImages) . "):\n";
if (count($testImages) > 1) {
    $multiRequest = new Request();
    $multiRequest->merge([
        'title' => 'Teste Múltiplas Imagens - ' . date('Y-m-d H:i:s'),
        'description' => 'Teste com múltiplas imagens',
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
        echo "   ✓ Upload com 5 imagens: SUCESSO (Work ID: {$workId})\n";
        
        if ($workId) {
            $work = PortfolioWork::with('images')->find($workId);
            echo "   ✓ Imagens salvas no banco: " . $work->images->count() . "/5\n";
            
            foreach ($work->images as $index => $img) {
                $fullPath = storage_path('app/public/' . $img->path);
                $exists = file_exists($fullPath) ? '✓' : '✗';
                echo "     {$exists} Imagem " . ($index + 1) . ": {$img->path} (existe: " . ($exists == '✓' ? 'SIM' : 'NÃO') . ")\n";
                
                // Testar URL de acesso
                $url = asset('storage/' . $img->path);
                echo "       URL: {$url}\n";
            }
            
            // Verificar se todas as imagens foram salvas
            $expectedCount = count($testImages);
            if ($work->images->count() == $expectedCount) {
                echo "   ✓ TODAS as {$expectedCount} imagens foram salvas corretamente!\n";
            } else {
                echo "   ✗ PROBLEMA: Esperadas {$expectedCount} imagens, encontradas " . $work->images->count() . "\n";
            }
        }
    } else {
        echo "   ✗ Erro no upload: Status " . $response->getStatusCode() . "\n";
        echo "   Resposta: " . $response->getContent() . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ Exceção: " . $e->getMessage() . "\n";
}
} else {
    echo "   ✗ Menos de 2 imagens disponíveis para teste múltiplo\n";
}

// 7. Listar todas as imagens do sistema
echo "\n7. LISTANDO TODAS AS IMAGENS DO SISTEMA:\n";
$allImages = PortfolioWorkImage::with('portfolioWork')->orderBy('created_at', 'desc')->take(10)->get();
echo "   Total de imagens (últimas 10): " . $allImages->count() . "\n";

foreach ($allImages as $img) {
    $fullPath = storage_path('app/public/' . $img->path);
    $exists = file_exists($fullPath) ? '✓' : '✗';
    $size = $exists == '✓' ? filesize($fullPath) : 0;
    echo "   {$exists} {$img->portfolioWork->title}: {$img->path} ({$size} bytes)\n";
}

// 8. Teste de exibição nas views
echo "\n8. TESTE DE EXIBIÇÃO NAS VIEWS:\n";
$recentWork = PortfolioWork::with('images')->latest()->first();
if ($recentWork && $recentWork->images->count() > 0) {
    echo "   Trabalho mais recente: {$recentWork->title}\n";
    echo "   Número de imagens: " . $recentWork->images->count() . "\n";
    
    foreach ($recentWork->images as $img) {
        $url = asset('storage/' . $img->path);
        echo "   URL para view: {$url}\n";
        
        // Simular verificação se a URL seria acessível
        $publicPath = public_path('storage/' . $img->path);
        $accessible = file_exists($publicPath) ? '✓' : '✗';
        echo "   Acessível via web: {$accessible}\n";
    }
} else {
    echo "   ✗ Nenhum trabalho com imagens encontrado\n";
}

// 9. Diagnóstico final
echo "\n9. DIAGNÓSTICO FINAL:\n";

// Verificar diferenças entre upload único vs múltiplo
$singleImageWorks = PortfolioWork::has('images')->whereHas('images', function($q) {
    $q->havingRaw('COUNT(*) = 1');
})->count();

$multipleImageWorks = PortfolioWork::has('images')->whereHas('images', function($q) {
    $q->havingRaw('COUNT(*) > 1');
})->count();

echo "   Trabalhos com 1 imagem: {$singleImageWorks}\n";
echo "   Trabalhos com múltiplas imagens: {$multipleImageWorks}\n";

// Verificar se há padrões nos problemas
if ($multipleImageWorks == 0) {
    echo "   ⚠ PROBLEMA IDENTIFICADO: Nenhum trabalho tem múltiplas imagens!\n";
    echo "   Isso indica que o upload múltiplo pode não estar funcionando corretamente.\n";
} else {
    echo "   ✓ Upload múltiplo parece estar funcionando\n";
}

// Limpeza
echo "\n10. LIMPEZA:\n";
foreach ($testImages as $img) {
    if (file_exists($img->getPathname())) {
        unlink($img->getPathname());
        echo "   ✓ Removido: " . basename($img->getPathname()) . "\n";
    }
}

echo "\n=== TESTE DE DEBUG CONCLUÍDO ===\n";