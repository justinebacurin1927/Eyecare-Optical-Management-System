<?php

namespace Tests\Feature\Controllers;

use App\Models\Patient;
use App\Models\Product;
use App\Models\SaleTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaleTransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'Admin',
            'status' => true,
        ]);
    }

    public function test_admin_can_view_sales(): void
    {
        $response = $this->actingAs($this->admin)->get(route('sales.index'));

        $response->assertStatus(200);
        $response->assertViewIs('sales.index');
    }

    public function test_admin_can_create_sale_transaction(): void
    {
        $patient = Patient::factory()->create();
        $product = Product::factory()->create(['quantity' => 50]);

        $response = $this->actingAs($this->admin)->post(route('sales.store'), [
            'patient_id' => $patient->id,
            'transaction_date' => now()->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'unit_price' => 500.00,
                    'total_price' => 1000.00,
                ],
            ],
            'total_amount' => 1000.00,
            'discount_amount' => 0,
            'payment_status' => 'Paid',
        ]);

        $response->assertRedirect(route('sales.index'));
        $this->assertDatabaseHas('sale_transactions', ['total_amount' => 1000.00]);
        $this->assertDatabaseHas('sale_items', ['quantity_sold' => 2]);
    }

    public function test_sale_transaction_checks_stock(): void
    {
        $patient = Patient::factory()->create();
        $product = Product::factory()->create(['quantity' => 1]);

        $this->actingAs($this->admin)->post(route('sales.store'), [
            'patient_id' => $patient->id,
            'transaction_date' => now()->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                    'unit_price' => 500.00,
                    'total_price' => 2500.00,
                ],
            ],
            'total_amount' => 2500.00,
            'discount_amount' => 0,
            'payment_status' => 'Paid',
        ]);

        $this->assertDatabaseMissing('sale_transactions', ['total_amount' => 2500.00]);
    }

    public function test_sale_transaction_decrements_stock(): void
    {
        $patient = Patient::factory()->create();
        $product = Product::factory()->create(['quantity' => 10]);

        $this->actingAs($this->admin)->post(route('sales.store'), [
            'patient_id' => $patient->id,
            'transaction_date' => now()->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                    'unit_price' => 500.00,
                    'total_price' => 1500.00,
                ],
            ],
            'total_amount' => 1500.00,
            'discount_amount' => 0,
            'payment_status' => 'Paid',
        ]);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 7]);
    }

    public function test_deleting_sale_restores_stock(): void
    {
        $patient = Patient::factory()->create();
        $product = Product::factory()->create(['quantity' => 10]);

        $this->actingAs($this->admin)->post(route('sales.store'), [
            'patient_id' => $patient->id,
            'transaction_date' => now()->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'unit_price' => 500.00,
                    'total_price' => 1000.00,
                ],
            ],
            'total_amount' => 1000.00,
            'discount_amount' => 0,
            'payment_status' => 'Paid',
        ]);

        $sale = SaleTransaction::first();
        $this->actingAs($this->admin)->delete(route('sales.destroy', $sale));

        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 10]);
    }

    public function test_admin_can_update_sale_transaction(): void
    {
        $patient = Patient::factory()->create();
        $sale = SaleTransaction::factory()->create(['patient_id' => $patient->id]);

        $response = $this->actingAs($this->admin)->put(route('sales.update', $sale), [
            'patient_id' => $patient->id,
            'transaction_date' => $sale->transaction_date->format('Y-m-d H:i:s'),
            'total_amount' => 2000.00,
            'discount_amount' => 100.00,
            'payment_status' => 'Paid',
        ]);

        $response->assertRedirect(route('sales.index'));
        $this->assertDatabaseHas('sale_transactions', ['total_amount' => 2000.00]);
    }

    public function test_check_product_returns_json(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('products.check', $product));

        $response->assertStatus(200);
        $response->assertJson(['id' => $product->id]);
    }

    public function test_validation_requires_patient_and_items(): void
    {
        $response = $this->actingAs($this->admin)->post(route('sales.store'), [
            'patient_id' => '',
            'items' => [],
        ]);

        $response->assertSessionHasErrors(['patient_id', 'items']);
    }
}
