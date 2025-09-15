@extends('layouts.app')

@section('title', 'Editar Modelo de Proposta')

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('modelos-propostas.index') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Editar Modelo de Proposta</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Editar o modelo "{{ $modeloProposta->nome }}"</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulário Principal -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Informações do Modelo</h2>
                    
                    <form method="POST" action="{{ route('modelos-propostas.update', $modeloProposta) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nome -->
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nome do Modelo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nome" 
                                   name="nome" 
                                   value="{{ old('nome', $modeloProposta->nome) }}"
                                   required
                                   maxlength="200"
                                   placeholder="Digite o nome do modelo"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nome') border-red-500 @enderror">
                            @error('nome')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Categoria -->
                        <div>
                            <label for="categoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Categoria
                            </label>
                            <input type="text" 
                                   id="categoria" 
                                   name="categoria" 
                                   value="{{ old('categoria', $modeloProposta->categoria) }}"
                                   maxlength="100"
                                   placeholder="Ex: Desenvolvimento Web, Design, Consultoria"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('categoria') border-red-500 @enderror">
                            @error('categoria')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Descrição -->
                        <div>
                            <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Descrição
                            </label>
                            <textarea id="descricao" 
                                      name="descricao" 
                                      rows="3"
                                      placeholder="Breve descrição do modelo de proposta..."
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('descricao') border-red-500 @enderror">{{ old('descricao', $modeloProposta->descricao) }}</textarea>
                            @error('descricao')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Valor Padrão -->
                            <div>
                                <label for="valor_padrao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Valor Padrão
                                </label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400">R$</span>
                                    <input type="text" 
                                           id="valor_padrao" 
                                           name="valor_padrao" 
                                           value="{{ old('valor_padrao', number_format($modeloProposta->valor_padrao ?? 0, 2, ',', '.')) }}"
                                           placeholder="0,00"
                                           class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('valor_padrao') border-red-500 @enderror">
                                    <input type="hidden" id="valor_padrao_raw" name="valor_padrao_raw" value="{{ old('valor_padrao_raw', $modeloProposta->valor_padrao ?? 0) }}">
                                </div>
                                @error('valor_padrao')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Prazo Padrão -->
                            <div>
                                <label for="prazo_padrao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Prazo Padrão (dias)
                                </label>
                                <input type="number" 
                                       id="prazo_padrao" 
                                       name="prazo_padrao" 
                                       min="1"
                                       value="{{ old('prazo_padrao', $modeloProposta->prazo_padrao) }}"
                                       placeholder="Ex: 30"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('prazo_padrao') border-red-500 @enderror">
                                @error('prazo_padrao')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" 
                                    name="status" 
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('status') border-red-500 @enderror">
                                <option value="ativo" {{ old('status', $modeloProposta->status) == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                <option value="inativo" {{ old('status', $modeloProposta->status) == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                <option value="rascunho" {{ old('status', $modeloProposta->status) == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Conteúdo -->
                        <div>
                            <label for="conteudo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Conteúdo da Proposta <span class="text-red-500">*</span>
                            </label>
                            <textarea id="conteudo" 
                                      name="conteudo" 
                                      rows="8"
                                      required
                                      placeholder="Digite o conteúdo do modelo de proposta..."
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('conteudo') border-red-500 @enderror">{{ old('conteudo', $modeloProposta->conteudo) }}</textarea>
                            @error('conteudo')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Observações -->
                        <div>
                            <label for="observacoes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Observações
                            </label>
                            <textarea id="observacoes" 
                                      name="observacoes" 
                                      rows="4"
                                      placeholder="Informações adicionais sobre o modelo..."
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('observacoes') border-red-500 @enderror">{{ old('observacoes', $modeloProposta->observacoes) }}</textarea>
                            @error('observacoes')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Botões -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('modelos-propostas.index') }}" 
                               class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Atualizar Modelo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Seção de Preview -->
        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Preview do Modelo</h3>
                    
                    <div class="space-y-4">
                        <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="mx-auto h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center mb-4">
                                <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Valor padrão</p>
                            <p id="preview-valor" class="text-2xl font-bold text-gray-900 dark:text-white">R$ {{ number_format($modeloProposta->valor_padrao ?? 0, 2, ',', '.') }}</p>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Nome:</span>
                                <span id="preview-nome" class="text-sm font-medium text-gray-900 dark:text-white">{{ $modeloProposta->nome ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Categoria:</span>
                                <span id="preview-categoria" class="text-sm font-medium text-gray-900 dark:text-white">{{ $modeloProposta->categoria ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Prazo:</span>
                                <span id="preview-prazo" class="text-sm font-medium text-gray-900 dark:text-white">{{ $modeloProposta->prazo_padrao ? $modeloProposta->prazo_padrao . ' dias' : '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Status:</span>
                                <span id="preview-status" class="text-sm font-medium text-gray-900 dark:text-white">
                                    @switch($modeloProposta->status)
                                        @case('ativo')
                                            Ativo
                                            @break
                                        @case('inativo')
                                            Inativo
                                            @break
                                        @case('rascunho')
                                            Rascunho
                                            @break
                                        @default
                                            Ativo
                                    @endswitch
                                </span>
                            </div>
                        </div>
                        
                        <!-- Informações Adicionais -->
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-600">
                            <div class="space-y-2">
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-500 dark:text-gray-400">Criado em:</span>
                                    <span class="text-gray-600 dark:text-gray-300">{{ $modeloProposta->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-500 dark:text-gray-400">Atualizado em:</span>
                                    <span class="text-gray-600 dark:text-gray-300">{{ $modeloProposta->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nomeInput = document.getElementById('nome');
    const categoriaInput = document.getElementById('categoria');
    const valorInput = document.getElementById('valor_padrao');
    const valorRawInput = document.getElementById('valor_padrao_raw');
    const prazoInput = document.getElementById('prazo_padrao');
    const statusSelect = document.getElementById('status');
    
    // Preview elements
    const previewNome = document.getElementById('preview-nome');
    const previewCategoria = document.getElementById('preview-categoria');
    const previewValor = document.getElementById('preview-valor');
    const previewPrazo = document.getElementById('preview-prazo');
    const previewStatus = document.getElementById('preview-status');
    
    // Função para formatar valor como moeda
    function formatCurrency(value) {
        // Remove tudo que não é dígito
        const numericValue = value.replace(/\D/g, '');
        
        if (!numericValue) return '';
        
        // Converte para número e divide por 100 para ter os centavos
        const number = parseInt(numericValue) / 100;
        
        // Formata como moeda brasileira
        return new Intl.NumberFormat('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(number);
    }
    
    // Função para obter valor numérico
    function getNumericValue(formattedValue) {
        return formattedValue.replace(/\D/g, '') / 100;
    }
    
    // Event listener para formatação do valor em tempo real
    valorInput.addEventListener('input', function(e) {
        const formatted = formatCurrency(e.target.value);
        e.target.value = formatted;
        
        // Atualiza o campo hidden com o valor numérico
        const numericValue = getNumericValue(formatted);
        valorRawInput.value = numericValue;
        
        updatePreview();
    });
    
    // Function to update preview
    function updatePreview() {
        // Update nome
        previewNome.textContent = nomeInput.value || '-';
        
        // Update categoria
        previewCategoria.textContent = categoriaInput.value || '-';
        
        // Update valor
        const valor = valorRawInput.value;
        if (valor) {
            const valorFormatted = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(parseFloat(valor));
            previewValor.textContent = valorFormatted;
        } else {
            previewValor.textContent = 'R$ 0,00';
        }
        
        // Update prazo
        const prazo = prazoInput.value;
        if (prazo) {
            previewPrazo.textContent = prazo + ' dias';
        } else {
            previewPrazo.textContent = '-';
        }
        
        // Update status
        const statusText = {
            'ativo': 'Ativo',
            'inativo': 'Inativo',
            'rascunho': 'Rascunho'
        };
        previewStatus.textContent = statusText[statusSelect.value] || 'Ativo';
    }
    
    // Add event listeners for preview updates
    nomeInput.addEventListener('input', updatePreview);
    categoriaInput.addEventListener('input', updatePreview);
    prazoInput.addEventListener('input', updatePreview);
    statusSelect.addEventListener('change', updatePreview);
    
    // Initial preview update
    updatePreview();
});
</script>
@endsection