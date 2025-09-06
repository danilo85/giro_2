<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class CreditCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nome_cartao',
        'bandeira',
        'bandeira_logo_url',
        'numero',
        'limite_total',
        'limite_utilizado',
        'data_fechamento',
        'data_vencimento',
        'observacoes',
        'ativo'
    ];

    protected $casts = [
        'limite_total' => 'decimal:2',
        'limite_utilizado' => 'decimal:2',
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
    public function getLimiteDisponivelAttribute()
    {
        return $this->limite_total - $this->limite_utilizado;
    }

    public function getPercentualUtilizadoAttribute()
    {
        if ($this->limite_total == 0) return 0;
        return ($this->limite_utilizado / $this->limite_total) * 100;
    }

    public function updateLimiteUtilizado($valor, $operacao = 'adicionar')
    {
        if ($operacao === 'adicionar') {
            $this->limite_utilizado += $valor;
        } else {
            $this->limite_utilizado -= $valor;
        }
        $this->save();
    }

    public function getProximoVencimento()
    {
        $hoje = Carbon::now();
        $vencimento = Carbon::create($hoje->year, $hoje->month, $this->data_vencimento);
        
        if ($vencimento->isPast()) {
            $vencimento->addMonth();
        }
        
        return $vencimento;
    }

    public function getDiasParaVencimento()
    {
        return Carbon::now()->diffInDays($this->getProximoVencimento(), false);
    }
}
