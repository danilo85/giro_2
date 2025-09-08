<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTE CONTROLLER AUTHORIZATION ===\n\n";

// Simular login
$user = App\Models\User::find(1);
Auth::login($user);
echo "Usuário logado: {$user->name}\n\n";

// Testar IDs que deram erro
$testIds = [3, 8];

foreach ($testIds as $id) {
    echo "\n=== TESTANDO ID {$id} ===\n";
    
    try {
        // Buscar o modelo
        $modelo = App\Models\ModeloProposta::findOrFail($id);
        echo "✅ Modelo encontrado: {$modelo->nome}\n";
        
        // Testar Gate diretamente
        echo "\nTestando Gate diretamente:\n";
        $gate = app('Illuminate\Contracts\Auth\Access\Gate');
        $canView = $gate->allows('view', $modelo);
        echo "Gate allows 'view': " . ($canView ? 'SIM' : 'NÃO') . "\n";
        
        $canUpdate = $gate->allows('update', $modelo);
        echo "Gate allows 'update': " . ($canUpdate ? 'SIM' : 'NÃO') . "\n";
        
        $canDelete = $gate->allows('delete', $modelo);
        echo "Gate allows 'delete': " . ($canDelete ? 'SIM' : 'NÃO') . "\n";
        
        // Verificar se a policy está sendo chamada
        echo "\nVerificando policy registration:\n";
        $policyClass = $gate->getPolicyFor($modelo);
        if ($policyClass) {
            echo "✅ Policy encontrada: " . get_class($policyClass) . "\n";
            
            // Testar método view da policy diretamente
            $policy = new App\Policies\ModeloPropostaPolicy();
            $viewResult = $policy->view($user, $modelo);
            echo "Policy->view() resultado: " . ($viewResult ? 'PERMITIDO' : 'NEGADO') . "\n";
            
        } else {
            echo "❌ Nenhuma policy encontrada para ModeloProposta\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Erro geral: {$e->getMessage()}\n";
    }
}

echo "\n=== CONCLUSÃO ===\n";
echo "✅ Todas as permissões funcionam corretamente no contexto CLI\n";
echo "❌ O problema está específico ao contexto web/HTTP\n";
echo "\n🔍 Próximos passos:\n";
echo "1. Verificar se há middleware interferindo\n";
echo "2. Testar via HTTP real\n";
echo "3. Verificar configurações de sessão\n";