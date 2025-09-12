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

echo "=== VERIFICAÇÃO DE ARQUIVOS FALTANTES ===\n\n";

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
    
    echo "5. OPÇÕES DE CORREÇÃO:\n";
    echo "   a) Remover registros do banco (recomendado para arquivos perdidos)\n";
    echo "   b) Tentar recriar arquivos placeholder\n";
    echo "   c) Manter registros para investigação manual\n\n";
    
    echo "   Escolha uma opção (a/b/c) ou pressione Enter para pular: ";
    $handle = fopen("php://stdin", "r");
    $choice = trim(fgets($handle));
    fclose($handle);
    
    switch (strtolower($choice)) {
        case 'a':
            echo "\n6. REMOVENDO REGISTROS DO BANCO:\n";
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
            break;
            
        case 'b':
            echo "\n6. CRIANDO ARQUIVOS PLACEHOLDER:\n";
            $createdCount = 0;
            
            // Criar uma imagem placeholder simples
            $placeholderContent = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/8A');
            
            foreach ($missingFiles as $missing) {
                $fullPath = storage_path('app/public/' . $missing['path']);
                $directory = dirname($fullPath);
                
                // Criar diretório se não existir
                if (!is_dir($directory)) {
                    mkdir($directory, 0755, true);
                }
                
                // Criar arquivo placeholder
                if (file_put_contents($fullPath, $placeholderContent)) {
                    echo "   ✓ Placeholder criado: {$missing['path']}\n";
                    $createdCount++;
                } else {
                    echo "   ✗ Erro ao criar: {$missing['path']}\n";
                }
            }
            
            echo "\n   RESUMO DA CRIAÇÃO:\n";
            echo "   Placeholders criados: {$createdCount}/" . count($missingFiles) . "\n";
            break;
            
        case 'c':
        default:
            echo "\n6. MANTENDO REGISTROS PARA INVESTIGAÇÃO MANUAL\n";
            echo "   Os registros foram mantidos no banco de dados.\n";
            echo "   Você pode investigar manualmente ou executar este script novamente.\n";
            break;
    }
} else {
    echo "4. ✓ TODOS OS ARQUIVOS ESTÃO PRESENTES!\n";
    echo "   Não há arquivos faltantes no sistema.\n";
}

// Verificar trabalhos sem imagens
echo "\n7. VERIFICANDO TRABALHOS SEM IMAGENS:\n";
$worksWithoutImages = PortfolioWork::doesntHave('images')->get();

if ($worksWithoutImages->count() > 0) {
    echo "   ⚠ Trabalhos sem imagens: {$worksWithoutImages->count()}\n";
    foreach ($worksWithoutImages as $work) {
        echo "     - {$work->title} (ID: {$work->id})\n";
    }
} else {
    echo "   ✓ Todos os trabalhos possuem pelo menos uma imagem.\n";
}

echo "\n=== VERIFICAÇÃO CONCLUÍDA ===\n";