<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CreditCardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\Financial\FileUploadController;

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

// Note: Other Financial API routes moved to routes/web.php to avoid middleware conflicts
