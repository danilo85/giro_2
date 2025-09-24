<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema em Manutenção</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-auto">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 text-center">
            <!-- Maintenance Icon -->
            <div class="mb-6">
                <div class="mx-auto w-16 h-16 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Title -->
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                Sistema em Manutenção
            </h1>
            
            <!-- Message -->
            <p class="text-gray-600 dark:text-gray-300 mb-6">
                Estamos realizando uma manutenção programada no sistema. Por favor, tente novamente em alguns minutos.
            </p>
            
            <!-- Additional Info -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Se você é um administrador, faça login para acessar o sistema.
                </p>
            </div>
            
            <!-- Login Button -->
            <div class="space-y-3">
                <a href="{{ route('login') }}" 
                   class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 inline-block">
                    Fazer Login
                </a>
                
                <button onclick="window.location.reload()" 
                        class="w-full bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 font-medium py-2 px-4 rounded-lg transition duration-200">
                    Tentar Novamente
                </button>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                © {{ date('Y') }} Sistema GIRO. Todos os direitos reservados.
            </p>
        </div>
    </div>
    
    <!-- Dark mode toggle script -->
    <script>
        // Check for saved theme preference or default to light mode
        const theme = localStorage.getItem('theme') || 'light';
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
</body>
</html>