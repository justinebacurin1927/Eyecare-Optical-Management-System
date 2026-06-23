<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFrameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'style' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
        ];
    }
}
