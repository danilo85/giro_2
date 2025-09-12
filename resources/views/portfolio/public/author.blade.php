@extends('layouts.public')

@section('title', $author->name . ' - Portfólio - ' . config('app.name'))
@section('description', 'Conheça os trabalhos de ' . $author->name . ' em nosso portfólio. Projetos e cases de sucesso desenvolvidos por nosso especialista.')

@push('meta')
<!-- Open Graph -->
<meta property="og:title" content="{{ $author->name }} - Portfólio">
<meta property="og:description" content="Conheça os trabalhos de {{ $author->name }} em nosso portfólio">
<meta property="og:type" content="profile">
<meta property="og:url" content="{{ route('public.portfolio.author', $author->id) }}">
@if($author->avatar)
<meta property="og:image" content="{{ asset('storage/' . $author->avatar) }}">
@endif

<!-- Twitter Card -->
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="{{ $author->name }} - Portfólio">
<meta name="twitter:description" content="Conheça os trabalhos de {{ $author->name }} em nosso portfólio">
@if($author->avatar)
<meta name="twitter:image" content="{{ asset('storage/' . $author->avatar) }}">
@endif

<!-- Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "{{ $author->name }}",
  "url": "{{ route('public.portfolio.author', $author->id) }}",
  @if($author->email)
  "email": "{{ $author->email }}",
  @endif
  @if($author->avatar)
  "image": "{{ asset('storage/' . $author->avatar) }}",
  @endif
  "worksFor": {
    "@type": "Organization",
    "name": "{{ config('app.name') }}"
  },
  "mainEntityOfPage": {
    "@type": "ProfilePage",
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
          "url": "{{ route('public.portfolio.work', $work->slug) }}"
        }@if(!$loop->last),@endif
        @endforeach
      ]
    }
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
            <li><a href="{{ route('public.portfolio.index') }}" class="text-blue-600 hover:text-blue-700">Portfólio</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-gray-700">{{ $author->name }}</li>
        </ol>
    </div>
</nav>

