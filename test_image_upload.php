<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PortfolioWork;
use App\Models\PortfolioWorkImage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

echo "=== TESTE DE UPLOAD DE IMAGENS ===\n\n";

// 1. Verificar se a tabela existe
echo "1. Verificando se a tabela portfolio_works_images existe...\n";
try {
    $tableExists = DB::getSchemaBuilder()->hasTable('portfolio_works_images');
    echo $tableExists ? "✓ Tabela existe\n" : "✗ Tabela NÃO existe\n";
} catch (Exception $e) {
    echo "✗ Erro ao verificar tabela: " . $e->getMessage() . "\n";
}

// 2. Verificar estrutura da tabela
echo "\n2. Verificando estrutura da tabela...\n";
try {
    $columns = DB::getSchemaBuilder()->getColumnListing('portfolio_works_images');
    echo "Colunas encontradas: " . implode(', ', $columns) . "\n";
} catch (Exception $e) {
    echo "✗ Erro ao verificar estrutura: " . $e->getMessage() . "\n";
}

// 3. Verificar se existe algum PortfolioWork para teste
echo "\n3. Verificando PortfolioWorks existentes...\n";
$portfolioWork = PortfolioWork::first();
if ($portfolioWork) {
    echo "✓ PortfolioWork encontrado: ID {$portfolioWork->id} - {$portfolioWork->title}\n";
} else {
    echo "✗ Nenhum PortfolioWork encontrado\n";
    echo "Criando um PortfolioWork de teste...\n";
    
    try {
        $portfolioWork = PortfolioWork::create([
            'title' => 'Teste Upload Imagens',
            'description' => 'Projeto de teste para upload de imagens',
            'portfolio_category_id' => 1,
            'user_id' => 1,
            'client_id' => null,
            'status' => 'published'
        ]);
        echo "✓ PortfolioWork criado: ID {$portfolioWork->id}\n";
    } catch (Exception $e) {
        echo "✗ Erro ao criar PortfolioWork: " . $e->getMessage() . "\n";
        exit(1);
    }
}

// 4. Testar criação manual de imagem
echo "\n4. Testando criação manual de PortfolioWorkImage...\n";
try {
    $imageData = [
        'portfolio_work_id' => $portfolioWork->id,
        'filename' => 'test_image.jpg',
        'path' => 'portfolio/test_image.jpg',
        'original_name' => 'test_image_original.jpg',
        'mime_type' => 'image/jpeg',
        'size' => 1024000,
        'is_featured' => false,
        'sort_order' => 1
    ];
    
    echo "Dados da imagem: " . json_encode($imageData, JSON_PRETTY_PRINT) . "\n";
    
    $image = PortfolioWorkImage::create($imageData);
    echo "✓ Imagem criada com sucesso: ID {$image->id}\n";
    
    // Verificar se foi salva no banco
    $savedImage = PortfolioWorkImage::find($image->id);
    if ($savedImage) {
        echo "✓ Imagem confirmada no banco: {$savedImage->filename}\n";
    } else {
        echo "✗ Imagem NÃO encontrada no banco após criação\n";
    }
    
} catch (Exception $e) {
    echo "✗ Erro ao criar imagem: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

// 5. Verificar relacionamento
echo "\n5. Verificando relacionamento PortfolioWork -> Images...\n";
try {
    $images = $portfolioWork->images;
    echo "Número de imagens relacionadas: " . $images->count() . "\n";
    
    foreach ($images as $img) {
        echo "- Imagem ID {$img->id}: {$img->filename}\n";
    }
} catch (Exception $e) {
    echo "✗ Erro ao verificar relacionamento: " . $e->getMessage() . "\n";
}

// 6. Verificar todas as imagens na tabela
echo "\n6. Verificando todas as imagens na tabela...\n";
try {
    $allImages = PortfolioWorkImage::all();
    echo "Total de imagens na tabela: " . $allImages->count() . "\n";
    
    foreach ($allImages as $img) {
        echo "- ID {$img->id}: {$img->filename} (Portfolio: {$img->portfolio_work_id})\n";
    }
} catch (Exception $e) {
    echo "✗ Erro ao listar imagens: " . $e->getMessage() . "\n";
}

echo "\n=== FIM DO TESTE ===\n";