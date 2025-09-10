<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\User;

class ClienteSeeder extends Seeder
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

        $clientes = [
            [
                'user_id' => $users->random()->id,
                'nome' => 'TechStart Inovações Ltda',
                'pessoa_contato' => 'Roberto Silva',
                'email' => 'contato@techstart.com.br',
                'telefone' => '(11) 3333-1111',
                'whatsapp' => '5511333311111',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Maria Santos',
                'pessoa_contato' => 'Maria Santos',
                'email' => 'maria.santos@email.com',
                'telefone' => '(11) 99999-2222',
                'whatsapp' => '5511999992222',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Comercial Bela Vista Ltda',
                'pessoa_contato' => 'Ana Beatriz',
                'email' => 'vendas@belavista.com.br',
                'telefone' => '(21) 3333-3333',
                'whatsapp' => '5521333333333',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'João Silva',
                'pessoa_contato' => 'João Silva',
                'email' => 'joao.silva@email.com',
                'telefone' => '(31) 99999-4444',
                'whatsapp' => '5531999994444',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Restaurante Sabor & Arte',
                'pessoa_contato' => 'Chef Carlos',
                'email' => 'contato@saborarte.com.br',
                'telefone' => '(41) 3333-5555',
                'whatsapp' => '5541333355555',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Ana Costa',
                'pessoa_contato' => 'Ana Costa',
                'email' => 'ana.costa@email.com',
                'telefone' => '(51) 99999-6666',
                'whatsapp' => '5551999996666',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Clínica Vida Saudável',
                'pessoa_contato' => 'Dr. Pedro Mendes',
                'email' => 'atendimento@vidasaudavel.com.br',
                'telefone' => '(61) 3333-7777',
                'whatsapp' => '5561333377777',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Carlos Oliveira',
                'pessoa_contato' => 'Carlos Oliveira',
                'email' => 'carlos.oliveira@email.com',
                'telefone' => '(71) 99999-8888',
                'whatsapp' => '5571999998888',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Escola Criativa Ltda',
                'pessoa_contato' => 'Diretora Lucia',
                'email' => 'secretaria@escolacriativa.edu.br',
                'telefone' => '(85) 3333-9999',
                'whatsapp' => '5585333399999',
            ],
            [
                'user_id' => $users->random()->id,
                'nome' => 'Fernanda Almeida',
                'pessoa_contato' => 'Fernanda Almeida',
                'email' => 'fernanda.almeida@email.com',
                'telefone' => '(11) 99999-0000',
                'whatsapp' => '5511999990000',
            ],
        ];

        foreach ($clientes as $clienteData) {
            Cliente::firstOrCreate(
                ['email' => $clienteData['email']],
                $clienteData
            );
        }

        $this->command->info('Clientes criados com sucesso!');
    }
}