@extends('layouts.app')

@section('title', 'Autores')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Tags de Navegação Rápida -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 012-2h6l2 2h6a2 2 0 012 2v1"></path>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('orcamentos.index') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Orçamentos
        </a>
        <a href="{{ route('clientes.index') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Clientes
        </a>
        <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-blue-600 text-white">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Autores
        </span>
        <a href="{{ route('pagamentos.index') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            Pagamentos
        </a>
        <a href="{{ route('modelos-propostas.index') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Modelos de Propostas
        </a>
    </div>

    <!-- Header -->

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Autores</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Gerencie os autores dos seus orçamentos</p>
        </div>
 
    </div>
    <!-- Cards de Resumo -->
    <div id="summary-cards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-lg shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total de Autores</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $autores->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 rounded-lg shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Orçamentos Ativos</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $autores->sum('orcamentos_count') ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 dark:from-yellow-600 dark:to-yellow-700 rounded-lg shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Valor Total</p>
                    <p class="text-2xl font-bold text-white mt-1">R$ {{ number_format($autores->sum('valor_total_orcamentos') ?? 0, 2, ',', '.') }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 rounded-lg shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Com WhatsApp</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $autores->whereNotNull('whatsapp')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.787"/>
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
    <!-- Resultados -->
    @if($autores->count() > 0)
        <div class="mb-4">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Mostrando {{ $autores->count() }} de {{ $autores->total() }} resultados
                @if(request()->hasAny(['search', 'has_contact']))
                    para os filtros aplicados
                @endif
            </p>
        </div>
    @endif

    <!-- Lista de Autores -->
    @if($autores->count() > 0)
        <div id="autores-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($autores as $autor)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200 flex flex-col h-full">
                    <!-- Header do Card -->
                    <div class="p-6 pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                @if($autor->avatar)
                                    <img src="{{ Storage::url($autor->avatar) }}" 
                                         alt="{{ $autor->nome }}" 
                                         class="w-12 h-12 rounded-full object-cover">
                                @else
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
                                        <span class="text-white font-semibold text-lg">
                                            {{ strtoupper(substr($autor->nome, 0, 1)) }}
                                        </span>
                                    </div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white break-words">{{ $autor->nome }}</h3>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Badges de Status -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            @if($autor->orcamentos_count > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $autor->orcamentos_count }} Orçamento{{ $autor->orcamentos_count !== 1 ? 's' : '' }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Sem Orçamentos
                                </span>
                            @endif
                            
                            @if($autor->created_at->diffInDays(now()) <= 30)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Novo
                                </span>
                            @endif
                            

                        </div>
                    </div>
                    <!-- Detalhes do Autor -->
                    <div class="px-6 pb-6">
                        <div class="space-y-3">
                            <!-- Informações de Contato -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <a href="mailto:{{ $autor->email }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 truncate transition-colors duration-200">{{ $autor->email }}</a>
                                    </div>
                                    @if($autor->telefone)
                                        <div class="flex items-center text-sm">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            @if($autor->whatsapp)
                                                <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $autor->telefone) }}" target="_blank" class="text-gray-900 dark:text-white hover:text-green-600 dark:hover:text-green-400 transition-colors duration-200 cursor-pointer">{{ $autor->telefone }}</a>
                                            @else
                                                <span class="text-gray-900 dark:text-white">{{ $autor->telefone }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Valor Total dos Orçamentos -->
                            @if($autor->orcamentos_count > 0)
                                <div class="text-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Valor Total em Orçamentos</p>
                                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                        @if(isset($autor->valor_total_orcamentos) && $autor->valor_total_orcamentos > 0)
                                            R$ {{ number_format($autor->valor_total_orcamentos, 2, ',', '.') }}
                                        @else
                                            R$ 0,00
                                        @endif
                                    </p>
                                </div>
                            @endif
                            
                            <!-- Data de Cadastro -->
                            <div class="text-center">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Cadastrado em {{ $autor->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Flex grow para empurrar os botões para o rodapé -->
                    <div class="flex-grow"></div>

                    <!-- Actions Footer -->
                    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
                        <div class="flex space-x-3">
                            <a href="{{ route('autores.show', $autor) }}" 
                               class="p-2 rounded-lg text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20" 
                               title="Visualizar Autor">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('autores.edit', $autor) }}" 
                               class="p-2 rounded-lg text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                               title="Editar Autor">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="flex space-x-3">
                            <form method="POST" action="{{ route('autores.destroy', $autor) }}" class="inline" id="delete-form-{{ $autor->id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="openDeleteModal({{ $autor->id }}, '{{ $autor->nome }}')"
                                        class="p-2 rounded-lg text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20" 
                                        title="Excluir Autor">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        @if($autores->hasPages())
            <div class="mt-8">
                {{ $autores->links() }}
            </div>
        @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="mx-auto h-24 w-24 text-gray-400 mb-6">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-full h-full">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    @if(request()->hasAny(['search', 'has_contact']))
                        Nenhum autor encontrado
                    @else
                        Nenhum autor cadastrado
                    @endif
                </h3>
                <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-sm mx-auto">
                    @if(request()->hasAny(['search', 'has_contact']))
                        Não encontramos autores que correspondam aos seus critérios de busca. Tente ajustar os filtros.
                    @else
                        Comece adicionando seu primeiro autor para gerenciar orçamentos e projetos.
                    @endif
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    @if(request()->hasAny(['search', 'has_contact']))
                        <a href="{{ route('autores.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Limpar Filtros
                        </a>
                    @endif
                    <a href="{{ route('autores.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Novo Autor
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Botão Flutuante -->
<div class="fixed bottom-6 right-6 z-50">
    <a href="{{ route('autores.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white w-14 h-14 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center"
       title="Novo Autor">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
    </a>
</div>

<!-- Modal de Exclusão -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">Confirmar Exclusão</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tem certeza que deseja excluir este autor?
                    <br>
                    <span id="deleteAutorName" class="font-semibold text-gray-700 dark:text-gray-300"></span>
                    <br>
                    Esta ação não pode ser desfeita.
                </p>
            </div>
            <div class="flex justify-center space-x-4 px-4 py-3">
                <button id="cancelDelete" type="button" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    Cancelar
                </button>
                <button id="confirmDelete" type="button" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Excluir
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let searchTimeout;
    let isLoading = false;
    
    const searchInput = document.getElementById('search');
    const clearSearch = document.getElementById('clearSearch');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(() => {
                const searchValue = this.value;
                performSearch(searchValue);
            }, 500);
        });
    }
    
    function performSearch(searchValue) {
        if (isLoading) return;
        
        isLoading = true;
        showLoadingState();
        
        const url = '/api/budget/autores/search';
        const params = new URLSearchParams();
        
        if (searchValue && searchValue.length >= 2) {
            params.append('search', searchValue);
        }
        
        fetch(`${url}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateAutoresList(data.data);
                updatePagination(data.pagination);
            } else {
                console.error('Erro na busca:', data.message);
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
        })
        .finally(() => {
            isLoading = false;
            hideLoadingState();
        });
    }
    
    function showLoadingState() {
        const container = document.querySelector('#autores-list');
        if (container) {
            container.style.opacity = '0.6';
            container.style.pointerEvents = 'none';
        }
    }
    
    function hideLoadingState() {
        const container = document.querySelector('#autores-list');
        if (container) {
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        }
    }
    
    function updateAutoresList(autores) {
        const container = document.querySelector('#autores-list');
        if (!container) return;
        
        if (autores.length === 0) {
            container.innerHTML = `
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum autor encontrado</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tente ajustar os filtros de busca.</p>
                    </div>
                </div>
            `;
            return;
        }
        
        let html = '';
        autores.forEach(autor => {
            const avatarHtml = autor.avatar 
                ? `<img src="/storage/${autor.avatar}" alt="${autor.nome}" class="w-12 h-12 rounded-full object-cover">`
                : `<div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
                     <span class="text-white font-semibold text-lg">${autor.nome.charAt(0).toUpperCase()}</span>
                   </div>`;
            
            const valorFormatado = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(autor.valor_total_orcamentos || 0);
            const isNovo = new Date(autor.created_at).getTime() > (Date.now() - 30 * 24 * 60 * 60 * 1000);
            
            html += `
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200 flex flex-col h-full">
                    <!-- Header do Card -->
                    <div class="p-6 pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                ${avatarHtml}
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white break-words">${autor.nome}</h3>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Badges de Status -->
                        <div class="flex flex-wrap gap-2 mb-4">
                            ${autor.orcamentos_count > 0 
                                ? `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                     <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                         <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                     </svg>
                                     ${autor.orcamentos_count} Orçamento${autor.orcamentos_count !== 1 ? 's' : ''}
                                   </span>`
                                : `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                     <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                     </svg>
                                     Sem Orçamentos
                                   </span>`
                            }
                            ${isNovo 
                                ? `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                     <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                     </svg>
                                     Novo
                                   </span>`
                                : ''
                            }
                        </div>
                    </div>
                    
                    <!-- Detalhes do Autor -->
                    <div class="px-6 pb-6">
                        <div class="space-y-3">
                            <!-- Informações de Contato -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="space-y-2">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        <a href="mailto:${autor.email}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 truncate transition-colors duration-200">${autor.email}</a>
                                    </div>
                                    ${autor.telefone 
                                        ? `<div class="flex items-center text-sm">
                                             <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                             </svg>
                                             ${autor.whatsapp 
                                                 ? `<a href="https://wa.me/55${autor.telefone.replace(/[^0-9]/g, '')}" target="_blank" class="text-gray-900 dark:text-white hover:text-green-600 dark:hover:text-green-400 transition-colors duration-200 cursor-pointer">${autor.telefone}</a>`
                                                 : `<span class="text-gray-900 dark:text-white">${autor.telefone}</span>`
                                             }
                                           </div>`
                                        : ''
                                    }
                                </div>
                            </div>
                            
                            ${autor.orcamentos_count > 0 
                                ? `<div class="text-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                     <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Valor Total em Orçamentos</p>
                                     <p class="text-2xl font-bold text-green-600 dark:text-green-400">${valorFormatado}</p>
                                   </div>`
                                : ''
                            }
                            
                            <div class="text-center">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Cadastrado em ${new Date(autor.created_at).toLocaleDateString('pt-BR')}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Flex grow para empurrar os botões para o rodapé -->
                    <div class="flex-grow"></div>
                    
                    <!-- Actions Footer -->
                    <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
                        <div class="flex space-x-3">
                            <a href="/autores/${autor.id}" class="p-2 rounded-lg text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20" title="Visualizar Autor">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                            <a href="/autores/${autor.id}/edit" class="p-2 rounded-lg text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700" title="Editar Autor">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                        <div class="flex space-x-3">
                            <button onclick="openDeleteModal(${autor.id}, '${autor.nome}')" class="p-2 rounded-lg text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20" title="Excluir Autor">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = html;
    }
    
    function updatePagination(pagination) {
        // Remover paginação existente se houver busca ativa
        const searchValue = document.getElementById('search').value;
        const paginationContainer = document.querySelector('.mt-6');
        
        if (searchValue && searchValue.length >= 2) {
            if (paginationContainer) {
                paginationContainer.style.display = 'none';
            }
        } else {
            if (paginationContainer) {
                paginationContainer.style.display = 'block';
            }
        }
    }
    
    // Limpar filtro
    function clearFilter() {
        document.getElementById('search').value = '';
        performSearch('');
        
        // Restaurar paginação
        const paginationContainer = document.querySelector('.mt-6');
        if (paginationContainer) {
            paginationContainer.style.display = 'block';
        }
    }
    
    if (clearSearch) {
        clearSearch.addEventListener('click', function() {
            clearFilter();
            searchInput.focus();
        });
    }
});

// Funções para gerenciar o modal de exclusão
let autorToDelete = null;

function openDeleteModal(autorId, autorNome) {
    autorToDelete = autorId;
    document.getElementById('deleteAutorName').textContent = autorNome;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    autorToDelete = null;
    document.getElementById('deleteModal').classList.add('hidden');
}

function confirmDelete() {
    if (autorToDelete) {
        const form = document.getElementById(`delete-form-${autorToDelete}`);
        if (form) {
            form.submit();
        } else {
            console.error('Formulário de exclusão não encontrado');
            closeDeleteModal();
        }
    } else {
        closeDeleteModal();
    }
}

// Event listeners para o modal
document.getElementById('cancelDelete').addEventListener('click', closeDeleteModal);
document.getElementById('confirmDelete').addEventListener('click', confirmDelete);

// Fechar modal ao clicar fora dele
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
        closeDeleteModal();
    }
});
</script>
@endpush