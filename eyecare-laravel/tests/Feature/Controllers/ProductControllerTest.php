<?php

namespace Tests\Feature\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
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

    public function test_admin_can_view_products(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('products.index');
    }

    public function test_admin_can_create_product(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => 'New Product',
            'category_id' => $category->id,
            'quantity' => 10,
            'selling_price' => 500.00,
            'reorder_level' => 5,
            'reorder_quantity' => 10,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'New Product']);
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('products.update', $product), [
            'name' => 'Updated Product',
            'category_id' => $product->category_id,
            'quantity' => 20,
            'selling_price' => 600.00,
            'reorder_level' => 5,
            'reorder_quantity' => 10,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'Updated Product']);
    }

    public function test_admin_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_validation_requires_name_and_price(): void
    {
        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => '',
            'category_id' => '',
            'quantity' => -1,
            'selling_price' => 'abc',
        ]);

        $response->assertSessionHasErrors(['name', 'category_id', 'quantity', 'selling_price']);
    }
}
