@extends('layouts.app')

@section('title', 'Muitas tentativas')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 text-center">
        <!-- Error Code -->
        <div>
            <h1 class="text-9xl font-bold text-purple-600 dark:text-purple-400">429</h1>
        </div>
        
        <!-- Error Icon -->
        <div class="flex justify-center">
            <svg class="w-24 h-24 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 12H4m16 0a8 8 0 01-8 8m8-8a8 8 0 00-8-8m8 8H4m0 0L12 4m-8 8l8 8"></path>
            </svg>
        </div>
        
        <!-- Error Message -->
        <div class="space-y-4">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                Muitas tentativas
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400">
                Você fez muitas tentativas em um curto período de tempo.
            </p>
            <p class="text-sm text-gray-500 dark:text-gray-500">
                Por favor, aguarde alguns minutos antes de tentar novamente.
            </p>
        </div>
        
        <!-- Countdown Timer -->
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-6 border border-purple-200 dark:border-purple-800">
            <div class="flex items-center justify-center space-x-2 mb-2">
                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm font-medium text-purple-800 dark:text-purple-200">Tempo de espera</span>
            </div>
            <div id="countdown" class="text-2xl font-bold text-purple-600 dark:text-purple-400">--:--</div>
            <p class="text-xs text-purple-600 dark:text-purple-400 mt-1">Tente novamente após este tempo</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="space-y-4">
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="window.location.reload()" 
                        class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Tentar novamente
                </button>
                
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
                    </svg>
                    Voltar ao Dashboard
                </a>
            </div>
        </div>
        
        <!-- Rate Limit Information -->
        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
            <div class="space-y-2">
                <div class="flex items-center justify-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Limite de taxa aplicado para proteger o sistema</span>
                </div>
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Esta medida ajuda a manter a performance e segurança para todos os usuários.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Simple countdown timer (5 minutes)
let timeLeft = 300; // 5 minutes in seconds
const countdownElement = document.getElementById('countdown');

function updateCountdown() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    countdownElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    
    if (timeLeft > 0) {
        timeLeft--;
        setTimeout(updateCountdown, 1000);
    } else {
        countdownElement.textContent = '00:00';
        countdownElement.parentElement.innerHTML = '<div class="text-green-600 dark:text-green-400 font-medium">✓ Você pode tentar novamente agora</div>';
    }
}

// Start countdown
updateCountdown();
</script>
@endsection