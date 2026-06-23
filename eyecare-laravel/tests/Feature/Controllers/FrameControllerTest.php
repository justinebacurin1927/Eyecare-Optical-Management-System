<?php

namespace Tests\Feature\Controllers;

use App\Models\Frame;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrameControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_frame(): void
    {
        $admin = User::factory()->create(['role' => 'Admin', 'status' => true]);

        $response = $this->actingAs($admin)->post(route('frames.store'), [
            'name' => 'New Frame',
            'brand' => 'Test Brand',
            'material' => 'Acetate',
            'style' => 'Full Rim',
            'size' => 'Medium',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('frames', ['name' => 'New Frame']);
    }

    public function test_admin_can_delete_frame(): void
    {
        $admin = User::factory()->create(['role' => 'Admin', 'status' => true]);
        $frame = Frame::factory()->create();

        $response = $this->actingAs($admin)->delete(route('frames.destroy', $frame));

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('frames', ['id' => $frame->id]);
    }

    public function test_validation_requires_frame_name(): void
    {
        $admin = User::factory()->create(['role' => 'Admin', 'status' => true]);

        $response = $this->actingAs($admin)->post(route('frames.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }
}
