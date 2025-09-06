@extends('layouts.app')

@section('title', 'Dashboard Financeiro')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header com navegação de mês -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Financeiro</h1>
            <p class="text-gray-600">Visão geral das suas finanças</p>
        </div>
        
        <!-- Navegação de mês -->
        <div class="flex items-center space-x-4 mt-4 md:mt-0">
            <a href="{{ route('financial.dashboard', ['year' => $currentYear, 'month' => $currentMonth - 1]) }}" 
               class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            
            <div class="text-center">
                <div class="text-lg font-semibold text-gray-900">
                    {{ \Carbon\Carbon::create($currentYear, $currentMonth)->format('F Y') }}
                </div>
            </div>
            
            <a href="{{ route('financial.dashboard', ['year' => $currentYear, 'month' => $currentMonth + 1]) }}" 
               class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Cards de resumo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Receitas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Receitas</p>
                    <p class="text-2xl font-bold text-green-600">R$ {{ number_format($summary['receitas_total'], 2, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        Pagas: R$ {{ number_format($summary['receitas_pagas'], 2, ',', '.') }}
                    </p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Despesas -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Despesas</p>
                    <p class="text-2xl font-bold text-red-600">R$ {{ number_format($summary['despesas_total'], 2, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        Pagas: R$ {{ number_format($summary['despesas_pagas'], 2, ',', '.') }}
                    </p>
                </div>
                <div class="p-3 bg-red-100 rounded-full">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Saldo -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Saldo</p>
                    <p class="text-2xl font-bold {{ $summary['saldo'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        R$ {{ number_format($summary['saldo'], 2, ',', '.') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $summary['saldo'] >= 0 ? 'Positivo' : 'Negativo' }}
                    </p>
                </div>
                <div class="p-3 {{ $summary['saldo'] >= 0 ? 'bg-green-100' : 'bg-red-100' }} rounded-full">
                    <svg class="w-6 h-6 {{ $summary['saldo'] >= 0 ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Transações -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Transações</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $summary['total_transacoes'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">
                        Pendentes: {{ $summary['transacoes_pendentes'] }}
                    </p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Contas Bancárias -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Contas Bancárias</h3>
                <a href="{{ route('financial.banks.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Ver todas
                </a>
            </div>
            
            @if($banks->count() > 0)
                <div class="space-y-3">
                    @foreach($banks->take(3) as $bank)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $bank->nome }}</p>
                                    <p class="text-sm text-gray-500">{{ $bank->banco }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">
                                    R$ {{ number_format($bank->saldo_atual, 2, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <p class="text-gray-500 mb-4">Nenhuma conta bancária cadastrada</p>
                    <a href="{{ route('financial.banks.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg hover:bg-blue-50 transition-colors" title="Adicionar Conta">
                        <svg class="w-5 h-5 text-blue-600 hover:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </a>
                </div>
            @endif
        </div>

        <!-- Cartões de Crédito -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Cartões de Crédito</h3>
                <a href="{{ route('financial.credit-cards.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Ver todos
                </a>
            </div>
            
            @if($creditCards->count() > 0)
                <div class="space-y-3">
                    @foreach($creditCards->take(3) as $card)
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $card->nome }}</p>
                                        <p class="text-sm text-gray-500">{{ $card->bandeira }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">
                                        {{ number_format($card->percentual_utilizado, 1) }}% usado
                                    </p>
                                </div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $card->percentual_utilizado }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>R$ {{ number_format($card->limite_utilizado, 2, ',', '.') }}</span>
                                <span>R$ {{ number_format($card->limite_total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <p class="text-gray-500 mb-4">Nenhum cartão cadastrado</p>
                    <a href="{{ route('financial.credit-cards.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg hover:bg-purple-50 transition-colors" title="Adicionar Cartão">
                        <svg class="w-5 h-5 text-purple-600 hover:text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </a>
                </div>
            @endif
        </div>

        <!-- Transações Pendentes -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Pendentes</h3>
                <a href="{{ route('financial.transactions.index', ['status' => 'pendente']) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Ver todas
                </a>
            </div>
            
            @if($pendingTransactions->count() > 0)
                <div class="space-y-3">
                    @foreach($pendingTransactions as $transaction)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 {{ $transaction->tipo === 'receita' ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center">
                                    @if($transaction->tipo === 'receita')
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 text-sm">{{ $transaction->descricao }}</p>
                                    <p class="text-xs text-gray-500">{{ $transaction->data->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-sm {{ $transaction->tipo === 'receita' ? 'text-green-600' : 'text-red-600' }}">
                                    R$ {{ number_format($transaction->valor, 2, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500">Nenhuma transação pendente</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Botão flutuante para adicionar transação -->
    <div class="fixed bottom-6 right-6">
        <div class="relative group">
            <button id="fab-button" class="w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </button>
            
            <!-- Menu de opções -->
            <div id="fab-menu" class="absolute bottom-16 right-0 hidden space-y-2">
                <a href="{{ route('financial.transactions.create') }}" class="flex items-center space-x-2 bg-white shadow-lg rounded-lg px-4 py-2 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Nova Transação</span>
                </a>
                <a href="{{ route('financial.banks.create') }}" class="flex items-center space-x-2 bg-white shadow-lg rounded-lg px-4 py-2 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Nova Conta</span>
                </a>
                <a href="{{ route('financial.credit-cards.create') }}" class="flex items-center space-x-2 bg-white shadow-lg rounded-lg px-4 py-2 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Novo Cartão</span>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Controle do botão flutuante
    document.getElementById('fab-button').addEventListener('click', function() {
        const menu = document.getElementById('fab-menu');
        menu.classList.toggle('hidden');
    });
    
    // Fechar menu ao clicar fora
    document.addEventListener('click', function(event) {
        const fabButton = document.getElementById('fab-button');
        const fabMenu = document.getElementById('fab-menu');
        
        if (!fabButton.contains(event.target) && !fabMenu.contains(event.target)) {
            fabMenu.classList.add('hidden');
        }
    });
</script>
@endpush
@endsection