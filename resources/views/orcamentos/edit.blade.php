@extends('layouts.app')

@section('title', 'Editar Orçamento')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('dashboard')],
        ['label' => 'Orçamentos', 'url' => route('orcamentos.index')],
        ['label' => 'Editar Orçamento']
    ]" />
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Orçamento</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $orcamento->titulo }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('orcamentos.show', $orcamento) }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" 
               title="Visualizar">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </a>
            <a href="{{ route('orcamentos.index') }}" 
               class="inline-flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" 
               title="Voltar">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="flex items-center space-x-4">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
            @if($orcamento->status === 'rascunho') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
            @elseif($orcamento->status === 'enviado') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
            @elseif($orcamento->status === 'aprovado') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
            @elseif($orcamento->status === 'rejeitado') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
            @elseif($orcamento->status === 'quitado') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
            @endif">
            {{ ucfirst($orcamento->status) }}
        </span>
        <span class="text-sm text-gray-500 dark:text-gray-400">
            ID: #{{ $orcamento->id }}
        </span>
        <span class="text-sm text-gray-500 dark:text-gray-400">
            Criado em {{ $orcamento->created_at->format('d/m/Y H:i') }}
        </span>
    </div>

    <!-- Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <form action="{{ route('orcamentos.update', $orcamento) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Informações Básicas -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                    Informações Básicas
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Título -->
                    <div class="md:col-span-2">
                        <label for="titulo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Título do Orçamento *
                        </label>
                        <input type="text" 
                               id="titulo" 
                               name="titulo" 
                               value="{{ old('titulo', $orcamento->titulo) }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('titulo') border-red-500 @enderror">
                        @error('titulo')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cliente -->
                    <div>
                    <label for="cliente_autocomplete" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cliente *
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="cliente_autocomplete" 
                               placeholder="Digite o nome ou email do cliente..."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('cliente_id') border-red-500 @enderror"
                               autocomplete="off">
                        <input type="hidden" 
                               id="cliente_id" 
                               name="cliente_id" 
                               value="{{ old('cliente_id', $orcamento->cliente_id) }}"
                               required>
                        
                        <!-- Container para as tags de clientes selecionados -->
                        <div id="cliente-tags" class="mt-2 flex flex-wrap gap-2"></div>
                        
                        <!-- Dropdown de resultados -->
                        <div id="cliente-dropdown" 
                             class="absolute z-10 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
                        </div>
                    </div>
                    @error('cliente_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                </div>

                    <!-- Modelo de Proposta -->
                    <div>
                        <label for="modelo_proposta_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Modelo de Proposta
                        </label>
                        <select id="modelo_proposta_id" 
                                name="modelo_proposta_id" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Selecione um modelo (opcional)</option>
                            @foreach($modelos as $modelo)
                                <option value="{{ $modelo->id }}" 
                                        data-conteudo="{{ $modelo->conteudo }}"
                                        data-valor="{{ $modelo->valor_padrao }}"
                                        data-prazo="{{ $modelo->prazo_padrao }}"
                                        {{ old('modelo_proposta_id', $orcamento->modelo_proposta_id) == $modelo->id ? 'selected' : '' }}>
                                    {{ $modelo->nome }} - {{ $modelo->categoria }}
                                </option>
                            @endforeach
                        </select>
                        <div class="mt-2">
                            <a href="{{ route('modelos-propostas.create') }}" 
                               class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                + Criar novo modelo
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Conteúdo -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                    Conteúdo da Proposta
                </h3>

                <!-- Descrição -->
                <div>
                    <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descrição/Conteúdo *
                    </label>
                    
                    <!-- Editor de Texto Rico -->
                    <div class="border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden @error('descricao') border-red-500 @enderror">
                        <!-- Barra de Ferramentas -->
                        <div class="bg-gray-50 dark:bg-gray-700 border-b border-gray-300 dark:border-gray-600 p-2 flex flex-wrap gap-1">
                            <button type="button" onclick="formatText('bold')" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" title="Negrito">
                                <i class="fas fa-bold"></i>
                            </button>
                            <button type="button" onclick="formatText('italic')" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" title="Itálico">
                                <i class="fas fa-italic"></i>
                            </button>
                            <button type="button" onclick="formatText('underline')" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" title="Sublinhado">
                                <i class="fas fa-underline"></i>
                            </button>
                            <button type="button" onclick="formatText('strikeThrough')" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" title="Riscado">
                                <i class="fas fa-strikethrough"></i>
                            </button>
                            <div class="w-px bg-gray-300 dark:bg-gray-600 mx-1"></div>
                            <button type="button" onclick="formatText('insertUnorderedList')" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" title="Lista com marcadores">
                                <i class="fas fa-list-ul"></i>
                            </button>
                            <button type="button" onclick="formatText('insertOrderedList')" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" title="Lista numerada">
                                <i class="fas fa-list-ol"></i>
                            </button>
                            <div class="w-px bg-gray-300 dark:bg-gray-600 mx-1"></div>
                            <button type="button" onclick="showLinkModal()" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" title="Inserir link">
                                <i class="fas fa-link"></i>
                            </button>
                            <button type="button" onclick="insertLineBreak()" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" title="Quebra de linha">
                                <i class="fas fa-level-down-alt"></i>
                            </button>
                            <div class="w-px bg-gray-300 dark:bg-gray-600 mx-1"></div>
                            <button type="button" onclick="undoEdit()" class="p-2 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" title="Desfazer">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                        
                        <!-- Área Editável -->
                        <div id="descricao-editor" 
                             contenteditable="true" 
                             class="min-h-[200px] p-3 focus:outline-none focus:ring-0 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                             placeholder="Descreva detalhadamente o projeto, escopo, entregas, etc."
                             style="white-space: pre-wrap;">{{ old('descricao', $orcamento->descricao) }}</div>
                    </div>
                    
                    <!-- Campo oculto para armazenar o conteúdo -->
                    <input type="hidden" id="descricao" name="descricao" value="{{ old('descricao', $orcamento->descricao) }}" required>
                    
                    @error('descricao')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Valores e Prazos -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                    Valores e Prazos
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Valor Total -->
                    <div>
                        <label for="valor_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Valor Total (R$) *
                        </label>
                        <input type="text" 
                               id="valor_total" 
                               name="valor_total" 
                               value="{{ old('valor_total', $orcamento->valor_total) }}"
                               placeholder="0,00"
                               required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('valor_total') border-red-500 @enderror">
                        @error('valor_total')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Prazo -->
                    <div>
                        <label for="prazo_dias" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Prazo (dias)
                        </label>
                        <input type="number" 
                               id="prazo_dias" 
                               name="prazo_dias" 
                               value="{{ old('prazo_dias', $orcamento->prazo_dias) }}"
                               min="1"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('prazo_dias') border-red-500 @enderror">
                        @error('prazo_dias')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Data de Validade -->
                    <div>
                        <label for="data_validade" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Data de Validade
                        </label>
                        <input type="date" 
                               id="data_validade" 
                               name="data_validade" 
                               value="{{ old('data_validade', $orcamento->data_validade?->format('Y-m-d')) }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('data_validade') border-red-500 @enderror">
                        @error('data_validade')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status *
                        </label>
                        <div class="relative">
                            <select id="status" 
                                    name="status" 
                                    required
                                    onchange="updateStatusColors(this)"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white text-sm appearance-none pr-8 @error('status') border-red-500 @enderror
                                    @if(old('status', $orcamento->status) === 'aprovado') border-green-500 bg-green-50 dark:bg-green-900/20
                                    @elseif(old('status', $orcamento->status) === 'analisando') border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20
                                    @elseif(old('status', $orcamento->status) === 'rejeitado') border-red-500 bg-red-50 dark:bg-red-900/20
                                    @elseif(old('status', $orcamento->status) === 'quitado') border-purple-500 bg-purple-50 dark:bg-purple-900/20
                                    @else border-gray-500 bg-gray-50 dark:bg-gray-900/20
                                    @endif">
                                <option value="rascunho" {{ old('status', $orcamento->status) == 'rascunho' ? 'selected' : '' }}>⚪ Rascunho</option>
                                <option value="analisando" {{ old('status', $orcamento->status) == 'analisando' ? 'selected' : '' }}>🟡 Analisando</option>
                                <option value="rejeitado" {{ old('status', $orcamento->status) == 'rejeitado' ? 'selected' : '' }}>🔴 Rejeitado</option>
                                <option value="aprovado" {{ old('status', $orcamento->status) == 'aprovado' ? 'selected' : '' }}>🟢 Aprovado</option>
                                <option value="finalizado" {{ old('status', $orcamento->status) == 'finalizado' ? 'selected' : '' }}>🔵 Finalizado</option>
                                <option value="quitado" {{ old('status', $orcamento->status) == 'quitado' ? 'selected' : '' }}>🟣 Quitado</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Autores -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                    Autores Responsáveis
                </h3>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Buscar e selecionar autores
                    </label>
                    
                    <!-- Campo de busca com autocomplete -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" 
                                   id="autor_autocomplete" 
                                   placeholder="Digite o nome do autor para buscar ou criar novo..."
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    
                    <!-- Container para autores selecionados -->
                    <div id="autores_container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                        <!-- Autores já selecionados aparecerão aqui -->
                        @foreach($orcamento->autores as $autor)
                            <div class="relative bg-white border border-gray-200 rounded-lg p-3 shadow-sm hover:shadow-md transition-shadow" data-autor-id="{{ $autor->id }}">
                                <input type="checkbox" name="autores[]" value="{{ $autor->id }}" checked class="hidden">
                                
                                <!-- Avatar -->
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                        {{ strtoupper(substr($autor->nome, 0, 2)) }}
                                    </div>
                                    
                                    <!-- Nome e Badge -->
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $autor->nome }}</p>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700">
                                            Existente
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Botão de remoção -->
                                <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 remove-autor" data-autor-id="{{ $autor->id }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    

                    @error('autores')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <div class="mt-2">
                        <a href="{{ route('autores.create') }}" 
                           class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            + Cadastrar novo autor
                        </a>
                    </div>
                </div>
            </div>

            <!-- Upload de Arquivos -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                    Anexos e Documentos
                </h3>
                
                <div id="file-upload-container"></div>
            </div>

            <!-- Observações -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">
                    Informações Adicionais
                </h3>

                <div>
                    <label for="observacoes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Observações Internas
                    </label>
                    <textarea id="observacoes" 
                              name="observacoes" 
                              rows="4" 
                              placeholder="Observações internas sobre o orçamento (não visível para o cliente)"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('observacoes') border-red-500 @enderror">{{ old('observacoes', $orcamento->observacoes) }}</textarea>
                    @error('observacoes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('orcamentos.show', $orcamento) }}" 
                   class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                    Atualizar Orçamento
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/cliente-autocomplete.js') }}"></script>
<script src="{{ asset('js/autor-autocomplete.js') }}"></script>
<script src="{{ asset('js/orcamento-file-upload.js') }}?v={{ time() }}"></script>
<script>
// Editor de Texto Rico - Funções
function formatText(command, value = null) {
    document.execCommand(command, false, value);
    syncEditorContent();
}

function syncEditorContent() {
    const editor = document.getElementById('descricao-editor');
    const hiddenInput = document.getElementById('descricao');
    hiddenInput.value = editor.innerHTML;
}

function showLinkModal() {
    const url = prompt('Digite a URL do link:');
    if (url) {
        formatText('createLink', url);
    }
}

function insertLineBreak() {
    formatText('insertHTML', '<br>');
}

function undoEdit() {
    formatText('undo');
}

// Sincronizar conteúdo do editor com o campo oculto
document.addEventListener('DOMContentLoaded', function() {
    const editor = document.getElementById('descricao-editor');
    const hiddenInput = document.getElementById('descricao');
    
    // Sincronizar quando o conteúdo do editor mudar
    editor.addEventListener('input', syncEditorContent);
    editor.addEventListener('paste', function() {
        setTimeout(syncEditorContent, 10);
    });
    
    // Placeholder behavior
    editor.addEventListener('focus', function() {
        if (this.innerHTML === '') {
            this.innerHTML = '';
        }
    });
    
    editor.addEventListener('blur', function() {
        if (this.innerHTML.trim() === '' || this.innerHTML === '<br>') {
            this.innerHTML = '';
        }
        syncEditorContent();
    });
    
    // Sincronizar conteúdo inicial
    if (hiddenInput.value) {
        editor.innerHTML = hiddenInput.value;
    }
});
// Auto-fill from template
document.getElementById('modelo_proposta_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    if (selectedOption.value && confirm('Deseja substituir o conteúdo atual pelo modelo selecionado?')) {
        // Fill content
        const conteudo = selectedOption.dataset.conteudo;
        if (conteudo) {
            document.getElementById('descricao').value = conteudo;
        }
        
        // Fill default value
        const valor = selectedOption.dataset.valor;
        if (valor) {
            document.getElementById('valor_total').value = valor;
        }
        
        // Fill default deadline
        const prazo = selectedOption.dataset.prazo;
        if (prazo) {
            document.getElementById('prazo_dias').value = prazo;
        }
        
        // Select default authors
        const autores = JSON.parse(selectedOption.dataset.autores || '[]');
        const checkboxes = document.querySelectorAll('input[name="autores[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = autores.includes(parseInt(checkbox.value));
        });
    }
});

