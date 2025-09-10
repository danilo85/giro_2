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

        // Agrupar pagamentos por orçamento_id para efeito de cartas empilhadas
        $pagamentosAgrupados = collect();
        foreach ($pagamentos->groupBy('orcamento_id') as $orcamentoId => $gruposPagamentos) {
            $pagamentosAgrupados->push([
                'orcamento_id' => $orcamentoId,
                'pagamentos' => $gruposPagamentos,
                'count' => $gruposPagamentos->count(),
                'total_valor' => $gruposPagamentos->sum('valor')
            ]);
        }

        // Carregar orçamentos para o filtro
        $orcamentos = Orcamento::whereHas('cliente', function($q) {
            $q->where('user_id', Auth::id());
        })->with('cliente')->orderBy('created_at', 'desc')->get();

        // Calcular totais para os cards de resumo
        $baseQuery = Pagamento::whereHas('orcamento', function($q) {
            $q->whereHas('cliente', function($q2) {
                $q2->where('user_id', Auth::id());
            });
        });

        $totalRecebido = $baseQuery->sum('valor');
        
        $totalMes = $baseQuery->whereYear('data_pagamento', date('Y'))
                             ->whereMonth('data_pagamento', date('m'))
                             ->sum('valor');
        
        $totalCartao = $baseQuery->whereIn('forma_pagamento', ['cartao_credito', 'cartao_debito'])
                                 ->sum('valor');
        
        $totalPix = $baseQuery->whereIn('forma_pagamento', ['pix', 'transferencia'])
                             ->sum('valor');
        
        $totalPagamentos = $baseQuery->count();

        return view('pagamentos.index', compact('pagamentos', 'pagamentosAgrupados', 'orcamentos', 'totalRecebido', 'totalMes', 'totalCartao', 'totalPix', 'totalPagamentos'));
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
        \Log::info('PagamentoController@store - Iniciando', [
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
            'session_id' => session()->getId(),
            'csrf_token' => csrf_token()
        ]);

        try {
            // Validação básica primeiro
            $request->validate([
                'valor' => 'required|numeric|min:0.01',
                'data_pagamento' => 'required|date',
                'forma_pagamento' => 'required|in:dinheiro,pix,cartao_credito,cartao_debito,transferencia,boleto',
                'status' => 'required|in:pendente,processando,confirmado,cancelado',
                'observacoes' => 'nullable|string',
                'bank_id' => 'nullable|integer',
                'transaction_id' => 'nullable|string|max:255'
            ]);

            \Log::info('PagamentoController@store - Validação básica passou');

            // Verificar se o orçamento existe e pertence ao usuário
            if (!$request->filled('orcamento_id')) {
                \Log::error('PagamentoController@store - orcamento_id não fornecido');
                return back()->withErrors(['orcamento_id' => 'Orçamento é obrigatório'])->withInput();
            }

            $orcamento = Orcamento::whereHas('cliente', function($q) {
                $q->where('user_id', Auth::id());
            })->find($request->orcamento_id);

            if (!$orcamento) {
                \Log::error('PagamentoController@store - Orçamento não encontrado ou não pertence ao usuário', [
                    'orcamento_id' => $request->orcamento_id,
                    'user_id' => Auth::id()
                ]);
                return back()->withErrors(['orcamento_id' => 'Orçamento não encontrado ou você não tem permissão para acessá-lo'])->withInput();
            }

            \Log::info('PagamentoController@store - Orçamento encontrado', [
                'orcamento_id' => $orcamento->id,
                'cliente_user_id' => $orcamento->cliente->user_id
            ]);

        $pagamento = null;
        DB::transaction(function() use ($request, $orcamento, &$pagamento) {
            $pagamento = Pagamento::create([
                'orcamento_id' => $request->orcamento_id,
                'bank_id' => $request->bank_id,
                'valor' => $request->valor,
                'data_pagamento' => $request->data_pagamento,
                'forma_pagamento' => $request->forma_pagamento,
                'status' => $request->status,
                'observacoes' => $request->observacoes,
                'transaction_id' => $request->transaction_id
            ]);

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

            \Log::info('PagamentoController@store - Pagamento criado com sucesso', [
                'pagamento_id' => $pagamento->id
            ]);

            return redirect()->route('pagamentos.index')
                           ->with('success', 'Pagamento registrado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('PagamentoController@store - Erro', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);
            
            if ($e->getCode() == 403 || strpos($e->getMessage(), '403') !== false) {
                \Log::error('PagamentoController@store - Erro 403 detectado');
            }
            
            throw $e;
        }
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
            'status' => 'required|in:pendente,processando,confirmado,cancelado',
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
            
            $pagamento->update([
                'orcamento_id' => $request->orcamento_id,
                'bank_id' => $request->bank_id,
                'valor' => $request->valor,
                'data_pagamento' => $request->data_pagamento,
                'forma_pagamento' => $request->forma_pagamento,
                'status' => $request->status,
                'observacoes' => $request->observacoes,
                'transaction_id' => $request->transaction_id
            ]);

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

    /**
     * Gerar token público para o recibo
     */
    public function gerarRecibo(Pagamento $pagamento)
    {
        // Verificar se o pagamento pertence ao usuário
        if ($pagamento->orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        // Gerar token se não existir
        if (!$pagamento->hasTokenPublico()) {
            $pagamento->generateTokenPublico();
        }

        // Registrar no histórico
        HistoricoOrcamento::create([
            'user_id' => Auth::id(),
            'orcamento_id' => $pagamento->orcamento_id,
            'acao' => 'recibo_gerado',
            'descricao' => 'Recibo público gerado para o pagamento',
            'dados_novos' => ['token_publico' => $pagamento->token_publico]
        ]);

        return redirect()->route('pagamentos.show', $pagamento)
                       ->with('success', 'Recibo público gerado com sucesso!');
    }

    /**
     * Visualizar recibo público
     */
    public function showReciboPublico($token)
    {
        $pagamento = Pagamento::where('token_publico', $token)
                             ->with(['orcamento.cliente.user'])
                             ->firstOrFail();

        return view('pagamentos.public', compact('pagamento'));
    }
}
