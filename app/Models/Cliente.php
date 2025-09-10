<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'user_id',
        'nome',
        'pessoa_contato',
        'email',
        'telefone',
        'whatsapp',
        'avatar',
        'extrato_token',
        'extrato_token_generated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'extrato_token_generated_at' => 'datetime',
    ];

    /**
     * Relacionamento com User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com Orçamentos
     */
    public function orcamentos(): HasMany
    {
        return $this->hasMany(Orcamento::class);
    }

    /**
     * Scope para filtrar por usuário
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Accessor para avatar URL
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        return null;
    }

    /**
     * Gerar token único para extrato público
     */
    public function generateExtratoToken()
    {
        $this->extrato_token = bin2hex(random_bytes(32));
        $this->extrato_token_generated_at = now();
        $this->save();
        
        return $this->extrato_token;
    }

    /**
     * Regenerar token do extrato
     */
    public function regenerateExtratoToken()
    {
        return $this->generateExtratoToken();
    }

    /**
     * Verificar se o token é válido
     */
    public function isValidExtratoToken($token)
    {
        return $this->extrato_token === $token && !empty($this->extrato_token);
    }

    /**
     * Obter ou gerar token do extrato
     */
    public function getOrGenerateExtratoToken()
    {
        if (empty($this->extrato_token)) {
            return $this->generateExtratoToken();
        }
        
        return $this->extrato_token;
    }
}
