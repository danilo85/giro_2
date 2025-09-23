@extends('layouts.app')

@section('title', 'Gestão de Projetos - Kanban')

@section('content')
<div class="container mx-auto px-4 py-6" x-data="kanbanBoard()" :class="{ 'dark': darkMode }" x-init="initDarkMode()">
    <!-- Tags de Navegação Rápida -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('dashboard') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v3H8V5z"></path>
            </svg>
            Dashboard
        </a>
  
        <a href="{{ route('orcamentos.index') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Orçamentos
        </a>
        <a href="{{ route('clientes.index') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Clientes
        </a>
        <a href="{{ route('autores.index') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Autores
        </a>
        <a href="{{ route('pagamentos.index') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
            Pagamentos
        </a>
        <a href="{{ route('modelos-propostas.index') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Modelos de Propostas
        </a>
        <a href="{{ route('kanban.index') }}" 
           class="px-3 py-1 text-sm font-medium rounded-full bg-blue-600 text-white dark:bg-blue-700 dark:text-blue-200">
            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
            </svg>
            Kanban
        </a>
    </div>

    <!-- Loading Overlay -->
    <div x-show="loading" x-cloak class="loading-overlay">
        <div class="loading-spinner"></div>
    </div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Gestão de Projetos</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Acompanhe o progresso dos seus projetos</p>
        </div>
        <div class="flex space-x-3">
            <!-- Lock Toggle -->
            <button 
                @click="toggleLock()" 
                class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                :title="isLocked ? 'Destravar movimentações' : 'Travar movimentações'"
            >
                <svg x-show="!isLocked" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                </svg>
                <svg x-show="isLocked" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2h-4V7a3 3 0 00-6 0v4H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                </svg>
            </button>
            <button 
                @click="refreshBoard()" 
                class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                title="Atualizar"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
            <a href="{{ route('kanban.etapas.index') }}" class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Gerenciar Etapas">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="loading" class="flex justify-center items-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500 dark:border-blue-400"></div>
    </div>

    <!-- Kanban Board -->
    <div x-show="!loading" class="kanban-slider-container" :class="{ 'kanban-locked': isLocked }">
        <!-- Slider Info -->
        <div class="flex justify-center items-center mb-4">
            <div class="text-sm text-gray-600 dark:text-gray-300 flex items-center space-x-2">
                <span>Arraste horizontalmente ou toque nas bordas para navegar</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                </svg>
            </div>
        </div>
        
        <!-- Slider Container -->
        <div class="slider-wrapper" 
             @touchstart="handleTouchStart($event)"
             @touchmove="handleTouchMove($event)"
             @touchend="handleTouchEnd($event)"
             @mousedown="handleMouseDown($event)"
             @mousemove="handleMouseMove($event)"
             @mouseup="handleMouseEnd($event)"
             @mouseleave="handleMouseEnd($event)">
            <div class="slider-track" 
                 :style="`transform: translateX(-${scrollOffset}px); transition: ${isScrolling ? 'none' : 'transform 0.3s ease-out'}`"
                 ref="sliderTrack">
            @foreach($etapas as $etapa)
            <div class="slider-column" data-column-id="{{ $etapa->id }}">
                <!-- Column Header -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 mb-4" 
                     style="background: linear-gradient(135deg, {{ $etapa->cor }}15, {{ $etapa->cor }}08);">
                    <div class="p-4 border-b border-gray-100 dark:border-gray-700" 
                         style="background: linear-gradient(135deg, {{ $etapa->cor }}20, {{ $etapa->cor }}10);">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div 
                                    class="w-4 h-4 rounded-full shadow-sm" 
                                    style="background-color: {{ $etapa->cor }}; box-shadow: 0 0 0 2px {{ $etapa->cor }}30;"
                                ></div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $etapa->nome }}</h3>
                            </div>
                            <span class="text-gray-600 dark:text-gray-300 text-sm px-2 py-1 rounded-full" 
                                  style="background-color: {{ $etapa->cor }}20; border: 1px solid {{ $etapa->cor }}30;">
                                <span x-text="getProjectCount({{ $etapa->id }})">{{ $projetos->get($etapa->id, collect())->count() }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Projects Container -->
                <div 
                    class="space-y-3 min-h-[200px] p-2 rounded-lg border-2 border-dashed transition-all duration-200"
                    style="border-color: {{ $etapa->cor }}40; background: linear-gradient(135deg, {{ $etapa->cor }}08, {{ $etapa->cor }}05);"
                    id="etapa-{{ $etapa->id }}"
                    data-etapa-id="{{ $etapa->id }}"
                >
                    @if($projetos->has($etapa->id))
                        @foreach($projetos->get($etapa->id) as $projeto)
                        <div 
                            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 p-4 cursor-move hover:shadow-md transition-shadow"
                            data-projeto-id="{{ $projeto->id }}"
                            data-etapa-id="{{ $projeto->etapa_id }}"
                            data-posicao="{{ $projeto->posicao }}"
                        >
                            <!-- Project Header -->
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900 dark:text-white text-sm leading-tight">
                                        {{ $projeto->orcamento->titulo ?? 'Projeto #' . $projeto->id }}
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $projeto->orcamento->cliente->nome ?? 'Cliente não informado' }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-1 ml-2">
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Project Details -->
                            <div class="space-y-2">
                                @if($projeto->orcamento->valor_total)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500 dark:text-gray-400">Valor:</span>
                                    <span class="font-medium text-green-600 dark:text-green-400">
                                        R$ {{ number_format($projeto->orcamento->valor_total, 2, ',', '.') }}
                                    </span>
                                </div>
                                @endif
                                
                                @if($projeto->orcamento->prazo_entrega)
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500 dark:text-gray-400">Prazo:</span>
                                    <span class="font-medium text-blue-600 dark:text-blue-400">
                                        {{ \Carbon\Carbon::parse($projeto->orcamento->prazo_entrega)->format('d/m/Y') }}
                                    </span>
                                </div>
                                @endif

                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-gray-500 dark:text-gray-400">Movido em:</span>
                                    <span class="font-medium text-gray-600 dark:text-gray-300">
                                        {{ $projeto->moved_at ? $projeto->moved_at->format('d/m/Y H:i') : 'N/A' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Project Actions -->
                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                <div class="flex items-center space-x-2">
                                    @if($projeto->orcamento->status)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($projeto->orcamento->status === 'aprovado') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                        @elseif($projeto->orcamento->status === 'pendente') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                        @elseif($projeto->orcamento->status === 'rejeitado') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200
                                        @endif">
                                        {{ ucfirst($projeto->orcamento->status) }}
                                    </span>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-1">
                                    <a 
                                        href="{{ route('orcamentos.show', $projeto->orcamento->id) }}" 
                                        class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors"
                                        title="Ver orçamento"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @endforeach
            </div>
            
            <!-- Edge Detection Areas -->
            <div class="edge-scroll-left" 
                 @mouseenter="startEdgeScroll('left')"
                 @mouseleave="stopEdgeScroll()"
                 @touchstart="startEdgeScroll('left')"
                 @touchend="stopEdgeScroll()"></div>
            <div class="edge-scroll-right" 
                 @mouseenter="startEdgeScroll('right')"
                 @mouseleave="stopEdgeScroll()"
                 @touchstart="startEdgeScroll('right')"
                 @touchend="stopEdgeScroll()"></div>
        </div>
        
        <!-- Scroll Indicators -->
        <div class="flex justify-center mt-4 space-x-1">
            <div class="scroll-indicator-track">
                <div class="scroll-indicator-thumb" 
                     :style="`left: ${scrollIndicatorPosition}%; width: ${scrollIndicatorWidth}%`"></div>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    @if($etapas->isEmpty())
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhuma etapa configurada</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando etapas para organizar seus projetos.</p>
        <div class="mt-6">
            <a href="{{ route('kanban.etapas.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600">
                Gerenciar Etapas
            </a>
        </div>
    </div>
    @endif
</div>
<footer class="mt-8">
    <div class="text-center py-6">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            © {{ date('Y') }} Danilo Miguel. Todos os direitos reservados.
        </p>
        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
            Sistema de Gestão Financeira - Desenvolvido com Laravel
        </p>
    </div>
</footer>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
function kanbanBoard() {
    return {
        etapas: @json($etapas),
        projetos: @json($projetosArray),
        loading: false,
        darkMode: false,
        isLocked: true,
        sortableInstances: [],
        
        // Slider properties
        scrollOffset: 0,
        maxScrollOffset: 0,
        columnWidth: 280,
        visibleColumns: 4,
        isScrolling: false,
        
        // Touch/Mouse handling
        startX: 0,
        startScrollOffset: 0,
        isDragging: false,
        columnsPerView: 4,
        
        // Edge scrolling
        edgeScrollInterval: null,
        edgeScrollSpeed: 4,
        
        // Scroll indicators
        scrollIndicatorPosition: 0,
        scrollIndicatorWidth: 100,
        
        init() {
            // Initialize dark mode
            this.initDarkMode();
            
            this.$nextTick(() => {
                this.initializeSlider();
                this.initializeSortable();
            });
            
            // Listener para redimensionamento da tela
            window.addEventListener('resize', () => {
                this.initializeSlider();
                this.updateScrollIndicators();
            });
        },
        
        initializeSlider() {
            this.calculateResponsiveSettings();
            this.visibleColumns = this.getVisibleColumns();
            this.columnWidth = this.getColumnWidth();
            this.maxScrollOffset = Math.max(0, (this.etapas.length * this.columnWidth) - (this.visibleColumns * this.columnWidth));
            
            // Ajustar scroll se necessário
            if (this.scrollOffset > this.maxScrollOffset) {
                this.scrollOffset = this.maxScrollOffset;
            }
            
            this.updateScrollIndicators();
        },
        
        calculateResponsiveSettings() {
            const screenWidth = window.innerWidth;
            
            if (screenWidth >= 1024) {
                // Desktop: mostrar todas as colunas
                this.columnsPerView = 4;
                this.columnWidth = 280;
            } else if (screenWidth >= 768) {
                // Tablet: mostrar 3 colunas
                this.columnsPerView = 3;
                this.columnWidth = Math.floor((screenWidth - 48) / 3);
            } else {
                // Mobile: mostrar 2 colunas
                this.columnsPerView = 2;
                this.columnWidth = Math.floor((screenWidth - 32) / 2);
            }
        },
        
        initDarkMode() {
            // Check for saved theme preference or default to light mode
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                this.enableDarkMode();
            } else {
                this.enableLightMode();
            }
        },
        
        enableDarkMode() {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            this.darkMode = true;
        },
        
        enableLightMode() {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            this.darkMode = false;
        },
        
        toggleDarkMode() {
            if (this.darkMode) {
                this.enableLightMode();
            } else {
                this.enableDarkMode();
            }
        },
        
        toggleLock() {
            this.isLocked = !this.isLocked;
            this.updateSortableState();
            
            const message = this.isLocked ? 'Movimentações travadas' : 'Movimentações liberadas';
            this.showNotification(message, 'info');
        },
        
        updateSortableState() {
            this.sortableInstances.forEach(sortable => {
                if (sortable) {
                    sortable.option('disabled', this.isLocked);
                }
            });
        },
        
        getVisibleColumns() {
            if (window.innerWidth >= 1536) return 4; // 2xl - 4 colunas
            if (window.innerWidth >= 1280) return 4; // xl - 4 colunas
            if (window.innerWidth >= 1024) return 3; // lg - 3 colunas
            if (window.innerWidth >= 768) return 2; // md - 2 colunas
            return 1; // sm - 1 coluna
        },
        
        getColumnWidth() {
            if (window.innerWidth >= 1024) return 280; // Desktop
            if (window.innerWidth >= 768) return Math.floor((window.innerWidth - 48) / 3); // Tablet
            return Math.floor((window.innerWidth - 32) / 2); // Mobile
        },
        
        // Touch handling
        handleTouchStart(event) {
            this.startX = event.touches[0].clientX;
            this.startScrollOffset = this.scrollOffset;
            this.isDragging = true;
            this.isScrolling = true;
        },
        
        handleTouchMove(event) {
            if (!this.isDragging) return;
            
            event.preventDefault();
            const currentX = event.touches[0].clientX;
            const deltaX = this.startX - currentX;
            const newOffset = this.startScrollOffset + deltaX;
            
            this.scrollOffset = Math.max(0, Math.min(newOffset, this.maxScrollOffset));
            this.updateScrollIndicators();
        },
        
        handleTouchEnd() {
            this.isDragging = false;
            this.isScrolling = false;
        },
        
        // Mouse handling
        handleMouseDown(event) {
            this.startX = event.clientX;
            this.startScrollOffset = this.scrollOffset;
            this.isDragging = true;
            this.isScrolling = true;
        },
        
        handleMouseMove(event) {
            if (!this.isDragging) return;
            
            const currentX = event.clientX;
            const deltaX = this.startX - currentX;
            const newOffset = this.startScrollOffset + deltaX;
            
            this.scrollOffset = Math.max(0, Math.min(newOffset, this.maxScrollOffset));
            this.updateScrollIndicators();
        },
        
        handleMouseEnd() {
            this.isDragging = false;
            this.isScrolling = false;
        },
        
        // Edge scrolling
        startEdgeScroll(direction) {
            this.stopEdgeScroll();
            
            this.edgeScrollInterval = setInterval(() => {
                if (direction === 'left' && this.scrollOffset > 0) {
                    this.scrollOffset = Math.max(0, this.scrollOffset - this.edgeScrollSpeed);
                } else if (direction === 'right' && this.scrollOffset < this.maxScrollOffset) {
                    this.scrollOffset = Math.min(this.maxScrollOffset, this.scrollOffset + this.edgeScrollSpeed);
                }
                this.updateScrollIndicators();
            }, 12); // ~83fps - velocidade aumentada
        },
        
        stopEdgeScroll() {
            if (this.edgeScrollInterval) {
                clearInterval(this.edgeScrollInterval);
                this.edgeScrollInterval = null;
            }
        },
        
        // Scroll indicators
        updateScrollIndicators() {
            if (this.maxScrollOffset === 0) {
                this.scrollIndicatorPosition = 0;
                this.scrollIndicatorWidth = 100;
                return;
            }
            
            const scrollPercentage = this.scrollOffset / this.maxScrollOffset;
            const visiblePercentage = this.columnsPerView / this.etapas.length;
            
            this.scrollIndicatorPosition = scrollPercentage * (100 - (visiblePercentage * 100));
            this.scrollIndicatorWidth = visiblePercentage * 100;
        },
        
        initializeSortable() {
            // Limpar instâncias anteriores
            this.sortableInstances = [];
            
            // Inicializar SortableJS para cada coluna
            this.etapas.forEach(etapa => {
                const container = document.getElementById(`etapa-${etapa.id}`);
                if (container) {
                    const sortableInstance = new Sortable(container, {
                        group: 'kanban',
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        dragClass: 'sortable-drag',
                        disabled: this.isLocked,
                        onEnd: (evt) => {
                            this.handleDrop(evt);
                        }
                    });
                    
                    // Armazenar a instância para controle posterior
                    this.sortableInstances.push(sortableInstance);
                }
            });
        },
        
        async handleDrop(evt) {
            const projetoId = evt.item.dataset.projetoId;
            const novaEtapaId = evt.to.dataset.etapaId;
            const novaPosicao = evt.newIndex + 1;
            
            if (!projetoId || !novaEtapaId) {
                console.error('Dados insuficientes para mover projeto');
                return;
            }
            
            this.loading = true;
            
            try {
                const response = await fetch('{{ route("kanban.api.projetos.mover") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        projeto_id: parseInt(projetoId),
                        etapa_id: parseInt(novaEtapaId),
                        posicao: novaPosicao
                    })
                });
                
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.error || 'Erro ao mover projeto');
                }
                
                // Atualizar dados locais
                this.atualizarProjetoLocal(parseInt(projetoId), parseInt(novaEtapaId), novaPosicao);
                
                this.showNotification(data.message, 'success');
                
            } catch (error) {
                console.error('Erro ao mover projeto:', error);
                this.showNotification(error.message, 'error');
                
                // Reverter a mudança visual
                window.location.reload();
            } finally {
                this.loading = false;
            }
        },
        
        atualizarProjetoLocal(projetoId, novaEtapaId, novaPosicao) {
            const projeto = this.projetos.find(p => p.id === projetoId);
            if (projeto) {
                projeto.etapa_id = novaEtapaId;
                projeto.posicao = novaPosicao;
                projeto.moved_at = new Date().toISOString();
            }
        },
        
        getProjetosPorEtapa(etapaId) {
            return this.projetos
                .filter(projeto => projeto.etapa_id === etapaId)
                .sort((a, b) => a.posicao - b.posicao);
        },
        
        async refreshBoard() {
            this.loading = true;
            try {
                const response = await fetch('{{ route("kanban.api.projetos.index") }}');
                const data = await response.json();
                
                // Recarregar a página para simplificar
                window.location.reload();
                
            } catch (error) {
                console.error('Erro ao atualizar board:', error);
                this.showNotification('Erro ao atualizar o board', 'error');
            } finally {
                this.loading = false;
            }
        },
        
        getProjectCount(etapaId) {
            if (!this.projetos || !Array.isArray(this.projetos)) {
                return 0;
            }
            return this.projetos.filter(projeto => projeto.etapa_id === etapaId).length;
        },
        
        formatarData(data) {
            if (!data) return '';
            return new Date(data).toLocaleDateString('pt-BR');
        },
        
        getStatusColor(status) {
            const colors = {
                'pendente': 'bg-yellow-100 text-yellow-800',
                'em_andamento': 'bg-blue-100 text-blue-800',
                'concluido': 'bg-green-100 text-green-800',
                'cancelado': 'bg-red-100 text-red-800'
            };
            return colors[status] || 'bg-gray-100 text-gray-800';
        },
        
        showNotification(message, type = 'info') {
            let alertClass;
            switch(type) {
                case 'success':
                    alertClass = 'bg-green-500 text-white';
                    break;
                case 'error':
                    alertClass = 'bg-red-500 text-white';
                    break;
                case 'info':
                    alertClass = 'bg-blue-500 text-white';
                    break;
                default:
                    alertClass = 'bg-gray-500 text-white';
            }
            
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${alertClass}`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    }
}
</script>
@endpush
@push('styles')
<style>
/* Estilos para o Slider */
.kanban-slider-container {
    position: relative;
    width: 100%;
    overflow: hidden;
}

.slider-wrapper {
    position: relative;
    width: 100%;
    cursor: grab;
    user-select: none;
}

.slider-wrapper:active {
    cursor: grabbing;
}

.slider-track {
    display: flex;
    will-change: transform;
}

.slider-column {
    flex: 0 0 auto;
    padding: 0 8px;
}

/* Responsividade do slider */
@media (min-width: 1024px) {
    .slider-column {
        width: 280px;
    }
}

@media (min-width: 768px) and (max-width: 1023px) {
    .slider-column {
        width: calc(50vw - 24px);
    }
}

@media (max-width: 767px) {
    .slider-column {
        width: calc(100vw - 32px);
    }
}

/* Áreas de detecção de borda */
.edge-scroll-left,
.edge-scroll-right {
    position: absolute;
    top: 0;
    bottom: 0;
    width: 50px;
    z-index: 10;
    pointer-events: auto;
    cursor: pointer;
}

.edge-scroll-left {
    left: 0;
    background: linear-gradient(to right, rgba(255, 255, 255, 0.5), transparent);
}

.edge-scroll-right {
    right: 0;
    background: linear-gradient(to left, rgba(255, 255, 255, 0.5), transparent);
}

/* Indicadores de scroll */
.scroll-indicator-track {
    position: relative;
    width: 100%;
    max-width: 200px;
    height: 4px;
    background: #e5e7eb;
    border-radius: 2px;
    overflow: hidden;
}

.scroll-indicator-thumb {
    position: absolute;
    height: 100%;
    background: #3b82f6;
    border-radius: 2px;
    transition: all 0.3s ease;
    min-width: 20px;
}

/* Feedback de toque */
.slider-wrapper.scrolling .slider-track {
    transition: none;
}

/* Estilos para SortableJS */
.sortable-ghost {
    opacity: 0.4;
    background: #f3f4f6;
    border: 2px dashed #d1d5db;
}

.sortable-chosen {
    transform: rotate(2deg);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    z-index: 1000;
}

.sortable-drag {
    transform: rotate(5deg);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
}

/* Estilos para estado travado */
.kanban-locked .kanban-card {
    cursor: not-allowed !important;
    opacity: 0.7;
}

.kanban-locked [data-etapa-id] {
    pointer-events: none;
}

.kanban-locked [data-projeto-id] {
    cursor: not-allowed !important;
    user-select: none;
}

/* Animações para transições suaves */
.kanban-card {
    transition: all 0.2s ease;
}

.kanban-card:hover {
    transform: translateY(-2px);
}

/* Loading overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.loading-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@endsection