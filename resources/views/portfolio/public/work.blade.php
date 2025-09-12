@extends('layouts.app')

@section('title', $work->meta_title ?: $work->title . ' - Portfólio - ' . config('app.name'))
@section('description', $work->meta_description ?: Str::limit($work->description, 160))

@push('meta')
<!-- Open Graph -->
<meta property="og:title" content="{{ $work->meta_title ?: $work->title }}">
<meta property="og:description" content="{{ $work->meta_description ?: Str::limit($work->description, 160) }}">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ route('public.portfolio.work', $work->slug) }}">
@if($work->featured_image)
<meta property="og:image" content="{{ asset('storage/' . $work->featured_image) }}">
@endif

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $work->meta_title ?: $work->title }}">
<meta name="twitter:description" content="{{ $work->meta_description ?: Str::limit($work->description, 160) }}">
@if($work->featured_image)
<meta name="twitter:image" content="{{ asset('storage/' . $work->featured_image) }}">
@endif

<!-- Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CreativeWork",
  "name": "{{ $work->title }}",
  "description": "{{ $work->description }}",
  "url": "{{ route('public.portfolio.work', $work->slug) }}",
  @if($work->featured_image)
  "image": "{{ asset('storage/' . $work->featured_image) }}",
  @endif
  "dateCreated": "{{ $work->created_at->toISOString() }}",
  "author": [
    @foreach($work->authors as $author)
    {
      "@type": "Person",
      "name": "{{ $author->name }}"
    }@if(!$loop->last),@endif
    @endforeach
  ],
  @if($work->category)
  "genre": "{{ $work->category->name }}",
  @endif
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
            <li><a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-700">Início</a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('public.portfolio.index') }}" class="text-blue-600 hover:text-blue-700">Portfólio</a></li>
            @if($work->category)
                <li class="text-gray-400">/</li>
                <li><a href="{{ route('public.portfolio.category', $work->category->slug) }}" class="text-blue-600 hover:text-blue-700">{{ $work->category->name }}</a></li>
            @endif
            <li class="text-gray-400">/</li>
            <li class="text-gray-700">{{ $work->title }}</li>
        </ol>
    </div>
</nav>

<!-- Hero Section -->
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Category Badge -->
            @if($work->category)
                <div class="mb-6">
                    <a href="{{ route('public.portfolio.category', $work->category->slug) }}" 
                       class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium text-white hover:opacity-90 transition-opacity" 
                       style="background-color: {{ $work->category->color }}">
                        <i class="fas fa-tag mr-2"></i>
                        {{ $work->category->name }}
                    </a>
                </div>
            @endif
            
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">{{ $work->title }}</h1>
            
            <div class="flex flex-wrap items-center gap-6 text-gray-600 mb-8">
                <!-- Authors -->
                @if($work->authors->count() > 0)
                    <div class="flex items-center">
                        <i class="fas fa-user mr-2"></i>
                        <span>{{ $work->authors->pluck('name')->join(', ') }}</span>
                    </div>
                @endif
                
                <!-- Date -->
                <div class="flex items-center">
                    <i class="fas fa-calendar mr-2"></i>
                    <span>{{ $work->created_at->format('d/m/Y') }}</span>
                </div>
                
                <!-- Client -->
                @if($work->client)
                    <div class="flex items-center">
                        <i class="fas fa-building mr-2"></i>
                        <span>{{ $work->client->name }}</span>
                    </div>
                @endif
            </div>
            
            <p class="text-xl text-gray-700 leading-relaxed">{{ $work->description }}</p>
        </div>
    </div>
</section>

<!-- Featured Image -->
@if($work->featured_image)
<section class="py-8">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <img src="{{ asset('storage/' . $work->featured_image) }}" 
                 alt="{{ $work->title }}" 
                 class="w-full rounded-xl shadow-lg">
        </div>
    </div>
</section>
@endif

