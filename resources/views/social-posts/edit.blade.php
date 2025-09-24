@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', 'Editar Post - Redes Sociais')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex mb-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('social-posts.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Redes Sociais
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Editar Post</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Editar Post
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Crie um novo post para suas redes sociais com preview em tempo real
                    </p>
                </div>
         <a href="{{ route('social-posts.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200" title="Voltar">
                        <i class="fas fa-arrow-left"></i>
                    </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <form action="{{ route('social-posts.update', $socialPost) }}" method="POST" enctype="multipart/form-data" id="socialPostForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Post Information Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M18 5h-.7c.229-.467.349-.98.351-1.5a3.5 3.5 0 0 0-3.5-3.5c-1.717 0-3.215 1.2-4.331 2.481C8.4.842 6.949 0 5.5 0A3.5 3.5 0 0 0 2 3.5c.003.52.123 1.033.351 1.5H2a2 2 0 0 0-2 2v3a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V7a2 2 0 0 0-2-2ZM8.531 4.75a1.5 1.5 0 0 1 2.938 0 2.5 2.5 0 0 1-2.938 0Z"/>
                                    <path d="M18 12H2v5a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5Z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Informações do Post</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Configure o conteúdo principal do seu post</p>
                            </div>
                        </div>

                        <!-- Título -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Título do Post
                            </label>
                            <input type="text" 
                                   id="titulo" 
                                   name="titulo" 
                                   value="{{ old('titulo', $socialPost->titulo) }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('titulo') border-red-500 dark:border-red-500 @enderror"
                                   placeholder="Digite o título do post..."
                                   maxlength="100">
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Máximo 100 caracteres</p>
                                <span id="titleCounter" class="text-xs text-gray-500 dark:text-gray-400">0/100</span>
                            </div>
                            @error('titulo')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z"/>
                                    <path d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1.969 1.969 0 0 0 1.933 2h12.134A1.97 1.97 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.513.81l-3.4.679a2.947 2.947 0 0 1-1.85-.227 2.96 2.96 0 0 1-1.635-3.257l.681-3.397Z"/>
                                    <path d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.679 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.359-4.236 4.237-1.7.339.341-1.699Z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Conteúdo do Post</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Escreva o conteúdo principal do seu post</p>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="mb-6">
                            <label for="content" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Conteúdo
                            </label>
                            <div class="mb-3">
                                <div class="flex items-center space-x-2 p-2 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <button type="button" class="p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600" onclick="formatText('bold')">
                                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M5 1a1 1 0 0 0 0 2h.5a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 0 0 2h10a1 1 0 0 0 0-2h-.5a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1H15a1 1 0 0 0 0-2H5Z"/>
                                        </svg>
                                    </button>
                                    <button type="button" class="p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600" onclick="formatText('italic')">
                                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M12.5 3a1 1 0 0 0 0 2h1.382l-4 12H8.5a1 1 0 0 0 0 2h4a1 1 0 0 0 0-2H10.618l4-12H16.5a1 1 0 0 0 0-2h-4Z"/>
                                        </svg>
                                    </button>

                                </div>
                            </div>
                            <textarea id="legenda" 
                                      name="legenda" 
                                      rows="6" 
                                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('legenda') border-red-500 dark:border-red-500 @enderror" 
                                      placeholder="Escreva o conteúdo do seu post...">{{ old('legenda', $socialPost->legenda) }}</textarea>
                            <div class="flex justify-between items-center mt-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Máximo 2200 caracteres</p>
                                <span id="contentCounter" class="text-xs text-gray-500 dark:text-gray-400">0/2200</span>
                            </div>
                            @error('legenda')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Hashtags Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M12.186 8.672 18.743.947a2.542 2.542 0 0 0-2.86-4.123L8.186 2.672a2.542 2.542 0 0 0-2.612 4.123l6.612 1.8Zm.564 1.426L19.5 12.186a2.542 2.542 0 0 1-4.123 2.86L13.672 8.186a2.542 2.542 0 0 1 4.123-2.612l1.8 6.612Z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Hashtags</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Adicione hashtags para aumentar o alcance do seu post</p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="hashtagInput" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Adicionar Hashtag
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="hashtagInput" 
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                       placeholder="Digite uma hashtag e pressione espaço para adicionar..."
                                       onkeydown="handleHashtagInput(event)"
                                       onkeyup="handleHashtagKeyup(event)">
                            </div>
                            <div id="hashtagSuggestions" class="relative mt-1" style="display: none;"></div>
                            <div id="selectedHashtags" class="flex flex-wrap gap-2 mt-3">
                                <!-- Hashtags serão adicionadas aqui dinamicamente -->
                            </div>
                            <div class="mt-2">
                                <span id="hashtagCounter" class="text-sm text-gray-500">0/30</span>
                            </div>
                            <input type="hidden" id="hashtags" name="hashtags" value="{{ old('hashtags') }}">
                            @error('hashtags')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Carousel Texts Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-10 h-10 bg-orange-100 dark:bg-orange-900 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M18 7.5h-.423l-.452-1.09.3-.3a1.5 1.5 0 0 0 0-2.121L16.01 2.575a1.5 1.5 0 0 0-2.121 0l-.3.3L12.5 2.423V2a1.5 1.5 0 0 0-1.5-1.5h-2A1.5 1.5 0 0 0 7.5 2v.423l-1.09.452-.3-.3a1.5 1.5 0 0 0-2.121 0L2.575 3.99a1.5 1.5 0 0 0 0 2.121l.3.3L2.423 7.5H2a1.5 1.5 0 0 0-1.5 1.5v2A1.5 1.5 0 0 0 2 12.5h.423l.452 1.09-.3.3a1.5 1.5 0 0 0 0 2.121l1.415 1.413a1.5 1.5 0 0 0 2.121 0l.3-.3 1.09.452V18A1.5 1.5 0 0 0 9 19.5h2a1.5 1.5 0 0 0 1.5-1.5v-.423l1.09-.452.3.3a1.5 1.5 0 0 0 2.121 0L17.425 16.01a1.5 1.5 0 0 0 0-2.121l-.3-.3.452-1.09H18a1.5 1.5 0 0 0 1.5-1.5V9A1.5 1.5 0 0 0 18 7.5ZM10 14a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Textos do Carrossel</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Textos para as imagens do carrossel (2-10)</p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="mb-3">
                                <div class="flex items-center space-x-2 p-2 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <button type="button" class="p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600" 
                                            onclick="formatCarouselText('bold')">
                                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M5 1a1 1 0 0 0 0 2h.5a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 0 0 2h10a1 1 0 0 0 0-2h-.5a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1H15a1 1 0 0 0 0-2H5Z"/>
                                        </svg>
                                    </button>
                                    <button type="button" class="p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600" 
                                            onclick="formatCarouselText('italic')">
                                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M12.5 3a1 1 0 0 0 0 2h1.382l-4 12H8.5a1 1 0 0 0 0 2h4a1 1 0 0 0 0-2H10.618l4-12H16.5a1 1 0 0 0 0-2h-4Z"/>
                                        </svg>
                                    </button>
                                    <button type="button" class="p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600" 
                                            onclick="formatCarouselText('underline')">
                                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 18h14a1 1 0 0 0 0-2H3a1 1 0 0 0 0 2ZM5 4h10a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1Z"/>
                                        </svg>
                                    </button>
                                    <div class="h-4 w-px bg-gray-300 dark:bg-gray-600"></div>
                                    <button type="button" class="p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600" 
                                            onclick="insertCarouselDivider()" title="Inserir divisor de slide">
                                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12l4-4m-4 4 4 4"/>
                                        </svg>
                                    </button>

                                </div>
                            </div>
                            <textarea id="carouselTextsInput" 
                                      name="carousel_texts_combined" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                      rows="8" 
                                      placeholder="Digite os textos do carrossel. Use '---' para separar cada slide.&#10;&#10;Exemplo:&#10;Primeiro texto do slide&#10;---&#10;Segundo texto do slide&#10;---&#10;Terceiro texto do slide"
                                      oninput="updateCarouselPreview()">{{ old('carousel_texts_combined', $socialPost->carouselTexts->pluck('texto')->filter(function($text) { return !empty(trim($text)); })->implode("\n---\n")) }}</textarea>
                            <div class="mt-2 flex justify-between items-center">
                                <span id="carouselCounter" class="text-sm text-gray-600">0 slides</span>
                                <span class="text-xs text-gray-500">Use '---' para separar os slides</span>
                            </div>
                        </div>
                    </div>

                    <!-- Call-to-Action Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-10 h-10 bg-red-100 dark:bg-red-900 rounded-lg mr-4">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Call-to-Action</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Texto final com chamada para ação</p>
                            </div>
                        </div>

                        <!-- Tipo de Call-to-Action -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-3">
                                Tipo de Call-to-Action
                            </label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="call_to_action_type" value="text" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ !$socialPost->call_to_action_image ? 'checked' : '' }} onchange="toggleCallToActionType()">
                                    <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Texto</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="call_to_action_type" value="image" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" {{ $socialPost->call_to_action_image ? 'checked' : '' }} onchange="toggleCallToActionType()">
                                    <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Imagem</span>
                                </label>
                            </div>
                        </div>

                        <!-- Texto Final -->
                        <div class="mb-6 {{ $socialPost->call_to_action_image ? 'hidden' : '' }}" id="text_call_to_action">
                            <label for="texto_final" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Texto Final
                            </label>
                            <div class="mb-3">
                                <div class="flex items-center space-x-2 p-2 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <button type="button" class="p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600" onclick="formatTextFinal('bold')">
                                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M5 1a1 1 0 0 0 0 2h.5a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 0 0 2h10a1 1 0 0 0 0-2h-.5a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1H15a1 1 0 0 0 0-2H5Z"/>
                                        </svg>
                                    </button>
                                    <button type="button" class="p-2 text-gray-500 rounded cursor-pointer hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-600" onclick="formatTextFinal('italic')">
                                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M12.5 3a1 1 0 0 0 0 2h1.382l-4 12H8.5a1 1 0 0 0 0 2h4a1 1 0 0 0 0-2H10.618l4-12H16.5a1 1 0 0 0 0-2h-4Z"/>
                                        </svg>
                                    </button>


                                </div>
                            </div>
                            <textarea id="texto_final" 
                                      name="texto_final" 
                                      rows="4" 
                                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('texto_final') border-red-500 dark:border-red-500 @enderror" 
                                      placeholder="Digite o call-to-action...">{{ old('texto_final', $socialPost->texto_final) }}</textarea>
                            @error('texto_final')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Imagem Call-to-Action -->
                        <div class="mb-6 {{ !$socialPost->call_to_action_image ? 'hidden' : '' }}" id="image_call_to_action">
                            <label for="call_to_action_image" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Imagem Call-to-Action
                            </label>
                            
                            @if($socialPost->call_to_action_image)
                                <!-- Imagem Atual -->
                                <div class="mb-4" id="current_image">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Imagem atual:</p>
                                    <div class="relative inline-block">
                                        <img src="{{ asset('storage/' . $socialPost->call_to_action_image) }}" alt="Call-to-Action atual" class="max-h-40 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <button type="button" onclick="removeCurrentImage()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                            ×
                                        </button>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex items-center justify-center w-full">
                                <label for="call_to_action_image" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload_placeholder">
                                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Clique para fazer upload</span> ou arraste e solte</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG ou JPEG (MAX. 2MB)</p>
                                    </div>
                                    <div class="hidden" id="image_preview">
                                        <img id="preview_img" src="" alt="Preview" class="max-h-60 rounded-lg">
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 text-center" id="file_name"></p>
                                    </div>
                                    <input id="call_to_action_image" name="call_to_action_image" type="file" class="hidden" accept="image/png,image/jpg,image/jpeg" onchange="previewCallToActionImage(event)" />
                                </label>
                            </div>
                            @error('call_to_action_image')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Status -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h6 class="text-lg font-medium text-gray-900 dark:text-white mb-1">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4ZM3 10a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-6ZM14 9a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-6a1 1 0 0 0-1-1h-2Z"/>
                            </svg>
                            Status
                        </h6>
                    </div>
                    <div class="px-6 py-4">
                        <div class="mb-4">
                            <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('status') border-red-500 dark:border-red-500 @enderror" 
                                    id="status" name="status" required>
                                <option value="rascunho" {{ old('status', $socialPost->status) == 'rascunho' ? 'selected' : '' }}>Rascunho</option>
                                <option value="publicado" {{ old('status', $socialPost->status) == 'publicado' ? 'selected' : '' }}>Publicado</option>
                                <option value="arquivado" {{ old('status', $socialPost->status) == 'arquivado' ? 'selected' : '' }}>Arquivado</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Agendamento -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h6 class="text-lg font-medium text-gray-900 dark:text-white mb-1">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5.5 13.5A1.5 1.5 0 0 1 4 12V4a1.5 1.5 0 0 1 1.5-1.5h9A1.5 1.5 0 0 1 16 4v8a1.5 1.5 0 0 1-1.5 1.5h-9ZM4 14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1H4v1Z"/>
                                <path d="M6 6.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5ZM6 8.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5ZM6 10.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5Z"/>
                            </svg>
                            Agendamento
                        </h6>
                    </div>
                    <div class="px-6 py-4">
                        <div class="mb-4">
                            <label for="scheduled_date" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Data de Publicação
                            </label>
                            <input type="date" 
                                   id="scheduled_date" 
                                   name="scheduled_date" 
                                   value="{{ old('scheduled_date', $socialPost->scheduled_date) }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('scheduled_date') border-red-500 dark:border-red-500 @enderror">
                            @error('scheduled_date')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="scheduled_time" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Horário de Publicação
                            </label>
                            <input type="time" 
                                   id="scheduled_time" 
                                   name="scheduled_time" 
                                   value="{{ old('scheduled_time', $socialPost->scheduled_time) }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('scheduled_time') border-red-500 dark:border-red-500 @enderror">
                            @error('scheduled_time')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h6 class="text-lg font-medium text-gray-900 dark:text-white mb-1">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/>
                                <path d="M17.5 9c-.215 0-.42.021-.625.05a8.5 8.5 0 0 0-13.75 0A2.5 2.5 0 0 0 2.5 11.5v.5A2.5 2.5 0 0 0 5 14.5h.5a8.5 8.5 0 0 0 13 0h.5a2.5 2.5 0 0 0 2.5-2.5v-.5a2.5 2.5 0 0 0-.625-1.5Z"/>
                            </svg>
                            Preview
                        </h6>
                    </div>
                    <div class="px-6 py-4">
                        <div id="postPreview" class="border border-gray-200 dark:border-gray-600 rounded-md p-4 bg-gray-50 dark:bg-gray-700">
                            <div class="text-gray-500 dark:text-gray-400 text-center py-3">
                                <svg class="w-8 h-8 mx-auto mb-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H2ZM2 5h16v10H2V5Z"/>
                                </svg>
                                <p class="mb-0">Preview do post aparecerá aqui</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="px-6 py-4">
                        <div class="space-y-3">
                            <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg class="w-4 h-4 mr-2 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z"/>
                                    <path d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1.969 1.969 0 0 0 1.933 2h12.134A1.97 1.97 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.513.81l-3.4.679a2.947 2.947 0 0 1-1.85-.227 2.96 2.96 0 0 1-1.635-3.257l.681-3.397Z"/>
                                    <path d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.679 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.359-4.236 4.237-1.7.339.341-1.699Z"/>
                                </svg>
                                Atualizar Post
                            </button>
                            <button type="button" class="w-full text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" onclick="saveDraft()">
                                <svg class="w-4 h-4 mr-2 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z"/>
                                    <path d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1.969 1.969 0 0 0 1.933 2h12.134A1.97 1.97 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.513.81l-3.4.679a2.947 2.947 0 0 1-1.85-.227 2.96 2.96 0 0 1-1.635-3.257l.681-3.397Z"/>
                                    <path d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.679 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.359-4.236 4.237-1.7.339.341-1.699Z"/>
                                </svg>
                                Salvar como Rascunho
                            </button>
                            <a href="{{ route('social-posts.index') }}" class="w-full text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 inline-flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal de Emoji Picker -->
