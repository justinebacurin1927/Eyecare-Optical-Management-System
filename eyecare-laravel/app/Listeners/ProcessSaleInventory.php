<?php

namespace App\Listeners;

use App\Events\SaleCompleted;

class ProcessSaleInventory
{
    public function handle(SaleCompleted $event): void
    {
        $event->sale->load('saleItems.product');

        foreach ($event->sale->saleItems as $item) {
            $product = $item->product;
            $product->decrement('quantity', $item->quantity_sold);
        }
    }
}
