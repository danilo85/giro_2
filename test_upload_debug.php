<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\PortfolioWork;
use App\Models\PortfolioWorkImage;
use App\Helpers\FileUploadHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

echo "=== TESTE DE UPLOAD DE MÚLTIPLAS IMAGENS ===\n\n";

// Verificar se existe um trabalho para testar
$work = PortfolioWork::first();
if (!$work) {
    echo "❌ Nenhum trabalho encontrado no banco de dados\n";
    exit(1);
}

echo "✅ Trabalho encontrado: ID {$work->id} - {$work->title}\n\n";

// Simular múltiplos arquivos de upload
$testFiles = [
    [
        'name' => 'test-image-1.jpg',
        'content' => base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/wA==')
    ],
    [
        'name' => 'test-image-2.jpg', 
        'content' => base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/wA==')
    ],
    [
        'name' => 'test-image-3.jpg',
        'content' => base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/wA==')
    ]
];

echo "📁 Criando arquivos temporários...\n";

$uploadedFiles = [];
foreach ($testFiles as $index => $fileData) {
    $tempPath = sys_get_temp_dir() . '/' . $fileData['name'];
    file_put_contents($tempPath, $fileData['content']);
    
    $uploadedFiles[] = new UploadedFile(
        $tempPath,
        $fileData['name'],
        'image/jpeg',
        null,
        true // test mode
    );
    
    echo "  ✅ Arquivo criado: {$fileData['name']}\n";
}

echo "\n🔄 Simulando o método uploadImages do PortfolioController...\n\n";

$savedImages = [];
$errors = [];

foreach ($uploadedFiles as $index => $file) {
    echo "📷 Processando imagem " . ($index + 1) . ": {$file->getClientOriginalName()}\n";
    
    try {
        // Validação básica
        if (!$file->isValid()) {
            throw new Exception("Arquivo inválido: {$file->getClientOriginalName()}");
        }
        
        // Usar o FileUploadHelper para salvar
        $uploadPath = FileUploadHelper::storeFile($file, 'portfolio');
        
        if (!$uploadPath) {
            throw new Exception("Erro no upload do arquivo");
        }
        
        echo "  ✅ Upload realizado: {$uploadPath}\n";
        
        // Tentar salvar no banco de dados
        $filename = basename($uploadPath);
        $imageData = [
            'portfolio_work_id' => $work->id,
            'filename' => $filename,
            'path' => $uploadPath,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'width' => 100, // Simulado
            'height' => 100, // Simulado
            'is_featured' => $index === 0 ? 1 : 0,
            'sort_order' => $index + 1
        ];
        
        echo "  💾 Tentando salvar no banco...\n";
        echo "  📊 Dados: " . json_encode($imageData, JSON_PRETTY_PRINT) . "\n";
        
        $portfolioImage = PortfolioWorkImage::create($imageData);
        
        if ($portfolioImage) {
            echo "  ✅ Imagem salva no banco com ID: {$portfolioImage->id}\n";
            $savedImages[] = $portfolioImage;
        } else {
            throw new Exception("Falha ao criar registro no banco");
        }
        
    } catch (Exception $e) {
        echo "  ❌ Erro: " . $e->getMessage() . "\n";
        $errors[] = $e->getMessage();
        
        // Log do erro
        Log::error("Erro no upload de imagem", [
            'file' => $file->getClientOriginalName(),
            'error' => $e->getMessage(),
            'work_id' => $work->id
        ]);
    }
    
    echo "\n";
}

echo "=== RESULTADO FINAL ===\n";
echo "✅ Imagens salvas com sucesso: " . count($savedImages) . "\n";
echo "❌ Erros encontrados: " . count($errors) . "\n\n";

if (count($errors) > 0) {
    echo "📋 Lista de erros:\n";
    foreach ($errors as $error) {
        echo "  - {$error}\n";
    }
    echo "\n";
}

// Verificar o que foi salvo no banco
echo "🔍 Verificando imagens no banco para o trabalho {$work->id}:\n";
$imagesInDb = PortfolioWorkImage::where('portfolio_work_id', $work->id)->get();
foreach ($imagesInDb as $img) {
    echo "  📷 ID: {$img->id} | Arquivo: {$img->filename} | Featured: " . ($img->is_featured ? 'Sim' : 'Não') . "\n";
}

echo "\n✅ Teste concluído!\n";