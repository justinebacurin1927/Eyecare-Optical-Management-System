<?php

namespace App\Services;

use App\Models\Product;
use App\Models\SaleItem;
use App\Models\SaleTransaction;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function createTransaction(array $validated): SaleTransaction
    {
        return DB::transaction(function () use ($validated) {
            $transaction = SaleTransaction::create([
                'patient_id' => $validated['patient_id'],
                'transaction_date' => $validated['transaction_date'],
                'total_amount' => $validated['total_amount'],
                'discount_amount' => $validated['discount_amount'] ?? 0,
                'payment_status' => $validated['payment_status'],
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->quantity < $item['quantity']) {
                    throw new \RuntimeException("Insufficient stock for {$product->name}");
                }

                SaleItem::create([
                    'sale_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity_sold' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);

                $product->decrement('quantity', $item['quantity']);
            }

            return $transaction;
        });
    }

    public function deleteTransaction(SaleTransaction $sale): void
    {
        DB::transaction(function () use ($sale) {
            $sale->load('saleItems.product');

            foreach ($sale->saleItems as $item) {
                $item->product->increment('quantity', $item->quantity_sold);
            }

            $sale->delete();
        });
    }

    public function checkProductAvailability(int $id): Product
    {
        return Product::with('category')->findOrFail($id);
    }
}
