@extends('layouts.app')

@section('title', 'Gestão de Etapas - Kanban')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="etapasManager()">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900  dark:text-white">Gestão de Etapas</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Configure as etapas do seu fluxo de trabalho</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('kanban.index') }}" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors" title="Voltar ao Kanban">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total de Etapas -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-blue-100">Total de Etapas</p>
                    <p class="text-3xl font-bold text-white">{{ $etapas->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Etapas Ativas -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-100">Etapas Ativas</p>
                    <p class="text-3xl font-bold text-white summary-ativas">{{ $etapas->where('ativo', true)->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Etapas Inativas -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-100">Etapas Inativas</p>
                    <p class="text-3xl font-bold text-white summary-inativas">{{ $etapas->where('ativo', false)->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Última Atualização -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-purple-100">Última Atualização</p>
                    <p class="text-lg font-bold text-white">
                        @if($etapas->count() > 0)
                            {{ $etapas->max('updated_at')->format('d/m/Y') }}
                        @else
                            Nenhuma
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Etapas -->
    <div class="space-y-4">
        @if($etapas->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhuma etapa encontrada</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando sua primeira etapa.</p>
            </div>
        @else
            <!-- Grid de Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($etapas as $etapa)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200 flex flex-col h-full">
                        <!-- Header do Card -->
                        <div class="p-6 pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 mr-3" style="background-color: {{ $etapa->cor }}20; border: 2px solid {{ $etapa->cor }}">
                                        <div class="w-4 h-4 rounded-full" style="background-color: {{ $etapa->cor }}"></div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate" title="{{ $etapa->nome }}">
                                            {{ $etapa->nome }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status and Order Badges -->
                            <div class="flex justify-center items-center gap-2 mb-4">
                                <!-- Order Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    Ordem: {{ $etapa->ordem }}
                                </span>
                                
                                <!-- Status Badge -->
                                 <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $etapa->ativa ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}" 
                                       :class="etapaStatus_{{ $etapa->id }} ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'">
                                     <span x-text="etapaStatus_{{ $etapa->id }} ? 'Ativo' : 'Inativo'">{{ $etapa->ativa ? 'Ativo' : 'Inativo' }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Detalhes da Etapa -->
                        <div class="px-6 pb-6 flex-1">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Cor da Etapa</p>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600" style="background-color: {{ $etapa->cor }}"></div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $etapa->cor }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Projetos</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $etapa->projetos->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions Footer -->
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 rounded-b-lg">
                            <div class="flex justify-center space-x-3">
                                <button @click="editEtapa({{ $etapa->toJson() }})" 
                                        class="p-2 rounded-lg text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700" 
                                        title="Editar Etapa">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button @click="toggleEtapaStatus({{ $etapa->id }})" 
                                        :disabled="loadingStatus_{{ $etapa->id }}"
                                        class="p-2 rounded-lg transition-all duration-200 disabled:opacity-50" 
                                        :class="etapaStatus_{{ $etapa->id }} ? 'text-orange-600 hover:text-orange-800 dark:text-orange-400 dark:hover:text-orange-300 hover:bg-orange-50 dark:hover:bg-orange-900/20' : 'text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20'"
                                        :title="etapaStatus_{{ $etapa->id }} ? 'Desativar Etapa' : 'Ativar Etapa'">
                                    <template x-if="!loadingStatus_{{ $etapa->id }}">
                                        <div>
                                            <svg x-show="etapaStatus_{{ $etapa->id }}" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 12M6 6l12 12" />
                                            </svg>
                                            <svg x-show="!etapaStatus_{{ $etapa->id }}" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </template>
                                    <template x-if="loadingStatus_{{ $etapa->id }}">
                                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </template>
                                </button>
                                @if($etapa->projetos->count() === 0)
                                <button @click="openDeleteModal({{ $etapa->id }})" 
                                        class="p-2 rounded-lg text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20" 
                                        title="Excluir Etapa">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                                @else
                                <span class="p-2 rounded-lg text-gray-400 cursor-not-allowed" title="Não é possível excluir etapa com projetos">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Modal para Criar/Editar Etapa -->
    <div x-show="showModal" x-cloak class="fixed inset-0 z-[10003] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form @submit.prevent="submitForm()">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    <span x-text="isEditing ? 'Editar Etapa' : 'Nova Etapa'"></span>
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label for="nome" class="block text-sm font-medium text-gray-700">Nome da Etapa</label>
                                        <input 
                                            type="text" 
                                            id="nome" 
                                            x-model="form.nome" 
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            placeholder="Ex: Em Desenvolvimento"
                                            required
                                        >
                                    </div>
                                    
                                    <div>
                                        <label for="cor" class="block text-sm font-medium text-gray-700">Cor</label>
                                        <div class="mt-1 flex items-center space-x-3">
                                            <input 
                                                type="color" 
                                                id="cor" 
                                                x-model="form.cor" 
                                                class="h-10 w-16 border border-gray-300 rounded-md cursor-pointer"
                                                required
                                            >
                                            <input 
                                                type="text" 
                                                x-model="form.cor" 
                                                class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm font-mono"
                                                placeholder="#3B82F6"
                                                pattern="^#[0-9A-Fa-f]{6}$"
                                                required
                                            >
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="ordem" class="block text-sm font-medium text-gray-700">Ordem</label>
                                        <input 
                                            type="number" 
                                            id="ordem" 
                                            x-model="form.ordem" 
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                            min="1"
                                            required
                                        >
                                        <p class="mt-1 text-sm text-gray-500">A ordem determina a posição da etapa no board</p>
                                    </div>
                                    
                                    <div x-show="isEditing">
                                        <label class="flex items-center">
                                            <input 
                                                type="checkbox" 
                                                x-model="form.ativa" 
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                            >
                                            <span class="ml-2 text-sm text-gray-700">Etapa ativa</span>
                                        </label>
                                        <p class="mt-1 text-sm text-gray-500">Etapas inativas não aparecem no board</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            type="submit" 
                            :disabled="loading"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                        >
                            <span x-show="!loading" x-text="isEditing ? 'Atualizar' : 'Criar'"></span>
                            <span x-show="loading">Salvando...</span>
                        </button>
                        <button 
                            type="button" 
                            @click="closeModal()" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[10003] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Confirmar Exclusão
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Tem certeza que deseja excluir esta etapa? Esta ação não pode ser desfeita.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        type="button" 
                        @click="confirmDelete()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Excluir
                    </button>
                    <button 
                        type="button" 
                        @click="closeDeleteModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Botão Flutuante -->
    <div class="fixed bottom-6 right-6 z-50">
        <button @click="openCreateModal()" 
                class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-105">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </button>
    </div>
</div>

<!-- Footer -->
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

@push('scripts')
<script>
function etapasManager() {
    return {
        showModal: false,
        isEditing: false,
        loading: false,
        editingId: null,
        form: {
            nome: '',
            cor: '#3B82F6',
            ordem: 1,
            ativa: true
        },
        
        // Inicializar status das etapas
        @foreach($etapas as $etapa)
        etapaStatus_{{ $etapa->id }}: {{ $etapa->ativa ? 'true' : 'false' }},
        loadingStatus_{{ $etapa->id }}: false,
        @endforeach
        
        openCreateModal() {
            this.isEditing = false;
            this.editingId = null;
            this.form = {
                nome: '',
                cor: '#3B82F6',
                ordem: this.getNextOrder(),
                ativa: true
            };
            this.showModal = true;
        },
        
        editEtapa(etapa) {
            this.isEditing = true;
            this.editingId = etapa.id;
            this.form = {
                nome: etapa.nome,
                cor: etapa.cor,
                ordem: etapa.ordem,
                ativa: etapa.ativa
            };
            this.showModal = true;
        },
        
        closeModal() {
            this.showModal = false;
            this.isEditing = false;
            this.editingId = null;
            this.form = {
                nome: '',
                cor: '#3B82F6',
                ordem: 1,
                ativa: true
            };
        },
        
        async submitForm() {
            this.loading = true;
            
            try {
                const url = this.isEditing 
                    ? `{{ route('kanban.api.etapas.update', '') }}/${this.editingId}`
                    : '{{ route("kanban.api.etapas.store") }}';
                    
                const method = this.isEditing ? 'PUT' : 'POST';
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.form)
                });
                
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.error || 'Erro ao salvar etapa');
                }
                
                this.showNotification(data.message, 'success');
                this.closeModal();
                
                // Recarregar a página para mostrar as mudanças
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                
            } catch (error) {
                console.error('Erro ao salvar etapa:', error);
                this.showNotification(error.message, 'error');
            } finally {
                this.loading = false;
            }
        },
        
        showDeleteModal: false,
        etapaToDelete: null,
        
        openDeleteModal(etapaId) {
            this.etapaToDelete = etapaId;
            this.showDeleteModal = true;
        },
        
        closeDeleteModal() {
            this.showDeleteModal = false;
            this.etapaToDelete = null;
        },
        
        async toggleEtapaStatus(etapaId) {
            // Ativar loading para esta etapa específica
            this[`loadingStatus_${etapaId}`] = true;
            
            // Obter o status atual
            const statusAtual = this[`etapaStatus_${etapaId}`];
            const novoStatus = !statusAtual;
            
            try {
                const response = await fetch(`/kanban/etapas/${etapaId}/toggle-status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ ativo: novoStatus })
                });

                if (response.ok) {
                    const result = await response.json();
                    
                    // Atualizar o status localmente
                    this[`etapaStatus_${etapaId}`] = novoStatus;
                    
                    // Mostrar notificação de sucesso
                    this.showNotification(result.message || 'Status alterado com sucesso!', 'success');
                    
                    // Atualizar os contadores nos cards de resumo
                    this.updateSummaryCards();
                    
                } else {
                    const errorData = await response.json();
                    throw new Error(errorData.error || 'Erro ao alterar status da etapa');
                }
            } catch (error) {
                console.error('Erro:', error);
                this.showNotification(error.message, 'error');
            } finally {
                // Desativar loading
                this[`loadingStatus_${etapaId}`] = false;
            }
        },
        
        updateSummaryCards() {
            // Contar etapas ativas e inativas
            let ativas = 0;
            let inativas = 0;
            
            @foreach($etapas as $etapa)
            if (this.etapaStatus_{{ $etapa->id }}) {
                ativas++;
            } else {
                inativas++;
            }
            @endforeach
            
            // Atualizar os números nos cards (se existirem elementos)
            const ativasElement = document.querySelector('.summary-ativas');
            const inativasElement = document.querySelector('.summary-inativas');
            
            if (ativasElement) ativasElement.textContent = ativas;
            if (inativasElement) inativasElement.textContent = inativas;
        },
        
        async confirmDelete() {
            if (!this.etapaToDelete) return;
            
            try {
                const response = await fetch(`{{ route('kanban.api.etapas.destroy', '') }}/${this.etapaToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.error || 'Erro ao excluir etapa');
                }
                
                this.showNotification(data.message, 'success');
                this.closeDeleteModal();
                
                // Recarregar a página para mostrar as mudanças
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
                
            } catch (error) {
                console.error('Erro ao excluir etapa:', error);
                this.showNotification(error.message, 'error');
                this.closeDeleteModal();
            }
        },
        
        getNextOrder() {
            // Calcular a próxima ordem baseada nas etapas existentes
            const etapas = @json($etapas);
            if (etapas.length === 0) return 1;
            return Math.max(...etapas.map(e => e.ordem)) + 1;
        },
        
        showNotification(message, type = 'info') {
            const alertClass = type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white';
            
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${alertClass}`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    }
}
</script>
@endpush
@endsection