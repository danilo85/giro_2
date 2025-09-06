@extends('layouts.app')

@section('title', $orcamento->titulo)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $orcamento->titulo }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Orçamento #{{ $orcamento->id }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('public.orcamentos.public', $orcamento->token_publico) }}" 
               target="_blank"
               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-1M14 6h6m0 0v6m0-6L10 16"></path>
                </svg>
                Ver Público
            </a>
            <a href="{{ route('orcamentos.edit', $orcamento) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Editar
            </a>
            <a href="{{ route('orcamentos.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <!-- Status and Info -->
    <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex items-center space-x-6">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status:</span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($orcamento->status === 'rascunho') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                    @elseif($orcamento->status === 'enviado') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                    @elseif($orcamento->status === 'aprovado') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                    @elseif($orcamento->status === 'rejeitado') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                    @elseif($orcamento->status === 'quitado') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                    @endif">
                    {{ ucfirst($orcamento->status) }}
                </span>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor:</span>
                <span class="text-lg font-bold text-gray-900 dark:text-white">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</span>
            </div>
            @if($orcamento->data_validade)
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Válido até:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $orcamento->data_validade->format('d/m/Y') }}</span>
                </div>
            @endif
        </div>
        
        <!-- Quick Actions -->
        <div class="flex items-center space-x-2">
            @if($orcamento->status !== 'quitado')
                <button onclick="updateStatus('{{ $orcamento->id }}', 'aprovado')" 
                        class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Aprovar
                </button>
                <button onclick="updateStatus('{{ $orcamento->id }}', 'quitado')" 
                        class="inline-flex items-center px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Quitar
                </button>
            @endif
            <a href="{{ route('pagamentos.create', ['orcamento_id' => $orcamento->id]) }}" 
               class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Pagamento
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Cliente Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações do Cliente</h3>
                <div class="flex items-center space-x-4">
                    @if($orcamento->cliente->avatar)
                        <img src="{{ Storage::url($orcamento->cliente->avatar) }}" 
                             alt="{{ $orcamento->cliente->nome }}" 
                             class="h-16 w-16 rounded-full object-cover">
                    @else
                        <div class="h-16 w-16 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                            <span class="text-xl font-medium text-gray-600 dark:text-gray-300">{{ substr($orcamento->cliente->nome, 0, 1) }}</span>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $orcamento->cliente->nome }}</h4>
                        <p class="text-gray-600 dark:text-gray-400">{{ $orcamento->cliente->email }}</p>
                        @if($orcamento->cliente->telefone)
                            <p class="text-gray-600 dark:text-gray-400">{{ $orcamento->cliente->telefone }}</p>
                        @endif
                        @if($orcamento->cliente->empresa)
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $orcamento->cliente->empresa }}</p>
                        @endif
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('clientes.show', $orcamento->cliente) }}" 
                           class="inline-flex items-center px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg transition-colors">
                            Ver Cliente
                        </a>
                        @if($orcamento->cliente->email)
                            <a href="mailto:{{ $orcamento->cliente->email }}" 
                               class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                E-mail
                            </a>
                        @endif
                        @if($orcamento->cliente->whatsapp)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $orcamento->cliente->whatsapp) }}" 
                               target="_blank"
                               class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                                WhatsApp
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Autores -->
            @if($orcamento->autores->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Autores Responsáveis</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($orcamento->autores as $autor)
                            <div class="flex items-center space-x-3 p-3 border border-gray-200 dark:border-gray-600 rounded-lg">
                                @if($autor->avatar)
                                    <img src="{{ Storage::url($autor->avatar) }}" 
                                         alt="{{ $autor->nome }}" 
                                         class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ substr($autor->nome, 0, 1) }}</span>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $autor->nome }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $autor->especialidade }}</p>
                                </div>
                                <a href="{{ route('autores.show', $autor) }}" 
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Conteúdo -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Conteúdo da Proposta</h3>
                    <button onclick="copyContent()" 
                            class="inline-flex items-center px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        Copiar
                    </button>
                </div>
                <div id="content-area" class="prose dark:prose-invert max-w-none">
                    {!! nl2br(e($orcamento->descricao)) !!}
                </div>
            </div>

            <!-- Anexos e Documentos -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Anexos e Documentos</h3>
                <div id="file-upload-container"></div>
            </div>

            <!-- Observações -->
            @if($orcamento->observacoes)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Observações Internas</h3>
                    <div class="text-gray-600 dark:text-gray-400">
                        {!! nl2br(e($orcamento->observacoes)) !!}
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Resumo Financeiro -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resumo Financeiro</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Valor Total:</span>
                        <span class="font-semibold text-gray-900 dark:text-white">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Pago:</span>
                        <span class="font-semibold text-green-600">R$ {{ number_format($orcamento->pagamentos->sum('valor'), 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Saldo Restante:</span>
                        <span class="font-semibold text-red-600">R$ {{ number_format($orcamento->valor_total - $orcamento->pagamentos->sum('valor'), 2, ',', '.') }}</span>
                    </div>
                    @php
                        $percentualPago = $orcamento->valor_total > 0 ? ($orcamento->pagamentos->sum('valor') / $orcamento->valor_total) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Progresso:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($percentualPago, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($percentualPago, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações Gerais -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações Gerais</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Criado em:</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $orcamento->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600 dark:text-gray-400">Última atualização:</span>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $orcamento->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($orcamento->prazo_dias)
                        <div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Prazo:</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $orcamento->prazo_dias }} dias</p>
                        </div>
                    @endif
                    @if($orcamento->data_validade)
                        <div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Válido até:</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $orcamento->data_validade->format('d/m/Y') }}</p>
                        </div>
                    @endif
                    @if($orcamento->modelo_proposta_id)
                        <div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Modelo usado:</span>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $orcamento->modeloProposta->nome ?? 'N/A' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pagamentos -->
            @if($orcamento->pagamentos->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Pagamentos</h3>
                        <a href="{{ route('pagamentos.create', ['orcamento_id' => $orcamento->id]) }}" 
                           class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
                            + Novo
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($orcamento->pagamentos->sortByDesc('data_pagamento') as $pagamento)
                            <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $pagamento->data_pagamento->format('d/m/Y') }}</p>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($pagamento->forma_pagamento === 'dinheiro') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @elseif($pagamento->forma_pagamento === 'pix') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                        @elseif($pagamento->forma_pagamento === 'cartao') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                        @elseif($pagamento->forma_pagamento === 'transferencia') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-300
                                        @elseif($pagamento->forma_pagamento === 'boleto') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                        @elseif($pagamento->forma_pagamento === 'cheque') bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst($pagamento->forma_pagamento) }}
                                    </span>
                                </div>
                                <a href="{{ route('pagamentos.show', $pagamento) }}" 
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Histórico -->
            @if($orcamento->historico->count() > 0)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Histórico de Alterações</h3>
                    <div class="space-y-3 max-h-64 overflow-y-auto">
                        @foreach($orcamento->historico->sortByDesc('created_at') as $historico)
                            <div class="flex items-start space-x-3 p-3 border border-gray-200 dark:border-gray-600 rounded-lg">
                                <div class="flex-shrink-0">
                                    <div class="h-2 w-2 bg-blue-600 rounded-full mt-2"></div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $historico->acao }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $historico->created_at->format('d/m/Y H:i') }}</p>
                                    @if($historico->observacoes)
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $historico->observacoes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/orcamento-file-upload.js') }}"></script>
<script>
// Inicializar componente de upload de arquivos
document.addEventListener('DOMContentLoaded', function() {
    new OrcamentoFileUpload({
        containerId: 'file-upload-container',
        orcamentoId: {{ $orcamento->id }},
        categoria: 'anexo'
    });
});

function copyContent() {
    const content = document.getElementById('content-area').innerText;
    navigator.clipboard.writeText(content).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Copiado!';
        setTimeout(() => {
            button.innerHTML = originalText;
        }, 2000);
    });
}

function updateStatus(orcamentoId, status) {
    if (confirm(`Tem certeza que deseja alterar o status para "${status}"?`)) {
        fetch(`/api/orcamentos/${orcamentoId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao atualizar status: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao atualizar status');
        });
    }
}
</script>
@endpush
@endsection