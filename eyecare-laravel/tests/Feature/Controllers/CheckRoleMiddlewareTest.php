<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckRoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->create(['role' => 'Admin', 'status' => true]);

        $response = $this->actingAs($admin)->get(route('users.index'));

        $response->assertStatus(200);
    }

    public function test_staff_cannot_access_admin_routes(): void
    {
        $staff = User::factory()->create(['role' => 'Staff', 'status' => true]);

        $response = $this->actingAs($staff)->get(route('users.index'));

        $response->assertStatus(403);
    }

    public function test_doctor_cannot_access_admin_routes(): void
    {
        $doctor = User::factory()->create(['role' => 'Doctor', 'status' => true]);

        $response = $this->actingAs($doctor)->get(route('users.index'));

        $response->assertStatus(403);
    }

    public function test_doctor_cannot_access_staff_routes(): void
    {
        $doctor = User::factory()->create(['role' => 'Doctor', 'status' => true]);

        $response = $this->actingAs($doctor)->get(route('categories.index'));

        $response->assertStatus(403);
    }

    public function test_staff_can_access_staff_routes(): void
    {
        $staff = User::factory()->create(['role' => 'Staff', 'status' => true]);

        $response = $this->actingAs($staff)->get(route('categories.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_access_staff_routes(): void
    {
        $admin = User::factory()->create(['role' => 'Admin', 'status' => true]);

        $response = $this->actingAs($admin)->get(route('categories.index'));

        $response->assertStatus(200);
    }
}
