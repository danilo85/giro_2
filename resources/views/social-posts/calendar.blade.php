@extends('layouts.app')

@section('title', 'Calendário - Redes Sociais')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header com Tags de Navegação -->
    <div class="mb-6">
        <!-- Tags de Navegação Rápida -->
        <div class="flex flex-wrap gap-2 mb-4">
            <a href="{{ route('social-posts.index') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                <i class="fas fa-list mr-2"></i>
                Todos os Posts
            </a>
            <a href="{{ route('social-posts.calendar') }}" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                <i class="fas fa-calendar mr-2"></i>
                Calendário
            </a>
        </div>
    </div>
    
    <!-- Header -->
    <div class="mb-6 sm:mb-8">
        <div class="flex items-center justify-between mb-6 sm:mb-8">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Calendário de Posts</h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Visualize e gerencie seus posts agendados</p>
            </div>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Total de Posts -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total de Posts</p>
                    <p class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Posts Agendados -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Posts Agendados</p>
                    <p class="text-2xl font-bold">{{ $stats['agendados'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Este Mês -->
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Este Mês</p>
                    <p class="text-2xl font-bold">{{ $stats['este_mes'] ?? 0 }}</p>
                </div>
                <div class="p-3 bg-white bg-opacity-20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendário -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <!-- Header do Calendário -->
        <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">
                    {{ $currentDate->locale('pt_BR')->isoFormat('MMMM [de] YYYY') }}
                </h2>
                <div class="flex items-center space-x-1 sm:space-x-2">
                    <a href="{{ route('social-posts.calendar', ['year' => $currentDate->copy()->subMonth()->year, 'month' => $currentDate->copy()->subMonth()->month]) }}" 
                       class="p-3 sm:p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors touch-manipulation">
                        <svg class="w-6 h-6 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('social-posts.calendar', ['year' => $currentDate->copy()->addMonth()->year, 'month' => $currentDate->copy()->addMonth()->month]) }}" 
                       class="p-3 sm:p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors touch-manipulation">
                        <svg class="w-6 h-6 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Grid do Calendário -->
        <div class="p-4 sm:p-6">
            <!-- Cabeçalho dos dias da semana - apenas desktop -->
            <div class="hidden md:grid grid-cols-7 gap-1 mb-2">
                @foreach(['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'] as $day)
                <div class="p-3 text-center text-sm font-medium text-gray-500 dark:text-gray-400">
                    {{ $day }}
                </div>
                @endforeach
            </div>

            <!-- Grid dos dias - Desktop -->
            <div class="hidden md:grid grid-cols-7 gap-1">
                @php
                    $startOfMonth = $currentDate->copy()->startOfMonth();
                    $endOfMonth = $currentDate->copy()->endOfMonth();
                    $startOfCalendar = $startOfMonth->copy()->startOfWeek();
                    $endOfCalendar = $endOfMonth->copy()->endOfWeek();
                    $currentDay = $startOfCalendar->copy();
                @endphp

                @while($currentDay <= $endOfCalendar)
                    @php
                        $dayKey = $currentDay->format('Y-m-d');
                        $dayPosts = $posts->get($dayKey, collect());
                        $isCurrentMonth = $currentDay->month === $currentDate->month;
                        $isToday = $currentDay->isToday();
                        
                        // Verificar se há posts arquivados ou publicados neste dia
                        $hasArquivadoOrPublicado = $dayPosts->whereIn('status', ['arquivado', 'publicado'])->count() > 0;
                        
                        // Debug: adicionar informação sobre posts encontrados
                        $debugInfo = $dayPosts->count() > 0 ? 'data-posts="' . $dayPosts->count() . '" data-status="' . $dayPosts->pluck('status')->implode(',') . '"' : '';
                        
                        // Definir classes de fundo
                        $backgroundClass = '';
                        $inlineStyle = '';
                        if ($hasArquivadoOrPublicado) {
                            $backgroundClass = $isCurrentMonth ? 'bg-green-100 border-green-300 dark:bg-green-900/30 dark:border-green-700' : 'bg-green-200 border-green-400 dark:bg-green-800/40 dark:border-green-600';
                            $inlineStyle = $isCurrentMonth ? 'background-color: #dcfce7; border-color: #86efac;' : 'background-color: #bbf7d0; border-color: #4ade80;';
                        } else {
                            $backgroundClass = $isCurrentMonth ? 'bg-white dark:bg-gray-800' : 'bg-gray-50 dark:bg-gray-700';
                        }
                    @endphp

                    <div class="relative min-h-[120px] border border-gray-200 dark:border-gray-600 rounded-lg p-2 {{ $backgroundClass }} {{ $isToday ? 'ring-2 ring-blue-500' : '' }}" style="{{ $inlineStyle }}" {!! $debugInfo !!} title="Posts: {{ $dayPosts->count() }} | Publicados/Arquivados: {{ $dayPosts->whereIn('status', ['arquivado', 'publicado'])->count() }}">
                        <!-- Número do dia -->
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium {{ $isCurrentMonth ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}">
                                {{ $currentDay->day }}
                            </span>
                            
                            <!-- Menu de 3 pontinhos -->
                            @if($isCurrentMonth)
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                    </svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 top-6 z-10 w-48 bg-white dark:bg-gray-700 rounded-md shadow-lg border border-gray-200 dark:border-gray-600">
                                    <a href="{{ route('social-posts.create', ['date' => $currentDay->format('Y-m-d')]) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-t-md">
                                        <i class="fas fa-plus mr-2"></i>
                                        Criar Post
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Posts do dia -->
                        <div class="space-y-1">
                            @foreach($dayPosts->take(3) as $post)
                            <a href="{{ route('social-posts.show', $post) }}" class="block">
                                <div class="flex items-center space-x-2 p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                    @php
                                        $statusColors = [
                                            'rascunho' => 'bg-yellow-400',
                                            'arquivado' => 'bg-orange-400',
                                            'publicado' => 'bg-green-400'
                                        ];
                                        $color = $statusColors[$post->status] ?? 'bg-gray-400';
                                    @endphp
                                    <div class="w-2 h-2 rounded-full {{ $color }} flex-shrink-0"></div>
                                    <span class="text-xs text-gray-600 dark:text-gray-300 truncate">{{ $post->titulo }}</span>
                                </div>
                            </a>
                            @endforeach
                            
                            @if($dayPosts->count() > 3)
                            <div class="text-xs text-gray-500 dark:text-gray-400 pl-4">
                                +{{ $dayPosts->count() - 3 }} mais
                            </div>
                            @endif
                        </div>
                    </div>

                    @php
                        $currentDay->addDay();
                    @endphp
                @endwhile
            </div>

            <!-- Lista Mobile -->
            <div class="md:hidden space-y-3">
                @php
                    $startOfMonth = $currentDate->copy()->startOfMonth();
                    $endOfMonth = $currentDate->copy()->endOfMonth();
                    $currentDay = $startOfMonth->copy();
                @endphp

                @while($currentDay <= $endOfMonth)
                    @php
                        $dayKey = $currentDay->format('Y-m-d');
                        $dayPosts = $posts->get($dayKey, collect());
                        $isToday = $currentDay->isToday();
                        $dayOfWeek = $currentDay->locale('pt_BR')->dayName;
                        
                        // Verificar se há posts arquivados ou publicados neste dia
                        $hasArquivadoOrPublicado = $dayPosts->whereIn('status', ['arquivado', 'publicado'])->count() > 0;
                    @endphp

                    <div class="bg-white dark:bg-gray-800 rounded-lg border {{ $isToday ? 'border-blue-500 ring-2 ring-blue-200 dark:ring-blue-800' : 'border-gray-200 dark:border-gray-700' }} {{ $hasArquivadoOrPublicado ? 'bg-green-50 dark:bg-green-900/20 border-green-300 dark:border-green-700' : '' }} p-4 shadow-sm">
                        <!-- Header do dia -->
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="text-center">
                                    <div class="text-2xl font-bold {{ $isToday ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-white' }}">
                                        {{ $currentDay->day }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                                        {{ $dayOfWeek }}
                                    </div>
                                </div>
                                @if($isToday)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    Hoje
                                </span>
                                @endif
                            </div>
                            
                            <!-- Botão criar post -->
                            <a href="{{ route('social-posts.create', ['date' => $currentDay->format('Y-m-d')]) }}" 
                               class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors touch-manipulation">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Criar
                            </a>
                        </div>

                        <!-- Posts do dia -->
                        @if($dayPosts->count() > 0)
                        <div class="space-y-2">
                            @foreach($dayPosts as $post)
                            <a href="{{ route('social-posts.show', $post) }}" class="block">
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                                        @php
                                            $statusColors = [
                                                'rascunho' => 'bg-yellow-400',
                                                'arquivado' => 'bg-orange-400',
                                                'publicado' => 'bg-green-400'
                                            ];
                                            $statusLabels = [
                                                'rascunho' => 'Rascunho',
                                                'arquivado' => 'Arquivado',
                                                'publicado' => 'Publicado'
                                            ];
                                            $color = $statusColors[$post->status] ?? 'bg-gray-400';
                                            $label = $statusLabels[$post->status] ?? 'Desconhecido';
                                        @endphp
                                        <div class="w-3 h-3 rounded-full {{ $color }} flex-shrink-0"></div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $post->titulo }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $label }}</p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum post agendado</p>
                        </div>
                        @endif
                    </div>

                    @php
                        $currentDay->addDay();
                    @endphp
                @endwhile
            </div>
        </div>
    </div>
</div>

<!-- Floating Add Button - apenas desktop, mobile tem botões inline -->
<div class="hidden md:block fixed bottom-6 right-6 z-50">
    <a href="{{ route('social-posts.create') }}" class="group inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-full shadow-lg hover:shadow-2xl transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-500/50 transform hover:scale-110" 
       title="Novo Post">
        <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
    </a>
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
<script>
    // Inicializar Alpine.js data
    document.addEventListener('alpine:init', () => {
        Alpine.data('calendar', () => ({
            currentMonth: {{ $currentDate->month }},
            currentYear: {{ $currentDate->year }},
            posts: @json($posts),
            
            navigateMonth(direction) {
                if (direction === 'prev') {
                    if (this.currentMonth === 1) {
                        this.currentMonth = 12;
                        this.currentYear--;
                    } else {
                        this.currentMonth--;
                    }
                } else {
                    if (this.currentMonth === 12) {
                        this.currentMonth = 1;
                        this.currentYear++;
                    } else {
                        this.currentMonth++;
                    }
                }
                
                // Recarregar página com nova data
                window.location.href = `{{ route('social-posts.calendar') }}?year=${this.currentYear}&month=${this.currentMonth}`;
            },
            
            getPostsForDay(day) {
                const dateKey = `${this.currentYear}-${String(this.currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                return this.posts[dateKey] || [];
            },
            
            createPostForDay(day) {
                const date = `${this.currentYear}-${String(this.currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const time = '09:00'; // Horário padrão
                window.location.href = `{{ route('social-posts.create') }}?date=${date}&time=${time}`;
            },
            
            hasPostsForDay(day) {
                return this.getPostsForDay(day).length > 0;
            },
            
            getMonthName(month) {
                const months = [
                    'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                ];
                return months[month - 1];
            }
        }));
        
        // Componente para dropdown dos 3 pontinhos
        Alpine.data('dayDropdown', (day) => ({
            open: false,
            day: day,
            
            toggle() {
                this.open = !this.open;
            },
            
            close() {
                this.open = false;
            },
            
            createPost() {
                const calendar = this.$store.calendar || this;
                calendar.createPostForDay(this.day);
                this.close();
            }
        }));
    });
    
    // Fechar dropdowns quando clicar fora
    document.addEventListener('click', function(event) {
        if (!event.target.closest('[x-data*="dayDropdown"]')) {
            // Fechar todos os dropdowns abertos
            document.querySelectorAll('[x-data*="dayDropdown"]').forEach(el => {
                if (el._x_dataStack && el._x_dataStack[0].open) {
                    el._x_dataStack[0].close();
                }
            });
        }
    });
</script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@endsection