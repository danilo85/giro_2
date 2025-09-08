<?php

require_once 'vendor/autoload.php';

// Carregar configuração do Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    // Verificar se a coluna status existe
    $hasStatusColumn = Schema::hasColumn('pagamentos', 'status');
    echo "Coluna 'status' existe na tabela 'pagamentos': " . ($hasStatusColumn ? 'SIM' : 'NÃO') . "\n";
    
    if ($hasStatusColumn) {
        // Verificar a estrutura da coluna
        $columns = DB::select("DESCRIBE pagamentos");
        foreach ($columns as $column) {
            if ($column->Field === 'status') {
                echo "Tipo da coluna status: {$column->Type}\n";
                echo "Valor padrão: {$column->Default}\n";
                echo "Permite NULL: {$column->Null}\n";
                break;
            }
        }
    }
    
    // Verificar se há registros com problemas no status
    $problematicRecords = DB::table('pagamentos')
        ->whereNotIn('status', ['pendente', 'processando', 'confirmado', 'cancelado'])
        ->orWhereNull('status')
        ->count();
    
    echo "Registros com status problemático: {$problematicRecords}\n";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}