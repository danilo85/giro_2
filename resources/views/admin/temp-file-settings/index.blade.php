@extends('layouts.app')

@section('title', 'Configurações de Arquivos Temporários')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Configurações de Arquivos Temporários</h1>
        <p class="text-gray-600">Gerencie as configurações globais para arquivos temporários e visualize estatísticas do sistema.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Settings Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Configurações Globais</h2>
            
            <form action="{{ route('admin.temp-file-settings.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div>
                    <label for="default_expiry_days" class="block text-sm font-medium text-gray-700 mb-2">
                        Dias de Expiração Padrão
                    </label>
                    <input type="number" 
                           id="default_expiry_days" 
                           name="default_expiry_days" 
                           value="{{ old('default_expiry_days', $settings['default_expiry_days']) }}"
                           min="1" 
                           max="365" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    <p class="text-sm text-gray-500 mt-1">Número padrão de dias antes da expiração de arquivos temporários</p>
                </div>
                
                <div>
                    <label for="max_expiry_days" class="block text-sm font-medium text-gray-700 mb-2">
                        Máximo de Dias de Expiração
                    </label>
                    <input type="number" 
                           id="max_expiry_days" 
                           name="max_expiry_days" 
                           value="{{ old('max_expiry_days', $settings['max_expiry_days']) }}"
                           min="1" 
                           max="365" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    <p class="text-sm text-gray-500 mt-1">Número máximo de dias que um usuário pode definir para expiração</p>
                </div>
                
                <div>
                    <label for="cleanup_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Horário da Limpeza Automática
                    </label>
                    <input type="time" 
                           id="cleanup_time" 
                           name="cleanup_time" 
                           value="{{ old('cleanup_time', $settings['cleanup_time']) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                    <p class="text-sm text-gray-500 mt-1">Horário diário para executar a limpeza automática</p>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="auto_cleanup_enabled" 
                           name="auto_cleanup_enabled" 
                           value="1"
                           {{ old('auto_cleanup_enabled', $settings['auto_cleanup_enabled']) ? 'checked' : '' }}
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="auto_cleanup_enabled" class="ml-2 block text-sm text-gray-900">
                        Habilitar limpeza automática de arquivos expirados
                    </label>
                </div>
                
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">
                        Salvar Configurações
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistics and Actions -->
        <div class="space-y-6">
            <!-- Statistics Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Estatísticas do Sistema</h2>
                
                <div id="statistics-container" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600" id="total-temp-files">{{ $statistics['total_temporary_files'] }}</div>
                            <div class="text-sm text-gray-600">Arquivos Temporários</div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600" id="expiring-soon">{{ $statistics['expiring_soon'] }}</div>
                            <div class="text-sm text-gray-600">Expirando em 24h</div>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-red-600" id="expired-files">{{ $statistics['expired_files'] }}</div>
                            <div class="text-sm text-gray-600">Arquivos Expirados</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-600" id="total-size">{{ $statistics['total_size_formatted'] }}</div>
                            <div class="text-sm text-gray-600">Espaço Ocupado</div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6">
                    <button onclick="refreshStatistics()" 
                            class="w-full bg-gray-600 text-white py-2 px-4 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200">
                        Atualizar Estatísticas
                    </button>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Ações Administrativas</h2>
                
                <div class="space-y-4">
                    <button onclick="triggerCleanup()" 
                            class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200">
                        Executar Limpeza Manual
                    </button>
                    
                    <button onclick="sendWarnings()" 
                            class="w-full bg-orange-600 text-white py-2 px-4 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-200">
                        Enviar Avisos de Expiração
                    </button>
                </div>
                
                <div id="action-result" class="mt-4 hidden">
                    <!-- Action results will be displayed here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshStatistics() {
    fetch('{{ route("admin.temp-file-settings.statistics") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('total-temp-files').textContent = data.statistics.total_temporary_files;
                document.getElementById('expiring-soon').textContent = data.statistics.expiring_soon;
                document.getElementById('expired-files').textContent = data.statistics.expired_files;
                document.getElementById('total-size').textContent = data.statistics.total_size_formatted;
                
                showActionResult('Estatísticas atualizadas com sucesso!', 'success');
            }
        })
        .catch(error => {
            showActionResult('Erro ao atualizar estatísticas.', 'error');
        });
}

function triggerCleanup() {
    if (!confirm('Tem certeza que deseja executar a limpeza manual? Esta ação irá remover permanentemente todos os arquivos expirados.')) {
        return;
    }
    
    fetch('{{ route("admin.temp-file-settings.cleanup") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showActionResult(data.message, 'success');
            refreshStatistics();
        } else {
            showActionResult(data.message, 'error');
        }
    })
    .catch(error => {
        showActionResult('Erro ao executar limpeza.', 'error');
    });
}

function sendWarnings() {
    fetch('{{ route("admin.temp-file-settings.warnings") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showActionResult(data.message, 'success');
        } else {
            showActionResult(data.message, 'error');
        }
    })
    .catch(error => {
        showActionResult('Erro ao enviar avisos.', 'error');
    });
}

function showActionResult(message, type) {
    const resultDiv = document.getElementById('action-result');
    const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
    
    resultDiv.innerHTML = `
        <div class="${bgColor} border px-4 py-3 rounded" role="alert">
            <span class="block sm:inline">${message}</span>
        </div>
    `;
    
    resultDiv.classList.remove('hidden');
    
    // Hide after 5 seconds
    setTimeout(() => {
        resultDiv.classList.add('hidden');
    }, 5000);
}
</script>
@endsection