<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\Cliente;
use App\Models\Autor;
use App\Models\ModeloProposta;
use App\Models\HistoricoOrcamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrcamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Orcamento::with(['cliente', 'autores'])
            ->whereHas('cliente', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc');

        // Filtro padrão: excluir finalizados a menos que seja especificamente solicitado
        if (!$request->filled('status') && !$request->has('incluir_finalizados')) {
            $query->whereIn('status', ['rascunho', 'aprovado', 'quitado', 'analisando']);
        }

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', "%{$search}%")
                  ->orWhere('descricao', 'like', "%{$search}%")
                  ->orWhereHas('cliente', function($clienteQuery) use ($search) {
                      $clienteQuery->where('nome', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('autores', function($autorQuery) use ($search) {
                      $autorQuery->where('nome', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orcamentos = $query->paginate(15);
        $clientes = Cliente::forUser(Auth::id())->orderBy('nome')->get();
        $statusOptions = Orcamento::getStatusOptions();

        // Calcular estatísticas
        $orcamentosQuery = Orcamento::with(['cliente', 'autores'])
            ->whereHas('cliente', function($q) {
                $q->where('user_id', Auth::id());
            });
        
        $totalOrcamentos = $orcamentosQuery->count();
        $aprovados = $orcamentosQuery->where('status', 'aprovado')->count();
        $pendentes = $orcamentosQuery->whereIn('status', ['rascunho', 'analisando'])->count();
        
        // Calcular valores financeiros - apenas orçamentos com status 'aprovado' e 'quitado'
        $valorTotalOrcamentosValidos = Orcamento::whereHas('cliente', function($q) {
                $q->where('user_id', Auth::id());
            })
            ->whereIn('status', ['aprovado', 'quitado'])
            ->sum('valor_total');
        
        // Calcular valor total já recebido (pagamentos) apenas dos orçamentos válidos
        $valorTotalRecebido = \App\Models\Pagamento::whereHas('orcamento', function($q) {
            $q->whereHas('cliente', function($clienteQuery) {
                $clienteQuery->where('user_id', Auth::id());
            })->whereIn('status', ['aprovado', 'quitado']);
        })->sum('valor');
        
        // Calcular valor restante a receber (total dos orçamentos válidos - pagamentos recebidos)
        $valorRestanteReceber = $valorTotalOrcamentosValidos - $valorTotalRecebido;
        
        $valorTotal = $valorRestanteReceber; // Valor que aparece no card "Valor a Receber" - apenas o resultado final

        return view('orcamentos.index', compact('orcamentos', 'clientes', 'statusOptions', 'totalOrcamentos', 'aprovados', 'pendentes', 'valorTotal', 'valorTotalOrcamentosValidos', 'valorTotalRecebido', 'valorRestanteReceber'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::forUser(Auth::id())->orderBy('nome')->get();
        $autores = Autor::forUser(Auth::id())->orderBy('nome')->get();
        $modelos = ModeloProposta::forUser(Auth::id())->active()->orderBy('nome')->get();
        
        return view('orcamentos.create', compact('clientes', 'autores', 'modelos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Iniciando store de orçamento', ['request_data' => $request->all()]);
        
        // Converter valor monetário para decimal
        $valorTotal = $request->valor_total;
        if ($valorTotal) {
            // Remove caracteres de formatação (R$, espaços, pontos)
            $valorTotal = preg_replace('/[R$\s\.]/', '', $valorTotal);
            // Substitui vírgula por ponto para decimal
            $valorTotal = str_replace(',', '.', $valorTotal);
            $request->merge(['valor_total' => $valorTotal]);
        }
        
        \Log::info('Valor total convertido', ['valor_original' => $request->valor_total, 'valor_convertido' => $valorTotal]);

        // Processar cliente - verificar se é novo ou existente
        $clienteId = $request->cliente_id;
        $isNewCliente = false;
        
        if (str_starts_with($clienteId, 'new:')) {
            $isNewCliente = true;
            $nomeNovoCliente = substr($clienteId, 4); // Remove 'new:' do início
            
            // Validação para novo cliente
            $validatedData = $request->validate([
                'titulo' => 'required|string|max:255',
                'descricao' => 'nullable|string',
                'valor_total' => 'required|numeric|min:0',
                'prazo_dias' => 'required|integer|min:1',
                'data_orcamento' => 'required|date',
                'data_validade' => 'required|date',
                'status' => 'nullable|string|in:rascunho,analisando,rejeitado,aprovado,finalizado,pago',
                'observacoes' => 'nullable|string',
                'autores' => 'array',
                'autores.*' => 'string' // Permite tanto IDs existentes quanto 'new:nome'
            ]);
        } else {
            // Validação para cliente existente
            $validatedData = $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'titulo' => 'required|string|max:255',
                'descricao' => 'nullable|string',
                'valor_total' => 'required|numeric|min:0',
                'prazo_dias' => 'required|integer|min:1',
                'data_orcamento' => 'required|date',
                'data_validade' => 'required|date',
                'status' => 'nullable|string|in:rascunho,analisando,rejeitado,aprovado,finalizado,pago',
                'observacoes' => 'nullable|string',
                'autores' => 'array',
                'autores.*' => 'string' // Permite tanto IDs existentes quanto 'new:nome'
            ]);
            
            // Verificar se o cliente pertence ao usuário
            $cliente = Cliente::forUser(Auth::id())->findOrFail($request->cliente_id);
        }

        \Log::info('Tentando criar orçamento', ['validated_data' => $validatedData, 'is_new_cliente' => $isNewCliente]);
        
        DB::beginTransaction();
        try {
            // Criar cliente automaticamente se necessário
            if ($isNewCliente) {
                $cliente = Cliente::create([
                    'user_id' => Auth::id(),
                    'nome' => $nomeNovoCliente,
                    'novo' => true,
                    'cadastro_incompleto' => true
                ]);
                \Log::info('Novo cliente criado automaticamente', ['cliente_id' => $cliente->id, 'nome' => $nomeNovoCliente]);
                $clienteIdFinal = $cliente->id;
            } else {
                $clienteIdFinal = $request->cliente_id;
            }
            
            $orcamento = Orcamento::create([
                'cliente_id' => $clienteIdFinal,
                'titulo' => $request->titulo,
                'descricao' => $request->descricao,
                'valor_total' => $request->valor_total,
                'prazo_dias' => $request->prazo_dias,
                'data_orcamento' => $request->data_orcamento,
                'data_validade' => $request->data_validade,
                'status' => $request->status ?? 'rascunho',
                'observacoes' => $request->observacoes
            ]);
            \Log::info('Orçamento criado com sucesso', ['orcamento_id' => $orcamento->id, 'cliente_id' => $clienteIdFinal]);

            // Processar e associar autores (incluindo criação de novos)
            if ($request->filled('autores')) {
                $autoresIds = [];
                
                foreach ($request->autores as $autorId) {
                    if (str_starts_with($autorId, 'new:')) {
                        // Criar novo autor
                        $nomeNovoAutor = substr($autorId, 4); // Remove 'new:' do início
                        $novoAutor = Autor::create([
                            'nome' => $nomeNovoAutor,
                            'user_id' => Auth::id()
                        ]);
                        $autoresIds[] = $novoAutor->id;
                        \Log::info('Novo autor criado durante criação', ['autor_id' => $novoAutor->id, 'nome' => $nomeNovoAutor]);
                    } else {
                        // Autor existente
                        $autoresIds[] = $autorId;
                    }
                }
                
                // Verificar se todos os autores pertencem ao usuário
                $autores = Autor::forUser(Auth::id())->whereIn('id', $autoresIds)->pluck('id');
                $orcamento->autores()->attach($autores);
                \Log::info('Autores associados', ['autores' => $autoresIds]);
            }

            // Registrar no histórico
            HistoricoOrcamento::create([
                'user_id' => Auth::id(),
                'orcamento_id' => $orcamento->id,
                'acao' => 'criado',
                'descricao' => 'Orçamento criado',
                'dados_novos' => $orcamento->toArray()
            ]);
            \Log::info('Histórico registrado');

            DB::commit();

            \Log::info('Redirecionando para show', ['route' => 'orcamentos.show', 'id' => $orcamento->id]);
            return redirect()->route('orcamentos.show', $orcamento)
                           ->with('success', 'Orçamento criado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Erro ao criar orçamento', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()->withErrors(['error' => 'Erro ao criar orçamento: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        $orcamento->load(['cliente', 'autores', 'pagamentos.bank', 'historico.user']);
        
        return view('orcamentos.show', compact('orcamento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        $clientes = Cliente::forUser(Auth::id())->orderBy('nome')->get();
        $autores = Autor::forUser(Auth::id())->orderBy('nome')->get();
        $modelos = ModeloProposta::forUser(Auth::id())->active()->orderBy('nome')->get();
        $orcamento->load(['autores']);
        
        return view('orcamentos.edit', compact('orcamento', 'clientes', 'autores', 'modelos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        // Processar data_validade se estiver no formato brasileiro
        if ($request->has('data_validade') && $request->data_validade) {
            $dataValidade = $request->data_validade;
            // Se está no formato dd/mm/yyyy, converter para yyyy-mm-dd
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dataValidade)) {
                $dataValidade = \Carbon\Carbon::createFromFormat('d/m/Y', $dataValidade)->format('Y-m-d');
                $request->merge(['data_validade' => $dataValidade]);
            }
        }

        // Converter valor formatado para decimal
        if ($request->has('valor_total')) {
            $valorTotal = str_replace(['.', ','], ['', '.'], $request->valor_total);
            $request->merge(['valor_total' => $valorTotal]);
        }
        
        \Log::info('Atualizando orçamento', ['orcamento_id' => $orcamento->id, 'dados' => $request->all()]);

        // Processar cliente - verificar se é novo ou existente
        $clienteId = $request->cliente_id;
        $isNewCliente = false;
        
        if (str_starts_with($clienteId, 'new:')) {
            $isNewCliente = true;
            $nomeNovoCliente = substr($clienteId, 4); // Remove 'new:' do início
            
            // Validação para novo cliente
            $request->validate([
                'titulo' => 'required|string|max:255',
                'descricao' => 'nullable|string',
                'valor_total' => 'required|numeric|min:0',
                'prazo_dias' => 'required|integer|min:1',
                'data_validade' => 'required|date',
                'status' => ['required', Rule::in(array_keys(Orcamento::getStatusOptions()))],
                'observacoes' => 'nullable|string',
                'autores' => 'array',
                'autores.*' => 'string' // Permite tanto IDs existentes quanto 'new:nome'
            ]);
        } else {
            // Validação para cliente existente
            $request->validate([
                'cliente_id' => 'required|exists:clientes,id',
                'titulo' => 'required|string|max:255',
                'descricao' => 'nullable|string',
                'valor_total' => 'required|numeric|min:0',
                'prazo_dias' => 'required|integer|min:1',
                'data_validade' => 'required|date',
                'status' => ['required', Rule::in(array_keys(Orcamento::getStatusOptions()))],
                'observacoes' => 'nullable|string',
                'autores' => 'array',
                'autores.*' => 'string' // Permite tanto IDs existentes quanto 'new:nome'
            ]);
            
            // Verificar se o cliente pertence ao usuário
            $cliente = Cliente::forUser(Auth::id())->findOrFail($request->cliente_id);
        }

        DB::beginTransaction();
        try {
            $dadosAnteriores = $orcamento->toArray();

            // Se é um novo cliente, criar primeiro
            if ($isNewCliente) {
                $cliente = Cliente::create([
                    'nome' => $nomeNovoCliente,
                    'user_id' => Auth::id()
                ]);
                $clienteId = $cliente->id;
                \Log::info('Novo cliente criado durante atualização', ['cliente_id' => $cliente->id, 'nome' => $nomeNovoCliente]);
            }

            $orcamento->update([
                'cliente_id' => $clienteId,
                'titulo' => $request->titulo,
                'descricao' => $request->descricao,
                'valor_total' => $request->valor_total,
                'prazo_dias' => $request->prazo_dias,
                'data_validade' => $request->data_validade,
                'status' => $request->status,
                'observacoes' => $request->observacoes
            ]);
            \Log::info('Orçamento atualizado com sucesso', ['orcamento_id' => $orcamento->id]);

            // Processar e atualizar autores (incluindo criação de novos)
            if ($request->filled('autores')) {
                $autoresIds = [];
                
                foreach ($request->autores as $autorId) {
                    if (str_starts_with($autorId, 'new:')) {
                        // Criar novo autor
                        $nomeNovoAutor = substr($autorId, 4); // Remove 'new:' do início
                        $novoAutor = Autor::create([
                            'nome' => $nomeNovoAutor,
                            'user_id' => Auth::id()
                        ]);
                        $autoresIds[] = $novoAutor->id;
                        \Log::info('Novo autor criado durante atualização', ['autor_id' => $novoAutor->id, 'nome' => $nomeNovoAutor]);
                    } else {
                        // Autor existente
                        $autoresIds[] = $autorId;
                    }
                }
                
                // Verificar se todos os autores pertencem ao usuário
                $autores = Autor::forUser(Auth::id())->whereIn('id', $autoresIds)->pluck('id');
                $orcamento->autores()->sync($autores);
                \Log::info('Autores atualizados', ['autores' => $autoresIds]);
            } else {
                $orcamento->autores()->detach();
                \Log::info('Autores removidos do orçamento');
            }

            // Registrar no histórico
            HistoricoOrcamento::create([
                'user_id' => Auth::id(),
                'orcamento_id' => $orcamento->id,
                'acao' => 'atualizado',
                'descricao' => 'Orçamento atualizado',
                'dados_anteriores' => $dadosAnteriores,
                'dados_novos' => $orcamento->fresh()->toArray()
            ]);
            \Log::info('Histórico de atualização registrado');

            DB::commit();

            \Log::info('Redirecionando para show após atualização', ['route' => 'orcamentos.show', 'id' => $orcamento->id]);
            return redirect()->route('orcamentos.show', $orcamento)
                           ->with('success', 'Orçamento atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Erro ao atualizar orçamento', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()->withErrors(['error' => 'Erro ao atualizar orçamento: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        // Não permitir exclusão se houver pagamentos
        if ($orcamento->pagamentos()->count() > 0) {
            return back()->withErrors(['error' => 'Não é possível excluir orçamento com pagamentos registrados.']);
        }

        DB::beginTransaction();
        try {
            // Registrar no histórico antes de excluir
            HistoricoOrcamento::create([
                'user_id' => Auth::id(),
                'orcamento_id' => $orcamento->id,
                'acao' => 'excluido',
                'descricao' => 'Orçamento excluído',
                'dados_anteriores' => $orcamento->toArray()
            ]);

            $orcamento->autores()->detach();
            $orcamento->delete();

            DB::commit();

            return redirect()->route('orcamentos.index')
                           ->with('success', 'Orçamento excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Erro ao excluir orçamento: ' . $e->getMessage()]);
        }
    }

    /**
     * Quitar orçamento (API)
     */
    public function quitar(Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        try {
            $orcamento->quitar();
            
            return response()->json([
                'success' => true,
                'message' => 'Orçamento quitado com sucesso!',
                'orcamento' => $orcamento->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao quitar orçamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Aprovar orçamento (API)
     */
    public function aprovar(Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        try {
            $orcamento->aprovar();
            
            return response()->json([
                'success' => true,
                'message' => 'Orçamento aprovado com sucesso!',
                'orcamento' => $orcamento->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao aprovar orçamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rejeitar orçamento (API)
     */
    public function rejeitar(Request $request, Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        $request->validate([
            'motivo' => 'nullable|string|max:500'
        ]);

        try {
            $orcamento->rejeitar($request->motivo);
            
            return response()->json([
                'success' => true,
                'message' => 'Orçamento rejeitado com sucesso!',
                'orcamento' => $orcamento->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao rejeitar orçamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualizar status do orçamento (API e Web)
     */
    public function atualizarStatus(Request $request, Orcamento $orcamento)
    {
        // Verificar se o usuário está autenticado e se o orçamento pertence ao usuário
        if (Auth::check() && $orcamento->cliente->user_id !== Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Não autorizado'], 403);
            }
            abort(403);
        }

        $request->validate([
            'status' => ['required', Rule::in(array_keys(Orcamento::getStatusOptions()))],
            'descricao' => 'nullable|string|max:500'
        ]);

        try {
            $orcamento->atualizarStatus($request->status, $request->descricao);
            
            // Se é uma requisição AJAX, retorna JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status atualizado com sucesso!',
                    'orcamento' => $orcamento->fresh()
                ]);
            }
            
            // Se é uma requisição web tradicional, redireciona
            return redirect()->back()->with('success', 'Status atualizado com sucesso!');
            
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar status: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->withErrors(['error' => 'Erro ao atualizar status: ' . $e->getMessage()]);
        }
    }

    /**
     * Obter valor atualizado do card financeiro (AJAX)
     */
    public function getValorFinanceiro()
    {
        // Calcular valor total dos orçamentos válidos (aprovados e quitados)
        $valorTotalOrcamentosValidos = Orcamento::whereHas('cliente', function($query) {
            $query->where('user_id', Auth::id());
        })
        ->whereIn('status', ['aprovado', 'quitado'])
        ->sum('valor_total');

        // Calcular valor total recebido (pagamentos dos orçamentos válidos)
        $valorTotalRecebido = Pagamento::whereHas('orcamento', function($query) {
            $query->whereHas('cliente', function($subQuery) {
                $subQuery->where('user_id', Auth::id());
            })
            ->whereIn('status', ['aprovado', 'quitado']);
        })->sum('valor');

        // Calcular valor restante a receber
        $valorRestanteReceber = $valorTotalOrcamentosValidos - $valorTotalRecebido;

        return response()->json([
            'success' => true,
            'valor_a_receber' => $valorRestanteReceber,
            'valor_formatado' => 'R$ ' . number_format($valorRestanteReceber, 2, ',', '.')
        ]);
    }

    /**
     * Visualização pública do orçamento
     */
    public function showPublic($token)
    {
        $orcamento = Orcamento::where('token_publico', $token)
                             ->with(['cliente.user', 'autores'])
                             ->firstOrFail();
        
        return view('orcamentos.public', compact('orcamento'));
    }

    /**
     * Visualização interna do orçamento (mesmo layout da página pública)
     */
    public function showInternal(Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        // Carregar relacionamentos necessários
        $orcamento->load(['cliente.user', 'autores']);
        
        return view('orcamentos.internal', compact('orcamento'));
    }

    /**
     * Aprovar orçamento via página pública (API)
     */
    public function aprovarPublico($token)
    {
        $orcamento = Orcamento::where('token_publico', $token)->firstOrFail();

        try {
            $orcamento->aprovar();
            
            return response()->json([
                'success' => true,
                'message' => 'Orçamento aprovado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao aprovar orçamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rejeitar orçamento via página pública (API)
     */
    public function rejeitarPublico(Request $request, $token)
    {
        $orcamento = Orcamento::where('token_publico', $token)->firstOrFail();

        $request->validate([
            'motivo' => 'nullable|string|max:500'
        ]);

        try {
            $orcamento->rejeitar($request->motivo);
            
            return response()->json([
                'success' => true,
                'message' => 'Orçamento rejeitado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao rejeitar orçamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retornar orçamento ao status analisando via página pública (API)
     */
    public function analisarPublico($token)
    {
        $orcamento = Orcamento::where('token_publico', $token)->firstOrFail();

        try {
            $orcamento->atualizarStatus(Orcamento::STATUS_ANALISANDO, 'Orçamento retornado ao status analisando');
            
            return response()->json([
                'success' => true,
                'message' => 'Orçamento retornado ao status analisando com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao retornar orçamento ao status analisando: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Página de gerenciamento de imagens do perfil
     */
    public function profileImages(Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            abort(403);
        }

        return view('orcamentos.profile-images', compact('orcamento'));
    }

    /**
     * Upload de QR Code
     */
    public function uploadQrCode(Request $request, Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        $request->validate([
            'qrcode' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            // Deletar imagem anterior se existir
            if ($orcamento->qrcode_image) {
                Storage::disk('public')->delete($orcamento->qrcode_image);
            }

            // Salvar nova imagem
            $path = $request->file('qrcode')->store('orcamentos/qrcodes', 'public');
            
            $orcamento->update([
                'qrcode_image' => $path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'QR Code enviado com sucesso!',
                'image_url' => Storage::disk('public')->url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload de Logo
     */
    public function uploadLogo(Request $request, Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            // Deletar imagem anterior se existir
            if ($orcamento->logo_image) {
                Storage::disk('public')->delete($orcamento->logo_image);
            }

            // Salvar nova imagem
            $path = $request->file('logo')->store('orcamentos/logos', 'public');
            
            $orcamento->update([
                'logo_image' => $path
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Logo enviado com sucesso!',
                'image_url' => Storage::disk('public')->url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar logo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deletar QR Code
     */
    public function deleteQrCode(Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        try {
            if ($orcamento->qrcode_image) {
                Storage::disk('public')->delete($orcamento->qrcode_image);
                $orcamento->update(['qrcode_image' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'QR Code removido com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover QR Code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deletar Logo
     */
    public function deleteLogo(Orcamento $orcamento)
    {
        // Verificar se o orçamento pertence ao usuário
        if ($orcamento->cliente->user_id !== Auth::id()) {
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        try {
            if ($orcamento->logo_image) {
                Storage::disk('public')->delete($orcamento->logo_image);
                $orcamento->update(['logo_image' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Logo removido com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover logo: ' . $e->getMessage()
            ], 500);
        }
    }

}
