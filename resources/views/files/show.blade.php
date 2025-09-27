@extends('layouts.app')

@section('title', 'Detalhes do Arquivo')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('files.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $file->original_name }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Detalhes e opções de compartilhamento</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('files.download', $file) }}" 
               target="_blank"
               class="p-2 text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors" 
               title="Download">
                <i class="fas fa-download"></i>
            </a>
            <button onclick="shareFile()" 
                    class="p-2 text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors" 
                    title="Compartilhar">
                <i class="fas fa-share-alt"></i>
            </button>
            <button onclick="deleteFile()" 
                    class="p-2 text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors" 
                    title="Excluir">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- File Preview -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Visualização</h2>
                
                <div class="text-center">
                    @if(str_starts_with($file->mime_type, 'image/'))
                        <img src="{{ $file->file_url }}" alt="{{ $file->original_name }}" 
                             class="max-w-full h-auto rounded-lg shadow-sm mx-auto">
                    @elseif(str_starts_with($file->mime_type, 'video/'))
                        <div class="video-player-container">
                            <video 
                                id="videoPlayer" 
                                controls 
                                preload="metadata"
                                class="max-w-full h-auto rounded-lg shadow-sm mx-auto"
                                style="max-height: 70vh;"
                                poster="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolygon points='5,3 19,12 5,21'%3E%3C/polygon%3E%3C/svg%3E"
                            >
                                <!-- Primary source with original mime type -->
                                <source src="{{ $file->file_url }}" type="{{ $file->mime_type }}">
                                
                                <!-- Additional sources for better compatibility -->
                                @if(str_contains($file->mime_type, 'mp4') || str_contains($file->original_name, '.mp4'))
                                    <source src="{{ $file->file_url }}" type="video/mp4">
                                @endif
                                @if(str_contains($file->mime_type, 'webm') || str_contains($file->original_name, '.webm'))
                                    <source src="{{ $file->file_url }}" type="video/webm">
                                @endif
                                @if(str_contains($file->mime_type, 'ogg') || str_contains($file->original_name, '.ogg'))
                                    <source src="{{ $file->file_url }}" type="video/ogg">
                                @endif
                                @if(str_contains($file->original_name, '.avi'))
                                    <source src="{{ $file->file_url }}" type="video/x-msvideo">
                                @endif
                                @if(str_contains($file->original_name, '.mov'))
                                    <source src="{{ $file->file_url }}" type="video/quicktime">
                                @endif
                                @if(str_contains($file->original_name, '.mkv'))
                                    <source src="{{ $file->file_url }}" type="video/x-matroska">
                                @endif
                                
                                <!-- Fallback message -->
                                <div class="p-8 text-center">
                                    <i class="fas fa-video text-6xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600 mb-4">Seu navegador não suporta a reprodução deste formato de vídeo.</p>
                                    <a href="{{ $file->file_url }}" target="_blank" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                                        <i class="fas fa-download mr-2"></i>Baixar Vídeo
                                    </a>
                                </div>
                            </video>
                            
                            <!-- Video Info -->
                            <div class="mt-4 text-center text-sm text-gray-600">
                                <p>Formato: {{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }} | Tamanho: {{ $file->fileSizeFormatted }}</p>
                            </div>
                        </div>
                    @elseif(str_starts_with($file->mime_type, 'audio/'))
                        <div class="p-8">
                            <i class="fas fa-music text-6xl text-gray-400 mb-4"></i>
                            <audio controls class="w-full">
                                <source src="{{ $file->file_url }}" type="{{ $file->mime_type }}">
                                Seu navegador não suporta a reprodução de áudio.
                            </audio>
                        </div>
                    @elseif($file->mime_type === 'application/pdf')
                        <div class="p-8">
                            <i class="fas fa-file-pdf text-6xl text-red-500 mb-4"></i>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">Documento PDF</p>
                            <a href="{{ $file->file_url }}" target="_blank" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                                <i class="fas fa-external-link-alt mr-2"></i>Abrir PDF
                            </a>
                        </div>
                    @else
                        <div class="p-8">
                            <i class="fas fa-file text-6xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600 dark:text-gray-300">Visualização não disponível para este tipo de arquivo</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Shared Links -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 p-6 mt-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Links Compartilhados</h2>
                    <button onclick="shareFile()" 
                            class="flex items-center justify-center w-10 h-10 text-blue-500 hover:text-blue-600 transition-colors hover:bg-blue-50 rounded-lg" 
                            title="Criar novo link">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </button>
                </div>
                
                <div id="sharedLinksList">
                    @if($sharedLinks->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($sharedLinks as $link)
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                    <!-- Card Header -->
                                    <div class="p-4 border-b border-gray-200 dark:border-gray-600">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center space-x-2">
                                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white bg-blue-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Link Compartilhado</h3>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $link->created_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Status Badges -->
                                        <div class="flex flex-wrap gap-2">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $link->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                                <div class="w-1.5 h-1.5 rounded-full mr-1 {{ $link->is_active ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                                {{ $link->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                            @if($link->expires_at)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $link->isExpired() ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' }}">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $link->isExpired() ? 'Expirado' : 'Expira ' . $link->expires_at->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Card Content -->
                                    <div class="p-4">
                                        <div class="space-y-3">
                                            <div class="text-sm text-gray-600">
                                                @if($link->download_limit)
                                                    <div class="flex items-center justify-between">
                                                        <span>Downloads:</span>
                                                        <span class="font-medium">{{ $link->download_count }}/{{ $link->download_limit }}</span>
                                                    </div>
                                                @else
                                                    <div class="flex items-center justify-between">
                                                        <span>Downloads:</span>
                                                        <span class="font-medium">{{ $link->download_count }}</span>
                                                    </div>
                                                @endif
                                                
                                                @if($link->password_hash)
                                                    <div class="flex items-center mt-2">
                                                        <svg class="w-4 h-4 mr-1 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <span class="text-xs dark:text-gray-400">Protegido por senha</span>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Link URL -->
                                            <div class="text-xs text-gray-500 dark:text-gray-400 break-all bg-gray-50 dark:bg-gray-700 p-2 rounded border dark:border-gray-600">
                                                {{ route('public.shared.show', $link->token) }}
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Card Actions -->
                                    <div class="flex items-center justify-center px-4 py-3 border-t border-gray-200 dark:border-gray-600">
                                        <div class="flex space-x-4">
                                            <button onclick="copyToClipboard('{{ route('public.shared.show', $link->token) }}', this)"
                                    class="flex items-center justify-center w-10 h-10 text-blue-500 hover:text-blue-600 transition-colors hover:bg-blue-50 dark:hover:bg-blue-900 rounded-lg"
                                    title="Copiar Link">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                                            <button onclick="viewAccessLogs('{{ $link->id }}')" 
                                                    class="flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 transition-colors hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg" 
                                                    title="Ver Acessos">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            <button onclick="deleteSharedLink('{{ $link->id }}')" 
                                                    class="flex items-center justify-center w-10 h-10 text-red-500 hover:text-red-600 transition-colors hover:bg-red-50 dark:hover:bg-red-900 rounded-lg" 
                                                    title="Excluir">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-600 dark:text-gray-300 mb-2">Nenhum link compartilhado</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">Crie um link para compartilhar este arquivo</p>
                            <button onclick="shareFile()" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Criar Link
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- File Information -->
        <div class="space-y-6">
            <!-- Basic Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Informações</h2>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome Original</label>
                        <p class="text-gray-900 dark:text-white break-all">{{ $file->original_name }}</p>
                    </div>
                    
                    @if($file->category)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria</label>
                            <span class="inline-block px-3 py-1 text-sm rounded-full mt-1" 
                                  style="background-color: {{ $file->category->color }}20; color: {{ $file->category->color }}">
                                {{ $file->category->name }}
                            </span>
                        </div>
                    @endif
                    
                    @if($file->description)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                            <p class="text-gray-900 dark:text-white">{{ $file->description }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tamanho</label>
                        <p class="text-gray-900 dark:text-white">{{ $file->fileSizeFormatted }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo</label>
                        <p class="text-gray-900 dark:text-white">{{ $file->mime_type }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload</label>
                        <p class="text-gray-900 dark:text-white">{{ $file->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Última Modificação</label>
                        <p class="text-gray-900 dark:text-white">{{ $file->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
            

            
            <!-- Activity Log -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Atividades Recentes</h2>
                
                @if($activityLogs->count() > 0)
                    <div class="space-y-3">
                        @foreach($activityLogs as $log)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    @switch($log->action)
                                        @case('upload')
                                            <i class="fas fa-upload text-blue-600"></i>
                                            @break
                                        @case('download')
                                            <i class="fas fa-download text-green-600"></i>
                                            @break
                                        @case('share')
                                            <i class="fas fa-share-alt text-purple-600"></i>
                                            @break
                                        @case('delete')
                                            <i class="fas fa-trash text-red-600"></i>
                                            @break
                                        @default
                                            <i class="fas fa-circle text-gray-400"></i>
                                    @endswitch
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $log->description }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $log->created_at ? $log->created_at->format('d/m/Y H:i') : 'Data não disponível' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nenhuma atividade registrada</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div id="shareModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Compartilhar Arquivo</h3>
                <form id="shareForm">
                    @csrf
                    <input type="hidden" name="file_id" value="{{ $file->id }}">
                    
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
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Criar Link
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Copy Link Modal -->
<div id="copyLinkModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden" style="z-index: 10003;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Copiar Link</h3>
                    <button onclick="closeCopyLinkModal()" class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Link do arquivo:</label>
                    <div class="flex">
                        <input type="text" id="linkToCopy" readonly 
                               class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-md bg-gray-50 dark:bg-gray-700 dark:text-gray-300 text-sm">
                        <button onclick="copyToClipboard()" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button onclick="closeCopyLinkModal()" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Share Success Modal -->
<div id="shareSuccessModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden" style="z-index: 10003;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Link Criado com Sucesso!</h3>
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Link compartilhado:</label>
                    <div class="flex">
                        <input type="text" id="shareSuccessUrl" readonly 
                               class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-l-md bg-gray-50 dark:bg-gray-700 dark:text-gray-300 text-sm">
                        <button onclick="copyShareSuccessUrl(this)" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button onclick="closeShareSuccessModal()" 
                            class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Access Logs Modal -->
<div id="accessLogsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden" style="z-index: 10003;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Logs de Acesso</h3>
                    <button onclick="closeAccessLogsModal()" class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="accessLogsContent" class="max-h-96 overflow-y-auto">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Shared Link Confirmation Modal -->
<div id="deleteConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden" style="z-index: 10003;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Confirmar Exclusão</h3>
                    </div>
                </div>
                <div class="mb-6">
                    <p class="text-gray-600 dark:text-gray-300">Tem certeza que deseja excluir este link compartilhado? Esta ação não pode ser desfeita.</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeDeleteConfirmModal()" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button onclick="confirmDelete()" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete File Confirmation Modal -->
<div id="deleteFileModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden" style="z-index: 10003;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Confirmar Exclusão do Arquivo</h3>
                    </div>
                </div>
                <div class="mb-6">
                    <p class="text-gray-600 dark:text-gray-300">Tem certeza que deseja excluir este arquivo? Esta ação não pode ser desfeita e todos os links compartilhados serão invalidados.</p>
                </div>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeDeleteFileModal()" 
                            class="px-4 py-2 text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button onclick="confirmDeleteFile()" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Excluir Arquivo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Share functionality
function shareFile() {
    document.getElementById('shareModal').classList.remove('hidden');
}

function closeShareModal() {
    document.getElementById('shareModal').classList.add('hidden');
    document.getElementById('shareForm').reset();
}

// Share success modal functions
function showShareSuccessModal(shareUrl) {
    document.getElementById('shareSuccessUrl').value = shareUrl;
    document.getElementById('shareSuccessModal').classList.remove('hidden');
}

function closeShareSuccessModal() {
    document.getElementById('shareSuccessModal').classList.add('hidden');
}

function copyShareSuccessUrl(button) {
    const urlInput = document.getElementById('shareSuccessUrl');
    
    // Try modern clipboard API first
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(urlInput.value).then(() => {
            showCopySuccessShare(button);
        }).catch(() => {
            fallbackCopyTextToClipboardShare(urlInput.value, button);
        });
    } else {
        fallbackCopyTextToClipboardShare(urlInput.value, button);
    }
}

function fallbackCopyTextToClipboardShare(text, button) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showCopySuccessShare(button);
        } else {
            // Final fallback - just select the text
            const urlInput = document.getElementById('shareSuccessUrl');
            urlInput.select();
            urlInput.setSelectionRange(0, 99999);
        }
    } catch (err) {
        // Final fallback - just select the text
        const urlInput = document.getElementById('shareSuccessUrl');
        urlInput.select();
        urlInput.setSelectionRange(0, 99999);
    } finally {
        document.body.removeChild(textArea);
    }
}

function showCopySuccessShare(button) {
    const originalHTML = button.innerHTML;
    button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
    button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
    button.classList.add('bg-green-600');
    
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.classList.remove('bg-green-600');
        button.classList.add('bg-blue-600', 'hover:bg-blue-700');
    }, 1500);
}

document.getElementById('shareForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    // Ensure CSRF token is included in FormData
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    
    try {
        const response = await fetch(`/files/{{ $file->id }}/share`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            showShareSuccessModal(result.share_url);
            closeShareModal();
            location.reload();
        } else {
            alert('Erro ao criar link: ' + (result.message || 'Erro desconhecido'));
        }
    } catch (error) {
        alert('Erro ao criar link: ' + error.message);
    }
});

// Copy link functionality
function copyToClipboard(url, button) {
    // Try modern clipboard API first
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(url).then(() => {
            showCopySuccess(button);
        }).catch(() => {
            fallbackCopyTextToClipboard(url, button);
        });
    } else {
        fallbackCopyTextToClipboard(url, button);
    }
}

