<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Patient;
use App\Models\Product;
use App\Models\SaleTransaction;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\PatientPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SaleTransactionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Product::class => ProductPolicy::class,
        Patient::class => PatientPolicy::class,
        SaleTransaction::class => SaleTransactionPolicy::class,
        User::class => UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
