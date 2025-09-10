@if($clientes->count() > 0)
    <!-- Grid de Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($clientes as $cliente)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200 flex flex-col h-full">
                <!-- Header do Card -->
                <div class="p-6 pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            @if($cliente->avatar)
                                <img src="{{ Storage::url($cliente->avatar) }}" 
                                     alt="{{ $cliente->nome }}" 
                                     class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-lg">
                                        {{ strtoupper(substr($cliente->nome, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white break-words">{{ $cliente->nome }}</h3>
                                @if($cliente->empresa)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $cliente->empresa }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Badges de Status -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        @if($cliente->orcamentos_count > 0)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $cliente->orcamentos_count }} Orçamento{{ $cliente->orcamentos_count !== 1 ? 's' : '' }}
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Sem Orçamentos
                            </span>
                        @endif
                        
                        {{-- Badge Novo --}}
                        @if($cliente->novo)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Novo
                            </span>
                        @endif
                        
                        {{-- Badge Cadastro Incompleto --}}
                        @if($cliente->cadastro_incompleto)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                Cadastro Incompleto
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Detalhes do Cliente -->
                <div class="px-6 pb-6">
                    <div class="space-y-3">
                        <!-- Informações de Contato -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="space-y-2">
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-gray-900 dark:text-white truncate">{{ $cliente->email }}</span>
                                </div>
                                @if($cliente->telefone)
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                        <span class="text-gray-900 dark:text-white">{{ $cliente->telefone }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Valor Total dos Orçamentos -->
                        @if($cliente->orcamentos_count > 0)
                            <div class="text-center bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Valor Total em Orçamentos</p>
                                <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                    R$ {{ number_format($cliente->orcamentos->sum('valor_total'), 2, ',', '.') }}
                                </p>
                            </div>
                        @endif
                        
                        <!-- Data de Cadastro -->
                        <div class="text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Cadastrado em {{ $cliente->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Flex grow para empurrar os botões para o rodapé -->
                <div class="flex-grow"></div>

                <!-- Actions Footer -->
                <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200 dark:border-gray-700 mt-auto">
                    <div class="flex space-x-3">
                        <a href="{{ route('clientes.show', $cliente) }}" 
                           class="p-2 rounded-lg text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-all duration-200 hover:bg-blue-50 dark:hover:bg-blue-900/20" 
                           title="Visualizar Cliente">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </a>
                        <a href="{{ route('clientes.edit', $cliente) }}" 
                           class="p-2 text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300 transition-all duration-200" 
                           title="Editar Cliente">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="flex space-x-3">
                        <button onclick="deleteCliente({{ $cliente->id }})" 
                                class="p-2 rounded-lg text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-all duration-200 hover:bg-red-50 dark:hover:bg-red-900/20" 
                                title="Excluir Cliente">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        @if(request('search'))
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum cliente encontrado</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Tente ajustar os filtros de busca.</p>
        @else
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum cliente cadastrado</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando seu primeiro cliente.</p>
            <div class="mt-6">
                <a href="{{ route('clientes.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Novo Cliente
                </a>
            </div>
        @endif
    </div>
@endif