<!-- Content -->
<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    @if($work->content)
                        <div class="prose prose-lg max-w-none">
                            {!! $work->content !!}
                        </div>
                    @endif
                    
                    <!-- Gallery -->
                    @if($work->images->count() > 0)
                        <div class="mt-12">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Galeria</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($work->images as $image)
                                    <div class="group cursor-pointer" onclick="openLightbox('{{ asset('storage/' . $image->path) }}', '{{ $image->alt_text }}')">
                                        <img src="{{ asset('storage/' . $image->path) }}" 
                                             alt="{{ $image->alt_text }}" 
                                             class="w-full h-64 object-cover rounded-lg shadow-md group-hover:shadow-lg transition-shadow">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-xl p-6 sticky top-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Detalhes do Projeto</h3>
                        
                        <!-- Technologies -->
                        @if($work->technologies && is_array($work->technologies))
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-700 mb-2">Tecnologias</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($work->technologies as $tech)
                                        <span class="px-3 py-1 bg-white text-gray-700 text-sm rounded-md border">
                                            {{ trim($tech) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <!-- Project URL -->
                        @if($work->project_url)
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-700 mb-2">Link do Projeto</h4>
                                <a href="{{ $work->project_url }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                                    Visitar site
                                    <i class="fas fa-external-link-alt ml-2 text-sm"></i>
                                </a>
                            </div>
                        @endif
                        
                        <!-- Repository URL -->
                        @if($work->repository_url)
                            <div class="mb-6">
                                <h4 class="font-medium text-gray-700 mb-2">Repositório</h4>
                                <a href="{{ $work->repository_url }}" 
                                   target="_blank" 
                                   rel="noopener noreferrer"
                                   class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                                    Ver código
                                    <i class="fab fa-github ml-2"></i>
                                </a>
                            </div>
                        @endif
                        
                        <!-- Share -->
                        <div class="border-t pt-6">
                            <h4 class="font-medium text-gray-700 mb-3">Compartilhar</h4>
                            <div class="flex space-x-3">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('public.portfolio.work', $work->slug)) }}" 
                                   target="_blank" 
                                   class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:bg-blue-700 transition-colors">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('public.portfolio.work', $work->slug)) }}&text={{ urlencode($work->title) }}" 
                                   target="_blank" 
                                   class="w-10 h-10 bg-blue-400 text-white rounded-lg flex items-center justify-center hover:bg-blue-500 transition-colors">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('public.portfolio.work', $work->slug)) }}" 
                                   target="_blank" 
                                   class="w-10 h-10 bg-blue-700 text-white rounded-lg flex items-center justify-center hover:bg-blue-800 transition-colors">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <button onclick="copyToClipboard('{{ route('public.portfolio.work', $work->slug) }}')" 
                                        class="w-10 h-10 bg-gray-600 text-white rounded-lg flex items-center justify-center hover:bg-gray-700 transition-colors">
                                    <i class="fas fa-link"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Works -->
@if($relatedWorks->count() > 0)
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Projetos Relacionados</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($relatedWorks as $relatedWork)
                    <article class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                        <div class="relative overflow-hidden">
                            @if($relatedWork->featured_image)
                                <img src="{{ asset('storage/' . $relatedWork->featured_image) }}" 
                                     alt="{{ $relatedWork->title }}" 
                                     class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                    <i class="fas fa-image text-3xl text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                                <a href="{{ route('public.portfolio.work', $relatedWork->slug) }}">
                                    {{ $relatedWork->title }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-600 text-sm line-clamp-2">{{ $relatedWork->description }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="bg-blue-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Gostou deste projeto?</h2>
        <p class="text-xl opacity-90 mb-8">Vamos criar algo incrível juntos</p>
        <a href="{{ route('public.contact') }}" 
           class="inline-flex items-center px-8 py-3 bg-white text-blue-600 font-bold rounded-lg hover:bg-gray-100 transition-colors">
            Solicitar orçamento
            <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeLightbox()" class="absolute -top-12 right-0 text-white text-2xl hover:text-gray-300">
            <i class="fas fa-times"></i>
        </button>
        <img id="lightbox-image" src="" alt="" class="max-w-full max-h-full object-contain">
    </div>
</div>
@endsection

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose {
    max-width: none;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    color: #1f2937;
    font-weight: 700;
}

.prose p {
    margin-bottom: 1rem;
    line-height: 1.7;
}

.prose ul, .prose ol {
    margin-bottom: 1rem;
    padding-left: 1.5rem;
}

.prose li {
    margin-bottom: 0.5rem;
}

.prose a {
    color: #2563eb;
    text-decoration: underline;
}

.prose a:hover {
    color: #1d4ed8;
}
</style>
@endpush

@push('scripts')
<script>
function openLightbox(src, alt) {
    const lightbox = document.getElementById('lightbox');
    const image = document.getElementById('lightbox-image');
    
    image.src = src;
    image.alt = alt;
    lightbox.classList.remove('hidden');
    lightbox.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    const lightbox = document.getElementById('lightbox');
    lightbox.classList.add('hidden');
    lightbox.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Close lightbox on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});

// Close lightbox on background click
document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLightbox();
    }
});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const button = event.target.closest('button');
        const originalIcon = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.remove('bg-gray-600', 'hover:bg-gray-700');
        button.classList.add('bg-green-600');
        
        setTimeout(() => {
            button.innerHTML = originalIcon;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-gray-600', 'hover:bg-gray-700');
        }, 2000);
    });
}
</script>
@endpush