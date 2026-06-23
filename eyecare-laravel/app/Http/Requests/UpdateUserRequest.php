<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $this->route('user')->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->route('user')->id,
            'role' => 'required|in:' . implode(',', \App\Enums\Role::values()),
            'password' => 'nullable|string|min:8',
        ];
    }
}
