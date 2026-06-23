<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'selling_price' => 'required|numeric|min:0',
            'discounted_price' => 'nullable|numeric|min:0',
            'reorder_level' => 'required|integer|min:0',
            'reorder_quantity' => 'required|integer|min:0',
        ];
    }
}
