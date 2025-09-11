@extends('layouts.public')

@section('title', $category->meta_title ?: $category->name . ' - Portfólio - ' . config('app.name'))
@section('description', $category->meta_description ?: 'Conheça nossos trabalhos na categoria ' . $category->name . '. ' . Str::limit($category->description, 120))

@push('meta')
<!-- Open Graph -->
<meta property="og:title" content="{{ $category->meta_title ?: $category->name . ' - Portfólio' }}">
<meta property="og:description" content="{{ $category->meta_description ?: 'Conheça nossos trabalhos na categoria ' . $category->name }}">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('portfolio.public.category', $category->slug) }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{ $category->meta_title ?: $category->name . ' - Portfólio' }}">
<meta name="twitter:description" content="{{ $category->meta_description ?: 'Conheça nossos trabalhos na categoria ' . $category->name }}">

<!-- Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CollectionPage",
  "name": "{{ $category->name }}",
  "description": "{{ $category->description }}",
  "url": "{{ route('portfolio.public.category', $category->slug) }}",
  "mainEntity": {
    "@type": "ItemList",
    "numberOfItems": {{ $works->total() }},
    "itemListElement": [
      @foreach($works->take(10) as $work)
      {
        "@type": "CreativeWork",
        "position": {{ $loop->iteration }},
        "name": "{{ $work->title }}",
        "description": "{{ $work->description }}",
        "url": "{{ route('portfolio.public.work', $work->slug) }}"
      }@if(!$loop->last),@endif
      @endforeach
    ]
  },
  "publisher": {
    "@type": "Organization",
    "name": "{{ config('app.name') }}"
  }
}
</script>
@endpush

@section('content')
<!-- Breadcrumb -->
<nav class="bg-gray-50 py-4">
    <div class="container mx-auto px-4">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700">Início</a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('portfolio.public.index') }}" class="text-blue-600 hover:text-blue-700">Portfólio</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-700">{{ $category->name }}</li>
        </ol>
    </div>
</nav>

<!-- Hero Section -->
<section class="py-16 bg-gradient-to-br from-blue-600 to-purple-700 text-white">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-4xl mx-auto">
            <!-- Category Badge -->
            <div class="inline-flex items-center px-6 py-3 rounded-full text-lg font-medium mb-6" 
                 style="background-color: {{ $category->color }}; color: white;">
                <i class="fas fa-tag mr-3"></i>
                {{ $category->name }}
            </div>
            
            <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ $category->name }}</h1>
            
            @if($category->description)
                <p class="text-xl opacity-90 mb-8 max-w-2xl mx-auto">{{ $category->description }}</p>
            @endif
            
            <!-- Stats -->
            <div class="flex justify-center items-center space-x-8 text-sm opacity-90">
                <div class="flex items-center">
                    <i class="fas fa-folder mr-2"></i>
                    <span>{{ $works->total() }} {{ Str::plural('projeto', $works->total()) }}</span>
                </div>
                @if($category->works()->whereNotNull('project_url')->count() > 0)
                    <div class="flex items-center">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        <span>{{ $category->works()->whereNotNull('project_url')->count() }} online</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Category Navigation -->
