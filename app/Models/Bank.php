<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nome',
        'banco',
        'agencia',
        'conta',
        'tipo_conta',
        'logo_url',
        'saldo_inicial',
        'saldo_atual',
        'numero_conta',
        'observacoes',
        'ativo'
    ];

    protected $casts = [
        'saldo_inicial' => 'decimal:2',
        'saldo_atual' => 'decimal:2',
        'ativo' => 'boolean'
    ];

    // Relacionamentos
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function pagamentos(): HasMany
    {
        return $this->hasMany(Pagamento::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('ativo', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Métodos auxiliares
    public function getSaldoDisponivelAttribute()
    {
        return $this->saldo_atual;
    }

    /**
     * Calcula o saldo atual baseado no saldo inicial + transações pagas
     */
    public function calculateSaldoAtual()
    {
        $transacoesPagas = $this->transactions()->where('status', 'pago')->get();
        
        $saldoCalculado = $this->saldo_inicial;
        
        foreach ($transacoesPagas as $transacao) {
            if ($transacao->tipo === 'receita') {
                $saldoCalculado += $transacao->valor;
            } else {
                $saldoCalculado -= $transacao->valor;
            }
        }
        
        return $saldoCalculado;
    }

    /**
     * Atualiza o saldo atual baseado no cálculo das transações
     */
    public function updateSaldoCalculado()
    {
        $this->saldo_atual = $this->calculateSaldoAtual();
        $this->save();
        return $this;
    }

    public function updateSaldo($valor, $tipo = 'receita')
    {
        if ($tipo === 'receita') {
            $this->saldo_atual += $valor;
        } else {
            $this->saldo_atual -= $valor;
        }
        $this->save();
    }
}
