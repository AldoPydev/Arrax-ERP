<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//======= IMPORTAR MODELO USER
use App\Models\User;

//======= IMPORTAR MODELO ROLES / MODELO PERMISOS
use Spatie\Permission\Models\Role;

//======= IMPORTAR SOPORTE MODIFICAR STRING
use Illuminate\Support\Str;

class UserController extends Controller
{
    
    public function index()
    {
        //======= Obtener los usuario / roles / paginado en grupos de 10
        $users = User::with('roles')->oldest('empleado')->paginate(10);
        
        //======= RETORNAR VISTA INDEX CON DATOS DE USUARIOS
        return view('admin.users.index', compact("users"));
    }

    
    public function create()
    {
        //return view('admin.users.create');

        //======= RECUPERAR LOS ROLES DE USUARIO
        $roles = Role::all();

        //======= RETORNAR VISTA CREATE
        return view('auth.register', compact('roles'));
    }

    
    public function store(Request $request)
    {

        //====== REGLA DE VALIDACION AL CREAR USUARIO
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'correo' => ['required', 'string', 'email', 'max:255'],
            'telefono' => ['required', 'integer'],
            'profesion' => ['required', 'string', 'max:255'],
            'empleado' => ['required', 'integer', 'unique:users,empleado'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

       //================== SI EL REGISTRO NO EXISTE
        
        //====== ENCRIPTAR CONTRASEÑA
        $data['password'] = bcrypt($data['password']);

        //====== EMAIL EN MINUSCULAS
        $data['correo'] = strtolower($data['correo']);
        $data['email'] = strtolower($data['email']);


        //====== CRAER USUARIO
        $user = User::create($data);

        //====== VALDACIÓN Y ASIGNAR ROL A USUARIO
        if(isset($data['roles'])){
            $user->roles()->attach($data['roles']);
        }

        //====== REDIRECCIONAR A INDEX 
        return redirect()->route('admin.users.index')->with('success', 'Usuario creado');
    
    }

    public function show(User $user)
    {

    //======= Obtener los usuario / roles / paginado en grupos de 10
        //$users = User::with('roles')->oldest('id')->paginate(10);

        //======= RETORNAR VISTA SHOW CON DATOS DE USUARIOS
        return view("admin.users.show", compact('user'));
    }


    public function edit(User $user)
    {
        //======= RECUPERAR LOS ROLES DE USUARIO
        $roles = Role::all();

        //======= RETORNAR VISTA EDIT CON DATOS DE USUARIOS
        return view("admin.users.edit", compact('user', 'roles'));
    }


    public function update(Request $request, User $user)
    {
        //====== REGLA DE VALIDACION AL ACTUALIZAR
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'correo' => ['required', 'string', 'email', 'max:255'],
            'telefono' => ['required', 'integer', 'digits:10'],
            'profesion' => ['required', 'string', 'max:255'],
            'empleado' => ['required', 'integer', 'unique:users,empleado'],
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, 
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        //====== EMAIL EN MINUSCULAS
        $data['correo'] = strtolower($data['correo']);
        $data['email'] = strtolower($data['email']);


        //====== ENVIAR LOS DATOS
        $user->name = $data['name'];
        $user->correo = $data['correo'];
        $user->telefono = $data['telefono'];
        $user->profesion = $data['profesion'];
        $user->empleado = $data['empleado'];
        $user->email = $data['email'];


        

        //====== VERIFICAR SI INTRODUCE UN NUEVO PASSWORD
        // Y ENVIARLO ENCRIPTADO

        if(isset($data['password'])){

            $user->password = bcrypt($data['password']);
        }

        //====== ACTUALIZAR ROL DE USUARIO
        $user->roles()->sync($request->input('roles', []));

        //====== GUARDAR CAMBIOS
        $user->save();

        //======== CONFIRMAR EDICIÓN Y REDIRECCIONAR A EDIT
        return redirect()->route('admin.users.edit', $user)->with('success', 'Usuario Actualizado');
    }


    public function destroy(User $user)
    {
        //======== ELIMINAR USUARIO
        $user->delete();

        //======== CONFIRMAR ELIMINACIÓN Y REDIRECCIONAR A INDEX
        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado');
    }



    public function search(User $user)
    {
        //
    }
}
