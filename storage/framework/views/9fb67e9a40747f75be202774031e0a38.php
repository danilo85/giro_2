<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Proposta #<?php echo e($orcamento->numero); ?> - <?php echo e($orcamento->cliente->name); ?></title>
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700;800&display=swap" rel="stylesheet">
    
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        .font-open-sans {
            font-family: 'Open Sans', sans-serif;
        }
    </style>
</head>
<body class="h-full bg-gray-50 font-open-sans">

    
    <style>
        @media print {
            body {
                background-color: #fff !important;
            }
            .no-print {
                display: none !important;
            }
            .print-container {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-bg-transparent {
                background-color: transparent !important;
            }
            .print-grid-cols-2 {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            }
        }
    </style>

    
    <?php if(session()->has('success')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
             class="no-print fixed top-5 right-5 z-50 rounded-md bg-green-600 px-4 py-3 text-sm font-bold text-white shadow-lg font-open-sans">
            <p><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>

    
    <?php if(session()->has('undo_action')): ?>
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 8000)" x-show="show" x-transition
            class="no-print fixed bottom-5 right-5 z-50 rounded-md bg-gray-800 px-4 py-3 text-sm text-white shadow-lg font-open-sans">
            <div class="flex items-center gap-4">
                <p><?php echo e(session('undo_action')); ?></p>
                <button wire:click="revertStatus" class="font-bold underline hover:text-yellow-400">Desfazer</button>
            </div>
        </div>
    <?php endif; ?>

    <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8 font-open-sans">
        <div class="bg-white p-8 md:p-12 shadow-lg rounded-md relative print-container">
            
            <?php
                $statusColors = [
                    'pendente' => 'bg-gray-400',
                    'analisando' => 'bg-yellow-400', 
                    'aprovado' => 'bg-green-500', 
                    'rejeitado' => 'bg-red-500'
                ];
                $statusClass = $statusColors[strtolower($orcamento->status)] ?? $statusColors['pendente'];
            ?>
            
            <!-- Status Badge (bolinha pulsante) -->
            <div class="absolute top-8 right-8 flex items-center space-x-2 animate-pulse no-print">
                <div class="w-4 h-4 <?php echo e($statusClass); ?> rounded-full"></div>
                            </div>

            <header class="flex justify-between items-start mb-12">
                <div class="flex items-start space-x-6">
                    
                    <?php if(optional($orcamento->cliente->user)->avatar && file_exists(storage_path('app/public/' . $orcamento->cliente->user->avatar))): ?>
                        <img src="<?php echo e(optional($orcamento->cliente->user)->avatar_url); ?>" alt="Logo da Empresa" class="h-16 w-16 rounded-lg object-cover border border-gray-200">
                    <?php else: ?>
                        <div class="bg-gray-800 text-white h-16 w-16 flex items-center justify-center rounded-lg font-bold text-lg">
                            <span class="text-white"><?php echo e(strtoupper(substr(optional($orcamento->cliente->user)->name ?? 'U', 0, 1))); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <div>
                        <h1 class="text-6xl font-black text-gray-800 tracking-tighter">PROPOSTA</h1>
                        <div class="mt-4  text-gray-500">
                            <p>Válido de <?php echo e($orcamento->data_orcamento->format('d/m/Y')); ?> a <?php echo e($orcamento->data_validade ? $orcamento->data_validade->format('d/m/Y') : 'Não definido'); ?></p>
                            <p>Para <span class="font-semibold text-gray-700"><?php echo e($orcamento->cliente->nome); ?></span></p>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="bg-gray-800 text-white w-12 h-12 flex items-center justify-center rounded-full">
                        <span class="text-l text-white"><?php echo e($orcamento->id); ?></span>
                    </div>
                </div>
            </header>

            <main>
                <div class="mb-8">
                    <h2 class="font-bold text-gray-900 text-lg mb-3">Orçamento:</h2>
                    <p class="text-gray-800 font-medium mb-4"><?php echo e($orcamento->titulo); ?></p>
                    <div class="prose max-w-none text-gray-700 leading-relaxed text-justify">
                        <?php echo $orcamento->descricao; ?>

                    </div>
                </div>

                <div class="mb-8">
                    <p class="font-bold text-gray-900">Prazo:</p>
                    <p class="text-gray-700 mt-1">Prazo estimado é de <?php echo e($orcamento->prazo_entrega_dias); ?> dias úteis</p>
                </div>

                <div class="bg-gray-50  p-8 mb-0  border border-gray-200">
                    <div>
                        <p class="text-gray-500 uppercase text-sm font-semibold tracking-wide">Total</p>
                        <p class="text-5xl font-black text-gray-900 mt-2">R$ <?php echo e(number_format($orcamento->valor_total, 2, ',', '.')); ?></p>
                        <p class="mt-3 text-sm text-gray-600">Forma de pagamento: <?php echo e($orcamento->condicoes_pagamento); ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 text-white font-bold overflow-hidden shadow-sm print-grid-cols-2">
                    <div class="bg-gray-800 p-6">
                        <p class="text-xs uppercase tracking-wider text-gray-300 mb-2">40% para iniciar</p>
                        <p class="text-2xl font-bold">1º R$ <?php echo e(number_format($orcamento->valor_total * 0.4, 2, ',', '.')); ?></p>
                    </div>
                    <div class="bg-gray-700 p-6">
                        <p class="text-xs uppercase tracking-wider text-gray-300 mb-2">60% ao término</p>
                        <p class="text-2xl font-bold">2º R$ <?php echo e(number_format($orcamento->valor_total * 0.6, 2, ',', '.')); ?></p>
                    </div>
                </div>
            

                <div class="bg-white p-6 mt-10 no-print" x-data="{ confirmingApproval: false, confirmingRejection: false }">
                     <?php if(strtolower($orcamento->status) === 'analisando'): ?>
                        <div class="text-center">
                            <h3 class="text-lg font-semibold text-gray-800">Ações</h3>
                            <p class="text-gray-600 mt-1 mb-4">O que você gostaria de fazer em relação a esta proposta?</p>
                            <div class="flex justify-center flex-wrap gap-4 no-print">
                                <button @click="confirmingApproval = true" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-md transition-colors">Aprovar Proposta</button>
                                <button @click="confirmingRejection = true" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-md transition-colors">Rejeitar</button>
                                <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-md transition-colors">Imprimir / Salvar PDF</button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center">
                            <p class="text-lg font-semibold italic text-gray-600">Este orçamento foi <?php echo e(strtolower($orcamento->status)); ?><?php echo e($orcamento->updated_at ? ' em ' . $orcamento->updated_at->format('d/m/Y') : ''); ?>.</p>
                             <div class="flex justify-center flex-wrap gap-4 mt-4 no-print">
                                <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-md transition-colors">Imprimir / Salvar PDF</button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Modal de Rejeição -->
                    <div x-show="confirmingRejection" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 no-print" style="display: none;">
                        <div @click.away="confirmingRejection = false" class="bg-white rounded-lg p-6 max-w-sm mx-auto text-center">
                            <h3 class="text-lg font-bold">Confirmar Rejeição</h3>
                            <p class="mt-2 text-sm text-gray-600">Tem certeza que deseja rejeitar esta proposta?</p>
                            <div class="mt-4 flex justify-center gap-4">
                                <button @click="confirmingRejection = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Cancelar</button>
                                <button onclick="rejeitarOrcamento()" @click="confirmingRejection = false" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">Sim, Rejeitar</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal de Aprovação -->
                    <div x-show="confirmingApproval" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 no-print" style="display: none;">
                        <div @click.away="confirmingApproval = false" class="bg-white rounded-lg p-6 max-w-sm mx-auto text-center">
                            <h3 class="text-lg font-bold">Confirmar Aprovação</h3>
                            <p class="mt-2 text-sm text-gray-600">Tem certeza que deseja aprovar este orçamento?</p>
                            <div class="mt-4 flex justify-center gap-4">
                                <button @click="confirmingApproval = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Cancelar</button>
                                <button onclick="aprovarOrcamento()" @click="confirmingApproval = false" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">Sim, Aprovar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>


            
            <footer class="mt-16 border-t border-gray-200 pt-8">
                <div class="flex justify-between items-start">
                    
                    <div class="flex flex-col space-y-4">
                        
                        <div class="flex items-center space-x-4">
                            
                            <?php
                                $iconLogo = optional($orcamento->cliente->user)->getLogoByType('vertical');
                            ?>
                            <?php if($iconLogo && file_exists(storage_path('app/public/' . $iconLogo->caminho))): ?>
                                <img src="<?php echo e($iconLogo->url); ?>" alt="Logo da Empresa" class="h-16 w-auto rounded">
                            <?php else: ?>
                                <div class="bg-gray-800 text-white px-4 py-2 rounded font-bold text-lg">
                                    <span class="text-white">LOGO</span>
                                </div>
                            <?php endif; ?>
                            
                            
                            
                        </div>

                        
                        
                    </div>

                    
                    <?php
                        $logoIcone = optional($orcamento->cliente->user)->getLogoByType('icone');
                    ?>
                    <?php if($logoIcone && file_exists(storage_path('app/public/' . $logoIcone->caminho))): ?>
                        <img src="<?php echo e($logoIcone->url); ?>" alt="Logo Ícone" class="h-16 w-auto rounded">
                    <?php else: ?>
                        <div class="w-16 h-16 bg-gray-200 border-2 border-gray-300 flex items-center justify-center">
                            <span class="text-gray-600 text-xs font-bold">ÍCONE</span>
                        </div>
                    <?php endif; ?>
                </div>
            </footer>
        </div>
    </div>
</div>

<script>
    function aprovarOrcamento() {
        fetch('<?php echo e(route("public.orcamentos.public.aprovar", $orcamento->token_publico)); ?>', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Orçamento aprovado com sucesso!');
                location.reload();
            } else {
                alert('Erro ao aprovar orçamento: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao aprovar orçamento');
        });
    }
    
    function rejeitarOrcamento() {
        fetch('<?php echo e(route("public.orcamentos.public.rejeitar", $orcamento->token_publico)); ?>', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Orçamento rejeitado com sucesso!');
                location.reload();
            } else {
                alert('Erro ao rejeitar orçamento: ' + (data.message || 'Erro desconhecido'));
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao rejeitar orçamento');
        });
    }


</script>
</body>
</html>
<?php /**PATH C:\laragon\www\giro_2\resources\views/orcamentos/public.blade.php ENDPATH**/ ?>