function fallbackCopyTextToClipboard(text, button) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showCopySuccess(button);
        } else {
            // Final fallback - just select the text for manual copy
            textArea.select();
            textArea.setSelectionRange(0, 99999);
            alert('Por favor, pressione Ctrl+C para copiar o link');
        }
    } catch (err) {
        console.error('Erro ao copiar texto: ', err);
        alert('Não foi possível copiar o link automaticamente. Link: ' + text);
    } finally {
        document.body.removeChild(textArea);
    }
}

function showCopySuccess(button) {
    const originalHTML = button.innerHTML;
    button.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
    button.classList.remove('text-blue-500', 'hover:text-blue-600');
    button.classList.add('text-green-500');
    
    // Show toast notification
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
    toast.textContent = 'Link copiado!';
    document.body.appendChild(toast);
    
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.classList.remove('text-green-500');
        button.classList.add('text-blue-500', 'hover:text-blue-600');
        document.body.removeChild(toast);
    }, 1500);
}

// Delete shared link
let linkToDelete = null;

function deleteSharedLink(linkId) {
    linkToDelete = linkId;
    document.getElementById('deleteConfirmModal').classList.remove('hidden');
}

function closeDeleteConfirmModal() {
    document.getElementById('deleteConfirmModal').classList.add('hidden');
    linkToDelete = null;
}

function confirmDelete() {
    if (!linkToDelete) return;
    
    fetch(`/files/shared-links/${linkToDelete}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            closeDeleteConfirmModal();
            location.reload();
        } else {
            alert('Erro ao excluir link: ' + (result.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao excluir link: ' + error.message);
    });
}

// View access logs
function viewAccessLogs(linkId) {
    fetch(`/files/shared-links/${linkId}/access-logs`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        const content = document.getElementById('accessLogsContent');
        
        if (data.logs && data.logs.length > 0) {
            content.innerHTML = data.logs.map(log => `
                <div class="border-b border-gray-200 py-3 last:border-b-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium text-gray-900">${log.action}</p>
                            <p class="text-sm text-gray-600">IP: ${log.ip_address}</p>
                            <p class="text-xs text-gray-500">${log.accessed_at}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            content.innerHTML = '<p class="text-gray-500 text-center py-4">Nenhum acesso registrado</p>';
        }
        
        document.getElementById('accessLogsModal').classList.remove('hidden');
    })
    .catch(error => {
        console.error('Error loading access logs:', error);
        alert('Erro ao carregar logs: ' + error.message);
    });
}

function closeAccessLogsModal() {
    document.getElementById('accessLogsModal').classList.add('hidden');
}

// Delete file
function deleteFile() {
    document.getElementById('deleteFileModal').classList.remove('hidden');
}

