<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
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

    public function test_admin_can_view_users(): void
    {
        $response = $this->actingAs($this->admin)->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('users.index');
    }

    public function test_admin_can_create_user(): void
    {
        $response = $this->actingAs($this->admin)->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'new@test.test',
            'password' => 'password123',
            'role' => 'Staff',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'new@test.test']);
    }

    public function test_admin_can_update_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('users.update', $user), [
            'name' => 'Updated Name',
            'email' => 'updated@test.test',
            'role' => 'Staff',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['name' => 'Updated Name']);
    }

    public function test_admin_can_toggle_user_status(): void
    {
        $user = User::factory()->create(['status' => true]);

        $response = $this->actingAs($this->admin)->patch(route('users.toggle-status', $user));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $user->id, 'status' => false]);
    }

    public function test_admin_can_delete_user(): void
    {
        $user = User::factory()->create(['role' => 'Staff']);

        $response = $this->actingAs($this->admin)->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_last_admin(): void
    {
        $response = $this->actingAs($this->admin)->delete(route('users.destroy', $this->admin));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_staff_cannot_access_users(): void
    {
        $staff = User::factory()->create(['role' => 'Staff', 'status' => true]);

        $response = $this->actingAs($staff)->get(route('users.index'));

        $response->assertStatus(403);
    }

    public function test_validation_requires_name_email_and_role(): void
    {
        $response = $this->actingAs($this->admin)->post(route('users.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
            'role' => 'InvalidRole',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'role']);
    }
}
