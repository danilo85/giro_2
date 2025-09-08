<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLogo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipo',
        'caminho',
        'nome_original'
    ];

    protected $casts = [
        'tipo' => 'string'
    ];

    /**
     * Relacionamento com User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor para URL da imagem
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->caminho);
    }
}
