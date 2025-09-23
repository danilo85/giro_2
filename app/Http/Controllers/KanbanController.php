<?php

namespace App\Http\Controllers;

use App\Models\EtapaKanban;
use App\Models\Orcamento;
use App\Models\Projeto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KanbanController extends Controller
{
    /**
     * Exibe o painel Kanban principal
     */
    public function index()
    {
        $user = Auth::user();
        
        // Buscar etapas ativas ordenadas
        $etapas = EtapaKanban::ativas()->ordenadas()->get();
        
        // Buscar apenas orçamentos aprovados e quitados do usuário
        // e criar projetos automaticamente se não existirem
        $orcamentos = Orcamento::with(['cliente'])
            ->whereHas('cliente', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereIn('status', ['aprovado', 'quitado'])
            ->get();
        
        // Criar projetos automaticamente para orçamentos que não têm projeto
        $primeiraEtapa = $etapas->first();
        if ($primeiraEtapa) {
            foreach ($orcamentos as $orcamento) {
                $projetoExistente = Projeto::where('orcamento_id', $orcamento->id)->first();
                if (!$projetoExistente) {
                    $proximaPosicao = Projeto::where('etapa_id', $primeiraEtapa->id)->max('posicao') + 1;
                    Projeto::create([
                        'orcamento_id' => $orcamento->id,
                        'etapa_id' => $primeiraEtapa->id,
                        'posicao' => $proximaPosicao,
                        'moved_at' => now()
                    ]);
                }
            }
        }
        
        // Buscar projetos do usuário apenas com orçamentos aprovados e quitados
        $projetosCollection = Projeto::with(['orcamento.cliente', 'orcamento.pagamentos', 'etapa'])
            ->whereHas('orcamento.cliente', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('orcamento', function($query) {
                $query->whereIn('status', ['aprovado', 'quitado']);
            })
            ->ordenadosPorPosicao()
            ->get();
        
        // Agrupar projetos por etapa_id para a view Blade
        $projetos = $projetosCollection->groupBy('etapa_id');
        
        // Array simples para JavaScript
        $projetosArray = $projetosCollection->toArray();
        
        // Calcular valor a receber (orçamentos aprovados e quitados menos pagamentos)
        $valorTotalOrcamentos = Orcamento::whereHas('cliente', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereIn('status', ['aprovado', 'quitado'])
            ->sum('valor_total');
        
        $valorPagamentos = DB::table('pagamentos')
            ->join('orcamentos', 'pagamentos.orcamento_id', '=', 'orcamentos.id')
            ->join('clientes', 'orcamentos.cliente_id', '=', 'clientes.id')
            ->where('clientes.user_id', $user->id)
            ->whereIn('orcamentos.status', ['aprovado', 'quitado'])
            ->sum('pagamentos.valor');
        
        $valorRestanteReceber = $valorTotalOrcamentos - $valorPagamentos;
        
        return view('kanban.index', compact('etapas', 'projetos', 'projetosArray', 'valorRestanteReceber'));
    }
    
    /**
     * API: Retorna dados do Kanban em JSON
     */
    public function apiIndex()
    {
        $user = Auth::user();
        
        $etapas = EtapaKanban::ativas()->ordenadas()->get();
        
        $projetos = Projeto::with(['orcamento.cliente', 'orcamento.pagamentos', 'etapa'])
            ->whereHas('orcamento.cliente', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('orcamento', function($query) {
                $query->whereIn('status', ['aprovado', 'quitado']);
            })
            ->ordenadosPorPosicao()
            ->get();
        
        return response()->json([
            'etapas' => $etapas,
            'projetos' => $projetos
        ]);
    }
    
    /**
     * API: Move projeto entre etapas
     */
    public function moverProjeto(Request $request)
    {
        $request->validate([
            'projeto_id' => 'required|exists:projetos,id',
            'etapa_id' => 'required|exists:etapas_kanban,id',
            'posicao' => 'required|integer|min:0'
        ]);
        
        try {
            DB::beginTransaction();
            
            $projeto = Projeto::findOrFail($request->projeto_id);
            
            // Verificar se o usuário tem permissão para mover este projeto
            if (!$this->userCanAccessProject($projeto)) {
                return response()->json(['error' => 'Acesso negado'], 403);
            }
            
            $etapaAnterior = $projeto->etapa_id;
            $posicaoAnterior = $projeto->posicao;
            
            // Atualizar posições na etapa de origem
            if ($etapaAnterior) {
                Projeto::where('etapa_id', $etapaAnterior)
                    ->where('posicao', '>', $posicaoAnterior)
                    ->decrement('posicao');
            }
            
            // Atualizar posições na etapa de destino
            Projeto::where('etapa_id', $request->etapa_id)
                ->where('posicao', '>=', $request->posicao)
                ->increment('posicao');
            
            // Mover o projeto
            $projeto->update([
                'etapa_id' => $request->etapa_id,
                'posicao' => $request->posicao,
                'moved_at' => now()
            ]);
            
            DB::commit();
            
            Log::info('Projeto movido', [
                'projeto_id' => $projeto->id,
                'etapa_anterior' => $etapaAnterior,
                'etapa_nova' => $request->etapa_id,
                'posicao' => $request->posicao,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Projeto movido com sucesso'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao mover projeto', [
                'error' => $e->getMessage(),
                'projeto_id' => $request->projeto_id,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => 'Erro interno do servidor'
            ], 500);
        }
    }
    
    /**
     * API: Cria projeto automaticamente quando orçamento é aprovado
     */
    public function criarProjetoAutomatico(Request $request)
    {
        $request->validate([
            'orcamento_id' => 'required|exists:orcamentos,id'
        ]);
        
        try {
            $orcamento = Orcamento::findOrFail($request->orcamento_id);
            
            // Verificar se o usuário tem permissão
            if (!$this->userCanAccessOrcamento($orcamento)) {
                return response()->json(['error' => 'Acesso negado'], 403);
            }
            
            // Verificar se já existe projeto para este orçamento
            $projetoExistente = Projeto::where('orcamento_id', $orcamento->id)->first();
            if ($projetoExistente) {
                return response()->json([
                    'error' => 'Já existe um projeto para este orçamento'
                ], 400);
            }
            
            // Buscar primeira etapa ativa
            $primeiraEtapa = EtapaKanban::ativas()->ordenadas()->first();
            if (!$primeiraEtapa) {
                return response()->json([
                    'error' => 'Nenhuma etapa ativa encontrada'
                ], 400);
            }
            
            // Calcular próxima posição na primeira etapa
            $proximaPosicao = Projeto::where('etapa_id', $primeiraEtapa->id)->max('posicao') + 1;
            
            // Criar projeto
            $projeto = Projeto::create([
                'orcamento_id' => $orcamento->id,
                'etapa_id' => $primeiraEtapa->id,
                'posicao' => $proximaPosicao,
                'moved_at' => now()
            ]);
            
            Log::info('Projeto criado automaticamente', [
                'projeto_id' => $projeto->id,
                'orcamento_id' => $orcamento->id,
                'etapa_id' => $primeiraEtapa->id,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Projeto criado com sucesso',
                'projeto' => $projeto->load(['orcamento.cliente', 'etapa'])
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar projeto automaticamente', [
                'error' => $e->getMessage(),
                'orcamento_id' => $request->orcamento_id,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => 'Erro interno do servidor'
            ], 500);
        }
    }
    
    /**
     * Gestão de Etapas - Listar
     */
    public function etapas()
    {
        $etapas = EtapaKanban::ordenadas()->get();
        return view('kanban.etapas.index', compact('etapas'));
    }
    
    /**
     * Gestão de Etapas - Criar
     */
    public function criarEtapa(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cor' => 'required|string|max:7',
            'ordem' => 'required|integer|min:1'
        ]);
        
        try {
            // Ajustar ordens existentes
            EtapaKanban::where('ordem', '>=', $request->ordem)
                ->increment('ordem');
            
            $etapa = EtapaKanban::create([
                'nome' => $request->nome,
                'cor' => $request->cor,
                'ordem' => $request->ordem,
                'ativa' => true
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Etapa criada com sucesso',
                'etapa' => $etapa
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar etapa', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => 'Erro interno do servidor'
            ], 500);
        }
    }
    
    /**
     * Gestão de Etapas - Atualizar
     */
    public function atualizarEtapa(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cor' => 'required|string|max:7',
            'ordem' => 'required|integer|min:1',
            'ativa' => 'boolean'
        ]);
        
        try {
            $etapa = EtapaKanban::findOrFail($id);
            $ordemAnterior = $etapa->ordem;
            
            // Se a ordem mudou, ajustar outras etapas
            if ($ordemAnterior != $request->ordem) {
                if ($request->ordem > $ordemAnterior) {
                    EtapaKanban::where('ordem', '>', $ordemAnterior)
                        ->where('ordem', '<=', $request->ordem)
                        ->where('id', '!=', $id)
                        ->decrement('ordem');
                } else {
                    EtapaKanban::where('ordem', '>=', $request->ordem)
                        ->where('ordem', '<', $ordemAnterior)
                        ->where('id', '!=', $id)
                        ->increment('ordem');
                }
            }
            
            $etapa->update([
                'nome' => $request->nome,
                'cor' => $request->cor,
                'ordem' => $request->ordem,
                'ativa' => $request->ativa ?? true
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Etapa atualizada com sucesso',
                'etapa' => $etapa
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar etapa', [
                'error' => $e->getMessage(),
                'etapa_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => 'Erro interno do servidor'
            ], 500);
        }
    }
    
    /**
     * Gestão de Etapas - Excluir
     */
    public function excluirEtapa($id)
    {
        try {
            $etapa = EtapaKanban::findOrFail($id);
            
            // Verificar se existem projetos nesta etapa
            $projetosNaEtapa = Projeto::where('etapa_id', $id)->count();
            if ($projetosNaEtapa > 0) {
                return response()->json([
                    'error' => 'Não é possível excluir esta etapa pois existem projetos associados a ela'
                ], 400);
            }
            
            // Ajustar ordens das etapas restantes
            EtapaKanban::where('ordem', '>', $etapa->ordem)
                ->decrement('ordem');
            
            $etapa->delete();
            
            Log::info('Etapa excluída', [
                'etapa_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Etapa excluída com sucesso'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao excluir etapa', [
                'error' => $e->getMessage(),
                'etapa_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Gestão de Etapas - Ativar/Desativar
     */
    public function toggleEtapaStatus(Request $request, $id)
    {
        $request->validate([
            'ativo' => 'required|boolean'
        ]);
        
        try {
            $etapa = EtapaKanban::findOrFail($id);
            
            $etapa->update([
                'ativa' => $request->ativo
            ]);
            
            Log::info('Status da etapa alterado', [
                'etapa_id' => $id,
                'novo_status' => $request->ativo,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => $request->ativo ? 'Etapa ativada com sucesso' : 'Etapa desativada com sucesso',
                'etapa' => $etapa
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao alterar status da etapa', [
                'error' => $e->getMessage(),
                'etapa_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'error' => 'Erro interno do servidor'
            ], 500);
        }
    }

    /**
     * Verifica se o usuário pode acessar o projeto
     */
    private function userCanAccessProject(Projeto $projeto): bool
    {
        $user = Auth::user();
        return $projeto->orcamento->cliente->user_id === $user->id;
    }
    
    /**
     * Verifica se o usuário pode acessar o orçamento
     */
    private function userCanAccessOrcamento(Orcamento $orcamento): bool
    {
        $user = Auth::user();
        return $orcamento->cliente->user_id === $user->id;
    }
}
