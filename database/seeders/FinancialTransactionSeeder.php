<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Category;
use App\Models\Bank;
use App\Models\CreditCard;
use Carbon\Carbon;

class FinancialTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar todos os usuários para distribuir as transações
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->error('Nenhum usuário encontrado. Execute o UserSeeder primeiro.');
            return;
        }
        
        $this->command->info('Criando transações para ' . $users->count() . ' usuários...');
        
        // Criar transações para cada usuário
        foreach ($users as $userIndex => $user) {
            $this->createTransactionsForUser($user, $userIndex);
        }
    }
    
    private function createTransactionsForUser(User $user, int $userIndex): void
    {

        // Busca categorias, bancos e cartões
        $receitas = Category::where('user_id', $user->id)->where('tipo', 'receita')->get();
        $despesas = Category::where('user_id', $user->id)->where('tipo', 'despesa')->get();
        $banks = Bank::where('user_id', $user->id)->get();
        $creditCards = CreditCard::where('user_id', $user->id)->get();

        if ($receitas->isEmpty() || $despesas->isEmpty()) {
            $this->command->error('Categorias não encontradas. Execute primeiro o CategorySeeder.');
            return;
        }

        if ($banks->isEmpty()) {
            $this->command->error('Bancos não encontrados. Execute primeiro o BankSeeder.');
            return;
        }

        // Lançamentos Normais - Receitas
        $normalReceitas = [
            [
                'user_id' => $user->id,
                'category_id' => $receitas->where('nome', 'Salário')->first()?->id ?? $receitas->first()->id,
                'bank_id' => $banks->first()->id,
                'descricao' => 'Salário Janeiro 2024',
                'valor' => 5500.00,
                'tipo' => 'receita',
                'frequency_type' => 'unica',
                'data' => Carbon::now()->subDays(15),
                'data_pagamento' => Carbon::now()->subDays(15),
                'status' => 'pago',
                'observacoes' => 'Salário mensal depositado',
            ],
            [
                'user_id' => $user->id,
                'category_id' => $receitas->where('nome', 'Freelance')->first()?->id ?? $receitas->first()->id,
                'bank_id' => $banks->skip(1)->first()?->id ?? $banks->first()->id,
                'descricao' => 'Projeto Website E-commerce',
                'valor' => 2800.00,
                'tipo' => 'receita',
                'frequency_type' => 'unica',
                'data' => Carbon::now()->subDays(10),
                'data_pagamento' => Carbon::now()->subDays(8),
                'status' => 'pago',
                'observacoes' => 'Desenvolvimento completo do site',
            ],
            [
                'user_id' => $user->id,
                'category_id' => $receitas->where('nome', 'Investimentos')->first()?->id ?? $receitas->first()->id,
                'bank_id' => $banks->first()->id,
                'descricao' => 'Dividendos Ações',
                'valor' => 450.75,
                'tipo' => 'receita',
                'frequency_type' => 'unica',
                'data' => Carbon::now()->subDays(5),
                'data_pagamento' => Carbon::now()->subDays(5),
                'status' => 'pago',
                'observacoes' => 'Dividendos trimestrais',
            ],
        ];

        // Lançamentos Normais - Despesas
        $normalDespesas = [
            [
                'user_id' => $user->id,
                'category_id' => $despesas->where('nome', 'Alimentação')->first()?->id ?? $despesas->first()->id,
                'bank_id' => $banks->first()->id,
                'descricao' => 'Supermercado Extra',
                'valor' => 320.50,
                'tipo' => 'despesa',
                'frequency_type' => 'unica',
                'data' => Carbon::now()->subDays(3),
                'data_pagamento' => Carbon::now()->subDays(3),
                'status' => 'pago',
                'observacoes' => 'Compras mensais',
            ],
            [
                'user_id' => $user->id,
                'category_id' => $despesas->where('nome', 'Transporte')->first()?->id ?? $despesas->first()->id,
                'credit_card_id' => $creditCards->first()?->id,
                'descricao' => 'Combustível Posto Shell',
                'valor' => 180.00,
                'tipo' => 'despesa',
                'frequency_type' => 'unica',
                'data' => Carbon::now()->subDays(7),
                'data_pagamento' => Carbon::now()->subDays(7),
                'status' => 'pago',
                'observacoes' => 'Abastecimento semanal',
            ],
            [
                'user_id' => $user->id,
                'category_id' => $despesas->where('nome', 'Moradia')->first()?->id ?? $despesas->first()->id,
                'bank_id' => $banks->first()->id,
                'descricao' => 'Aluguel Apartamento',
                'valor' => 1200.00,
                'tipo' => 'despesa',
                'frequency_type' => 'unica',
                'data' => Carbon::now()->addDays(5),
                'status' => 'pendente',
                'observacoes' => 'Aluguel mensal',
            ],
        ];

        // Lançamentos Parcelados
        $parceladosDespesas = [
            // Compra parcelada em 6x
            [
                'user_id' => $user->id,
                'category_id' => $despesas->where('nome', 'Tecnologia')->first()?->id ?? $despesas->first()->id,
                'credit_card_id' => $creditCards->first()?->id,
                'descricao' => 'Notebook Dell Inspiron',
                'valor' => 500.00, // Valor da parcela
                'tipo' => 'despesa',
                'frequency_type' => 'parcelada',
                'data' => Carbon::now()->subDays(20),
                'data_pagamento' => Carbon::now()->subDays(20),
                'status' => 'pago',
                'installment_number' => 1,
                'installment_count' => 6,
                'observacoes' => 'Notebook para trabalho - Parcela 1/6',
            ],
            [
                'user_id' => $user->id,
                'category_id' => $despesas->where('nome', 'Tecnologia')->first()?->id ?? $despesas->first()->id,
                'credit_card_id' => $creditCards->first()?->id,
                'descricao' => 'Notebook Dell Inspiron',
                'valor' => 500.00,
                'tipo' => 'despesa',
                'frequency_type' => 'parcelada',
                'data' => Carbon::now()->addDays(10),
                'status' => 'pendente',
                'installment_number' => 2,
                'installment_count' => 6,
                'observacoes' => 'Notebook para trabalho - Parcela 2/6',
            ],
            // Compra parcelada em 12x
            [
                'user_id' => $user->id,
                'category_id' => $despesas->where('nome', 'Vestuário')->first()?->id ?? $despesas->first()->id,
                'credit_card_id' => $creditCards->skip(1)->first()?->id ?? $creditCards->first()->id,
                'descricao' => 'Roupas Loja Zara',
                'valor' => 150.00,
                'tipo' => 'despesa',
                'frequency_type' => 'parcelada',
                'data' => Carbon::now()->subDays(30),
                'data_pagamento' => Carbon::now()->subDays(30),
                'status' => 'pago',
                'installment_number' => 1,
                'installment_count' => 12,
                'observacoes' => 'Roupas de inverno - Parcela 1/12',
            ],
        ];

        // Lançamentos Recorrentes - Receitas
        $recorrentesReceitas = [
            [
                'user_id' => $user->id,
                'category_id' => $receitas->where('nome', 'Aluguel Recebido')->first()?->id ?? $receitas->first()->id,
                'bank_id' => $banks->first()->id,
                'descricao' => 'Aluguel Imóvel Rua das Flores',
                'valor' => 800.00,
                'tipo' => 'receita',
                'frequency_type' => 'recorrente',
                'data' => Carbon::now()->subDays(25),
                'data_pagamento' => Carbon::now()->subDays(25),
                'status' => 'pago',

                'observacoes' => 'Aluguel mensal do apartamento',
            ],
        ];

        // Lançamentos Recorrentes - Despesas
        $recorrentesDespesas = [
            [
                'user_id' => $user->id,
                'category_id' => $despesas->where('nome', 'Seguros')->first()?->id ?? $despesas->first()->id,
                'bank_id' => $banks->first()->id,
                'descricao' => 'Seguro Auto Porto Seguro',
                'valor' => 280.00,
                'tipo' => 'despesa',
                'frequency_type' => 'recorrente',
                'data' => Carbon::now()->addDays(15),
                'status' => 'pendente',

                'observacoes' => 'Seguro do veículo',
            ],
            [
                'user_id' => $user->id,
                'category_id' => $despesas->where('nome', 'Tecnologia')->first()?->id ?? $despesas->first()->id,
                'credit_card_id' => $creditCards->first()?->id,
                'descricao' => 'Netflix Premium',
                'valor' => 45.90,
                'tipo' => 'despesa',
                'frequency_type' => 'recorrente',
                'data' => Carbon::now()->subDays(5),
                'data_pagamento' => Carbon::now()->subDays(5),
                'status' => 'pago',

                'observacoes' => 'Assinatura streaming',
            ],
            [
                'user_id' => $user->id,
                'category_id' => $despesas->where('nome', 'Saúde')->first()?->id ?? $despesas->first()->id,
                'bank_id' => $banks->first()->id,
                'descricao' => 'Plano de Saúde Unimed',
                'valor' => 350.00,
                'tipo' => 'despesa',
                'frequency_type' => 'recorrente',
                'data' => Carbon::now()->addDays(8),
                'status' => 'pendente',

                'observacoes' => 'Plano de saúde familiar',
            ],
        ];

        // Criar todos os lançamentos
        $allTransactions = array_merge(
            $normalReceitas,
            $normalDespesas,
            $parceladosDespesas,
            $recorrentesReceitas,
            $recorrentesDespesas
        );

        foreach ($allTransactions as $transactionData) {
            Transaction::create($transactionData);
        }

        $this->command->info('Lançamentos financeiros criados para usuário: ' . $user->name);
        $this->command->info('Total de lançamentos criados: ' . count($allTransactions));
        $this->command->info('- Receitas normais: ' . count($normalReceitas));
        $this->command->info('- Despesas normais: ' . count($normalDespesas));
        $this->command->info('- Despesas parceladas: ' . count($parceladosDespesas));
        $this->command->info('- Receitas recorrentes: ' . count($recorrentesReceitas));
        $this->command->info('- Despesas recorrentes: ' . count($recorrentesDespesas));
    }
}