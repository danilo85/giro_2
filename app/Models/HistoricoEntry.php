<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HistoricoEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'orcamento_id',
        'user_id',
        'type',
        'title',
        'description',
        'entry_date',
        'metadata',
        'completed'
    ];

    protected $casts = [
        'entry_date' => 'datetime',
        'metadata' => 'array'
    ];

    /**
     * Relacionamento com Orçamento
     */
    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class);
    }

    /**
     * Relacionamento com Usuário
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com Arquivos
     */
    public function files(): HasMany
    {
        return $this->hasMany(HistoricoFile::class);
    }

    /**
     * Scope para ordenar por data de entrada
     */
    public function scopeOrderByEntryDate($query, $direction = 'desc')
    {
        return $query->orderBy('entry_date', $direction);
    }

    /**
     * Scope para filtrar por tipo
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope para filtrar por orçamento
     */
    public function scopeForOrcamento($query, $orcamentoId)
    {
        return $query->where('orcamento_id', $orcamentoId);
    }
}
