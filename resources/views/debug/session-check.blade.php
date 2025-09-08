@extends('layouts.app')

@section('title', 'Debug - Verificação de Sessão')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Debug - Verificação de Sessão</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Status da Autenticação -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Status da Autenticação</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium">Autenticado:</span>
                        <span class="{{ auth()->check() ? 'text-green-600' : 'text-red-600' }}">
                            {{ auth()->check() ? 'SIM' : 'NÃO' }}
                        </span>
                    </div>
                    @if(auth()->check())
                        <div class="flex justify-between">
                            <span class="font-medium">ID do Usuário:</span>
                            <span class="text-blue-600">{{ auth()->id() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Email:</span>
                            <span class="text-blue-600">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium">Nome:</span>
                            <span class="text-blue-600">{{ auth()->user()->name }}</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Informações da Sessão -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informações da Sessão</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium">ID da Sessão:</span>
                        <span class="text-blue-600 text-sm">{{ session()->getId() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Driver:</span>
                        <span class="text-blue-600">{{ config('session.driver') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Lifetime:</span>
                        <span class="text-blue-600">{{ config('session.lifetime') }} min</span>
                    </div>
                </div>
            </div>
            
            <!-- CSRF Token -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">CSRF Token</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium">Token Atual:</span>
                        <span class="text-blue-600 text-sm">{{ csrf_token() }}</span>
                    </div>
                    <button onclick="refreshToken()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Atualizar Token
                    </button>
                </div>
            </div>
            
            <!-- Teste de Rotas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Teste de Rotas</h2>
                <div class="space-y-3">
                    <a href="{{ route('modelos-propostas.index') }}" class="block bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center">
                        Ir para Modelos de Propostas
                    </a>
                    <button onclick="testDuplicate()" class="w-full bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Testar Duplicação (ID 1)
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Log de Testes -->
        <div class="mt-8 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Log de Testes</h2>
            <div id="test-log" class="bg-gray-100 p-4 rounded-lg min-h-32 font-mono text-sm">
                Aguardando testes...
            </div>
        </div>
    </div>
</div>

<script>
function log(message) {
    const logDiv = document.getElementById('test-log');
    const timestamp = new Date().toLocaleTimeString();
    logDiv.innerHTML += `[${timestamp}] ${message}\n`;
    logDiv.scrollTop = logDiv.scrollHeight;
}

function refreshToken() {
    log('Atualizando CSRF token...');
    fetch('/debug/csrf-refresh', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.token);
            log('✅ Token atualizado com sucesso');
            location.reload();
        } else {
            log('❌ Erro ao atualizar token');
        }
    })
    .catch(error => {
        log('❌ Erro: ' + error.message);
    });
}

function testDuplicate() {
    log('Testando duplicação do modelo ID 1...');
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/modelos-propostas/1/duplicate';
    
    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    form.appendChild(tokenInput);
    document.body.appendChild(form);
    
    log('Enviando requisição de duplicação...');
    form.submit();
}

// Log inicial
log('Página carregada. Status de autenticação: {{ auth()->check() ? "LOGADO" : "NÃO LOGADO" }}');
</script>
@endsection