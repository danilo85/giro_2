<?php

namespace App\Observers;

use App\Models\Orcamento;
use App\Models\HistoricoEntry;
use Illuminate\Support\Facades\Auth;

class OrcamentoObserver
{
    /**
     * Handle the Orcamento "created" event.
     */
    public function created(Orcamento $orcamento): void
    {
        $this->createHistoryEntry($orcamento, 'system', 'Orçamento criado', [
            'valor_total' => $orcamento->valor_total,
            'status' => $orcamento->status,
            'cliente' => $orcamento->cliente->nome ?? 'N/A'
        ]);
    }

    /**
     * Handle the Orcamento "updated" event.
     */
    public function updated(Orcamento $orcamento): void
    {
        $changes = $orcamento->getChanges();
        $original = $orcamento->getOriginal();
        
        if (empty($changes)) {
            return;
        }

        // Detectar mudanças específicas
        if (isset($changes['status'])) {
            $this->createHistoryEntry($orcamento, 'status_change', 'Status alterado', [
                'status_anterior' => $original['status'] ?? 'N/A',
                'status_novo' => $changes['status'],
                'motivo' => 'Alteração automática do sistema'
            ]);
        }

        if (isset($changes['valor_total'])) {
            $this->createHistoryEntry($orcamento, 'system', 'Valor total alterado', [
                'valor_anterior' => $original['valor_total'] ?? 0,
                'valor_novo' => $changes['valor_total'],
                'diferenca' => ($changes['valor_total'] - ($original['valor_total'] ?? 0))
            ]);
        }

        // Outras mudanças gerais
        $ignoredFields = ['updated_at', 'status', 'valor_total'];
        $relevantChanges = array_diff_key($changes, array_flip($ignoredFields));
        
        if (!empty($relevantChanges)) {
            $this->createHistoryEntry($orcamento, 'system', 'Orçamento editado', [
                'campos_alterados' => array_keys($relevantChanges),
                'total_alteracoes' => count($relevantChanges)
            ]);
        }
    }

    /**
     * Handle the Orcamento "deleted" event.
     */
    public function deleted(Orcamento $orcamento): void
    {
        $this->createHistoryEntry($orcamento, 'system', 'Orçamento excluído', [
            'valor_total' => $orcamento->valor_total,
            'status_final' => $orcamento->status,
            'motivo' => 'Exclusão pelo usuário'
        ]);
    }

    /**
     * Handle the Orcamento "restored" event.
     */
    public function restored(Orcamento $orcamento): void
    {
        $this->createHistoryEntry($orcamento, 'system', 'Orçamento restaurado', [
            'valor_total' => $orcamento->valor_total,
            'status' => $orcamento->status,
            'motivo' => 'Restauração pelo usuário'
        ]);
    }

    /**
     * Create a history entry for the orcamento.
     */
    private function createHistoryEntry(Orcamento $orcamento, string $tipo, string $titulo, array $detalhes = []): void
    {
        try {
            HistoricoEntry::create([
                'orcamento_id' => $orcamento->id,
                'user_id' => Auth::id() ?? 1, // Fallback para sistema
                'type' => $tipo,
                'title' => $titulo,
                'description' => $this->generateDescription($tipo, $titulo, $detalhes),
                'entry_date' => now(),
                'metadata' => array_merge($detalhes, [
                    'created_via' => 'system',
                    'automatic' => true
                ])
            ]);
        } catch (\Exception $e) {
            // Log error but don't break the main operation
            \Log::error('Erro ao criar entrada no histórico: ' . $e->getMessage(), [
                'orcamento_id' => $orcamento->id,
                'type' => $tipo,
                'title' => $titulo
            ]);
        }
    }

    /**
     * Generate a description based on the event type and details.
     */
    private function generateDescription(string $tipo, string $titulo, array $detalhes): string
    {
        switch ($tipo) {
            case 'system':
                if (isset($detalhes['valor_anterior'])) {
                    return sprintf(
                        'Valor total alterado de R$ %s para R$ %s (diferença: R$ %s).',
                        number_format($detalhes['valor_anterior'] ?? 0, 2, ',', '.'),
                        number_format($detalhes['valor_novo'] ?? 0, 2, ',', '.'),
                        number_format($detalhes['diferenca'] ?? 0, 2, ',', '.')
                    );
                }
                if (isset($detalhes['valor_total'])) {
                    return sprintf(
                        'Orçamento criado automaticamente pelo sistema. Valor: R$ %s, Status: %s, Cliente: %s',
                        number_format($detalhes['valor_total'] ?? 0, 2, ',', '.'),
                        ucfirst($detalhes['status'] ?? 'N/A'),
                        $detalhes['cliente'] ?? 'N/A'
                    );
                }
                if (isset($detalhes['campos_alterados'])) {
                    return sprintf(
                        'Orçamento editado automaticamente. %d campo(s) alterado(s): %s',
                        $detalhes['total_alteracoes'] ?? 0,
                        implode(', ', $detalhes['campos_alterados'] ?? [])
                    );
                }
                return $titulo . ' - Evento registrado automaticamente pelo sistema.';

            case 'status_change':
                return sprintf(
                    'Status alterado de "%s" para "%s" automaticamente pelo sistema.',
                    ucfirst($detalhes['status_anterior'] ?? 'N/A'),
                    ucfirst($detalhes['status_novo'] ?? 'N/A')
                );

            default:
                return $titulo . ' - Evento registrado automaticamente pelo sistema.';
        }
    }
}