<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Bank;
use App\Models\User;

class CheckBanks extends Command
{
    protected $signature = 'check:banks';
    protected $description = 'Check banks data in database';

    public function handle()
    {
        $this->info('=== VERIFICAÇÃO DE BANCOS ===');
        
        // Total de bancos
        $totalBanks = Bank::count();
        $this->info("Total de bancos: {$totalBanks}");
        
        if ($totalBanks > 0) {
            $this->info('\n=== DETALHES DOS BANCOS ===');
            
            Bank::all()->each(function ($bank) {
                $this->info("ID: {$bank->id}");
                $this->info("Nome: {$bank->nome}");
                $this->info("Banco: {$bank->banco}");
                $this->info("User ID: {$bank->user_id}");
                $this->info("Saldo Inicial: R$ " . number_format($bank->saldo_inicial, 2, ',', '.'));
                $this->info("Saldo Atual: R$ " . number_format($bank->saldo_atual, 2, ',', '.'));
                $this->info("Ativo: " . ($bank->ativo ? 'Sim' : 'Não'));
                $this->info('---');
            });
        }
        
        // Verificar usuários
        $this->info('\n=== USUÁRIOS ===');
        $totalUsers = User::count();
        $this->info("Total de usuários: {$totalUsers}");
        
        if ($totalUsers > 0) {
            User::all()->each(function ($user) {
                $banksCount = $user->banks()->count();
                $this->info("User ID: {$user->id} - Email: {$user->email} - Bancos: {$banksCount}");
            });
        }
        
        return 0;
    }
}