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
use App\Http\Controllers\ExtratoController;
use App\Http\Controllers\HistoricoController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\PortfolioCategoryController;
use App\Http\Controllers\PortfolioApiController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FileCategoryController;
use App\Http\Controllers\SharedLinkController;
use App\Utils\MimeTypeDetector;

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

// Maintenance page (accessible during maintenance mode)
Route::get('/maintenance', function () {
    return view('maintenance');
})->name('maintenance');

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Register
    Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register')->middleware('check.public.registration');
    Route::post('/register', [LoginController::class, 'register'])->middleware('check.public.registration');

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

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard')->with('success', 'Email verificado com sucesso!');
    })->middleware(['signed'])->name('verification.verify');
    
    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link de verificação enviado!');
    })->middleware(['throttle:6,1'])->name('verification.send');
});

// Test route for transactions create (temporarily without auth)
Route::get('/test-transaction-form', [TransactionController::class, 'create'])->name('test.transaction.form');

// Test route for categories API (temporarily without auth)
Route::get('/test-categories', [CategoryController::class, 'getCategoriesGrouped'])->name('test.categories');



// AJAX Routes (require session but not full auth middleware)
Route::get('/clientes/autocomplete', [ClienteController::class, 'autocomplete'])->name('clientes.autocomplete');

