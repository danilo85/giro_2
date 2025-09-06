<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Bank;
use App\Models\CreditCard;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;

class FinancialDashboardController extends Controller
{
    /**
     * Display the financial dashboard.
     */
    public function index(Request $request)
    {
        $currentMonth = $request->get('month', now()->month);
        $currentYear = $request->get('year', now()->year);
        
        // Resumo mensal
        $summary = $this->getMonthlySummary($currentYear, $currentMonth);
        
        // Dados para gráficos
        $chartData = $this->getChartData($currentYear, $currentMonth);
        
        // Contas bancárias
        $banks = Bank::forUser(Auth::id())
            ->active()
            ->withSum('transactions', 'valor')
            ->get();
        
        // Cartões de crédito
        $creditCards = CreditCard::forUser(Auth::id())
            ->active()
            ->get();
        
        // Transações recentes
        $recentTransactions = Transaction::forUser(Auth::id())
            ->with(['bank', 'creditCard', 'category'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Transações pendentes
        $pendingTransactions = Transaction::forUser(Auth::id())
            ->pendentes()
            ->with(['bank', 'creditCard', 'category'])
            ->orderBy('data', 'asc')
            ->limit(5)
            ->get();
        
        return view('financial.dashboard', compact(
            'summary',
            'chartData',
            'banks',
            'creditCards',
            'recentTransactions',
            'pendingTransactions',
            'currentMonth',
            'currentYear'
        ));
    }
    
    /**
     * Get monthly summary data.
     */
    public function getMonthlySummary($year, $month)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        
        $transactions = Transaction::forUser(Auth::id())
            ->whereBetween('data', [$startDate, $endDate])
            ->get();
        
        $receitas = $transactions->where('tipo', 'receita')->sum('valor');
        $despesas = $transactions->where('tipo', 'despesa')->sum('valor');
        $saldo = $receitas - $despesas;
        
        $receitasPagas = $transactions->where('tipo', 'receita')
            ->where('status', 'pago')
            ->sum('valor');
        
        $despesasPagas = $transactions->where('tipo', 'despesa')
            ->where('status', 'pago')
            ->sum('valor');
        
        $receitasPendentes = $transactions->where('tipo', 'receita')
            ->where('status', 'pendente')
            ->sum('valor');
        
        $despesasPendentes = $transactions->where('tipo', 'despesa')
            ->where('status', 'pendente')
            ->sum('valor');
        
        return [
            'receitas_total' => $receitas,
            'despesas_total' => $despesas,
            'saldo' => $saldo,
            'receitas_pagas' => $receitasPagas,
            'despesas_pagas' => $despesasPagas,
            'receitas_pendentes' => $receitasPendentes,
            'despesas_pendentes' => $despesasPendentes,
            'total_transacoes' => $transactions->count(),
            'transacoes_pagas' => $transactions->where('status', 'pago')->count(),
            'transacoes_pendentes' => $transactions->where('status', 'pendente')->count()
        ];
    }
    
    /**
     * Get chart data for the dashboard.
     */
    public function getChartData($year, $month)
    {
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        
        // Dados por categoria
        $categoriesData = Transaction::forUser(Auth::id())
            ->whereBetween('data', [$startDate, $endDate])
            ->with('category')
            ->get()
            ->groupBy('category.nome')
            ->map(function ($transactions, $categoryName) {
                return [
                    'name' => $categoryName ?: 'Sem categoria',
                    'receitas' => $transactions->where('tipo', 'receita')->sum('valor'),
                    'despesas' => $transactions->where('tipo', 'despesa')->sum('valor'),
                    'total' => $transactions->sum('valor')
                ];
            })
            ->values();
        
        // Evolução diária do mês
        $dailyData = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $dayTransactions = Transaction::forUser(Auth::id())
                ->whereDate('data', $currentDate)
                ->get();
            
            $dailyData[] = [
                'date' => $currentDate->format('Y-m-d'),
                'day' => $currentDate->day,
                'receitas' => $dayTransactions->where('tipo', 'receita')->sum('valor'),
                'despesas' => $dayTransactions->where('tipo', 'despesa')->sum('valor')
            ];
            
            $currentDate->addDay();
        }
        
        // Comparação com mês anterior
        $previousMonth = $startDate->copy()->subMonth();
        $previousSummary = $this->getMonthlySummary($previousMonth->year, $previousMonth->month);
        
        return [
            'categories' => $categoriesData,
            'daily' => $dailyData,
            'comparison' => [
                'previous_month' => $previousSummary,
                'receitas_growth' => $this->calculateGrowth(
                    $previousSummary['receitas_total'],
                    $this->getMonthlySummary($year, $month)['receitas_total']
                ),
                'despesas_growth' => $this->calculateGrowth(
                    $previousSummary['despesas_total'],
                    $this->getMonthlySummary($year, $month)['despesas_total']
                )
            ]
        ];
    }
    
    /**
     * Calculate growth percentage.
     */
    private function calculateGrowth($previous, $current)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100, 2);
    }
}
