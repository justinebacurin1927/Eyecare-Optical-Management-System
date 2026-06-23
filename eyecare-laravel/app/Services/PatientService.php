<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\Prescription;

class PatientService
{
    public function createPatientWithPrescription(array $patientData, array $prescriptionData): Patient
    {
        $patient = Patient::create([
            'first_name' => $patientData['first_name'],
            'middle_name' => $patientData['middle_name'] ?? null,
            'last_name' => $patientData['last_name'],
            'birthdate' => $patientData['birthdate'],
            'gender' => $patientData['gender'],
            'phone_number' => $patientData['phone_number'] ?? null,
            'address' => $patientData['address'] ?? null,
        ]);

        Prescription::create([
            'patient_id' => $patient->id,
            'sphere' => $prescriptionData['sphere'] ?? null,
            'cylinder' => $prescriptionData['cylinder'] ?? null,
            'axis' => $prescriptionData['axis'] ?? null,
            'addition' => $prescriptionData['addition'] ?? null,
            'pd' => $prescriptionData['pd'] ?? null,
            'frame_id' => $prescriptionData['frame_id'] ?? null,
            'lens_type_id' => $prescriptionData['lens_type_id'] ?? null,
            'tint' => $prescriptionData['tint'] ?? null,
        ]);

        return $patient->load('prescription.frame', 'prescription.lensType');
    }

    public function updatePatientWithPrescription(Patient $patient, array $patientData, array $prescriptionData): Patient
    {
        $patient->update([
            'first_name' => $patientData['first_name'],
            'middle_name' => $patientData['middle_name'] ?? null,
            'last_name' => $patientData['last_name'],
            'birthdate' => $patientData['birthdate'],
            'gender' => $patientData['gender'],
            'phone_number' => $patientData['phone_number'] ?? null,
            'address' => $patientData['address'] ?? null,
        ]);

        if ($patient->prescription) {
            $patient->prescription->update([
                'sphere' => $prescriptionData['sphere'] ?? null,
                'cylinder' => $prescriptionData['cylinder'] ?? null,
                'axis' => $prescriptionData['axis'] ?? null,
                'addition' => $prescriptionData['addition'] ?? null,
                'pd' => $prescriptionData['pd'] ?? null,
                'frame_id' => $prescriptionData['frame_id'] ?? null,
                'lens_type_id' => $prescriptionData['lens_type_id'] ?? null,
                'tint' => $prescriptionData['tint'] ?? null,
            ]);
        }

        return $patient->load('prescription.frame', 'prescription.lensType');
    }

    public function searchPatients(?string $query, int $perPage = 10)
    {
        if (empty($query)) {
            return Patient::with('prescription.frame', 'prescription.lensType')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
        }

        return Patient::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('middle_name', 'like', "%{$query}%")
            ->orWhere('phone_number', 'like', "%{$query}%")
            ->with('prescription.frame', 'prescription.lensType')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
