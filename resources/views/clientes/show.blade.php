@extends('layouts.app')

@section('title', $cliente->nome)

@section('content')
<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('clientes.index') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div class="flex items-center space-x-4">
                    <img class="h-16 w-16 object-cover rounded-full border-2 border-gray-300 dark:border-gray-600" 
                         src="{{ $cliente->avatar ? Storage::url($cliente->avatar) : 'data:image/svg+xml,%3csvg width=\'100\' height=\'100\' xmlns=\'http://www.w3.org/2000/svg\'%3e%3crect width=\'100\' height=\'100\' fill=\'%23f3f4f6\'/%3e%3ctext x=\'50%25\' y=\'50%25\' font-size=\'18\' text-anchor=\'middle\' alignment-baseline=\'middle\' font-family=\'monospace, sans-serif\' fill=\'%236b7280\'%3e' . strtoupper(substr($cliente->nome, 0, 2)) . '%3c/text%3e%3c/svg%3e' }}" 
                         alt="{{ $cliente->nome }}">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $cliente->nome }}</h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Cliente #{{ $cliente->id }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('clientes.edit', $cliente) }}" 
                   class="inline-flex items-center justify-center w-10 h-10 text-blue-500 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Layout Principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Card de Contato -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contato Rápido</h3>
                <div class="space-y-3">
                    @if($cliente->email)
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-white truncate">{{ $cliente->email }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($cliente->telefone)
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-white truncate">{{ $cliente->telefone }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($cliente->whatsapp)
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900 dark:text-white truncate">{{ $cliente->whatsapp }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Informações do Sistema -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Informações do Sistema</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">ID do Cliente</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">#{{ $cliente->id }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Data de Criação</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $cliente->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Última Atualização</label>
                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $cliente->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informações Principais -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Card de Informações do Cliente -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Informações do Cliente</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome</label>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $cliente->nome }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pessoa de Contato</label>
                        <p class="text-gray-900 dark:text-white">{{ $cliente->pessoa_contato ?? 'Não informado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">E-mail</label>
                        <p class="text-gray-900 dark:text-white">{{ $cliente->email ?? 'Não informado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefone</label>
                        <p class="text-gray-900 dark:text-white">{{ $cliente->telefone ?? 'Não informado' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">WhatsApp</label>
                        <p class="text-gray-900 dark:text-white">{{ $cliente->whatsapp ?? 'Não informado' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Ações Rápidas -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Ações Rápidas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('clientes.edit', $cliente) }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar Cliente
                    </a>
                    @if($cliente->telefone)
                    <a href="tel:{{ $cliente->telefone }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Ligar
                    </a>
                    @else
                    <button type="button" disabled
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-400 text-white font-medium rounded-lg cursor-not-allowed opacity-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Ligar
                    </button>
                    @endif
                    @if($cliente->whatsapp)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->whatsapp) }}" 
                       target="_blank"
                       class="inline-flex items-center justify-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        WhatsApp
                    </a>
                    @else
                    <button type="button" disabled
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-400 text-white font-medium rounded-lg cursor-not-allowed opacity-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        WhatsApp
                    </button>
                    @endif
                    @if($cliente->email)
                    <a href="mailto:{{ $cliente->email }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Email
                    </a>
                    @else
                    <button type="button" disabled
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-400 text-white font-medium rounded-lg cursor-not-allowed opacity-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Email
                    </button>
                    @endif
                </div>
            </div>
            
            <!-- Endereço -->
            @if($cliente->endereco || $cliente->cep || $cliente->cidade)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Endereço</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($cliente->cep)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">CEP</label>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->cep }}</p>
                        </div>
                        @endif
                        
                        @if($cliente->endereco)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Endereço</label>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->endereco }}</p>
                        </div>
                        @endif
                        
                        @if($cliente->numero)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Número</label>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->numero }}</p>
                        </div>
                        @endif
                        
                        @if($cliente->complemento)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Complemento</label>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->complemento }}</p>
                        </div>
                        @endif
                        
                        @if($cliente->bairro)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Bairro</label>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->bairro }}</p>
                        </div>
                        @endif
                        
                        @if($cliente->cidade)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Cidade</label>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->cidade }}</p>
                        </div>
                        @endif
                        
                        @if($cliente->estado)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Estado</label>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->estado }}</p>
                        </div>
                        @endif
                        
                        @if($cliente->pais)
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">País</label>
                            <p class="text-gray-900 dark:text-white">{{ $cliente->pais }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Observações -->
            @if($cliente->observacoes)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Observações</h2>
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $cliente->observacoes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Portfólio do Cliente -->
    <div class="mt-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Portfólio
                    </h3>
                    <a href="{{ route('portfolio.works.create', ['cliente_id' => $cliente->id]) }}" 
                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Novo Trabalho
                    </a>
                </div>

                @php
                    $portfolioWorks = $cliente->portfolioWorks()->with(['category', 'images'])->latest()->take(6)->get();
                @endphp

                @if($portfolioWorks->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                        @foreach($portfolioWorks as $work)
                            <div class="group relative bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden hover:shadow-md transition-shadow duration-200">
                                @if($work->featured_image)
                                    <div class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-600">
                                        <img src="{{ Storage::url($work->featured_image) }}" 
                                             alt="{{ $work->title }}" 
                                             class="w-full h-32 object-cover group-hover:scale-105 transition-transform duration-200">
                                    </div>
                                @else
                                    <div class="h-32 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-800 dark:to-purple-900 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="p-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="font-medium text-gray-900 dark:text-white text-sm truncate">{{ $work->title }}</h4>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($work->status === 'published') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                            @elseif($work->status === 'draft') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                            {{ ucfirst($work->status) }}
                                        </span>
                                    </div>
                                    
                                    @if($work->category)
                                        <div class="flex items-center mb-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300">
                                                {{ $work->category->name }}
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                        <span>{{ $work->created_at->format('d/m/Y') }}</span>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('portfolio.works.edit', $work) }}" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            @if($work->status === 'published')
                                                <a href="{{ route('public.portfolio.work', $work->slug) }}" 
                                                   target="_blank"
                                                   class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($cliente->portfolioWorks()->count() > 6)
                        <div class="text-center">
                            <a href="{{ route('portfolio.works.index', ['cliente_id' => $cliente->id]) }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors duration-200">
                                Ver todos os trabalhos ({{ $cliente->portfolioWorks()->count() }})
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum trabalho no portfólio</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando o primeiro trabalho para este cliente.</p>
                        <div class="mt-6">
                            <a href="{{ route('portfolio.works.create', ['cliente_id' => $cliente->id]) }}" 
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Criar Primeiro Trabalho
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Timeline de Orçamentos do Cliente -->
    @if($cliente->orcamentos->count() > 0)
    <div class="mt-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <!-- Header responsivo -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Timeline de Orçamentos</h2>
                    
                    <!-- Botões de ação - Layout responsivo -->
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <!-- Botão Gerar Link -->
                        <button id="generateLinkBtn" 
                                class="w-10 h-10 sm:w-auto sm:h-auto sm:px-4 sm:py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center sm:space-x-2 text-sm"
                                onclick="generateExtractLink({{ $cliente->id }})"
                                title="Gerar Link">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                            </svg>
                            <span class="hidden sm:inline ml-2">Gerar Link</span>
                        </button>
                        
                        <!-- Botão Desativar Link -->
                        <button id="deactivateLinkBtn" 
                                class="w-10 h-10 sm:w-auto sm:h-auto sm:px-4 sm:py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center sm:space-x-2 text-sm hidden"
                                onclick="deactivateExtractLink({{ $cliente->id }})"
                                title="Desativar Link">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                            </svg>
                            <span class="hidden sm:inline ml-2">Desativar Link</span>
                        </button>
                        
                        <!-- Botão Ver Extrato -->
                        <button id="viewExtractBtn" 
                                class="w-10 h-10 sm:w-auto sm:h-auto sm:px-4 sm:py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center sm:space-x-2 text-sm hidden"
                                onclick="viewExtract()"
                                title="Ver Extrato">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span class="hidden sm:inline ml-2">Ver Extrato</span>
                        </button>
                        
                        <!-- Botão Novo Orçamento -->
                        <a href="{{ route('orcamentos.create', ['cliente_id' => $cliente->id]) }}" 
                           class="w-10 h-10 sm:w-auto sm:h-auto sm:px-4 sm:py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center sm:space-x-2 text-sm"
                           title="Novo Orçamento">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="hidden sm:inline ml-2">Novo Orçamento</span>
                        </a>
                    </div>
                </div>
                
                <!-- Timeline Container - Responsivo -->
                <div class="relative">
                    <!-- Timeline Line - Corrigida para mobile -->
                    <div class="absolute left-4 sm:left-36 top-0 bottom-0 w-0.5 bg-gray-300"></div>
                    
                    <!-- Timeline Items -->
                    <div class="space-y-6">
                        @foreach($cliente->orcamentos->sortByDesc('created_at') as $index => $orcamento)
                        <div class="relative">
                            <!-- Layout Mobile: Vertical Stack -->
                            <div class="block sm:hidden">
                                <!-- Timeline Marker - Corrigido para mobile -->
                                <div class="absolute left-3.5 top-2 w-3 h-3 bg-gray-800 rounded-full border-2 border-white shadow-sm z-10"></div>
                                
                                <!-- Content Container -->
                                <div class="ml-10">
                                    <!-- Data/Hora -->
                                    <div class="mb-2">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $orcamento->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $orcamento->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                    
                                    <!-- Timeline Card -->
                                    <a href="{{ route('orcamentos.show', $orcamento) }}" class="block">
                                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 hover:border-gray-300 dark:hover:border-gray-500 transition-all duration-200 hover:shadow-md">
                                            <!-- Header -->
                                            <div class="mb-3">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                                    {{ $orcamento->titulo }}
                                                </h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                                    Orçamento #{{ $orcamento->id }}
                                                </p>
                                                
                                                <!-- Status Badge -->
                                                @php
                                                    $statusColors = [
                                                        'rascunho' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                        'enviado' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                                        'aprovado' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                        'rejeitado' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                        'cancelado' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                        'quitado' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300'
                                                    ];
                                                @endphp
                                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$orcamento->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                    {{ ucfirst($orcamento->status) }}
                                                </span>
                                            </div>
                                            
                                            <!-- Content - Stack Vertical -->
                                            <div class="space-y-3">
                                                <!-- Valor -->
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                    </svg>
                                                    <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                                        R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}
                                                    </span>
                                                </div>
                                                
                                                <!-- Autor/Responsável -->
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                                        @if($orcamento->user)
                                                            {{ $orcamento->user->name }}
                                                        @else
                                                            Sistema
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <!-- Actions -->
                                            <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-600">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        Criado em {{ $orcamento->created_at->format('d/m/Y H:i') }}
                                                    </span>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                                                            Ver detalhes
                                                        </span>
                                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Layout Desktop: Horizontal -->
                            <div class="hidden sm:flex sm:items-start">
                                <!-- Data/Hora Column -->
                                <div class="w-32 flex-shrink-0 text-right pr-6">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $orcamento->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $orcamento->created_at->format('H:i') }}
                                    </div>
                                </div>
                                
                                <!-- Timeline Marker -->
                                <div class="absolute left-34 top-2 w-3 h-3 bg-gray-800 rounded-full border-2 border-white shadow-sm z-10"></div>
                                
                                <!-- Timeline Content -->
                                <div class="flex-1 ml-6">
                                    <!-- Timeline Card -->
                                    <a href="{{ route('orcamentos.show', $orcamento) }}" class="block">
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 hover:border-gray-300 dark:hover:border-gray-500 transition-all duration-200 hover:shadow-md">
                                        <!-- Header -->
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                                    {{ $orcamento->titulo }}
                                                </h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Orçamento #{{ $orcamento->id }}
                                                </p>
                                            </div>
                                            
                                            <!-- Status Badge -->
                                            @php
                                                $statusColors = [
                                                    'rascunho' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                    'enviado' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                                    'aprovado' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                    'rejeitado' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                    'cancelado' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                    'quitado' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300'
                                                ];
                                            @endphp
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $statusColors[$orcamento->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($orcamento->status) }}
                                            </span>
                                        </div>
                                        
                                        <!-- Content - Responsivo -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                            <!-- Valor -->
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                                <span class="text-base sm:text-lg font-bold text-green-600 dark:text-green-400">
                                                    R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}
                                                </span>
                                            </div>
                                            
                                            <!-- Autor/Responsável -->
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                                    @if($orcamento->user)
                                                        {{ $orcamento->user->name }}
                                                    @else
                                                        Sistema
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-600">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-4">
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        Criado em {{ $orcamento->created_at->format('d/m/Y H:i') }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-xs text-blue-600 dark:text-blue-400 font-medium">
                                                        Clique para ver detalhes
                                                    </span>
                                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal para Link do Extrato -->
<div id="extractModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900">
                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">Link do Extrato</h3>
            <div class="mt-4">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Link público para compartilhar o extrato de pagamentos do cliente:
                </p>
                <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-lg border">
                    <input type="text" id="extractLink" readonly 
                           class="w-full bg-transparent text-sm text-gray-700 dark:text-gray-300 border-none focus:outline-none" 
                           value="Gerando link...">
                </div>
            </div>
            <div class="flex items-center justify-center space-x-3 mt-6">
                <button id="copyLinkBtn" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    <span>Copiar Link</span>
                </button>
                <button onclick="closeExtractModal()" 
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação para Desativar -->
<div id="confirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">Confirmar Desativação</h3>
            <div class="mt-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tem certeza que deseja desativar o link do extrato? O link atual ficará inválido.
                </p>
            </div>
            <div class="flex items-center justify-center space-x-3 mt-6">
                <button id="confirmDeactivateBtn" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Sim, Desativar
                </button>
                <button onclick="closeConfirmModal()" 
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentExtractUrl = '';
let currentClienteId = {{ $cliente->id }};

// Verificar se já existe um link ativo ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    checkExtractStatus();
});

