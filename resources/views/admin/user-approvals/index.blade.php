@extends('layouts.app')

@section('title', 'Aprovação de Usuários')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Tags de Navegação Rápida -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Dashboard
        </a>
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
            Aprovação de Usuários
        </span>
        <a href="{{ route('profile.show') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Perfil
        </a>
        <a href="{{ route('settings.index') }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-full border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Configurações
        </a>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Aprovação de Usuários</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Gerencie aprovações de novos usuários do sistema</p>
            </div>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 dark:from-yellow-600 dark:to-yellow-700 rounded-lg shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm font-medium">Usuários Pendentes</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $pendingUsers->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 dark:from-green-600 dark:to-green-700 rounded-lg shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Usuários Aprovados</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $approvedUsers->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-lg shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Registros Hoje</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $pendingUsers->where('created_at', '>=', today())->count() + $approvedUsers->where('created_at', '>=', today())->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 rounded-lg shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Total no Sistema</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ $pendingUsers->total() + $approvedUsers->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Filters -->
    <div class="flex flex-wrap gap-2 mb-6">
        <button onclick="showTab('pending')" id="pending-filter" 
                class="filter-btn inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
            </svg>
            Pendentes ({{ $pendingUsers->count() }})
        </button>
        <button onclick="showTab('approved')" id="approved-filter" 
                class="filter-btn inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
            </svg>
            Aprovados ({{ $approvedUsers->count() }})
        </button>
        <button onclick="filterToday()" id="today-filter" 
                class="filter-btn inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
            </svg>
            Hoje
        </button>
        <button onclick="showAll()" id="all-filter" 
                class="filter-btn inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 transition-colors duration-200 dark:border-gray-600 dark:text-gray-300 dark:bg-gray-800 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
            </svg>
            Todos
        </button>
    </div>

    <!-- Content Container -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Pending Users Tab -->
            <div id="pending-content" class="tab-content">
                @if($pendingUsers->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                        @foreach($pendingUsers as $user)
                            <div class="user-card bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                                <!-- User Avatar & Status -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="relative">
                                        @if($user->avatar)
                                            <img class="w-12 h-12 rounded-full" 
                                                 src="{{ $user->getAvatarUrlAttribute() }}" 
                                                 alt="{{ $user->name }}">
                                        @else
                                            <img class="w-12 h-12 rounded-full" 
                                                 src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=3B82F6&background=EBF4FF" 
                                                 alt="{{ $user->name }}">
                                        @endif
                                        <!-- Status indicator -->
                                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-yellow-400 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                        <!-- Admin Crown -->
                                        @if($user->isAdmin())
                                            <div class="absolute -top-1 -right-1 w-4 h-4 text-yellow-500">
                                                <svg fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        Pendente
                                    </span>
                                </div>
                                
                                <!-- User Info -->
                                <div class="mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300">
                                        {{ $user->isAdmin() ? 'Administrador' : 'Usuário Padrão' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Registrado em {{ $user->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center justify-center space-x-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <!-- Approve -->
                                    <form method="POST" action="{{ route('admin.user-approvals.approve', $user) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="p-2 rounded-lg text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 dark:text-green-400 transition-all duration-200" 
                                                title="Aprovar usuário">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    
                                    <!-- Reject -->
                                    <form method="POST" action="{{ route('admin.user-approvals.reject', $user) }}" class="inline" 
                                          onsubmit="return confirm('Tem certeza que deseja rejeitar este usuário?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 dark:text-red-400 transition-all duration-200" 
                                                title="Rejeitar usuário">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-center">
                        {{ $pendingUsers->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum usuário pendente</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Todos os usuários foram aprovados ou não há novos registros.</p>
                    </div>
                @endif
            </div>

            <!-- Approved Users Tab -->
            <div id="approved-content" class="tab-content hidden">
                @if($approvedUsers->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                        @foreach($approvedUsers as $user)
                            <div class="user-card bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                                <!-- User Avatar & Status -->
                                <div class="flex items-center justify-between mb-4">
                                    <div class="relative">
                                        @if($user->avatar)
                                            <img class="w-12 h-12 rounded-full" 
                                                 src="{{ $user->getAvatarUrlAttribute() }}" 
                                                 alt="{{ $user->name }}">
                                        @else
                                            <img class="w-12 h-12 rounded-full" 
                                                 src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=3B82F6&background=EBF4FF" 
                                                 alt="{{ $user->name }}">
                                        @endif
                                        <!-- Status indicator -->
                                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-white dark:border-gray-800 rounded-full"></div>
                                        <!-- Admin Crown -->
                                        @if($user->isAdmin())
                                            <div class="absolute -top-1 -right-1 w-4 h-4 text-yellow-500">
                                                <svg fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Aprovado
                                    </span>
                                </div>
                                
                                <!-- User Info -->
                                <div class="mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">{{ $user->name }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ $user->email }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300">
                                        {{ $user->isAdmin() ? 'Administrador' : 'Usuário Padrão' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Registrado em {{ $user->created_at->format('d/m/Y') }}
                                    </p>
                                </div>
                                
                                <!-- Actions -->
                                <div class="flex items-center justify-center pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <!-- Remove Approval -->
                                     <form method="POST" action="{{ route('admin.user-approvals.remove-approval', $user) }}" class="inline remove-approval-form">
                                         @csrf
                                         @method('PATCH')
                                         <button type="button" 
                                                 class="p-2 rounded-lg text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 dark:text-yellow-400 transition-all duration-200 remove-approval-btn" 
                                                 title="Remover aprovação"
                                                 data-user-name="{{ $user->name }}">
                                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                             </svg>
                                         </button>
                                     </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 flex justify-center">
                        {{ $approvedUsers->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhum usuário aprovado</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ainda não há usuários aprovados no sistema.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação para Remover Aprovação -->
<div id="removeApprovalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 10003;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800 dark:border-gray-700">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 dark:bg-yellow-900">
                <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mt-4">Confirmar Remoção de Aprovação</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tem certeza que deseja remover a aprovação deste usuário?
                </p>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mt-2" id="userNameDisplay"></p>
            </div>
            <div class="items-center px-4 py-3">
                <div class="flex space-x-3">
                    <button id="cancelRemoveApproval" 
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200">
                        Cancelar
                    </button>
                    <button id="confirmRemoveApproval" 
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200">
                        Remover Aprovação
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentFilter = 'pending';
let currentRemovalForm = null;

// Modal functionality for remove approval
function showRemoveApprovalModal(userName, form) {
    currentRemovalForm = form;
    document.getElementById('userNameDisplay').textContent = userName;
    document.getElementById('removeApprovalModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideRemoveApprovalModal() {
    document.getElementById('removeApprovalModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentRemovalForm = null;
}

function confirmRemoveApproval() {
    if (currentRemovalForm) {
        currentRemovalForm.submit();
    }
    hideRemoveApprovalModal();
}

// Filter functionality
function showTab(tabName) {
    currentFilter = tabName;
    
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all filter buttons
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.classList.remove('bg-blue-100', 'text-blue-800', 'dark:bg-blue-900', 'dark:text-blue-200');
        button.classList.add('border-gray-300', 'text-gray-700', 'dark:border-gray-600', 'dark:text-gray-300');
    });
    
    // Show selected tab content
    document.getElementById(tabName + '-content').classList.remove('hidden');
    
    // Add active class to selected filter button
    const activeButton = document.getElementById(tabName + '-filter');
    activeButton.classList.remove('border-gray-300', 'text-gray-700', 'dark:border-gray-600', 'dark:text-gray-300');
    activeButton.classList.add('bg-blue-100', 'text-blue-800', 'dark:bg-blue-900', 'dark:text-blue-200');
}

function filterToday() {
    // Show all content first
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('hidden');
    });
    
    // Remove active class from all filter buttons
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.classList.remove('bg-blue-100', 'text-blue-800', 'dark:bg-blue-900', 'dark:text-blue-200');
        button.classList.add('border-gray-300', 'text-gray-700', 'dark:border-gray-600', 'dark:text-gray-300');
    });
    
    // Add active class to today filter
    const todayButton = document.getElementById('today-filter');
    todayButton.classList.remove('border-gray-300', 'text-gray-700', 'dark:border-gray-600', 'dark:text-gray-300');
    todayButton.classList.add('bg-blue-100', 'text-blue-800', 'dark:bg-blue-900', 'dark:text-blue-200');
    
    currentFilter = 'today';
}

function showAll() {
    // Show all content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('hidden');
    });
    
    // Remove active class from all filter buttons
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.classList.remove('bg-blue-100', 'text-blue-800', 'dark:bg-blue-900', 'dark:text-blue-200');
        button.classList.add('border-gray-300', 'text-gray-700', 'dark:border-gray-600', 'dark:text-gray-300');
    });
    
    // Add active class to all filter
    const allButton = document.getElementById('all-filter');
    allButton.classList.remove('border-gray-300', 'text-gray-700', 'dark:border-gray-600', 'dark:text-gray-300');
    allButton.classList.add('bg-blue-100', 'text-blue-800', 'dark:bg-blue-900', 'dark:text-blue-200');
    
    currentFilter = 'all';
}

// Initialize first tab as active
document.addEventListener('DOMContentLoaded', function() {
    showTab('pending');
    
    // Add event listeners for remove approval buttons
    document.querySelectorAll('.remove-approval-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userName = this.getAttribute('data-user-name');
            const form = this.closest('.remove-approval-form');
            showRemoveApprovalModal(userName, form);
        });
    });
    
    // Add event listeners for modal buttons
    document.getElementById('cancelRemoveApproval').addEventListener('click', hideRemoveApprovalModal);
    document.getElementById('confirmRemoveApproval').addEventListener('click', confirmRemoveApproval);
    
    // Close modal when clicking outside
    document.getElementById('removeApprovalModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideRemoveApprovalModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('removeApprovalModal').classList.contains('hidden')) {
            hideRemoveApprovalModal();
        }
    });
});

function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
    });
}

function bulkApprove() {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Selecione pelo menos um usuário para aprovar.');
        return;
    }
    
    if (confirm(`Tem certeza que deseja aprovar ${checkedBoxes.length} usuário(s)?`)) {
        const form = document.getElementById('bulkApprovalForm');
        form.action = '{{ route("admin.user-approvals.bulk-approve") }}';
        form.submit();
    }
}

function bulkDelete() {
    const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Selecione pelo menos um usuário para excluir.');
        return;
    }
    
    if (confirm(`Tem certeza que deseja excluir ${checkedBoxes.length} usuário(s)? Esta ação não pode ser desfeita.`)) {
        const form = document.getElementById('bulkApprovalForm');
        form.action = '{{ route("admin.user-approvals.bulk-delete") }}';
        form.submit();
    }
}

// Update select all checkbox state when individual checkboxes change
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    const selectAll = document.getElementById('selectAll');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const totalCheckboxes = checkboxes.length;
            const checkedCheckboxes = document.querySelectorAll('.user-checkbox:checked').length;
            
            if (selectAll) {
                selectAll.checked = totalCheckboxes === checkedCheckboxes;
                selectAll.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;
            }
        });
    });
});
</script>
@endpush
@endsection