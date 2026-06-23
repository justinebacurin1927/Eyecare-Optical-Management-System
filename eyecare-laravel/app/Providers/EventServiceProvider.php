<?php

namespace App\Providers;

use App\Events\SaleCompleted;
use App\Listeners\ProcessSaleInventory;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SaleCompleted::class => [
            ProcessSaleInventory::class,
        ],
    ];

    public function boot(): void
    {
        //
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
