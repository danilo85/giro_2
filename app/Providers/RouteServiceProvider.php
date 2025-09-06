<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
                
            // Test route without any middleware
            Route::group([], function () {
                Route::post('/test-upload', function () {
                    return response()->json(['message' => 'Test route working', 'status' => 'success']);
                });
                Route::post('/upload/files', [\App\Http\Controllers\Financial\FileUploadController::class, 'upload'])->name('upload.files');
                Route::get('/upload/files/transaction/{transactionId}', [\App\Http\Controllers\Financial\FileUploadController::class, 'getFiles'])->name('upload.files.transaction');
                Route::delete('/upload/files/{fileId}', [\App\Http\Controllers\Financial\FileUploadController::class, 'delete'])->name('upload.files.delete');
                Route::get('/upload/files/{fileId}/download', [\App\Http\Controllers\Financial\FileUploadController::class, 'download'])->name('upload.files.download');
                
                // Temporary file upload routes (without auth middleware for create mode)
                Route::post('/api/financial/files/upload-temp', [\App\Http\Controllers\Financial\FileUploadController::class, 'uploadTemp'])->name('api.financial.files.upload-temp');
                Route::get('/api/financial/files/temp/{tempId}', [\App\Http\Controllers\Financial\FileUploadController::class, 'getTempFiles'])->name('api.financial.files.temp');
                Route::post('/api/financial/files/move-temp-to-transaction', [\App\Http\Controllers\Financial\FileUploadController::class, 'moveTempFilesToTransaction'])->name('api.financial.files.move-temp');
                Route::delete('/api/financial/files/temp/{fileId}', [\App\Http\Controllers\Financial\FileUploadController::class, 'deleteTempFile'])->name('api.financial.files.delete-temp');
            });
        });
    }
}