function checkExtractStatus() {
    fetch(`/clientes/${currentClienteId}/check-extract-status`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Resposta não é JSON válido');
        }
        return response.json();
    })
    .then(data => {
        if (data.hasActiveLink) {
            currentExtractUrl = data.url;
            showActiveButtons();
        } else {
            showGenerateButton();
        }
    })
    .catch(error => {
        console.error('Erro ao verificar status:', error);
        
        // Se for erro de autenticação, redirecionar para login
        if (error.message.includes('401') || error.message.includes('Não autenticado')) {
            alert('Sua sessão expirou. Você será redirecionado para a página de login.');
            window.location.href = '/login';
            return;
        }
        
        showGenerateButton();
    });
}

function generateExtractLink(clienteId) {
    // Mostrar modal
    document.getElementById('extractModal').classList.remove('hidden');
    document.getElementById('extractLink').value = 'Gerando link...';
    
    // Gerar/buscar token do extrato
    fetch(`/clientes/${clienteId}/gerar-token-extrato`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Resposta não é JSON válido');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            currentExtractUrl = data.url;
            document.getElementById('extractLink').value = currentExtractUrl;
            showActiveButtons();
            showSuccessMessage('Link gerado com sucesso!');
        } else {
            document.getElementById('extractLink').value = 'Erro ao gerar link';
            showErrorMessage('Erro ao gerar link do extrato');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        
        // Se for erro de autenticação, redirecionar para login
        if (error.message.includes('401') || error.message.includes('Não autenticado')) {
            alert('Sua sessão expirou. Você será redirecionado para a página de login.');
            window.location.href = '/login';
            return;
        }
        
        document.getElementById('extractLink').value = 'Erro ao gerar link';
        showErrorMessage('Erro de conexão ao gerar link');
    });
}

