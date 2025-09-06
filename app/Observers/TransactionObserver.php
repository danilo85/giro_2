<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\Bank;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        $this->updateBankBalance($transaction);
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        $this->updateBankBalance($transaction);
        
        // Se o status mudou, também atualizar com base no estado anterior
        if ($transaction->isDirty('status') || $transaction->isDirty('valor') || $transaction->isDirty('tipo')) {
            $this->updateBankBalance($transaction);
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        $this->updateBankBalance($transaction);
    }

    /**
     * Atualiza o saldo da conta bancária relacionada à transação
     */
    private function updateBankBalance(Transaction $transaction): void
    {
        if ($transaction->bank_id && $transaction->bank) {
            $transaction->bank->updateSaldoCalculado();
        }
    }
}