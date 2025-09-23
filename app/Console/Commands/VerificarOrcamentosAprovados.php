<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orcamento;
use App\Models\Projeto;

class VerificarOrcamentosAprovados extends Command
{
    protected $signature = 'orcamentos:verificar-aprovados';
    protected $description = 'Verifica orçamentos aprovados e seus projetos no Kanban';

    public function handle()
    {
        $this->info('=== VERIFICAÇÃO DE ORÇAMENTOS APROVADOS ===');
        $this->newLine();

        // Total de orçamentos aprovados
        $totalAprovados = Orcamento::where('status', 'aprovado')->count();
        $this->info("Total de orçamentos aprovados: {$totalAprovados}");

        // Orçamentos aprovados com projetos
        $comProjetos = Orcamento::where('status', 'aprovado')->whereHas('projetos')->count();
        $this->info("Orçamentos aprovados com projetos: {$comProjetos}");

        // Orçamentos aprovados sem projetos
        $semProjetos = Orcamento::where('status', 'aprovado')->whereDoesntHave('projetos')->count();
        $this->info("Orçamentos aprovados sem projetos: {$semProjetos}");

        $this->newLine();

        if ($semProjetos > 0) {
            $this->warn('=== ORÇAMENTOS APROVADOS SEM PROJETOS ===');
            $this->newLine();

            $orcamentosSemProjetos = Orcamento::where('status', 'aprovado')
                ->whereDoesntHave('projetos')
                ->with('cliente')
                ->get();

            foreach ($orcamentosSemProjetos as $orcamento) {
                $this->line(sprintf(
                    'ID: %d | Título: %s | Valor: R$ %s | Cliente: %s | Criado em: %s',
                    $orcamento->id,
                    $orcamento->titulo,
                    number_format($orcamento->valor_total, 2, ',', '.'),
                    $orcamento->cliente->nome ?? 'N/A',
                    $orcamento->created_at->format('d/m/Y H:i')
                ));
            }

            $this->newLine();
            $this->comment('Estes orçamentos deveriam ter projetos criados automaticamente no Kanban.');
            $this->comment('Verifique se o método criarProjetoKanban() está sendo chamado corretamente.');
        } else {
            $this->info('✓ Todos os orçamentos aprovados já possuem projetos no Kanban!');
        }

        return 0;
    }
}