function deactivateExtractLink(clienteId) {
    // Mostrar modal de confirmação
    document.getElementById('confirmModal').classList.remove('hidden');
    
    // Configurar ação de confirmação
    document.getElementById('confirmDeactivateBtn').onclick = function() {
        fetch(`/clientes/${clienteId}/desativar-token-extrato`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Resposta não é JSON válido');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                currentExtractUrl = '';
                showGenerateButton();
                closeConfirmModal();
                showSuccessMessage('Link desativado com sucesso!');
            } else {
                showErrorMessage('Erro ao desativar link');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            
            // Se for erro de autenticação, redirecionar para login
            if (error.message.includes('401') || error.message.includes('Não autenticado')) {
                alert('Sua sessão expirou. Você será redirecionado para a página de login.');
                window.location.href = '/login';
                return;
            }
            
            showErrorMessage('Erro de conexão ao desativar link');
        });
    };
}

function viewExtract() {
    if (currentExtractUrl) {
        window.open(currentExtractUrl, '_blank');
    } else {
        showErrorMessage('Nenhum link ativo encontrado');
    }
}

function showGenerateButton() {
    const generateBtn = document.getElementById('generateLinkBtn');
    const deactivateBtn = document.getElementById('deactivateLinkBtn');
    const viewBtn = document.getElementById('viewExtractBtn');
    
    if (generateBtn) generateBtn.classList.remove('hidden');
    if (deactivateBtn) deactivateBtn.classList.add('hidden');
    if (viewBtn) viewBtn.classList.add('hidden');
}

