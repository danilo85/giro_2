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
<body class="h-full bg-gray-50 font-open-sans overflow-x-hidden">

    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 font-open-sans w-full">
        <div class="bg-white p-8 md:p-12 shadow-lg rounded-md relative">
            
            <header class="flex flex-col sm:flex-row justify-between items-start mb-8 sm:mb-12 space-y-4 sm:space-y-0">
                <div class="flex items-start space-x-3 sm:space-x-6">

                    <div>
                        <h1 class="text-3xl sm:text-6xl font-black text-gray-800 tracking-tighter">EXTRATO</h1>
                        <div class="mt-4 text-gray-500 text-sm sm:text-base">
                            <p>Cliente: <span class="font-semibold text-gray-700">{{ $cliente->nome }}</span></p>
                            <p>Atualizado em {{ now()->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="bg-gray-800 text-white w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-full">
                        <span class="text-sm sm:text-l text-white">{{ $cliente->id }}</span>
                    </div>
                </div>
            </header>

            <main>
                {{-- Lista de Orçamentos --}}
                <div class="mb-6 sm:mb-8">
                    <h2 class="font-bold text-gray-900 text-base sm:text-lg mb-4 sm:mb-6">Orçamentos:</h2>
                    
                    @if($orcamentos->count() > 0)
                        <div class="space-y-4 sm:space-y-6">
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
                                
                                <div class="border border-gray-200 rounded-lg p-4 sm:p-6 bg-gray-50">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 space-y-2 sm:space-y-0">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900 text-base sm:text-lg">{{ $orcamento->titulo }}</h3>
                                            <p class="text-gray-600 text-xs sm:text-sm mt-1">Orçamento #{{ $orcamento->numero ?? $orcamento->id }}</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div class="w-3 h-3 {{ $statusClass }} rounded-full"></div>
                                            <span class="text-xs sm:text-sm font-medium text-gray-700 capitalize">{{ $orcamento->status }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mt-4">
                                        <div class="text-center p-3 sm:p-4 bg-white rounded border">
                                            <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">Valor Total</p>
                                            <p class="text-lg sm:text-xl font-bold text-gray-900">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</p>
                                        </div>
                                        <div class="text-center p-3 sm:p-4 bg-white rounded border">
                                            <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">Valor Pago</p>
                                            <p class="text-lg sm:text-xl font-bold text-green-600">R$ {{ number_format($valorPago, 2, ',', '.') }}</p>
                                        </div>
                                        <div class="text-center p-3 sm:p-4 bg-white rounded border">
                                            <p class="text-xs uppercase tracking-wider text-gray-500 mb-1">Saldo Restante</p>
                                            <p class="text-lg sm:text-xl font-bold {{ $saldoRestante > 0 ? 'text-red-600' : 'text-green-600' }}">R$ {{ number_format($saldoRestante, 2, ',', '.') }}</p>
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
                <div class="bg-gray-800 text-white p-4 sm:p-8 rounded-lg">
                    <h2 class="font-bold text-lg sm:text-xl mb-4 sm:mb-6">Resumo Geral</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                        <div class="text-center">
                            <p class="text-gray-300 text-xs sm:text-sm uppercase tracking-wider mb-2">Total dos Orçamentos</p>
                            <p class="text-xl sm:text-3xl font-bold">R$ {{ number_format($totalOrcamentos, 2, ',', '.') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-300 text-xs sm:text-sm uppercase tracking-wider mb-2">Total Pago</p>
                            <p class="text-xl sm:text-3xl font-bold text-green-400">R$ {{ number_format($totalPago, 2, ',', '.') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-300 text-xs sm:text-sm uppercase tracking-wider mb-2">Saldo Restante</p>
                            <p class="text-xl sm:text-3xl font-bold {{ $saldoRestanteGeral > 0 ? 'text-red-400' : 'text-green-400' }}">R$ {{ number_format($saldoRestanteGeral, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </main>

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
        </div>
    </div>

</body>
</html>