// Protected Routes
Route::middleware(['auth', 'conditional.verified'])->group(function () {
    // Main Dashboard (redirects to financial dashboard)
    Route::get('/dashboard', function () {
        return redirect()->route('financial.dashboard');
    })->name('dashboard');
    
    // Budget Dashboard
    Route::get('/orcamentos/dashboard', [DashboardController::class, 'index'])->name('orcamentos.dashboard');

    // User Management (Admin only)
    Route::middleware(['admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // User Approval Management
         Route::prefix('admin/user-approvals')->name('admin.user-approvals.')->group(function () {
             Route::get('/', [\App\Http\Controllers\Admin\UserApprovalController::class, 'index'])->name('index');
             Route::patch('/{user}/approve', [\App\Http\Controllers\Admin\UserApprovalController::class, 'approve'])->name('approve');
             Route::patch('/{user}/reject', [\App\Http\Controllers\Admin\UserApprovalController::class, 'reject'])->name('reject');
             Route::patch('/{user}/remove-approval', [\App\Http\Controllers\Admin\UserApprovalController::class, 'removeApproval'])->name('remove-approval');
             Route::delete('/{user}', [\App\Http\Controllers\Admin\UserApprovalController::class, 'delete'])->name('delete');
             Route::post('/bulk-approve', [\App\Http\Controllers\Admin\UserApprovalController::class, 'bulkApprove'])->name('bulk-approve');
             Route::post('/bulk-reject', [\App\Http\Controllers\Admin\UserApprovalController::class, 'bulkReject'])->name('bulk-reject');
             Route::post('/bulk-delete', [\App\Http\Controllers\Admin\UserApprovalController::class, 'bulkDelete'])->name('bulk-delete');
         });
     });

// Debug route for form submission (outside auth middleware)
Route::post('/debug-form', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Log::info('=== DEBUG FORM SUBMISSION ===');
    \Illuminate\Support\Facades\Log::info('Request method: ' . $request->method());
    \Illuminate\Support\Facades\Log::info('Content type: ' . $request->header('Content-Type'));
    \Illuminate\Support\Facades\Log::info('All input data:', $request->all());
    \Illuminate\Support\Facades\Log::info('Has file images: ' . ($request->hasFile('images') ? 'YES' : 'NO'));
    
    if ($request->hasFile('images')) {
        $files = $request->file('images');
        \Illuminate\Support\Facades\Log::info('Images count: ' . count($files));
        
        foreach ($files as $index => $file) {
            \Illuminate\Support\Facades\Log::info("Image $index:", [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => MimeTypeDetector::detect($file),
                'is_valid' => $file->isValid(),
                'error' => $file->getError()
            ]);
        }
    } else {
        \Illuminate\Support\Facades\Log::info('No images found in request');
        \Illuminate\Support\Facades\Log::info('Files in request:', array_keys($request->allFiles()));
    }
    
    return response()->json([
        'status' => 'success',
        'message' => 'Debug data logged',
        'has_images' => $request->hasFile('images'),
        'images_count' => $request->hasFile('images') ? count($request->file('images')) : 0
    ]);
})->withoutMiddleware(['auth', 'conditional.verified']);

    // Profile routes
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/logo/{type}', [ProfileController::class, 'uploadLogo'])->name('profile.logo');
    Route::post('/profile/signature', [ProfileController::class, 'uploadSignature'])->name('profile.signature');
    Route::delete('/profile/logo/{type}', [ProfileController::class, 'deleteLogo'])->name('profile.logo.delete');
    Route::delete('/profile/signature', [ProfileController::class, 'deleteSignature'])->name('profile.signature.delete');
    Route::delete('/profile', [ProfileController::class, 'delete'])->name('profile.delete');
    Route::put('/profile/social-media', [ProfileController::class, 'updateSocialMedia'])->name('profile.social-media.update');
    Route::delete('/profile/social-media/{platform}', [ProfileController::class, 'deleteSocialMedia'])->name('profile.social-media.delete');
    Route::post('/profile/upload-rodape', [ProfileController::class, 'uploadRodapeImage'])->name('profile.rodape.upload');
    Route::post('/profile/upload-qrcode', [ProfileController::class, 'uploadQrcodeImage'])->name('profile.qrcode.upload');
    Route::delete('/profile/rodape', [ProfileController::class, 'deleteRodapeImage'])->name('profile.rodape.delete');
    Route::delete('/profile/qrcode', [ProfileController::class, 'deleteQrcodeImage'])->name('profile.qrcode.delete');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    Route::post('/settings/toggle-registration', [SettingsController::class, 'toggleRegistration'])->name('settings.toggle-registration');

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
        Route::get('/credit-card-invoices/details', [TransactionController::class, 'getCreditCardInvoiceDetails']);
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
    Route::get('/orcamentos/{orcamento}/internal-view', [OrcamentoController::class, 'showInternal'])->name('orcamentos.internal-view');
    Route::patch('/orcamentos/{orcamento}/quitar', [OrcamentoController::class, 'quitar'])->name('orcamentos.quitar');
    Route::patch('/orcamentos/{orcamento}/update-status', [OrcamentoController::class, 'atualizarStatus'])->name('orcamentos.update-status');
    
    // Gerenciamento de Imagens dos Orçamentos
    Route::get('/orcamentos/{orcamento}/profile-images', [OrcamentoController::class, 'profileImages'])->name('orcamentos.profile-images');
    Route::post('/orcamentos/{orcamento}/upload-qrcode', [OrcamentoController::class, 'uploadQrCode'])->name('orcamentos.upload-qrcode');
    Route::post('/orcamentos/{orcamento}/upload-logo', [OrcamentoController::class, 'uploadLogo'])->name('orcamentos.upload-logo');
    Route::delete('/orcamentos/{orcamento}/qrcode', [OrcamentoController::class, 'deleteQrCode'])->name('orcamentos.delete-qrcode');
    Route::delete('/orcamentos/{orcamento}/logo', [OrcamentoController::class, 'deleteLogo'])->name('orcamentos.delete-logo');

    // Histórico do Projeto
    Route::prefix('orcamentos/{orcamento}/historico')->name('orcamentos.historico.')->group(function () {
        Route::get('/', [HistoricoController::class, 'index'])->name('index');
        Route::get('/create', [HistoricoController::class, 'create'])->name('create');
        Route::post('/', [HistoricoController::class, 'store'])->name('store');
        Route::put('/{historico}', [HistoricoController::class, 'update'])->name('update');
        Route::delete('/{historico}', [HistoricoController::class, 'destroy'])->name('destroy');
        Route::patch('/{historico}/toggle-status', [HistoricoController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/upload', [HistoricoController::class, 'upload'])->name('upload');
        Route::delete('/files/{file}', [HistoricoController::class, 'deleteFile'])->name('files.delete');
        Route::get('/api', [HistoricoController::class, 'api'])->name('api');
        Route::get('/files/{file}/download', [HistoricoController::class, 'download'])->name('files.download');
        Route::get('/{historico}/files', [HistoricoController::class, 'files'])->name('files');
    });

    // Clientes
    Route::resource('clientes', ClienteController::class);
    Route::post('/clientes/{cliente}/gerar-token-extrato', [ClienteController::class, 'gerarTokenExtrato'])->name('clientes.gerar-token-extrato');
    Route::get('/clientes/{cliente}/check-extract-status', [ClienteController::class, 'checkExtractStatus'])->name('clientes.check-extract-status');
    Route::post('/clientes/{cliente}/desativar-token-extrato', [ClienteController::class, 'desativarTokenExtrato'])->name('clientes.desativar-token-extrato');

    // Autores
    Route::resource('autores', AutorController::class)->parameters(['autores' => 'autor']);

    // Pagamentos
    Route::resource('pagamentos', PagamentoController::class);
    Route::post('/pagamentos/{pagamento}/gerar-recibo', [PagamentoController::class, 'gerarRecibo'])->name('pagamentos.gerar-recibo');

    // Modelos de Propostas
    Route::resource('modelos-propostas', ModeloPropostaController::class)->parameters(['modelos-propostas' => 'modeloProposta']);
    Route::post('modelos-propostas/{modeloProposta}/duplicate', [ModeloPropostaController::class, 'duplicate'])->name('modelos-propostas.duplicate');

    // Social Posts Module (Módulo de Redes Sociais)
    Route::prefix('social-posts')->name('social-posts.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SocialPostController::class, 'index'])->name('index');
        Route::get('/calendar', [\App\Http\Controllers\SocialPostController::class, 'calendar'])->name('calendar');
        Route::get('/create', [\App\Http\Controllers\SocialPostController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\SocialPostController::class, 'store'])->name('store');
        Route::get('/{socialPost}', [\App\Http\Controllers\SocialPostController::class, 'show'])->name('show');
        Route::get('/{socialPost}/edit', [\App\Http\Controllers\SocialPostController::class, 'edit'])->name('edit');
        Route::put('/{socialPost}', [\App\Http\Controllers\SocialPostController::class, 'update'])->name('update');
        Route::delete('/{socialPost}', [\App\Http\Controllers\SocialPostController::class, 'destroy'])->name('destroy');
        Route::get('/{socialPost}/generate-images', [\App\Http\Controllers\SocialPostController::class, 'generateImages'])->name('generate-images');
        Route::post('/{socialPost}/duplicate', [\App\Http\Controllers\SocialPostController::class, 'duplicate'])->name('duplicate');
        
        // Background Colors Routes
        Route::post('/background-colors', [\App\Http\Controllers\SocialPostController::class, 'saveBackgroundColor'])->name('background-colors.store');
        Route::post('/background-colors/set-default', [\App\Http\Controllers\SocialPostController::class, 'setDefaultColorFromPicker'])->name('background-colors.set-default-picker');
        Route::patch('/background-colors/{colorId}/default', [\App\Http\Controllers\SocialPostController::class, 'setDefaultColor'])->name('background-colors.set-default');
        Route::delete('/background-colors/{colorId}', [\App\Http\Controllers\SocialPostController::class, 'deleteBackgroundColor'])->name('background-colors.destroy');
    });

    // Kanban Module (Módulo Kanban)
    Route::prefix('kanban')->name('kanban.')->group(function () {
        Route::get('/', [\App\Http\Controllers\KanbanController::class, 'index'])->name('index');
        Route::get('/api', [\App\Http\Controllers\KanbanController::class, 'apiIndex'])->name('api.index');
        Route::get('/api/projetos', [\App\Http\Controllers\KanbanController::class, 'apiIndex'])->name('api.projetos.index');
        Route::post('/mover-projeto', [\App\Http\Controllers\KanbanController::class, 'moverProjeto'])->name('mover-projeto');
        Route::post('/api/projetos/mover', [\App\Http\Controllers\KanbanController::class, 'moverProjeto'])->name('api.projetos.mover');
        Route::post('/criar-projeto-automatico', [\App\Http\Controllers\KanbanController::class, 'criarProjetoAutomatico'])->name('criar-projeto-automatico');
        
        // Etapas Routes
        Route::get('/etapas', [\App\Http\Controllers\KanbanController::class, 'etapas'])->name('etapas.index');
        Route::post('/etapas', [\App\Http\Controllers\KanbanController::class, 'criarEtapa'])->name('etapas.store');
        Route::put('/etapas/{id}', [\App\Http\Controllers\KanbanController::class, 'atualizarEtapa'])->name('etapas.update');
        Route::delete('/etapas/{id}', [\App\Http\Controllers\KanbanController::class, 'excluirEtapa'])->name('etapas.destroy');
        Route::patch('/etapas/{etapa}/toggle-status', [\App\Http\Controllers\KanbanController::class, 'toggleEtapaStatus'])->name('etapas.toggle-status');
        Route::put('/api/etapas/{etapa}', [\App\Http\Controllers\KanbanController::class, 'atualizarEtapa'])->name('api.etapas.update');
        Route::post('/api/etapas', [\App\Http\Controllers\KanbanController::class, 'criarEtapa'])->name('api.etapas.store');
        Route::delete('/api/etapas/{etapa}', [\App\Http\Controllers\KanbanController::class, 'excluirEtapa'])->name('api.etapas.destroy');
    });

    // Portfolio Module (Módulo de Portfólio)
    Route::prefix('portfolio')->name('portfolio.')->group(function () {
        // Dashboard do portfólio
        Route::get('/dashboard', [PortfolioController::class, 'dashboard'])->name('dashboard');
        
        // Página principal do portfólio
        Route::get('/', [PortfolioController::class, 'dashboard'])->name('index');
        Route::get('/works-list', [PortfolioController::class, 'index'])->name('works.list');
        
        // Pipeline de orçamentos finalizados
        Route::get('/pipeline', [PortfolioController::class, 'pipeline'])->name('pipeline');
        
        // CRUD de categorias
        Route::resource('categories', PortfolioCategoryController::class)->except(['show']);
        Route::patch('/categories/{category}/toggle-status', [PortfolioCategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        Route::patch('/categories/update-order', [PortfolioCategoryController::class, 'updateOrder'])->name('categories.update-order');
        
        // CRUD de trabalhos
        Route::get('/works', [PortfolioController::class, 'worksIndex'])->name('works.index');
        Route::resource('works', PortfolioController::class)->except(['index', 'pipeline']);
        Route::post('/works/{work}/images/upload', [PortfolioController::class, 'uploadImages'])->name('works.images.upload');
        Route::delete('/works/images/{image}', [PortfolioController::class, 'deleteImage'])->name('works.images.delete');
        Route::patch('/works/images/{image}/set-cover', [PortfolioController::class, 'setCoverImage'])->name('works.images.set-cover');
        Route::patch('/works/images/update-order', [PortfolioController::class, 'updateImagesOrder'])->name('works.images.update-order');
    });

    // File Management Module (Módulo de Gestão de Arquivos)
    Route::prefix('files')->name('files.')->group(function () {
        // File management routes
        Route::get('/', [FileController::class, 'index'])->name('index');
        Route::get('/create', [FileController::class, 'create'])->name('create');
        Route::post('/', [FileController::class, 'store'])->name('store');
        Route::get('/{file}', [FileController::class, 'show'])->name('show');
        Route::get('/{file}/download', [FileController::class, 'download'])->name('download');
        Route::put('/{file}', [FileController::class, 'update'])->name('update');
        Route::delete('/{file}', [FileController::class, 'destroy'])->name('destroy');
        Route::get('/category/{category}', [FileController::class, 'getByCategory'])->name('by-category');
        Route::post('/upload-progress', [FileController::class, 'uploadProgress'])->name('upload-progress');
        
        // Shared links management
        Route::post('/{file}/share', [SharedLinkController::class, 'store'])->name('share');
        Route::get('/{file}/links', [SharedLinkController::class, 'getFileLinks'])->name('links');
        Route::delete('/shared-links/{sharedLink}', [SharedLinkController::class, 'destroy'])->name('shared-links.destroy');
        Route::get('/shared-links/{sharedLink}/access-logs', [SharedLinkController::class, 'getAccessLogs'])->name('shared-links.access-logs');
    });

    // File Categories Management
    Route::resource('file-categories', FileCategoryController::class)->except(['show']);
    Route::get('/file-categories/api', [FileCategoryController::class, 'api'])->name('file-categories.api');
   



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
        
        Route::get('/csrf-token', function () {
            return response()->json([
                'token' => csrf_token()
            ]);
        });
    });

});