function closeDeleteFileModal() {
    document.getElementById('deleteFileModal').classList.add('hidden');
}

function confirmDeleteFile() {
    fetch(`/files/{{ $file->id }}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            window.location.href = '{{ route("files.index") }}';
        } else {
            alert('Erro ao excluir arquivo: ' + (result.message || 'Erro desconhecido'));
        }
    })
    .catch(error => {
        alert('Erro ao excluir arquivo: ' + error.message);
    });
}

// Video Player Enhancement
@if(str_starts_with($file->mime_type, 'video/'))
document.addEventListener('DOMContentLoaded', function() {
    const videoPlayer = document.getElementById('videoPlayer');
    
    if (videoPlayer) {
        // Handle video load errors
        videoPlayer.addEventListener('error', function(e) {
            console.error('Erro ao carregar vídeo:', e);
            const errorDiv = document.createElement('div');
            errorDiv.className = 'p-8 text-center';
            errorDiv.innerHTML = `
                <i class="fas fa-exclamation-triangle text-6xl text-red-400 mb-4"></i>
                <p class="text-gray-600 mb-4">Erro ao carregar o vídeo. Verifique se o arquivo não está corrompido.</p>
                <a href="{{ $file->file_url }}" target="_blank" 
                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-download mr-2"></i>Baixar Vídeo
                </a>
            `;
            videoPlayer.parentNode.replaceChild(errorDiv, videoPlayer);
        });
        
        // Handle successful load
        videoPlayer.addEventListener('loadedmetadata', function() {
            console.log('Vídeo carregado com sucesso');
            console.log('Duração:', Math.round(videoPlayer.duration), 'segundos');
            console.log('Dimensões:', videoPlayer.videoWidth + 'x' + videoPlayer.videoHeight);
        });
        
        // Handle play/pause events for analytics
        videoPlayer.addEventListener('play', function() {
            console.log('Reprodução iniciada');
        });
        
        videoPlayer.addEventListener('pause', function() {
            console.log('Reprodução pausada');
        });
        
        // Handle fullscreen
        videoPlayer.addEventListener('dblclick', function() {
            if (videoPlayer.requestFullscreen) {
                videoPlayer.requestFullscreen();
            } else if (videoPlayer.webkitRequestFullscreen) {
                videoPlayer.webkitRequestFullscreen();
            } else if (videoPlayer.msRequestFullscreen) {
                videoPlayer.msRequestFullscreen();
            }
        });
        
        // Keyboard shortcuts
        videoPlayer.addEventListener('keydown', function(e) {
            switch(e.code) {
                case 'Space':
                    e.preventDefault();
                    if (videoPlayer.paused) {
                        videoPlayer.play();
                    } else {
                        videoPlayer.pause();
                    }
                    break;
                case 'ArrowLeft':
                    e.preventDefault();
                    videoPlayer.currentTime = Math.max(0, videoPlayer.currentTime - 10);
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    videoPlayer.currentTime = Math.min(videoPlayer.duration, videoPlayer.currentTime + 10);
                    break;
                case 'KeyF':
                    e.preventDefault();
                    if (videoPlayer.requestFullscreen) {
                        videoPlayer.requestFullscreen();
                    }
                    break;
            }
        });
        
        // Make video focusable for keyboard shortcuts
        videoPlayer.setAttribute('tabindex', '0');
    }
});
@endif
</script>
@endpush