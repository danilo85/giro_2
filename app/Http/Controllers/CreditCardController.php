<?php

namespace App\Http\Controllers;

use App\Models\CreditCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CreditCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $creditCards = CreditCard::forUser(Auth::id())->active()->get();
        return view('financial.credit-cards.index', compact('creditCards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('financial.credit-cards.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Converter valores monetários do formato brasileiro para decimal
        $limite = str_replace(['.', ','], ['', '.'], $request->limite ?? '0');
        $limiteUtilizado = str_replace(['.', ','], ['', '.'], $request->limite_utilizado ?? '0');
        
        $validator = Validator::make(array_merge($request->all(), [
            'limite_decimal' => $limite,
            'limite_utilizado_decimal' => $limiteUtilizado
        ]), [
            'nome' => 'required|string|max:255',
            'bandeira' => 'required|string|max:100',
            'numero' => 'nullable|string|min:13|max:19',
            'limite_decimal' => 'required|numeric|min:0',
            'limite_utilizado_decimal' => 'required|numeric|min:0',
            'dia_vencimento' => 'required|integer|min:1|max:31',
            'dia_fechamento' => 'required|integer|min:1|max:31',
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

        // Validar se limite utilizado não é maior que o limite total
        if ($limiteUtilizado > $limite) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['limite_utilizado' => ['O limite utilizado não pode ser maior que o limite total.']]
                ], 422);
            }
            return redirect()->back()
                ->withErrors(['limite_utilizado' => 'O limite utilizado não pode ser maior que o limite total.'])
                ->withInput();
        }

        $creditCard = CreditCard::create([
            'user_id' => Auth::id(),
            'nome_cartao' => $request->nome,
            'bandeira' => $request->bandeira,
            'numero' => $request->numero,
            'limite_total' => $limite,
            'limite_utilizado' => $limiteUtilizado,
            'data_fechamento' => $request->dia_fechamento,
            'data_vencimento' => $request->dia_vencimento,
            'observacoes' => $request->observacoes,
            'ativo' => $request->has('ativo') ? true : false
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cartão de crédito criado com sucesso!',
                'creditCard' => $creditCard
            ]);
        }
        
        return redirect()->route('financial.credit-cards.index')
            ->with('success', 'Cartão de crédito criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(CreditCard $creditCard)
    {
        $this->authorize('view', $creditCard);
        
        $transactions = $creditCard->transactions()
            ->with('category')
            ->orderBy('data', 'desc')
            ->paginate(20);
            
        return view('financial.credit-cards.show', compact('creditCard', 'transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CreditCard $creditCard)
    {
        $this->authorize('update', $creditCard);
        return view('financial.credit-cards.edit', compact('creditCard'));
    }

    /**
     * Função para limpar e converter valores monetários
     */
    private function cleanMonetaryValue($value)
    {
        if (empty($value) || $value === null) {
            return 0;
        }
        
        // Remove todos os caracteres não numéricos exceto vírgula e ponto
        $cleaned = preg_replace('/[^0-9.,]/', '', $value);
        
        // Se contém vírgula, assume formato brasileiro (1.234,56)
        if (strpos($cleaned, ',') !== false) {
            // Remove pontos (separadores de milhares) e substitui vírgula por ponto
            $cleaned = str_replace('.', '', $cleaned);
            $cleaned = str_replace(',', '.', $cleaned);
        }
        // Se contém apenas pontos, verifica se é separador decimal ou de milhares
        elseif (substr_count($cleaned, '.') === 1) {
            // Se há apenas um ponto e está nas últimas 3 posições, é decimal
            $dotPosition = strrpos($cleaned, '.');
            if (strlen($cleaned) - $dotPosition <= 3) {
                // É separador decimal, mantém
            } else {
                // É separador de milhares, remove
                $cleaned = str_replace('.', '', $cleaned);
            }
        }
        // Se há múltiplos pontos, remove todos (são separadores de milhares)
        elseif (substr_count($cleaned, '.') > 1) {
            $cleaned = str_replace('.', '', $cleaned);
        }
        
        return (float) $cleaned;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CreditCard $creditCard)
    {
        $this->authorize('update', $creditCard);

        try {
            $request->validate([
                'nome_cartao' => 'required|string|max:255',
                'bandeira' => 'required|string|max:255',
                'numero' => 'nullable|string|max:20', // Tornado não obrigatório
                'limite_total' => 'required|numeric|min:0',
                'limite_utilizado' => 'nullable|numeric|min:0',
                'data_vencimento' => 'nullable|integer|min:1|max:31',
                'data_fechamento' => 'nullable|integer|min:1|max:31',
                'observacoes' => 'nullable|string',
                'ativo' => 'boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        // Converter valores monetários usando função robusta
        $limite = $this->cleanMonetaryValue($request->limite_total);
        $limite_utilizado = $this->cleanMonetaryValue($request->limite_utilizado);

        $creditCard->update([
            'nome_cartao' => $request->nome_cartao,
            'bandeira' => $request->bandeira,
            'numero' => $request->numero,
            'limite_total' => $limite,
            'limite_utilizado' => $limite_utilizado,
            'data_vencimento' => $request->data_vencimento,
            'data_fechamento' => $request->data_fechamento,
            'observacoes' => $request->observacoes,
            'ativo' => $request->has('ativo'),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cartão de crédito atualizado com sucesso!',
                'creditCard' => $creditCard
            ]);
        }
        
        return redirect()->route('financial.credit-cards.index')
            ->with('success', 'Cartão de crédito atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CreditCard $creditCard)
    {
        $this->authorize('delete', $creditCard);
        
        try {
            // Verificar se há transações associadas
            $hasTransactions = $creditCard->transactions()->exists();
            
            if ($hasTransactions) {
                // Se há transações, fazer soft delete
                $creditCard->update(['ativo' => false]);
                $message = 'Cartão de crédito desativado com sucesso! (Mantido no sistema devido a transações associadas)';
            } else {
                // Se não há transações, fazer hard delete
                $creditCard->delete();
                $message = 'Cartão de crédito excluído com sucesso!';
            }
            
            // Verificar se é uma requisição AJAX
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            return redirect()->route('financial.credit-cards.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao excluir cartão de crédito: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('financial.credit-cards.index')
                ->with('error', 'Erro ao excluir cartão de crédito.');
        }
    }

    /**
     * Display the credit card statement.
     */
    public function statement(CreditCard $creditCard)
    {
        $this->authorize('view', $creditCard);
        
        // Buscar transações do cartão de crédito ordenadas por data
        $transactions = $creditCard->transactions()
            ->with('category')
            ->orderBy('data', 'desc')
            ->paginate(20);
            
        return view('financial.credit-cards.statement', compact('creditCard', 'transactions'));
    }

    /**
     * API endpoint para obter informações do cartão
     */
    public function getInfo(CreditCard $creditCard)
    {
        $this->authorize('view', $creditCard);
        
        return response()->json([
            'limite_total' => $creditCard->limite_total,
            'limite_utilizado' => $creditCard->limite_utilizado,
            'limite_disponivel' => $creditCard->limite_disponivel,
            'percentual_utilizado' => $creditCard->percentual_utilizado,
            'proximo_vencimento' => $creditCard->proximo_vencimento,
            'dias_para_vencimento' => $creditCard->dias_para_vencimento
        ]);
    }

    /**
     * API endpoint para pagar fatura integral
     */
    public function pagarFatura(CreditCard $creditCard)
    {
        $this->authorize('update', $creditCard);
        
        if ($creditCard->limite_utilizado <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Não há fatura para pagar'
            ], 400);
        }

        $valorPago = $creditCard->limite_utilizado;
        $creditCard->update(['limite_utilizado' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Fatura paga com sucesso!',
            'valor_pago' => $valorPago,
            'limite_disponivel' => $creditCard->limite_total
        ]);
    }

    /**
     * API endpoint para atualizar limite utilizado
     */
    public function updateLimite(Request $request, CreditCard $creditCard)
    {
        $this->authorize('update', $creditCard);
        
        $validator = Validator::make($request->all(), [
            'valor' => 'required|numeric|min:0',
            'operacao' => 'required|in:adicionar,remover'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $creditCard->updateLimiteUtilizado($request->valor, $request->operacao);

        return response()->json([
            'success' => true,
            'limite_utilizado' => $creditCard->fresh()->limite_utilizado,
            'limite_disponivel' => $creditCard->fresh()->limite_disponivel
        ]);
    }

    /**
     * API endpoint para listar cartões de crédito
     */
    public function apiIndex()
    {
        $creditCards = CreditCard::forUser(Auth::id())
            ->active()
            ->get(['id', 'nome_cartao', 'bandeira', 'bandeira_logo_url', 'limite_total', 'limite_utilizado']);
            
        return response()->json($creditCards);
    }

    /**
     * API endpoint para obter todos os cartões de crédito ativos
     */
    public function getAll()
    {
        $creditCards = CreditCard::forUser(Auth::id())
            ->active()
            ->orderBy('nome_cartao')
            ->get(['id', 'nome_cartao', 'bandeira', 'bandeira_logo_url', 'limite_total', 'limite_utilizado', 'limite_disponivel']);
            
        return response()->json($creditCards);
    }

    /**
     * API endpoint para atualizar limite utilizado específico
     */
    public function updateUsedLimit(Request $request, CreditCard $creditCard)
    {
        $this->authorize('update', $creditCard);
        
        $validator = Validator::make($request->all(), [
            'limite_utilizado' => 'required|numeric|min:0|max:' . $creditCard->limite_total
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $creditCard->update([
            'limite_utilizado' => $request->limite_utilizado
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Limite utilizado atualizado com sucesso!',
            'limite_utilizado' => $creditCard->limite_utilizado,
            'limite_disponivel' => $creditCard->limite_disponivel
        ]);
    }

    /**
     * API endpoint para atualizar limites de todos os cartões
     */
    public function refreshLimits()
    {
        try {
            // Aqui você pode implementar a lógica para atualizar os limites
            // Por exemplo, recalcular limites utilizados baseado nas transações
            $creditCards = CreditCard::forUser(Auth::id())->active()->get();
            
            foreach ($creditCards as $card) {
                // Recalcular limite utilizado baseado nas transações não pagas
                $limiteUtilizado = $card->transactions()
                    ->where('status', '!=', 'pago')
                    ->sum('valor');
                    
                $card->update(['limite_utilizado' => $limiteUtilizado]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Limites atualizados com sucesso!',
                'cards_updated' => $creditCards->count()
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar limites: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint para obter resumo das estatísticas dos cartões
     */
    public function getSummary()
    {
        try {
            $creditCards = CreditCard::forUser(Auth::id())->active()->get();
            
            $totalLimit = $creditCards->sum('limite_total');
            $usedLimit = $creditCards->sum('limite_utilizado');
            $activeCards = $creditCards->count();
            
            return response()->json([
                'success' => true,
                'total_limit' => $totalLimit,
                'used_limit' => $usedLimit,
                'active_cards' => $activeCards,
                'available_limit' => $totalLimit - $usedLimit
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao obter resumo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint para calcular limite disponível baseado nas transações
     */
    public function calculateLimit(CreditCard $creditCard)
    {
        $this->authorize('view', $creditCard);
        
        try {
            // Recalcular limite utilizado baseado nas transações não pagas
            $limiteUtilizado = $creditCard->transactions()
                ->where('status', '!=', 'pago')
                ->sum('valor');
                
            // Atualizar o cartão com o novo limite utilizado
            $creditCard->update(['limite_utilizado' => $limiteUtilizado]);
            
            $limiteDisponivel = $creditCard->limite_total - $limiteUtilizado;
            
            return response()->json([
                'success' => true,
                'message' => 'Limite recalculado com sucesso!',
                'total_limit' => $creditCard->limite_total,
                'used_limit' => $limiteUtilizado,
                'available_limit' => $limiteDisponivel,
                'percentage_used' => $creditCard->limite_total > 0 ? ($limiteUtilizado / $creditCard->limite_total) * 100 : 0
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao calcular limite: ' . $e->getMessage()
            ], 500);
        }
    }
}
