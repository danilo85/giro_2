<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Extrato - {{ $cliente->nome }}</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    
    <!-- Meta tags para SEO -->
    <meta name="description" content="Extrato de {{ $cliente->nome }}">
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
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <h1 class="text-xl font-bold text-gray-900">Extrato do Cliente</h1>
                    </div>
                    <div class="hidden md:block">
                        <span class="text-sm text-gray-600">{{ $cliente->nome }}</span>
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
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Client Info -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-8 mb-8">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-6">
                    @if($cliente->avatar)
                        <img src="{{ Storage::url($cliente->avatar) }}" 
                             alt="{{ $cliente->nome }}" 
                             class="h-20 w-20 rounded-full object-cover">
                    @else
                        <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-2xl font-medium text-gray-600">{{ substr($cliente->nome, 0, 1) }}</span>
                        </div>
                    @endif
                    
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $cliente->nome }}</h1>
                        @if($cliente->empresa)
                            <p class="text-lg text-gray-600">{{ $cliente->empresa }}</p>
                        @endif
                        @if($cliente->cpf_cnpj)
                            <p class="text-sm text-gray-500">{{ $cliente->cpf_cnpj }}</p>
                        @endif
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="text-sm text-gray-600">{{ $cliente->email }}</span>
                            @if($cliente->telefone)
                                <span class="text-sm text-gray-600">{{ $cliente->telefone }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="text-right">
                    <p class="text-sm text-gray-500">Extrato gerado em</p>
                    <p class="text-lg font-medium text-gray-900">{{ now()->format('d/m/Y H:i') }}</p>
                    @if(request('periodo'))
                        <p class="text-sm text-gray-600 mt-2">
                            Período: {{ request('data_inicio') ? \Carbon\Carbon::parse(request('data_inicio'))->format('d/m/Y') : 'Início' }} 
                            até {{ request('data_fim') ? \Carbon\Carbon::parse(request('data_fim'))->format('d/m/Y') : 'Hoje' }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total de Orçamentos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $orcamentos->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Valor Total</p>
                        <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($orcamentos->sum('valor_total'), 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Pago</p>
                        @php
                            $totalPago = $orcamentos->sum(function($orcamento) {
                                return $orcamento->pagamentos->sum('valor');
                            });
                        @endphp
                        <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($totalPago, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Saldo Devedor</p>
                        @php
                            $saldoDevedor = $orcamentos->sum('valor_total') - $totalPago;
                        @endphp
                        <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($saldoDevedor, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Budgets List -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Histórico de Orçamentos</h3>
            </div>
            
            @if($orcamentos->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orçamento</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Pago</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progresso</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orcamentos as $orcamento)
                                @php
                                    $valorPago = $orcamento->pagamentos->sum('valor');
                                    $saldo = $orcamento->valor_total - $valorPago;
                                    $percentual = $orcamento->valor_total > 0 ? ($valorPago / $orcamento->valor_total) * 100 : 0;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $orcamento->titulo }}</div>
                                            <div class="text-sm text-gray-500">#{{ $orcamento->id }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $orcamento->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($orcamento->status === 'rascunho') bg-gray-100 text-gray-800
                                            @elseif($orcamento->status === 'enviado') bg-blue-100 text-blue-800
                                            @elseif($orcamento->status === 'aprovado') bg-green-100 text-green-800
                                            @elseif($orcamento->status === 'rejeitado') bg-red-100 text-red-800
                                            @elseif($orcamento->status === 'quitado') bg-purple-100 text-purple-800
                                            @endif">
                                            {{ ucfirst($orcamento->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                        R$ {{ number_format($valorPago, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium
                                        @if($saldo > 0) text-red-600 @else text-green-600 @endif">
                                        R$ {{ number_format($saldo, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($percentual, 100) }}%"></div>
                                            </div>
                                            <span class="text-xs text-gray-600">{{ number_format($percentual, 0) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum orçamento encontrado</h3>
                    <p class="mt-1 text-sm text-gray-500">Este cliente ainda não possui orçamentos cadastrados.</p>
                </div>
            @endif
        </div>
        
        <!-- Payments History -->
        @php
            $pagamentos = collect();
            foreach($orcamentos as $orcamento) {
                foreach($orcamento->pagamentos as $pagamento) {
                    $pagamentos->push($pagamento);
                }
            }
            $pagamentos = $pagamentos->sortByDesc('data_pagamento');
        @endphp
        
        @if($pagamentos->count() > 0)
            <div class="bg-white rounded-lg shadow-lg border border-gray-200 mt-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Histórico de Pagamentos</h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orçamento</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Forma de Pagamento</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pagamentos as $pagamento)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $pagamento->data_pagamento->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $pagamento->orcamento->titulo }}</div>
                                            <div class="text-sm text-gray-500">#{{ $pagamento->orcamento->id }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                        R$ {{ number_format($pagamento->valor, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $pagamento->observacoes ?: '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </main>
    
    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-8 no-print">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-sm text-gray-500">
                Extrato gerado em {{ now()->format('d/m/Y H:i') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">
                Este documento apresenta o histórico completo de orçamentos e pagamentos
            </p>
        </div>
    </footer>
</body>
</html>