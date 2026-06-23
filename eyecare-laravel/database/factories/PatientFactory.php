<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->optional(0.5)->lastName(),
            'last_name' => fake()->lastName(),
            'birthdate' => fake()->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'phone_number' => fake()->phoneNumber(),
            'address' => fake()->address(),
        ];
    }
}
