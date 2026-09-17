<?php

namespace Database\Seeders;

//=======  IMPORTAR MODELO ROLES DE PERMISSIONS
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //=======  INICIALIZAR ROLES Y ASIGANAR PERMISOS A ROLES

    $roles = [

            'Administrador' => [
            'Crear',
            'Ver',
            'Editar',
            'Eliminar'
        ],

            
        'Encargado' => [
            'Crear',
            'Ver',
            'Editar',
        ],

        'Auxiliar' => [
            'Crear',
            'Ver',
            'Editar',
        ],

            'Practicante' => [
            'Crear',
            'Ver',
            'Editar',
        ],
        
            'Invitado' => [
            'Ver',

        ]
    ];

        //======= GENERAR ROLES EN BASE DE DATOS 

        foreach($roles as $name => $permissions){
            $role = Role::firstOrCreate ([
                'name' => $name
            ]);

            //======= RELACIONAR ROL CON PERMISOS

            $role -> syncPermissions($permissions);
        }
    }
}
