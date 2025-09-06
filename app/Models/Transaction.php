<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bank_id',
        'credit_card_id',
        'category_id',
        'descricao',
        'valor',
        'tipo',
        'data',
        'status',
        'frequency_type',
        'installment_id',
        'installment_count',
        'installment_number',
        'data_pagamento',
        'observacoes',
        'is_recurring',
        'recurring_type',
        'recurring_end_date'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data' => 'date',
        'data_pagamento' => 'date',
        'recurring_end_date' => 'date',
        'is_recurring' => 'boolean'
    ];

    // Relacionamentos
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function creditCard(): BelongsTo
    {
        return $this->belongsTo(CreditCard::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(\App\Models\TransactionFile::class);
    }

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopePagas($query)
    {
        return $query->where('status', 'pago');
    }

    public function scopeReceitas($query)
    {
        return $query->where('tipo', 'receita');
    }

    public function scopeDespesas($query)
    {
        return $query->where('tipo', 'despesa');
    }

    public function scopeByMonth($query, $year, $month)
    {
        return $query->whereYear('data', $year)->whereMonth('data', $month);
    }

    public function scopeParceladas($query)
    {
        return $query->where('frequency_type', 'parcelada');
    }

    public function scopeRecorrentes($query)
    {
        return $query->where('frequency_type', 'recorrente');
    }

    // Métodos auxiliares
    public function getCorTipoAttribute()
    {
        return $this->tipo === 'receita' ? '#10B981' : '#EF4444';
    }

    public function getDescricaoParcelaAttribute()
    {
        if ($this->frequency_type === 'parcelada' && $this->installment_count) {
            return $this->installment_number . '/' . $this->installment_count;
        }
        return null;
    }

    public function marcarComoPago()
    {
        $this->status = 'pago';
        $this->data_pagamento = Carbon::now();
        $this->save();

        // Atualizar saldo do banco ou limite do cartão
        if ($this->bank_id) {
            $this->bank->updateSaldo($this->valor, $this->tipo);
        } elseif ($this->credit_card_id) {
            $operacao = $this->tipo === 'despesa' ? 'adicionar' : 'remover';
            $this->creditCard->updateLimiteUtilizado($this->valor, $operacao);
        }
    }

    public function duplicar($novaData = null)
    {
        $novaTransacao = $this->replicate();
        $novaTransacao->data = $novaData ?: Carbon::now();
        $novaTransacao->status = 'pendente';
        $novaTransacao->data_pagamento = null;
        $novaTransacao->installment_id = null;
        $novaTransacao->installment_number = null;
        $novaTransacao->save();

        return $novaTransacao;
    }
}
