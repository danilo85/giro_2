<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Projeto extends Model
{
    use HasFactory;
    
    protected $table = 'projetos';
    
    protected $fillable = [
        'orcamento_id',
        'etapa_id',
        'posicao',
        'moved_at'
    ];
    
    protected $casts = [
        'posicao' => 'integer',
        'moved_at' => 'datetime'
    ];
    
    /**
     * Relacionamento com Orçamento
     */
    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class);
    }
    
    /**
     * Relacionamento com Etapa Kanban
     */
    public function etapa(): BelongsTo
    {
        return $this->belongsTo(EtapaKanban::class, 'etapa_id');
    }
    
    /**
     * Scope para ordenar por posição
     */
    public function scopeOrdenadosPorPosicao($query)
    {
        return $query->orderBy('posicao');
    }
    
    /**
     * Scope para filtrar por etapa
     */
    public function scopePorEtapa($query, $etapaId)
    {
        return $query->where('etapa_id', $etapaId);
    }
    
    /**
     * Scope para projetos do usuário
     */
    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('orcamento.cliente', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
}