<section class="bg-white py-6 border-b">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('portfolio.public.index') }}" 
               class="px-6 py-2 rounded-full border-2 border-gray-300 text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all duration-300">
                Todas as categorias
            </a>
            @foreach($allCategories as $cat)
                <a href="{{ route('portfolio.public.category', $cat->slug) }}" 
                   class="px-6 py-2 rounded-full border-2 transition-all duration-300 {{ $cat->id === $category->id ? 'text-white' : 'border-gray-300 text-gray-700 hover:border-blue-500 hover:text-blue-600' }}"
                   @if($cat->id === $category->id) style="background-color: {{ $cat->color }}; border-color: {{ $cat->color }};" @endif>
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Works Grid -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($works->count() > 0)
            <!-- Sort Options -->
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900">
                    Projetos em {{ $category->name }}
                    <span class="text-lg font-normal text-gray-600">({{ $works->total() }})</span>
                </h2>
                
                <div class="flex items-center space-x-4">
                    <label class="text-sm font-medium text-gray-700">Ordenar por:</label>
                    <select onchange="window.location.href = this.value" 
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="{{ route('portfolio.public.category', [$category->slug, 'sort' => 'newest']) }}" 
                                {{ request('sort') === 'newest' || !request('sort') ? 'selected' : '' }}>
                            Mais recentes
                        </option>
                        <option value="{{ route('portfolio.public.category', [$category->slug, 'sort' => 'oldest']) }}" 
                                {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                            Mais antigos
                        </option>
                        <option value="{{ route('portfolio.public.category', [$category->slug, 'sort' => 'title']) }}" 
                                {{ request('sort') === 'title' ? 'selected' : '' }}>
                            Título A-Z
                        </option>
                    </select>
                </div>
            </div>
            
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
                            
                            <!-- Status Badge -->
                            @if($work->project_url)
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1 bg-green-500 text-white text-sm font-medium rounded-full">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        Online
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
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach(array_slice($work->technologies, 0, 3) as $tech)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                            {{ trim($tech) }}
                                        </span>
                                    @endforeach
                                    @if(count($work->technologies) > 3)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            +{{ count($work->technologies) - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Author & Date -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                @if($work->authors->count() > 0)
                                    <div class="flex items-center">
                                        <i class="fas fa-user mr-2"></i>
                                        {{ $work->authors->first()->name }}
                                        @if($work->authors->count() > 1)
                                            <span class="ml-1">+{{ $work->authors->count() - 1 }}</span>
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="flex items-center">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ $work->created_at->format('M Y') }}
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <a href="{{ route('portfolio.public.work', $work->slug) }}" 
                                   class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                                    Ver projeto
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                                
                                @if($work->project_url)
                                    <a href="{{ $work->project_url }}" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center text-green-600 hover:text-green-700 font-medium">
                                        <i class="fas fa-external-link-alt mr-1"></i>
                                        Site
                                    </a>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12">
                {{ $works->appends(request()->query())->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6" 
                         style="background-color: {{ $category->color }}20;">
                        <i class="fas fa-folder-open text-4xl" style="color: {{ $category->color }};"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Nenhum projeto encontrado</h3>
                    <p class="text-gray-600 mb-8">
                        Ainda não temos projetos na categoria <strong>{{ $category->name }}</strong>.
                        <br>Volte em breve para conferir nossas novidades!
                    </p>
                    <a href="{{ route('portfolio.public.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Ver todos os projetos
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Related Categories -->
@if($relatedCategories->count() > 0)
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Outras Categorias</h2>
            <p class="text-gray-600">Explore outros tipos de projetos em nosso portfólio</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedCategories as $relatedCategory)
                <a href="{{ route('portfolio.public.category', $relatedCategory->slug) }}" 
                   class="group block p-6 bg-gray-50 rounded-xl hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4" 
                             style="background-color: {{ $relatedCategory->color }}20;">
                            <i class="fas fa-tag text-xl" style="color: {{ $relatedCategory->color }};"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                {{ $relatedCategory->name }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ $relatedCategory->works_count }} {{ Str::plural('projeto', $relatedCategory->works_count) }}
                            </p>
                        </div>
                    </div>
                    
                    @if($relatedCategory->description)
                        <p class="text-gray-600 text-sm line-clamp-2">{{ $relatedCategory->description }}</p>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-16" style="background: linear-gradient(135deg, {{ $category->color }}dd, {{ $category->color }});">
    <div class="container mx-auto px-4 text-center text-white">
        <h2 class="text-3xl font-bold mb-4">Precisa de um projeto em {{ $category->name }}?</h2>
        <p class="text-xl opacity-90 mb-8">Nossa equipe especializada está pronta para ajudar</p>
        <a href="{{ route('contact') }}" 
           class="inline-flex items-center px-8 py-3 bg-white text-gray-900 font-bold rounded-lg hover:bg-gray-100 transition-colors">
            Solicitar orçamento
            <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
</section>
@endsection

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush