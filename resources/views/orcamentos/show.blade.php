@extends('layouts.app')

@section('title', $orcamento->titulo)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Mensagens de Sucesso e Erro -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $orcamento->titulo }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Orçamento #{{ $orcamento->id }}</p>
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
            <!-- Ações -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ações</h3>
                <div class="space-y-3">
                    <a href="{{ route('public.orcamentos.public', $orcamento->token_publico) }}" 
                       target="_blank"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-1M14 6h6m0 0v6m0-6L10 16"></path>
                        </svg>
                        Ver Público
                    </a>
                    <a href="{{ route('orcamentos.edit', $orcamento) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar
                    </a>
                    <a href="{{ route('orcamentos.historico.index', $orcamento->id) }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Histórico
                    </a>
                    <a href="{{ route('orcamentos.index') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar
                    </a>
                </div>
            </div>

            <!-- Status e Resumo Financeiro -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status e Resumo Financeiro</h3>
                
                <!-- Status e Informações Principais -->
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
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
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Valor Total:</span>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</span>
                    </div>
                    @if($orcamento->data_validade)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Válido até:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $orcamento->data_validade->format('d/m/Y') }}</span>
                        </div>
                    @endif
                    @if($orcamento->prazo_dias)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Prazo:</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $orcamento->prazo_dias }} dias</span>
                        </div>
                    @endif
                </div>

                <!-- Divisor -->
                <div class="border-t border-gray-200 dark:border-gray-600 my-4"></div>

                <!-- Resumo Financeiro -->
                <div class="space-y-4 mb-6">
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

                <!-- Quick Actions -->
                <div class="space-y-3">
                    <!-- Status Selector -->
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status do Orçamento</label>
                        <form id="status-form" method="POST" action="{{ route('orcamentos.update-status', $orcamento) }}" class="relative">
                            @csrf
                            @method('PATCH')
                            <select name="status" onchange="document.getElementById('status-form').submit()" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm appearance-none pr-8
                                    @if($orcamento->status === 'aprovado') border-green-500 bg-green-50 dark:bg-green-900/20
                                    @elseif($orcamento->status === 'analisando') border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20
                                    @elseif($orcamento->status === 'rejeitado') border-red-500 bg-red-50 dark:bg-red-900/20
                                    @elseif($orcamento->status === 'quitado') border-purple-500 bg-purple-50 dark:bg-purple-900/20
                                    @endif">
                                <option value="analisando" {{ $orcamento->status === 'analisando' ? 'selected' : '' }}>🟡 Analisando</option>
                                <option value="aprovado" {{ $orcamento->status === 'aprovado' ? 'selected' : '' }}>🟢 Aprovado</option>
                                <option value="rejeitado" {{ $orcamento->status === 'rejeitado' ? 'selected' : '' }}>🔴 Rejeitado</option>
                                <option value="quitado" {{ $orcamento->status === 'quitado' ? 'selected' : '' }}>🟣 Quitado</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Novo Pagamento Button -->
                    <a href="{{ route('pagamentos.create', ['orcamento_id' => $orcamento->id]) }}" 
                       class="w-full inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Novo Pagamento
                    </a>
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
let orcamentoFileUpload;
document.addEventListener('DOMContentLoaded', function() {
    orcamentoFileUpload = new OrcamentoFileUpload({
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

// Removed AJAX implementation - now using traditional form submission for better reliability
</script>
@endpush
@endsection