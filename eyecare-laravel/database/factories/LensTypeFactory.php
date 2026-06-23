<?php

namespace Database\Factories;

use App\Models\LensType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LensTypeFactory extends Factory
{
    protected $model = LensType::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'material' => fake()->randomElement(['Plastic', 'Polycarbonate', 'Glass', 'Trivex']),
            'coating' => fake()->randomElement(['Anti-Reflective', 'Anti-Scratch', 'Blue Light Filter', 'Mirror', 'None']),
        ];
    }
}
