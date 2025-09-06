@extends('layouts.app')

@section('title', 'Detalhes do Pagamento')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Detalhes do Pagamento</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Informações completas do pagamento #{{ $pagamento->id }}</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('pagamentos.edit', $pagamento) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                
                <form method="POST" action="{{ route('pagamentos.destroy', $pagamento) }}" class="inline" 
                      onsubmit="return confirm('Tem certeza que deseja excluir este pagamento? Esta ação não pode ser desfeita.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Excluir
                    </button>
                </form>
                
                <a href="{{ route('pagamentos.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Principais -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Dados do Pagamento -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informações do Pagamento</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">ID do Pagamento</label>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">#{{ $pagamento->id }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Valor</label>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Data do Pagamento</label>
                            <p class="text-lg text-gray-900 dark:text-white">{{ $pagamento->data_pagamento->format('d/m/Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Forma de Pagamento</label>
                            @php
                                $formaColors = [
                                    'dinheiro' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                    'pix' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                    'cartao_credito' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                    'cartao_debito' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300',
                                    'transferencia' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    'boleto' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
                                    'cheque' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                    'outros' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                ];
                                $formaLabels = [
                                    'dinheiro' => 'Dinheiro',
                                    'pix' => 'PIX',
                                    'cartao_credito' => 'Cartão de Crédito',
                                    'cartao_debito' => 'Cartão de Débito',
                                    'transferencia' => 'Transferência Bancária',
                                    'boleto' => 'Boleto Bancário',
                                    'cheque' => 'Cheque',
                                    'outros' => 'Outros'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $formaColors[$pagamento->forma_pagamento] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $formaLabels[$pagamento->forma_pagamento] ?? ucfirst($pagamento->forma_pagamento) }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Data de Criação</label>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $pagamento->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Última Atualização</label>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $pagamento->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($pagamento->observacoes)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Observações</label>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">{{ $pagamento->observacoes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Informações do Orçamento -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Orçamento Relacionado</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-start space-x-4">
                        <img class="h-12 w-12 rounded-full" 
                             src="{{ $pagamento->orcamento->cliente->avatar ? Storage::url($pagamento->orcamento->cliente->avatar) : 'data:image/svg+xml,%3csvg width=\'100\' height=\'100\' xmlns=\'http://www.w3.org/2000/svg\'%3e%3crect width=\'100\' height=\'100\' fill=\'%23f3f4f6\'/%3e%3ctext x=\'50%25\' y=\'50%25\' font-size=\'18\' text-anchor=\'middle\' alignment-baseline=\'middle\' font-family=\'monospace, sans-serif\' fill=\'%236b7280\'%3e' . strtoupper(substr($pagamento->orcamento->cliente->nome, 0, 2)) . '%3c/text%3e%3c/svg%3e' }}" 
                             alt="{{ $pagamento->orcamento->cliente->nome }}">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $pagamento->orcamento->titulo }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Cliente: {{ $pagamento->orcamento->cliente->nome }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Orçamento #{{ $pagamento->orcamento->id }}</p>
                            
                            <div class="mt-3 flex items-center space-x-4">
                                @php
                                    $statusColors = [
                                        'rascunho' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'enviado' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                        'aprovado' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                        'rejeitado' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                        'em_andamento' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                        'concluido' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                                        'cancelado' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                        'quitado' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-300'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$pagamento->orcamento->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $pagamento->orcamento->status)) }}
                                </span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    Valor Total: <span class="font-medium">R$ {{ number_format($pagamento->orcamento->valor_total, 2, ',', '.') }}</span>
                                </span>
                            </div>
                            
                            <div class="mt-3">
                                <a href="{{ route('orcamentos.show', $pagamento->orcamento) }}" 
                                   class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Ver Orçamento Completo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Resumo Financeiro -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Resumo Financeiro</h2>
                </div>
                <div class="p-6 space-y-4">
                    @php
                        $totalPago = $pagamento->orcamento->pagamentos->sum('valor');
                        $saldoRestante = $pagamento->orcamento->valor_total - $totalPago;
                        $percentualPago = $pagamento->orcamento->valor_total > 0 ? ($totalPago / $pagamento->orcamento->valor_total) * 100 : 0;
                    @endphp
                    
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">R$ {{ number_format($pagamento->orcamento->valor_total, 2, ',', '.') }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Valor Total do Orçamento</div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total Pago</span>
                            <span class="text-sm font-medium text-green-600 dark:text-green-400">R$ {{ number_format($totalPago, 2, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Saldo Restante</span>
                            <span class="text-sm font-medium {{ $saldoRestante > 0 ? 'text-orange-600 dark:text-orange-400' : 'text-green-600 dark:text-green-400' }}">
                                R$ {{ number_format($saldoRestante, 2, ',', '.') }}
                            </span>
                        </div>
                        
                        <div class="pt-2">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-xs text-gray-600 dark:text-gray-400">Progresso</span>
                                <span class="text-xs text-gray-600 dark:text-gray-400">{{ number_format($percentualPago, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: {{ min($percentualPago, 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Ações Rápidas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ações Rápidas</h2>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('pagamentos.create', ['orcamento_id' => $pagamento->orcamento_id]) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Novo Pagamento
                    </a>
                    
                    <a href="{{ route('clientes.show', $pagamento->orcamento->cliente) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Ver Cliente
                    </a>
                    
                    @if($pagamento->orcamento->cliente->email)
                        <a href="mailto:{{ $pagamento->orcamento->cliente->email }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Enviar E-mail
                        </a>
                    @endif
                    
                    @if($pagamento->orcamento->cliente->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pagamento->orcamento->cliente->whatsapp) }}" 
                           target="_blank"
                           class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.119"/>
                            </svg>
                            WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection