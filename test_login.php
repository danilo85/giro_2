<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

try {
    echo "Testando sistema de sessões...\n\n";
    
    // Verificar sessões antes do login
    $sessionsBefore = DB::table('sessions')->count();
    echo "Sessões antes do teste: $sessionsBefore\n";
    
    // Pegar um usuário para teste
    $user = User::first();
    if (!$user) {
        echo "Nenhum usuário encontrado!\n";
        exit(1);
    }
    
    echo "Usuário para teste: {$user->email}\n";
    
    // Iniciar uma nova sessão
    Session::start();
    $sessionId = Session::getId();
    echo "ID da sessão criada: $sessionId\n";
    
    // Simular login
    Auth::login($user);
    echo "Login realizado para: {$user->name}\n";
    
    // Verificar se a sessão foi atualizada
    $sessionData = DB::table('sessions')->where('id', $sessionId)->first();
    if ($sessionData) {
        echo "Sessão encontrada no banco:\n";
        echo "- ID: {$sessionData->id}\n";
        echo "- User ID: {$sessionData->user_id}\n";
        echo "- IP: {$sessionData->ip_address}\n";
        echo "- Last Activity: " . date('Y-m-d H:i:s', $sessionData->last_activity) . "\n";
    } else {
        echo "Sessão não encontrada no banco!\n";
    }
    
    // Verificar sessões após o login
    $sessionsAfter = DB::table('sessions')->count();
    echo "\nSessões após o teste: $sessionsAfter\n";
    
    // Fazer logout
    Auth::logout();
    echo "\nLogout realizado.\n";
    
    // Verificar sessão após logout
    $sessionAfterLogout = DB::table('sessions')->where('id', $sessionId)->first();
    if ($sessionAfterLogout) {
        echo "Sessão após logout:\n";
        echo "- User ID: {$sessionAfterLogout->user_id}\n";
    }
    
    echo "\n✓ Teste de sessões concluído com sucesso!\n";
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}