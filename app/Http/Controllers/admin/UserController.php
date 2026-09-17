<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//======= IMPORTAR MODELO DEPARTAMENTO
use App\Models\Departamento;


//======= IMPORTAR MODELO USER
use App\Models\User;

//======= IMPORTAR MODELO ROLES DE Spatie permissions
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    
    public function index(Request $request)
    {

        //======= Obtener lo que se va a buscar
        $buscar = $request->get('buscar');
        $query = User::query();
        

        //======= Condición buscar de acuerdo al nombre o numero de trabajador
        if($buscar){
            $query->where('name', 'like', '%' . $buscar . '%')
                ->orWhere('empleado', 'like', '%' . $buscar . '%');
        }


        //======= Obtener los usuario / roles / departamento /paginado en grupos de 10
        $users = $query->with(['roles', 'departamento']) 
           ->oldest('empleado')
           ->paginate(10)
           ->withQueryString();
        
        //======= RETORNAR VISTA INDEX CON DATOS DE USUARIOS Y LA BUSQUEDA 
        return view('admin.users.index', compact("users", "buscar"));
    }

    
    public function create()
    {
        //======= Obtenemos todos los departamentos ordenados
        $departamentos = Departamento::orderBy('name', 'asc')->get();

        //======= RECUPERAR LOS ROLES DE USUARIO
        $roles = Role::all();

        //======= RETORNAR VISTA CREATE
        return view('auth.register', compact('roles', 'departamentos'));
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
            'status' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'departamento_id' => ['required', 'exists:departamentos,id'],
        ]);

       //======= SI EL REGISTRO NO EXISTE
        
        //====== ENCRIPTAR CONTRASEÑA
        $data['password'] = bcrypt($data['password']);

        //====== EMAIL EN MINUSCULAS
        $data['correo'] = strtolower($data['correo']);
        $data['email'] = strtolower($data['email']);
        
    //====== CRAER USUARIO
        $user = User::create($data);
        
         //====== GUARDAR EL DEPARTAMENTO
        $data['departamento_id'] = $user->departamento_id;

        //====== VALDACIÓN Y ASIGNAR ROL A USUARIO
        if($request->has('roles')){
            $user->roles()->sync($request->input('roles'));
}

        //====== REDIRECCIONAR A INDEX 
        return redirect()->route('admin.users.index')->with('success', 'Usuario creado');
    
    }

    public function show(User $user)
    {

        //======= RETORNAR VISTA SHOW CON DATOS DE USUARIOS
        return view("admin.users.show", compact('user'));
    }


    public function edit(User $user)
    {
        //======= Obtenemos todos los departamentos ordenados
        $departamentos = Departamento::orderBy('name', 'asc')->get();


        //======= RECUPERAR LOS ROLES DE USUARIO
        $roles = Role::all();

        //======= RETORNAR VISTA EDIT CON DATOS DE USUARIOS
        return view("admin.users.edit", compact('user', 'roles', 'departamentos'));
    }


    public function update(Request $request, User $user)
    {
        //====== REGLA DE VALIDACION AL ACTUALIZAR
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'correo' => ['required', 'string', 'email', 'max:255'],
            'telefono' => ['required', 'integer', 'digits:10'],
            'profesion' => ['required', 'string', 'max:255'],
            'empleado' => ['required', 'integer', 'unique:users,empleado,' . $user->id],
            'status' => ['required', 'string', 'max:255'],
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, 
            'password' => 'nullable|string|min:8|confirmed',
            'departamento_id' => 'required|exists:departamentos,id',
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
        $user->status = $data['status'];
        $user->email = $data['email'];

        //====== GUARDAR EL DEPARTAMENTO
            $user->departamento_id = $data['departamento_id'];
        

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

    public function departamento()
{
    return $this->belongsTo(Departamento::class);
}


}
