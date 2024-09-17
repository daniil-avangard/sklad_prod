<?php

namespace App\Http\Requests\UserSettings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordRequest extends FormRequest
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
            'password' => ['required', 'string', 'min:8', 'max:255', function ($attribute, $value, $fail) {
                if (!\Hash::check($value, auth()->user()->password)) {
                    $fail('The ' . $attribute . ' is incorrect.');
                }
            }],
            'new_password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
            'new_password_confirmation' => ['required', 'string', 'min:8', 'max:255'],

        ];
    }
}
