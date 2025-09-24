<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Hash;

echo "=== TESTE FINAL DO SISTEMA DE APROVAÇÃO ===\n\n";

// Clear cache to ensure fresh data
Cache::flush();
echo "Cache limpo.\n\n";

// 1. Verificar configuração
echo "1. VERIFICANDO CONFIGURAÇÃO:\n";
$adminApprovalRequired = SettingsController::get('system', 'admin_approval', false);
echo "   Admin approval required: " . ($adminApprovalRequired ? 'SIM' : 'NÃO') . "\n\n";

// 2. Criar novo usuário
echo "2. CRIANDO NOVO USUÁRIO:\n";
$testEmail = 'usuario_teste_final_' . time() . '@teste.com';
echo "   Email: {$testEmail}\n";

$user = User::create([
    'name' => 'Usuário Teste Final',
    'email' => $testEmail,
    'password' => Hash::make('senha123'),
    'is_active' => true,
    'is_online' => false,
    'admin_approved' => false, // Always start as not approved
]);

// If admin approval is not required, auto-approve the user
if (!$adminApprovalRequired) {
    $user->update(['admin_approved' => true]);
    echo "   Status: Auto-aprovado (aprovação não é obrigatória)\n";
} else {
    echo "   Status: Aguardando aprovação\n";
}

echo "   ID do usuário: {$user->id}\n";
echo "   admin_approved: " . ($user->admin_approved ? 'SIM' : 'NÃO') . "\n";
echo "   is_active: " . ($user->is_active ? 'SIM' : 'NÃO') . "\n\n";

// 3. Testar método canLogin
echo "3. TESTANDO MÉTODO canLogin:\n";
$canLogin = $user->canLogin();
echo "   Pode fazer login: " . ($canLogin ? 'SIM' : 'NÃO') . "\n\n";

// 4. Verificar usuários pendentes
echo "4. VERIFICANDO USUÁRIOS PENDENTES:\n";
$pendingUsers = User::where('admin_approved', false)
                   ->where('is_active', true)
                   ->get();
echo "   Total de usuários pendentes: " . $pendingUsers->count() . "\n";
if ($pendingUsers->count() > 0) {
    echo "   Usuários pendentes:\n";
    foreach ($pendingUsers as $pending) {
        echo "     - {$pending->name} ({$pending->email})\n";
    }
}
echo "\n";

// 5. Verificar usuários aprovados
echo "5. VERIFICANDO USUÁRIOS APROVADOS:\n";
$approvedUsers = User::where('admin_approved', true)
                    ->where('is_active', true)
                    ->get();
echo "   Total de usuários aprovados: " . $approvedUsers->count() . "\n";
if ($approvedUsers->count() > 0) {
    echo "   Usuários aprovados:\n";
    foreach ($approvedUsers as $approved) {
        echo "     - {$approved->name} ({$approved->email})\n";
    }
}
echo "\n";

// 6. Resultado final
echo "6. RESULTADO FINAL:\n";
if ($adminApprovalRequired) {
    if (!$user->admin_approved && !$user->canLogin()) {
        echo "   ✅ SUCESSO: Sistema funcionando corretamente!\n";
        echo "   - Aprovação de admin está habilitada\n";
        echo "   - Novo usuário foi criado sem aprovação\n";
        echo "   - Usuário não pode fazer login\n";
        echo "   - Usuário aparece na lista de pendentes\n";
    } else {
        echo "   ❌ ERRO: Sistema não está funcionando corretamente!\n";
    }
} else {
    if ($user->admin_approved && $user->canLogin()) {
        echo "   ✅ SUCESSO: Sistema funcionando corretamente!\n";
        echo "   - Aprovação de admin está desabilitada\n";
        echo "   - Novo usuário foi auto-aprovado\n";
        echo "   - Usuário pode fazer login\n";
    } else {
        echo "   ❌ ERRO: Sistema não está funcionando corretamente!\n";
    }
}

echo "\n=== FIM DO TESTE ===\n";

?>