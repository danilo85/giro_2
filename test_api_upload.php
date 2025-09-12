<?php

require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PortfolioWork;
use App\Models\PortfolioCategory;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

echo "=== TESTE DE API: UPLOAD DE MÚLTIPLAS IMAGENS ===\n\n";

try {
    // 1. Preparar dados de teste
    echo "1. PREPARANDO DADOS DE TESTE:\n";
    
    $user = User::first();
    if (!$user) {
        throw new Exception('Nenhum usuário encontrado');
    }
    echo "✓ Usuário: {$user->name} (ID: {$user->id})\n";
    
    $category = PortfolioCategory::first();
    if (!$category) {
        throw new Exception('Nenhuma categoria encontrada');
    }
    echo "✓ Categoria: {$category->name} (ID: {$category->id})\n";
    
    // 2. Criar imagens de teste reais
    echo "\n2. CRIANDO IMAGENS DE TESTE REAIS:\n";
    
    $testImages = [];
    $tempDir = sys_get_temp_dir();
    
    for ($i = 1; $i <= 3; $i++) {
        $filename = "api_test_image_{$i}.jpg";
        $tempPath = $tempDir . DIRECTORY_SEPARATOR . $filename;
        
        // Criar uma imagem simples de 100x100 pixels
        $imageContent = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/wA==');
        
        if (file_put_contents($tempPath, $imageContent) === false) {
            throw new Exception("Erro ao criar imagem de teste: {$filename}");
        }
        
        // Criar UploadedFile simulado
        $uploadedFile = new UploadedFile(
            $tempPath,
            $filename,
            'image/jpeg',
            null,
            true // test mode
        );
        
        $testImages[] = $uploadedFile;
        echo "✓ Imagem criada: {$filename} ({$uploadedFile->getSize()} bytes)\n";
    }
    
    // 3. Simular requisição HTTP
    echo "\n3. SIMULANDO REQUISIÇÃO HTTP:\n";
    
    $requestData = [
        'title' => 'Teste API - Múltiplas Imagens - ' . date('Y-m-d H:i:s'),
        'description' => 'Teste de upload de múltiplas imagens via API para diagnosticar problemas.',
        'portfolio_category_id' => $category->id,
        'technologies' => ['PHP', 'Laravel', 'API', 'Testing'],
        'status' => 'published',
        'meta_title' => 'Teste API Meta Title',
        'meta_description' => 'Teste API Meta Description'
    ];
    
    echo "✓ Dados da requisição preparados\n";
    echo "✓ Número de imagens: " . count($testImages) . "\n";
    
    // 4. Instanciar controller e testar método store
    echo "\n4. TESTANDO CONTROLLER STORE:\n";
    
    $controller = new App\Http\Controllers\PortfolioController();
    
    // Simular Request
    $request = new Illuminate\Http\Request();
    $request->merge($requestData);
    
    // Adicionar arquivos à requisição
    $files = [];
    foreach ($testImages as $index => $image) {
        $files["images.{$index}"] = $image;
    }
    $request->files->add(['images' => $testImages]);
    
    // Forçar resposta JSON
    $request->headers->set('Accept', 'application/json');
    $request->headers->set('X-Requested-With', 'XMLHttpRequest');
    
    echo "✓ Request simulada criada\n";
    echo "✓ Arquivos adicionados à request: " . count($request->file('images')) . "\n";
    echo "✓ Headers JSON configurados\n";
    
    // 5. Executar método store
    echo "\n5. EXECUTANDO MÉTODO STORE:\n";
    
    DB::beginTransaction();
    
    try {
        // Simular autenticação
        auth()->login($user);
        
        $response = $controller->store($request);
        
        echo "✓ Método store executado com sucesso\n";
        
        // Verificar resposta
        if ($response instanceof Illuminate\Http\JsonResponse) {
            $responseData = $response->getData(true);
            echo "✓ Resposta JSON recebida\n";
            echo "✓ Status: {$response->getStatusCode()}\n";
            
            echo "Resposta completa: " . json_encode($responseData, JSON_PRETTY_PRINT) . "\n";
            
            if (isset($responseData['success']) && $responseData['success']) {
                echo "✓ Trabalho criado com sucesso\n";
                
                // Verificar se há dados do trabalho na resposta
                $workId = null;
                if (isset($responseData['work_id'])) {
                    $workId = $responseData['work_id'];
                } elseif (isset($responseData['work']['id'])) {
                    $workId = $responseData['work']['id'];
                }
                
                if ($workId) {
                    echo "✓ ID do trabalho: {$workId}\n";
                } else {
                    echo "✗ ID do trabalho não encontrado na resposta\n";
                    // Buscar o último trabalho criado
                    $lastWork = PortfolioWork::latest()->first();
                    if ($lastWork && $lastWork->title === $requestData['title']) {
                        $workId = $lastWork->id;
                        echo "✓ Trabalho encontrado por título: ID {$workId}\n";
                    }
                }
                
                if ($workId) {
                    // Verificar trabalho no banco
                    $work = PortfolioWork::with('images')->find($workId);
                    if ($work) {
                        echo "✓ Trabalho encontrado no banco: {$work->title}\n";
                        echo "✓ Número de imagens associadas: {$work->images->count()}\n";
                        
                        foreach ($work->images as $index => $image) {
                            echo "  - Imagem " . ($index + 1) . ": {$image->filename}\n";
                            echo "    Path: {$image->path}\n";
                            
                            $fullPath = storage_path('app/public/' . $image->path);
                            if (file_exists($fullPath)) {
                                echo "    ✓ Arquivo físico existe\n";
                            } else {
                                echo "    ✗ Arquivo físico NÃO existe: {$fullPath}\n";
                            }
                        }
                    } else {
                        echo "✗ Trabalho NÃO encontrado no banco\n";
                    }
                }
            } else {
                echo "✗ Resposta indica falha\n";
                echo "Dados da resposta: " . json_encode($responseData, JSON_PRETTY_PRINT) . "\n";
            }
        } else {
            echo "✗ Resposta não é JSON\n";
            echo "Tipo da resposta: " . get_class($response) . "\n";
        }
        
        DB::commit();
        echo "✓ Transação commitada\n";
        
    } catch (Exception $e) {
        DB::rollback();
        echo "✗ Erro durante execução: {$e->getMessage()}\n";
        echo "Stack trace: {$e->getTraceAsString()}\n";
        throw $e;
    }
    
    // 6. Limpeza
    echo "\n6. LIMPEZA:\n";
    foreach ($testImages as $image) {
        if (file_exists($image->getPathname())) {
            unlink($image->getPathname());
            echo "✓ Arquivo temporário removido: {$image->getClientOriginalName()}\n";
        }
    }
    
} catch (Exception $e) {
    echo "\n✗ ERRO GERAL: {$e->getMessage()}\n";
    echo "Arquivo: {$e->getFile()}\n";
    echo "Linha: {$e->getLine()}\n";
    echo "Stack trace: {$e->getTraceAsString()}\n";
}

echo "\n=== TESTE DE API CONCLUÍDO ===\n";