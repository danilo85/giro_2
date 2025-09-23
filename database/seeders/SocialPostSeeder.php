<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SocialPost;
use App\Models\User;
use Carbon\Carbon;

class SocialPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        
        if (!$user) {
            $this->command->info('Nenhum usuário encontrado. Criando usuário de teste...');
            $user = User::create([
                'name' => 'Usuário Teste',
                'email' => 'teste@exemplo.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Posts para o mês atual
        $posts = [
            [
                'titulo' => 'Post de Marketing Digital',
                'legenda' => 'Dicas importantes sobre marketing digital para pequenas empresas',
                'texto_final' => 'Aprenda as melhores estratégias de marketing digital!',
                'status' => 'publicado',
                'scheduled_date' => Carbon::now()->startOfMonth()->addDays(2),
                'scheduled_time' => '09:00',
            ],
            [
                'titulo' => 'Tendências de Design 2024',
                'legenda' => 'As principais tendências de design que você precisa conhecer',
                'texto_final' => 'Fique por dentro das tendências de design!',
                'status' => 'rascunho',
                'scheduled_date' => Carbon::now()->startOfMonth()->addDays(5),
                'scheduled_time' => '14:30',
            ],
            [
                'titulo' => 'Dicas de Produtividade',
                'legenda' => 'Como ser mais produtivo no trabalho remoto',
                'texto_final' => 'Maximize sua produtividade trabalhando de casa!',
                'status' => 'publicado',
                'scheduled_date' => Carbon::now()->startOfMonth()->addDays(10),
                'scheduled_time' => '16:00',
            ],
            [
                'titulo' => 'Estratégias de Vendas',
                'legenda' => 'Técnicas comprovadas para aumentar suas vendas',
                'texto_final' => 'Aumente suas vendas com essas estratégias!',
                'status' => 'arquivado',
                'scheduled_date' => Carbon::now()->startOfMonth()->addDays(15),
                'scheduled_time' => '10:15',
            ],
            [
                'titulo' => 'Inovação Tecnológica',
                'legenda' => 'As últimas inovações que estão mudando o mercado',
                'texto_final' => 'Descubra as inovações que vão revolucionar seu negócio!',
                'status' => 'publicado',
                'scheduled_date' => Carbon::now()->startOfMonth()->addDays(20),
                'scheduled_time' => '11:45',
            ],
        ];

        foreach ($posts as $postData) {
            SocialPost::create(array_merge($postData, ['user_id' => $user->id]));
        }

        $this->command->info('Posts de exemplo criados com sucesso!');
    }
}