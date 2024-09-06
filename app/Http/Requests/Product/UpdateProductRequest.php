<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'company_id' => ['required', 'exists:companies,id'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:10240'],
            'kko_hall' => ['nullable', 'boolean'],
            'kko_account_opening' => ['nullable', 'boolean'],
            'kko_manager' => ['nullable', 'boolean'],
            'kko_operator' => ['nullable', 'in:' . implode(',', array_column(\App\Enum\Products\PointsSale\Operator::cases(), 'value'))],
            'express_hall' => ['nullable', 'boolean'],
            'express_operator' => ['nullable', 'in:' . implode(',', array_column(\App\Enum\Products\PointsSale\Operator::cases(), 'value'))],
            'description' => ['nullable', 'string'],
            'delete_image' => ['nullable', 'boolean'],
        ];
    }
}
