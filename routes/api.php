<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Financial\FileUploadController;
use App\Http\Controllers\PortfolioApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



// File upload routes moved to web.php for proper session handling

// Financial API routes
Route::middleware(['auth:sanctum'])->prefix('financial')->name('api.financial.')->group(function () {
    Route::delete('/transactions/delete-selected-installments', [TransactionController::class, 'destroySelectedInstallments'])->name('transactions.delete-selected-installments');
});

// Portfolio Public API Routes (Rotas Públicas da API do Portfólio)
Route::prefix('portfolio')->name('api.portfolio.')->group(function () {
    // Listar trabalhos públicos
    Route::get('/works', [PortfolioApiController::class, 'works'])->name('works');
    
    // Detalhes de um trabalho específico
    Route::get('/works/{work:slug}', [PortfolioApiController::class, 'work'])->name('work');
    
    // Listar categorias públicas
    Route::get('/categories', [PortfolioApiController::class, 'categories'])->name('categories');
    
    // Estatísticas do portfólio
    Route::get('/stats', [PortfolioApiController::class, 'stats'])->name('stats');
    
    // Busca no portfólio
    Route::get('/search', [PortfolioApiController::class, 'search'])->name('search');
    
    // Trabalhos relacionados
    Route::get('/works/{work:slug}/related', [PortfolioApiController::class, 'getRelatedWorks'])->name('works.related');
    
    // Portfólio de um usuário específico (autor)
    Route::get('/users/{user}/works', [PortfolioApiController::class, 'userPortfolio'])->name('users.works');
});

// Note: Other Financial API routes moved to routes/web.php to avoid middleware conflicts
