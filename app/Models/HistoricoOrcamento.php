<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoricoOrcamento extends Model
{
    use HasFactory;

    protected $table = 'historico_orcamentos';

    protected $fillable = [
        'user_id',
        'orcamento_id',
        'acao',
        'descricao',
        'dados_anteriores',
        'dados_novos'
    ];

    protected $casts = [
        'dados_anteriores' => 'array',
        'dados_novos' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com Orçamento
     */
    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class);
    }

    /**
     * Scope para filtrar por usuário
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para filtrar por orçamento
     */
    public function scopeForOrcamento($query, $orcamentoId)
    {
        return $query->where('orcamento_id', $orcamentoId);
    }

    /**
     * Accessor para data formatada
     */
    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }
}
