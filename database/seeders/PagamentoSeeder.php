<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pagamento;
use App\Models\Orcamento;
use App\Models\Bank;
use Carbon\Carbon;

class PagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar orçamentos que podem ter pagamentos (aprovados, finalizados ou pagos)
        $orcamentos = Orcamento::whereIn('status', [
            Orcamento::STATUS_APROVADO,
            Orcamento::STATUS_FINALIZADO,
            Orcamento::STATUS_PAGO
        ])->get();
        
        $banks = Bank::all();
        
        if ($orcamentos->isEmpty()) {
            $this->command->warn('Nenhum orçamento elegível encontrado. Execute OrcamentoSeeder primeiro.');
            return;
        }
        
        if ($banks->isEmpty()) {
            $this->command->warn('Nenhum banco encontrado. Execute BankSeeder primeiro.');
            return;
        }

        $formasPagamento = ['pix', 'dinheiro', 'cartao_credito', 'cartao_debito', 'transferencia', 'boleto', 'cheque'];
        $statusPagamento = ['pendente', 'processando', 'confirmado', 'cancelado'];

        $pagamentos = [];
        
        foreach ($orcamentos as $orcamento) {
            // Determinar quantos pagamentos este orçamento terá (1 a 3)
            $numPagamentos = rand(1, 3);
            $valorRestante = $orcamento->valor_total;
            
            for ($i = 0; $i < $numPagamentos; $i++) {
                if ($valorRestante <= 0) break;
                
                // Para o último pagamento, usar o valor restante
                if ($i == $numPagamentos - 1) {
                    $valorPagamento = $valorRestante;
                } else {
                    // Pagamento parcial (20% a 60% do valor restante)
                    $percentual = rand(20, 60) / 100;
                    $valorPagamento = round($valorRestante * $percentual, 2);
                }
                
                $valorRestante -= $valorPagamento;
                
                // Data do pagamento baseada no status do orçamento
                $dataPagamento = match($orcamento->status) {
                    Orcamento::STATUS_APROVADO => Carbon::now()->addDays(rand(1, 15)),
                    Orcamento::STATUS_FINALIZADO => Carbon::now()->subDays(rand(1, 10)),
                    Orcamento::STATUS_PAGO => Carbon::now()->subDays(rand(5, 30)),
                    default => Carbon::now()
                };
                
                // Status do pagamento baseado no status do orçamento
                $statusPag = match($orcamento->status) {
                    Orcamento::STATUS_APROVADO => rand(0, 1) ? 'pendente' : 'processando',
                    Orcamento::STATUS_FINALIZADO => rand(0, 1) ? 'confirmado' : 'processando',
                    Orcamento::STATUS_PAGO => 'confirmado',
                    default => 'pendente'
                };
                
                $pagamentos[] = [
                    'orcamento_id' => $orcamento->id,
                    'bank_id' => $banks->random()->id,
                    'valor' => $valorPagamento,
                    'data_pagamento' => $dataPagamento,
                    'forma_pagamento' => $formasPagamento[array_rand($formasPagamento)],
                    'status' => $statusPag,
                    'observacoes' => $this->gerarObservacao($statusPag, $i + 1, $numPagamentos),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        // Adicionar alguns pagamentos extras para variedade
        $pagamentosExtras = [
            [
                'orcamento_id' => $orcamentos->random()->id,
                'bank_id' => $banks->random()->id,
                'valor' => 2500.00,
                'data_pagamento' => Carbon::now()->subDays(5),
                'forma_pagamento' => 'pix',
                'status' => 'confirmado',
                'observacoes' => 'Pagamento via PIX - confirmado automaticamente.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'orcamento_id' => $orcamentos->random()->id,
                'bank_id' => $banks->random()->id,
                'valor' => 1800.00,
                'data_pagamento' => Carbon::now()->addDays(7),
                'forma_pagamento' => 'boleto',
                'status' => 'pendente',
                'observacoes' => 'Boleto enviado por email. Aguardando pagamento.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'orcamento_id' => $orcamentos->random()->id,
                'bank_id' => $banks->random()->id,
                'valor' => 5000.00,
                'data_pagamento' => Carbon::now()->subDays(15),
                'forma_pagamento' => 'transferencia',
                'status' => 'confirmado',
                'observacoes' => 'Transferência bancária confirmada via extrato.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'orcamento_id' => $orcamentos->random()->id,
                'bank_id' => $banks->random()->id,
                'valor' => 750.00,
                'data_pagamento' => Carbon::now()->subDays(2),
                'forma_pagamento' => 'cartao_credito',
                'status' => 'processando',
                'observacoes' => 'Pagamento em processamento pela operadora.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'orcamento_id' => $orcamentos->random()->id,
                'bank_id' => $banks->random()->id,
                'valor' => 3200.00,
                'data_pagamento' => Carbon::now()->subDays(8),
                'forma_pagamento' => 'dinheiro',
                'status' => 'confirmado',
                'observacoes' => 'Pagamento em dinheiro recebido presencialmente.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'orcamento_id' => $orcamentos->random()->id,
                'bank_id' => $banks->random()->id,
                'valor' => 1200.00,
                'data_pagamento' => Carbon::now()->addDays(3),
                'forma_pagamento' => 'cheque',
                'status' => 'pendente',
                'observacoes' => 'Cheque recebido. Aguardando compensação.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        $pagamentos = array_merge($pagamentos, $pagamentosExtras);
        
        // Inserir pagamentos em lotes para melhor performance
        $chunks = array_chunk($pagamentos, 50);
        foreach ($chunks as $chunk) {
            Pagamento::insert($chunk);
        }

        $this->command->info('Pagamentos criados com sucesso! Total: ' . count($pagamentos));
    }
    
    /**
     * Gerar observação baseada no status e posição do pagamento
     */
    private function gerarObservacao($status, $numeroPagamento, $totalPagamentos): string
    {
        $observacoes = [
            'pendente' => [
                'Aguardando confirmação do pagamento.',
                'Boleto enviado por email.',
                'PIX gerado. Aguardando pagamento.',
                'Aguardando processamento.',
            ],
            'processando' => [
                'Pagamento em processamento pela operadora.',
                'Verificando dados bancários.',
                'Aguardando confirmação do banco.',
                'Processamento em andamento.',
            ],
            'confirmado' => [
                'Pagamento confirmado com sucesso.',
                'Valor creditado na conta.',
                'Confirmado via extrato bancário.',
                'Recebimento confirmado.',
            ],
            'cancelado' => [
                'Pagamento cancelado pelo cliente.',
                'Transação não autorizada.',
                'Cancelado por falta de fundos.',
                'Cancelamento solicitado.',
            ],
        ];
        
        $obs = $observacoes[$status][array_rand($observacoes[$status])];
        
        if ($totalPagamentos > 1) {
            $obs .= " (Parcela {$numeroPagamento}/{$totalPagamentos})";
        }
        
        return $obs;
    }
}