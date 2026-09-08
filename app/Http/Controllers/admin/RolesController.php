<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

//======= IMPORTAR MODELO ROLES / MODELO PERMISOS
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    
    public function index()
    {
        
       

        $roles = Role::oldest('id')->paginate(3);

        //======= RETORNAR VISTA INDEX CON DATOS DE USUARIOS
        return view('admin.roles.index', compact("roles"));
    }

    
    public function create()
    {
        //======= OBTENER LOS PERMISOS 
        $permissions = Permission::all();


        //======= RETORNAR VISTA CREATE
        return view('admin.roles.create', compact('permissions'));
    }

    
    public function store(Request $request)
    {
        //======= VALIDACION

        $request->validate([

            // Campo name requerido y unico en tabla roles
            'name' => 'required|unique:roles,name',

            'name.*' => 'exists:roles,name',

            // Campo permisos debe estar seleccioando uno y enviado en array
            'permissions' => 'required|array',

            // Campo permisos requerido y unico en tabla permisos
            'permissions.*' => 'exists:permissions,id',
        ]);


            // Crear el Rol, al pasar la validación
            $role = Role::create([
                'name' => $request->name,
            ]);

            // Asignar los permisos al nuevo Rol
            $role->permissions()->attach($request->permissions);

            //======= RETORNAR VISTA EDIT CON DATOS DE ROL CREADO
            return redirect()->route('admin.roles.edit', $role);
        
        

    }

    
    public function show(Role $role)
    {
        $permissions = Permission::all();

        //======= RETORNAR VISTA SHOW CON DATOS DE ROL
        return view('admin.roles.show', compact("role", "permissions"));
    }

    
    public function edit(Role $role)
    {
        //======= OBTENER LOS PERMISOS 
        $permissions = Permission::all();

        //======= RETORNAR VISTA EDIT CON DATOS DE ROL
        return view('admin.roles.edit', compact("role", "permissions"));
    }

    
    public function update(Request $request, Role $role)
    {
        //======= VALIDACION

        $request->validate([

            // Campo name requerido y unico en tabla roles, ignorar el id que editamos
            'name' => 'required|unique:roles,name,' . $role->id,

            // Campo permisos debe estar seleccioando uno y enviado en array
            'permissions' => 'required|array',

            // Campo permisos requerido y unico en tabla permisos
            'permissions.*' => 'exists:permissions,id',
        ]);


        // Actualizar el Rol, al pasar la validación
        $role->update([
            'name' => $request->name,
        ]);

        // Sincronizar los permisos actuales y nuevos
        $role->permissions()->sync($request->permissions);

        //======= RETORNAR VISTA EDIT CON DATOS DEL ROL 
        return redirect()->route('admin.roles.edit', $role);


    }

    
    public function destroy(Role $role)
    {
        //======== ELIMINAR ROL
        $role->delete();

        //======== CONFIRMAR ELIMINACIÓN Y REDIRECCIONAR A INDEX
        return redirect()->route('admin.users.index')->with('success', 'Rol eliminado');
    }
}
