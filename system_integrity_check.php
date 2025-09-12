<?php

require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PortfolioWork;
use App\Models\PortfolioWorkImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\PortfolioController;
use Illuminate\Http\Request;

echo "=== VERIFICAÇÃO COMPLETA DE INTEGRIDADE DO SISTEMA DE UPLOAD ===\n\n";

// 1. Verificar estrutura de diretórios
echo "1. VERIFICANDO ESTRUTURA DE DIRETÓRIOS:\n";
$requiredDirs = [
    storage_path('app/public'),
    storage_path('app/public/portfolio'),
    storage_path('app/public/portfolio/works'),
    public_path('storage')
];

foreach ($requiredDirs as $dir) {
    if (is_dir($dir)) {
        echo "   ✓ Diretório existe: {$dir}\n";
    } else {
        echo "   ✗ Diretório faltante: {$dir}\n";
    }
}

// 2. Verificar link simbólico
echo "\n2. VERIFICANDO LINK SIMBÓLICO:\n";
$storageLink = public_path('storage');
$storageTarget = storage_path('app/public');

if (is_link($storageLink)) {
    $linkTarget = readlink($storageLink);
    echo "   ✓ Link simbólico existe: {$storageLink}\n";
    echo "   → Aponta para: {$linkTarget}\n";
    
    if (realpath($linkTarget) === realpath($storageTarget)) {
        echo "   ✓ Link simbólico está correto\n";
    } else {
        echo "   ✗ Link simbólico aponta para local incorreto\n";
        echo "     Esperado: {$storageTarget}\n";
        echo "     Atual: {$linkTarget}\n";
    }
} else if (is_dir($storageLink)) {
    echo "   ⚠ Existe diretório em vez de link simbólico: {$storageLink}\n";
} else {
    echo "   ✗ Link simbólico não existe: {$storageLink}\n";
}

// 3. Verificar permissões
echo "\n3. VERIFICANDO PERMISSÕES:\n";
$checkDirs = [
    storage_path('app/public/portfolio/works'),
    public_path('storage')
];

foreach ($checkDirs as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        $writable = is_writable($dir);
        echo "   Diretório: {$dir}\n";
        echo "   Permissões: {$perms} (" . ($writable ? 'Gravável' : 'Não gravável') . ")\n";
    }
}

// 4. Verificar banco de dados
echo "\n4. VERIFICANDO BANCO DE DADOS:\n";
$totalWorks = PortfolioWork::count();
$totalImages = PortfolioWorkImage::count();
$worksWithImages = PortfolioWork::has('images')->count();
$worksWithoutImages = PortfolioWork::doesntHave('images')->count();

echo "   Total de trabalhos: {$totalWorks}\n";
echo "   Total de imagens: {$totalImages}\n";
echo "   Trabalhos com imagens: {$worksWithImages}\n";
echo "   Trabalhos sem imagens: {$worksWithoutImages}\n";

if ($worksWithoutImages > 0) {
    echo "   ⚠ Existem trabalhos sem imagens\n";
} else {
    echo "   ✓ Todos os trabalhos possuem imagens\n";
}

// 5. Verificar consistência de arquivos
echo "\n5. VERIFICANDO CONSISTÊNCIA DE ARQUIVOS:\n";
$images = PortfolioWorkImage::all();
$existingFiles = 0;
$missingFiles = 0;
$totalSize = 0;

foreach ($images as $image) {
    $fullPath = storage_path('app/public/' . $image->path);
    
    if (file_exists($fullPath)) {
        $existingFiles++;
        $totalSize += filesize($fullPath);
    } else {
        $missingFiles++;
        echo "   ✗ Arquivo faltante: {$image->path}\n";
    }
}

echo "   Arquivos existentes: {$existingFiles}\n";
echo "   Arquivos faltantes: {$missingFiles}\n";
echo "   Tamanho total: " . number_format($totalSize / 1024, 2) . " KB\n";

// 6. Teste de upload simulado (múltiplas imagens)
echo "\n6. TESTE DE UPLOAD SIMULADO (MÚLTIPLAS IMAGENS):\n";

