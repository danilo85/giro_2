<?php

require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PortfolioWorkImage;
use App\Models\PortfolioWork;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

echo "=== VERIFICAÇÃO AUTOMÁTICA DE ARQUIVOS FALTANTES ===\n\n";

// Buscar todas as imagens registradas no banco
$images = PortfolioWorkImage::with('portfolioWork')->get();

echo "1. ANÁLISE GERAL:\n";
echo "   Total de imagens no banco: {$images->count()}\n\n";

$missingFiles = [];
$existingFiles = 0;
$totalSize = 0;

echo "2. VERIFICANDO EXISTÊNCIA FÍSICA DOS ARQUIVOS:\n";

foreach ($images as $image) {
    $fullPath = storage_path('app/public/' . $image->path);
    
    if (file_exists($fullPath)) {
        $existingFiles++;
        $fileSize = filesize($fullPath);
        $totalSize += $fileSize;
        echo "   ✓ OK: {$image->path} (" . number_format($fileSize) . " bytes)\n";
    } else {
        $missingFiles[] = [
            'id' => $image->id,
            'path' => $image->path,
            'filename' => $image->filename,
            'work_id' => $image->portfolio_work_id,
            'work_title' => $image->portfolioWork ? $image->portfolioWork->title : 'N/A',
            'created_at' => $image->created_at->format('Y-m-d H:i:s')
        ];
        echo "   ✗ FALTANTE: {$image->path}\n";
    }
}

echo "\n3. RESUMO DA VERIFICAÇÃO:\n";
echo "   Arquivos existentes: {$existingFiles}\n";
echo "   Arquivos faltantes: " . count($missingFiles) . "\n";
echo "   Tamanho total dos existentes: " . number_format($totalSize) . " bytes (" . number_format($totalSize / 1024, 2) . " KB)\n\n";

if (count($missingFiles) > 0) {
    echo "4. DETALHES DOS ARQUIVOS FALTANTES:\n";
    foreach ($missingFiles as $index => $missing) {
        echo "   " . ($index + 1) . ". ID: {$missing['id']} - {$missing['filename']}\n";
        echo "      Path: {$missing['path']}\n";
        echo "      Trabalho: {$missing['work_title']} (ID: {$missing['work_id']})\n";
        echo "      Criado em: {$missing['created_at']}\n\n";
    }
    
    echo "5. REMOVENDO REGISTROS ÓRFÃOS AUTOMATICAMENTE:\n";
    $removedCount = 0;
    
    foreach ($missingFiles as $missing) {
        $image = PortfolioWorkImage::find($missing['id']);
        if ($image) {
            $image->delete();
            echo "   ✓ Removido registro: {$missing['filename']} (ID: {$missing['id']})\n";
            $removedCount++;
        } else {
            echo "   ✗ Registro não encontrado: ID {$missing['id']}\n";
        }
    }
    
    echo "\n   RESUMO DA REMOÇÃO:\n";
    echo "   Registros removidos: {$removedCount}/" . count($missingFiles) . "\n";
    
    if ($removedCount === count($missingFiles)) {
        echo "   ✓ TODOS OS REGISTROS ÓRFÃOS FORAM REMOVIDOS COM SUCESSO!\n";
    } else {
        echo "   ⚠ Alguns registros não puderam ser removidos.\n";
    }
} else {
    echo "4. ✓ TODOS OS ARQUIVOS ESTÃO PRESENTES!\n";
    echo "   Não há arquivos faltantes no sistema.\n";
}

// Verificar trabalhos sem imagens
echo "\n6. VERIFICANDO TRABALHOS SEM IMAGENS:\n";
$worksWithoutImages = PortfolioWork::doesntHave('images')->get();

if ($worksWithoutImages->count() > 0) {
    echo "   ⚠ Trabalhos sem imagens: {$worksWithoutImages->count()}\n";
    foreach ($worksWithoutImages as $work) {
        echo "     - {$work->title} (ID: {$work->id})\n";
    }
    
    echo "\n   REMOVENDO TRABALHOS SEM IMAGENS:\n";
    $removedWorksCount = 0;
    
    foreach ($worksWithoutImages as $work) {
        $work->delete();
        echo "   ✓ Trabalho removido: {$work->title} (ID: {$work->id})\n";
        $removedWorksCount++;
    }
    
    echo "   Trabalhos removidos: {$removedWorksCount}\n";
} else {
    echo "   ✓ Todos os trabalhos possuem pelo menos uma imagem.\n";
}

echo "\n=== VERIFICAÇÃO AUTOMÁTICA CONCLUÍDA ===\n";