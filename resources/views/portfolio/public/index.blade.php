@extends('layouts.public')

@section('title', 'Portfólio - ' . config('app.name'))
@section('description', 'Conheça nossos trabalhos e projetos realizados. Portfólio completo com cases de sucesso e soluções criativas.')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-br from-blue-600 to-purple-700 text-white py-20">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-4xl mx-auto">
            <h1 class="text-5xl font-bold mb-6">Nosso Portfólio</h1>
            <p class="text-xl opacity-90 mb-8">Conheça nossos trabalhos e projetos que transformaram ideias em realidade</p>
            
            <!-- Categories Filter -->
            <div class="flex flex-wrap justify-center gap-3 mb-8">
                <a href="{{ route('portfolio.public.index') }}" 
                   class="px-6 py-2 rounded-full border-2 border-white/30 hover:bg-white hover:text-blue-600 transition-all duration-300 {{ !request('category') ? 'bg-white text-blue-600' : 'text-white' }}">
                    Todos
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('portfolio.public.index', ['category' => $category->slug]) }}" 
                       class="px-6 py-2 rounded-full border-2 border-white/30 hover:bg-white hover:text-blue-600 transition-all duration-300 {{ request('category') === $category->slug ? 'bg-white text-blue-600' : 'text-white' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Works Grid -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($works->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($works as $work)
                    <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                        <div class="relative overflow-hidden">
                            @if($work->featured_image)
                                <img src="{{ Storage::url($work->featured_image) }}" 
                                     alt="{{ $work->title }}" 
                                     class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-64 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                    <i class="fas fa-image text-4xl text-gray-400"></i>
                                </div>
                            @endif
                            
                            <!-- Category Badge -->
                            @if($work->category)
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium text-white" 
                                          style="background-color: {{ $work->category->color }}">
                                        {{ $work->category->name }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                                <a href="{{ route('portfolio.public.work', $work->slug) }}">
                                    {{ $work->title }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ $work->description }}</p>
                            
                            <!-- Technologies -->
                            @if($work->technologies && is_array($work->technologies))
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach($work->technologies as $tech)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-md">
                                            {{ trim($tech) }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                            
                            <!-- Author -->
                            @if($work->authors->count() > 0)
                                <div class="flex items-center text-sm text-gray-500 mb-4">
                                    <i class="fas fa-user mr-2"></i>
                                    {{ $work->authors->pluck('name')->join(', ') }}
                                </div>
                            @endif
                            
                            <a href="{{ route('portfolio.public.work', $work->slug) }}" 
                               class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                                Ver projeto
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12">
                {{ $works->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <i class="fas fa-folder-open text-6xl text-gray-300 mb-6"></i>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Nenhum trabalho encontrado</h3>
                    <p class="text-gray-600">
                        @if(request('category'))
                            Não há trabalhos nesta categoria no momento.
                        @else
                            Nosso portfólio está sendo preparado. Volte em breve!
                        @endif
                    </p>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="bg-blue-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Gostou do que viu?</h2>
        <p class="text-xl opacity-90 mb-8">Vamos conversar sobre seu próximo projeto</p>
        <a href="{{ route('public.contact') }}" 
           class="inline-flex items-center px-8 py-3 bg-white text-blue-600 font-bold rounded-lg hover:bg-gray-100 transition-colors">
            Entre em contato
            <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
</section>
@endsection

@push('styles')
<style>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}