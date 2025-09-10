@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-3">
                            <li class="inline-flex items-center">
                                <a href="{{ route('portfolio.works.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Trabalhos
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $work->title }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">Editar Trabalho</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $work->title }}</p>
                </div>
                
                <div class="flex space-x-3">
                    @if($work->status === 'published')
                        <a href="{{ route('portfolio.public.work', $work->slug) }}" target="_blank"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Ver Público
                        </a>
                    @endif
                    
                    <form action="{{ route('portfolio.works.destroy', $work) }}" method="POST" class="inline"
                          onsubmit="return confirm('Tem certeza que deseja excluir este trabalho?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <form action="{{ route('portfolio.works.update', $work) }}" method="POST" enctype="multipart/form-data" 
              x-data="workForm()" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Progress Steps -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <nav aria-label="Progress">
                    <ol class="flex items-center">
                        <li class="relative" :class="currentStep >= 1 ? 'text-blue-600' : 'text-gray-500'">
                            <button type="button" @click="setStep(1)" class="flex items-center">
                                <span class="flex items-center justify-center w-8 h-8 border-2 rounded-full" 
                                      :class="currentStep >= 1 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300'">
                                    1
                                </span>
                                <span class="ml-2 text-sm font-medium">Informações Básicas</span>
                            </button>
                        </li>
                        
                        <li class="relative ml-8" :class="currentStep >= 2 ? 'text-blue-600' : 'text-gray-500'">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="h-0.5 w-full bg-gray-200 dark:bg-gray-700" :class="currentStep >= 2 ? 'bg-blue-600' : ''"></div>
                            </div>
                            <button type="button" @click="setStep(2)" class="relative flex items-center bg-white dark:bg-gray-800">
                                <span class="flex items-center justify-center w-8 h-8 border-2 rounded-full" 
                                      :class="currentStep >= 2 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300'">
                                    2
                                </span>
                                <span class="ml-2 text-sm font-medium">Imagens</span>
                            </button>
                        </li>
                        
                        <li class="relative ml-8" :class="currentStep >= 3 ? 'text-blue-600' : 'text-gray-500'">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="h-0.5 w-full bg-gray-200 dark:bg-gray-700" :class="currentStep >= 3 ? 'bg-blue-600' : ''"></div>
                            </div>
                            <button type="button" @click="setStep(3)" class="relative flex items-center bg-white dark:bg-gray-800">
                                <span class="flex items-center justify-center w-8 h-8 border-2 rounded-full" 
                                      :class="currentStep >= 3 ? 'border-blue-600 bg-blue-600 text-white' : 'border-gray-300'">
                                    3
                                </span>
                                <span class="ml-2 text-sm font-medium">SEO & Publicação</span>
                            </button>
                        </li>
                    </ol>
                </nav>
            </div>
            
            <!-- Step 1: Basic Information -->
            <div x-show="currentStep === 1" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Informações Básicas</h2>
                
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $work->title) }}" required
                           x-model="form.title" @input="generateSlug()"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-300 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $work->slug) }}"
                           x-model="form.slug"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-300 @enderror">
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">URL amigável</p>
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição Curta</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-300 @enderror">{{ old('description', $work->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Conteúdo Completo</label>
                    <textarea name="content" id="content" rows="8"
                              class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-300 @enderror">{{ old('content', $work->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Category -->
                <div>
                    <label for="portfolio_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria *</label>
                    <select name="portfolio_category_id" id="portfolio_category_id" required
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('portfolio_category_id') border-red-300 @enderror">
                        <option value="">Selecione uma categoria</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('portfolio_category_id', $work->portfolio_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('portfolio_category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Client -->
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cliente</label>
                    <select name="client_id" id="client_id"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('client_id') border-red-300 @enderror">
                        <option value="">Selecione um cliente (opcional)</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $work->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Authors -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Autores</label>
                    <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-md p-3">
                        @foreach($authors as $author)
                            <label class="flex items-center">
                                <input type="checkbox" name="authors[]" value="{{ $author->id }}"
                                       {{ in_array($author->id, old('authors', $work->authors->pluck('id')->toArray())) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $author->nome }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('authors')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Project Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="project_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL do Projeto</label>
                        <input type="url" name="project_url" id="project_url" value="{{ old('project_url', $work->project_url) }}"
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('project_url') border-red-300 @enderror">
                        @error('project_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="completion_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Conclusão</label>
                        <input type="date" name="completion_date" id="completion_date" value="{{ old('completion_date', $work->completion_date?->format('Y-m-d')) }}"
                               class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('completion_date') border-red-300 @enderror">
                        @error('completion_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Technologies -->
                <div>
                    <label for="technologies" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tecnologias Utilizadas</label>
                    <input type="text" name="technologies" id="technologies" value="{{ old('technologies', $work->technologies) }}"
                           placeholder="Ex: Laravel, Vue.js, Tailwind CSS"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('technologies') border-red-300 @enderror">
                    @error('technologies')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Step 2: Images -->
            <div x-show="currentStep === 2" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Imagens do Trabalho</h2>
                
                <!-- Current Images -->
                @if($work->images->count() > 0)
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Imagens Atuais</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" x-data="{ imagesToDelete: [] }">
                            @foreach($work->images as $image)
                                <div class="relative group" x-data="{ marked: false }">
                                    <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->alt_text }}" class="w-full h-32 object-cover rounded-lg" :class="marked ? 'opacity-50' : ''">
                                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                        <button type="button" @click="marked = !marked; toggleImageForDeletion({{ $image->id }})"
                                                class="text-white hover:text-red-300" :class="marked ? 'text-red-400' : ''">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="absolute top-2 left-2">
                                        <span class="bg-blue-600 text-white text-xs px-2 py-1 rounded">{{ $loop->iteration }}</span>
                                        @if($image->is_cover)
                                            <span class="bg-green-600 text-white text-xs px-2 py-1 rounded ml-1">Capa</span>
                                        @endif
                                    </div>
                                    <div x-show="marked" class="absolute top-2 right-2">
                                        <span class="bg-red-600 text-white text-xs px-2 py-1 rounded">Excluir</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="delete_images" id="delete_images" value="">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Clique nas imagens que deseja excluir</p>
                    </div>
                @endif
                
                <!-- Add New Images -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Adicionar Novas Imagens</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors"
                         @dragover.prevent @drop.prevent="handleDrop($event)">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                <label for="images" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Clique para selecionar</span>
                                    <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" @change="handleFileSelect($event)">
                                </label>
                                <p class="pl-1">ou arraste e solte</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF até 10MB cada</p>
                        </div>
                    </div>
                    @error('images')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- New Images Preview -->
                <div x-show="selectedImages.length > 0" class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Novas Imagens</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <template x-for="(image, index) in selectedImages" :key="index">
                            <div class="relative group">
                                <img :src="image.preview" :alt="image.name" class="w-full h-32 object-cover rounded-lg">
                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                    <button type="button" @click="removeImage(index)" class="text-white hover:text-red-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="absolute top-2 left-2">
                                    <span class="bg-green-600 text-white text-xs px-2 py-1 rounded">Nova</span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Step 3: SEO & Publication -->
            <div x-show="currentStep === 3" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">SEO & Publicação</h2>
                
                <!-- Meta Title -->
                <div>
                    <label for="meta_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Título (SEO)</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $work->meta_title) }}"
                           class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('meta_title') border-red-300 @enderror">
                    @error('meta_title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Meta Description -->
                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Descrição (SEO)</label>
                    <textarea name="meta_description" id="meta_description" rows="3"
                              class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('meta_description') border-red-300 @enderror">{{ old('meta_description', $work->meta_description) }}</textarea>
                    @error('meta_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status Options -->
                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Opções de Publicação</h3>
                    
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="status" value="draft" {{ old('status', $work->status) === 'draft' ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Rascunho - Não visível no site público</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="status" value="published" {{ old('status', $work->status) === 'published' ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Publicado - Visível no site público</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- Featured -->
                    <div>
                        <div class="flex items-center">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $work->is_featured) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="is_featured" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Trabalho em destaque</label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Trabalhos em destaque aparecem em posição de destaque no site</p>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Buttons -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex justify-between">
                    <button type="button" @click="previousStep()" x-show="currentStep > 1"
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition-colors">
                        Anterior
                    </button>
                    
                    <div class="flex space-x-3">
                        <a href="{{ route('portfolio.works.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition-colors">
                            Cancelar
                        </a>
                        
                        <button type="button" @click="nextStep()" x-show="currentStep < 3"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            Próximo
                        </button>
                        
                        <button type="submit" x-show="currentStep === 3"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition-colors">
                            Salvar Alterações
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function workForm() {
    return {
        currentStep: 1,
        selectedImages: [],
        imagesToDelete: [],
        form: {
            title: '{{ $work->title }}',
            slug: '{{ $work->slug }}'
        },
        
        setStep(step) {
            this.currentStep = step;
        },
        
        nextStep() {
            if (this.currentStep < 3) {
                this.currentStep++;
            }
        },
        
        previousStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
            }
        },
        
        generateSlug() {
            if (!this.form.slug || this.slugAutoGenerated) {
                this.form.slug = this.form.title
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '') // Remove accents
                    .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                    .replace(/\s+/g, '-') // Replace spaces with hyphens
                    .replace(/-+/g, '-') // Replace multiple hyphens with single
                    .replace(/^-|-$/g, ''); // Remove leading/trailing hyphens
                
                document.getElementById('slug').value = this.form.slug;
                this.slugAutoGenerated = true;
            }
        },
        
        handleFileSelect(event) {
            this.processFiles(event.target.files);
        },
        
        handleDrop(event) {
            this.processFiles(event.dataTransfer.files);
        },
        
        processFiles(files) {
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.selectedImages.push({
                            file: file,
                            name: file.name,
                            preview: e.target.result
                        });
                    };
                    reader.readAsDataURL(file);
                }
            });
            
            this.updateFileInput();
        },
        
        removeImage(index) {
            this.selectedImages.splice(index, 1);
            this.updateFileInput();
        },
        
        updateFileInput() {
            const dt = new DataTransfer();
            this.selectedImages.forEach(image => {
                dt.items.add(image.file);
            });
            document.getElementById('images').files = dt.files;
        },
        
        toggleImageForDeletion(imageId) {
            const index = this.imagesToDelete.indexOf(imageId);
            if (index > -1) {
                this.imagesToDelete.splice(index, 1);
            } else {
                this.imagesToDelete.push(imageId);
            }
            document.getElementById('delete_images').value = this.imagesToDelete.join(',');
        }
    }
}
</script>
@endpush
@endsection