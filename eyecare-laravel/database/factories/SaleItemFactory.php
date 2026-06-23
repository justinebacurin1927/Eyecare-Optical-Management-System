<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\SaleItem;
use App\Models\SaleTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleItemFactory extends Factory
{
    protected $model = SaleItem::class;

    public function definition(): array
    {
        return [
            'sale_id' => SaleTransaction::factory(),
            'product_id' => Product::factory(),
            'quantity_sold' => fake()->numberBetween(1, 3),
            'unit_price' => fake()->randomFloat(2, 100, 3000),
            'total_price' => fake()->randomFloat(2, 100, 5000),
        ];
    }
}
