<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Autor;
use App\Models\User;

class AutorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar usuários existentes
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('Nenhum usuário encontrado. Execute UserSeeder primeiro.');
            return;
        }

        $autores = [
            [
                'user_id' => $users->random()->id,
                'nome' => 'Ana Silva',
                'email' => 'ana.silva@email.com',
                'telefone' => '(11) 99999-1111',
                'whatsapp' => '5511999991111',
                'biografia' => 'Designer gráfica com 8 anos de experiência em identidade visual e branding. Especializada em design gráfico e criação de marcas.',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Carlos Mendes',
                'email' => 'carlos.mendes@email.com',
                'telefone' => '(11) 98888-2222',
                'whatsapp' => '5511988882222',
                'biografia' => 'Desenvolvedor full-stack especializado em Laravel e Vue.js com 10 anos de experiência. Foco em desenvolvimento web.',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Marina Costa',
                'email' => 'marina.costa@email.com',
                'telefone' => '(21) 97777-3333',
                'whatsapp' => '5521977773333',
                'biografia' => 'Especialista em marketing digital e gestão de redes sociais com foco em ROI e conversão.',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Roberto Santos',
                'email' => 'roberto.santos@email.com',
                'telefone' => '(31) 96666-4444',
                'whatsapp' => '5531966664444',
                'biografia' => 'Consultor empresarial com MBA em gestão e 15 anos de experiência em otimização de processos.',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Juliana Oliveira',
                'email' => 'juliana.oliveira@email.com',
                'telefone' => '(41) 95555-5555',
                'whatsapp' => '5541955555555',
                'biografia' => 'Produtora audiovisual especializada em conteúdo corporativo e institucional.',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Pedro Almeida',
                'email' => 'pedro.almeida@email.com',
                'telefone' => '(51) 94444-6666',
                'whatsapp' => '5551944446666',
                'biografia' => 'Copywriter especializado em vendas e conversão para e-commerce e infoprodutos.',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Fernanda Lima',
                'email' => 'fernanda.lima@email.com',
                'telefone' => '(61) 93333-7777',
                'whatsapp' => '5561933337777',
                'biografia' => 'Designer UX/UI com foco em experiência do usuário e interfaces intuitivas.',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Lucas Ferreira',
                'email' => 'lucas.ferreira@email.com',
                'telefone' => '(71) 92222-8888',
                'whatsapp' => '5571922228888',
                'biografia' => 'Especialista em SEO e análise de dados com certificações Google Analytics e Ads.',
            ],
        ];

        foreach ($autores as $autorData) {
            Autor::firstOrCreate(
                ['email' => $autorData['email']],
                $autorData
            );
        }

        $this->command->info('Autores criados com sucesso!');
    }
}