// Currency mask function
function applyCurrencyMask(input) {
    let value = input.value.replace(/\D/g, '');
    value = (value / 100).toFixed(2) + '';
    value = value.replace('.', ',');
    value = value.replace(/(\d)(\d{3})(\d{3}),/g, '$1.$2.$3,');
    value = value.replace(/(\d)(\d{3}),/g, '$1.$2,');
    input.value = value;
}

// Função para atualizar cores do select de status
function updateStatusColors(select) {
    // Remove todas as classes de cor
    select.classList.remove(
        'border-green-500', 'bg-green-50', 'dark:bg-green-900/20',
        'border-yellow-500', 'bg-yellow-50', 'dark:bg-yellow-900/20',
        'border-red-500', 'bg-red-50', 'dark:bg-red-900/20',
        'border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/20',
        'border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20',
        'border-gray-500', 'bg-gray-50', 'dark:bg-gray-900/20'
    );
    
    // Adiciona classes baseadas no valor selecionado
    const value = select.value;
    switch(value) {
        case 'aprovado':
            select.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/20');
            break;
        case 'analisando':
            select.classList.add('border-yellow-500', 'bg-yellow-50', 'dark:bg-yellow-900/20');
            break;
        case 'rejeitado':
            select.classList.add('border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
            break;
        case 'pago':
            select.classList.add('border-purple-500', 'bg-purple-50', 'dark:bg-purple-900/20');
            break;
        case 'finalizado':
            select.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
            break;
        default: // rascunho
            select.classList.add('border-gray-500', 'bg-gray-50', 'dark:bg-gray-900/20');
            break;
    }
}

// Apply currency mask to valor_total field
document.addEventListener('DOMContentLoaded', function() {
    const valorTotalInput = document.getElementById('valor_total');
    
    // Format existing value on page load
    if (valorTotalInput.value) {
        const numericValue = parseFloat(valorTotalInput.value).toFixed(2);
        const formattedValue = numericValue.replace('.', ',').replace(/(\d)(\d{3})(\d{3}),/g, '$1.$2.$3,').replace(/(\d)(\d{3}),/g, '$1.$2,');
        valorTotalInput.value = formattedValue;
    }
    
    valorTotalInput.addEventListener('input', function() {
        applyCurrencyMask(this);
    });
    
    // Convert formatted value to decimal before form submission
    document.querySelector('form').addEventListener('submit', function() {
        const formattedValue = valorTotalInput.value;
        const decimalValue = formattedValue.replace(/\./g, '').replace(',', '.');
        valorTotalInput.value = decimalValue;
        
        // Sincronizar conteúdo do editor antes do envio
        syncEditorContent();
    });
    
    // Initialize file upload component
    console.log('About to initialize OrcamentoFileUpload');
    window.orcamentoFileUpload = new OrcamentoFileUpload({
        containerId: 'file-upload-container',
        orcamentoId: {{ $orcamento->id }},
        categoria: 'anexo',
        getFilesUrl: '/orcamentos/',
        deleteUrl: '/api/budget/orcamentos/files/',
        downloadUrl: '/api/budget/orcamentos/files/'
    });
    console.log('OrcamentoFileUpload initialized:', window.orcamentoFileUpload);
    
    // Pré-selecionar cliente atual se existir
    @if($orcamento->cliente)
    const clienteAtual = {
        id: {{ $orcamento->cliente->id }},
        nome: '{{ addslashes($orcamento->cliente->nome) }}',
        email: '{{ addslashes($orcamento->cliente->email) }}',
        telefone: '{{ addslashes($orcamento->cliente->telefone ?? '') }}'
    };
    
    // Definir o valor do campo hidden cliente_id
    document.getElementById('cliente_id').value = clienteAtual.id;
    
    // Adicionar tag do cliente atual
    const clienteTagsContainer = document.getElementById('cliente-tags');
    const tag = document.createElement('div');
    tag.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
    tag.innerHTML = `
        <span>${clienteAtual.nome} - ${clienteAtual.email}</span>
        <button type="button" class="ml-2 text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200" onclick="removeClienteTag()">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    clienteTagsContainer.appendChild(tag);
    
    // Definir função para remover tag do cliente
    window.removeClienteTag = function() {
        document.getElementById('cliente_id').value = '';
        document.getElementById('cliente_autocomplete').value = '';
        clienteTagsContainer.innerHTML = '';
    };
    @endif
});
</script>
@endpush
@endsection