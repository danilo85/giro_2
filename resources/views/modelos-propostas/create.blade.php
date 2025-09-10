@extends('layouts.app')

@section('title', 'Novo Modelo de Proposta')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                    <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="{{ route('modelos-propostas.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Modelos de Propostas</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Novo Modelo</span>
                </div>
            </li>
        </ol>
    </nav>

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
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Novo Modelo de Proposta</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Criar um novo modelo de proposta</p>
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
                    
                    <form method="POST" action="{{ route('modelos-propostas.store') }}" class="space-y-6">
                        @csrf
                        
                        <!-- Nome -->
                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nome do Modelo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nome" 
                                   name="nome" 
                                   value="{{ old('nome') }}"
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
                                   value="{{ old('categoria') }}"
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
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('descricao') border-red-500 @enderror">{{ old('descricao') }}</textarea>
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
                                           value="{{ old('valor_padrao') }}"
                                           placeholder="0,00"
                                           class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('valor_padrao') border-red-500 @enderror">
                                    <input type="hidden" id="valor_padrao_raw" name="valor_padrao_raw">
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
                                       value="{{ old('prazo_padrao') }}"
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
                                <option value="ativo" {{ old('status', 'ativo') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                <option value="inativo" {{ old('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                <option value="rascunho" {{ old('status') == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
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
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('conteudo') border-red-500 @enderror">{{ old('conteudo') }}</textarea>
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
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('observacoes') border-red-500 @enderror">{{ old('observacoes') }}</textarea>
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
                                Criar Modelo
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
                            <p id="preview-valor" class="text-2xl font-bold text-gray-900 dark:text-white">R$ 0,00</p>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Nome:</span>
                                <span id="preview-nome" class="text-sm font-medium text-gray-900 dark:text-white">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Categoria:</span>
                                <span id="preview-categoria" class="text-sm font-medium text-gray-900 dark:text-white">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Prazo:</span>
                                <span id="preview-prazo" class="text-sm font-medium text-gray-900 dark:text-white">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Status:</span>
                                <span id="preview-status" class="text-sm font-medium text-gray-900 dark:text-white">Ativo</span>
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
    
    // Função para formatar valor monetário
    function formatCurrency(value) {
        // Remove tudo que não é dígito
        value = value.replace(/\D/g, '');
        
        // Converte para centavos
        value = (value / 100).toFixed(2) + '';
        
        // Adiciona separadores
        value = value.replace('.', ',');
        value = value.replace(/(\d)(\d{3})(\d{3}),/g, '$1.$2.$3,');
        value = value.replace(/(\d)(\d{3}),/g, '$1.$2,');
        
        return value;
    }
    
    // Função para obter valor numérico
    function getNumericValue(value) {
        return value.replace(/\D/g, '') / 100;
    }
    
    // Máscara para o campo valor
    valorInput.addEventListener('input', function(e) {
        const formatted = formatCurrency(e.target.value);
        e.target.value = formatted;
        
        // Atualiza o campo hidden com valor numérico
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
        const valor = valorRawInput.value || getNumericValue(valorInput.value);
        if (valor && valor > 0) {
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