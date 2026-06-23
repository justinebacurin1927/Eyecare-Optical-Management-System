<?php

namespace Database\Factories;

use App\Models\Frame;
use Illuminate\Database\Eloquent\Factories\Factory;

class FrameFactory extends Factory
{
    protected $model = Frame::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'brand' => fake()->company(),
            'material' => fake()->randomElement(['Acetate', 'Metal', 'Titanium', 'Plastic', 'Stainless Steel']),
            'style' => fake()->randomElement(['Full Rim', 'Half Rim', 'Rimless']),
            'size' => fake()->randomElement(['Small', 'Medium', 'Large']),
        ];
    }
}
