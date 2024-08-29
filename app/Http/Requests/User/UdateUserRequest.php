<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UdateUserRequest extends FormRequest
{
  
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $unique = Rule::unique('users')->ignore($this->route('user'));

        return [
            'surname' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', $unique],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email обязателен для заполнения.',
            'email.email' => 'Email должен быть действительным адресом электронной почты.',
            'email.max' => 'Email должен быть не более 255 символов.',
            'email.unique' => 'Email должен быть уникальным.',
        ];
    }
}