<!-- Author Profile Section -->
<section class="py-16 bg-gradient-to-br from-blue-600 to-purple-700 text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Avatar -->
            <div class="mb-8">
                @if($author->avatar)
                    <img src="{{ asset('storage/' . $author->avatar) }}" 
                         alt="{{ $author->name }}" 
                         class="w-32 h-32 rounded-full mx-auto border-4 border-white/20 shadow-lg">
                @else
                    <div class="w-32 h-32 rounded-full mx-auto border-4 border-white/20 bg-white/10 flex items-center justify-center">
                        <i class="fas fa-user text-4xl text-white/70"></i>
                    </div>
                @endif
            </div>
            
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $author->name }}</h1>
            
            @if($author->bio)
                <p class="text-xl opacity-90 mb-8 max-w-2xl mx-auto">{{ $author->bio }}</p>
            @endif
            
            <!-- Stats -->
            <div class="flex flex-wrap justify-center items-center gap-8 text-sm opacity-90">
                <div class="flex items-center">
                    <i class="fas fa-briefcase mr-2"></i>
                    <span>{{ $works->total() }} {{ Str::plural('projeto', $works->total()) }}</span>
                </div>
                
                @if($categoriesCount > 0)
                    <div class="flex items-center">
                        <i class="fas fa-tags mr-2"></i>
                        <span>{{ $categoriesCount }} {{ Str::plural('categoria', $categoriesCount) }}</span>
                    </div>
                @endif
                
                @if($author->created_at)
                    <div class="flex items-center">
                        <i class="fas fa-calendar mr-2"></i>
                        <span>Desde {{ $author->created_at->format('M Y') }}</span>
                    </div>
                @endif
            </div>
            
            <!-- Contact Info -->
            @if($author->email || $author->phone)
                <div class="flex flex-wrap justify-center items-center gap-4 mt-8">
                    @if($author->email)
                        <a href="mailto:{{ $author->email }}" 
                           class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 rounded-lg transition-colors">
                            <i class="fas fa-envelope mr-2"></i>
                            Enviar e-mail
                        </a>
                    @endif
                    
                    @if($author->phone)
                        <a href="tel:{{ $author->phone }}" 
                           class="inline-flex items-center px-6 py-3 bg-white/10 hover:bg-white/20 rounded-lg transition-colors">
                            <i class="fas fa-phone mr-2"></i>
                            Ligar
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Categories Filter -->
@if($authorCategories->count() > 1)
<section class="bg-white py-6 border-b">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('public.portfolio.author', $author->id) }}" 
               class="px-6 py-2 rounded-full border-2 transition-all duration-300 {{ !request('category') ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-300 text-gray-700 hover:border-blue-500 hover:text-blue-600' }}">
                Todos os projetos
            </a>
            @foreach($authorCategories as $category)
                <a href="{{ route('public.portfolio.author', [$author->id, 'category' => $category->slug]) }}" 
                   class="px-6 py-2 rounded-full border-2 transition-all duration-300 {{ request('category') === $category->slug ? 'text-white' : 'border-gray-300 text-gray-700 hover:border-blue-500 hover:text-blue-600' }}"
                   @if(request('category') === $category->slug) style="background-color: {{ $category->color }}; border-color: {{ $category->color }};" @endif>
                    {{ $category->name }}
                    <span class="ml-2 text-xs opacity-75">({{ $category->works_count }})</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Works Grid -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($works->count() > 0)
            <!-- Section Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        Projetos de {{ $author->name }}
                        @if(request('category'))
                            em {{ $authorCategories->where('slug', request('category'))->first()->name ?? '' }}
                        @endif
                    </h2>
                    <p class="text-gray-600 mt-1">{{ $works->total() }} {{ Str::plural('projeto', $works->total()) }} encontrado{{ $works->total() > 1 ? 's' : '' }}</p>
                </div>
                
                <div class="flex items-center space-x-4">
                    <label class="text-sm font-medium text-gray-700">Ordenar por:</label>
                    <select onchange="window.location.href = this.value" 
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="{{ route('public.portfolio.author', array_merge([$author->id], request()->query(), ['sort' => 'newest'])) }}" 
                                {{ request('sort') === 'newest' || !request('sort') ? 'selected' : '' }}>
                            Mais recentes
                        </option>
                        <option value="{{ route('public.portfolio.author', array_merge([$author->id], request()->query(), ['sort' => 'oldest'])) }}" 
                                {{ request('sort') === 'oldest' ? 'selected' : '' }}>
                            Mais antigos
                        </option>
                        <option value="{{ route('public.portfolio.author', array_merge([$author->id], request()->query(), ['sort' => 'title'])) }}" 
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
                                <img src="{{ asset('storage/' . $work->featured_image) }}" 
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
                                    <a href="{{ route('public.portfolio.category', $work->category->slug) }}" 
                                       class="px-3 py-1 rounded-full text-sm font-medium text-white hover:opacity-90 transition-opacity" 
                                       style="background-color: {{ $work->category->color }}">
                                        {{ $work->category->name }}
                                    </a>
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
                                <a href="{{ route('public.portfolio.work', $work->slug) }}">
                                    {{ $work->title }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ $work->description }}</p>
                            
                            <!-- Technologies -->
                            @if($work->technologies && is_array($work->technologies))
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach(array_slice($work->technologies, 0, 3) as $tech)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-md">
                                            {{ trim($tech) }}
                                        </span>
                                    @endforeach
                                    @if(count($work->technologies) > 3)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-md">
                                            +{{ count($work->technologies) - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                            
                            <!-- Collaborators & Date -->
                            <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                @if($work->authors->count() > 1)
                                    <div class="flex items-center">
                                        <i class="fas fa-users mr-2"></i>
                                        <span>{{ $work->authors->count() }} colaboradores</span>
                                    </div>
                                @else
                                    <div class="flex items-center">
                                        <i class="fas fa-user mr-2"></i>
                                        <span>Projeto individual</span>
                                    </div>
                                @endif
                                
                                <div class="flex items-center">
                                    <i class="fas fa-calendar mr-2"></i>
                                    {{ $work->created_at->format('M Y') }}
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <a href="{{ route('public.portfolio.work', $work->slug) }}" 
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
                    @if($author->avatar)
                        <img src="{{ asset('storage/' . $author->avatar) }}" 
                             alt="{{ $author->name }}" 
                             class="w-24 h-24 rounded-full mx-auto mb-6 opacity-50">
                    @else
                        <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-user text-4xl text-gray-400"></i>
                        </div>
                    @endif
                    
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Nenhum projeto encontrado</h3>
                    <p class="text-gray-600 mb-8">
                        @if(request('category'))
                            {{ $author->name }} ainda não tem projetos na categoria selecionada.
                        @else
                            {{ $author->name }} ainda não tem projetos publicados.
                        @endif
                        <br>Volte em breve para conferir as novidades!
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @if(request('category'))
                            <a href="{{ route('public.portfolio.author', $author->id) }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Ver todos os projetos
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        @endif
                        
                        <a href="{{ route('public.portfolio.index') }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                            Explorar portfólio
                            <i class="fas fa-search ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Other Authors -->
@if($otherAuthors->count() > 0)
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Outros Especialistas</h2>
            <p class="text-gray-600">Conheça outros profissionais da nossa equipe</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($otherAuthors as $otherAuthor)
                <a href="{{ route('public.portfolio.author', $otherAuthor->id) }}" 
                   class="group block p-6 bg-gray-50 rounded-xl hover:shadow-lg transition-all duration-300 text-center">
                    @if($otherAuthor->avatar)
                        <img src="{{ asset('storage/' . $otherAuthor->avatar) }}" 
                             alt="{{ $otherAuthor->name }}" 
                             class="w-16 h-16 rounded-full mx-auto mb-4 group-hover:scale-105 transition-transform">
                    @else
                        <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center mx-auto mb-4 group-hover:scale-105 transition-transform">
                            <i class="fas fa-user text-2xl text-gray-400"></i>
                        </div>
                    @endif
                    
                    <h3 class="font-bold text-gray-900 group-hover:text-blue-600 transition-colors mb-2">
                        {{ $otherAuthor->name }}
                    </h3>
                    
                    <p class="text-sm text-gray-500 mb-2">
                        {{ $otherAuthor->works_count }} {{ Str::plural('projeto', $otherAuthor->works_count) }}
                    </p>
                    
                    @if($otherAuthor->bio)
                        <p class="text-gray-600 text-sm line-clamp-2">{{ $otherAuthor->bio }}</p>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="bg-blue-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Impressionado com o trabalho de {{ $author->name }}?</h2>
        <p class="text-xl opacity-90 mb-8">Nossa equipe está pronta para criar algo incrível para você</p>
        <a href="{{ route('public.contact') }}" 
           class="inline-flex items-center px-8 py-3 bg-white text-blue-600 font-bold rounded-lg hover:bg-gray-100 transition-colors">
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