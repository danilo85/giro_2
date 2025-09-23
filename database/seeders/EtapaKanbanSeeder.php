<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EtapaKanban;

class EtapaKanbanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $etapas = [
            [
                'nome' => 'Backlog',
                'cor' => '#6c757d',
                'ordem' => 1,
                'ativa' => true
            ],
            [
                'nome' => 'Em Análise',
                'cor' => '#ffc107',
                'ordem' => 2,
                'ativa' => true
            ],
            [
                'nome' => 'Em Desenvolvimento',
                'cor' => '#007bff',
                'ordem' => 3,
                'ativa' => true
            ],
            [
                'nome' => 'Em Revisão',
                'cor' => '#fd7e14',
                'ordem' => 4,
                'ativa' => true
            ],
            [
                'nome' => 'Em Teste',
                'cor' => '#e83e8c',
                'ordem' => 5,
                'ativa' => true
            ],
            [
                'nome' => 'Concluído',
                'cor' => '#28a745',
                'ordem' => 6,
                'ativa' => true
            ],
            [
                'nome' => 'Arquivado',
                'cor' => '#dc3545',
                'ordem' => 7,
                'ativa' => false
            ]
        ];

        foreach ($etapas as $etapa) {
            EtapaKanban::firstOrCreate(
                ['nome' => $etapa['nome']],
                $etapa
            );
        }

        $this->command->info('Etapas Kanban criadas com sucesso!');
    }
}
