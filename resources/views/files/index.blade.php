@extends('layouts.app')

@section('title', 'Gestão de Arquivos')

@section('content')

@include('components.error-modal')
<div class="container mx-auto px-4 py-6">

    <!-- Tags de Navegação Rápida -->
    <div class="flex flex-wrap gap-2 mb-6">
        <span class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full bg-blue-600 text-white">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 012-2h6l2 2h6a2 2 0 012 2v1"></path>
            </svg>
            Meus Arquivos
        </span>
        <a href="{{ route('files.create') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            Upload
        </a>
        <a href="{{ route('file-categories.index') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700 transition-colors duration-200">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            Categorias
        </a>
    </div>

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestão de Arquivos</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Gerencie seus arquivos e compartilhe com links seguros</p>
        </div>
    </div>



    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-emerald-100">Total de Arquivos</p>
                    <p class="text-2xl font-bold text-white">{{ $files->total() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 012-2h6l2 2h6a2 2 0 012 2v1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-100">Com Categoria</p>
                    <p class="text-2xl font-bold text-white">{{ $files->where('category_id', '!=', null)->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-amber-100">Sem Categoria</p>
                    <p class="text-2xl font-bold text-white">{{ $files->where('category_id', null)->count() }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 012-2h6l2 2h6a2 2 0 012 2v1"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>


    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
        <!-- Filtro de busca completo -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="flex-1 relative">
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Buscar arquivos por nome..."
                           class="w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    @if(request()->filled('search'))
                        <button type="button" id="clearSearch" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Filtros Avançados -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-wrap gap-2 mb-3">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Filtros por Tipo:</span>
                <button class="type-filter-btn px-3 py-1 rounded-full text-xs font-medium border-2 active" 
                        data-type="all" 
                        style="background-color: #f3f4f6; color: #374151; border-color: #d1d5db;">
                    Todos
                </button>
                <button class="type-filter-btn px-3 py-1 rounded-full text-xs font-medium border-2" 
                        data-type="permanent" 
                        style="background-color: #10b98120; color: #10b981; border-color: #10b98140;">
                    Permanentes
                </button>
                <button class="type-filter-btn px-3 py-1 rounded-full text-xs font-medium border-2" 
                        data-type="temporary" 
                        style="background-color: #f59e0b20; color: #f59e0b; border-color: #f59e0b40;">
                    Temporários
                </button>
                <button class="type-filter-btn px-3 py-1 rounded-full text-xs font-medium border-2" 
                        data-type="expiring" 
                        style="background-color: #ef444420; color: #ef4444; border-color: #ef444440;">
                    Próximos ao Vencimento
                </button>
            </div>
        </div>
        
        <!-- Quick Category Filter -->
        <div class="p-4">
            <div class="flex flex-wrap gap-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">Categorias:</span>
                <button class="category-filter-btn px-3 py-1 rounded-full text-xs font-medium border-2 active" 
                        data-category="all" 
                        style="background-color: #f3f4f6; color: #374151; border-color: #d1d5db;">
                    Todas
                </button>
                @foreach($categories as $category)
                    <button class="category-filter-btn px-3 py-1 rounded-full text-xs font-medium border-2" 
                            data-category="{{ $category->id }}" 
                            style="background-color: {{ $category->color }}20; color: {{ $category->color }}; border-color: {{ $category->color }}40;">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Files Grid -->
    <div id="files-container">
        @if($files->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($files as $file)
                    @php
                        $pulseClass = '';
                        $pulseSpeed = '';
                        if($file->is_temporary && $file->expires_at) {
                            $now = now();
                            $expiresAt = \Carbon\Carbon::parse($file->expires_at);
                            $daysUntilExpiry = $now->diffInDays($expiresAt, false);
                            
                            if($expiresAt->isPast()) {
                                $pulseClass = 'pulse-expired';
                                $pulseSpeed = '0.5s';
                            } elseif($daysUntilExpiry <= 1) {
                                $pulseClass = 'pulse-critical';
                                $pulseSpeed = '0.8s';
                            } elseif($daysUntilExpiry <= 3) {
                                $pulseClass = 'pulse-warning';
                                $pulseSpeed = '1.5s';
                            } elseif($daysUntilExpiry <= 7) {
                                $pulseClass = 'pulse-caution';
                                $pulseSpeed = '2.5s';
                            } else {
                                $pulseClass = 'pulse-safe';
                                $pulseSpeed = '4s';
                            }
                        }
                    @endphp
                    
                    <div class="file-card rounded-lg shadow-md hover:shadow-lg transition-all duration-200 overflow-hidden flex flex-col {{ $pulseClass }}" 
                         data-category="{{ $file->category_id }}" 
                         data-name="{{ strtolower($file->original_name) }}" 
                         data-created="{{ $file->created_at->timestamp }}" 
                         data-size="{{ $file->file_size }}"
                         data-temporary="{{ $file->is_temporary ? 'true' : 'false' }}"
                         data-expires="{{ $file->expires_at ? $file->expires_at->toISOString() : '' }}"
                         @if($file->is_temporary && $file->expires_at)
                             style="background: linear-gradient(135deg, {{ $file->category ? $file->category->color : '#ffffff' }}08 0%, {{ $file->category ? $file->category->color : '#ffffff' }}15 100%); animation-duration: {{ $pulseSpeed }};"
                         @elseif($file->category)
                             style="background: linear-gradient(135deg, {{ $file->category->color }}08 0%, {{ $file->category->color }}15 100%);"
                         @else
                             style="background: #ffffff; "
                         @endif
                         class="dark:bg-gray-800">
                        
                        <!-- File Header -->
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    @php
                                        $extension = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
                                        $iconConfig = [
                                            // Archive files
                                            'zip' => ['color' => 'bg-yellow-500', 'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 012-2h6l2 2h6a2 2 0 012 2v1'],
                                            'rar' => ['color' => 'bg-orange-500', 'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 012-2h6l2 2h6a2 2 0 012 2v1'],
                                            '7z' => ['color' => 'bg-amber-500', 'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 012-2h6l2 2h6a2 2 0 012 2v1'],
                                            // PDF files
                                            'pdf' => ['color' => 'bg-red-500', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                                            // Document files
                                            'doc' => ['color' => 'bg-blue-600', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                                            'docx' => ['color' => 'bg-blue-600', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                                            // Spreadsheet files
                                            'xls' => ['color' => 'bg-green-600', 'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 012-2h6l2 2h6a2 2 0 012 2v1'],
                                            'xlsx' => ['color' => 'bg-green-600', 'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 012-2h6l2 2h6a2 2 0 012 2v1'],
                                            'csv' => ['color' => 'bg-green-500', 'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 012-2h6l2 2h6a2 2 0 012 2v1'],
                                            // Presentation files
                                            'ppt' => ['color' => 'bg-orange-600', 'icon' => 'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2'],
                                            'pptx' => ['color' => 'bg-orange-600', 'icon' => 'M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2'],
                                            // Text files
                                            'txt' => ['color' => 'bg-gray-500', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                                            'rtf' => ['color' => 'bg-gray-600', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                                            // Audio files
                                            'mp3' => ['color' => 'bg-purple-500', 'icon' => 'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3'],
                                            'wav' => ['color' => 'bg-purple-600', 'icon' => 'M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3'],
                                            // Video files
                                            'mp4' => ['color' => 'bg-indigo-500', 'icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
                                            'avi' => ['color' => 'bg-indigo-600', 'icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
                                            'mov' => ['color' => 'bg-indigo-700', 'icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
                                            // Code files
                                            'html' => ['color' => 'bg-orange-500', 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
                                            'css' => ['color' => 'bg-blue-500', 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
                                            'js' => ['color' => 'bg-yellow-400', 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
                                            'php' => ['color' => 'bg-purple-600', 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
                                            'json' => ['color' => 'bg-gray-700', 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4'],
                                        ];
                                        
                                        // Get icon configuration based on extension
                                        if (str_starts_with($file->mime_type, 'image/')) {
                                            $config = ['color' => 'bg-pink-500', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'];
                                        } else {
                                            $config = $iconConfig[$extension] ?? ['color' => 'bg-gray-500', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'];
                                        }
                                    @endphp
                                    
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white {{ $config['color'] }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate" title="{{ $file->original_name }}">{{ $file->original_name }}</h3>
                                        <div class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                                            <span>{{ $file->fileSizeFormatted }}</span>
                                            <span>&bull;</span>
                                            <span>{{ $file->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Badges Row -->
                            <div class="flex flex-wrap gap-1 mb-3">
                                <!-- Status Badge -->
                                @if($file->is_temporary && $file->expires_at)
                                    @php
                                        $now = now();
                                        $expiresAt = \Carbon\Carbon::parse($file->expires_at);
                                        $hoursUntilExpiry = $now->diffInHours($expiresAt, false);
                                        $daysUntilExpiry = $now->diffInDays($expiresAt, false);
                                    @endphp
                                    
                                    @if($expiresAt->isPast())
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Expirado
                                        </span>
                                    @elseif($hoursUntilExpiry <= 24)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            Expira em {{ $hoursUntilExpiry }}h
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $daysUntilExpiry }} {{ $daysUntilExpiry == 1 ? 'dia restante' : 'dias restantes' }}
                                        </span>
                                        <button onclick="extendExpiration({{ $file->id }})" 
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200 hover:bg-orange-200 dark:hover:bg-orange-800 transition-colors cursor-pointer" 
                                                title="Estender Prazo">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Estender
                                        </button>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Permanente
                                    </span>
                                @endif
                                
                                @if($file->category)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium" style="background-color: {{ $file->category->color }}20; color: {{ $file->category->color }}">
                                        <div class="w-2 h-2 rounded-full mr-1" style="background-color: {{ $file->category->color }}"></div>
                                        {{ $file->category->name }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Flex grow para empurrar os botões para o rodapé -->
                        <div class="flex-grow"></div>

                        <!-- Actions Footer -->
                        <div class="flex items-center justify-center px-2 py-3 border-t border-gray-200 dark:border-gray-700 mt-auto">
                            <div class="flex justify-center items-center gap-1 w-full">
                                <!-- Download -->
                                <button onclick="downloadFile('{{ route('files.download', $file) }}')" 
                                        class="flex items-center justify-center w-7 h-7 text-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md" 
                                        title="Download">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </button>
                                
                                <!-- Share -->
                                <button onclick="shareFile({{ $file->id }})" 
                                        class="flex items-center justify-center w-7 h-7 text-green-500 hover:text-green-600 dark:hover:text-green-400 transition-colors hover:bg-green-50 dark:hover:bg-green-900/20 rounded-md" 
                                        title="Compartilhar">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                    </svg>
                                </button>
                                
                                <!-- Copy Link (sempre presente) -->
                                <button onclick="copyToClipboard('{{ route('files.show', $file) }}')" 
                                        class="flex items-center justify-center w-7 h-7 text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 transition-colors hover:bg-gray-100 dark:hover:bg-gray-600 rounded-md" 
                                        title="Copiar Link">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                                
                                <!-- View Button -->
                                <a href="{{ route('files.show', $file) }}" 
                                   class="flex items-center justify-center w-7 h-7 text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 transition-colors hover:bg-gray-100 dark:hover:bg-gray-600 rounded-md" 
                                   title="Ver Detalhes">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </a>
                                
                                <!-- Convert -->
                                @if($file->is_temporary)
                                    <button onclick="convertToPermanent({{ $file->id }})" 
                                            class="flex items-center justify-center w-7 h-7 text-purple-500 hover:text-purple-600 dark:hover:text-purple-400 transition-colors hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-md" 
                                            title="Converter para Permanente">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                        </svg>
                                    </button>
                                @else
                                    <button onclick="convertToTemporary({{ $file->id }})" 
                                            class="flex items-center justify-center w-7 h-7 text-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-md" 
                                            title="Converter para Temporário">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                @endif
                                
                                <!-- Delete -->
                                <button onclick="deleteFile({{ $file->id }})" 
                                        class="flex items-center justify-center w-7 h-7 text-red-500 hover:text-red-600 dark:hover:text-red-400 transition-colors hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md" 
                                        title="Excluir">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-6">
                {{ $files->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="bg-gray-100 dark:bg-gray-700 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 012-2h6l2 2h6a2 2 0 012 2v1"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-300 mb-2">Nenhum arquivo encontrado</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Comece fazendo upload do seu primeiro arquivo</p>
                <a href="{{ route('files.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Fazer Upload
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Floating Action Button -->
<div class="fixed bottom-6 right-6 z-50">
    <a href="{{ route('files.create') }}" 
       class="flex items-center justify-center w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-200 group">
        <svg class="w-6 h-6 group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
    </a>
</div>

<!-- Share Modal -->
<div id="shareModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-75 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 dark:text-white">Compartilhar Arquivo</h3>
                <form id="shareForm" method="POST" class="space-y-4 no-loading">
                    @csrf
                    <input type="hidden" id="shareFileId" name="file_id">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Data de Expiração</label>
                        <input type="datetime-local" id="expiresAt" name="expires_at" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Limite de Downloads</label>
                        <input type="number" id="downloadLimit" name="download_limit" min="1" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Senha (opcional)</label>
                        <input type="password" id="password" name="password" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeShareModal()" 
                                class="px-4 py-2 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                            Criar Link
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Share Link Result Modal -->
<div id="shareLinkModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-75 hidden" style="z-index: 10003;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 text-green-600 dark:text-green-400">Link Compartilhado Criado</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-4">Seu link de compartilhamento foi criado com sucesso!</p>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Link de Compartilhamento:</label>
                    <div class="flex">
                        <input type="text" id="shareUrlInput" readonly 
                               class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-md bg-gray-50 dark:bg-gray-700 dark:text-white text-sm">
                        <button type="button" onclick="copyShareUrl(this)" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-sm">
                            Copiar
                        </button>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeShareLinkModal()" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-75 hidden" style="z-index: 10003;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 text-red-600 dark:text-red-400">Confirmar Exclusão</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-6">Tem certeza que deseja excluir este arquivo? Esta ação não pode ser desfeita.</p>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button type="button" onclick="confirmDelete()" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800">
                        Confirmar Exclusão
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Extend Expiration Modal -->
<div id="extendModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-75 hidden" style="z-index: 10003;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 text-blue-600 dark:text-blue-400">Estender Prazo de Expiração</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-4">Por quantos dias deseja estender a expiração deste arquivo?</p>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dias para estender:</label>
                    <input type="number" id="extendDays" min="1" max="365" value="7" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeExtendModal()" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button type="button" onclick="confirmExtend()" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800">
                        Estender Prazo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Convert to Permanent Modal -->
<div id="convertPermanentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-75 hidden" style="z-index: 10003;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 text-green-600 dark:text-green-400">Converter para Permanente</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-6">Deseja converter este arquivo temporário para permanente? O arquivo não terá mais data de expiração.</p>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeConvertPermanentModal()" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button type="button" onclick="confirmConvertPermanent()" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800">
                        Converter para Permanente
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Convert to Temporary Modal -->
<div id="convertTemporaryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 dark:bg-gray-900 dark:bg-opacity-75 hidden" style="z-index: 10003;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 text-orange-600 dark:text-orange-400">Converter para Temporário</h3>
                <p class="text-gray-700 dark:text-gray-300 mb-4">Por quantos dias este arquivo deve permanecer disponível?</p>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dias de validade:</label>
                    <input type="number" id="temporaryDays" min="1" max="365" value="30" 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeConvertTemporaryModal()" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button type="button" onclick="confirmConvertTemporary()" 
                            class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700 dark:bg-orange-700 dark:hover:bg-orange-800">
                        Converter para Temporário
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.category-filter-btn.active {
    background-color: #3b82f6 !important;
    color: white !important;
    border-color: #3b82f6 !important;
}

.category-filter-btn:hover {
    opacity: 0.8;
}

/* Efeitos de pulsação para arquivos temporários */
@keyframes pulse-wave-safe {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
    }
    50% {
        box-shadow: 0 0 0 8px rgba(34, 197, 94, 0.1), 0 0 0 16px rgba(34, 197, 94, 0.05);
    }
}

@keyframes pulse-wave-caution {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.5);
    }
    50% {
        box-shadow: 0 0 0 10px rgba(251, 191, 36, 0.15), 0 0 0 20px rgba(251, 191, 36, 0.08);
    }
}

@keyframes pulse-wave-warning {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.6);
    }
    50% {
        box-shadow: 0 0 0 12px rgba(249, 115, 22, 0.2), 0 0 0 24px rgba(249, 115, 22, 0.1);
    }
}

@keyframes pulse-wave-critical {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
    }
    50% {
        box-shadow: 0 0 0 14px rgba(239, 68, 68, 0.25), 0 0 0 28px rgba(239, 68, 68, 0.12);
    }
}

@keyframes pulse-wave-expired {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(127, 29, 29, 0.8);
    }
    50% {
        box-shadow: 0 0 0 16px rgba(127, 29, 29, 0.3), 0 0 0 32px rgba(127, 29, 29, 0.15);
    }
}

/* Classes para aplicar as animações */
.pulse-safe {
    animation: pulse-wave-safe infinite ease-in-out;
}

.pulse-caution {
    animation: pulse-wave-caution infinite ease-in-out;
}

.pulse-warning {
    animation: pulse-wave-warning infinite ease-in-out;
}

.pulse-critical {
    animation: pulse-wave-critical infinite ease-in-out;
}

.pulse-expired {
    animation: pulse-wave-expired infinite ease-in-out;
}

/* Efeito hover para cartões temporários */
.file-card.pulse-safe:hover,
.file-card.pulse-caution:hover,
.file-card.pulse-warning:hover,
.file-card.pulse-critical:hover,
.file-card.pulse-expired:hover {
    animation-play-state: paused;
}
</style>
@endpush

@push('scripts')
<script>
// Search and filter functionality
const searchInput = document.getElementById('search');
const clearSearchBtn = document.getElementById('clearSearch');
let activeCategory = 'all';

// Add event listeners only if elements exist
if (searchInput) {
    searchInput.addEventListener('input', filterFiles);
}

if (clearSearchBtn) {
    clearSearchBtn.addEventListener('click', function() {
        if (searchInput) {
            searchInput.value = '';
            filterFiles();
        }
    });
}

// Category filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const categoryButtons = document.querySelectorAll('.category-filter-btn');
    
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            categoryButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Update active category
            activeCategory = this.dataset.category;
            
            // Apply filters
            filterFiles();
        });
    });
});

function filterFiles() {
    const searchElement = document.getElementById('search');
    const search = searchElement ? searchElement.value.toLowerCase() : '';
    const cards = document.querySelectorAll('.file-card');
    
    // Filter cards based on search and category
    cards.forEach(card => {
        const name = card.dataset.name || '';
        const category = card.dataset.category || '';
        
        const matchesSearch = name.includes(search);
        const matchesCategory = activeCategory === 'all' || category === activeCategory;
        
        if (matchesSearch && matchesCategory) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Share functionality
function shareFile(fileId) {
    document.getElementById('shareFileId').value = fileId;
    document.getElementById('shareModal').classList.remove('hidden');
}

function closeShareModal() {
    document.getElementById('shareModal').classList.add('hidden');
    document.getElementById('shareForm').reset();
}

document.getElementById('shareForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const fileId = document.getElementById('shareFileId').value;
    const formData = new FormData(this);
    
    // Garante que o file_id está no FormData
    if (!formData.has('file_id') || !formData.get('file_id')) {
        formData.append('file_id', fileId);
    }

    try {
        const response = await fetch(`/files/${fileId}/share`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();
        
        if (response.ok && result.success) {
            const shareUrl = result.share_url;
            
            // Exibir o modal com o link criado
            document.getElementById('shareUrlInput').value = shareUrl;
            closeShareModal();
            document.getElementById('shareLinkModal').classList.remove('hidden');
            
            // Opcional: Atualizar a lista de links na página de detalhes sem recarregar
            if (typeof loadSharedLinks === 'function') {
                loadSharedLinks(fileId);
            }

        } else {
            let errorMessage = 'Erro ao criar link compartilhado.';
            if (result.errors) {
                errorMessage += '\n\nErros:\n';
                for (const field in result.errors) {
                    errorMessage += `- ${result.errors[field].join(', ')}\n`;
                }
            } else if (result.message) {
                errorMessage += `\n\nDetalhe: ${result.message}`;
            }
            showErrorModal(errorMessage);
        }
    } catch (error) {
        // console.error('Fetch error:', error);
        showErrorModal('Ocorreu um erro de comunicação ao tentar criar o link.');
    }
});

// Download functionality
function downloadFile(downloadUrl) {
    const downloadWindow = window.open(downloadUrl, '_blank');
    
    // Fechar a janela após um pequeno delay para permitir o download
    setTimeout(() => {
        if (downloadWindow) {
            downloadWindow.close();
        }
    }, 1000);
}

// Share Link Modal functionality
function closeShareLinkModal() {
    document.getElementById('shareLinkModal').classList.add('hidden');
    document.getElementById('shareUrlInput').value = '';
}

function copyShareUrl(buttonElement) {
    const shareUrlInput = document.getElementById('shareUrlInput');
    const shareUrl = shareUrlInput.value;
    
    if (shareUrl) {
        // Tentar usar a API moderna do clipboard primeiro
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(shareUrl).then(() => {
                showCopySuccess(buttonElement);
            }).catch(err => {
                // console.warn('Clipboard API falhou, tentando método alternativo:', err);
                fallbackCopyTextToClipboard(shareUrl, buttonElement);
            });
        } else {
            // Fallback para contextos não seguros ou navegadores antigos
            fallbackCopyTextToClipboard(shareUrl, buttonElement);
        }
    }
}

function fallbackCopyTextToClipboard(text, buttonElement) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    
    // Evitar scroll para o textarea
    textArea.style.top = '0';
    textArea.style.left = '0';
    textArea.style.position = 'fixed';
    textArea.style.opacity = '0';
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showCopySuccess(buttonElement);
        } else {
            throw new Error('execCommand falhou');
        }
    } catch (err) {
        // console.error('Erro ao copiar texto:', err);
        // Selecionar o texto no input para o usuário copiar manualmente
        const shareUrlInput = document.getElementById('shareUrlInput');
        shareUrlInput.select();
        shareUrlInput.setSelectionRange(0, 99999); // Para dispositivos móveis
        showErrorModal('Não foi possível copiar automaticamente. O link foi selecionado, use Ctrl+C para copiar.');
    } finally {
        document.body.removeChild(textArea);
    }
}

function showCopySuccess(buttonElement) {
    // Feedback visual temporário
    if (buttonElement) {
        const originalText = buttonElement.textContent;
        buttonElement.textContent = 'Copiado!';
        buttonElement.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        buttonElement.classList.add('bg-green-600');
        
        setTimeout(() => {
            buttonElement.textContent = originalText;
            buttonElement.classList.remove('bg-green-600');
            buttonElement.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 2000);
    }
}

// Type filter functionality
document.querySelectorAll('.type-filter-btn').forEach(button => {
    button.addEventListener('click', function() {
        // Remove active class from all type filter buttons
        document.querySelectorAll('.type-filter-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.style.backgroundColor = btn.style.backgroundColor.replace('40', '20');
            btn.style.borderColor = btn.style.borderColor.replace('80', '40');
        });
        
        // Add active class to clicked button
        this.classList.add('active');
        this.style.backgroundColor = this.style.backgroundColor.replace('20', '40');
        this.style.borderColor = this.style.borderColor.replace('40', '80');
        
        filterFiles();
    });
});

function filterFiles() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const selectedCategory = document.querySelector('.category-filter-btn.active').dataset.category;
    const selectedType = document.querySelector('.type-filter-btn.active').dataset.type;
    const cards = document.querySelectorAll('.file-card');
    
    cards.forEach(card => {
        const fileName = card.dataset.name;
        const categoryId = card.dataset.category;
        const isTemporary = card.dataset.temporary === 'true';
        const expiresAt = card.dataset.expires;
        
        // Search filter
        const matchesSearch = !searchTerm || fileName.includes(searchTerm);
        
        // Category filter
        const matchesCategory = selectedCategory === 'all' || categoryId === selectedCategory;
        
        // Type filter
        let matchesType = true;
        if (selectedType === 'permanent') {
            matchesType = !isTemporary;
        } else if (selectedType === 'temporary') {
            matchesType = isTemporary;
        } else if (selectedType === 'expiring') {
            if (isTemporary && expiresAt) {
                const expirationDate = new Date(expiresAt);
                const now = new Date();
                const hoursUntilExpiration = (expirationDate - now) / (1000 * 60 * 60);
                matchesType = hoursUntilExpiration <= 24 && hoursUntilExpiration > 0;
            } else {
                matchesType = false;
            }
        }
        
        if (matchesSearch && matchesCategory && matchesType) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Extend expiration functionality
let fileToExtend = null;

function extendExpiration(fileId) {
    fileToExtend = fileId;
    document.getElementById('extendModal').classList.remove('hidden');
}

function closeExtendModal() {
    document.getElementById('extendModal').classList.add('hidden');
    fileToExtend = null;
}

function confirmExtend() {
    const days = document.getElementById('extendDays').value;
    
    if (fileToExtend && days && !isNaN(days) && parseInt(days) > 0) {
        fetch(`/files/${fileToExtend}/extend-expiration`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ additional_days: parseInt(days) })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                location.reload();
            } else {
                showErrorModal('Erro ao estender expiração: ' + (result.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            showErrorModal('Erro ao estender expiração: ' + error.message);
        })
        .finally(() => {
            closeExtendModal();
        });
    }
}

// Convert to permanent functionality
let fileToConvertPermanent = null;

function convertToPermanent(fileId) {
    fileToConvertPermanent = fileId;
    document.getElementById('convertPermanentModal').classList.remove('hidden');
}

function closeConvertPermanentModal() {
    document.getElementById('convertPermanentModal').classList.add('hidden');
    fileToConvertPermanent = null;
}

function confirmConvertPermanent() {
    if (fileToConvertPermanent) {
        fetch(`/files/${fileToConvertPermanent}/convert-to-permanent`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                location.reload();
            } else {
                showErrorModal('Erro ao converter arquivo: ' + (result.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            showErrorModal('Erro ao converter arquivo: ' + error.message);
        })
        .finally(() => {
            closeConvertPermanentModal();
        });
    }
}

// Convert to temporary functionality
let fileToConvertTemporary = null;

function convertToTemporary(fileId) {
    fileToConvertTemporary = fileId;
    document.getElementById('convertTemporaryModal').classList.remove('hidden');
}

function closeConvertTemporaryModal() {
    document.getElementById('convertTemporaryModal').classList.add('hidden');
    fileToConvertTemporary = null;
}

function confirmConvertTemporary() {
    const days = document.getElementById('temporaryDays').value;
    
    if (fileToConvertTemporary && days && !isNaN(days) && parseInt(days) > 0) {
        fetch(`/files/${fileToConvertTemporary}/convert-to-temporary`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ expiry_days: parseInt(days) })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                location.reload();
            } else {
                showErrorModal('Erro ao converter arquivo: ' + (result.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            showErrorModal('Erro ao converter arquivo: ' + error.message);
        })
        .finally(() => {
            closeConvertTemporaryModal();
        });
    }
}

// Delete functionality
let fileToDelete = null;

function deleteFile(fileId) {
    fileToDelete = fileId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    fileToDelete = null;
}

function confirmDelete() {
    if (fileToDelete) {
        fetch(`/files/${fileToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                location.reload();
            } else {
                showErrorModal('Erro ao excluir arquivo: ' + (result.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            showErrorModal('Erro ao excluir arquivo: ' + error.message);
        })
        .finally(() => {
            closeDeleteModal();
        });
    }
}
</script>
@endpush