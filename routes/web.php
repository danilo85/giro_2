<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\FinancialDashboardController;
use App\Http\Controllers\Financial\FileUploadController;
use App\Http\Controllers\OrcamentoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\AutorController;
use App\Http\Controllers\PagamentoController;
use App\Http\Controllers\ModeloPropostaController;
use App\Http\Controllers\OrcamentoFileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Social Login
    Route::get('/auth/{provider}', [SocialLoginController::class, 'redirectToProvider'])->name('social.redirect');
    Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'handleProviderCallback'])->name('social.callback');

    // Password Reset
    Route::get('/password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');
});

// Logout (available for authenticated users)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Test route for transactions create (temporarily without auth)
Route::get('/test-transaction-form', [TransactionController::class, 'create'])->name('test.transaction.form');

// Test route for categories API (temporarily without auth)
Route::get('/test-categories', [CategoryController::class, 'getCategoriesGrouped'])->name('test.categories');

// AJAX Routes (require session but not full auth middleware)
Route::get('/clientes/autocomplete', [ClienteController::class, 'autocomplete'])->name('clientes.autocomplete');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Management (Admin only)
    Route::middleware(['admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });

    // Profile
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'delete'])->name('profile.delete');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');

    // Financial Management Module
    Route::prefix('financial')->name('financial.')->group(function () {
        // Dashboard
        Route::get('/', [FinancialDashboardController::class, 'index'])->name('dashboard');
        Route::get('/summary/{year}/{month}', [FinancialDashboardController::class, 'getMonthlySummary'])->name('summary');
        Route::get('/chart-data/{year}/{month}', [FinancialDashboardController::class, 'getChartData'])->name('chart-data');

        // Banks
        Route::resource('banks', BankController::class);
        Route::get('/banks/{bank}/saldo', [BankController::class, 'getSaldo'])->name('banks.saldo');
        Route::post('/banks/{bank}/update-saldo', [BankController::class, 'updateSaldo'])->name('banks.update-saldo');
        Route::post('/banks/{bank}/recalculate-saldo', [BankController::class, 'recalculateSaldo'])->name('banks.recalculate-saldo');
        Route::get('/banks/{bank}/extrato', [BankController::class, 'extrato'])->name('banks.extrato');

        // Credit Cards
        Route::resource('credit-cards', CreditCardController::class);
        Route::get('/credit-cards/{creditCard}/info', [CreditCardController::class, 'getInfo'])->name('credit-cards.info');
        Route::get('/credit-cards/{creditCard}/statement', [CreditCardController::class, 'statement'])->name('credit-cards.statement');
        Route::post('/credit-cards/{creditCard}/pagar-fatura', [CreditCardController::class, 'pagarFatura'])->name('credit-cards.pagar-fatura');
        Route::post('/credit-cards/{creditCard}/update-limite', [CreditCardController::class, 'updateLimite'])->name('credit-cards.update-limite');
        Route::post('/credit-cards/refresh-limits', [CreditCardController::class, 'refreshLimits'])->name('credit-cards.refresh-limits');

        // Categories
        Route::resource('categories', CategoryController::class);
        Route::get('/categories/tipo/{tipo}', [CategoryController::class, 'getByType'])->name('categories.by-type');
        Route::get('/categories/all', [CategoryController::class, 'getAll'])->name('categories.all');

        // Transactions
        Route::resource('transactions', TransactionController::class);
        Route::post('/transactions/{transaction}/duplicate', [TransactionController::class, 'duplicate'])->name('transactions.duplicate');
        Route::post('/transactions/{transaction}/mark-paid', [TransactionController::class, 'markAsPaid'])->name('transactions.mark-paid');
        Route::post('/transactions/{transaction}/mark-pending', [TransactionController::class, 'markAsPending'])->name('transactions.mark-pending');
        Route::delete('/transactions/{transaction}/delete-all-installments', [TransactionController::class, 'destroyAllInstallments'])->name('transactions.delete-all-installments');
        Route::delete('/transactions/delete-selected-installments', [TransactionController::class, 'destroySelectedInstallments'])->name('transactions.delete-selected-installments');
        Route::get('/transactions/summary/{year}/{month}', [TransactionController::class, 'getMonthlySummary'])->name('transactions.summary');
        Route::get('/transactions/by-category/{year}/{month}', [TransactionController::class, 'getByCategory'])->name('transactions.by-category');
    });

    // Auth check route for web sessions
    Route::get('/api/auth/check', function () {
        return response()->json(['authenticated' => true, 'user' => auth()->user()]);
    })->name('api.auth.check');



    // API Routes for AJAX calls
    Route::prefix('api/financial')->name('api.financial.')->group(function () {
        // Categories API
        Route::get('/categories', [CategoryController::class, 'getCategoriesGrouped']);
        Route::get('/categories/all', [CategoryController::class, 'getAll']);
        Route::get('/categories/{tipo}', [CategoryController::class, 'getByType']);
        
        // Banks API
        Route::get('/banks', [BankController::class, 'apiIndex']);
        Route::get('/banks/{bank}/balance', [BankController::class, 'getSaldo']);
        Route::get('/banks/{bank}/transactions', [BankController::class, 'getTransactions']);
        
        // Credit Cards API
        Route::get('/credit-cards', [CreditCardController::class, 'apiIndex']);
        Route::get('/credit-cards/summary', [CreditCardController::class, 'getSummary']);
        Route::get('/credit-cards/{creditCard}/info', [CreditCardController::class, 'getInfo']);
        Route::post('/credit-cards/{creditCard}/update-used-limit', [CreditCardController::class, 'updateUsedLimit']);
        Route::post('/credit-cards/{creditCard}/calculate-limit', [CreditCardController::class, 'calculateLimit']);
        
        // Transactions API
        Route::get('/transactions', [TransactionController::class, 'apiIndex']);
        Route::post('/transactions', [TransactionController::class, 'store']);
        Route::put('/transactions/{transaction}', [TransactionController::class, 'update']);
        Route::post('/transactions/{transaction}/mark-paid', [TransactionController::class, 'markAsPaid']);
        Route::post('/transactions/{transaction}/mark-pending', [TransactionController::class, 'markAsPending']);
        Route::post('/transactions/{transaction}/duplicate', [TransactionController::class, 'duplicate']);
        Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy']);
        Route::delete('/transactions/{transaction}/delete-all-installments', [TransactionController::class, 'destroyAllInstallments']);
        Route::get('/transactions/{transaction}/installments', [TransactionController::class, 'getInstallments']);
        Route::delete('/transactions/delete-selected-installments', [TransactionController::class, 'destroySelectedInstallments'])->name('transactions.delete-selected-installments');
        
        // Credit Card Invoices API
        Route::get('/credit-card-invoices', [TransactionController::class, 'getCreditCardInvoices']);
        Route::post('/credit-card-invoices/pay', [TransactionController::class, 'payCreditCardInvoice']);
        Route::post('/credit-card-invoices/undo-payment', [TransactionController::class, 'undoCreditCardInvoicePayment']);

        // Dashboard data
        Route::get('/dashboard/summary/{year}/{month}', [TransactionController::class, 'getMonthlySummary']);
        Route::get('/dashboard/categories/{year}/{month}', [TransactionController::class, 'getByCategory']);

        // File uploads
        Route::post('/files/upload', [FileUploadController::class, 'upload']);
        Route::get('/files/transaction/{transactionId}', [FileUploadController::class, 'getFiles']);
        Route::delete('/files/{fileId}', [FileUploadController::class, 'delete']);
        Route::get('/files/{fileId}/download', [FileUploadController::class, 'download']);
        
        // Temporary file uploads (for create transactions)
        Route::post('/files/upload-temp', [FileUploadController::class, 'uploadTemp']);
        Route::get('/files/temp/{tempId}', [FileUploadController::class, 'getTempFiles']);
        Route::post('/files/move-temp-to-transaction', [FileUploadController::class, 'moveTempFilesToTransaction']);
        Route::delete('/files/temp/{fileId}', [FileUploadController::class, 'deleteTempFile']);
        
    });

    // Budget Management Module (Módulo de Orçamentos)
    Route::resource('orcamentos', OrcamentoController::class);
    Route::patch('/orcamentos/{orcamento}/quitar', [OrcamentoController::class, 'quitar'])->name('orcamentos.quitar');

    // Clientes
    Route::resource('clientes', ClienteController::class);

    // Autores
    Route::resource('autores', AutorController::class)->parameters(['autores' => 'autor']);

    // Pagamentos
    Route::resource('pagamentos', PagamentoController::class);

    // Modelos de Propostas
    Route::resource('modelos-propostas', ModeloPropostaController::class);
    Route::post('modelos-propostas/{modelo_proposta}/duplicate', [ModeloPropostaController::class, 'duplicate'])->name('modelos-propostas.duplicate');
   



    // Debug Routes (apenas para desenvolvimento)
    Route::prefix('debug')->name('debug.')->group(function () {
        Route::get('/csrf', function () {
            return view('debug.csrf');
        })->name('csrf');
        
        Route::get('/session-check', function () {
            return view('debug.session-check');
        })->name('session-check');
        
        Route::post('/test-csrf', function () {
            return response()->json([
                'success' => true,
                'message' => 'CSRF funcionando corretamente!',
                'data' => request()->all(),
                'session_id' => session()->getId(),
                'csrf_token' => csrf_token()
            ]);
        })->name('test-csrf');
        
        Route::get('/test-payment', function () {
            return view('debug.test-payment');
        });
        
        Route::get('/csrf-fix', function () {
            return view('debug.csrf-fix');
        });
        
        Route::post('/csrf-refresh', function () {
            return response()->json([
                'success' => true,
                'token' => csrf_token(),
                'session_id' => session()->getId()
            ]);
        });
    });

    // API Routes for Budget Module
    Route::prefix('api/budget')->name('api.budget.')->group(function () {
        // Orçamentos API
        Route::patch('/orcamentos/{orcamento}/quitar', [OrcamentoController::class, 'quitar'])->name('orcamentos.quitar');
        Route::patch('/orcamentos/{orcamento}/aprovar', [OrcamentoController::class, 'aprovar'])->name('orcamentos.aprovar');
        Route::patch('/orcamentos/{orcamento}/rejeitar', [OrcamentoController::class, 'rejeitar'])->name('orcamentos.rejeitar');
        Route::patch('/orcamentos/{orcamento}/status', [OrcamentoController::class, 'atualizarStatus'])->name('orcamentos.status');
        
        // Clientes API
        Route::get('/clientes/autocomplete', [ClienteController::class, 'autocomplete'])->name('clientes.autocomplete');
        
        // Autores API
        Route::get('/autores/autocomplete', [AutorController::class, 'autocomplete'])->name('autores.autocomplete');
        
        // Modelos de Propostas API
        Route::get('/modelos-propostas/autocomplete', [ModeloPropostaController::class, 'autocomplete'])->name('modelos-propostas.autocomplete');
        Route::get('/modelos-propostas/{modeloProposta}/conteudo', [ModeloPropostaController::class, 'getConteudo'])->name('modelos-propostas.conteudo');
        
        // Upload de Arquivos para Orçamentos
        Route::post('/orcamentos/{orcamento}/files/upload', [OrcamentoFileController::class, 'upload'])->name('orcamentos.files.upload');
        Route::get('/orcamentos/{orcamento}/files', [OrcamentoFileController::class, 'getFiles'])->name('orcamentos.files.get');
        Route::delete('/orcamentos/files/{file}', [OrcamentoFileController::class, 'delete'])->name('orcamentos.files.delete');
        Route::get('/orcamentos/files/{file}/download', [OrcamentoFileController::class, 'download'])->name('orcamentos.files.download');
        Route::patch('/orcamentos/files/{file}/description', [OrcamentoFileController::class, 'updateDescription'])->name('orcamentos.files.description');
    });
});

// Public Routes for Budget Module (Rotas Públicas)
Route::prefix('public')->name('public.')->group(function () {
    // Rotas públicas para orçamentos
    Route::get('/orcamento/{token}', [OrcamentoController::class, 'showPublic'])->name('orcamentos.public');
    Route::patch('/orcamento/{token}/aprovar', [OrcamentoController::class, 'aprovarPublico'])->name('orcamentos.public.aprovar');
    Route::patch('/orcamento/{token}/rejeitar', [OrcamentoController::class, 'rejeitarPublico'])->name('orcamentos.public.rejeitar');
});

// File upload routes moved to RouteServiceProvider (without any middleware)

// Debug routes (only in development)
if (app()->environment('local')) {
    require __DIR__.'/debug.php';
}

// Incluir rotas de teste
require __DIR__ . '/test.php';
