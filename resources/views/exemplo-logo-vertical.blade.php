@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-6">Exemplo de Logo Vertical</h1>
        
        {{-- Exemplo de como substituir uma imagem pela logo vertical --}}
        <div class="flex items-center justify-center mb-8">
            @php
                $logoVertical = auth()->user()->getLogoByType('vertical');
            @endphp
            
            @if($logoVertical && file_exists(storage_path('app/public/' . $logoVertical->caminho)))
                <img src="{{ asset('storage/' . $logoVertical->caminho) }}" 
                     alt="Logo Vertical" 
                     class="h-12 w-auto rounded">
            @else
                {{-- Placeholder caso não tenha logo vertical --}}
                <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            @endif
        </div>
        
        <div class="text-center text-gray-600">
            <p>Esta é uma demonstração de como substituir uma imagem pela logo vertical cadastrada no perfil do usuário.</p>
            <p class="mt-2">Para aplicar em seu arquivo específico, substitua a tag &lt;img&gt; existente pelo código acima.</p>
        </div>
        
        {{-- Código de exemplo para copiar --}}
        <div class="mt-8 bg-gray-100 p-4 rounded">
            <h3 class="font-semibold mb-2">Código para substituir:</h3>
            <pre class="text-sm text-gray-700 overflow-x-auto"><code>{{-- Substitua sua tag <img> atual por este código --}}
@php
    $logoVertical = auth()->user()->getLogoByType('vertical');
@endphp

@if($logoVertical && file_exists(storage_path('app/public/' . $logoVertical->caminho)))
    <img src="{{ asset('storage/' . $logoVertical->caminho) }}" 
         alt="Logo Vertical" 
         class="h-12 w-auto rounded">
@else
    {{-- Placeholder caso não tenha logo vertical --}}
    <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
        </svg>
    </div>
@endif</code></pre>
        </div>
    </div>
</div>
@endsection