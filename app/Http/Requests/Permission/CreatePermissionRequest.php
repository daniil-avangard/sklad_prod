<?php

namespace App\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;

class CreatePermissionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:50', 'unique:permissions'],
            'action' => ['required', 'string', 'max:50', 'unique:permissions'],
        ];
    }
}
