<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKpiRequest extends FormRequest
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
            'value' => 'required|numeric',
            'trend' => 'required|in:↑,↓,→',
            'status' => 'required|in:high,medium,low'
           

        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'The name is required.',
            'value.required' => 'The value is required.',
            'trend.required' => 'The trend is required.',
            'trend.in' => 'The selected trend is invalid. Please choose from: ↑,↓,→.',
            'status.required' => 'The status is required.',
            'status.in' => 'The selected status is invalid. Please choose from: high,medium,low.',
           
          
        ];
    }
}