// API Routes for Budget Module (outside auth middleware for AJAX calls)
Route::prefix('api/budget')->name('api.budget.')->group(function () {
    // Orçamentos API - Status update accessible for AJAX
    Route::patch('/orcamentos/{orcamento}/status', [OrcamentoController::class, 'atualizarStatus'])->name('orcamentos.status');
    
    // Protected API routes requiring authentication
    Route::middleware('auth')->group(function () {
        Route::patch('/orcamentos/{orcamento}/quitar', [OrcamentoController::class, 'quitar'])->name('orcamentos.quitar');
        Route::patch('/orcamentos/{orcamento}/aprovar', [OrcamentoController::class, 'aprovar'])->name('orcamentos.aprovar');
        Route::patch('/orcamentos/{orcamento}/rejeitar', [OrcamentoController::class, 'rejeitar'])->name('orcamentos.rejeitar');
        
        // Clientes API
        Route::get('/clientes/autocomplete', [ClienteController::class, 'autocomplete'])->name('clientes.autocomplete');
        Route::get('/clientes/search', [ClienteController::class, 'search'])->name('clientes.search');
        
        // Autores API
        Route::get('/autores/autocomplete', [AutorController::class, 'autocomplete'])->name('autores.autocomplete');
        Route::get('/autores/search', [AutorController::class, 'search'])->name('autores.search');
        
        // Modelos de Propostas API
        Route::get('/modelos-propostas/autocomplete', [ModeloPropostaController::class, 'autocomplete'])->name('modelos-propostas.autocomplete');
        Route::get('/modelos-propostas/{modeloProposta}/conteudo', [ModeloPropostaController::class, 'getConteudo'])->name('modelos-propostas.conteudo');
        
        // Upload de Arquivos para Orçamentos
        Route::post('/orcamentos/{orcamento}/files/upload', [OrcamentoFileController::class, 'upload'])->name('orcamentos.files.upload');
        Route::get('/orcamentos/{orcamento}/files', [OrcamentoFileController::class, 'getFiles'])->name('orcamentos.files.get');
        Route::delete('/orcamentos/files/{file}', [OrcamentoFileController::class, 'delete'])->name('orcamentos.files.delete');
        Route::get('/orcamentos/files/{file}/download', [OrcamentoFileController::class, 'download'])->name('orcamentos.files.download');
        Route::patch('/orcamentos/files/{file}/description', [OrcamentoFileController::class, 'updateDescription'])->name('orcamentos.files.description');
        
        // Portfolio API Routes (APIs internas do portfólio)
        Route::prefix('portfolio')->name('portfolio.')->group(function () {
            // API de categorias
            Route::get('/categories/api', [PortfolioCategoryController::class, 'api'])->name('categories.api');
            
            // API de trabalhos
            Route::get('/works/api', [PortfolioController::class, 'api'])->name('works.api');
            Route::get('/works/{work}/images', [PortfolioController::class, 'getImages'])->name('works.images.get');
            
            // API para pipeline
            Route::get('/pipeline/api', [PortfolioController::class, 'pipelineApi'])->name('pipeline.api');
            Route::post('/pipeline/{orcamento}/create-work', [PortfolioController::class, 'createWorkFromBudget'])->name('pipeline.create-work');
        });
    });
});

