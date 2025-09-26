<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Arquivo Compartilhado - {{ $file->original_name }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-2xl w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-share-alt text-3xl text-blue-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Arquivo Compartilhado</h1>
                <p class="text-blue-100">Acesse o arquivo compartilhado com você</p>
            </div>
            
            <!-- Main Card -->
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <!-- File Preview -->
                <div class="p-6 text-center border-b border-gray-200">
                    @if(str_starts_with($file->mime_type, 'image/'))
                        <img src="{{ $file->file_url }}" alt="{{ $file->original_name }}" 
                             class="max-w-full h-64 object-contain rounded-lg mx-auto mb-4">
                    @elseif(str_starts_with($file->mime_type, 'video/'))
                        <video controls class="max-w-full h-64 rounded-lg mx-auto mb-4">
                            <source src="{{ $file->file_url }}" type="{{ $file->mime_type }}">
                            Seu navegador não suporta a reprodução de vídeo.
                        </video>
                    @elseif(str_starts_with($file->mime_type, 'audio/'))
                        <div class="mb-4">
                            <i class="fas fa-music text-6xl text-gray-400 mb-4"></i>
                            <audio controls class="w-full max-w-md mx-auto">
                                <source src="{{ $file->file_url }}" type="{{ $file->mime_type }}">
                                Seu navegador não suporta a reprodução de áudio.
                            </audio>
                        </div>
                    @elseif($file->mime_type === 'application/pdf')
                        <div class="mb-4">
                            <i class="fas fa-file-pdf text-6xl text-red-500 mb-4"></i>
                            <p class="text-gray-600">Documento PDF</p>
                        </div>
                    @else
                        <div class="mb-4">
                            <i class="fas fa-file text-6xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600">{{ $file->original_name }}</p>
                        </div>
                    @endif
                </div>
                
                <!-- File Information -->
                <div class="p-6">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $file->original_name }}</h2>
                        
                        @if($file->category)
                            <span class="inline-block px-3 py-1 text-sm rounded-full mb-3" 
                                  style="background-color: {{ $file->category->color }}20; color: {{ $file->category->color }}">
                                {{ $file->category->name }}
                            </span>
                        @endif
                        
                        @if($file->description)
                            <p class="text-gray-600 mb-4">{{ $file->description }}</p>
                        @endif
                    </div>
                    
                    <!-- File Details -->
                    <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-hdd text-gray-400 mb-1"></i>
                            <p class="font-medium text-gray-900">{{ $file->fileSizeFormatted }}</p>
                            <p class="text-gray-500">Tamanho</p>
                        </div>
                        
                        <div class="text-center p-3 bg-gray-50 rounded-lg">
                            <i class="fas fa-file-alt text-gray-400 mb-1"></i>
                            <p class="font-medium text-gray-900">{{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }}</p>
                            <p class="text-gray-500">Formato</p>
                        </div>
                    </div>
                    
                    <!-- Link Information -->
                    @if($sharedLink->expires_at || $sharedLink->download_limit)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-yellow-600 mt-1 mr-3"></i>
                                <div class="text-sm">
                                    <p class="font-medium text-yellow-800 mb-1">Informações do Link</p>
                                    @if($sharedLink->expires_at)
                                        <p class="text-yellow-700 mb-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            Expira em: {{ $sharedLink->expires_at->format('d/m/Y H:i') }}
                                        </p>
                                    @endif
                                    @if($sharedLink->download_limit)
                                        <p class="text-yellow-700">
                                            <i class="fas fa-download mr-1"></i>
                                            Downloads restantes: {{ $sharedLink->download_limit - $sharedLink->download_count }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Password Form -->
                    @if($sharedLink->password_hash && !session('shared_link_' . $sharedLink->id . '_authenticated'))
                        <div class="mb-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center">
                                    <i class="fas fa-lock text-blue-600 mr-3"></i>
                                    <div>
                                        <p class="font-medium text-blue-800">Arquivo Protegido</p>
                                        <p class="text-blue-700 text-sm">Este arquivo está protegido por senha</p>
                                    </div>
                                </div>
                            </div>
                            
                            <form id="passwordForm" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Digite a senha para acessar o arquivo
                                    </label>
                                    <input type="password" id="password" name="password" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Senha">
                                </div>
                                
                                <button type="submit" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition duration-200">
                                    <i class="fas fa-unlock mr-2"></i>Acessar Arquivo
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Download Button -->
                        <div class="text-center">
                            <a href="{{ route('public.shared.download', $sharedLink->token) }}" 
                               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg text-lg font-medium transition duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-download mr-3"></i>
                                Baixar Arquivo
                            </a>
                            
                            @if(str_starts_with($file->mime_type, 'image/') || $file->mime_type === 'application/pdf')
                                <p class="text-sm text-gray-500 mt-3">
                                    <i class="fas fa-eye mr-1"></i>
                                    Você pode visualizar o arquivo acima antes de baixá-lo
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
                
                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 text-center">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-shield-alt mr-1"></i>
                        Arquivo compartilhado de forma segura
                    </p>
                </div>
            </div>
            
            <!-- Additional Info -->
            <div class="text-center mt-6">
                <p class="text-blue-100 text-sm">
                    <i class="fas fa-info-circle mr-1"></i>
                    Este link foi criado em {{ $sharedLink->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
    </div>
    
    <!-- Error Messages -->
    @if(session('error'))
        <div id="errorAlert" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ session('error') }}
            </div>
        </div>
    @endif
    
    @if(session('success'))
        <div id="successAlert" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                {{ session('success') }}
            </div>
        </div>
    @endif
    
    <script>
        // Auto-hide alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('#errorAlert, #successAlert');
            alerts.forEach(alert => {
                if (alert) {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(100%)';
                    setTimeout(() => alert.remove(), 300);
                }
            });
        }, 5000);
        
        // Password form submission
        @if($sharedLink->password_hash && !session('shared_link_' . $sharedLink->id . '_authenticated'))
        document.getElementById('passwordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const formData = new FormData();
            formData.append('password', password);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            try {
                const response = await fetch('{{ route("public.shared.download", $sharedLink->token) }}', {
                    method: 'POST',
                    body: formData
                });
                
                if (response.ok) {
                    // Check if response is a file download
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/')) {
                        // It's a file download, trigger download
                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = '{{ $file->original_name }}';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);
                        
                        // Show success message and reload to show download button
                        alert('Senha correta! Download iniciado.');
                        window.location.reload();
                    } else {
                        // Authentication successful, reload page
                        window.location.reload();
                    }
                } else {
                    alert('Senha incorreta. Tente novamente.');
                    document.getElementById('password').value = '';
                    document.getElementById('password').focus();
                }
            } catch (error) {
                alert('Erro ao verificar senha. Tente novamente.');
            }
        });
        @endif
        
        // Add smooth transitions
        document.addEventListener('DOMContentLoaded', function() {
            const card = document.querySelector('.bg-white.rounded-lg.shadow-xl');
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease-out';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        });
    </script>
</body>
</html>