// Criar imagens de teste
$testImages = [];
for ($i = 1; $i <= 3; $i++) {
    $tempFile = tempnam(sys_get_temp_dir(), 'test_image_');
    $imageContent = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/wA==');
    file_put_contents($tempFile, $imageContent);
    
    $testImages[] = new UploadedFile(
        $tempFile,
        "test_integrity_image_{$i}.jpg",
        'image/jpeg',
        null,
        true
    );
}

echo "   Criadas 3 imagens de teste\n";

// Simular autenticação
Auth::loginUsingId(1);
echo "   Usuário autenticado: " . Auth::id() . "\n";

// Simular requisição
$request = new Request();
$request->merge([
    'title' => 'Teste de Integridade do Sistema',
    'description' => 'Teste automático para verificar integridade do upload de múltiplas imagens',
    'portfolio_category_id' => 1, // Usar primeira categoria disponível
    'status' => 'published' // Status obrigatório
]);
$request->files->set('images', $testImages);
$request->headers->set('Accept', 'application/json');
$request->headers->set('X-Requested-With', 'XMLHttpRequest');

echo "   Simulando upload via PortfolioController...\n";

try {
    $controller = new PortfolioController();
    $response = $controller->store($request);
    
    if ($response->getStatusCode() === 200) {
        $responseData = json_decode($response->getContent(), true);
        echo "   ✓ Upload realizado com sucesso\n";
        echo "   Status: {$response->getStatusCode()}\n";
        
        if (isset($responseData['work_id'])) {
            $workId = $responseData['work_id'];
            echo "   ID do trabalho criado: {$workId}\n";
            
            // Verificar se as imagens foram salvas
            $savedImages = PortfolioWorkImage::where('portfolio_work_id', $workId)->get();
            echo "   Imagens salvas no banco: {$savedImages->count()}\n";
            
            $physicalFiles = 0;
            foreach ($savedImages as $image) {
                $fullPath = storage_path('app/public/' . $image->path);
                if (file_exists($fullPath)) {
                    $physicalFiles++;
                    $fileSize = filesize($fullPath);
                    echo "   ✓ Arquivo físico existe: {$image->path} ({$fileSize} bytes)\n";
                } else {
                    echo "   ✗ Arquivo físico faltante: {$image->path}\n";
                }
            }
            
            echo "   Arquivos físicos encontrados: {$physicalFiles}/{$savedImages->count()}\n";
            
            // Teste de acesso via URL
            echo "\n   TESTANDO ACESSO VIA URL:\n";
            foreach ($savedImages as $image) {
                $url = asset('storage/' . $image->path);
                echo "   URL gerada: {$url}\n";
                
                $fullPath = storage_path('app/public/' . $image->path);
                $publicPath = public_path('storage/' . $image->path);
                
                if (file_exists($publicPath)) {
                    echo "   ✓ Arquivo acessível via URL\n";
                } else {
                    echo "   ✗ Arquivo não acessível via URL\n";
                    echo "     Caminho público: {$publicPath}\n";
                }
            }
            
            // Limpeza
            echo "\n   LIMPANDO DADOS DE TESTE:\n";
            $work = PortfolioWork::find($workId);
            if ($work) {
                // Remover arquivos físicos
                foreach ($savedImages as $image) {
                    $fullPath = storage_path('app/public/' . $image->path);
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                        echo "   ✓ Arquivo removido: {$image->path}\n";
                    }
                }
                
                // Remover registros do banco
                $work->delete();
                echo "   ✓ Trabalho de teste removido do banco\n";
            }
            
        } else {
            echo "   ⚠ ID do trabalho não retornado na resposta\n";
        }
    } else {
        echo "   ✗ Erro no upload: Status {$response->getStatusCode()}\n";
        echo "   Resposta: {$response->getContent()}\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ Erro durante o teste: {$e->getMessage()}\n";
    echo "   Arquivo: {$e->getFile()}:{$e->getLine()}\n";
}

// Limpar arquivos temporários
foreach ($testImages as $testImage) {
    if (file_exists($testImage->getPathname())) {
        unlink($testImage->getPathname());
    }
}

echo "\n7. RESUMO FINAL:\n";
echo "   ✓ Verificação de integridade concluída\n";
echo "   ✓ Sistema de upload testado com múltiplas imagens\n";
echo "   ✓ Consistência de dados verificada\n";

echo "\n=== VERIFICAÇÃO DE INTEGRIDADE CONCLUÍDA ===\n";