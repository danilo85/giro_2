<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HistoricoOrcamento;
use App\Models\Orcamento;
use App\Models\User;
use Carbon\Carbon;

class HistoricoOrcamentoSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $orcamentos = Orcamento::all();
        
        if ($users->isEmpty() || $orcamentos->isEmpty()) {
            return;
        }
        
        $atividades = [
            [
                'acao' => 'criado',
                'descricao' => 'Orçamento criado com sucesso',
                'created_at' => Carbon::now()->subDays(5)
            ],
            [
                'acao' => 'atualizado', 
                'descricao' => 'Orçamento atualizado - valor alterado',
                'created_at' => Carbon::now()->subDays(3)
            ],
            [
                'acao' => 'aprovado',
                'descricao' => 'Orçamento aprovado pelo cliente',
                'created_at' => Carbon::now()->subDays(2)
            ],
            [
                'acao' => 'enviado',
                'descricao' => 'Orçamento enviado para análise do cliente',
                'created_at' => Carbon::now()->subDays(4)
            ],
            [
                'acao' => 'atualizado',
                'descricao' => 'Status alterado para em análise',
                'created_at' => Carbon::now()->subDays(1)
            ]
        ];
        
        foreach ($atividades as $atividade) {
            HistoricoOrcamento::create([
                'user_id' => $users->random()->id,
                'orcamento_id' => $orcamentos->random()->id,
                'acao' => $atividade['acao'],
                'descricao' => $atividade['descricao'],
                'created_at' => $atividade['created_at'],
                'updated_at' => $atividade['created_at']
            ]);
        }
    }
}