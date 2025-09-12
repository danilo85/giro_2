<?php

require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PortfolioWorkImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

echo "=== LIMPEZA DE ARQUIVOS ÓRFÃOS ===\n\n";

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
        } else {
            echo "   ✓ OK: {$relativePath}\n";
        }
    }
    
    echo "\n";
}

echo "2. RESUMO DA ANÁLISE:\n";
echo "   Total de arquivos órfãos: {$totalOrphans}\n";
echo "   Tamanho total: " . number_format($totalSize) . " bytes (" . number_format($totalSize / 1024, 2) . " KB)\n\n";

if ($totalOrphans > 0) {
    echo "3. ARQUIVOS ÓRFÃOS ENCONTRADOS:\n";
    foreach ($orphanFiles as $index => $orphan) {
        echo "   " . ($index + 1) . ". {$orphan['path']}\n";
        echo "      Tamanho: " . number_format($orphan['size']) . " bytes\n";
        echo "      Modificado: {$orphan['modified']}\n";
        echo "      Caminho completo: {$orphan['full_path']}\n\n";
    }
    
    echo "4. DESEJA REMOVER OS ARQUIVOS ÓRFÃOS? (s/n): ";
    $handle = fopen("php://stdin", "r");
    $response = trim(fgets($handle));
    fclose($handle);
    
    if (strtolower($response) === 's' || strtolower($response) === 'sim') {
        echo "\n5. REMOVENDO ARQUIVOS ÓRFÃOS:\n";
        $removedCount = 0;
        $removedSize = 0;
        
        foreach ($orphanFiles as $orphan) {
            if (file_exists($orphan['full_path'])) {
                if (unlink($orphan['full_path'])) {
                    echo "   ✓ Removido: {$orphan['path']}\n";
                    $removedCount++;
                    $removedSize += $orphan['size'];
                } else {
                    echo "   ✗ Erro ao remover: {$orphan['path']}\n";
                }
            } else {
                echo "   ⚠ Arquivo já não existe: {$orphan['path']}\n";
            }
        }
        
        echo "\n   RESUMO DA REMOÇÃO:\n";
        echo "   Arquivos removidos: {$removedCount}/{$totalOrphans}\n";
        echo "   Espaço liberado: " . number_format($removedSize) . " bytes (" . number_format($removedSize / 1024, 2) . " KB)\n";
    } else {
        echo "\n   Operação cancelada. Nenhum arquivo foi removido.\n";
    }
} else {
    echo "3. ✓ NENHUM ARQUIVO ÓRFÃO ENCONTRADO!\n";
    echo "   Todos os arquivos no storage estão devidamente registrados no banco de dados.\n";
}

echo "\n=== LIMPEZA CONCLUÍDA ===\n";