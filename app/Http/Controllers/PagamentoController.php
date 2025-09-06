<?php

namespace App\Http\Controllers;

use App\Models\Pagamento;
use App\Models\Orcamento;
use App\Models\HistoricoOrcamento;
use App\Models\Transaction;
use App\Models\Bank;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PagamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pagamento::with(['orcamento.cliente'])
            ->whereHas('orcamento', function($q) {
                $q->whereHas('cliente', function($q2) {
                    $q2->where('user_id', Auth::id());
                });
            })
            ->orderBy('data_pagamento', 'desc');

        // Filtro por orçamento
        if ($request->filled('orcamento_id')) {
            $query->where('orcamento_id', $request->orcamento_id);
        }

        // Filtro por forma de pagamento
        if ($request->filled('forma_pagamento')) {
            $query->where('forma_pagamento', $request->forma_pagamento);
        }

        // Filtro por período
        if ($request->filled('data_inicio')) {
            $query->whereDate('data_pagamento', '>=', $request->data_inicio);
        }
        if ($request->filled('data_fim')) {
            $query->whereDate('data_pagamento', '<=', $request->data_fim);
        }

        $pagamentos = $query->paginate(15);

        // Carregar orçamentos para o filtro
        $orcamentos = Orcamento::whereHas('cliente', function($q) {
            $q->where('user_id', Auth::id());
        })->with('cliente')->orderBy('created_at', 'desc')->get();

        return view('pagamentos.index', compact('pagamentos', 'orcamentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $orcamento = null;
        if ($request->filled('orcamento_id')) {
            $orcamento = Orcamento::whereHas('cliente', function($q) {
                $q->where('user_id', Auth::id());
            })->findOrFail($request->orcamento_id);
        }

        $orcamentos = Orcamento::whereHas('cliente', function($q) {
            $q->where('user_id', Auth::id());
        })->with('cliente')->orderBy('created_at', 'desc')->get();
        
        $bancos = Bank::where('user_id', Auth::id())
                     ->where('ativo', true)
                     ->orderBy('nome')
                     ->get();

        return view('pagamentos.create', compact('orcamentos', 'orcamento', 'bancos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'orcamento_id' => 'required|exists:orcamentos,id',
            'valor' => 'required|numeric|min:0.01',
            'data_pagamento' => 'required|date',
            'forma_pagamento' => 'required|in:dinheiro,pix,cartao_credito,cartao_debito,transferencia,boleto',
            'observacoes' => 'nullable|string',
            'bank_id' => 'nullable|integer',
            'transaction_id' => 'nullable|string|max:255'
        ]);

        // Verificar se o orçamento pertence ao usuário
        $orcamento = Orcamento::whereHas('cliente', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($request->orcamento_id);

        $pagamento = null;
        DB::transaction(function() use ($request, $orcamento, &$pagamento) {
            $pagamento = Pagamento::create($request->all());

            // Integrar com sistema financeiro
            $this->integrarSistemaFinanceiro($pagamento);

            // Registrar no histórico
            HistoricoOrcamento::create([
                'user_id' => Auth::id(),
                'orcamento_id' => $orcamento->id,
                'acao' => 'pagamento_adicionado',
                'descricao' => "Pagamento de R$ {$pagamento->valor_formatted} adicionado",
                'dados_novos' => $pagamento->toArray()
            ]);

            // Verificar se o orçamento deve ser marcado como quitado
            $totalPagamentos = $orcamento->pagamentos()->sum('valor');
            if ($totalPagamentos >= $orcamento->valor_total && $orcamento->status !== 'quitado') {
                $orcamento->update(['status' => 'quitado']);
                
                HistoricoOrcamento::create([
                    'user_id' => Auth::id(),
                    'orcamento_id' => $orcamento->id,
                    'acao' => 'status_alterado',
                    'descricao' => 'Status alterado para quitado automaticamente',
                    'dados_anteriores' => ['status' => $orcamento->getOriginal('status')],
                    'dados_novos' => ['status' => 'quitado']
                ]);
            }
        });

        return redirect()->route('pagamentos.index')
                       ->with('success', 'Pagamento registrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pagamento $pagamento)
    {
        // Verificar se o pagamento pertence ao usuário
        if ($pagamento->orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        $pagamento->load(['orcamento.cliente', 'orcamento.autores']);

        return view('pagamentos.show', compact('pagamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pagamento $pagamento)
    {
        // Verificar se o pagamento pertence ao usuário
        if ($pagamento->orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        $orcamentos = Orcamento::whereHas('cliente', function($q) {
            $q->where('user_id', Auth::id());
        })->with('cliente')->orderBy('created_at', 'desc')->get();
        
        $bancos = Bank::where('user_id', Auth::id())
                     ->where('ativo', true)
                     ->orderBy('nome')
                     ->get();

        return view('pagamentos.edit', compact('pagamento', 'orcamentos', 'bancos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pagamento $pagamento)
    {
        // Verificar se o pagamento pertence ao usuário
        if ($pagamento->orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'orcamento_id' => 'required|exists:orcamentos,id',
            'valor' => 'required|numeric|min:0.01',
            'data_pagamento' => 'required|date',
            'forma_pagamento' => 'required|in:dinheiro,pix,cartao_credito,cartao_debito,transferencia,boleto',
            'observacoes' => 'nullable|string',
            'bank_id' => 'nullable|integer',
            'transaction_id' => 'nullable|string|max:255'
        ]);

        // Verificar se o novo orçamento pertence ao usuário
        $novoOrcamento = Orcamento::whereHas('cliente', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($request->orcamento_id);

        DB::transaction(function() use ($request, $pagamento) {
            $dadosAnteriores = $pagamento->toArray();
            
            // Remover integração anterior
            $this->removerIntegracaoFinanceira($pagamento);
            
            $pagamento->update($request->all());

            // Integrar novamente com sistema financeiro
            $this->integrarSistemaFinanceiro($pagamento);

            // Registrar no histórico
            HistoricoOrcamento::create([
                'user_id' => Auth::id(),
                'orcamento_id' => $pagamento->orcamento_id,
                'acao' => 'pagamento_editado',
                'descricao' => "Pagamento de R$ {$pagamento->valor_formatted} editado",
                'dados_anteriores' => $dadosAnteriores,
                'dados_novos' => $pagamento->toArray()
            ]);

            // Verificar se o orçamento deve ser marcado como quitado
            $orcamento = $pagamento->orcamento;
            $totalPagamentos = $orcamento->pagamentos()->sum('valor');
            if ($totalPagamentos >= $orcamento->valor_total && $orcamento->status !== 'quitado') {
                $orcamento->update(['status' => 'quitado']);
                
                HistoricoOrcamento::create([
                    'user_id' => Auth::id(),
                    'orcamento_id' => $orcamento->id,
                    'acao' => 'status_alterado',
                    'descricao' => 'Status alterado para quitado automaticamente',
                    'dados_anteriores' => ['status' => $orcamento->getOriginal('status')],
                    'dados_novos' => ['status' => 'quitado']
                ]);
            }
        });

        return redirect()->route('pagamentos.show', $pagamento)
                       ->with('success', 'Pagamento atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pagamento $pagamento)
    {
        // Verificar se o pagamento pertence ao usuário
        if ($pagamento->orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        DB::transaction(function() use ($pagamento) {
            $orcamentoId = $pagamento->orcamento_id;
            
            // Remover integração com sistema financeiro
            $this->removerIntegracaoFinanceira($pagamento);
            
            $pagamento->delete();

            // Registrar no histórico
            HistoricoOrcamento::create([
                'user_id' => Auth::id(),
                'orcamento_id' => $orcamentoId,
                'acao' => 'pagamento_removido',
                'descricao' => "Pagamento de R$ {$pagamento->valor_formatted} removido",
                'dados_anteriores' => $pagamento->toArray()
            ]);
        });

        return redirect()->route('pagamentos.index')
                       ->with('success', 'Pagamento excluído com sucesso!');
    }

    /**
     * Integrar pagamento com sistema financeiro
     */
    private function integrarSistemaFinanceiro(Pagamento $pagamento)
    {
        try {
            // Verificar se já existe transação vinculada
            if ($pagamento->transaction_id) {
                return;
            }

            // Buscar banco do pagamento
            $bank = $pagamento->bank;
            if (!$bank) {
                return; // Não há banco configurado
            }

            // Buscar ou criar categoria para orçamentos
            $category = Category::firstOrCreate(
                [
                    'user_id' => Auth::id(),
                    'nome' => 'Orçamentos'
                ],
                [
                    'tipo' => 'receita',
                    'cor' => '#10B981',
                    'descricao' => 'Receitas de orçamentos'
                ]
            );

            // Criar transação no sistema financeiro
            $transaction = Transaction::create([
                'user_id' => Auth::id(),
                'bank_id' => $bank->id,
                'category_id' => $category->id,
                'descricao' => "Pagamento - Orçamento #{$pagamento->orcamento->id} - {$pagamento->orcamento->titulo}",
                'valor' => $pagamento->valor,
                'tipo' => 'receita',
                'data' => $pagamento->data_pagamento,
                'status' => 'pago',
                'data_pagamento' => $pagamento->data_pagamento,
                'observacoes' => $pagamento->observacoes,
                'reference_type' => 'pagamento_orcamento',
                'reference_id' => $pagamento->id
            ]);

            // Vincular transação ao pagamento
            $pagamento->update(['transaction_id' => $transaction->id]);

            // Atualizar saldo do banco
            $bank->increment('saldo_atual', $pagamento->valor);

        } catch (\Exception $e) {
            // Log do erro mas não interrompe o fluxo
            \Log::error('Erro ao integrar pagamento com sistema financeiro: ' . $e->getMessage());
        }
    }

    /**
     * Remover integração com sistema financeiro
     */
    private function removerIntegracaoFinanceira(Pagamento $pagamento)
    {
        try {
            // Buscar transação vinculada
            $transaction = $pagamento->transaction;

            if ($transaction) {
                // Reverter saldo do banco
                if ($transaction->bank) {
                    $transaction->bank->decrement('saldo_atual', $transaction->valor);
                }
                
                // Remover vinculação
                $pagamento->update(['transaction_id' => null]);
                
                // Deletar transação
                $transaction->delete();
            }

        } catch (\Exception $e) {
            // Log do erro mas não interrompe o fluxo
            \Log::error('Erro ao remover integração financeira: ' . $e->getMessage());
        }
    }
}
