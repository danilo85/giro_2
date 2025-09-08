<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ModeloProposta;
use Illuminate\Support\Facades\Auth;

echo "=== TESTANDO AUTORIZAÇÃO ===\n";

// Simular login do usuário
$user = User::find(1);
if (!$user) {
    echo "Usuário não encontrado!\n";
    exit(1);
}

Auth::login($user);
echo "Usuário logado: {$user->name} (ID: {$user->id})\n";

// Testar acesso aos modelos
echo "\n=== TESTANDO VIEWANY ===\n";
try {
    $canViewAny = Auth::user()->can('viewAny', ModeloProposta::class);
    echo "viewAny: " . ($canViewAny ? 'PERMITIDO' : 'NEGADO') . "\n";
} catch (Exception $e) {
    echo "Erro em viewAny: " . $e->getMessage() . "\n";
}

// Testar acesso a um modelo específico
echo "\n=== TESTANDO VIEW ===\n";
$modelo = ModeloProposta::first();
if ($modelo) {
    echo "Testando modelo ID: {$modelo->id}, User ID: {$modelo->user_id}\n";
    try {
        $canView = Auth::user()->can('view', $modelo);
        echo "view: " . ($canView ? 'PERMITIDO' : 'NEGADO') . "\n";
    } catch (Exception $e) {
        echo "Erro em view: " . $e->getMessage() . "\n";
    }
    
    try {
        $canUpdate = Auth::user()->can('update', $modelo);
        echo "update: " . ($canUpdate ? 'PERMITIDO' : 'NEGADO') . "\n";
    } catch (Exception $e) {
        echo "Erro em update: " . $e->getMessage() . "\n";
    }
    
    try {
        $canDelete = Auth::user()->can('delete', $modelo);
        echo "delete: " . ($canDelete ? 'PERMITIDO' : 'NEGADO') . "\n";
    } catch (Exception $e) {
        echo "Erro em delete: " . $e->getMessage() . "\n";
    }
} else {
    echo "Nenhum modelo encontrado!\n";
}

echo "\n=== TESTE CONCLUÍDO ===\n";