function showActiveButtons() {
    const generateBtn = document.getElementById('generateLinkBtn');
    const deactivateBtn = document.getElementById('deactivateLinkBtn');
    const viewBtn = document.getElementById('viewExtractBtn');
    
    if (generateBtn) generateBtn.classList.add('hidden');
    if (deactivateBtn) deactivateBtn.classList.remove('hidden');
    if (viewBtn) viewBtn.classList.remove('hidden');
}

function closeExtractModal() {
    document.getElementById('extractModal').classList.add('hidden');
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
}

function showSuccessMessage(message) {
    // Criar toast de sucesso
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

function showErrorMessage(message) {
    // Criar toast de erro
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Copiar link para clipboard
document.getElementById('copyLinkBtn').addEventListener('click', function() {
    const linkInput = document.getElementById('extractLink');
    linkInput.select();
    linkInput.setSelectionRange(0, 99999);
    
    navigator.clipboard.writeText(linkInput.value).then(function() {
        const btn = document.getElementById('copyLinkBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg><span>Copiado!</span>';
        btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        btn.classList.add('bg-green-600', 'hover:bg-green-700');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('bg-green-600', 'hover:bg-green-700');
            btn.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 2000);
    }).catch(function(err) {
        console.error('Erro ao copiar: ', err);
        showErrorMessage('Erro ao copiar link');
    });
});

// Fechar modais ao clicar fora
document.getElementById('extractModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeExtractModal();
    }
});

document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeConfirmModal();
    }
});
</script>

@endsection