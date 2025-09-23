@extends('layouts.app')

@section('title', 'Visualizar Modelo - Giro')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $modelo->nome }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Detalhes do modelo de proposta</p>
        </div>
        
        <div class="flex flex-row space-x-3 mt-4 sm:mt-0">
            <a href="{{ route('modelos-propostas.edit', $modelo->id) }}" 
               class="inline-flex items-center p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 rounded-lg transition-all duration-200" 
               title="Editar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>
            
            <a href="{{ route('modelos-propostas.index') }}" 
               class="inline-flex items-center p-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 rounded-lg transition-all duration-200" 
               title="Voltar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 lg:grid-cols-2 gap-6 lg:gap-8">
        <!-- Primeira Coluna - Conteúdo Principal -->
        <div class="xl:col-span-2 lg:col-span-1 space-y-6">
            <!-- Bloco Status e Categoria -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Status e Categoria</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Status -->
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</p>
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
                                </div>
                            </div>
                            <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Categoria -->
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Categoria</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white mt-2">{{ ucfirst($modelo->categoria) }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bloco Conteúdo, Descrição e Observações -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Conteúdo do Modelo</h3>
                </div>
                <div class="p-6">
                    <div class="prose dark:prose-invert max-w-none text-gray-900 dark:text-gray-100">
                        {!! $modelo->conteudo !!}
                    </div>
                </div>
                
                @if($modelo->descricao)
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-white">Descrição</h4>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 dark:text-gray-300">{{ $modelo->descricao }}</p>
                    </div>
                </div>
                @endif

                @if($modelo->observacoes)
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-white">Observações</h4>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 dark:text-gray-300">{{ $modelo->observacoes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Segunda Coluna - Informações Adicionais -->
        <div class="xl:col-span-1 lg:col-span-1 space-y-6">

            
            <!-- Cards de Informações Adicionais -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                    <!-- Ações -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ações</p>
                        </div>
                
                    </div>
                    <div class="space-y-3">
                        <button onclick="showDuplicateModal({{ $modelo->id }})" 
                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Duplicar Modelo
                        </button>
                        
                        <form id="deleteModelForm" action="{{ route('modelos-propostas.destroy', $modelo->id) }}" method="POST" 
                            onsubmit="return confirmDelete()">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" id="deleteConfirmed" name="delete_confirmed" value="false">
                            <button type="button" onclick="showDeleteModal()" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Excluir Modelo
                            </button>
                        </form>
                    </div>
                </div>
  
                <!-- Usage Count Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Usos</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white mt-2">{{ $modelo->orcamentos_count ?? 0 }} vezes</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Valor Padrão Card -->
                @if($modelo->valor_padrao)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Valor Padrão</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white mt-2">R$ {{ number_format($modelo->valor_padrao, 2, ',', '.') }}</p>
                        </div>
                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Prazo Padrão Card -->
                @if($modelo->prazo_padrao)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Prazo Padrão</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white mt-2">{{ $modelo->prazo_padrao }} dias</p>
                        </div>
                        <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Autores Padrão -->
            @if($modelo->autores_padrao && is_array($modelo->autores_padrao) && count($modelo->autores_padrao) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Autores Padrão</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-2">
                        @foreach($modelo->autores_padrao as $autor)
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ is_array($autor) ? ($autor['nome'] ?? $autor['name'] ?? 'Autor') : $autor }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @elseif($modelo->autores_padrao && is_string($modelo->autores_padrao))
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Autores Padrão</h3>
                </div>
                <div class="p-6">
                    <div class="prose prose-sm max-w-none dark:prose-invert">
                        {!! nl2br(e($modelo->autores_padrao)) !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Metadata -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Informações</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Criado em:</span>
                        <span class="text-sm text-gray-900 dark:text-white">{{ $modelo->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Atualizado em:</span>
                        <span class="text-sm text-gray-900 dark:text-white">{{ $modelo->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>


        </div>
    </div>


</div>

<!-- Modal de Confirmação de Exclusão -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
    <div class="relative mx-auto p-5 border max-w-md w-full mx-4 shadow-lg rounded-md bg-white dark:bg-gray-800">
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
            <div class="flex justify-center space-x-3 px-4 py-3">
                <button onclick="hideDeleteModal()" 
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-md">
                    Cancelar
                </button>
                <button onclick="executeDelete()" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md">
                    Excluir
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação de Duplicação -->
<div id="duplicateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
    <div class="relative mx-auto p-5 border max-w-md w-full mx-4 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900">
                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-2">Confirmar Duplicação</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Deseja duplicar este modelo de proposta? Uma cópia será criada e você será redirecionado para editá-la.
                </p>
            </div>
            <div class="flex justify-center space-x-3 px-4 py-3">
                <button onclick="hideDuplicateModal()" 
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-md">
                    Cancelar
                </button>
                <button onclick="executeDuplicate()" 
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md">
                    Duplicar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let currentModelId = null;

    function showDuplicateModal(modelId) {
        currentModelId = modelId;
        document.getElementById('duplicateModal').classList.remove('hidden');
    }

    function hideDuplicateModal() {
        document.getElementById('duplicateModal').classList.add('hidden');
        currentModelId = null;
    }

    function executeDuplicate() {
        if (!currentModelId) {
            alert('Erro: ID do modelo não encontrado');
            return;
        }

        // Desabilitar botão para evitar cliques múltiplos
        const button = event.target;
        button.disabled = true;
        button.textContent = 'Duplicando...';

        fetch(`/modelos-propostas/${currentModelId}/duplicate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                hideDuplicateModal();
                // Redirecionar para editar o novo modelo
                window.location.href = `/modelos-propostas/${data.modelo_id}/edit`;
            } else {
                throw new Error(data.message || 'Erro ao duplicar modelo');
            }
        })
        .catch(error => {
            alert('Erro ao duplicar modelo: ' + error.message);
            // Reabilitar botão
            button.disabled = false;
            button.textContent = 'Duplicar';
        });
    }

    function showDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function hideDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        // Reset confirmation flag
        document.getElementById('deleteConfirmed').value = 'false';
    }

    function executeDelete() {
        // Set confirmation flag
        document.getElementById('deleteConfirmed').value = 'true';
        // Submit the form
        document.getElementById('deleteModelForm').submit();
    }

    function confirmDelete() {
        // Only allow form submission if confirmed through modal
        const confirmed = document.getElementById('deleteConfirmed').value === 'true';
        if (!confirmed) {
            console.warn('Tentativa de exclusão sem confirmação detectada');
            return false;
        }
        // Reset flag after validation
        document.getElementById('deleteConfirmed').value = 'false';
        return true;
    }
</script>
@endpush