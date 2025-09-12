<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use App\Models\PortfolioWorkImage;
use Illuminate\Support\Facades\Storage;

echo "=== Verificação de Imagens Existentes ===\n";

try {
    $images = PortfolioWorkImage::all();
    echo "Total de imagens no banco: " . $images->count() . "\n\n";
    
    foreach ($images as $image) {
        echo "ID: {$image->id}\n";
        echo "Filename: {$image->filename}\n";
        echo "Path: {$image->path}\n";
        echo "URL: {$image->url}\n";
        
        // Verificar se o arquivo existe
        if ($image->path && Storage::disk('public')->exists($image->path)) {
            echo "Status: ✓ Arquivo existe\n";
        } else {
            echo "Status: ✗ Arquivo NÃO existe\n";
        }
        
        echo "Portfolio Work ID: {$image->portfolio_work_id}\n";
        echo "---\n";
    }
    
    // Verificar estrutura de pastas
    echo "\n=== Estrutura de Pastas ===\n";
    $publicPath = storage_path('app/public');
    echo "Storage public path: {$publicPath}\n";
    
    if (is_dir($publicPath . '/portfolio')) {
        echo "✓ Pasta portfolio existe\n";
        
        $portfolioFiles = glob($publicPath . '/portfolio/**/*', GLOB_BRACE);
        echo "Arquivos na pasta portfolio: " . count($portfolioFiles) . "\n";
        
        foreach ($portfolioFiles as $file) {
            if (is_file($file)) {
                $relativePath = str_replace($publicPath . '/', '', $file);
                echo "- {$relativePath}\n";
            }
        }
    } else {
        echo "✗ Pasta portfolio NÃO existe\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}

echo "\n=== Verificação concluída ===\n";