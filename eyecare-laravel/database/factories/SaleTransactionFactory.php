<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\SaleTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleTransactionFactory extends Factory
{
    protected $model = SaleTransaction::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'transaction_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'total_amount' => fake()->randomFloat(2, 500, 10000),
            'discount_amount' => fake()->randomFloat(2, 0, 500),
            'payment_status' => fake()->randomElement(['Paid', 'Paid', 'Paid', 'Pending']),
        ];
    }
}
