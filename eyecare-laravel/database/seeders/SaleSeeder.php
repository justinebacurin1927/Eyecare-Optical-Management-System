<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\Product;
use App\Models\SaleItem;
use App\Models\SaleTransaction;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $products = Product::all();

        $salesData = [
            ['months_ago' => 11, 'day' => 5,  'status' => 'Paid',    'items' => [[0, 2], [6, 1]],              'discount' => 0],
            ['months_ago' => 11, 'day' => 18, 'status' => 'Paid',    'items' => [[1, 1], [5, 2]],              'discount' => 100],
            ['months_ago' => 10, 'day' => 3,  'status' => 'Paid',    'items' => [[2, 1]],                      'discount' => 0],
            ['months_ago' => 10, 'day' => 15, 'status' => 'Paid',    'items' => [[3, 1], [7, 3]],              'discount' => 200],
            ['months_ago' => 10, 'day' => 28, 'status' => 'Paid',    'items' => [[4, 2], [0, 1]],              'discount' => 50],
            ['months_ago' => 9,  'day' => 2,  'status' => 'Paid',    'items' => [[5, 1]],                      'discount' => 0],
            ['months_ago' => 9,  'day' => 14, 'status' => 'Paid',    'items' => [[6, 2], [1, 1], [2, 1]],      'discount' => 150],
            ['months_ago' => 9,  'day' => 27, 'status' => 'Paid',    'items' => [[7, 1], [3, 2]],              'discount' => 0],
            ['months_ago' => 8,  'day' => 1,  'status' => 'Paid',    'items' => [[0, 1], [4, 1]],              'discount' => 0],
            ['months_ago' => 8,  'day' => 12, 'status' => 'Pending', 'items' => [[1, 2]],                      'discount' => 0],
            ['months_ago' => 8,  'day' => 26, 'status' => 'Paid',    'items' => [[2, 1], [5, 3]],              'discount' => 300],
            ['months_ago' => 7,  'day' => 4,  'status' => 'Paid',    'items' => [[3, 1]],                      'discount' => 0],
            ['months_ago' => 7,  'day' => 19, 'status' => 'Paid',    'items' => [[4, 3], [6, 1], [0, 2]],      'discount' => 250],
            ['months_ago' => 7,  'day' => 30, 'status' => 'Paid',    'items' => [[5, 2], [7, 1]],              'discount' => 0],
            ['months_ago' => 6,  'day' => 8,  'status' => 'Paid',    'items' => [[6, 1]],                      'discount' => 0],
            ['months_ago' => 6,  'day' => 20, 'status' => 'Refunded','items' => [[0, 1], [1, 1]],              'discount' => 0],
            ['months_ago' => 6,  'day' => 25, 'status' => 'Paid',    'items' => [[2, 2], [3, 1], [4, 1]],      'discount' => 180],
            ['months_ago' => 5,  'day' => 7,  'status' => 'Paid',    'items' => [[5, 1], [7, 2]],              'discount' => 0],
            ['months_ago' => 5,  'day' => 16, 'status' => 'Paid',    'items' => [[0, 2], [6, 1]],              'discount' => 75],
            ['months_ago' => 5,  'day' => 29, 'status' => 'Pending', 'items' => [[1, 1], [2, 1], [3, 1]],      'discount' => 0],
            ['months_ago' => 4,  'day' => 3,  'status' => 'Paid',    'items' => [[4, 1]],                      'discount' => 0],
            ['months_ago' => 4,  'day' => 11, 'status' => 'Paid',    'items' => [[5, 2], [0, 1]],              'discount' => 120],
            ['months_ago' => 4,  'day' => 22, 'status' => 'Paid',    'items' => [[6, 1], [7, 1], [1, 2]],      'discount' => 0],
            ['months_ago' => 3,  'day' => 5,  'status' => 'Paid',    'items' => [[2, 1]],                      'discount' => 0],
            ['months_ago' => 3,  'day' => 14, 'status' => 'Paid',    'items' => [[3, 2], [4, 2]],              'discount' => 400],
            ['months_ago' => 3,  'day' => 28, 'status' => 'Paid',    'items' => [[5, 1], [0, 1], [6, 1]],      'discount' => 90],
            ['months_ago' => 2,  'day' => 2,  'status' => 'Paid',    'items' => [[7, 1]],                      'discount' => 0],
            ['months_ago' => 2,  'day' => 10, 'status' => 'Refunded','items' => [[1, 1]],                      'discount' => 0],
            ['months_ago' => 2,  'day' => 21, 'status' => 'Paid',    'items' => [[2, 1], [3, 1], [4, 1]],      'discount' => 200],
            ['months_ago' => 1,  'day' => 6,  'status' => 'Paid',    'items' => [[5, 1], [6, 2]],              'discount' => 0],
            ['months_ago' => 1,  'day' => 15, 'status' => 'Paid',    'items' => [[0, 1]],                      'discount' => 0],
            ['months_ago' => 1,  'day' => 28, 'status' => 'Pending', 'items' => [[7, 1], [1, 1], [2, 1]],      'discount' => 0],
            ['months_ago' => 0,  'day' => 3,  'status' => 'Paid',    'items' => [[3, 1], [4, 2]],              'discount' => 150],
            ['months_ago' => 0,  'day' => 8,  'status' => 'Paid',    'items' => [[5, 1]],                      'discount' => 0],
            ['months_ago' => 0,  'day' => 12, 'status' => 'Paid',    'items' => [[0, 2], [6, 1], [7, 1]],      'discount' => 0],
            ['months_ago' => 0,  'day' => 20, 'status' => 'Paid',    'items' => [[1, 1], [2, 1]],              'discount' => 80],
        ];

        foreach ($salesData as $saleDef) {
            $patient = $patients->random();
            $date = now()->subMonths($saleDef['months_ago'])->subDays(rand(0, 3));
            $date = $date->setDay(min($saleDef['day'], $date->daysInMonth));

            $totalAmount = 0;
            $computedItems = [];

            foreach ($saleDef['items'] as $itemDef) {
                $product = $products[$itemDef[0]];
                $qty = $itemDef[1];
                $unitPrice = $product->discounted_price ?? $product->selling_price;
                $lineTotal = $unitPrice * $qty;
                $totalAmount += $lineTotal;

                $computedItems[] = [
                    'product_id' => $product->id,
                    'quantity_sold' => $qty,
                    'unit_price' => $unitPrice,
                    'total_price' => $lineTotal,
                ];
            }

            $discount = min($saleDef['discount'], $totalAmount);

            $sale = SaleTransaction::create([
                'patient_id' => $patient->id,
                'transaction_date' => $date,
                'total_amount' => $totalAmount - $discount,
                'discount_amount' => $discount,
                'payment_status' => $saleDef['status'],
            ]);

            foreach ($computedItems as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity_sold' => $item['quantity_sold'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);
            }
        }
    }
}
