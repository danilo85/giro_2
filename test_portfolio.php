<?php

// Teste simples para verificar se as tabelas e campos estão corretos

require_once 'vendor/autoload.php';

// Simular o ambiente Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PortfolioWork;
use App\Models\PortfolioWorkImage;
use App\Models\PortfolioCategory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== TESTE DE ESTRUTURA DAS TABELAS ===\n\n";

// Verificar se as tabelas existem
echo "1. Verificando se as tabelas existem:\n";
echo "- portfolio_works: " . (Schema::hasTable('portfolio_works') ? 'OK' : 'ERRO') . "\n";
echo "- portfolio_work_images: " . (Schema::hasTable('portfolio_work_images') ? 'OK' : 'ERRO') . "\n";
echo "- portfolio_categories: " . (Schema::hasTable('portfolio_categories') ? 'OK' : 'ERRO') . "\n\n";

// Verificar colunas da tabela portfolio_works
echo "2. Verificando colunas da tabela portfolio_works:\n";
$columns = Schema::getColumnListing('portfolio_works');
foreach (['title', 'slug', 'description', 'content', 'portfolio_category_id', 'client', 'project_url', 'completion_date', 'technologies', 'featured_image', 'meta_title', 'meta_description', 'status', 'is_featured', 'user_id'] as $column) {
    echo "- $column: " . (in_array($column, $columns) ? 'OK' : 'ERRO') . "\n";
}

echo "\n3. Verificando colunas da tabela portfolio_work_images:\n";
$imageColumns = Schema::getColumnListing('portfolio_work_images');
foreach (['portfolio_work_id', 'filename', 'original_name', 'path', 'alt_text', 'caption', 'size', 'mime_type', 'sort_order', 'is_featured'] as $column) {
    echo "- $column: " . (in_array($column, $imageColumns) ? 'OK' : 'ERRO') . "\n";
}

echo "\n=== TESTE CONCLUÍDO ===\n";