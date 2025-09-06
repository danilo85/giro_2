<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banks = Bank::forUser(Auth::id())->active()->get();
        return view('financial.banks.index', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('financial.banks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Debug: Log dos dados recebidos
        \Log::info('Dados recebidos no store:', $request->all());
        \Log::info('Logo URL recebido:', ['logo_url' => $request->logo_url]);
        
        // Converter saldo de formato brasileiro para decimal
        $saldo = str_replace(['.', ','], ['', '.'], $request->saldo);
        
        $validator = Validator::make(array_merge($request->all(), ['saldo_decimal' => $saldo]), [
            'nome' => 'required|string|max:255',
            'banco' => 'required|string|max:255',
            'tipo_conta' => 'required|string|max:255',
            'agencia' => 'nullable|string|max:50',
            'conta' => 'nullable|string|max:50',
            'logo_url' => 'nullable|string|max:2048',
            'saldo_decimal' => 'required|numeric|min:0',
            'observacoes' => 'nullable|string|max:1000',
            'ativo' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $bankData = [
            'user_id' => Auth::id(),
            'nome' => $request->nome,
            'banco' => $request->banco,
            'tipo_conta' => $request->tipo_conta,
            'agencia' => $request->agencia,
            'conta' => $request->conta,
            'logo_url' => $request->logo_url,
            'saldo_inicial' => $saldo,
            'saldo_atual' => $saldo,
            'observacoes' => $request->observacoes,
            'ativo' => $request->has('ativo') ? true : false
        ];
        
        // Debug: Log dos dados que serão salvos
        \Log::info('Dados para criar banco:', $bankData);
        
        $bank = Bank::create($bankData);
        
        // Debug: Log do banco criado
        \Log::info('Banco criado:', $bank->toArray());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Conta bancária criada com sucesso!',
                'bank' => $bank
            ]);
        }
        
        return redirect()->route('financial.banks.index')
            ->with('success', 'Conta bancária criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Bank $bank)
    {
        // Verificar se o banco pertence ao usuário logado
        if ($bank->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }
        
        $transactions = $bank->transactions()
            ->with('category')
            ->orderBy('data', 'desc')
            ->paginate(20);
            
        return view('financial.banks.show', compact('bank', 'transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $bank)
    {
        // Verificar se o banco pertence ao usuário logado
        if ($bank->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }
        return view('financial.banks.edit', compact('bank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bank $bank)
    {
        // Verificar se o banco pertence ao usuário logado
        if ($bank->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }
        
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'tipo_conta' => 'required|string|max:255',
            'agencia' => 'nullable|string|max:50',
            'conta' => 'nullable|string|max:50',
            'saldo' => 'required|string',
            'observacoes' => 'nullable|string',
            'logo_url' => 'nullable|url|max:500',
            'ativo' => 'boolean'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Converter o saldo de formato brasileiro para decimal
        $saldo = str_replace(['.', ','], ['', '.'], $request->saldo);
        $saldo = (float) $saldo;

        $bank->update([
            'nome' => $request->nome,
            'tipo_conta' => $request->tipo_conta,
            'agencia' => $request->agencia,
            'conta' => $request->conta,
            'saldo_atual' => $saldo,
            'observacoes' => $request->observacoes,
            'logo_url' => $request->logo_url,
            'ativo' => $request->has('ativo') ? 1 : 0
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Conta bancária atualizada com sucesso!',
                'bank' => $bank
            ]);
        }
        
        return redirect()->route('financial.banks.index')
            ->with('success', 'Conta bancária atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        // Verificar se o banco pertence ao usuário logado
        if ($bank->user_id !== Auth::id()) {
            return response()->json(['error' => 'Acesso negado.'], 403);
        }
        
        try {
            // Hard delete - exclusão física do banco de dados
            $bank->delete();

            return response()->json([
                'success' => true,
                'message' => 'Conta bancária removida com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover conta bancária.'
            ], 500);
        }
    }

    /**
     * API endpoint para obter saldo atual
     */
    public function getSaldo(Bank $bank)
    {
        // Verificar se o banco pertence ao usuário logado
        if ($bank->user_id !== Auth::id()) {
            return response()->json(['error' => 'Acesso negado.'], 403);
        }
        
        return response()->json([
            'success' => true,
            'saldo_atual' => $bank->saldo_atual,
            'saldo_disponivel' => $bank->saldo_disponivel,
            'balance_formatted' => number_format($bank->saldo_atual, 2, ',', '.')
        ]);
    }

    /**
     * API endpoint para atualizar saldo
     */
    public function updateSaldo(Request $request, Bank $bank)
    {
        // Verificar se o banco pertence ao usuário logado
        if ($bank->user_id !== Auth::id()) {
            return response()->json(['error' => 'Acesso negado.'], 403);
        }
        
        $validator = Validator::make($request->all(), [
            'valor' => 'required|numeric',
            'tipo' => 'required|in:receita,despesa'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $bank->updateSaldo($request->valor, $request->tipo);

        return response()->json([
            'success' => true,
            'saldo_atual' => $bank->fresh()->saldo_atual
        ]);
    }

    /**
     * API endpoint para recalcular saldo baseado nas transações
     */
    public function recalculateSaldo(Bank $bank)
    {
        // Verificar se o banco pertence ao usuário logado
        if ($bank->user_id !== Auth::id()) {
            return response()->json(['error' => 'Acesso negado.'], 403);
        }

        try {
            $bank->updateSaldoCalculado();
            
            return response()->json([
                'success' => true,
                'saldo_atual' => $bank->saldo_atual,
                'balance_formatted' => number_format($bank->saldo_atual, 2, ',', '.'),
                'message' => 'Saldo recalculado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao recalcular saldo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exibir extrato de transações do banco
     */
    public function extrato(Bank $bank)
    {
        // Verificar se o banco pertence ao usuário logado
        if ($bank->user_id !== Auth::id()) {
            abort(403, 'Acesso negado.');
        }
        
        $transactions = $bank->transactions()
            ->with('category')
            ->orderBy('data', 'desc')
            ->paginate(20);
            
        return view('financial.banks.extrato', compact('bank', 'transactions'));
    }

    /**
     * API endpoint para listar bancos
     */
    public function apiIndex()
    {
        $banks = Bank::forUser(Auth::id())
            ->active()
            ->get(['id', 'nome', 'banco', 'logo_url', 'saldo_atual']);
            
        return response()->json($banks);
    }

    /**
     * API endpoint para obter todos os bancos ativos
     */
    public function getAll()
    {
        $banks = Bank::forUser(Auth::id())
            ->active()
            ->orderBy('nome')
            ->get(['id', 'nome', 'banco', 'logo_url', 'saldo_atual']);
            
        return response()->json($banks);
    }

    /**
     * API endpoint para obter transações de um banco
     */
    public function getTransactions(Request $request, Bank $bank)
    {
        // Log da requisição
        \Log::info('getTransactions chamado para banco ID: ' . $bank->id);
        \Log::info('User ID logado: ' . Auth::id());
        \Log::info('Bank user_id: ' . $bank->user_id);
        
        // Verificar se o banco pertence ao usuário logado
        if ($bank->user_id !== Auth::id()) {
            \Log::error('Acesso negado para banco ID: ' . $bank->id);
            return response()->json(['error' => 'Acesso negado.'], 403);
        }
        
        $limit = $request->get('limit', 10);
        \Log::info('Limit definido: ' . $limit);
        
        try {
            $transactions = $bank->transactions()
                ->with('category')
                ->orderBy('data', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($transaction) {
                    return [
                        'id' => $transaction->id,
                        'descricao' => $transaction->descricao,
                        'valor' => $transaction->valor,
                        'valor_formatado' => number_format($transaction->valor, 2, ',', '.'),
                        'tipo' => $transaction->tipo,
                        'data' => $transaction->data->format('d/m/Y'),
                        'category' => $transaction->category ? $transaction->category->nome : null,
                        'status' => $transaction->status
                    ];
                });
            
            \Log::info('Transações encontradas: ' . $transactions->count());
            
            // Retornar no formato esperado pelo JavaScript
            return response()->json([
                'success' => true,
                'transactions' => $transactions
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao buscar transações: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Erro interno do servidor'
            ], 500);
        }
    }
}
