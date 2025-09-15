<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Monitorando sessões...\n";
echo "Pressione Ctrl+C para parar\n\n";

$lastCount = 0;

while (true) {
    try {
        $count = DB::table('sessions')->count();
        
        if ($count !== $lastCount) {
            echo "[" . date('Y-m-d H:i:s') . "] Mudança detectada! Total de sessões: $count\n";
            
            // Mostrar últimas sessões
            $sessions = DB::table('sessions')
                ->orderBy('last_activity', 'desc')
                ->limit(3)
                ->get(['id', 'user_id', 'ip_address', 'last_activity']);
            
            foreach ($sessions as $session) {
                $userId = $session->user_id ?: 'Não logado';
                echo "  - ID: " . substr($session->id, 0, 10) . "..., User ID: $userId, IP: {$session->ip_address}, Last Activity: " . date('Y-m-d H:i:s', $session->last_activity) . "\n";
            }
            echo "\n";
            
            $lastCount = $count;
        } else {
            echo "[" . date('Y-m-d H:i:s') . "] Total de sessões: $count (sem mudanças)\n";
        }
        
        sleep(5); // Aguarda 5 segundos
        
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage() . "\n";
        break;
    }
}