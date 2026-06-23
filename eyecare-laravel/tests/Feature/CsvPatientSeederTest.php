<?php

namespace Tests\Feature;

use App\Models\Frame;
use App\Models\LensType;
use Database\Seeders\CsvPatientSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CsvPatientSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_csv_seeder_imports_patients(): void
    {
        Frame::factory()->count(5)->create();
        LensType::factory()->count(3)->create();

        $this->seed(CsvPatientSeeder::class);

        $this->assertDatabaseCount('patients', 103);
        $this->assertDatabaseCount('prescriptions', 103);
    }
}
