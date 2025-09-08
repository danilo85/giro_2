<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\CreditCard;
use App\Policies\CreditCardPolicy;
use App\Models\Category;
use App\Policies\CategoryPolicy;
use App\Models\Transaction;
use App\Policies\TransactionPolicy;
use App\Models\Autor;
use App\Policies\AuthorPolicy;
use App\Models\ModeloProposta;
use App\Policies\ModeloPropostaPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        CreditCard::class => CreditCardPolicy::class,
        Category::class => CategoryPolicy::class,
        Transaction::class => TransactionPolicy::class,
        Autor::class => AuthorPolicy::class,
        ModeloProposta::class => ModeloPropostaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
