<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar todos os usuários existentes
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->info('Nenhum usuário encontrado. Execute primeiro o UserSeeder.');
            return;
        }

        $categoryTemplates = [
            // Categorias de Receita
            [
                'nome' => 'Salário',
                'tipo' => 'receita',
                'cor' => '#10B981', // Verde
                'icone_url' => 'wallet',
                'ativo' => true,
            ],
            [
                'nome' => 'Freelance',
                'tipo' => 'receita',
                'cor' => '#3B82F6', // Azul
                'icone_url' => 'briefcase',
                'ativo' => true,
            ],
            [
                'nome' => 'Investimentos',
                'tipo' => 'receita',
                'cor' => '#8B5CF6', // Roxo
                'icone_url' => 'trending-up',
                'ativo' => true,
            ],
            [
                'nome' => 'Vendas',
                'tipo' => 'receita',
                'cor' => '#F59E0B', // Amarelo
                'icone_url' => 'shopping-bag',
                'ativo' => true,
            ],
            [
                'nome' => 'Aluguel Recebido',
                'tipo' => 'receita',
                'cor' => '#06B6D4', // Ciano
                'icone_url' => 'home',
                'ativo' => true,
            ],
            [
                'nome' => 'Bonificação',
                'tipo' => 'receita',
                'cor' => '#84CC16', // Verde Lima
                'icone_url' => 'gift',
                'ativo' => true,
            ],
            // Categorias de Despesa
            [
                'nome' => 'Alimentação',
                'tipo' => 'despesa',
                'cor' => '#EF4444', // Vermelho
                'icone_url' => 'utensils',
                'ativo' => true,
            ],
            [
                'nome' => 'Transporte',
                'tipo' => 'despesa',
                'cor' => '#F97316', // Laranja
                'icone_url' => 'car',
                'ativo' => true,
            ],
            [
                'nome' => 'Moradia',
                'tipo' => 'despesa',
                'cor' => '#DC2626', // Vermelho Escuro
                'icone_url' => 'home',
                'ativo' => true,
            ],
            [
                'nome' => 'Saúde',
                'tipo' => 'despesa',
                'cor' => '#EC4899', // Rosa
                'icone_url' => 'heart',
                'ativo' => true,
            ],
            [
                'nome' => 'Educação',
                'tipo' => 'despesa',
                'cor' => '#6366F1', // Índigo
                'icone_url' => 'book',
                'ativo' => true,
            ],
            [
                'nome' => 'Lazer',
                'tipo' => 'despesa',
                'cor' => '#14B8A6', // Teal
                'icone_url' => 'gamepad-2',
                'ativo' => true,
            ],
            [
                'nome' => 'Vestuário',
                'tipo' => 'despesa',
                'cor' => '#A855F7', // Roxo Médio
                'icone_url' => 'shirt',
                'ativo' => true,
            ],
            [
                'nome' => 'Tecnologia',
                'tipo' => 'despesa',
                'cor' => '#0EA5E9', // Azul Céu
                'icone_url' => 'smartphone',
                'ativo' => true,
            ],
            [
                'nome' => 'Impostos',
                'tipo' => 'despesa',
                'cor' => '#7C2D12', // Marrom
                'icone_url' => 'file-text',
                'ativo' => true,
            ],
            [
                'nome' => 'Seguros',
                'tipo' => 'despesa',
                'cor' => '#374151', // Cinza
                'icone_url' => 'shield',
                'ativo' => true,
            ],
            [
                'nome' => 'Pets',
                'tipo' => 'despesa',
                'cor' => '#F472B6', // Rosa Claro
                'icone_url' => 'heart',
                'ativo' => true,
            ],
            [
                'nome' => 'Viagens',
                'tipo' => 'despesa',
                'cor' => '#0891B2', // Azul Escuro
                'icone_url' => 'plane',
                'ativo' => true,
            ],
        ];

        // Criar categorias para cada usuário
        foreach ($users as $user) {
            foreach ($categoryTemplates as $categoryData) {
                Category::create(array_merge($categoryData, [
                    'user_id' => $user->id
                ]));
            }
        }
        
        $this->command->info('Categorias criadas com sucesso!');
        $this->command->info('Total de categorias criadas: ' . (count($categoryTemplates) * $users->count()));
        $this->command->info('Distribuídas entre ' . $users->count() . ' usuários.');
    }
}
