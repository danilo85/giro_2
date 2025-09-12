@extends('layouts.app')

@section('title', 'Contato')

@section('content')
<div class="container mx-auto px-4 py-16">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-4xl font-bold text-gray-900 mb-8 text-center">Entre em Contato</h1>
        
        <div class="bg-white rounded-lg shadow-lg p-8">
            <p class="text-lg text-gray-600 text-center mb-8">
                Tem um projeto em mente? Vamos conversar sobre como podemos ajudar você a transformar suas ideias em realidade.
            </p>
            
            <div class="text-center">
                <a href="mailto:contato@exemplo.com" 
                   class="inline-flex items-center px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-envelope mr-2"></i>
                    Enviar E-mail
                </a>
            </div>
        </div>
    </div>
</div>
@endsection