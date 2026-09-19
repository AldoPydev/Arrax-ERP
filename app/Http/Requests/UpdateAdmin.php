<?php

namespace App\Http\Requests;

//======= IMPORTAR MODELO USER
use App\Models\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdmin extends FormRequest
{
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
    public function rules(User $user): array
    {
        //======= OBTIENE AL USUARIO SELECCIONADO
            $user = $this->route('user');

        return [
            'name' => 'required|string|max:255',
            'correo' => 'nullable|string|email|max:255', 
            'telefono' => 'required|integer',
            'profesion' => 'nullable|string|max:255',
            'empleado' => 'required|integer|unique:users,empleado,' . $user->id,
            'status' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'departamento_id' => 'nullable|exists:departamentos,id',
        ];
    }
}
