@extends('layouts.app')

@section('title', $creditCard->name . ' - Extrato do Cartão - Giro')

@section('content')
<!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Home', 'url' => route('dashboard'), 'icon' => 'fas fa-home'],
        ['label' => 'Financeiro', 'url' => '#'],
        ['label' => 'Cartões', 'url' => route('financial.credit-cards.index')],
        ['label' => $creditCard->nome, 'url' => route('financial.credit-cards.show', $creditCard)],
        ['label' => 'Extrato']
    ]" />

<div class="max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $creditCard->name }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Extrato do cartão de crédito</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('financial.credit-cards.edit', $creditCard) }}" class="flex items-center justify-center w-10 h-10 text-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg" title="Editar Cartão">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </a>
            <button onclick="updateUsedLimit()" class="flex items-center justify-center w-10 h-10 text-green-500 hover:text-green-600 dark:hover:text-green-400 transition-colors hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg" title="Atualizar Limite">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
            </button>
            <a href="{{ route('financial.transactions.create', ['credit_card_id' => $creditCard->id]) }}" class="flex items-center justify-center w-10 h-10 text-purple-500 hover:text-purple-600 dark:hover:text-purple-400 transition-colors hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg" title="Nova Transação">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </a>
            <a href="{{ route('financial.credit-cards.index') }}" class="flex items-center justify-center w-10 h-10 text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 transition-colors hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg" title="Voltar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Credit Card Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Main Info -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informações do Cartão</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $creditCard->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
                        {{ $creditCard->status === 'active' ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nome do Cartão</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $creditCard->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Bandeira</label>
                        <p class="text-lg text-gray-900 dark:text-white">{{ $creditCard->brand ?? 'Não informado' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Limite Total</label>
                        <p class="text-lg text-gray-900 dark:text-white">R$ {{ number_format($creditCard->total_limit, 2, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Limite Utilizado</label>
                        <p class="text-lg text-red-600 dark:text-red-400">R$ {{ number_format($creditCard->used_limit, 2, ',', '.') }}</p>
                        
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Data de Vencimento</label>
                        <p class="text-lg text-gray-900 dark:text-white">
                            @if($creditCard->dia_vencimento)
                                Dia {{ $creditCard->dia_vencimento }}
                            @else
                                Não informado
                            @endif
                        </p>
                        
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Data de Fechamento</label>
                        <p class="text-lg text-gray-900 dark:text-white">
                            @if($creditCard->dia_fechamento)
                                Dia {{ $creditCard->dia_fechamento }}
                            @else
                                Não informado
                            @endif
                        </p>
                        
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Criado em:</span>
                            <span class="text-gray-900 dark:text-white ml-1">{{ $creditCard->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Última atualização:</span>
                            <span class="text-gray-900 dark:text-white ml-1">{{ $creditCard->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Limit Card -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm text-white">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold">Limite Disponível</h3>
                    <svg class="w-8 h-8 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                <div class="mb-4">
                    <p class="text-3xl font-bold">R$ {{ number_format($creditCard->total_limit - $creditCard->used_limit, 2, ',', '.') }}</p>
                    <p class="text-blue-100 text-sm mt-1">{{ number_format((($creditCard->total_limit - $creditCard->used_limit) / $creditCard->total_limit) * 100, 1) }}% disponível</p>
                </div>
                <div class="flex items-center text-sm text-blue-100">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    ID: #{{ $creditCard->id }}
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Transações do Cartão</h2>
                <a href="{{ route('financial.transactions.index', ['credit_card_id' => $creditCard->id]) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 text-sm font-medium">
                    Ver todas →
                </a>
            </div>

            @if($transactions->count() > 0)
                <div class="space-y-3">
                    @foreach($transactions as $transaction)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center
                                        {{ $transaction->type === 'expense' ? 'bg-red-100 dark:bg-red-900' : 'bg-green-100 dark:bg-green-900' }}">
                                        <svg class="w-5 h-5 {{ $transaction->type === 'expense' ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($transaction->type === 'expense')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                            @endif
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $transaction->description }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $transaction->category->name ?? 'Sem categoria' }} • {{ $transaction->date?->format('d/m/Y') ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="text-right">
                                    <p class="text-sm font-semibold {{ $transaction->type === 'expense' ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                        {{ $transaction->type === 'expense' ? '-' : '+' }} R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                                    </p>
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full
                                        {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                           ($transaction->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $transactions->links() }}
                </div>
            @endif
            @else
                <div class="text-center py-12">
                    <div class="mx-auto w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Nenhuma transação encontrada</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Este cartão ainda não possui transações registradas.</p>
                    <a href="{{ route('financial.transactions.create', ['credit_card_id' => $creditCard->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Adicionar Transação
                    </a>
                </div>
            @endif
        </div>
    </div>


</div>

@push('scripts')
<script>
// Update used limit function
function updateUsedLimit() {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    
    button.innerHTML = `
        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Atualizando...
    `;
    button.disabled = true;
    
    fetch(`/financial/api/credit-cards/{{ $creditCard->id }}/update-used-limit`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            toast.textContent = 'Limite atualizado com sucesso!';
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
                location.reload(); // Reload to show updated values
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Error updating used limit:', error);
        
        // Show error message
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        toast.textContent = 'Erro ao atualizar limite';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}
</script>
@endpush
@endsection