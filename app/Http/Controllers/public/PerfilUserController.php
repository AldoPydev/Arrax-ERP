<?php

namespace App\Http\Controllers\public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


//======= IMPORTAR MODELO DEPARTAMENTO
use App\Models\Departamento;

//======= IMPORTAR MODELO USER
use App\Models\User;

//======= IMPORTAR MODELO ROLES DE Spatie permissions
use Spatie\Permission\Models\Role;

//======= IMPORTAR REGLAS DE VALIDACIÓN UPDATE USER
use App\Http\Requests\UpdateUser;

//======= IMPORTAR AUTENTICACION
use Illuminate\Support\Facades\Auth;


class PerfilUserController extends Controller
{
   

    public function show(User $user)
    {

        //======= RETORNAR VISTA SHOW CON DATOS DE USUARIOS
        return view("public.users.show", compact('user'));
    }


    public function edit(string $id)
    {
        //======= OBTENER DATOS DE DEPARTAMENTOS / ORDENADO
        $departamentos = Departamento::orderBy('name', 'asc')->get();


        //======= RECUPERAR LOS ROLES DE USUARIO
        $roles = Role::all();

        return view("public.users.edit", compact("id", "departamentos", "roles"));
    }


    public function update(UpdateUser $request, User $user)
    {
        // $user instancia del modelo User y reconoce el metodo Update
        /** @var \App\Models\User $user */
        $user = Auth::user(); 
        
        //====== REQUESTS DE VALIDACION 
        $user->update($request->validated());

        return redirect()->back()->with('success', 'Tu perfil se ha actualizado.');
    }

}
