<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PortfolioWorkImage;
use Illuminate\Support\Facades\DB;

echo "=== CORREÇÃO DE CAMINHOS DE IMAGENS ===\n\n";

// 1. Listar imagens com caminhos incorretos
echo "1. Verificando imagens com caminhos incorretos...\n";
$incorrectImages = PortfolioWorkImage::where('path', 'LIKE', 'portfolio/%')
    ->where('path', 'NOT LIKE', 'portfolio/works/%')
    ->get();

echo "Encontradas {$incorrectImages->count()} imagens com caminhos incorretos:\n";
foreach ($incorrectImages as $image) {
    echo "- ID {$image->id}: {$image->path}\n";
}

if ($incorrectImages->count() === 0) {
    echo "✓ Nenhuma imagem com caminho incorreto encontrada!\n";
    exit(0);
}

// 2. Corrigir os caminhos
echo "\n2. Corrigindo caminhos...\n";
$correctedCount = 0;

foreach ($incorrectImages as $image) {
    $oldPath = $image->path;
    
    // Converter portfolio/ para portfolio/works/
    if (strpos($oldPath, 'portfolio/') === 0 && strpos($oldPath, 'portfolio/works/') !== 0) {
        $newPath = str_replace('portfolio/', 'portfolio/works/', $oldPath);
        
        try {
            $image->path = $newPath;
            $image->save();
            
            echo "✓ ID {$image->id}: {$oldPath} → {$newPath}\n";
            $correctedCount++;
        } catch (Exception $e) {
            echo "✗ Erro ao corrigir ID {$image->id}: {$e->getMessage()}\n";
        }
    }
}

echo "\n3. Resumo da correção:\n";
echo "- Imagens corrigidas: {$correctedCount}\n";
echo "- Total de imagens incorretas: {$incorrectImages->count()}\n";

// 4. Verificar resultado final
echo "\n4. Verificação final...\n";
$remainingIncorrect = PortfolioWorkImage::where('path', 'LIKE', 'portfolio/%')
    ->where('path', 'NOT LIKE', 'portfolio/works/%')
    ->count();

if ($remainingIncorrect === 0) {
    echo "✓ Todos os caminhos foram corrigidos com sucesso!\n";
} else {
    echo "✗ Ainda existem {$remainingIncorrect} imagens com caminhos incorretos.\n";
}

// 5. Listar todos os caminhos atuais
echo "\n5. Caminhos atuais no banco:\n";
$allImages = PortfolioWorkImage::select('id', 'portfolio_work_id', 'path', 'filename')->get();
foreach ($allImages as $image) {
    $status = strpos($image->path, 'portfolio/works/') === 0 ? '✓' : '✗';
    echo "{$status} ID {$image->id}: {$image->path}\n";
}

echo "\n=== CORREÇÃO CONCLUÍDA ===\n";