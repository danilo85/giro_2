<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orcamento;
use App\Models\Projeto;

class CheckApprovedBudgets extends Command
{
    protected $signature = 'check:approved-budgets';
    protected $description = 'Verifica orçamentos aprovados e seus projetos correspondentes';

    public function handle()
    {
        $this->info('=== VERIFICAÇÃO DE ORÇAMENTOS APROVADOS ===');
        
        // 1. Total de orçamentos aprovados
        $totalAprovados = Orcamento::where('status', 'aprovado')->count();
        $this->info("1. Total de orçamentos aprovados: {$totalAprovados}");
        
        if ($totalAprovados == 0) {
            $this->warn('Não há orçamentos aprovados no sistema.');
            return;
        }
        
        // 2. Orçamentos aprovados com projetos
        $comProjetos = Orcamento::where('status', 'aprovado')
            ->whereHas('projetos')
            ->count();
        $this->info("2. Orçamentos aprovados com projetos: {$comProjetos}");
        
        // 3. Orçamentos aprovados sem projetos
        $semProjetos = $totalAprovados - $comProjetos;
        $this->info("3. Orçamentos aprovados sem projetos: {$semProjetos}");
        
        if ($semProjetos > 0) {
            $this->warn('\n=== ORÇAMENTOS SEM PROJETOS ===');
            
            $orcamentosSemProjetos = Orcamento::where('status', 'aprovado')
                ->whereDoesntHave('projetos')
                ->get(['id', 'titulo', 'cliente_id', 'valor_total', 'created_at']);
            
            $this->table(
                ['ID', 'Título', 'Cliente ID', 'Valor Total', 'Data Criação'],
                $orcamentosSemProjetos->map(function ($orcamento) {
                    return [
                        $orcamento->id,
                        $orcamento->titulo,
                        $orcamento->cliente_id,
                        'R$ ' . number_format($orcamento->valor_total, 2, ',', '.'),
                        $orcamento->created_at->format('d/m/Y H:i')
                    ];
                })->toArray()
            );
            
            $this->info('\nEsses orçamentos deveriam ter projetos criados automaticamente.');
        } else {
            $this->info('\n✅ Todos os orçamentos aprovados possuem projetos correspondentes!');
        }
        
        // 4. Verificar se há projetos órfãos (sem orçamento)
        $projetosOrfaos = Projeto::whereNull('orcamento_id')->count();
        if ($projetosOrfaos > 0) {
            $this->warn("\n⚠️  Encontrados {$projetosOrfaos} projetos sem orçamento correspondente.");
        }
    }
}