<?php

namespace App\Http\Requests\Product\Variant;

use Illuminate\Foundation\Http\FormRequest;

class CreateVariantRequest extends FormRequest
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
            'pdf_maket' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'is_active' => ['nullable', 'boolean'],
            'date_of_actuality' => ['nullable', 'date'],
        ];
    }
}
