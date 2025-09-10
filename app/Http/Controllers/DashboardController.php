<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Orcamento;
use App\Models\HistoricoOrcamento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Estatísticas de orçamentos
        $totalOrcamentos = Orcamento::forUser($userId)->count();
        $orcamentosAprovados = Orcamento::forUser($userId)->byStatus('aprovado')->count();
        $orcamentosPendentes = Orcamento::forUser($userId)->whereIn('status', ['rascunho', 'analisando'])->count();
        $valorTotal = Orcamento::forUser($userId)->sum('valor_total');
        
        // Taxa de conversão
        $taxaConversao = $totalOrcamentos > 0 ? round(($orcamentosAprovados / $totalOrcamentos) * 100, 1) : 0;
        
        // Orçamentos recentes
        $orcamentosRecentes = Orcamento::with(['cliente', 'autores'])
            ->forUser($userId)
            ->latest()
            ->take(5)
            ->get();
        
        // Atividades recentes
        $atividades_recentes = HistoricoOrcamento::with(['orcamento', 'user'])
            ->whereHas('orcamento.cliente', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->latest()
            ->take(10)
            ->get();
        
        // Dados para gráfico de orçamentos por status
        $statusData = Orcamento::forUser($userId)
            ->select('status', DB::raw('count(*) as count'), DB::raw('sum(valor_total) as valor'))
            ->groupBy('status')
            ->get();
            
        $stats_por_status = [];
        foreach($statusData as $item) {
            $stats_por_status[$item->status] = [
                'count' => $item->count,
                'valor' => $item->valor ?? 0
            ];
        }
        
        return view('dashboard', compact(
            'totalOrcamentos',
            'orcamentosAprovados', 
            'orcamentosPendentes',
            'valorTotal',
            'taxaConversao',
            'orcamentosRecentes',
            'atividades_recentes',
            'stats_por_status'
        ));
    }
}
