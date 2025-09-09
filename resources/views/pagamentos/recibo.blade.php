<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recibo de Pagamento #{{ $pagamento->id }}</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Meta tags para SEO -->
    <meta name="description" content="Recibo de Pagamento #{{ $pagamento->id }}">
    <meta name="robots" content="noindex, nofollow">
    
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .bg-gray-50 { background: white !important; }
            .shadow-lg { box-shadow: none !important; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 no-print">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-gray-900">Recibo de Pagamento</h1>
                    </div>
                    <div class="hidden md:block">
                        <span class="text-sm text-gray-600">#{{ $pagamento->id }}</span>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <button onclick="window.print()" 
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Imprimir
                    </button>
                    
                    <a href="{{ route('public.orcamentos.public', $pagamento->orcamento->token_publico) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Ver Proposta
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Receipt Header -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8 mb-8">
            <div class="text-center mb-8">
                <!-- Logo/Avatar do usuário -->
                @if(optional($pagamento->orcamento->cliente->user)->getLogoByType('icone'))
                    <img src="{{ asset(storage/' . $pagamento->orcamento->cliente->user->getLogoByType('icone')->caminho) }}" 
                         alt="Logo" 
                         class="w-16 h-16 rounded-full object-cover border-2 border-gray-300">
                @else
                    <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xl border-2 border-gray-300">
                        {{ strtoupper(substr(optional($pagamento->orcamento->cliente->user)->name ?? 'U', 0, 1)) }}
                    </div>
                @endif
                
                <h1 class="text-3xl font-bold text-gray-900 mb-2">RECIBO DE PAGAMENTO</h1>
                <p class="text-lg text-gray-600">Nº {{ str_pad($pagamento->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p class="text-sm text-gray-500 mt-2">
                    Emitido em {{ $pagamento->created_at->format('d/m/Y') }}
                </p>
            </div>
            
            <!-- Payment Amount -->
            <div class="text-center mb-8 p-6 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Valor Recebido</p>
                <p class="text-4xl font-bold text-green-600">
                    R$ {{ number_format($pagamento->valor, 2, ',', '.') }}
                </p>
                <p class="text-sm text-gray-600 mt-2">
                    ({{ ucfirst(\NumberFormatter::create('pt_BR', \NumberFormatter::SPELLOUT)->format($pagamento->valor)) }} reais)
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Pagador Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recebido de:</h3>
                    <div class="space-y-2">
                        <p class="text-lg font-medium text-gray-900">{{ $pagamento->orcamento->cliente->nome }}</p>
                        @if($pagamento->orcamento->cliente->empresa)
                            <p class="text-gray-600">{{ $pagamento->orcamento->cliente->empresa }}</p>
                        @endif
                        @if($pagamento->orcamento->cliente->cpf_cnpj)
                            <p class="text-gray-600">{{ $pagamento->orcamento->cliente->cpf_cnpj }}</p>
                        @endif
                        <p class="text-gray-600">{{ $pagamento->orcamento->cliente->email }}</p>
                        @if($pagamento->orcamento->cliente->telefone)
                            <p class="text-gray-600">{{ $pagamento->orcamento->cliente->telefone }}</p>
                        @endif
                        @if($pagamento->orcamento->cliente->endereco_completo)
                            <p class="text-gray-600">{{ $pagamento->orcamento->cliente->endereco_completo }}</p>
                        @endif
                    </div>
                </div>
                
                <!-- Recebedor Info -->
                @if($pagamento->orcamento->autores->count() > 0)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recebido por:</h3>
                        <div class="space-y-4">
                            @foreach($pagamento->orcamento->autores as $autor)
                                <div class="flex items-center space-x-3">
                                    @if($autor->avatar)
                                        <img src="{{ Storage::url($autor->avatar) }}" 
                                             alt="{{ $autor->nome }}" 
                                             class="h-12 w-12 rounded-full object-cover">
                                    @else
                                        <div class="h-12 w-12 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-lg font-medium text-gray-600">{{ substr($autor->nome, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $autor->nome }}</p>
                                        @if($autor->especialidade)
                                            <p class="text-sm text-gray-600">{{ $autor->especialidade }}</p>
                                        @endif
                                        @if($autor->cpf_cnpj)
                                            <p class="text-sm text-gray-600">{{ $autor->cpf_cnpj }}</p>
                                        @endif
                                        @if($autor->email)
                                            <p class="text-sm text-gray-600">{{ $autor->email }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Payment Details -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Detalhes do Pagamento</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Data do Pagamento:</span>
                        <span class="font-medium text-gray-900">{{ $pagamento->data_pagamento->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Forma de Pagamento:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($pagamento->forma_pagamento === 'dinheiro') bg-green-100 text-green-800
                            @elseif($pagamento->forma_pagamento === 'pix') bg-blue-100 text-blue-800
                            @elseif($pagamento->forma_pagamento === 'cartao') bg-purple-100 text-purple-800
                            @elseif($pagamento->forma_pagamento === 'transferencia') bg-indigo-100 text-indigo-800
                            @elseif($pagamento->forma_pagamento === 'boleto') bg-yellow-100 text-yellow-800
                            @elseif($pagamento->forma_pagamento === 'cheque') bg-orange-100 text-orange-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($pagamento->forma_pagamento) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Valor:</span>
                        <span class="font-bold text-gray-900">R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Referente ao Orçamento:</span>
                        <span class="font-medium text-gray-900">#{{ $pagamento->orcamento->id }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Descrição:</span>
                        <span class="font-medium text-gray-900">{{ $pagamento->orcamento->titulo }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Recibo Emitido em:</span>
                        <span class="font-medium text-gray-900">{{ $pagamento->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
            
            @if($pagamento->observacoes)
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Observações:</h4>
                    <p class="text-sm text-gray-600">{{ $pagamento->observacoes }}</p>
                </div>
            @endif
        </div>
        
        <!-- Project Summary -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Resumo do Projeto</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Valor Total do Projeto:</span>
                        <span class="font-bold text-gray-900">R$ {{ number_format($pagamento->orcamento->valor_total, 2, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Total Pago até Agora:</span>
                        <span class="font-bold text-green-600">R$ {{ number_format($pagamento->orcamento->pagamentos->sum('valor'), 2, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Saldo Restante:</span>
                        <span class="font-bold text-red-600">R$ {{ number_format($pagamento->orcamento->valor_total - $pagamento->orcamento->pagamentos->sum('valor'), 2, ',', '.') }}</span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Status do Projeto:</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($pagamento->orcamento->status === 'rascunho') bg-gray-100 text-gray-800
                            @elseif($pagamento->orcamento->status === 'enviado') bg-blue-100 text-blue-800
                            @elseif($pagamento->orcamento->status === 'aprovado') bg-green-100 text-green-800
                            @elseif($pagamento->orcamento->status === 'rejeitado') bg-red-100 text-red-800
                            @elseif($pagamento->orcamento->status === 'quitado') bg-purple-100 text-purple-800
                            @endif">
                            {{ ucfirst($pagamento->orcamento->status) }}
                        </span>
                    </div>
                    
                    @php
                        $percentualPago = $pagamento->orcamento->valor_total > 0 ? ($pagamento->orcamento->pagamentos->sum('valor') / $pagamento->orcamento->valor_total) * 100 : 0;
                    @endphp
                    
                    <div class="py-2">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Progresso do Pagamento:</span>
                            <span class="font-medium text-gray-900">{{ number_format($percentualPago, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-green-600 h-3 rounded-full" style="width: {{ min($percentualPago, 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Signature Area -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Assinaturas</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <div class="text-center">
                    <div class="border-t border-gray-400 pt-2 mt-16">
                        <p class="text-sm text-gray-600">{{ $pagamento->orcamento->cliente->nome }}</p>
                        <p class="text-xs text-gray-500">Pagador</p>
                    </div>
                </div>
                
                <!-- Assinatura Digital do Usuário -->
                @if(optional($pagamento->orcamento->cliente->user)->assinatura_digital)
                    <div class="text-center">
                        <img src="{{ asset('storage/' . $pagamento->orcamento->cliente->user->assinatura_digital) }}" 
                             alt="Assinatura Digital" 
                             class="max-h-16 mx-auto mb-2">
                        <div class="border-t border-gray-400 pt-1">
                            <p class="text-sm font-medium">{{ optional($pagamento->orcamento->cliente->user)->name }}</p>
                        </div>
                    </div>
                @else
                    <div class="text-center">
                        <div class="border-t border-gray-400 pt-1 mt-12">
                            <p class="text-sm font-medium">{{ optional($pagamento->orcamento->cliente->user)->name }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-8 no-print">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500">
                Recibo gerado em {{ $pagamento->created_at->format('d/m/Y H:i') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                Este documento comprova o recebimento do valor especificado
            </p>
        </div>
    </footer>
</body>
</html>