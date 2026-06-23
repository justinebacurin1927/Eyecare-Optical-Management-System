<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'category_id' => Category::factory(),
            'quantity' => fake()->numberBetween(10, 100),
            'selling_price' => fake()->randomFloat(2, 100, 5000),
            'discounted_price' => fake()->optional(0.3)->randomFloat(2, 50, 4000),
            'reorder_level' => fake()->numberBetween(5, 20),
            'reorder_quantity' => fake()->numberBetween(10, 50),
        ];
    }
}