<div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50" id="emojiModal">
    <div class="relative top-4 mx-auto p-4 border w-11/12 sm:w-10/12 md:w-8/12 lg:w-6/12 xl:w-4/12 max-w-md shadow-lg rounded-lg bg-white dark:bg-gray-800">
        <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-700">
            <h5 class="text-lg font-medium text-gray-900 dark:text-white">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM13.5 6a1.5 1.5 0 1 1-1.5 1.5A1.5 1.5 0 0 1 13.5 6ZM10 15.5a5.5 5.5 0 0 1-4.6-2.5 1 1 0 0 1 1.7-1 3.5 3.5 0 0 0 5.8 0 1 1 0 0 1 1.7 1 5.5 5.5 0 0 1-4.6 2.5ZM6.5 6a1.5 1.5 0 1 1 1.5 1.5A1.5 1.5 0 0 1 6.5 6Z"/>
                </svg>
                Selecionar Emoji
            </h5>
            <button type="button" class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100" onclick="closeEmojiModal()">
                <span class="sr-only">Fechar</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Search Input -->
        <div class="py-4">
            <div class="mb-4">
                <input type="text" 
                       id="emojiSearch" 
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                       placeholder="Buscar emoji..." 
                       onkeyup="filterEmojis()">
            </div>
            
            <!-- Emoji Categories -->
            <div class="flex space-x-2 mb-4 overflow-x-auto pb-2">
                <button type="button" class="emoji-category-btn px-3 py-1 text-sm font-medium rounded-lg bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 whitespace-nowrap" data-category="all" onclick="filterEmojisByCategory('all')">
                    Todos
                </button>
                <button type="button" class="emoji-category-btn px-3 py-1 text-sm font-medium rounded-lg bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 whitespace-nowrap" data-category="smileys" onclick="filterEmojisByCategory('smileys')">
                    😀 Rostos
                </button>
                <button type="button" class="emoji-category-btn px-3 py-1 text-sm font-medium rounded-lg bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 whitespace-nowrap" data-category="people" onclick="filterEmojisByCategory('people')">
                    👥 Pessoas
                </button>
                <button type="button" class="emoji-category-btn px-3 py-1 text-sm font-medium rounded-lg bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 whitespace-nowrap" data-category="nature" onclick="filterEmojisByCategory('nature')">
                    🌿 Natureza
                </button>
                <button type="button" class="emoji-category-btn px-3 py-1 text-sm font-medium rounded-lg bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 whitespace-nowrap" data-category="food" onclick="filterEmojisByCategory('food')">
                    🍕 Comida
                </button>
                <button type="button" class="emoji-category-btn px-3 py-1 text-sm font-medium rounded-lg bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 whitespace-nowrap" data-category="activities" onclick="filterEmojisByCategory('activities')">
                    ⚽ Atividades
                </button>
                <button type="button" class="emoji-category-btn px-3 py-1 text-sm font-medium rounded-lg bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 whitespace-nowrap" data-category="objects" onclick="filterEmojisByCategory('objects')">
                    💎 Objetos
                </button>
            </div>
            
            <!-- Emoji Grid -->
            <div id="emojiGrid" class="grid grid-cols-6 sm:grid-cols-8 gap-1 sm:gap-2 max-h-48 sm:max-h-64 overflow-y-auto p-2 sm:p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                <!-- Emojis serão carregados aqui -->
            </div>
        </div>
        
        <div class="flex justify-end pt-3 border-t border-gray-200 dark:border-gray-700">
            <button type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700" onclick="closeEmojiModal()">Fechar</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite(['resources/js/social-post-form.js'])
    <script>
        // Inicializar dados existentes para edição
        document.addEventListener('DOMContentLoaded', function() {
            // Carregar hashtags existentes
            @if($socialPost->hashtags->count() > 0)
                const existingHashtags = @json($socialPost->hashtags->pluck('name')->toArray());
                
                // Aguardar um pouco para garantir que o JS foi carregado
                setTimeout(function() {
                    // Garantir que selectedHashtags seja sempre um array
                    if (typeof selectedHashtags === 'undefined' || !Array.isArray(selectedHashtags)) {
                        window.selectedHashtags = [];
                    }
                    
                    // Limpar e adicionar hashtags existentes
                    selectedHashtags.length = 0;
                    existingHashtags.forEach(function(hashtag) {
                        selectedHashtags.push(hashtag);
                    });
                    
                    if (typeof updateHashtagDisplay === 'function') {
                        updateHashtagDisplay();
                    }
                    
                    // Renderizar hashtags diretamente como fallback
                    const selectedHashtagsDiv = document.getElementById('selectedHashtags');
                    if (selectedHashtagsDiv && selectedHashtagsDiv.innerHTML.trim() === '') {
                        let hashtagsHTML = '';
                        existingHashtags.forEach(function(hashtag) {
                            hashtagsHTML += `
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 mr-2 mb-2">
                                    #${hashtag}
                                    <button type="button" class="ml-1 text-blue-600 hover:text-blue-800 dark:text-blue-300 dark:hover:text-blue-100" onclick="removeHashtag('${hashtag}')">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </span>`;
                        });
                        selectedHashtagsDiv.innerHTML = hashtagsHTML;
                    }
                    
                    // Atualizar preview inicial
                    if (typeof updatePreview === 'function') updatePreview();
                    if (typeof updateCarouselPreview === 'function') updateCarouselPreview();
                }, 100);
            @endif
        });
    </script>
@endpush