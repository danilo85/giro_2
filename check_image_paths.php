<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VERIFICANDO CAMINHOS DAS IMAGENS NO BANCO ===\n\n";

$images = App\Models\PortfolioWorkImage::select('id', 'portfolio_work_id', 'path', 'filename')->get();

echo "Total de imagens: " . $images->count() . "\n\n";

foreach($images as $img) {
    echo "ID: {$img->id}, Work: {$img->portfolio_work_id}, Path: {$img->path}\n";
    echo "  Filename: {$img->filename}\n";
    
    // Verificar se arquivo existe
    $fullPath = storage_path('app/public/' . $img->path);
    $exists = file_exists($fullPath) ? '✓' : '✗';
    echo "  Existe: {$exists}\n";
    echo "  Full path: {$fullPath}\n\n";
}

echo "=== ANÁLISE DE PADRÕES ===\n";

$portfolioWorksPattern = $images->filter(function($img) {
    return strpos($img->path, 'portfolio/works/') === 0;
})->count();

$portfolioOnlyPattern = $images->filter(function($img) {
    return strpos($img->path, 'portfolio/') === 0 && strpos($img->path, 'portfolio/works/') !== 0;
})->count();

echo "Imagens com padrão 'portfolio/works/': {$portfolioWorksPattern}\n";
echo "Imagens com padrão 'portfolio/' (sem works): {$portfolioOnlyPattern}\n";
echo "Outros padrões: " . ($images->count() - $portfolioWorksPattern - $portfolioOnlyPattern) . "\n";