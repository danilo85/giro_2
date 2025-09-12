<?php

require_once 'vendor/autoload.php';

// Configurar Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PortfolioCategory;

try {
    $categories = PortfolioCategory::all(['id', 'name']);
    echo "Categorias encontradas: " . $categories->count() . "\n";
    
    foreach ($categories as $category) {
        echo "ID: {$category->id} - Nome: {$category->name}\n";
    }
    
    if ($categories->count() === 0) {
        echo "Nenhuma categoria encontrada. Criando categoria de teste...\n";
        $testCategory = PortfolioCategory::create([
            'name' => 'Teste',
            'slug' => 'teste'
        ]);
        echo "Categoria criada: ID {$testCategory->id}\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
}