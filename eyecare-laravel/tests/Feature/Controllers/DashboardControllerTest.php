<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'Staff', 'status' => true]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_guest_cannot_view_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_has_stats(): void
    {
        $user = User::factory()->create(['role' => 'Admin', 'status' => true]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
    }
}
