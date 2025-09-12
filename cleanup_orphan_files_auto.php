<?php

require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PortfolioWorkImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

echo "=== LIMPEZA AUTOMÁTICA DE ARQUIVOS ÓRFÃOS ===\n\n";

// Diretórios para verificar
$directories = [
    'portfolio/works',
    'test_uploads'
];

$totalOrphans = 0;
$totalSize = 0;
$orphanFiles = [];

foreach ($directories as $directory) {
    echo "1. VERIFICANDO DIRETÓRIO: {$directory}\n";
    
    $storagePath = storage_path('app/public/' . $directory);
    
    if (!is_dir($storagePath)) {
        echo "   ✗ Diretório não existe: {$storagePath}\n";
        continue;
    }
    
    // Listar todos os arquivos no diretório
    $files = File::allFiles($storagePath);
    echo "   ✓ Arquivos encontrados: " . count($files) . "\n";
    
    foreach ($files as $file) {
        $relativePath = str_replace(storage_path('app/public/'), '', $file->getPathname());
        $relativePath = str_replace('\\', '/', $relativePath); // Normalizar separadores
        
        // Verificar se o arquivo está registrado no banco
        $imageExists = PortfolioWorkImage::where('path', $relativePath)->exists();
        
        if (!$imageExists) {
            $fileSize = $file->getSize();
            $totalOrphans++;
            $totalSize += $fileSize;
            
            $orphanFiles[] = [
                'path' => $relativePath,
                'full_path' => $file->getPathname(),
                'size' => $fileSize,
                'modified' => date('Y-m-d H:i:s', $file->getMTime())
            ];
            
            echo "   ✗ ÓRFÃO: {$relativePath} (" . number_format($fileSize) . " bytes)\n";
        }
    }
    
    echo "\n";
}

echo "2. RESUMO DA ANÁLISE:\n";
echo "   Total de arquivos órfãos: {$totalOrphans}\n";
echo "   Tamanho total: " . number_format($totalSize) . " bytes (" . number_format($totalSize / 1024, 2) . " KB)\n\n";

if ($totalOrphans > 0) {
    echo "3. REMOVENDO ARQUIVOS ÓRFÃOS AUTOMATICAMENTE:\n";
    $removedCount = 0;
    $removedSize = 0;
    
    foreach ($orphanFiles as $orphan) {
        if (file_exists($orphan['full_path'])) {
            if (unlink($orphan['full_path'])) {
                echo "   ✓ Removido: {$orphan['path']} (" . number_format($orphan['size']) . " bytes)\n";
                $removedCount++;
                $removedSize += $orphan['size'];
            } else {
                echo "   ✗ Erro ao remover: {$orphan['path']}\n";
            }
        } else {
            echo "   ⚠ Arquivo já não existe: {$orphan['path']}\n";
        }
    }
    
    echo "\n4. RESUMO DA REMOÇÃO:\n";
    echo "   Arquivos removidos: {$removedCount}/{$totalOrphans}\n";
    echo "   Espaço liberado: " . number_format($removedSize) . " bytes (" . number_format($removedSize / 1024, 2) . " KB)\n";
    
    if ($removedCount === $totalOrphans) {
        echo "   ✓ TODOS OS ARQUIVOS ÓRFÃOS FORAM REMOVIDOS COM SUCESSO!\n";
    } else {
        echo "   ⚠ Alguns arquivos não puderam ser removidos.\n";
    }
} else {
    echo "3. ✓ NENHUM ARQUIVO ÓRFÃO ENCONTRADO!\n";
    echo "   Todos os arquivos no storage estão devidamente registrados no banco de dados.\n";
}

echo "\n=== LIMPEZA AUTOMÁTICA CONCLUÍDA ===\n";