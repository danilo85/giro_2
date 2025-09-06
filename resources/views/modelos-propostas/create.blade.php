@extends('layouts.app')

@section('title', 'Novo Modelo de Proposta')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4">
            <a href="{{ route('modelos-propostas.index') }}" 
               class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Novo Modelo de Proposta</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Crie um template para agilizar a criação de orçamentos</p>
            </div>
        </div>
    </div>
    
    <!-- Formulário -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <form method="POST" action="{{ route('modelos-propostas.store') }}" class="p-6 space-y-6">
            @csrf
            
            <!-- Informações Básicas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nome do Modelo *</label>
                    <input type="text" 
                           id="nome" 
                           name="nome" 
                           value="{{ old('nome') }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('nome') border-red-500 @enderror"
                           placeholder="Ex: Modelo Website Corporativo">
                    @error('nome')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="categoria" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categoria</label>
                    <input type="text" 
                           id="categoria" 
                           name="categoria" 
                           value="{{ old('categoria') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('categoria') border-red-500 @enderror"
                           placeholder="Ex: Websites, Aplicativos, Design">
                    @error('categoria')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Descrição -->
            <div>
                <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descrição</label>
                <textarea id="descricao" 
                          name="descricao" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('descricao') border-red-500 @enderror"
                          placeholder="Descreva o propósito e características deste modelo...">{{ old('descricao') }}</textarea>
                @error('descricao')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Conteúdo do Modelo -->
            <div>
                <label for="conteudo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Conteúdo do Modelo *</label>
                <div class="mb-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Use as seguintes variáveis que serão substituídas automaticamente:</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{'cliente_nome'}}</span>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{cliente_email}}</span>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{cliente_empresa}}</span>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{data_atual}}</span>
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">{{valor_total}}</span>
                    </div>
                </div>
                <textarea id="conteudo" 
                          name="conteudo" 
                          rows="15"
                          required
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white font-mono text-sm @error('conteudo') border-red-500 @enderror"
                          placeholder="Digite o conteúdo do modelo aqui...

Exemplo:
Prezado(a) {{'cliente_nome'}},

Segue nossa proposta para desenvolvimento de website para {{'cliente_empresa'}}.

Valor total: {{'valor_total'}}
Data: {{'data_atual'}}

Atenciosamente,
Equipe">{{ old('conteudo') }}</textarea>
                @error('conteudo')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Configurações Avançadas -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Configurações Avançadas</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="valor_padrao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Valor Padrão</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">R$</span>
                            <input type="number" 
                                   id="valor_padrao" 
                                   name="valor_padrao" 
                                   value="{{ old('valor_padrao') }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('valor_padrao') border-red-500 @enderror"
                                   placeholder="0,00">
                        </div>
                        @error('valor_padrao')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="prazo_padrao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prazo Padrão (dias)</label>
                        <input type="number" 
                               id="prazo_padrao" 
                               name="prazo_padrao" 
                               value="{{ old('prazo_padrao') }}"
                               min="1"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('prazo_padrao') border-red-500 @enderror"
                               placeholder="30">
                        @error('prazo_padrao')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Autores Padrão -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Autores Padrão</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($autores as $autor)
                            <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                <input type="checkbox" 
                                       name="autores_padrao[]" 
                                       value="{{ $autor->id }}"
                                       {{ in_array($autor->id, old('autores_padrao', [])) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <div class="ml-3 flex items-center">
                                    @if($autor->avatar)
                                        <img src="{{ Storage::url($autor->avatar) }}" 
                                             alt="{{ $autor->nome }}" 
                                             class="h-6 w-6 rounded-full object-cover">
                                    @else
                                        <div class="h-6 w-6 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">{{ substr($autor->nome, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <span class="ml-2 text-sm text-gray-900 dark:text-white">{{ $autor->nome }}</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('autores_padrao')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Observações -->
            <div>
                <label for="observacoes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Observações</label>
                <textarea id="observacoes" 
                          name="observacoes" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('observacoes') border-red-500 @enderror"
                          placeholder="Observações internas sobre este modelo...">{{ old('observacoes') }}</textarea>
                @error('observacoes')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Botões -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('modelos-propostas.index') }}" 
                   class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white font-medium">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Criar Modelo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Preview das variáveis
document.addEventListener('DOMContentLoaded', function() {
    const conteudoTextarea = document.getElementById('conteudo');
    
    // Adicionar exemplo se estiver vazio
    if (!conteudoTextarea.value.trim()) {
        conteudoTextarea.value = `Prezado(a) {{'cliente_nome'}},

Segue nossa proposta comercial para {{'cliente_empresa'}}.

=== DETALHES DO PROJETO ===

Descrição: [Descrever o projeto aqui]

Valor Total: {{'valor_total'}}
Data da Proposta: {{'data_atual'}}

=== CONDIÇÕES ===

• Prazo de execução: [X] dias úteis
• Forma de pagamento: [Definir condições]
• Validade da proposta: 30 dias

=== PRÓXIMOS PASSOS ===

1. Aprovação da proposta
2. Assinatura do contrato
3. Início do desenvolvimento

Atenciosamente,
Equipe de Desenvolvimento`;
    }
});
</script>
@endsection