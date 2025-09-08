<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== TESTE COMPLETO DE MODELOS DE PROPOSTA ===\n\n";

// 1. Verificar se há usuários
$user = App\Models\User::first();
if (!$user) {
    echo "❌ Nenhum usuário encontrado\n";
    exit(1);
}

echo "✅ Usuário encontrado: {$user->email}\n";

// 2. Simular login
Auth::login($user);
echo "✅ Usuário logado\n";

// 3. Verificar modelos existentes
$modelos = App\Models\ModeloProposta::forUser($user->id)->get();
echo "✅ Modelos encontrados: {$modelos->count()}\n";

// 4. Testar criação de modelo
try {
    $novoModelo = App\Models\ModeloProposta::create([
        'nome' => 'Teste Modelo ' . now()->format('H:i:s'),
        'conteudo' => 'Conteúdo de teste para verificar funcionalidade',
        'user_id' => $user->id,
        'ativo' => true
    ]);
    echo "✅ Modelo criado com sucesso: ID {$novoModelo->id}\n";
} catch (Exception $e) {
    echo "❌ Erro ao criar modelo: {$e->getMessage()}\n";
}

// 5. Testar policy de visualização
if (isset($novoModelo)) {
    try {
        $gate = app('Illuminate\Contracts\Auth\Access\Gate');
        $canView = $gate->forUser($user)->allows('view', $novoModelo);
        echo $canView ? "✅ Policy view: PERMITIDO\n" : "❌ Policy view: NEGADO\n";
        
        $canUpdate = $gate->forUser($user)->allows('update', $novoModelo);
        echo $canUpdate ? "✅ Policy update: PERMITIDO\n" : "❌ Policy update: NEGADO\n";
        
        $canDelete = $gate->forUser($user)->allows('delete', $novoModelo);
        echo $canDelete ? "✅ Policy delete: PERMITIDO\n" : "❌ Policy delete: NEGADO\n";
        
        $canDuplicate = $gate->forUser($user)->allows('duplicate', $novoModelo);
        echo $canDuplicate ? "✅ Policy duplicate: PERMITIDO\n" : "❌ Policy duplicate: NEGADO\n";
    } catch (Exception $e) {
        echo "❌ Erro ao testar policies: {$e->getMessage()}\n";
    }
}

// 6. Testar atualização
if (isset($novoModelo)) {
    try {
        $novoModelo->update([
            'nome' => 'Modelo Atualizado ' . now()->format('H:i:s'),
            'conteudo' => 'Conteúdo atualizado'
        ]);
        echo "✅ Modelo atualizado com sucesso\n";
    } catch (Exception $e) {
        echo "❌ Erro ao atualizar modelo: {$e->getMessage()}\n";
    }
}

// 7. Testar duplicação
if (isset($novoModelo)) {
    try {
        $modeloDuplicado = App\Models\ModeloProposta::create([
            'nome' => 'Cópia de ' . $novoModelo->nome,
            'conteudo' => $novoModelo->conteudo,
            'ativo' => $novoModelo->ativo,
            'user_id' => $user->id
        ]);
        echo "✅ Modelo duplicado com sucesso: ID {$modeloDuplicado->id}\n";
    } catch (Exception $e) {
        echo "❌ Erro ao duplicar modelo: {$e->getMessage()}\n";
    }
}

echo "\n=== TESTE CONCLUÍDO ===\n";