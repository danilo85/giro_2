<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BankSeeder::class,
            FinancialTransactionSeeder::class,
            ClienteSeeder::class,
            AutorSeeder::class,
            CategorySeeder::class,
            OrcamentoSeeder::class,
            EtapaKanbanSeeder::class,
            ProjetoSeeder::class,
            SocialPostSeeder::class,
            AdminUserSeeder::class,
            CreditCardSeeder::class,
            HashtagSeeder::class,
        ]);
    }
}
