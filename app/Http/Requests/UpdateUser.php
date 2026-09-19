<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //======= PERMITE EL METODO DE AUTENTICACION CREADO
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'correo' => ['required', 'string', 'email', 'max:255'],
            'telefono' => ['required', 'integer', 'digits:10'],
            'profesion' => ['required', 'string', 'max:255'],
        ];
    }
}
