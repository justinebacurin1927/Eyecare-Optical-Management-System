<?php

namespace Database\Factories;

use App\Models\Frame;
use App\Models\LensType;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Database\Eloquent\Factories\Factory;

class PrescriptionFactory extends Factory
{
    protected $model = Prescription::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'sphere' => fake()->randomFloat(2, -6, 2),
            'cylinder' => fake()->randomFloat(2, -3, 0),
            'axis' => (string) fake()->numberBetween(0, 180),
            'addition' => fake()->randomFloat(2, 0, 3),
            'pd' => (string) fake()->numberBetween(58, 70),
            'frame_id' => Frame::factory(),
            'lens_type_id' => LensType::factory(),
            'tint' => fake()->randomElement(['None', 'Brown', 'Grey', 'Green']),
        ];
    }
}
