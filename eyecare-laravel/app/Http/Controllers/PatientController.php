<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Requests\SearchPatientRequest;
use App\Models\Frame;
use App\Models\LensType;
use App\Models\Patient;
use App\Services\PatientService;

class PatientController extends Controller
{
    public function __construct(
        private readonly PatientService $patientService
    ) {}

    public function index()
    {
        $patients = Patient::with('prescription.frame', 'prescription.lensType')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        $frames = Frame::all();
        $lensTypes = LensType::all();

        return view('patients.create', compact('frames', 'lensTypes'));
    }

    public function store(StorePatientRequest $request)
    {
        $validated = $request->validated();

        $prescriptionData = [
            'sphere' => $validated['sphere'] ?? null,
            'cylinder' => $validated['cylinder'] ?? null,
            'axis' => $validated['axis'] ?? null,
            'addition' => $validated['addition'] ?? null,
            'pd' => $validated['pd'] ?? null,
            'frame_id' => $validated['frame_id'] ?? null,
            'lens_type_id' => $validated['lens_type_id'] ?? null,
            'tint' => $validated['tint'] ?? null,
        ];

        $this->patientService->createPatientWithPrescription($validated, $prescriptionData);

        return redirect()->route('patients.index')->with('success', 'Patient registered successfully.');
    }

    public function show(Patient $patient)
    {
        $patient->load('prescription.frame', 'prescription.lensType');

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        $patient->load('prescription');
        $frames = Frame::all();
        $lensTypes = LensType::all();

        return view('patients.edit', compact('patient', 'frames', 'lensTypes'));
    }

    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $validated = $request->validated();

        $prescriptionData = [
            'sphere' => $validated['sphere'] ?? null,
            'cylinder' => $validated['cylinder'] ?? null,
            'axis' => $validated['axis'] ?? null,
            'addition' => $validated['addition'] ?? null,
            'pd' => $validated['pd'] ?? null,
            'frame_id' => $validated['frame_id'] ?? null,
            'lens_type_id' => $validated['lens_type_id'] ?? null,
            'tint' => $validated['tint'] ?? null,
        ];

        $this->patientService->updatePatientWithPrescription($patient, $validated, $prescriptionData);

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }

    public function search(SearchPatientRequest $request)
    {
        $search = $request->validated('q');
        $patients = $this->patientService->searchPatients($search);

        if ($request->ajax()) {
            return response()->json($patients);
        }

        return view('patients.index', compact('patients'));
    }
}
