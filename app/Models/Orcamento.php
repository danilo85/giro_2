<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Orcamento extends Model
{
    use HasFactory;

    protected $table = 'orcamentos';

    protected $fillable = [
        'cliente_id',
        'titulo',
        'descricao',
        'valor_total',
        'prazo_dias',
        'data_orcamento',
        'data_validade',
        'status',
        'observacoes',
        'observacoes_internas',
        'token_publico',
        'qrcode_image',
        'logo_image'
    ];

    protected $casts = [
        'valor_total' => 'decimal:2',
        'data_orcamento' => 'date',
        'data_validade' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Status constants - Alinhados com o ENUM do banco de dados
    const STATUS_RASCUNHO = 'rascunho';
    const STATUS_ANALISANDO = 'analisando';
    const STATUS_REJEITADO = 'rejeitado';
    const STATUS_APROVADO = 'aprovado';
    const STATUS_FINALIZADO = 'finalizado';
    const STATUS_PAGO = 'pago';
    const STATUS_QUITADO = 'quitado';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_RASCUNHO => 'Rascunho',
            self::STATUS_ANALISANDO => 'Analisando',
            self::STATUS_REJEITADO => 'Rejeitado',
            self::STATUS_APROVADO => 'Aprovado',
            self::STATUS_FINALIZADO => 'Finalizado',
            self::STATUS_PAGO => 'Pago',
            self::STATUS_QUITADO => 'Quitado'
        ];
    }

    /**
     * Boot method para gerar token público automaticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orcamento) {
            if (empty($orcamento->token_publico)) {
                $orcamento->token_publico = Str::random(32);
            }
        });
    }

    /**
     * Relacionamento com Cliente
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relacionamento com Autores (many-to-many)
     */
    public function autores(): BelongsToMany
    {
        return $this->belongsToMany(Autor::class, 'orcamento_autores')
                    ->withTimestamps();
    }

    /**
     * Relacionamento com Pagamentos
     */
    public function pagamentos(): HasMany
    {
        return $this->hasMany(Pagamento::class);
    }

    /**
     * Relacionamento com Histórico
     */
    public function historico(): HasMany
    {
        return $this->hasMany(HistoricoOrcamento::class);
    }

    /**
     * Relacionamento com Arquivos
     */
    public function arquivos(): HasMany
    {
        return $this->hasMany(OrcamentoFile::class);
    }

    /**
     * Relacionamento com Anexos
     */
    public function anexos(): HasMany
    {
        return $this->hasMany(OrcamentoFile::class)->where('categoria', 'anexo');
    }

    /**
     * Relacionamento com Avatars
     */
    public function avatars(): HasMany
    {
        return $this->hasMany(OrcamentoFile::class)->where('categoria', 'avatar');
    }

    /**
     * Relacionamento com Logos
     */
    public function logos(): HasMany
    {
        return $this->hasMany(OrcamentoFile::class)->where('categoria', 'logo');
    }

    /**
     * Relacionamento com Portfolio Work
     */
    public function portfolioWork()
    {
        return $this->hasOne(PortfolioWork::class, 'orcamento_id');
    }

    /**
     * Relacionamento com Projetos
     */
    public function projetos(): HasMany
    {
        return $this->hasMany(Projeto::class);
    }

    /**
     * Scope para filtrar por status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para filtrar por cliente do usuário
     */
    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('cliente', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    /**
     * Accessor para status formatado
     */
    public function getStatusLabelAttribute()
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    /**
     * Accessor para valor total formatado
     */
    public function getValorTotalFormattedAttribute()
    {
        return 'R$ ' . number_format($this->valor_total, 2, ',', '.');
    }

    /**
     * Calcular valor total pago
     */
    public function getValorPagoAttribute()
    {
        return $this->pagamentos()->sum('valor');
    }

    /**
     * Accessor para valor pago formatado
     */
    public function getValorPagoFormattedAttribute()
    {
        return 'R$ ' . number_format($this->valor_pago, 2, ',', '.');
    }

    /**
     * Calcular saldo restante
     */
    public function getSaldoRestanteAttribute()
    {
        return $this->valor_total - $this->valor_pago;
    }

    /**
     * Accessor para saldo restante formatado
     */
    public function getSaldoRestanteFormattedAttribute()
    {
        return 'R$ ' . number_format($this->saldo_restante, 2, ',', '.');
    }

    /**
     * Calcular percentual pago
     */
    public function getPercentualPagoAttribute()
    {
        if ($this->valor_total == 0) return 0;
        return round(($this->valor_pago / $this->valor_total) * 100, 1);
    }

    /**
     * Accessor para URL pública
     */
    public function getPublicUrlAttribute()
    {
        return route('public.orcamentos.public', $this->token_publico);
    }

    /**
     * Método para quitar orçamento
     */
    public function quitar()
    {
        $this->update(['status' => self::STATUS_QUITADO]);
        
        // Registrar no histórico
        $this->historico()->create([
            'user_id' => auth()->id(),
            'acao' => 'quitacao',
            'descricao' => 'Orçamento quitado',
            'dados_anteriores' => ['status' => $this->getOriginal('status')],
            'dados_novos' => ['status' => self::STATUS_QUITADO]
        ]);
    }

    /**
     * Método para aprovar orçamento
     */
    public function aprovar()
    {
        $statusAnterior = $this->status;
        $this->update(['status' => self::STATUS_APROVADO]);
        
        // Registrar no histórico
        $this->historico()->create([
            'user_id' => auth()->id(),
            'acao' => 'aprovacao',
            'descricao' => 'Orçamento aprovado',
            'dados_anteriores' => ['status' => $statusAnterior],
            'dados_novos' => ['status' => self::STATUS_APROVADO]
        ]);
        
        // Criar projeto automaticamente no Kanban
        $this->criarProjetoKanban();
    }
    
    /**
     * Criar projeto automaticamente no Kanban quando orçamento é aprovado
     */
    public function criarProjetoKanban()
    {
        try {
            // Verificar se já existe projeto para este orçamento
            $projetoExistente = \App\Models\Projeto::where('orcamento_id', $this->id)->first();
            if ($projetoExistente) {
                return $projetoExistente;
            }
            
            // Buscar primeira etapa (Backlog)
            $primeiraEtapa = \App\Models\EtapaKanban::where('user_id', $this->cliente->user_id)
                                                    ->orderBy('ordem')
                                                    ->first();
            
            if (!$primeiraEtapa) {
                // Se não existir etapas, criar as padrão
                $this->criarEtapasPadrao();
                $primeiraEtapa = \App\Models\EtapaKanban::where('user_id', $this->cliente->user_id)
                                                        ->orderBy('ordem')
                                                        ->first();
            }
            
            // Criar o projeto
            $projeto = \App\Models\Projeto::create([
                'orcamento_id' => $this->id,
                'etapa_kanban_id' => $primeiraEtapa->id,
                'titulo' => $this->titulo,
                'descricao' => $this->descricao,
                'valor' => $this->valor_total,
                'data_inicio' => now(),
                'data_prazo' => now()->addDays(30), // Prazo padrão de 30 dias
                'status' => 'ativo',
                'prioridade' => 'media',
                'progresso' => 0
            ]);
            
            \Log::info('Projeto criado automaticamente no Kanban', [
                'projeto_id' => $projeto->id,
                'orcamento_id' => $this->id
            ]);
            
            return $projeto;
            
        } catch (\Exception $e) {
            \Log::error('Erro ao criar projeto no Kanban: ' . $e->getMessage(), [
                'orcamento_id' => $this->id,
                'error' => $e->getMessage()
            ]);
            
            // Não falha a aprovação do orçamento se houver erro na criação do projeto
            return null;
        }
    }
    
    /**
     * Criar etapas padrão do Kanban para o usuário
     */
    private function criarEtapasPadrao()
    {
        $etapasPadrao = [
            ['nome' => 'Backlog', 'cor' => '#6B7280', 'ordem' => 1],
            ['nome' => 'Em Andamento', 'cor' => '#3B82F6', 'ordem' => 2],
            ['nome' => 'Revisão', 'cor' => '#F59E0B', 'ordem' => 3],
            ['nome' => 'Concluído', 'cor' => '#10B981', 'ordem' => 4]
        ];
        
        foreach ($etapasPadrao as $etapa) {
            \App\Models\EtapaKanban::create([
                'user_id' => $this->cliente->user_id,
                'nome' => $etapa['nome'],
                'cor' => $etapa['cor'],
                'ordem' => $etapa['ordem']
            ]);
        }
    }

    /**
     * Método para rejeitar orçamento
     */
    public function rejeitar($motivo = null)
    {
        $statusAnterior = $this->status;
        $this->update(['status' => self::STATUS_REJEITADO]);
        
        // Registrar no histórico
        $this->historico()->create([
            'user_id' => auth()->id(),
            'acao' => 'rejeicao',
            'descricao' => $motivo ? "Orçamento rejeitado: {$motivo}" : 'Orçamento rejeitado',
            'dados_anteriores' => ['status' => $statusAnterior],
            'dados_novos' => ['status' => self::STATUS_REJEITADO]
        ]);
    }

    /**
     * Atualizar status do orçamento
     */
    public function atualizarStatus($novoStatus, $descricao = null)
    {
        $statusAnterior = $this->status;
        $this->status = $novoStatus;
        $this->save();

        // Registrar no histórico (temporariamente desabilitado para debug)
        try {
            HistoricoOrcamento::create([
                'orcamento_id' => $this->id,
                'acao' => 'status_atualizado',
                'descricao' => $descricao ?? "Status alterado de {$statusAnterior} para {$novoStatus}",
                'user_id' => Auth::check() ? Auth::id() : null
            ]);
        } catch (\Exception $e) {
            // Log do erro mas não falha a operação
            \Log::error('Erro ao registrar histórico: ' . $e->getMessage());
        }

        return $this;
    }

}
