<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $orcamento->titulo }} - Orçamento</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto py-8 px-4">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Proposta de Orçamento</h1>
            <p class="text-gray-600">{{ $orcamento->titulo }}</p>
        </div>

        <!-- Status -->
        <div class="text-center mb-8">
            @php
                $statusColors = [
                    'rascunho' => 'bg-gray-100 text-gray-800',
                    'enviado' => 'bg-blue-100 text-blue-800',
                    'aprovado' => 'bg-green-100 text-green-800',
                    'rejeitado' => 'bg-red-100 text-red-800',
                    'em_andamento' => 'bg-yellow-100 text-yellow-800',
                    'concluido' => 'bg-purple-100 text-purple-800',
                    'quitado' => 'bg-emerald-100 text-emerald-800'
                ];
                $statusClass = $statusColors[$orcamento->status] ?? 'bg-gray-100 text-gray-800';
            @endphp
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $statusClass }}">
                {{ ucfirst(str_replace('_', ' ', $orcamento->status)) }}
            </span>
        </div>

        <!-- Orçamento Card -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h2 class="text-xl font-semibold text-white">{{ $orcamento->titulo }}</h2>
                <p class="text-blue-100 text-sm mt-1">Orçamento #{{ $orcamento->id }}</p>
            </div>

            <div class="p-6">
                <!-- Cliente -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Cliente</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="font-medium text-gray-900">{{ $orcamento->cliente->nome }}</p>
                        <p class="text-gray-600">{{ $orcamento->cliente->email }}</p>
                        @if($orcamento->cliente->telefone)
                        <p class="text-gray-600">{{ $orcamento->cliente->telefone }}</p>
                        @endif
                    </div>
                </div>

                <!-- Descrição -->
                @if($orcamento->descricao)
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Descrição</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-700">{{ $orcamento->descricao }}</p>
                    </div>
                </div>
                @endif

                <!-- Valores -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Valores</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="text-center">
                                <span class="text-sm font-medium text-gray-500">Valor Total</span>
                                <p class="text-2xl font-bold text-gray-900">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</p>
                            </div>
                            <div class="text-center">
                                <span class="text-sm font-medium text-gray-500">Validade</span>
                                <p class="text-lg font-medium text-gray-900">{{ $orcamento->data_validade->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações -->
        @if(in_array($orcamento->status, ['enviado', 'rascunho']))
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Ações</h3>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <button onclick="aprovarOrcamento()" 
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition-colors">
                    Aprovar Orçamento
                </button>
                
                <button onclick="rejeitarOrcamento()" 
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg transition-colors">
                    Rejeitar Orçamento
                </button>
            </div>
        </div>
        @endif
    </div>

    <script>
        function aprovarOrcamento() {
            fetch(`/orcamento/{{ $orcamento->token_publico }}/aprovar`, {
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
                    alert('Erro: ' + data.message);
                }
            });
        }
        
        function rejeitarOrcamento() {
            const motivo = prompt('Motivo da rejeição (opcional):');
            
            fetch(`/orcamento/{{ $orcamento->token_publico }}/rejeitar`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ motivo: motivo })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Orçamento rejeitado com sucesso!');
                    location.reload();
                } else {
                    alert('Erro: ' + data.message);
                }
            });
        }
    </script>
</body>
</html>