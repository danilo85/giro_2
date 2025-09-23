@extends('layouts.app')

@section('title', 'Modelos de Propostas - Giro')

@section('content')
<div class="max-w-7xl mx-auto  px-4 py-6">
    <!-- Tags de Navegação Rápida -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('orcamentos.index') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Orçamentos
        </a>
        <a href="{{ route('clientes.index') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Clientes
        </a>
        <a href="{{ route('autores.index') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Autores
        </a>
        <a href="{{ route('pagamentos.index') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            Pagamentos
        </a>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Modelos de Propostas
        </span>
                <a href="{{ route('kanban.index') }}" 
           class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
            </svg>
            Kanban
        </a>
    </div>
    
    <!-- Header -->
    <div class="flex items-center justify-between mb-8 mt-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Modelos de Propostas</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Gerenciamento de modelos de propostas</p>
        </div>

    </div>


    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Total de Modelos</p>
                    <p class="text-2xl font-bold text-white">{{ $modelos->total() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-100">Modelos Ativos</p>
                    <p class="text-2xl font-bold text-white">{{ $modelos->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-100">Mais Usado</p>
                    <p class="text-2xl font-bold text-white">{{ $modelos->sortByDesc('orcamentos_count')->first()->orcamentos_count ?? 0 }} usos</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <!-- Filtro de busca completo -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="flex-1 relative">
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar por cliente, título ou autor..."
                           class="w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    @if(request()->filled('search'))
                        <button type="button" id="clearSearch" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>

            </div>
        </div>
      </div>
    
    <!-- Models Grid -->
    @if($modelos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="models-grid">
            @foreach($modelos as $modelo)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg p-6 transition-all duration-200 overflow-hidden model-card modelo-card flex flex-col" data-model-id="{{ $modelo->id }}">
                    <!-- Model Header -->
                    <div class="">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-14 h-14 rounded-full flex items-center justify-center text-2xl overflow-hidden bg-blue-100 dark:bg-blue-900">
                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white modelo-name">{{ $modelo->nome }}</h3>
                                    @if($modelo->descricao)
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($modelo->descricao, 40) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Badges Row -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @switch($modelo->categoria)
                                    @case('servicos')
                                        bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @break
                                    @case('produtos')
                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @break
                                    @case('consultoria')
                                        bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                        @break
                                    @case('manutencao')
                                        bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200
                                        @break
                                    @default
                                        bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                @endswitch
                            ">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="modelo-category">{{ ucfirst($modelo->categoria) }}</span>
                            </span>
                            
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @switch($modelo->status)
                                    @case('ativo')
                                        bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @break
                                    @case('inativo')
                                        bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                        @break
                                    @case('rascunho')
                                        bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                        @break
                                    @case('arquivado')
                                        bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                        @break
                                    @default
                                        bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                @endswitch
                            ">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ ucfirst($modelo->status) }}
                            </span>
                            
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                {{ $modelo->usos ?? 0 }} usos
                            </span>
                        </div>
                    </div>

                    <!-- Model Details -->
                    <div class="px-6 pb-6">
                        <div class="space-y-4">
                            <!-- Creation Date -->
                            <div class="text-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Criado em</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $modelo->created_at->format('d/m/Y') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $modelo->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Flex grow para empurrar os botões para o rodapé -->
                    <div class="flex-grow"></div>

                    <!-- Actions Footer -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
                        <div class="flex space-x-3">
                            <a href="{{ route('modelos-propostas.show', $modelo) }}" class="p-2 rounded-lg text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20" title="Visualizar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <button onclick="useModel({{ $modelo->id }})" class="p-2 rounded-lg text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-all duration-200 hover:bg-green-50 dark:hover:bg-green-900/20" title="Usar Modelo">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                            <a href="{{ route('modelos-propostas.edit', $modelo) }}" class="p-2 rounded-lg text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="flex space-x-3">
                            <button onclick="duplicateModel({{ $modelo->id }})" class="p-2 rounded-lg text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 transition-all duration-200 hover:bg-purple-50 dark:hover:bg-purple-900/20" title="Duplicar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                            <button onclick="deleteModel({{ $modelo->id }})" class="p-2 rounded-lg text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20" title="Excluir">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <footer class="mt-8">
    <div class="text-center py-6">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            © {{ date('Y') }} Danilo Miguel. Todos os direitos reservados.
        </p>
        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
            Sistema de Gestão Financeira - Desenvolvido com Laravel
        </p>
    </div>
</footer>

        <!-- Paginação -->
        @if($modelos->hasPages())
            <div class="mt-6">
                {{ $modelos->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum modelo encontrado</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando um novo modelo de proposta.</p>
            <div class="mt-6">
                <a href="{{ route('modelos-propostas.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Novo Modelo
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Floating Action Button -->
<div class="fixed bottom-6 right-6 z-50">
    <a href="{{ route('modelos-propostas.create') }}" 
       class="group bg-blue-600 hover:bg-blue-700 text-white rounded-full p-4 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110 flex items-center justify-center"
       title="Novo Modelo">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        <!-- Tooltip -->
        <span class="absolute right-full mr-3 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap">
            Novo Modelo
        </span>
    </a>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[10003] flex items-center justify-center">
    <div class="relative mx-4 sm:mx-auto p-5  w-full sm:w-96 max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-2">Confirmar Exclusão</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tem certeza que deseja excluir este modelo de proposta? Esta ação não pode ser desfeita.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="deleteForm" method="POST" class="inline" onsubmit="return confirmDelete(event)">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="deleteConfirmed" value="false">
                    <button type="submit" 
                            onclick="document.getElementById('deleteConfirmed').value='true'"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Excluir
                    </button>
                </form>
                <button onclick="closeDeleteModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-24 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Duplicate Modal -->
<div id="duplicateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[10003] flex items-center justify-center">
    <div class="relative mx-4 sm:mx-auto p-5 w-full sm:w-96 max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 dark:bg-purple-900">
                <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-2">Confirmar Duplicação</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Deseja criar uma cópia deste modelo de proposta? Uma nova versão será criada com o mesmo conteúdo.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <form id="duplicateForm" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 bg-purple-600 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-300">
                        Duplicar
                    </button>
                </form>
                <button onclick="closeDuplicateModal()" 
                        class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-24 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Search toggle functionality
function toggleSearch() {
    const searchContainer = document.getElementById('search-container');
    const searchToggle = document.getElementById('search-toggle');
    
    if (searchContainer.classList.contains('hidden')) {
        searchContainer.classList.remove('hidden');
        searchToggle.querySelector('svg').style.transform = 'rotate(180deg)';
    } else {
        searchContainer.classList.add('hidden');
        searchToggle.querySelector('svg').style.transform = 'rotate(0deg)';
    }
}

// Close search
const closeSearchBtn = document.getElementById('close-search');
if (closeSearchBtn) {
    closeSearchBtn.addEventListener('click', function() {
        const searchContainer = document.getElementById('search-container');
        const searchToggle = document.getElementById('search-toggle');
        if (searchContainer) {
            searchContainer.classList.add('hidden');
        }
        if (searchToggle) {
            const svg = searchToggle.querySelector('svg');
            if (svg) {
                svg.style.transform = 'rotate(0deg)';
            }
        }
    });
}

// Model actions
function useModel(id) {
    // Redirect to create budget with model
    window.location.href = `/orcamentos/create?modelo_id=${id}`;
}

function duplicateModel(id) {
    // Set the form action for duplicate
    const duplicateForm = document.getElementById('duplicateForm');
    duplicateForm.action = `/modelos-propostas/${id}/duplicate`;
    
    // Show the duplicate modal
    document.getElementById('duplicateModal').classList.remove('hidden');
}

function deleteModel(id) {
    // Prevent any console execution by adding validation
    if (!id || typeof id !== 'number' && typeof id !== 'string') {
        console.error('ID inválido para exclusão');
        return false;
    }
    
    // Set the form action for delete
    const deleteForm = document.getElementById('deleteForm');
    if (!deleteForm) {
        console.error('Formulário de exclusão não encontrado');
        return false;
    }
    
    deleteForm.action = `/modelos-propostas/${id}`;
    
    // Show the delete modal
    const deleteModal = document.getElementById('deleteModal');
    if (!deleteModal) {
        console.error('Modal de exclusão não encontrado');
        return false;
    }
    
    deleteModal.classList.remove('hidden');
    
    // Prevent default action and force modal confirmation
    return false;
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    // Reset confirmation flag when modal is closed
    document.getElementById('deleteConfirmed').value = 'false';
}

function confirmDelete(event) {
    // Check if deletion was confirmed through modal
    const deleteConfirmed = document.getElementById('deleteConfirmed');
    
    if (!deleteConfirmed || deleteConfirmed.value !== 'true') {
        event.preventDefault();
        console.warn('Exclusão deve ser confirmada através do modal');
        alert('Por favor, use o botão de exclusão para confirmar a ação.');
        return false;
    }
    
    // Reset flag after successful confirmation
    deleteConfirmed.value = 'false';
    return true;
}

function closeDuplicateModal() {
    document.getElementById('duplicateModal').classList.add('hidden');
}

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    const deleteModal = document.getElementById('deleteModal');
    const duplicateModal = document.getElementById('duplicateModal');
    
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
    if (event.target === duplicateModal) {
        closeDuplicateModal();
    }
});

// Close modals with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeDeleteModal();
        closeDuplicateModal();
    }
});
</script>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all elements with null checks
        const searchToggle = document.getElementById('search-toggle');
        const searchContainer = document.getElementById('search-container');
        const searchInput = document.getElementById('modelo-search');
        const clearSearch = document.getElementById('clear-search');
        const closeSearch = document.getElementById('close-search');
        
        // Toggle search container
        if (searchToggle && searchContainer) {
            searchToggle.addEventListener('click', function() {
                searchContainer.classList.toggle('hidden');
                if (!searchContainer.classList.contains('hidden') && searchInput) {
                    searchInput.focus();
                }
            });
        }
        
        // Close search
        if (closeSearch && searchContainer) {
            closeSearch.addEventListener('click', function() {
                searchContainer.classList.add('hidden');
                if (searchInput) {
                    searchInput.value = '';
                }
                if (clearSearch) {
                    clearSearch.classList.add('hidden');
                }
            });
        }
        
        // Clear search
        if (clearSearch && searchInput) {
            clearSearch.addEventListener('click', function() {
                searchInput.value = '';
                clearSearch.classList.add('hidden');
                searchInput.focus();
            });
        }
        
        // Show/hide clear button
        if (searchInput && clearSearch) {
            searchInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    clearSearch.classList.remove('hidden');
                } else {
                    clearSearch.classList.add('hidden');
                }
            });
        }
        
        // Search functionality - versão melhorada
        if (searchInput) {
            let searchTimeout;
            
            function filterModels() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const modelCards = document.querySelectorAll('.modelo-card');
                let visibleCount = 0;
                
                if (modelCards.length > 0) {
                    modelCards.forEach(card => {
                        const modelName = card.querySelector('.modelo-name');
                        const modelCategory = card.querySelector('.modelo-category');
                        const modelDescription = card.querySelector('.model-description');
                        
                        if (modelName && modelCategory) {
                            const nameText = modelName.textContent.toLowerCase();
                            const categoryText = modelCategory.textContent.toLowerCase();
                            const descriptionText = modelDescription ? modelDescription.textContent.toLowerCase() : '';
                            
                            const isVisible = nameText.includes(searchTerm) || 
                                            categoryText.includes(searchTerm) || 
                                            descriptionText.includes(searchTerm);
                            
                            if (isVisible) {
                                card.style.display = '';
                                visibleCount++;
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                }
                
                // Mostrar/ocultar botão de limpar
                const clearButton = document.getElementById('clearSearch');
                if (clearButton) {
                    if (searchTerm) {
                        clearButton.classList.remove('hidden');
                    } else {
                        clearButton.classList.add('hidden');
                    }
                }
            }
            
            // Busca com debounce para melhor performance
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(filterModels, 300);
            });
            
            // Busca instantânea ao pressionar Enter
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    clearTimeout(searchTimeout);
                    filterModels();
                }
            });
            
            // Inicializar busca se houver valor no campo
            if (searchInput.value.trim()) {
                filterModels();
            }
        }
    });
</script>
@endpush