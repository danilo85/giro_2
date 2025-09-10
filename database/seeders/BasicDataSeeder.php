<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class BasicDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin se não existir
        if (DB::table('users')->where('email', 'admin@giro.com')->doesntExist()) {
            DB::table('users')->insert([
                'name' => 'Administrador',
                'email' => 'admin@giro.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'is_admin' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Criar usuário demo se não existir
        if (DB::table('users')->where('email', 'demo@giro.com')->doesntExist()) {
            DB::table('users')->insert([
                'name' => 'Usuário Demo',
                'email' => 'demo@giro.com',
                'email_verified_at' => now(),
                'password' => Hash::make('demo123'),
                'is_admin' => 0,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Criar algumas categorias de portfólio básicas
        $userId = DB::table('users')->where('email', 'admin@giro.com')->value('id');
        
        $categories = [
            ['name' => 'Web Design', 'slug' => 'web-design', 'description' => 'Projetos de design para web'],
            ['name' => 'Desenvolvimento', 'slug' => 'desenvolvimento', 'description' => 'Projetos de desenvolvimento de software'],
            ['name' => 'Marketing Digital', 'slug' => 'marketing-digital', 'description' => 'Projetos de marketing e publicidade digital']
        ];

        foreach ($categories as $category) {
            if (DB::table('portfolio_categories')->where('slug', $category['slug'])->doesntExist()) {
                DB::table('portfolio_categories')->insert([
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                    'description' => $category['description'],
                    'user_id' => $userId,
                    'is_active' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Criar alguns clientes de exemplo
        $clientes = [
            [
                'nome' => 'Empresa ABC Ltda',
                'email' => 'contato@empresaabc.com',
                'telefone' => '(11) 99999-9999'
            ],
            [
                'nome' => 'João Silva',
                'email' => 'joao@email.com',
                'telefone' => '(11) 88888-8888'
            ]
        ];

        foreach ($clientes as $cliente) {
            if (DB::table('clientes')->where('email', $cliente['email'])->doesntExist()) {
                DB::table('clientes')->insert([
                    'nome' => $cliente['nome'],
                    'email' => $cliente['email'],
                    'telefone' => $cliente['telefone'],
                    'user_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        $this->command->info('Dados básicos criados com sucesso!');
        $this->command->info('Usuário Admin: admin@giro.com / admin123');
        $this->command->info('Usuário Demo: demo@giro.com / demo123');
    }
}