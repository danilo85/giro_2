@extends('layouts.app')


@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header com título, subtítulo e botão voltar -->
    <div class="flex items-center justify-between mb-6 no-print px-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $orcamento->titulo }}</h1>
            <p class="text-gray-500 mt-1 dark:text-gray-400">Orçamento #{{ $orcamento->id }}</p>
        </div>
        <a href="{{ route('orcamentos.index') }}" class="inline-flex items-center px-4 py-2 dark:text-gray-400 text-gray-700 hover:text-gray-900 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <!-- Conteúdo Principal com layout da página pública -->
    <div class="font-open-sans">
        <div class="bg-white p-8 shadow-lg rounded-md relative print-container">
            
            @php
                $statusColors = [
                    'pendente' => 'bg-gray-400',
                    'analisando' => 'bg-yellow-400', 
                    'aprovado' => 'bg-green-500', 
                    'rejeitado' => 'bg-red-500'
                ];
                $statusClass = $statusColors[strtolower($orcamento->status)] ?? $statusColors['pendente'];
            @endphp
            
            <!-- Status Badge (bolinha pulsante) -->
            <div class="absolute top-8 right-8 flex items-center space-x-2 animate-pulse no-print">
                <div class="w-4 h-4 {{ $statusClass }} rounded-full"></div>
            </div>

            <header class="flex justify-between items-start mb-12 ">
                <div class="flex items-start space-x-6">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-black text-gray-800 tracking-tighter">PROPOSTA</h1>
                        <div class="mt-4 text-gray-500">
                            <p>Válido de {{ $orcamento->data_orcamento->format('d/m/Y') }} a {{ $orcamento->data_validade ? $orcamento->data_validade->format('d/m/Y') : 'Não definido' }}</p>
                            <p>Para <span class="font-semibold text-gray-700">{{ $orcamento->cliente->nome }}</span></p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="bg-gray-800 text-white w-12 h-12 flex items-center justify-center rounded-full">
                        <span class="text-l text-white">{{ $orcamento->id }}</span>
                    </div>
                </div>
            </header>

            <main >
                <div class="mb-8 rounded-lg">
                    <h2 class="font-bold text-gray-900 text-lg mb-3">Orçamento:</h2>
                    <p class="text-gray-800 font-medium mb-4">{{ $orcamento->titulo }}</p>
                    <div class="prose max-w-none text-gray-700 leading-relaxed text-justify">
                        {!! $orcamento->descricao !!}
                    </div>
                </div>

                <div class="mb-8 bg-white rounded-lg">
                    <p class="font-bold text-gray-900">Prazo:</p>
                    <p class="text-gray-700 mt-1">Prazo estimado é de {{ $orcamento->prazo_entrega_dias }} dias úteis</p>
                </div>

                <div class="bg-gray-100 p-8 mb-0 border border-gray-200">
                    <div>
                        <p class="text-gray-500 uppercase text-sm font-semibold tracking-wide">Total</p>
                        <p class="text-3xl sm:text-5xl font-black text-gray-900 mt-2">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</p>
                        <p class="mt-3 text-sm text-gray-600">Forma de pagamento: {{ $orcamento->condicoes_pagamento }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 text-white font-bold overflow-hidden shadow-sm print-grid-cols-2">
                    <div class="bg-gray-800 p-6">
                        <p class="text-xs uppercase tracking-wider text-gray-300 mb-2">40% para iniciar</p>
                        <p class="text-xl sm:text-2xl font-bold">1º R$ {{ number_format($orcamento->valor_total * 0.4, 2, ',', '.') }}</p>
                    </div>
                    <div class="bg-gray-700 p-6">
                        <p class="text-xs uppercase tracking-wider text-gray-300 mb-2">60% ao término</p>
                        <p class="text-xl sm:text-2xl font-bold">2º R$ {{ number_format($orcamento->valor_total * 0.6, 2, ',', '.') }}</p>
                    </div>
                </div>
            
                <!-- Botão de Impressão/PDF (sem botões de aprovação/rejeição) -->
                <div class="bg-white p-6 mt-10 no-print">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold text-gray-800">Ações</h3>
                        <p class="text-gray-600 mt-1 mb-4">Visualização interna do orçamento</p>
                        <div class="flex justify-center flex-wrap gap-4 no-print">
                            <button onclick="window.print()" class="w-full sm:w-auto bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-md transition-colors">Imprimir / Salvar PDF</button>
                        </div>
                    </div>
                </div>
            </main>

            {{-- Rodapé com logo, redes sociais e QR code --}}
            <footer class="mt-16 border-t border-gray-200 pt-8">
                <div class="flex flex-col sm:flex-row justify-between items-center sm:items-start space-y-4 sm:space-y-0">
                    {{-- Lado esquerdo: Logo e contatos --}}
                    <div class="flex flex-col space-y-4">
                        {{-- Primeira linha: Logo ícone e contatos --}}
                        <div class="flex items-center justify-center sm:justify-start space-x-4">
                            {{-- Logo ícone do usuário --}}
                            @php
                                $iconLogo = optional($orcamento->cliente->user)->getLogoByType('vertical');
                            @endphp
                            @if($iconLogo && file_exists(storage_path('app/public/' . $iconLogo->caminho)))
                                <img src="{{ $iconLogo->url }}" alt="Logo da Empresa" class="h-16 w-auto rounded">
                            @else
                                <div class="bg-gray-800 text-white px-4 py-2 rounded font-bold text-lg">
                                    <span class="text-white">LOGO</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Lado direito: Logo Ícone --}}
                    <div class="flex justify-center sm:justify-end">
                        @php
                            $logoIcone = optional($orcamento->cliente->user)->getLogoByType('icone');
                        @endphp
                        @if($logoIcone && file_exists(storage_path('app/public/' . $logoIcone->caminho)))
                            <img src="{{ $logoIcone->url }}" alt="Logo Ícone" class="h-16 w-auto rounded">
                        @else
                            <div class="w-16 h-16 bg-gray-200 border-2 border-gray-300 flex items-center justify-center">
                                <span class="text-gray-600 text-xs font-bold">ÍCONE</span>
                            </div>
                        @endif
                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>

<!-- Estilos para impressão -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .container, .container * {
            visibility: visible;
        }
        .container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        nav, .no-print {
            display: none !important;
        }
        .bg-gray-50, .bg-blue-50 {
            background-color:rgb(255, 255, 255) !important;
        }
        .shadow-lg {
            box-shadow: none !important;
        }
        /* Manter layout dos blocos de pagamento na impressão */
        .print-grid-cols-2 {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
        }
        .print-grid-cols-2 > div {
            display: block !important;
        }
    }
</style>
@endsection