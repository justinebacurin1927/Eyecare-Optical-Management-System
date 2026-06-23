<?php

namespace Tests\Feature\Controllers;

use App\Models\LensType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LensTypeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_lens_type(): void
    {
        $admin = User::factory()->create(['role' => 'Admin', 'status' => true]);

        $response = $this->actingAs($admin)->post(route('lens-types.store'), [
            'name' => 'New Lens Type',
            'material' => 'Plastic',
            'coating' => 'Anti-Reflective',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('lens_types', ['name' => 'New Lens Type']);
    }

    public function test_admin_can_delete_lens_type(): void
    {
        $admin = User::factory()->create(['role' => 'Admin', 'status' => true]);
        $lensType = LensType::factory()->create();

        $response = $this->actingAs($admin)->delete(route('lens-types.destroy', $lensType));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('lens_types', ['id' => $lensType->id]);
    }

    public function test_validation_requires_lens_type_name(): void
    {
        $admin = User::factory()->create(['role' => 'Admin', 'status' => true]);

        $response = $this->actingAs($admin)->post(route('lens-types.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }
}
