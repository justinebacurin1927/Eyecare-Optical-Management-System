<?php

namespace App\Console\Commands;

use App\Models\Frame;
use App\Models\LensType;
use App\Models\Patient;
use App\Models\Prescription;
use Illuminate\Console\Command;

class ImportPatientsCsv extends Command
{
    protected $signature = 'import:patients-csv {file? : Path to the CSV file}';
    protected $description = 'Import patients from a CSV file';

    public function handle(): int
    {
        $csvPath = $this->argument('file') ?? database_path('seeders/data/patients.csv');

        if (!file_exists($csvPath)) {
            $this->error("File not found: {$csvPath}");
            return Command::FAILURE;
        }

        $handle = fopen($csvPath, 'r');
        $headers = fgetcsv($handle);

        $rowCount = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($headers, $row);

            $patient = Patient::create([
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'] ?: null,
                'last_name' => $data['last_name'],
                'birthdate' => $data['birthdate'],
                'gender' => $data['gender'],
                'phone_number' => $data['phone_number'] ?: null,
                'address' => $data['address'] ?: null,
            ]);

            $frame = Frame::find($data['frame_id']);
            $lensType = LensType::find($data['lens_type_id']);

            Prescription::create([
                'patient_id' => $patient->id,
                'sphere' => $data['sphere'] ?: null,
                'cylinder' => $data['cylinder'] ?: null,
                'axis' => $data['axis'] ?: null,
                'addition' => $data['addition'] ?: null,
                'pd' => $data['pd'] ?: null,
                'frame_id' => $frame?->id,
                'lens_type_id' => $lensType?->id,
                'tint' => $data['tint'] ?: null,
            ]);

            $rowCount++;
        }

        fclose($handle);

        $this->info("Imported {$rowCount} patients from CSV.");

        return Command::SUCCESS;
    }
}
