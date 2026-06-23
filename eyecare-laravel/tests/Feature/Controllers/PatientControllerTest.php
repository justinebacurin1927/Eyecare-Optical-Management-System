<?php

namespace Tests\Feature\Controllers;

use App\Models\Frame;
use App\Models\LensType;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientControllerTest extends TestCase
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

    public function test_admin_can_view_patients(): void
    {
        Patient::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('patients.index'));

        $response->assertStatus(200);
        $response->assertViewIs('patients.index');
    }

    public function test_admin_can_create_patient_with_prescription(): void
    {
        $frame = Frame::factory()->create();
        $lensType = LensType::factory()->create();

        $response = $this->actingAs($this->admin)->post(route('patients.store'), [
            'first_name' => 'Juan',
            'last_name' => 'Cruz',
            'birthdate' => '1990-01-01',
            'gender' => 'Male',
            'phone_number' => '09171234567',
            'address' => '123 Test St.',
            'sphere' => '-1.25',
            'cylinder' => '-0.50',
            'axis' => '90',
            'addition' => '1.50',
            'pd' => '62',
            'frame_id' => $frame->id,
            'lens_type_id' => $lensType->id,
            'tint' => 'None',
        ]);

        $response->assertRedirect(route('patients.index'));
        $this->assertDatabaseHas('patients', ['first_name' => 'Juan']);
        $this->assertDatabaseHas('prescriptions', ['sphere' => '-1.25']);
    }

    public function test_admin_can_update_patient(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->actingAs($this->admin)->put(route('patients.update', $patient), [
            'first_name' => 'Updated',
            'last_name' => $patient->last_name,
            'birthdate' => '1990-01-01',
            'gender' => 'Male',
        ]);

        $response->assertRedirect(route('patients.index'));
        $this->assertDatabaseHas('patients', ['first_name' => 'Updated']);
    }

    public function test_admin_can_delete_patient(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('patients.destroy', $patient));

        $response->assertRedirect(route('patients.index'));
        $this->assertDatabaseMissing('patients', ['id' => $patient->id]);
    }

    public function test_admin_can_view_single_patient(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->actingAs($this->admin)->get(route('patients.show', $patient));

        $response->assertStatus(200);
        $response->assertViewIs('patients.show');
    }

    public function test_admin_can_search_patients(): void
    {
        Patient::factory()->create(['first_name' => 'Juan']);
        Patient::factory()->create(['first_name' => 'Pedro']);

        $response = $this->actingAs($this->admin)->get(route('patients.search', ['q' => 'Juan']));

        $response->assertStatus(200);
        $response->assertViewHas('patients');
    }

    public function test_validation_requires_name_and_birthdate(): void
    {
        $response = $this->actingAs($this->admin)->post(route('patients.store'), [
            'first_name' => '',
            'last_name' => '',
            'birthdate' => '',
            'gender' => '',
        ]);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'birthdate', 'gender']);
    }
}
