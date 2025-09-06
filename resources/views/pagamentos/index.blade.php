@extends('layouts.app')

@section('title', 'Pagamentos')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home w-4 h-4 mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Pagamentos</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pagamentos</h1>
            <p class="mt-1 text-sm text-gray-600">Gerencie todos os pagamentos recebidos</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('pagamentos.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                <i class="fas fa-plus w-4 h-4 mr-2"></i>
                Novo Pagamento
            </a>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total de Pagamentos -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-blue-600 text-sm"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Pagamentos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalPagamentos ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Valor Total Recebido -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-green-600 text-sm"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Valor Total</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $valorTotal ?? 'R$ 0,00' }}</p>
                </div>
            </div>
        </div>

        <!-- Pagamentos Hoje -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-day text-yellow-600 text-sm"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Hoje</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $pagamentosHoje ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Média Mensal -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-purple-600 text-sm"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Média Mensal</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $mediaMensal ?? 'R$ 0,00' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Filtros</h3>
                <button type="button" onclick="toggleFilters()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-chevron-down" id="filter-icon"></i>
                </button>
            </div>
        </div>
        
        <div id="filter-content" class="px-6 py-4">
            <!-- Busca Rápida -->
            <div class="mb-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="busca" placeholder="Buscar por cliente, orçamento ou valor..." value="{{ request('busca') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
            </div>
            
            <form method="GET" action="{{ route('pagamentos.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="orcamento_id" class="block text-sm font-medium text-gray-700 mb-1">Orçamento</label>
                    <select name="orcamento_id" id="orcamento_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Todos os orçamentos</option>
                        @foreach($orcamentos as $orcamento)
                            <option value="{{ $orcamento->id }}" {{ request('orcamento_id') == $orcamento->id ? 'selected' : '' }}>
                                #{{ $orcamento->numero }} - {{ $orcamento->cliente->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="forma_pagamento" class="block text-sm font-medium text-gray-700 mb-1">Forma de Pagamento</label>
                    <select name="forma_pagamento" id="forma_pagamento" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">Todas as formas</option>
                        <option value="dinheiro" {{ request('forma_pagamento') == 'dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                        <option value="pix" {{ request('forma_pagamento') == 'pix' ? 'selected' : '' }}>PIX</option>
                        <option value="cartao_credito" {{ request('forma_pagamento') == 'cartao_credito' ? 'selected' : '' }}>Cartão de Crédito</option>
                        <option value="cartao_debito" {{ request('forma_pagamento') == 'cartao_debito' ? 'selected' : '' }}>Cartão de Débito</option>
                        <option value="transferencia" {{ request('forma_pagamento') == 'transferencia' ? 'selected' : '' }}>Transferência</option>
                        <option value="boleto" {{ request('forma_pagamento') == 'boleto' ? 'selected' : '' }}>Boleto</option>
                    </select>
                </div>
                
                <div>
                    <label for="data_inicio" class="block text-sm font-medium text-gray-700 mb-1">Data Início</label>
                    <input type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                
                <div>
                    <label for="data_fim" class="block text-sm font-medium text-gray-700 mb-1">Data Fim</label>
                    <input type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>
                
                <div class="md:col-span-4 flex flex-wrap gap-2 pt-2">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-search w-4 h-4 mr-2"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('pagamentos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-times w-4 h-4 mr-2"></i>
                        Limpar
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Resultados -->
    @if(request()->hasAny(['orcamento_id', 'forma_pagamento', 'data_inicio', 'data_fim', 'busca']))
        <div class="mb-4">
            <p class="text-sm text-gray-600">
                <span class="font-medium">{{ $pagamentos->total() }}</span> 
                {{ $pagamentos->total() === 1 ? 'pagamento encontrado' : 'pagamentos encontrados' }}
                @if(request()->hasAny(['orcamento_id', 'forma_pagamento', 'data_inicio', 'data_fim', 'busca']))
                    com os filtros aplicados
                @endif
            </p>
        </div>
    @endif

    <!-- Lista de Pagamentos -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if($pagamentos->count() > 0)
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orçamento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Forma</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pagamentos as $pagamento)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">#{{ $pagamento->orcamento->numero }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $pagamento->orcamento->cliente->nome }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-green-600">{{ $pagamento->valor_formatted }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $pagamento->data_pagamento_formatted }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $pagamento->forma_pagamento_formatted }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-1">
                                        <a href="{{ route('pagamentos.show', $pagamento) }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors duration-200" title="Visualizar">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('pagamentos.edit', $pagamento) }}" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 hover:text-yellow-700 hover:bg-yellow-50 rounded-lg transition-colors duration-200" title="Editar">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <form action="{{ route('pagamentos.destroy', $pagamento) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este pagamento?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors duration-200" title="Excluir">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Mobile Cards -->
            <div class="md:hidden divide-y divide-gray-200">
                @foreach($pagamentos as $pagamento)
                    <div class="p-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="text-sm font-medium text-gray-900">#{{ $pagamento->orcamento->numero }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $pagamento->forma_pagamento_formatted }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $pagamento->orcamento->cliente->nome }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-green-600">{{ $pagamento->valor_formatted }}</p>
                                <p class="text-xs text-gray-500">{{ $pagamento->data_pagamento_formatted }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div class="flex items-center space-x-1">
                                <a href="{{ route('pagamentos.show', $pagamento) }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('pagamentos.edit', $pagamento) }}" class="inline-flex items-center justify-center w-8 h-8 text-yellow-600 hover:text-yellow-700 hover:bg-yellow-50 rounded-lg transition-colors duration-200">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('pagamentos.destroy', $pagamento) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este pagamento?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginação -->
            @if($pagamentos->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    <!-- Mobile Pagination -->
                    <div class="flex items-center justify-between md:hidden">
                        <div class="flex-1 flex justify-between">
                            @if($pagamentos->onFirstPage())
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-lg">
                                    Anterior
                                </span>
                            @else
                                <a href="{{ $pagamentos->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    Anterior
                                </a>
                            @endif
                            
                            @if($pagamentos->hasMorePages())
                                <a href="{{ $pagamentos->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                    Próximo
                                </a>
                            @else
                                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 cursor-default rounded-lg">
                                    Próximo
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Desktop Pagination -->
                    <div class="hidden md:flex md:items-center md:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Mostrando
                                <span class="font-medium">{{ $pagamentos->firstItem() }}</span>
                                até
                                <span class="font-medium">{{ $pagamentos->lastItem() }}</span>
                                de
                                <span class="font-medium">{{ $pagamentos->total() }}</span>
                                resultados
                            </p>
                        </div>
                        <div>
                            {{ $pagamentos->withQueryString()->links() }}
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-money-bill-wave text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">
                    @if(request()->hasAny(['orcamento_id', 'forma_pagamento', 'data_inicio', 'data_fim', 'busca']))
                        Nenhum pagamento encontrado
                    @else
                        Nenhum pagamento cadastrado
                    @endif
                </h3>
                <p class="text-gray-500 mb-6 max-w-sm mx-auto">
                    @if(request()->hasAny(['orcamento_id', 'forma_pagamento', 'data_inicio', 'data_fim', 'busca']))
                        Não encontramos pagamentos que correspondam aos filtros aplicados. Tente ajustar os critérios de busca.
                    @else
                        Comece registrando o primeiro pagamento do seu sistema.
                    @endif
                </p>
                @if(request()->hasAny(['orcamento_id', 'forma_pagamento', 'data_inicio', 'data_fim', 'busca']))
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('pagamentos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-times w-4 h-4 mr-2"></i>
                            Limpar Filtros
                        </a>
                        <a href="{{ route('pagamentos.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-plus w-4 h-4 mr-2"></i>
                            Novo Pagamento
                        </a>
                    </div>
                @else
                    <a href="{{ route('pagamentos.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <i class="fas fa-plus w-4 h-4 mr-2"></i>
                        Registrar Primeiro Pagamento
                    </a>
                @endif
            </div>
        @endif
    </div>
    
    <script>
        function toggleFilters() {
            const content = document.getElementById('filter-content');
            const icon = document.getElementById('filter-icon');
            
            if (content.style.display === 'none') {
                content.style.display = 'block';
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            } else {
                content.style.display = 'none';
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
        }
    </script>
</div>
@endsection