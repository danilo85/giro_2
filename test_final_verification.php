<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\PortfolioWork;
use App\Models\PortfolioWorkImage;

// Inicializar Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== VERIFICAÇÃO FINAL DO SISTEMA DE UPLOAD ===\n\n";

// 1. Verificar link simbólico
echo "1. VERIFICANDO LINK SIMBÓLICO:\n";
$publicStoragePath = public_path('storage');
$storageAppPath = storage_path('app/public');

if (is_link($publicStoragePath)) {
    echo "   ✓ Link simbólico existe: {$publicStoragePath}\n";
    echo "   ✓ Aponta para: " . readlink($publicStoragePath) . "\n";
} elseif (is_dir($publicStoragePath)) {
    echo "   ⚠ Diretório existe (não é link): {$publicStoragePath}\n";
} else {
    echo "   ✗ Link/diretório não existe: {$publicStoragePath}\n";
}

// 2. Estatísticas gerais
echo "\n2. ESTATÍSTICAS DO SISTEMA:\n";
$totalWorks = PortfolioWork::count();
$totalImages = PortfolioWorkImage::count();
$worksWithMultiple = PortfolioWork::withCount('images')->having('images_count', '>', 1)->count();
$worksWithSingle = PortfolioWork::withCount('images')->having('images_count', '=', 1)->count();

echo "   Total de trabalhos: {$totalWorks}\n";
echo "   Total de imagens: {$totalImages}\n";
echo "   Trabalhos com 1 imagem: {$worksWithSingle}\n";
echo "   Trabalhos com múltiplas imagens: {$worksWithMultiple}\n";

// 3. Verificar trabalhos recentes com múltiplas imagens
echo "\n3. TRABALHOS RECENTES COM MÚLTIPLAS IMAGENS:\n";
$recentMultiple = PortfolioWork::withCount('images')
    ->having('images_count', '>', 1)
    ->orderBy('created_at', 'desc')
    ->take(3)
    ->get();

if ($recentMultiple->count() > 0) {
    foreach ($recentMultiple as $work) {
        echo "   ✓ '{$work->title}' - {$work->images_count} imagens\n";
        
        // Verificar se as imagens existem fisicamente
        foreach ($work->images as $index => $image) {
            $fullPath = storage_path('app/public/' . $image->path);
            $publicUrl = asset('storage/' . $image->path);
            $exists = file_exists($fullPath) ? '✓' : '✗';
            echo "     {$exists} Imagem " . ($index + 1) . ": {$image->path}\n";
            echo "       URL: {$publicUrl}\n";
            if (file_exists($fullPath)) {
                echo "       Tamanho: " . filesize($fullPath) . " bytes\n";
            }
        }
        echo "\n";
    }
} else {
    echo "   ⚠ Nenhum trabalho com múltiplas imagens encontrado\n";
}

// 4. Teste de URLs de imagem
echo "4. TESTE DE URLS DE IMAGEM:\n";
$sampleImages = PortfolioWorkImage::take(5)->get();

foreach ($sampleImages as $index => $image) {
    $fullPath = storage_path('app/public/' . $image->path);
    $publicUrl = asset('storage/' . $image->path);
    $exists = file_exists($fullPath);
    
    echo "   Imagem " . ($index + 1) . ":\n";
    echo "     Path: {$image->path}\n";
    echo "     URL: {$publicUrl}\n";
    echo "     Arquivo existe: " . ($exists ? 'SIM' : 'NÃO') . "\n";
    
    if ($exists) {
        $size = filesize($fullPath);
        $mime = mime_content_type($fullPath);
        echo "     Tamanho: {$size} bytes\n";
        echo "     MIME: {$mime}\n";
    }
    echo "\n";
}

// 5. Diagnóstico final
echo "5. DIAGNÓSTICO FINAL:\n";

if ($worksWithMultiple > 0) {
    echo "   ✓ UPLOAD MÚLTIPLO: Funcionando ({$worksWithMultiple} trabalhos)\n";
} else {
    echo "   ✗ UPLOAD MÚLTIPLO: Não funcionando\n";
}

if (is_link($publicStoragePath) || is_dir($publicStoragePath)) {
    echo "   ✓ LINK SIMBÓLICO: Configurado\n";
} else {
    echo "   ✗ LINK SIMBÓLICO: Não configurado\n";
}

$imagesWithFiles = 0;
foreach (PortfolioWorkImage::all() as $img) {
    if (file_exists(storage_path('app/public/' . $img->path))) {
        $imagesWithFiles++;
    }
}

$imageFilePercentage = $totalImages > 0 ? round(($imagesWithFiles / $totalImages) * 100, 1) : 0;
echo "   ✓ ARQUIVOS DE IMAGEM: {$imagesWithFiles}/{$totalImages} ({$imageFilePercentage}%)\n";

if ($worksWithMultiple > 0 && $imageFilePercentage > 80) {
    echo "\n🎉 SISTEMA FUNCIONANDO CORRETAMENTE!\n";
    echo "   - Upload múltiplo: OK\n";
    echo "   - Armazenamento: OK\n";
    echo "   - Links públicos: OK\n";
} else {
    echo "\n⚠ SISTEMA COM PROBLEMAS:\n";
    if ($worksWithMultiple == 0) {
        echo "   - Upload múltiplo não está funcionando\n";
    }
    if ($imageFilePercentage <= 80) {
        echo "   - Muitos arquivos de imagem estão faltando\n";
    }
}

echo "\n=== VERIFICAÇÃO CONCLUÍDA ===\n";