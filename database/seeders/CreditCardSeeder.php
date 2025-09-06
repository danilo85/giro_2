<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CreditCard;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreditCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar usuários existentes
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->info('Nenhum usuário encontrado. Execute primeiro o UserSeeder.');
            return;
        }

        // Dados fictícios de cartões de crédito
        $creditCards = [
            [
                'nome_cartao' => 'Cartão Visa Gold',
                'bandeira' => 'Visa',
                'bandeira_logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg',
                'limite_total' => 5000.00,
                'limite_utilizado' => 1250.75,
                'data_fechamento' => 15,
                'data_vencimento' => 25,
                'ativo' => true
            ],
            [
                'nome_cartao' => 'Mastercard Platinum',
                'bandeira' => 'Mastercard',
                'bandeira_logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg',
                'limite_total' => 8000.00,
                'limite_utilizado' => 2340.50,
                'data_fechamento' => 10,
                'data_vencimento' => 20,
                'ativo' => true
            ],
            [
                'nome_cartao' => 'Elo Mais',
                'bandeira' => 'Elo',
                'bandeira_logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/8/8c/Elo_logo.svg',
                'limite_total' => 3000.00,
                'limite_utilizado' => 890.25,
                'data_fechamento' => 5,
                'data_vencimento' => 15,
                'ativo' => true
            ],
            [
                'nome_cartao' => 'American Express Green',
                'bandeira' => 'American Express',
                'bandeira_logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/f/fa/American_Express_logo_%282018%29.svg',
                'limite_total' => 12000.00,
                'limite_utilizado' => 4567.80,
                'data_fechamento' => 20,
                'data_vencimento' => 30,
                'ativo' => true
            ],
            [
                'nome_cartao' => 'Visa Infinite',
                'bandeira' => 'Visa',
                'bandeira_logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg',
                'limite_total' => 15000.00,
                'limite_utilizado' => 6789.45,
                'data_fechamento' => 8,
                'data_vencimento' => 18,
                'ativo' => true
            ],
            [
                'nome_cartao' => 'Mastercard Black',
                'bandeira' => 'Mastercard',
                'bandeira_logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg',
                'limite_total' => 20000.00,
                'limite_utilizado' => 0.00,
                'data_fechamento' => 12,
                'data_vencimento' => 22,
                'ativo' => false
            ],
            [
                'nome_cartao' => 'Elo Nanquim',
                'bandeira' => 'Elo',
                'bandeira_logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/8/8c/Elo_logo.svg',
                'limite_total' => 7500.00,
                'limite_utilizado' => 3245.60,
                'data_fechamento' => 25,
                'data_vencimento' => 5,
                'ativo' => true
            ],
            [
                'nome_cartao' => 'Visa Universitário',
                'bandeira' => 'Visa',
                'bandeira_logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg',
                'limite_total' => 1500.00,
                'limite_utilizado' => 456.30,
                'data_fechamento' => 3,
                'data_vencimento' => 13,
                'ativo' => true
            ]
        ];

        // Distribuir cartões entre os usuários
        foreach ($creditCards as $index => $cardData) {
            $user = $users[$index % $users->count()];
            
            CreditCard::create(array_merge($cardData, [
                'user_id' => $user->id
            ]));
        }

        $this->command->info('Cartões de crédito criados com sucesso!');
        $this->command->info('Total de cartões criados: ' . count($creditCards));
    }
}