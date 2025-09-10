<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Orcamento;
use App\Models\Pagamento;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExtratoController extends Controller
{
    /**
     * Exibe o extrato público do cliente
     *
     * @param int $cliente_id
     * @param string $token
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function show($cliente_id, $token)
    {
        // Buscar o cliente
        $cliente = Cliente::find($cliente_id);
        
        if (!$cliente) {
            abort(404, 'Cliente não encontrado');
        }
        
        // Validar o token
        if (!$cliente->isValidExtratoToken($token)) {
            abort(403, 'Token inválido ou expirado');
        }
        
        // Buscar orçamentos do cliente com seus pagamentos
        $orcamentos = Orcamento::where('cliente_id', $cliente_id)
            ->with(['pagamentos' => function($query) {
                $query->orderBy('data_pagamento', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calcular totais
        $totalOrcamentos = $orcamentos->sum('valor_total');
        $totalPago = 0;
        
        foreach ($orcamentos as $orcamento) {
            $orcamento->total_pago = $orcamento->pagamentos->sum('valor');
            $orcamento->saldo_restante = $orcamento->valor_total - $orcamento->total_pago;
            $totalPago += $orcamento->total_pago;
        }
        
        $saldoRestante = $totalOrcamentos - $totalPago;
        
        // Preparar dados para a view
        return view('extrato.public', [
            'cliente' => $cliente,
            'orcamentos' => $orcamentos,
            'totalOrcamentos' => $totalOrcamentos,
            'totalPago' => $totalPago,
            'saldoRestanteGeral' => $saldoRestante
        ]);
    }
}