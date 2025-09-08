<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Pagamento extends Model
{
    use HasFactory;

    protected $table = 'pagamentos';

    protected $fillable = [
        'orcamento_id',
        'bank_id',
        'valor',
        'data_pagamento',
        'forma_pagamento',
        'status',
        'observacoes',
        'transaction_id',
        'token_publico'
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'data_pagamento' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com Orçamento
     */
    public function orcamento(): BelongsTo
    {
        return $this->belongsTo(Orcamento::class);
    }

    /**
     * Relacionamento com Bank (conta bancária)
     */
    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * Relacionamento com Transaction (sistema financeiro)
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Accessor para valor formatado
     */
    public function getValorFormattedAttribute()
    {
        return 'R$ ' . number_format($this->valor, 2, ',', '.');
    }

    /**
     * Accessor para data formatada
     */
    public function getDataPagamentoFormattedAttribute()
    {
        return $this->data_pagamento->format('d/m/Y');
    }

    /**
     * Accessor para forma de pagamento formatada
     */
    public function getFormaPagamentoFormattedAttribute()
    {
        $formas = [
            'pix' => 'PIX',
            'dinheiro' => 'Dinheiro',
            'cartao_credito' => 'Cartão de Crédito',
            'cartao_debito' => 'Cartão de Débito',
            'transferencia' => 'Transferência',
            'boleto' => 'Boleto',
            'cheque' => 'Cheque'
        ];

        return $formas[$this->forma_pagamento] ?? ucfirst($this->forma_pagamento);
    }

    /**
     * Accessor para valor por extenso
     */
    public function getValorExtensoAttribute()
    {
        return $this->numeroParaExtenso($this->valor);
    }

    /**
     * Converte número para extenso em português
     */
    private function numeroParaExtenso($numero)
    {
        $numero = number_format($numero, 2, '.', '');
        $partes = explode('.', $numero);
        $reais = (int) $partes[0];
        $centavos = (int) $partes[1];
        
        $extenso = '';
        
        if ($reais == 0) {
            $extenso = 'zero';
        } else {
            $extenso = $this->converterParteInteira($reais);
        }
        
        if ($reais == 1) {
            $extenso .= ' real';
        } else {
            $extenso .= ' reais';
        }
        
        if ($centavos > 0) {
            $extenso .= ' e ' . $this->converterParteInteira($centavos);
            if ($centavos == 1) {
                $extenso .= ' centavo';
            } else {
                $extenso .= ' centavos';
            }
        }
        
        return $extenso;
    }
    
    /**
     * Converte parte inteira para extenso
     */
    private function converterParteInteira($numero)
    {
        $unidades = ['', 'um', 'dois', 'três', 'quatro', 'cinco', 'seis', 'sete', 'oito', 'nove'];
        $dezenas = ['', '', 'vinte', 'trinta', 'quarenta', 'cinquenta', 'sessenta', 'setenta', 'oitenta', 'noventa'];
        $especiais = ['dez', 'onze', 'doze', 'treze', 'quatorze', 'quinze', 'dezesseis', 'dezessete', 'dezoito', 'dezenove'];
        $centenas = ['', 'cento', 'duzentos', 'trezentos', 'quatrocentos', 'quinhentos', 'seiscentos', 'setecentos', 'oitocentos', 'novecentos'];
        
        if ($numero == 0) return '';
        if ($numero == 100) return 'cem';
        
        $resultado = '';
        
        // Milhares
        if ($numero >= 1000) {
            $milhares = intval($numero / 1000);
            if ($milhares == 1) {
                $resultado .= 'mil';
            } else {
                $resultado .= $this->converterParteInteira($milhares) . ' mil';
            }
            $numero = $numero % 1000;
            if ($numero > 0) $resultado .= ' e ';
        }
        
        // Centenas
        if ($numero >= 100) {
            $centena = intval($numero / 100);
            $resultado .= $centenas[$centena];
            $numero = $numero % 100;
            if ($numero > 0) $resultado .= ' e ';
        }
        
        // Dezenas e unidades
        if ($numero >= 20) {
            $dezena = intval($numero / 10);
            $resultado .= $dezenas[$dezena];
            $numero = $numero % 10;
            if ($numero > 0) $resultado .= ' e ';
        } elseif ($numero >= 10) {
            $resultado .= $especiais[$numero - 10];
            $numero = 0;
        }
        
        // Unidades
        if ($numero > 0) {
            $resultado .= $unidades[$numero];
        }
        
        return $resultado;
    }

    /**
     * Gerar token público único
     */
    public function generateTokenPublico()
    {
        do {
            $token = Str::random(32);
        } while (self::where('token_publico', $token)->exists());

        $this->update(['token_publico' => $token]);
        return $token;
    }

    /**
     * Accessor para URL pública do recibo
     */
    public function getUrlPublicaAttribute()
    {
        if (!$this->token_publico) {
            return null;
        }
        
        return url('public/recibo/' . $this->token_publico);
    }

    /**
     * Verificar se tem token público
     */
    public function hasTokenPublico()
    {
        return !empty($this->token_publico);
    }
}
