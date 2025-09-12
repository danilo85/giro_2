<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PortfolioWork;
use App\Models\PortfolioWorkImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

try {
    echo "Testando método uploadImages...\n";
    
    // Pegar um trabalho existente
    $work = PortfolioWork::first();
    if (!$work) {
        echo "Nenhum trabalho encontrado!\n";
        exit(1);
    }
    
    echo "Usando trabalho ID: {$work->id}\n";
    
    // Simular o que acontece no método uploadImages
    echo "Simulando uploadImages...\n";
    
    // Criar dados de imagem fictícios (como se fossem arquivos enviados)
    $fakeImages = [
        [
            'filename' => 'fake_image_1_' . time() . '.jpg',
            'path' => 'portfolio/images/fake_image_1_' . time() . '.jpg',
            'original_name' => 'fake_image_1.jpg'
        ],
        [
            'filename' => 'fake_image_2_' . time() . '.jpg', 
            'path' => 'portfolio/images/fake_image_2_' . time() . '.jpg',
            'original_name' => 'fake_image_2.jpg'
        ]
    ];
    
    foreach ($fakeImages as $index => $imageData) {
        echo "Processando imagem {$index}...\n";
        
        // Calcular sort_order como no método original
        $maxSortOrder = PortfolioWorkImage::where('portfolio_work_id', $work->id)->max('sort_order') ?? 0;
        $sortOrder = $maxSortOrder + 1;
        
        echo "Sort order calculado: {$sortOrder}\n";
        
        // Dados para salvar no banco (exatamente como no método original)
        $imageRecord = [
            'portfolio_work_id' => $work->id,
            'filename' => $imageData['filename'],
            'original_name' => $imageData['original_name'],
            'path' => $imageData['path'],
            'file_size' => 1024, // Fake size
            'mime_type' => 'image/jpeg',
            'width' => 800,
            'height' => 600,
            'sort_order' => $sortOrder,
            'is_featured' => false
        ];
        
        echo "Dados para salvar: " . json_encode($imageRecord) . "\n";
        
        try {
            $savedImage = PortfolioWorkImage::create($imageRecord);
            echo "Imagem salva com sucesso! ID: {$savedImage->id}\n";
        } catch (Exception $e) {
            echo "ERRO ao salvar imagem: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString() . "\n";
        }
    }
    
    // Verificar quantas imagens existem para este trabalho
    $imageCount = PortfolioWorkImage::where('portfolio_work_id', $work->id)->count();
    echo "Total de imagens para o trabalho {$work->id}: {$imageCount}\n";
    
    // Listar todas as imagens
    $images = PortfolioWorkImage::where('portfolio_work_id', $work->id)->get();
    foreach ($images as $img) {
        echo "- Imagem ID {$img->id}: {$img->filename} (sort_order: {$img->sort_order})\n";
    }
    
} catch (Exception $e) {
    echo "ERRO GERAL: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}