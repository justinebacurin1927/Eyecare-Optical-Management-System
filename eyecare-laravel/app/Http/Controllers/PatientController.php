<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Frame;
use App\Models\LensType;
use Illuminate\Http\Request;

class PatientController extends Controller
{
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|string',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'sphere' => 'nullable|string|max:50',
            'cylinder' => 'nullable|string|max:50',
            'axis' => 'nullable|string|max:50',
            'addition' => 'nullable|string|max:50',
            'pd' => 'nullable|string|max:50',
            'frame_id' => 'nullable|exists:frames,id',
            'lens_type_id' => 'nullable|exists:lens_types,id',
            'tint' => 'nullable|string|max:50',
        ]);

        $patient = Patient::create([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'birthdate' => $validated['birthdate'],
            'gender' => $validated['gender'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
        ]);

        Prescription::create([
            'patient_id' => $patient->id,
            'sphere' => $validated['sphere'],
            'cylinder' => $validated['cylinder'],
            'axis' => $validated['axis'],
            'addition' => $validated['addition'],
            'pd' => $validated['pd'],
            'frame_id' => $validated['frame_id'],
            'lens_type_id' => $validated['lens_type_id'],
            'tint' => $validated['tint'],
        ]);

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

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|string',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'sphere' => 'nullable|string|max:50',
            'cylinder' => 'nullable|string|max:50',
            'axis' => 'nullable|string|max:50',
            'addition' => 'nullable|string|max:50',
            'pd' => 'nullable|string|max:50',
            'frame_id' => 'nullable|exists:frames,id',
            'lens_type_id' => 'nullable|exists:lens_types,id',
            'tint' => 'nullable|string|max:50',
        ]);

        $patient->update([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'birthdate' => $validated['birthdate'],
            'gender' => $validated['gender'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
        ]);

        if ($patient->prescription) {
            $patient->prescription->update([
                'sphere' => $validated['sphere'],
                'cylinder' => $validated['cylinder'],
                'axis' => $validated['axis'],
                'addition' => $validated['addition'],
                'pd' => $validated['pd'],
                'frame_id' => $validated['frame_id'],
                'lens_type_id' => $validated['lens_type_id'],
                'tint' => $validated['tint'],
            ]);
        }

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully.');
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        $patients = Patient::where('first_name', 'like', "%{$search}%")
            ->orWhere('last_name', 'like', "%{$search}%")
            ->orWhere('middle_name', 'like', "%{$search}%")
            ->orWhere('phone_number', 'like', "%{$search}%")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json($patients);
        }

        return view('patients.index', compact('patients'));
    }
}
