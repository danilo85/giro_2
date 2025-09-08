<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Orcamento;
use App\Models\Bank;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\PagamentoController;

class TestPagamento extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:pagamento {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa a criação de pagamento para identificar erro 403';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id') ?? 1;
        
        // Autenticar usuário
        $user = User::find($userId);
        if (!$user) {
            $this->error('Usuário não encontrado');
            return 1;
        }
        
        Auth::login($user);
        $this->info('Usuário autenticado: ' . $user->name);
        
        // Verificar se usuário está ativo
        if (isset($user->is_active) && !$user->is_active) {
            $this->error('Usuário não está ativo: is_active = ' . ($user->is_active ? 'true' : 'false'));
            return 1;
        }
        
        // Buscar orçamento
        $orcamento = Orcamento::whereHas('cliente', function($q) {
            $q->where('user_id', Auth::id());
        })->first();
        
        if (!$orcamento) {
            $this->error('Nenhum orçamento encontrado para o usuário');
            return 1;
        }
        
        $this->info('Orçamento encontrado: ' . $orcamento->titulo);
        
        // Buscar banco
        $banco = Bank::where('user_id', Auth::id())->first();
        if (!$banco) {
            $this->error('Nenhum banco encontrado para o usuário');
            return 1;
        }
        
        $this->info('Banco encontrado: ' . $banco->nome);
        
        // Teste 1: Simular request via controller direto
        $request = new Request([
            'orcamento_id' => $orcamento->id,
            'valor' => 100.00,
            'data_pagamento' => now()->format('Y-m-d'),
            'forma_pagamento' => 'pix',
            'bank_id' => $banco->id,
            'observacoes' => 'Teste via comando'
        ]);

        $this->info('=== TESTE 1: Controller Direto ===');
        $this->info('Dados do request: ' . json_encode($request->all()));

        try {
            $this->info('Tentando criar pagamento...');
            
            $controller = new PagamentoController();
            $response = $controller->store($request);
            
            $this->info('✓ Pagamento criado com sucesso via controller!');
            
        } catch (\Exception $e) {
            $this->error('✗ Erro ao criar pagamento: ' . $e->getMessage());
            $this->error('Código do erro: ' . $e->getCode());
        }

        // Teste 2: Verificar middleware e sessão
        $this->info('\n=== TESTE 2: Verificação de Middleware ===');
        
        // Verificar se o usuário está ativo
        $user = Auth::user();
        $this->info('Status do usuário: ' . ($user->is_active ? 'Ativo' : 'Inativo'));
        
        // Verificar se há algum middleware que pode estar bloqueando
        $this->info('Middleware aplicado nas rotas de pagamento:');
        
        // Simular verificação de autorização como no controller
        try {
            // Verificar se o orçamento pertence ao usuário (como no controller)
            if ($orcamento->cliente->user_id !== Auth::id()) {
                $this->error('✗ ERRO: Orçamento não pertence ao usuário logado!');
                $this->error('Cliente User ID: ' . $orcamento->cliente->user_id);
                $this->error('Auth User ID: ' . Auth::id());
            } else {
                $this->info('✓ Autorização OK: Orçamento pertence ao usuário');
            }
            
        } catch (\Exception $e) {
            $this->error('✗ Erro na verificação de autorização: ' . $e->getMessage());
        }
        
        // Teste 3: Verificar se há problemas com CSRF ou sessão
        $this->info('\n=== TESTE 3: Informações de Sessão ===');
        $this->info('Session ID: ' . session()->getId());
        $this->info('CSRF Token: ' . csrf_token());
        
        return 0;
    }
}
