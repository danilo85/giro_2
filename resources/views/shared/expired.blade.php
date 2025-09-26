<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Expirado - Sistema de Gestão de Arquivos</title>
    
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
        <div class="max-w-lg w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="bg-white rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-clock text-3xl text-red-500"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Link Expirado</h1>
                <p class="text-blue-100">Este link compartilhado não está mais disponível</p>
            </div>
            
            <!-- Main Card -->
            <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                <!-- Content -->
                <div class="p-8 text-center">
                    <div class="mb-6">
                        <i class="fas fa-exclamation-triangle text-6xl text-red-500 mb-4"></i>
                    </div>
                    
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Link Não Disponível</h2>
                    
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start justify-center">
                            <i class="fas fa-info-circle text-red-600 mt-1 mr-3"></i>
                            <div class="text-sm text-left">
                                <p class="font-medium text-red-800 mb-2">Este link compartilhado expirou</p>
                                <p class="text-red-700">O arquivo não está mais disponível através deste link. Possíveis motivos:</p>
                                <ul class="list-disc list-inside text-red-700 mt-2 space-y-1">
                                    <li>O prazo de validade do link expirou</li>
                                    <li>O limite de downloads foi atingido</li>
                                    <li>O link foi revogado pelo proprietário</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <p class="text-gray-600">
                            Se você precisa acessar este arquivo, entre em contato com a pessoa que compartilhou o link para solicitar um novo.
                        </p>
                        
                        <div class="pt-4">
                            <button onclick="window.history.back()" 
                                    class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 shadow-lg hover:shadow-xl">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Voltar
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 text-center">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-shield-alt mr-1"></i>
                        Sistema de Gestão de Arquivos
                    </p>
                </div>
            </div>
            
            <!-- Additional Info -->
            <div class="text-center mt-6">
                <p class="text-blue-100 text-sm">
                    <i class="fas fa-clock mr-1"></i>
                    Links compartilhados possuem prazo de validade para garantir a segurança
                </p>
            </div>
        </div>
    </div>
</body>
</html>