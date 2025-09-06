<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bank;
use App\Models\User;

class BankSeeder extends Seeder
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

        $bankTemplates = [
            [
                'nome' => 'Conta Corrente Banco do Brasil',
                'banco' => 'Banco do Brasil',
                'logo_url' => 'https://logoeps.com/wp-content/uploads/2013/03/banco-do-brasil-vector-logo.png',
                'saldo_inicial' => 5000.00,
                'saldo_atual' => 4750.50,
                'numero_conta' => '12345-6',
                'ativo' => true,
            ],
            [
                'nome' => 'Conta Corrente Itaú Unibanco',
                'banco' => 'Itaú Unibanco',
                'logo_url' => 'https://logoeps.com/wp-content/uploads/2013/03/itau-vector-logo.png',
                'saldo_inicial' => 3200.00,
                'saldo_atual' => 2890.75,
                'numero_conta' => '98765-4',
                'ativo' => true,
            ],
            [
                'nome' => 'Conta Corrente Bradesco',
                'banco' => 'Bradesco',
                'logo_url' => 'https://logoeps.com/wp-content/uploads/2013/03/bradesco-vector-logo.png',
                'saldo_inicial' => 2800.00,
                'saldo_atual' => 3150.25,
                'numero_conta' => '54321-9',
                'ativo' => true,
            ],
            [
                'nome' => 'Conta Corrente Santander',
                'banco' => 'Santander',
                'logo_url' => 'https://logoeps.com/wp-content/uploads/2013/03/santander-vector-logo.png',
                'saldo_inicial' => 1500.00,
                'saldo_atual' => 1725.80,
                'numero_conta' => '11111-2',
                'ativo' => true,
            ],
            [
                'nome' => 'Poupança Caixa Econômica Federal',
                'banco' => 'Caixa Econômica Federal',
                'logo_url' => 'https://logoeps.com/wp-content/uploads/2013/03/caixa-vector-logo.png',
                'saldo_inicial' => 8000.00,
                'saldo_atual' => 8245.60,
                'numero_conta' => '22222-3',
                'ativo' => true,
            ],
            [
                'nome' => 'Conta Nubank',
                'banco' => 'Nubank',
                'logo_url' => 'https://logoeps.com/wp-content/uploads/2020/09/nubank-vector-logo.png',
                'saldo_inicial' => 1200.00,
                'saldo_atual' => 980.45,
                'numero_conta' => '33333-4',
                'ativo' => true,
            ],
            [
                'nome' => 'Conta Inter',
                'banco' => 'Banco Inter',
                'logo_url' => 'https://logoeps.com/wp-content/uploads/2020/09/inter-vector-logo.png',
                'saldo_inicial' => 750.00,
                'saldo_atual' => 825.30,
                'numero_conta' => '44444-5',
                'ativo' => true,
            ],
            [
                'nome' => 'Conta C6 Bank',
                'banco' => 'C6 Bank',
                'logo_url' => 'https://logoeps.com/wp-content/uploads/2020/09/c6-bank-vector-logo.png',
                'saldo_inicial' => 2000.00,
                'saldo_atual' => 1875.90,
                'numero_conta' => '55555-6',
                'ativo' => true,
            ],
            [
                'nome' => 'Conta Poupança Bradesco',
                'banco' => 'Bradesco',
                'logo_url' => 'https://logoeps.com/wp-content/uploads/2013/03/bradesco-vector-logo.png',
                'saldo_inicial' => 5500.00,
                'saldo_atual' => 5678.45,
                'numero_conta' => '66666-7',
                'ativo' => true,
            ],
            [
                'nome' => 'Conta Empresarial Itaú',
                'banco' => 'Itaú Unibanco',
                'logo_url' => 'https://logoeps.com/wp-content/uploads/2013/03/itau-vector-logo.png',
                'saldo_inicial' => 15000.00,
                'saldo_atual' => 12450.75,
                'numero_conta' => '77777-8',
                'ativo' => false, // Conta inativa para teste
            ],
        ];

        // Distribuir bancos entre os usuários
        foreach ($bankTemplates as $index => $bankData) {
            $user = $users[$index % $users->count()];
            
            Bank::create(array_merge($bankData, [
                'user_id' => $user->id
            ]));
        }
        
        $this->command->info('Bancos criados com sucesso!');
        $this->command->info('Total de bancos criados: ' . count($bankTemplates));
        $this->command->info('Distribuídos entre ' . $users->count() . ' usuários.');
    }
}