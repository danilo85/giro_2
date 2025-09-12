<?php

require_once 'vendor/autoload.php';

// Simular ambiente Laravel
$_ENV['APP_ENV'] = 'local';
$_ENV['APP_DEBUG'] = 'true';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\PortfolioController;
use App\Models\PortfolioWork;
use App\Models\PortfolioCategory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

echo "=== TESTE DE UPLOAD DE PORTFÓLIO ===\n\n";

// Criar aplicação Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Simular autenticação
    Auth::loginUsingId(1);
    echo "✅ Usuário autenticado: " . Auth::id() . "\n";
    
    // Verificar se existe categoria
    $category = PortfolioCategory::where('user_id', 1)->first();
    if (!$category) {
        echo "❌ Nenhuma categoria encontrada para o usuário\n";
        exit;
    }
    echo "✅ Categoria encontrada: {$category->name} (ID: {$category->id})\n";
    
    // Criar arquivo de teste
    $testImagePath = storage_path('app/test_image.jpg');
    if (!file_exists($testImagePath)) {
        // Criar uma imagem simples de teste
        $image = imagecreate(100, 100);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        imagestring($image, 5, 10, 40, 'TEST', $black);
        imagejpeg($image, $testImagePath);
        imagedestroy($image);
    }
    echo "✅ Arquivo de teste criado: $testImagePath\n";
    
    // Simular UploadedFile
    $uploadedFile = new UploadedFile(
        $testImagePath,
        'test_image.jpg',
        'image/jpeg',
        null,
        true // test mode
    );
    
    echo "✅ UploadedFile criado: {$uploadedFile->getClientOriginalName()}\n";
    echo "   - Tamanho: {$uploadedFile->getSize()} bytes\n";
    echo "   - MIME: {$uploadedFile->getMimeType()}\n";
    echo "   - Válido: " . ($uploadedFile->isValid() ? 'Sim' : 'Não') . "\n";
    
    // Criar request simulado
    $requestData = [
        'title' => 'Teste de Upload',
        'slug' => 'teste-upload-' . time(),
        'description' => 'Teste de upload de imagens',
        'portfolio_category_id' => $category->id,
        'status' => 'draft',
        'is_featured' => false
    ];
    
    $request = Request::create('/portfolio/works', 'POST', $requestData);
    $request->files->set('images', [$uploadedFile]);
    
    echo "\n=== INICIANDO TESTE DE UPLOAD ===\n";
    
    // Testar controller
    $controller = new PortfolioController();
    
    DB::beginTransaction();
    
    try {
        echo "📤 Chamando método store do controller...\n";
        $response = $controller->store($request);
        
        echo "✅ Upload realizado com sucesso!\n";
        echo "   - Tipo de resposta: " . get_class($response) . "\n";
        
        // Verificar se o trabalho foi criado
        $work = PortfolioWork::where('slug', $requestData['slug'])->first();
        if ($work) {
            echo "✅ Trabalho criado no banco: ID {$work->id}\n";
            echo "   - Título: {$work->title}\n";
            echo "   - Imagem destacada: " . ($work->featured_image ?: 'Nenhuma') . "\n";
            echo "   - Imagens adicionais: {$work->images()->count()}\n";
            
            // Listar imagens
            foreach ($work->images as $image) {
                echo "     * {$image->filename} ({$image->file_size} bytes)\n";
                echo "       Caminho: {$image->path}\n";
                echo "       Existe arquivo: " . (file_exists(storage_path('app/public/' . $image->path)) ? 'Sim' : 'Não') . "\n";
            }
        } else {
            echo "❌ Trabalho não foi criado no banco\n";
        }
        
        DB::rollback(); // Não salvar no teste
        echo "\n🔄 Transação revertida (teste)\n";
        
    } catch (Exception $e) {
        DB::rollback();
        echo "❌ Erro durante upload: " . $e->getMessage() . "\n";
        echo "   - Arquivo: {$e->getFile()}:{$e->getLine()}\n";
        echo "   - Stack trace:\n" . $e->getTraceAsString() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro geral: " . $e->getMessage() . "\n";
    echo "   - Arquivo: {$e->getFile()}:{$e->getLine()}\n";
}

echo "\n=== TESTE FINALIZADO ===\n";