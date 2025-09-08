<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ModeloProposta;
use App\Models\User;

class ModeloPropostaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        
        if ($user) {
            ModeloProposta::create([
                'nome' => 'Proposta Padrão',
                'conteudo' => 'Este é um modelo de proposta padrão para testes.',
                'user_id' => $user->id,
                'ativo' => true
            ]);
            
            ModeloProposta::create([
                'nome' => 'Proposta Comercial',
                'conteudo' => 'Modelo de proposta comercial com termos e condições.',
                'user_id' => $user->id,
                'ativo' => true
            ]);
        }
    }
}