<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Bank;
use App\Models\CreditCard;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::forUser(Auth::id())
            ->with(['bank', 'creditCard', 'category']);
        
        // Filtros
        if ($request->has('status') && in_array($request->status, ['pendente', 'pago'])) {
            $query->byStatus($request->status);
        }
        
        if ($request->has('tipo') && in_array($request->tipo, ['receita', 'despesa'])) {
            $query->where('tipo', $request->tipo);
        }
        
        if ($request->has('mes') && $request->has('ano')) {
            $query->byMonth($request->ano, $request->mes);
        }
        
        if ($request->has('bank_id')) {
            $query->where('bank_id', $request->bank_id);
        }
        
        if ($request->has('credit_card_id')) {
            $query->where('credit_card_id', $request->credit_card_id);
        }
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        $transactions = $query->orderBy('data', 'desc')->paginate(20);
        
        // Dados para filtros
        $banks = Bank::forUser(Auth::id())->active()->get();
        $creditCards = CreditCard::forUser(Auth::id())->active()->get();
        $categories = Category::forUser(Auth::id())->active()->get();
        
        return view('financial.transactions.index', compact('transactions', 'banks', 'creditCards', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $banks = Bank::forUser(Auth::id())->active()->get();
            $creditCards = CreditCard::forUser(Auth::id())->active()->get();
            $categories = Category::forUser(Auth::id())->active()->get();
        } else {
            // Mock data for testing without authentication
            $banks = collect([
                (object)['id' => 1, 'nome' => 'Banco Teste', 'saldo_atual' => 1000.00],
                (object)['id' => 2, 'nome' => 'Banco Exemplo', 'saldo_atual' => 2500.00]
            ]);
            $creditCards = collect([
                (object)['id' => 1, 'nome' => 'Cartão Teste', 'limite_total' => 5000.00, 'limite_utilizado' => 1200.00],
                (object)['id' => 2, 'nome' => 'Cartão Exemplo', 'limite_total' => 3000.00, 'limite_utilizado' => 800.00]
            ]);
            $categories = collect([
                (object)['id' => 1, 'nome' => 'Alimentação', 'tipo' => 'despesa'],
                (object)['id' => 2, 'nome' => 'Transporte', 'tipo' => 'despesa'],
                (object)['id' => 3, 'nome' => 'Salário', 'tipo' => 'receita']
            ]);
        }
        
        return view('financial.transactions.create', compact('banks', 'creditCards', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'descricao' => 'nullable|string|max:255',
            'valor' => 'required|numeric|min:0.01',
            'tipo' => 'required|in:receita,despesa',
            'data' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'bank_id' => 'nullable|exists:banks,id',
            'credit_card_id' => 'nullable|exists:credit_cards,id',
            'status' => 'required|in:pendente,pago',
            'frequency_type' => 'nullable|in:unica,parcelada,recorrente',
            'recurring_type' => 'nullable|in:monthly,weekly,yearly',
            'recurring_end_date' => 'nullable|date|after:data',
            'installment_count' => 'nullable|integer|min:2|max:60',
            'data_pagamento' => 'nullable|date',
            'observacoes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            // Se for requisição AJAX, retornar JSON com erro
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Validar se tem banco OU cartão
        if (!$request->bank_id && !$request->credit_card_id) {
            // Se for requisição AJAX, retornar JSON com erro
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selecione um banco ou cartão de crédito.'
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors(['payment_method' => 'Selecione um banco ou cartão de crédito.'])
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            // Criar transação principal
            $transactionData = [
                'user_id' => Auth::id(),
                'bank_id' => $request->bank_id,
                'credit_card_id' => $request->credit_card_id,
                'category_id' => $request->category_id,
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'tipo' => $request->tipo,
                'data' => $request->data,
                'status' => $request->status,
                'frequency_type' => $request->frequency_type ?? 'unica',
                'recurring_type' => $request->recurring_type,
                'recurring_end_date' => $request->recurring_end_date,
                'data_pagamento' => $request->data_pagamento,
                'observacoes' => $request->observacoes
            ];
            
            $createdTransaction = null;
            
            // Se for parcelado
            if ($request->installment_count && $request->installment_count > 1) {
                $installmentValue = $request->valor / $request->installment_count;
                $installmentId = uniqid();
                
                for ($i = 1; $i <= $request->installment_count; $i++) {
                    $installmentData = $transactionData;
                    $installmentData['valor'] = $installmentValue;
                    $installmentData['installment_id'] = $installmentId;
                    $installmentData['installment_count'] = $request->installment_count;
                    $installmentData['installment_number'] = $i;
                    $installmentData['data'] = Carbon::parse($request->data)->addMonths($i - 1)->format('Y-m-d');
                    $installmentData['descricao'] = $request->descricao . " ({$i}/{$request->installment_count})";
                    
                    $transaction = Transaction::create($installmentData);
                    
                    // Guardar a primeira transação como referência
                    if ($i === 1) {
                        $createdTransaction = $transaction;
                    }
                }
            } else {
                $createdTransaction = Transaction::create($transactionData);
            }
            
            // Atualizar saldos se a transação estiver paga
            if ($request->status === 'pago') {
                if ($request->bank_id) {
                    $bank = Bank::find($request->bank_id);
                    $bank->updateSaldo($request->valor, $request->tipo);
                }
                
                if ($request->credit_card_id && $request->tipo === 'despesa') {
                    $creditCard = CreditCard::find($request->credit_card_id);
                    $creditCard->updateLimiteUtilizado($request->valor, 'add');
                }
            }
            
            // Recalcular limite do cartão automaticamente se houver cartão envolvido
            if ($request->credit_card_id) {
                $creditCard = CreditCard::find($request->credit_card_id);
                if ($creditCard) {
                    // Recalcular limite baseado nas transações não pagas
                    $limiteUtilizado = Transaction::where('credit_card_id', $creditCard->id)
                        ->where('tipo', 'despesa')
                        ->where('status', 'pendente')
                        ->sum('valor');
                    
                    $creditCard->update(['limite_utilizado' => $limiteUtilizado]);
                }
            }
            
            DB::commit();
            
            // Se for requisição AJAX, retornar JSON com sucesso
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transação criada com sucesso!',
                    'transaction' => [
                        'id' => $createdTransaction->id
                    ]
                ]);
            }
            
            return redirect()->route('financial.transactions.index')
                ->with('success', 'Transação criada com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            // Se for requisição AJAX, retornar JSON com erro
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao criar transação: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erro ao criar transação: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        
        $transaction->load(['bank', 'creditCard', 'category', 'files']);
        
        return view('financial.transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        
        $banks = Bank::forUser(Auth::id())->active()->get();
        $creditCards = CreditCard::forUser(Auth::id())->active()->get();
        $categories = Category::forUser(Auth::id())->active()->get();
        
        return view('financial.transactions.edit', compact('transaction', 'banks', 'creditCards', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        
        $validator = Validator::make($request->all(), [
            'descricao' => 'nullable|string|max:255',
            'valor' => 'required|numeric|min:0.01',
            'tipo' => 'required|in:receita,despesa',
            'data' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'bank_id' => 'nullable|exists:banks,id',
            'credit_card_id' => 'nullable|exists:credit_cards,id',
            'status' => 'required|in:pendente,pago',
            'frequency_type' => 'nullable|in:unica,parcelada,recorrente',
            'recurring_type' => 'nullable|in:monthly,weekly,yearly',
            'recurring_end_date' => 'nullable|date|after:data',
            'data_pagamento' => 'nullable|date',
            'observacoes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            // Se for requisição AJAX, retornar JSON com erro
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dados inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Validar se tem banco OU cartão
        if (!$request->bank_id && !$request->credit_card_id) {
            // Se for requisição AJAX, retornar JSON com erro
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selecione um banco ou cartão de crédito.'
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors(['payment_method' => 'Selecione um banco ou cartão de crédito.'])
                ->withInput();
        }
        
        DB::beginTransaction();
        
        try {
            $oldStatus = $transaction->status;
            $oldValue = $transaction->valor;
            $oldType = $transaction->tipo;
            $oldBankId = $transaction->bank_id;
            $oldCreditCardId = $transaction->credit_card_id;
            
            // Reverter saldos antigos se estava pago
            if ($oldStatus === 'pago') {
                if ($oldBankId) {
                    $bank = Bank::find($oldBankId);
                    $bank->updateSaldo($oldValue, $oldType === 'receita' ? 'despesa' : 'receita');
                }
                
                if ($oldCreditCardId && $oldType === 'despesa') {
                    $creditCard = CreditCard::find($oldCreditCardId);
                    $creditCard->updateLimiteUtilizado($oldValue, 'subtract');
                }
            }
            
            // Processar campos de recorrência
            $frequencyType = 'unica';
            $isRecurring = $request->boolean('is_recurring');
            $recurringType = $request->recurring_type;
            $recurringEndDate = $request->recurring_end_date;
            
            if ($isRecurring && $recurringType) {
                $frequencyType = 'recorrente';
            }
            
            // Atualizar transação
            $transaction->update([
                'bank_id' => $request->bank_id,
                'credit_card_id' => $request->credit_card_id,
                'category_id' => $request->category_id,
                'descricao' => $request->descricao,
                'valor' => $request->valor,
                'tipo' => $request->tipo,
                'data' => $request->data,
                'status' => $request->status,
                'data_pagamento' => $request->data_pagamento,
                'observacoes' => $request->observacoes,
                'frequency_type' => $frequencyType,
                'is_recurring' => $isRecurring,
                'recurring_type' => $recurringType,
                'recurring_end_date' => $recurringEndDate
            ]);
            
            // Aplicar novos saldos se estiver pago
            if ($request->status === 'pago') {
                if ($request->bank_id) {
                    $bank = Bank::find($request->bank_id);
                    $bank->updateSaldo($request->valor, $request->tipo);
                }
                
                if ($request->credit_card_id && $request->tipo === 'despesa') {
                    $creditCard = CreditCard::find($request->credit_card_id);
                    $creditCard->updateLimiteUtilizado($request->valor, 'add');
                }
            }
            
            // Recalcular limite dos cartões envolvidos automaticamente
            $cardsToUpdate = collect([$oldCreditCardId, $request->credit_card_id])->filter()->unique();
            foreach ($cardsToUpdate as $cardId) {
                $creditCard = CreditCard::find($cardId);
                if ($creditCard) {
                    // Recalcular limite baseado nas transações não pagas
                    $limiteUtilizado = Transaction::where('credit_card_id', $creditCard->id)
                        ->where('tipo', 'despesa')
                        ->where('status', 'pendente')
                        ->sum('valor');
                    
                    $creditCard->update(['limite_utilizado' => $limiteUtilizado]);
                }
            }
            
            DB::commit();
            
            // Se for requisição AJAX, retornar JSON com sucesso
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transação atualizada com sucesso!'
                ]);
            }
            
            return redirect()->route('financial.transactions.index')
                ->with('success', 'Transação atualizada com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            // Se for requisição AJAX, retornar JSON com erro
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar transação: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Erro ao atualizar transação: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        
        DB::beginTransaction();
        
        try {
            // Reverter saldos se estava pago
            if ($transaction->status === 'pago') {
                if ($transaction->bank_id) {
                    $bank = Bank::find($transaction->bank_id);
                    $bank->updateSaldo($transaction->valor, $transaction->tipo === 'receita' ? 'despesa' : 'receita');
                }
                
                if ($transaction->credit_card_id && $transaction->tipo === 'despesa') {
                    $creditCard = CreditCard::find($transaction->credit_card_id);
                    $creditCard->updateLimiteUtilizado($transaction->valor, 'subtract');
                }
            }
            
            $transaction->delete();
            
            // Recalcular limite do cartão automaticamente se houver cartão envolvido
            if ($transaction->credit_card_id) {
                $creditCard = CreditCard::find($transaction->credit_card_id);
                if ($creditCard) {
                    // Recalcular limite baseado nas transações não pagas
                    $limiteUtilizado = Transaction::where('credit_card_id', $creditCard->id)
                        ->where('tipo', 'despesa')
                        ->where('status', 'pendente')
                        ->sum('valor');
                    
                    $creditCard->update(['limite_utilizado' => $limiteUtilizado]);
                }
            }
            
            DB::commit();
            
            // Se for requisição AJAX, retornar JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Transação removida com sucesso!'
                ]);
            }
            
            return redirect()->route('financial.transactions.index')
                ->with('success', 'Transação removida com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            // Se for requisição AJAX, retornar JSON com erro
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'Erro ao remover transação: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('financial.transactions.index')
                ->with('error', 'Erro ao remover transação: ' . $e->getMessage());
        }
    }





    /**
     * Remove todas as parcelas de um lançamento parcelado
     */
    public function destroyAllInstallments(Request $request, Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        
        // Verificar se a transação tem parcelas
        if (!$transaction->parcela_numero || !$transaction->parcela_total) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'Esta transação não é parcelada'
                ], 400);
            }
            
            return redirect()->route('financial.transactions.index')
                ->with('error', 'Esta transação não é parcelada');
        }
        
        DB::beginTransaction();
        
        try {
            // Buscar todas as parcelas do mesmo grupo
            $allInstallments = Transaction::forUser(Auth::id())
                ->where('descricao', $transaction->descricao)
                ->where('valor', $transaction->valor)
                ->where('parcela_total', $transaction->parcela_total)
                ->whereNotNull('parcela_numero')
                ->get();
            
            foreach ($allInstallments as $installment) {
                // Reverter saldos se estava pago
                if ($installment->status === 'pago') {
                    if ($installment->bank_id) {
                        $bank = Bank::find($installment->bank_id);
                        $bank->updateSaldo($installment->valor, $installment->tipo === 'receita' ? 'despesa' : 'receita');
                    }
                    
                    if ($installment->credit_card_id && $installment->tipo === 'despesa') {
                        $creditCard = CreditCard::find($installment->credit_card_id);
                        $creditCard->updateLimiteUtilizado($installment->valor, 'subtract');
                    }
                }
                
                $installment->delete();
            }
            
            // Recalcular limite do cartão automaticamente se houver cartão envolvido
            if ($transaction->credit_card_id) {
                $creditCard = CreditCard::find($transaction->credit_card_id);
                if ($creditCard) {
                    // Recalcular limite baseado nas transações não pagas
                    $limiteUtilizado = Transaction::where('credit_card_id', $creditCard->id)
                        ->where('tipo', 'despesa')
                        ->where('status', 'pendente')
                        ->sum('valor');
                    
                    $creditCard->update(['limite_utilizado' => $limiteUtilizado]);
                }
            }
            
            DB::commit();
            
            // Se for requisição AJAX, retornar JSON
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Todas as parcelas foram removidas com sucesso!'
                ]);
            }
            
            return redirect()->route('financial.transactions.index')
                ->with('success', 'Todas as parcelas foram removidas com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            // Se for requisição AJAX, retornar JSON com erro
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'error' => 'Erro ao remover parcelas: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('financial.transactions.index')
                ->with('error', 'Erro ao remover parcelas: ' . $e->getMessage());
        }
    }

    /**
     * Marcar transação como paga
     */
    public function markAsPaid(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        
        if ($transaction->status === 'pago') {
            return response()->json(['error' => 'Transação já está paga'], 400);
        }
        
        DB::beginTransaction();
        
        try {
            $transaction->marcarComoPago();
            
            // Atualizar saldos
            if ($transaction->bank_id) {
                $bank = Bank::find($transaction->bank_id);
                $bank->updateSaldo($transaction->valor, $transaction->tipo);
            }
            
            if ($transaction->credit_card_id && $transaction->tipo === 'despesa') {
                $creditCard = CreditCard::find($transaction->credit_card_id);
                $creditCard->updateLimiteUtilizado($transaction->valor, 'add');
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Transação marcada como paga!',
                'transaction' => $transaction->fresh()
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Erro ao marcar como pago: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Marcar transação como pendente
     */
    public function markAsPending(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        
        if ($transaction->status === 'pendente') {
            return response()->json(['error' => 'Transação já está pendente'], 400);
        }
        
        DB::beginTransaction();
        
        try {
            // Reverter saldos se estava pago
            if ($transaction->status === 'pago') {
                if ($transaction->bank_id) {
                    $bank = Bank::find($transaction->bank_id);
                    $bank->updateSaldo($transaction->valor, $transaction->tipo === 'receita' ? 'despesa' : 'receita');
                }
                
                if ($transaction->credit_card_id && $transaction->tipo === 'despesa') {
                    $creditCard = CreditCard::find($transaction->credit_card_id);
                    $creditCard->updateLimiteUtilizado($transaction->valor, 'subtract');
                }
            }
            
            $transaction->status = 'pendente';
            $transaction->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Transação marcada como pendente!',
                'transaction' => $transaction->fresh()
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Erro ao marcar como pendente: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Duplicar transação
     */
    public function duplicate(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        
        try {
            $newTransaction = $transaction->duplicar();
            
            return response()->json([
                'success' => true,
                'message' => 'Transação duplicada com sucesso!',
                'transaction' => $newTransaction
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao duplicar transação: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obter resumo mensal
     */
    public function getMonthlySummary($year, $month)
    {
        $receitas = Transaction::forUser(Auth::id())
            ->receitas()
            ->pagas()
            ->byMonth($year, $month)
            ->sum('valor');
            
        $despesas = Transaction::forUser(Auth::id())
            ->despesas()
            ->pagas()
            ->byMonth($year, $month)
            ->sum('valor');
            
        $saldo = $receitas - $despesas;
        
        return response()->json([
            'receitas' => $receitas,
            'despesas' => $despesas,
            'saldo' => $saldo
        ]);
    }

    /**
     * Obter transações por categoria
     */
    public function getByCategory($year, $month)
    {
        $transactions = Transaction::forUser(Auth::id())
            ->with('category')
            ->pagas()
            ->byMonth($year, $month)
            ->get()
            ->groupBy('category.nome')
            ->map(function ($group) {
                return [
                    'categoria' => $group->first()->category->nome,
                    'total' => $group->sum('valor'),
                    'cor' => $group->first()->category->cor_final
                ];
            })
            ->values();
            
        return response()->json($transactions);
    }

    /**
     * API endpoint para listar transações
     */
    public function apiIndex(Request $request)
    {
        $query = Transaction::forUser(Auth::id())
            ->with(['category', 'bank', 'creditCard'])
            ->orderBy('data', 'desc');
            
        // Filtros opcionais
        if ($request->has('mes') && $request->has('ano')) {
            $query->byMonth($request->ano, $request->mes);
        }
        
        if ($request->has('tipo') && in_array($request->tipo, ['receita', 'despesa'])) {
            $query->where('tipo', $request->tipo);
        }
        
        if ($request->has('status') && in_array($request->status, ['pendente', 'pago'])) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('conta_id')) {
            $query->where('bank_id', $request->conta_id);
        }
        
        if ($request->has('credit_card_id')) {
            $query->where('credit_card_id', $request->credit_card_id);
        }
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('data_inicio') && $request->has('data_fim')) {
            $query->whereBetween('data', [$request->data_inicio, $request->data_fim]);
        }
        
        $transactions = $query->paginate(20);
        
        // Calcular resumo financeiro para o período
        $summaryQuery = Transaction::forUser(Auth::id());
        
        // Aplicar os mesmos filtros para o resumo
        if ($request->has('mes') && $request->has('ano')) {
            $summaryQuery->byMonth($request->ano, $request->mes);
        }
        
        if ($request->has('tipo') && in_array($request->tipo, ['receita', 'despesa'])) {
            $summaryQuery->where('tipo', $request->tipo);
        }
        
        if ($request->has('status') && in_array($request->status, ['pendente', 'pago'])) {
            $summaryQuery->where('status', $request->status);
        }
        
        if ($request->has('conta_id')) {
            $summaryQuery->where('bank_id', $request->conta_id);
        }
        
        if ($request->has('credit_card_id')) {
            $summaryQuery->where('credit_card_id', $request->credit_card_id);
        }
        
        if ($request->has('category_id')) {
            $summaryQuery->where('category_id', $request->category_id);
        }
        
        if ($request->has('data_inicio') && $request->has('data_fim')) {
            $summaryQuery->whereBetween('data', [$request->data_inicio, $request->data_fim]);
        }
        
        // Calcular totais
        $totalIncome = $summaryQuery->clone()->where('tipo', 'receita')->sum('valor');
        $totalExpenses = $summaryQuery->clone()->where('tipo', 'despesa')->sum('valor');
        $balance = $totalIncome - $totalExpenses;
        $pendingCount = $summaryQuery->clone()->where('status', 'pendente')->count();
        
        $summary = [
            'total_income' => $totalIncome,
            'total_expenses' => $totalExpenses,
            'balance' => $balance,
            'pending_count' => $pendingCount
        ];
        
        // Mapear transações incluindo campos de recorrência
        $response = $transactions->toArray();
        $response['data'] = $transactions->getCollection()->map(function ($transaction) {
            $data = $transaction->toArray();
            // Incluir campos de recorrência explicitamente
            $data['frequency_type'] = $transaction->frequency_type;
            $data['is_recurring'] = $transaction->is_recurring;
            $data['recurring_type'] = $transaction->recurring_type;
            return $data;
        });
        $response['summary'] = $summary;
        
        return response()->json($response);
    }

    /**
     * Obter todas as parcelas de uma transação parcelada
     */
    public function getInstallments(Transaction $transaction)
    {
        $this->authorize('view', $transaction);
        
        try {
            $installments = collect();
            
            // Verificar se usa o sistema novo (installment_id)
            if ($transaction->installment_id) {
                $installments = Transaction::forUser(Auth::id())
                    ->where('installment_id', $transaction->installment_id)
                    ->orderBy('installment_number')
                    ->get();
            }
            // Verificar se usa o sistema antigo (parcela_numero/parcela_total)
            elseif ($transaction->parcela_numero && $transaction->parcela_total) {
                // Buscar todas as parcelas do mesmo grupo usando descricao, valor e parcela_total
                $baseDescription = preg_replace('/\s*\(\d+\/\d+\)\s*$/', '', $transaction->descricao);
                
                $installments = Transaction::forUser(Auth::id())
                    ->where('valor', $transaction->valor)
                    ->where('parcela_total', $transaction->parcela_total)
                    ->where('descricao', 'like', $baseDescription . '%')
                    ->whereNotNull('parcela_numero')
                    ->orderBy('parcela_numero')
                    ->get();
            }
            else {
                return response()->json([
                    'success' => false,
                    'error' => 'Esta transação não é parcelada',
                    'message' => 'A transação selecionada não possui parcelas para exibir.'
                ], 422);
            }
            
            // Formatar dados para o frontend
            $formattedInstallments = $installments->map(function ($installment) {
                return [
                    'id' => $installment->id,
                    'descricao' => $installment->descricao,
                    'valor' => $installment->valor,
                    'data' => $installment->data,
                    'status' => $installment->status,
                    'installment_number' => $installment->installment_number ?? $installment->parcela_numero,
                    'installment_count' => $installment->installment_count ?? $installment->parcela_total
                ];
            });
            
            return response()->json([
                'success' => true,
                'installments' => $formattedInstallments
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao buscar parcelas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Excluir parcelas selecionadas
     */
    public function destroySelectedInstallments(Request $request)
    {
        $request->validate([
            'installment_ids' => 'required|array|min:1',
            'installment_ids.*' => 'required|integer|exists:transactions,id'
        ]);
        
        DB::beginTransaction();
        
        try {
            $deletedCount = 0;
            $deletedTransactions = [];
            
            foreach ($request->installment_ids as $installmentId) {
                $transaction = Transaction::forUser(Auth::id())->find($installmentId);
                
                if ($transaction) {
                    $this->authorize('delete', $transaction);
                    
                    // Reverter saldos se estava pago
                    if ($transaction->status === 'pago') {
                        if ($transaction->bank_id) {
                            $bank = Bank::find($transaction->bank_id);
                            $bank->updateSaldo($transaction->valor, $transaction->tipo === 'receita' ? 'despesa' : 'receita');
                        }
                        
                        if ($transaction->credit_card_id && $transaction->tipo === 'despesa') {
                            $creditCard = CreditCard::find($transaction->credit_card_id);
                            $creditCard->updateLimiteUtilizado($transaction->valor, 'subtract');
                        }
                    }
                    
                    $deletedTransactions[] = [
                        'id' => $transaction->id,
                        'installment_number' => $transaction->installment_number ?? $transaction->parcela_numero,
                        'installment_count' => $transaction->installment_count ?? $transaction->parcela_total
                    ];
                    
                    $transaction->delete();
                    $deletedCount++;
                }
            }
            
            DB::commit();
            
            // Verificar se é uma requisição AJAX ou formulário HTML
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "$deletedCount parcela(s) excluída(s) com sucesso!",
                    'deleted_count' => $deletedCount,
                    'deleted_transactions' => $deletedTransactions
                ]);
            } else {
                return redirect()->route('financial.transactions.index')
                    ->with('success', "$deletedCount parcela(s) excluída(s) com sucesso!");
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Erro ao excluir parcelas: ' . $e->getMessage()
                ], 500);
            } else {
                return redirect()->route('financial.transactions.index')
                    ->with('error', 'Erro ao excluir parcelas: ' . $e->getMessage());
            }
        }
    }

    /**
     * Listar faturas de cartão de crédito agrupadas por cartão e mês
     */
    public function getCreditCardInvoices(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));
        
        $invoices = Transaction::forUser(Auth::id())
            ->with(['creditCard', 'category'])
            ->whereNotNull('credit_card_id')
            ->where('tipo', 'despesa')
            ->byMonth($year, $month)
            ->get()
            ->groupBy('credit_card_id')
            ->map(function ($transactions, $creditCardId) use ($year, $month) {
                $creditCard = $transactions->first()->creditCard;
                $totalValue = $transactions->sum('valor');
                $paidTransactions = $transactions->where('status', 'pago');
                $pendingTransactions = $transactions->where('status', 'pendente');
                $isPaid = $pendingTransactions->count() === 0;
                
                return [
                    'credit_card_id' => $creditCardId,
                    'credit_card_name' => $creditCard->nome_cartao,
                    'credit_card_brand' => $creditCard->bandeira,
                    'credit_card_logo' => $creditCard->bandeira_logo_url,
                    'year' => $year,
                    'month' => $month,
                    'month_name' => Carbon::create($year, $month, 1)->format('F'),
                    'total_value' => $totalValue,
                    'paid_value' => $paidTransactions->sum('valor'),
                    'pending_value' => $pendingTransactions->sum('valor'),
                    'transaction_count' => $transactions->count(),
                    'paid_count' => $paidTransactions->count(),
                    'pending_count' => $pendingTransactions->count(),
                    'is_paid' => $isPaid,
                    'status' => $isPaid ? 'pago' : 'pendente',
                    'transactions' => $transactions->map(function ($transaction) {
                        return [
                            'id' => $transaction->id,
                            'descricao' => $transaction->descricao,
                            'valor' => $transaction->valor,
                            'data' => $transaction->data,
                            'status' => $transaction->status,
                            'category_name' => $transaction->category->nome ?? 'Sem categoria',
                            'category_color' => $transaction->category->cor_final ?? '#6B7280'
                        ];
                    })->values()
                ];
            })
            ->values();
            
        return response()->json([
            'success' => true,
            'invoices' => $invoices
        ]);
    }

    /**
     * Pagar fatura completa de um cartão de crédito
     */
    public function payCreditCardInvoice(Request $request)
    {
        $request->validate([
            'credit_card_id' => 'required|exists:credit_cards,id',
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12'
        ]);
        
        DB::beginTransaction();
        
        try {
            $transactions = Transaction::forUser(Auth::id())
                ->whereNotNull('credit_card_id')
                ->where('credit_card_id', $request->credit_card_id)
                ->where('tipo', 'despesa')
                ->where('status', 'pendente')
                ->byMonth($request->year, $request->month)
                ->get();
                
            if ($transactions->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhuma transação pendente encontrada para esta fatura.'
                ], 422);
            }
            
            $totalValue = 0;
            $creditCard = CreditCard::find($request->credit_card_id);
            
            foreach ($transactions as $transaction) {
                $transaction->status = 'pago';
                $transaction->data_pagamento = Carbon::now();
                $transaction->save();
                
                $totalValue += $transaction->valor;
            }
            
            // Atualizar limite utilizado do cartão
            $creditCard->updateLimiteUtilizado($totalValue, 'add');
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Fatura paga com sucesso!',
                'total_paid' => $totalValue,
                'transactions_count' => $transactions->count()
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao pagar fatura: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Desfazer pagamento de fatura de cartão de crédito
     */
    public function undoCreditCardInvoicePayment(Request $request)
    {
        $request->validate([
            'credit_card_id' => 'required|exists:credit_cards,id',
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'required|integer|min:1|max:12'
        ]);
        
        DB::beginTransaction();
        
        try {
            $transactions = Transaction::forUser(Auth::id())
                ->whereNotNull('credit_card_id')
                ->where('credit_card_id', $request->credit_card_id)
                ->where('tipo', 'despesa')
                ->where('status', 'pago')
                ->byMonth($request->year, $request->month)
                ->get();
                
            if ($transactions->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhuma transação paga encontrada para esta fatura.'
                ], 422);
            }
            
            $totalValue = 0;
            $creditCard = CreditCard::find($request->credit_card_id);
            
            foreach ($transactions as $transaction) {
                $transaction->status = 'pendente';
                $transaction->data_pagamento = null;
                $transaction->save();
                
                $totalValue += $transaction->valor;
            }
            
            // Reverter limite utilizado do cartão
            $creditCard->updateLimiteUtilizado($totalValue, 'subtract');
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Pagamento da fatura desfeito com sucesso!',
                'total_reverted' => $totalValue,
                'transactions_count' => $transactions->count()
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao desfazer pagamento: ' . $e->getMessage()
            ], 500);
        }
    }
}
