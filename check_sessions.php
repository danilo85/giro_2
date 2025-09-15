<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    echo "Verificando tabela sessions...\n";
    
    // Verificar se a tabela existe
    if (Schema::hasTable('sessions')) {
        echo "✓ Tabela sessions existe\n";
        
        // Mostrar colunas
        $columns = Schema::getColumnListing('sessions');
        echo "\nColunas da tabela sessions:\n";
        foreach ($columns as $column) {
            echo "- $column\n";
        }
        
        // Contar registros
        $count = DB::table('sessions')->count();
        echo "\nTotal de registros na tabela sessions: $count\n";
        
        // Mostrar alguns registros se existirem
        if ($count > 0) {
            echo "\nÚltimos 3 registros:\n";
            $sessions = DB::table('sessions')
                ->orderBy('last_activity', 'desc')
                ->limit(3)
                ->get(['id', 'user_id', 'ip_address', 'last_activity']);
            
            foreach ($sessions as $session) {
                echo "ID: {$session->id}, User ID: {$session->user_id}, IP: {$session->ip_address}, Last Activity: " . date('Y-m-d H:i:s', $session->last_activity) . "\n";
            }
        }
        
    } else {
        echo "✗ Tabela sessions NÃO existe\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}