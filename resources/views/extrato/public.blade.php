<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Extrato - {{ $cliente->nome }}</title>
    
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

    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 font-open-sans">
        <div class="bg-white p-8 md:p-12 shadow-lg rounded-md relative">
            
            <header class="flex justify-between items-start mb-12">
                <div class="flex items-start space-x-6">
                    {{-- Avatar do cliente --}}
                    @if($cliente->avatar_url)
                        <img src="{{ $cliente->avatar_url }}" alt="Avatar de {{ $cliente->nome }}" class="h-16 w-16 rounded-lg object-cover border border-gray-200">
                    @else
                        <div class="bg-gray-800 text-white h-16 w-16 flex items-center justify-center rounded-lg font-bold text-lg">
                            <span class="text-white">{{ strtoupper(substr($cliente->nome, 0, 1)) }}</span>
                        </div>
                    @endif
                    
                    <div>
                        <h1 class="text-6xl font-black text-gray-800 tracking-tighter">EXTRATO</h1>
                        <div class="mt-4 text-gray-500">
                            <p>Cliente: <span class="font-semibold text-gray-700">{{ $cliente->nome }}</span></p>
                            <p>Atualizado em {{ now()->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="bg-gray-800 text-white w-12 h-12 flex items-center justify-center rounded-full">
                        <span class="text-l text-white">{{ $cliente->id }}</span>
                    </div>
                </div>
            </header>

            <main>
                {{-- Lista de Orçamentos --}}
                <div class="mb-8">
                    <h2 class="font-bold text-gray-900 text-lg mb-6">Orçamentos:</h2>
                    
                    @if($orcamentos->count() > 0)
                        <div class="space-y-6">
                            @foreach($orcamentos as $orcamento)
                                @php
                                    $statusColors = [
                                        'pendente' => 'bg-gray-400',
                                        'analisando' => 'bg-yellow-400', 
                                        'aprovado' => 'bg-green-500', 
                                        'rejeitado' => 'bg-red-500'
                                    ];
                                    $statusClass = $statusColors[strtolower($orcamento->status)] ?? $statusColors['pendente'];
                                    $valorPago = $orcamento->pagamentos->sum('valor');
                                    $saldoRestante = $orcamento->valor_total - $valorPago;
                                @endphp
                                
                                <div class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900 text-lg">{{ $orcamento->titulo }}</h3>
                                            <p class="text-gray-600 text-sm mt-1">Orçamento #{{ $orcamento->numero ?? $orcamento->id }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 {{ $statusClass }} rounded-full"></div>
                                            <span class="text-sm font-medium text-gray-700 capitalize">{{ $orcamento->status }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                        <div class="text-center p-4 bg-white rounded border">
                                            <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">Valor Total</p>
                                            <p class="text-xl font-bold text-gray-900">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</p>
                                        </div>
                                        <div class="text-center p-4 bg-white rounded border">
                                            <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">Valor Pago</p>
                                            <p class="text-xl font-bold text-green-600">R$ {{ number_format($valorPago, 2, ',', '.') }}</p>
                                        </div>
                                        <div class="text-center p-4 bg-white rounded border">
                                            <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">Saldo Restante</p>
                                            <p class="text-xl font-bold {{ $saldoRestante > 0 ? 'text-red-600' : 'text-green-600' }}">R$ {{ number_format($saldoRestante, 2, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    
                                    {{-- Histórico de Pagamentos --}}
                                    @if($orcamento->pagamentos->count() > 0)
                                        <div class="mt-4">
                                            <h4 class="font-medium text-gray-700 mb-2">Pagamentos:</h4>
                                            <div class="space-y-2">
                                                @foreach($orcamento->pagamentos as $pagamento)
                                                    <div class="flex justify-between items-center text-sm bg-white p-3 rounded border">
                                                        <div>
                                                            <span class="font-medium">{{ $pagamento->descricao ?? 'Pagamento' }}</span>
                                                            <span class="text-gray-500 ml-2">{{ $pagamento->created_at->format('d/m/Y') }}</span>
                                                        </div>
                                                        <span class="font-bold text-green-600">R$ {{ number_format($pagamento->valor, 2, ',', '.') }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Nenhum orçamento encontrado para este cliente.</p>
                        </div>
                    @endif
                </div>

                {{-- Resumo Geral --}}
                <div class="bg-gray-800 text-white p-8 rounded-lg">
                    <h2 class="font-bold text-xl mb-6">Resumo Geral</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center">
                            <p class="text-gray-300 text-sm uppercase tracking-wider mb-2">Total dos Orçamentos</p>
                            <p class="text-3xl font-bold">R$ {{ number_format($totalOrcamentos, 2, ',', '.') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-300 text-sm uppercase tracking-wider mb-2">Total Pago</p>
                            <p class="text-3xl font-bold text-green-400">R$ {{ number_format($totalPago, 2, ',', '.') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-300 text-sm uppercase tracking-wider mb-2">Saldo Restante</p>
                            <p class="text-3xl font-bold {{ $saldoRestanteGeral > 0 ? 'text-red-400' : 'text-green-400' }}">R$ {{ number_format($saldoRestanteGeral, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </main>

            {{-- Rodapé com informações do usuário --}}
            <footer class="mt-16 border-t border-gray-200 pt-8">
                <div class="flex justify-between items-start">
                    {{-- Lado esquerdo: Logo e contatos --}}
                    <div class="flex flex-col space-y-4">
                        {{-- Primeira linha: Logo ícone e contatos --}}
                        <div class="flex items-center space-x-4">
                            {{-- Logo ícone do usuário --}}
                            @if(optional($cliente->user)->avatar && file_exists(storage_path('app/public/' . $cliente->user->avatar)))
                                <img src="{{ $cliente->user->avatar_url }}" alt="Logo da Empresa" class="h-12 w-auto rounded">
                            @else
                                <div class="bg-gray-800 text-white px-4 py-2 rounded font-bold text-lg">
                                    <span class="text-white">{{ strtoupper(substr(optional($cliente->user)->name ?? 'EMPRESA', 0, 1)) }}</span>
                                </div>
                            @endif
                            
                            {{-- Contatos ao lado da logo --}}
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                @if(optional($cliente->user)->website_url)
                                    <a href="{{ $cliente->user->website_url }}" target="_blank" class="flex items-center hover:text-blue-600 transition-colors" title="Website">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M12.232 4.232a2.5 2.5 0 013.536 3.536l-1.225 1.224a.75.75 0 001.061 1.06l1.224-1.224a4 4 0 00-5.656-5.656l-3 3a4 4 0 00.225 5.865.75.75 0 00.977-1.138 2.5 2.5 0 01-.142-3.665l3-3.001z"></path>
                                            <path d="M4.468 12.232a2.5 2.5 0 010-3.536l1.225-1.224a.75.75 0 00-1.061-1.06l-1.224 1.224a4 4 0 005.656 5.656l-3 3a4 4 0 00-.225-5.865.75.75 0 00-.977 1.138 2.5 2.5 0 01.142 3.665l-3 3a2.5 2.5 0 01-3.536 0z"></path>
                                        </svg>
                                        {{ str_replace(['http://', 'https://'], '', $cliente->user->website_url) }}
                                    </a>
                                @endif
                                
                                @if(optional($cliente->user)->email_extra)
                                    <a href="mailto:{{ $cliente->user->email_extra }}" class="flex items-center hover:text-blue-600 transition-colors" title="Email Extra">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                        {{ $cliente->user->email_extra }}
                                    </a>
                                @endif
                                
                                @if(optional($cliente->user)->telefone_whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $cliente->user->telefone_whatsapp) }}" target="_blank" class="flex items-center hover:text-green-600 transition-colors" title="WhatsApp">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.894 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.523.074-.797.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.626.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                        {{ $cliente->user->telefone_whatsapp }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Lado direito: QR Code placeholder --}}
                    <div class="w-16 h-16 bg-gray-200 border-2 border-gray-300 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 3h6v6H3V3zm2 2v2h2V5H5zM3 15h6v6H3v-6zm2 2v2h2v-2H5zM15 3h6v6h-6V3zm2 2v2h2V5h-2zM15 15h2v2h-2v-2zM17 17h2v2h-2v-2zM19 15h2v2h-2v-2zM15 19h2v2h-2v-2zM17 21h2v2h-2v-2zM19 19h2v2h-2v-2zM21 17h2v2h-2v-2zM15 17h2v2h-2v-2z"/>
                        </svg>
                    </div>
                </div>
            </footer>
        </div>
    </div>

</body>
</html>