@extends('layouts.app')

@section('title', 'Editar Modelo - Giro')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('dashboard'), 'icon' => 'fas fa-home'],
        ['label' => 'Modelos de Propostas', 'url' => route('modelos-propostas.index')],
        ['label' => 'Editar Modelo']
    ]" />
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar Modelo</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Modifique as informações do modelo de proposta</p>
        </div>
        
        <a href="{{ route('modelos-propostas.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors mt-4 sm:mt-0">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <form action="{{ route('modelos-propostas.update', $modelo->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Nome do Modelo -->
                <div class="lg:col-span-2">
                    <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Nome do Modelo *
                    </label>
                    <input type="text" 
                           id="nome" 
                           name="nome" 
                           value="{{ old('nome', $modelo->nome) }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                           placeholder="Digite o nome do modelo">
                    @error('nome')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Categoria -->
                <div>
                    <label for="categoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Categoria *
                    </label>
                    <select id="categoria" 
                            name="categoria" 
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="">Selecione uma categoria</option>
                        <option value="servicos" {{ old('categoria', $modelo->categoria) == 'servicos' ? 'selected' : '' }}>Serviços</option>
                        <option value="produtos" {{ old('categoria', $modelo->categoria) == 'produtos' ? 'selected' : '' }}>Produtos</option>
                        <option value="consultoria" {{ old('categoria', $modelo->categoria) == 'consultoria' ? 'selected' : '' }}>Consultoria</option>
                        <option value="manutencao" {{ old('categoria', $modelo->categoria) == 'manutencao' ? 'selected' : '' }}>Manutenção</option>
                        <option value="outros" {{ old('categoria', $modelo->categoria) == 'outros' ? 'selected' : '' }}>Outros</option>
                    </select>
                    @error('categoria')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="ativo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>
                    <select id="ativo" 
                            name="ativo" 
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="1" {{ old('ativo', $modelo->ativo) == '1' ? 'selected' : '' }}>Ativo</option>
                        <option value="0" {{ old('ativo', $modelo->ativo) == '0' ? 'selected' : '' }}>Inativo</option>
                    </select>
                    @error('ativo')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div class="lg:col-span-2">
                    <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Descrição
                    </label>
                    <textarea id="descricao" 
                              name="descricao" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                              placeholder="Descreva brevemente o modelo">{{ old('descricao', $modelo->descricao) }}</textarea>
                    @error('descricao')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Conteúdo do Modelo -->
                <div class="lg:col-span-2">
                    <label for="conteudo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Conteúdo do Modelo *
                    </label>
                    <textarea id="conteudo" 
                              name="conteudo" 
                              rows="10"
                              required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                              placeholder="Digite o conteúdo do modelo de proposta">{{ old('conteudo', $modelo->conteudo) }}</textarea>
                    @error('conteudo')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Observações -->
                <div class="lg:col-span-2">
                    <label for="observacoes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Observações
                    </label>
                    <textarea id="observacoes" 
                              name="observacoes" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                              placeholder="Observações adicionais sobre o modelo">{{ old('observacoes', $modelo->observacoes) }}</textarea>
                    @error('observacoes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('modelos-propostas.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancelar
                </a>
                
                <button type="submit" 
                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Atualizar Modelo
                </button>
            </div>
        </form>
    </div>
</div>
@endsection