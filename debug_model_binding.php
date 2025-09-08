<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== DEBUG MODEL BINDING ===\n\n";

// Simular login
$user = App\Models\User::find(1);
Auth::login($user);
echo "Usuário logado: {$user->name}\n\n";

// Verificar modelos existentes
echo "=== MODELOS EXISTENTES ===\n";
$modelos = App\Models\ModeloProposta::all();
foreach ($modelos as $modelo) {
    echo "ID: {$modelo->id}, Nome: {$modelo->nome}, User ID: {$modelo->user_id}\n";
}

// Testar model binding para IDs específicos que deram erro
echo "\n=== TESTE MODEL BINDING ===\n";
$testIds = [3, 8]; // IDs que deram erro 403 nos logs

foreach ($testIds as $id) {
    echo "\nTestando ID {$id}:\n";
    
    try {
        $modelo = App\Models\ModeloProposta::findOrFail($id);
        echo "✅ Modelo encontrado: {$modelo->nome} (User ID: {$modelo->user_id})\n";
        
        // Testar policy manualmente
        $gate = app('Illuminate\Contracts\Auth\Access\Gate');
        
        echo "Testando permissões:\n";
        $canView = $gate->forUser($user)->allows('view', $modelo);
        echo "- view: " . ($canView ? 'PERMITIDO' : 'NEGADO') . "\n";
        
        $canUpdate = $gate->forUser($user)->allows('update', $modelo);
        echo "- update: " . ($canUpdate ? 'PERMITIDO' : 'NEGADO') . "\n";
        
        $canDelete = $gate->forUser($user)->allows('delete', $modelo);
        echo "- delete: " . ($canDelete ? 'PERMITIDO' : 'NEGADO') . "\n";
        
    } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        echo "❌ Modelo não encontrado\n";
    } catch (Exception $e) {
        echo "❌ Erro: {$e->getMessage()}\n";
    }
}

// Verificar se há algum gate personalizado
echo "\n=== VERIFICAR GATES PERSONALIZADOS ===\n";
try {
    $gate = app('Illuminate\Contracts\Auth\Access\Gate');
    $reflection = new ReflectionClass($gate);
    
    // Tentar acessar propriedades privadas para ver gates registrados
    if ($reflection->hasProperty('abilities')) {
        $abilitiesProperty = $reflection->getProperty('abilities');
        $abilitiesProperty->setAccessible(true);
        $abilities = $abilitiesProperty->getValue($gate);
        
        echo "Gates registrados:\n";
        foreach ($abilities as $ability => $callback) {
            echo "- {$ability}\n";
        }
    }
} catch (Exception $e) {
    echo "Erro ao verificar gates: {$e->getMessage()}\n";
}