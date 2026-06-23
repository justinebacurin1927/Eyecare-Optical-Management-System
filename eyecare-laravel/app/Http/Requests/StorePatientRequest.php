<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
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
        ];
    }
}