// Public Routes for Budget Module (Rotas Públicas)
Route::prefix('public')->name('public.')->group(function () {
    // Rotas públicas para orçamentos
    Route::get('/orcamento/{token}', [OrcamentoController::class, 'showPublic'])->name('orcamentos.public');
    Route::patch('/orcamento/{token}/aprovar', [OrcamentoController::class, 'aprovarPublico'])->name('orcamentos.public.aprovar');
    Route::patch('/orcamento/{token}/rejeitar', [OrcamentoController::class, 'rejeitarPublico'])->name('orcamentos.public.rejeitar');
    
    // Rotas públicas para recibos
    Route::get('/recibo/{token}', [PagamentoController::class, 'showReciboPublico'])->name('recibos.public');
    
    // Rotas públicas para extrato do cliente
    Route::get('/extrato/{cliente_id}/{token}', [ExtratoController::class, 'show'])->name('extrato.public');
    
    // Rotas públicas do portfólio
    Route::prefix('portfolio')->name('portfolio.public.')->group(function () {
        // Página principal do portfólio
        Route::get('/', [PortfolioApiController::class, 'index'])->name('index');
        
        // Página de categoria específica
        Route::get('/categoria/{category:slug}', [PortfolioApiController::class, 'category'])->name('category');
        
        // Página de trabalho específico
        Route::get('/trabalho/{work:slug}', [PortfolioApiController::class, 'workDetail'])->name('work');
        
        // Página de autor específico
        Route::get('/autor/{author:slug}', [PortfolioApiController::class, 'authorPortfolio'])->name('author');
    });
    
    // Rota de contato
    Route::get('/contato', function () {
        return view('contact');
    })->name('contact');
    
    // Public shared file access routes
    Route::get('/shared/{token}', [SharedLinkController::class, 'show'])->name('shared.show');
    Route::match(['GET', 'POST'], '/shared/{token}/download', [SharedLinkController::class, 'download'])->name('shared.download');
    Route::get('/shared/{token}/preview', [SharedLinkController::class, 'preview'])->name('shared.preview');
});

// File upload routes moved to RouteServiceProvider (without any middleware)

// Debug routes (only in development) - REMOVIDO
