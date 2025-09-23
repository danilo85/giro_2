<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Projeto;
use App\Models\Orcamento;
use App\Models\EtapaKanban;

class ProjetoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar se existem orçamentos e etapas
        $orcamentos = Orcamento::all();
        $etapas = EtapaKanban::ativas()->ordenadas()->get();
        
        if ($orcamentos->isEmpty()) {
            $this->command->info('Nenhum orçamento encontrado. Execute o OrcamentoSeeder primeiro.');
            return;
        }
        
        if ($etapas->isEmpty()) {
            $this->command->info('Nenhuma etapa kanban encontrada. Execute o EtapaKanbanSeeder primeiro.');
            return;
        }

        // Criar projetos baseados nos orçamentos aprovados
        $orcamentosAprovados = $orcamentos->where('status', 'aprovado');
        
        foreach ($orcamentosAprovados as $index => $orcamento) {
            // Distribuir projetos entre diferentes etapas
            $etapaIndex = $index % $etapas->count();
            $etapa = $etapas[$etapaIndex];
            
            Projeto::firstOrCreate(
                ['orcamento_id' => $orcamento->id],
                [
                    'etapa_id' => $etapa->id,
                    'posicao' => $index + 1,
                    'moved_at' => now()->subDays(rand(1, 30))
                ]
            );
        }
        
        // Criar alguns projetos adicionais em diferentes etapas
        $outrosOrcamentos = $orcamentos->whereNotIn('status', ['aprovado'])->take(5);
        
        foreach ($outrosOrcamentos as $index => $orcamento) {
            if ($orcamento->status === 'em_analise') {
                $etapa = $etapas->where('nome', 'Em Análise')->first();
            } elseif ($orcamento->status === 'rejeitado') {
                $etapa = $etapas->where('nome', 'Arquivado')->first();
            } else {
                $etapa = $etapas->where('nome', 'Backlog')->first();
            }
            
            if ($etapa) {
                Projeto::firstOrCreate(
                    ['orcamento_id' => $orcamento->id],
                    [
                        'etapa_id' => $etapa->id,
                        'posicao' => $index + 1,
                        'moved_at' => now()->subDays(rand(1, 15))
                    ]
                );
            }
        }

        $this->command->info('Projetos criados com sucesso!');
    }
}