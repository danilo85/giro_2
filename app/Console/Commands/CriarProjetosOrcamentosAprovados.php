<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Orcamento;
use App\Models\Projeto;
use App\Models\EtapaKanban;

class CriarProjetosOrcamentosAprovados extends Command
{
    protected $signature = 'orcamentos:criar-projetos-aprovados';
    protected $description = 'Cria projetos no Kanban para orçamentos aprovados que não possuem projetos';

    public function handle()
    {
        $this->info('=== CRIANDO PROJETOS PARA ORÇAMENTOS APROVADOS ===');
        $this->newLine();

        // Buscar orçamentos aprovados sem projetos
        $orcamentosSemProjetos = Orcamento::where('status', 'aprovado')
            ->whereDoesntHave('projetos')
            ->with('cliente')
            ->get();

        if ($orcamentosSemProjetos->isEmpty()) {
            $this->success('Todos os orçamentos aprovados já possuem projetos!');
            return 0;
        }

        $this->info("Encontrados {$orcamentosSemProjetos->count()} orçamentos aprovados sem projetos.");
        $this->newLine();

        $projetosCriados = 0;
        $erros = 0;

        foreach ($orcamentosSemProjetos as $orcamento) {
            try {
                $this->line("Processando orçamento ID {$orcamento->id}: {$orcamento->titulo}");
                
                // Buscar primeira etapa disponível
                $primeiraEtapa = EtapaKanban::orderBy('ordem')->first();

                if (!$primeiraEtapa) {
                    $this->warn("  - Criando etapas padrão");
                    $this->criarEtapasPadrao();
                    $primeiraEtapa = EtapaKanban::orderBy('ordem')->first();
                }

                // Buscar próxima posição na etapa
                $proximaPosicao = Projeto::where('etapa_id', $primeiraEtapa->id)->max('posicao') + 1;

                // Criar o projeto
                $projeto = Projeto::create([
                    'orcamento_id' => $orcamento->id,
                    'etapa_id' => $primeiraEtapa->id,
                    'posicao' => $proximaPosicao,
                    'moved_at' => now()
                ]);

                $this->info("  ✓ Projeto criado com ID {$projeto->id} na etapa '{$primeiraEtapa->nome}'");
                $projetosCriados++;

            } catch (\Exception $e) {
                $this->error("  ✗ Erro ao criar projeto para orçamento {$orcamento->id}: {$e->getMessage()}");
                $erros++;
            }
        }

        $this->newLine();
        $this->info("=== RESUMO ===");
        $this->info("Projetos criados com sucesso: {$projetosCriados}");
        if ($erros > 0) {
            $this->warn("Erros encontrados: {$erros}");
        }

        return 0;
    }

    private function criarEtapasPadrao()
    {
        $etapasPadrao = [
            ['nome' => 'Backlog', 'cor' => '#6B7280', 'ordem' => 1],
            ['nome' => 'Em Andamento', 'cor' => '#3B82F6', 'ordem' => 2],
            ['nome' => 'Revisão', 'cor' => '#F59E0B', 'ordem' => 3],
            ['nome' => 'Concluído', 'cor' => '#10B981', 'ordem' => 4]
        ];

        foreach ($etapasPadrao as $etapa) {
            EtapaKanban::create([
                'nome' => $etapa['nome'],
                'cor' => $etapa['cor'],
                'ordem' => $etapa['ordem'],
                'ativa' => true
            ]);
        }
    }
}