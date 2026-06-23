<?php

namespace App\Events;

use App\Models\SaleTransaction;
use Illuminate\Foundation\Events\Dispatchable;

class SaleCompleted
{
    use Dispatchable;

    public function __construct(
        public SaleTransaction $sale
    ) {}
}
