<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CreditCard;

class CheckCreditCards extends Command
{
    protected $signature = 'check:credit-cards';
    protected $description = 'Verifica cartões de crédito com limite zero';

    public function handle()
    {
        $allCards = CreditCard::all();
        $this->info('Total de cartões: ' . $allCards->count());
        
        if ($allCards->count() > 0) {
            $this->table(
                ['ID', 'Nome', 'Limite Total', 'Limite Usado', 'Percentual'],
                $allCards->map(function ($card) {
                    try {
                        $percentual = $card->percentual_utilizado;
                    } catch (\Exception $e) {
                        $percentual = 'ERRO: ' . $e->getMessage();
                    }
                    return [
                        $card->id,
                        $card->nome_cartao ?? 'N/A',
                        $card->limite_total ?? 'NULL',
                        $card->limite_utilizado ?? 'NULL',
                        $percentual
                    ];
                })
            );
        }
        
        $cardsWithZeroLimit = CreditCard::where('limite_total', 0)->get();
        $this->info('Cartões com limite_total = 0: ' . $cardsWithZeroLimit->count());
        
        $cardsWithNullLimit = CreditCard::whereNull('limite_total')->get();
        $this->info('Cartões com limite_total NULL: ' . $cardsWithNullLimit->count());
        
        if ($cardsWithZeroLimit->count() > 0 || $cardsWithNullLimit->count() > 0) {
            if ($this->confirm('Deseja corrigir os cartões com limite zero/null definindo um limite padrão de R$ 1.000,00?')) {
                CreditCard::where('limite_total', 0)->orWhereNull('limite_total')->update(['limite_total' => 1000.00]);
                $this->info('Cartões corrigidos com sucesso!');
            }
        }
        
        return 0;
    }
}