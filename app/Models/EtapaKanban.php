<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EtapaKanban extends Model
{
    use HasFactory;
    
    protected $table = 'etapas_kanban';
    
    protected $fillable = [
        'nome',
        'cor',
        'ordem',
        'ativa'
    ];
    
    protected $casts = [
        'ativa' => 'boolean',
        'ordem' => 'integer'
    ];
    
    /**
     * Relacionamento com projetos
     */
    public function projetos()
    {
        return $this->hasMany(Projeto::class, 'etapa_id');
    }
    
    /**
     * Scope para etapas ativas
     */
    public function scopeAtivas($query)
    {
        return $query->where('ativa', true);
    }
    
    /**
     * Scope para ordenar por ordem
     */
    public function scopeOrdenadas($query)
    {
        return $query->orderBy('ordem');
    }
}
