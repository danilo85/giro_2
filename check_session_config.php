<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Configuração de Sessão ===".PHP_EOL;
echo "SESSION_DRIVER (.env): ".env('SESSION_DRIVER').PHP_EOL;
echo "session.driver (config): ".config('session.driver').PHP_EOL;
echo "session.table (config): ".config('session.table').PHP_EOL;
echo "session.connection (config): ".config('session.connection').PHP_EOL;
echo "session.lifetime (config): ".config('session.lifetime').PHP_EOL;
echo "session.encrypt (config): ".(config('session.encrypt') ? 'true' : 'false').PHP_EOL;
echo "session.cookie (config): ".config('session.cookie').PHP_EOL;

echo "\n=== Verificando Tabela Sessions ===".PHP_EOL;

use Illuminate\Support\Facades\DB;

try {
    $sessionCount = DB::table('sessions')->count();
    echo "Total de registros na tabela sessions: ".$sessionCount.PHP_EOL;
    
    $latestSessions = DB::table('sessions')
        ->orderBy('last_activity', 'desc')
        ->limit(3)
        ->get();
    
    if ($latestSessions->count() > 0) {
        echo "\nÚltimas sessões na tabela:".PHP_EOL;
        foreach ($latestSessions as $session) {
            echo "  - ID: ".substr($session->id, 0, 10).'...'.PHP_EOL;
            echo "    User ID: ".($session->user_id ?: 'Não logado').PHP_EOL;
            echo "    IP: ".$session->ip_address.PHP_EOL;
            echo "    Last Activity: ".date('Y-m-d H:i:s', $session->last_activity).PHP_EOL;
            echo "    Payload length: ".strlen($session->payload)." chars".PHP_EOL;
            echo "\n";
        }
    }
} catch (Exception $e) {
    echo "Erro ao acessar tabela sessions: ".$e->getMessage().PHP_EOL;
}

echo "\n=== Testando Laravel Session Manager ===".PHP_EOL;

try {
    // Usar o session manager do Laravel
    $sessionManager = app('session');
    echo "Session manager class: ".get_class($sessionManager).PHP_EOL;
    
    $driver = $sessionManager->getDefaultDriver();
    echo "Default driver: ".$driver.PHP_EOL;
    
    // Tentar criar uma nova sessão
    $sessionStore = $sessionManager->driver();
    echo "Session store class: ".get_class($sessionStore).PHP_EOL;
    
} catch (Exception $e) {
    echo "Erro com session manager: ".$e->getMessage().PHP_EOL;
}

echo "\n✓ Verificação de configuração concluída!".PHP_EOL;