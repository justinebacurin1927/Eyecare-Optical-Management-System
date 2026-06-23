<?php

namespace Tests\Feature\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $staff;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'Admin',
            'status' => true,
        ]);

        $this->staff = User::factory()->create([
            'role' => 'Staff',
            'status' => true,
        ]);
    }

    public function test_admin_can_view_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('categories.index');
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)->post(route('categories.store'), [
            'name' => 'New Category',
            'description' => 'Test description',
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'New Category']);
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('categories.update', $category), [
            'name' => 'Updated Category',
            'description' => 'Updated',
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Updated Category']);
    }

    public function test_admin_can_delete_category_without_products(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_cannot_delete_category_with_products(): void
    {
        $category = Category::factory()->hasProducts(1)->create();

        $response = $this->actingAs($this->admin)->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_staff_can_view_categories(): void
    {
        $response = $this->actingAs($this->staff)->get(route('categories.index'));

        $response->assertStatus(200);
    }

    public function test_staff_can_create_category(): void
    {
        $response = $this->actingAs($this->staff)->post(route('categories.store'), [
            'name' => 'Staff Category',
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Staff Category']);
    }

    public function test_validation_requires_name(): void
    {
        $response = $this->actingAs($this->admin)->post(route('categories.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_validation_requires_unique_name(): void
    {
        Category::factory()->create(['name' => 'Existing']);

        $response = $this->actingAs($this->admin)->post(route('categories.store'), [
            'name' => 'Existing',
        ]);

        $response->assertSessionHasErrors('name');
    }
}
