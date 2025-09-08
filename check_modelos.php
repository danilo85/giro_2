<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ModeloProposta;
use App\Models\User;

echo "=== VERIFICANDO MODELOS DE PROPOSTA ===\n";

$modelos = ModeloProposta::with('user')->get();

foreach ($modelos as $modelo) {
    echo "ID: {$modelo->id}, User ID: {$modelo->user_id}, User: " . 
         ($modelo->user ? $modelo->user->name : 'NULL') . "\n";
}

echo "\n=== VERIFICANDO USUÁRIO LOGADO ===\n";
$user = User::find(1);
if ($user) {
    echo "Usuário ID 1: {$user->name} ({$user->email})\n";
} else {
    echo "Usuário ID 1 não encontrado\n";
}

echo "\n=== TESTANDO PERMISSÕES ===\n";
if ($user && $modelos->count() > 0) {
    $modelo = $modelos->first();
    echo "Testando permissões para modelo ID {$modelo->id}:\n";
    echo "- view: " . ($user->can('view', $modelo) ? 'SIM' : 'NÃO') . "\n";
    echo "- update: " . ($user->can('update', $modelo) ? 'SIM' : 'NÃO') . "\n";
    echo "- delete: " . ($user->can('delete', $modelo) ? 'SIM' : 'NÃO') . "\n";
    echo "- duplicate: " . ($user->can('duplicate', $modelo) ? 'SIM' : 'NÃO') . "\n";
}