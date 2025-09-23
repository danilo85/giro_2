<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Observers\TransactionObserver;
use App\Models\Orcamento;
use App\Observers\OrcamentoObserver;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar Carbon para português do Brasil
        Carbon::setLocale('pt_BR');
        setlocale(LC_TIME, 'pt_BR.UTF-8', 'pt_BR', 'portuguese');
        
        // Registrar observers
        Transaction::observe(TransactionObserver::class);
        Orcamento::observe(OrcamentoObserver::class);
    }
}
