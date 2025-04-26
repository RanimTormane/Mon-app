<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class saveUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|string|in:Admin,Marketing manager,Marketing assistant',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name is required.',
            'email.required' => 'The email is required.',
            'email.email' => 'The email must be valid.',
            'email.unique' => 'This email is already taken.',
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 6 characters long.',
            'role.required' => 'The role is required.',
            'role.in' => 'The role must be either "Admin" or "Marketing manager" or "Marketing assistant".',
        ];
}
}