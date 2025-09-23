<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Proposta #{{ $orcamento->numero }} - {{ $orcamento->cliente->name }}</title>
    
    {{-- Importação da fonte Open Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    
    {{-- Carregamento do Tailwind CSS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Alpine.js --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        .font-open-sans {
            font-family: 'Open Sans', sans-serif;
        }
    </style>
</head>
<body class="h-full bg-gray-50 font-open-sans">

    {{-- Estilos específicos para a impressão --}}
    <style>
        @media print {
            body {
                background-color: #fff !important;
            }
            .no-print {
                display: none !important;
            }
            .print-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-bg-transparent {
                background-color: transparent !important;
            }
            .print-grid-cols-2 {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }
    </style>

    {{-- Notificação "Toast" de SUCESSO --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
             class="no-print fixed top-5 right-5 z-50 rounded-md bg-green-600 px-4 py-3 text-sm font-bold text-white shadow-lg font-open-sans">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Botão de Desfazer --}}
    @if (session()->has('undo_action'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 8000)" x-show="show" x-transition
            class="no-print fixed bottom-5 right-5 z-50 rounded-md bg-gray-800 px-4 py-3 text-sm text-white shadow-lg font-open-sans">
            <div class="flex items-center gap-4">
                <p>{{ session('undo_action') }}</p>
                <button wire:click="revertStatus" class="font-bold underline hover:text-yellow-400">Desfazer</button>
            </div>
        </div>
    @endif

    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 font-open-sans">
        <div class="bg-white p-8 md:p-12 shadow-lg rounded-md relative print-container">
            
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

            <header class="flex justify-between items-start mb-12">
                <div class="flex items-start space-x-6">
    
                    <div>
                        <h1 class="text-3xl sm:text-6xl font-black text-gray-800 tracking-tighter">PROPOSTA</h1>
                        <div class="mt-4 text-gray-500 text-sm sm:text-base">
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

            <main>
                <div class="mb-8">
                    <h2 class="font-bold text-gray-900 text-lg mb-3">Orçamento:</h2>
                    <p class="text-gray-800 font-medium mb-4">{{ $orcamento->titulo }}</p>
                    <div class="prose max-w-none text-gray-700 leading-relaxed text-justify">
                        {!! $orcamento->descricao !!}
                    </div>
                </div>

                <div class="mb-8">
                    <p class="font-bold text-gray-900">Prazo:</p>
                    <p class="text-gray-700 mt-1">Prazo estimado é de {{ $orcamento->prazo_entrega_dias }} dias úteis</p>
                </div>

                <div class="bg-gray-50 p-4 sm:p-8 mb-0 border border-gray-200">
                    <div>
                        <p class="text-gray-500 uppercase text-sm font-semibold tracking-wide">Total</p>
                        <p class="text-3xl sm:text-5xl font-black text-gray-900 mt-2">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</p>
                        <p class="mt-3 text-sm text-gray-600">Forma de pagamento: {{ $orcamento->condicoes_pagamento }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 text-white font-bold overflow-hidden shadow-sm print-grid-cols-2">
                    <div class="bg-gray-800 p-4 sm:p-6">
                        <p class="text-xs uppercase tracking-wider text-gray-300 mb-2">40% para iniciar</p>
                        <p class="text-lg sm:text-2xl font-bold">1º R$ {{ number_format($orcamento->valor_total * 0.4, 2, ',', '.') }}</p>
                    </div>
                    <div class="bg-gray-700 p-4 sm:p-6">
                        <p class="text-xs uppercase tracking-wider text-gray-300 mb-2">60% ao término</p>
                        <p class="text-lg sm:text-2xl font-bold">2º R$ {{ number_format($orcamento->valor_total * 0.6, 2, ',', '.') }}</p>
                    </div>
                </div>
            

                <div class="bg-white p-6 mt-10 no-print" x-data="{ confirmingApproval: false, confirmingRejection: false }">
                     @if(strtolower($orcamento->status) === 'analisando')
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-gray-800">Ações</h3>
                            <p class="text-gray-600 mt-1 mb-4">O que você gostaria de fazer em relação a esta proposta?</p>
                            <div class="flex flex-col sm:flex-row justify-center flex-wrap gap-4 no-print">
                                <button @click="confirmingApproval = true" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-md transition-colors">Aprovar Proposta</button>
                                <button @click="confirmingRejection = true" class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-md transition-colors">Rejeitar</button>
                                <button onclick="window.print()" class="w-full sm:w-auto bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-md transition-colors">Imprimir / Salvar PDF</button>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <p class="text-lg font-semibold italic text-gray-600">Este orçamento foi {{ strtolower($orcamento->status) }}{{ $orcamento->updated_at ? ' em ' . $orcamento->updated_at->format('d/m/Y') : '' }}.</p>
                             <div class="flex justify-center flex-wrap gap-4 mt-4 no-print">
                                <button onclick="window.print()" class="w-full sm:w-auto bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-md transition-colors">Imprimir / Salvar PDF</button>
                            </div>
                        </div>
                    @endif

                    <!-- Modal de Rejeição -->
                    <div x-show="confirmingRejection" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 no-print" style="display: none;">
                        <div @click.away="confirmingRejection = false" class="bg-white rounded-lg p-6 max-w-sm mx-auto text-center">
                            <h3 class="text-lg font-bold">Confirmar Rejeição</h3>
                            <p class="mt-2 text-sm text-gray-600">Tem certeza que deseja rejeitar esta proposta?</p>
                            <div class="mt-4 flex justify-center gap-4">
                                <button @click="confirmingRejection = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Cancelar</button>
                                <button onclick="rejeitarOrcamento()" @click="confirmingRejection = false" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">Sim, Rejeitar</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal de Aprovação -->
                    <div x-show="confirmingApproval" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 no-print" style="display: none;">
                        <div @click.away="confirmingApproval = false" class="bg-white rounded-lg p-6 max-w-sm mx-auto text-center">
                            <h3 class="text-lg font-bold">Confirmar Aprovação</h3>
                            <p class="mt-2 text-sm text-gray-600">Tem certeza que deseja aprovar este orçamento?</p>
                            <div class="mt-4 flex justify-center gap-4">
                                <button @click="confirmingApproval = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Cancelar</button>
                                <button onclick="aprovarOrcamento()" @click="confirmingApproval = false" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">Sim, Aprovar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>


            {{-- Rodapé com logo, redes sociais e QR code --}}
            <footer class="mt-16 border-t border-gray-200 pt-8">
                <div class="flex flex-col sm:flex-row justify-between items-center sm:items-start space-y-4 sm:space-y-0">
                    {{-- Lado esquerdo: Logo e contatos --}}
                    <div class="flex flex-col space-y-4 items-center sm:items-start">
                        {{-- Primeira linha: Logo ícone e contatos --}}
                        <div class="flex items-center space-x-4">
                            {{-- Logo ícone do usuário --}}
                            @php
                                $iconLogo = optional($orcamento->cliente->user)->getLogoByType('vertical');
                            @endphp
                            @if($iconLogo && file_exists(storage_path('app/public/' . $iconLogo->caminho)))
                                <img src="{{ $iconLogo->url }}" alt="Logo da Empresa" class="h-12 sm:h-16 w-auto rounded">
                            @else
                                <div class="bg-gray-800 text-white px-3 sm:px-4 py-2 rounded font-bold text-base sm:text-lg">
                                    <span class="text-white">LOGO</span>
                                </div>
                            @endif
                            
                            {{-- Contatos ao lado da logo --}}
                            {{-- <div class="flex items-center space-x-4 text-sm text-gray-600">
                                @if(optional($orcamento->cliente->user)->website_url)
                                    <a href="{{ $orcamento->cliente->user->website_url }}" target="_blank" class="flex items-center hover:text-blue-600 transition-colors" title="Website">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M12.232 4.232a2.5 2.5 0 013.536 3.536l-1.225 1.224a.75.75 0 001.061 1.06l1.224-1.224a4 4 0 00-5.656-5.656l-3 3a4 4 0 00.225 5.865.75.75 0 00.977-1.138 2.5 2.5 0 01-.142-3.665l3-3.001z"></path>
                                            <path d="M4.468 12.232a2.5 2.5 0 010-3.536l1.225-1.224a.75.75 0 00-1.061-1.06l-1.224 1.224a4 4 0 005.656 5.656l-3 3a4 4 0 00-.225-5.865.75.75 0 00-.977 1.138 2.5 2.5 0 01.142 3.665l-3 3a2.5 2.5 0 01-3.536 0z"></path>
                                        </svg>
                                        {{ str_replace(['http://', 'https://'], '', $orcamento->cliente->user->website_url) }}
                                    </a>
                                @endif
                                
                                @if(optional($orcamento->cliente->user)->email_extra)
                                    <a href="mailto:{{ $orcamento->cliente->user->email_extra }}" class="flex items-center hover:text-blue-600 transition-colors" title="Email Extra">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                        {{ $orcamento->cliente->user->email_extra }}
                                    </a>
                                @endif
                                
                                @if(optional($orcamento->cliente->user)->telefone_whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $orcamento->cliente->user->telefone_whatsapp) }}" target="_blank" class="flex items-center hover:text-green-600 transition-colors" title="WhatsApp">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.894 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.523.074-.797.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                        {{ $orcamento->cliente->user->telefone_whatsapp }}
                                    </a>
                                @endif
                                
                                @if(optional($orcamento->cliente->user)->whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $orcamento->cliente->user->whatsapp) }}" target="_blank" class="flex items-center hover:text-green-600 transition-colors" title="WhatsApp">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.894 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.523.074-.797.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                        {{ $orcamento->cliente->user->whatsapp }}
                                    </a>
                                @endif
                            </div> --}}
                        </div>

                        {{-- Segunda linha: Redes sociais centralizadas --}}
                        {{-- <div class="flex justify-center mt-4">
                            <div class="flex space-x-3">
                                @if(optional($orcamento->cliente->user)->facebook_url)
                                    <a href="{{ $orcamento->cliente->user->facebook_url }}" target="_blank" class="text-gray-500 hover:text-blue-600 transition-colors" title="Facebook">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if(optional($orcamento->cliente->user)->instagram_url)
                                    <a href="{{ $orcamento->cliente->user->instagram_url }}" target="_blank" class="text-gray-500 hover:text-pink-600 transition-colors" title="Instagram">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if(optional($orcamento->cliente->user)->linkedin_url)
                                    <a href="{{ $orcamento->cliente->user->linkedin_url }}" target="_blank" class="text-gray-500 hover:text-blue-700 transition-colors" title="LinkedIn">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if(optional($orcamento->cliente->user)->twitter_url)
                                    <a href="{{ $orcamento->cliente->user->twitter_url }}" target="_blank" class="text-gray-500 hover:text-blue-400 transition-colors" title="Twitter">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if(optional($orcamento->cliente->user)->youtube_url)
                                    <a href="{{ $orcamento->cliente->user->youtube_url }}" target="_blank" class="text-gray-500 hover:text-red-600 transition-colors" title="YouTube">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if(optional($orcamento->cliente->user)->tiktok_url)
                                    <a href="{{ $orcamento->cliente->user->tiktok_url }}" target="_blank" class="text-gray-500 hover:text-black transition-colors" title="TikTok">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if(optional($orcamento->cliente->user)->behance_url)
                                    <a href="{{ $orcamento->cliente->user->behance_url }}" target="_blank" class="text-gray-500 hover:text-blue-500 transition-colors" title="Behance">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M22 7h-7v-2h7v2zm1.726 10c-.442 1.297-2.029 2-5.101 2-3.074 0-5.564-1.729-5.564-5.675 0-3.91 2.325-5.92 5.466-5.92 3.082 0 4.964 1.782 5.375 4.426.078.506.109 1.188.095 2.14H15.97c.13 3.211 3.483 3.312 4.588 2.029h3.168zm-7.686-4h4.965c-.105-1.547-1.136-2.219-2.477-2.219-1.466 0-2.277.768-2.488 2.219zm-9.574 6.988h-6.466v-14.967h6.953c5.476.081 5.58 5.444 2.72 6.906 3.461 1.26 3.577 8.061-3.207 8.061zm-3.466-8.988h3.584c2.508 0 2.906-3-.312-3h-3.272v3zm3.391 3h-3.391v3.016h3.341c3.055 0 2.868-3.016.05-3.016z"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div> --}}
                    </div>

                    {{-- Lado direito: Logo Ícone --}}
                    @php
                        $logoIcone = optional($orcamento->cliente->user)->getLogoByType('icone');
                    @endphp
                    @if($logoIcone && file_exists(storage_path('app/public/' . $logoIcone->caminho)))
                        <img src="{{ $logoIcone->url }}" alt="Logo Ícone" class="h-12 sm:h-16 w-auto rounded">
                    @else
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-200 border-2 border-gray-300 flex items-center justify-center">
                            <span class="text-gray-600 text-xs font-bold">ÍCONE</span>
                        </div>
                    @endif
                </div>
            </footer>
        </div>
    </div>
</div>

<script>
    function aprovarOrcamento() {
        fetch('{{ route("public.orcamentos.public.aprovar", $orcamento->token_publico) }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Orçamento aprovado com sucesso!');
                location.reload();
            } else {
                alert('Erro ao aprovar orçamento: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao aprovar orçamento');
        });
    }
    
    function rejeitarOrcamento() {
        fetch('{{ route("public.orcamentos.public.rejeitar", $orcamento->token_publico) }}', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Orçamento rejeitado com sucesso!');
                location.reload();
            } else {
                alert('Erro ao rejeitar orçamento: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao rejeitar orçamento');
        });
    }


</script>
</body>
</html>
