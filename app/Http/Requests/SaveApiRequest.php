<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveApiRequest extends FormRequest
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
            'name'=>'required|max:100',
            'description'=>'nullable|min:3',
            'token'=>'required|max:300',
        ];
    }
    public function messages()
{
    return [
        'name.required' => 'The name is required.',
        'description.min' => 'The description must contain at least 3 characters.',
        'token.required' => 'The token is required.',
      
    ];
}
}
