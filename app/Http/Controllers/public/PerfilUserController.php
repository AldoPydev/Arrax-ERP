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

//======= IMPORTAR LOCAL STORAGE
use Illuminate\Support\Facades\Storage;


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
        //======  $user instancia del modelo User y reconoce el metodo Update
        /** @var \App\Models\User $user */
        $user = Auth::user(); 
        
        //====== REQUESTS DE VALIDACION 
        $user->update($request->validated());


        // ====== GUARDAR LA FOTO DE PERFIL
        if ($request->hasFile('image')) {

            // OBTENER FOTO ANTERIOR 
            $oldImage = $user->getOriginal('image_path') ?? $user->image_path;

            //====== GUARDAR FOTO EN storage/app/public/perfil
            $path = $request->file('image')->store('perfil', 'public');

            // OBTENER LA URL 
            $user->image_path = $path;

            // ELIMINAR FOTO ANTERIOR PARA GUARDAR LA NUEVA 
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
        }


        //====== GUARDAR CAMBIOS
        $user->save();

        return redirect()->back()->with('success', 'Tu perfil se ha actualizado.');
    }

}
