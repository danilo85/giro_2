@extends('layouts.app')

@section('title', 'Extrato - ' . $bank->nome)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('financial.banks.index') }}" class="text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Extrato - {{ $bank->nome }}</h1>
                <p class="text-gray-600">{{ $bank->banco }} - {{ $bank->tipo_conta }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-600">Saldo Atual</p>
            <p class="text-2xl font-bold {{ $bank->saldo_atual >= 0 ? 'text-green-600' : 'text-red-600' }}">
                R$ {{ number_format($bank->saldo_atual, 2, ',', '.') }}
            </p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Filtros</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Inicial</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Final</label>
                <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="receita">Receitas</option>
                    <option value="despesa">Despesas</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="inline-flex justify-center items-center p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors w-full" title="Filtrar">
                    <svg class="w-5 h-5 text-blue-500 hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Lista de Transações -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold">Transações</h2>
        </div>
        
        @if($transactions->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($transactions as $transaction)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $transaction->tipo === 'receita' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    @if($transaction->tipo === 'receita')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $transaction->descricao }}</h3>
                                    <p class="text-sm text-gray-600">
                                        {{ $transaction->category->nome ?? 'Sem categoria' }} • 
                                        {{ \Carbon\Carbon::parse($transaction->data)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold {{ $transaction->tipo === 'receita' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->tipo === 'receita' ? '+' : '-' }} R$ {{ number_format($transaction->valor, 2, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $transaction->pago ? 'Pago' : 'Pendente' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Paginação -->
            <div class="p-6 border-t border-gray-200">
                {{ $transactions->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma transação encontrada</h3>
                <p class="text-gray-600">Este banco ainda não possui transações registradas.</p>
            </div>
        @endif
    </div>
</div>
@endsection