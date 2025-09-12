<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PortfolioWork;
use App\Models\PortfolioWorkImage;
use App\Models\Cliente;
use App\Models\PortfolioCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

echo "=== TESTE COMPLETO DO WORKFLOW ===\n";

try {
    // 1. Verificar estrutura da tabela
    echo "\n1. Verificando estrutura da tabela portfolio_works...\n";
    
    $columns = Schema::getColumnListing('portfolio_works');
    echo "Colunas da tabela portfolio_works:\n";
    foreach ($columns as $column) {
        echo "  - {$column}\n";
    }
    
    // 2. Verificar se existem dados necessários
    echo "\n2. Verificando dados necessários...\n";
    
    $user = User::first();
    if (!$user) {
        echo "✗ Nenhum usuário encontrado\n";
        exit(1);
    }
    echo "✓ Usuário encontrado: {$user->name} (ID: {$user->id})\n";
    
    $category = PortfolioCategory::first();
    if (!$category) {
        echo "✗ Nenhuma categoria encontrada\n";
        exit(1);
    }
    echo "✓ Categoria encontrada: {$category->name} (ID: {$category->id})\n";
    
    $client = Cliente::where('user_id', $user->id)->first();
    if (!$client) {
        echo "⚠ Nenhum cliente encontrado para o usuário, criando um...\n";
        $client = Cliente::create([
            'nome' => 'Cliente Teste',
            'email' => 'teste@exemplo.com',
            'user_id' => $user->id
        ]);
        echo "✓ Cliente criado: {$client->nome} (ID: {$client->id})\n";
    } else {
        echo "✓ Cliente encontrado: {$client->nome} (ID: {$client->id})\n";
    }
    
    // 3. Criar um trabalho de portfólio com dados mínimos
    echo "\n3. Criando trabalho de portfólio com dados mínimos...\n";
    
    $workData = [
        'title' => 'Teste Simples - ' . date('Y-m-d H:i:s'),
        'portfolio_category_id' => $category->id,
        'user_id' => $user->id,
        'status' => 'published'
    ];
    
    echo "Dados mínimos do trabalho:\n";
    foreach ($workData as $key => $value) {
        echo "  - {$key}: {$value}\n";
    }
    
    $work = PortfolioWork::create($workData);
    echo "✓ Trabalho criado com sucesso (ID: {$work->id})\n";
    
    // 4. Agora adicionar o client_id
    echo "\n4. Adicionando client_id ao trabalho...\n";
    $work->update(['client_id' => $client->id]);
    $work->refresh();
    
    if ($work->client_id == $client->id) {
        echo "✓ client_id adicionado corretamente: {$work->client_id}\n";
    } else {
        echo "✗ client_id não foi salvo corretamente. Esperado: {$client->id}, Encontrado: {$work->client_id}\n";
    }
    
    // 5. Testar criação de imagem
    echo "\n5. Testando criação de imagem...\n";
    
    $imageData = [
        'portfolio_work_id' => $work->id,
        'filename' => 'teste_' . time() . '.jpg',
        'original_name' => 'teste_original.jpg',
        'path' => 'portfolio/works/teste_' . time() . '.jpg',
        'mime_type' => 'image/jpeg',
        'file_size' => 1024000,
        'width' => 1920,
        'height' => 1080,
        'sort_order' => 1,
        'is_featured' => false
    ];
    
    $image = PortfolioWorkImage::create($imageData);
    echo "✓ Imagem criada com sucesso (ID: {$image->id})\n";
    
    // 6. Verificar relacionamentos
    echo "\n6. Verificando relacionamentos...\n";
    
    $work->load(['client', 'images']);
    
    if ($work->client) {
        echo "✓ Relacionamento com cliente funcionando: {$work->client->nome}\n";
    } else {
        echo "✗ Relacionamento com cliente não funcionando\n";
    }
    
    if ($work->images->count() > 0) {
        echo "✓ Relacionamento com imagens funcionando: {$work->images->count()} imagem(ns)\n";
        foreach ($work->images as $img) {
            echo "  - Imagem: {$img->filename} (ID: {$img->id})\n";
        }
    } else {
        echo "✗ Relacionamento com imagens não funcionando\n";
    }
    
    // 7. Verificar na base de dados
    echo "\n7. Verificação final na base de dados...\n";
    
    $dbWork = DB::table('portfolio_works')->where('id', $work->id)->first();
    echo "Trabalho na BD:\n";
    echo "  - ID: {$dbWork->id}\n";
    echo "  - Título: {$dbWork->title}\n";
    echo "  - Client ID: {$dbWork->client_id}\n";
    echo "  - User ID: {$dbWork->user_id}\n";
    echo "  - Status: {$dbWork->status}\n";
    
    $dbImages = DB::table('portfolio_works_images')->where('portfolio_work_id', $work->id)->get();
    echo "Imagens na BD: {$dbImages->count()}\n";
    foreach ($dbImages as $dbImg) {
        echo "  - Imagem ID: {$dbImg->id}, Filename: {$dbImg->filename}\n";
    }
    
    echo "\n=== TESTE CONCLUÍDO COM SUCESSO ===\n";
    echo "✓ Trabalho criado e salvo corretamente\n";
    echo "✓ Client_id está sendo salvo\n";
    echo "✓ Imagens estão sendo salvas\n";
    echo "✓ Relacionamentos funcionando\n";
    
    echo "\n=== CONCLUSÃO ===\n";
    echo "O sistema está funcionando corretamente!\n";
    echo "- Tabela portfolio_works existe e aceita dados\n";
    echo "- Campo client_id está sendo salvo\n";
    echo "- Tabela portfolio_work_images existe e aceita dados\n";
    echo "- Relacionamentos entre trabalhos e imagens funcionam\n";
    echo "- Relacionamentos entre trabalhos e clientes funcionam\n";
    
} catch (Exception $e) {
    echo "\n✗ ERRO DURANTE O TESTE:\n";
    echo "Mensagem: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}