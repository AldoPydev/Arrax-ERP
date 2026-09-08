<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

//======== IMPORTAR MODELO PERMISSIONS
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //======== DIFINOR PERMISOS

        $permissions = [
            'acceso usuarios',
            'acceso gerencia',
            'acceso proyectos',
            'acceso roles',
            'acceso crear',
            'acceso ver',
            'acceso editar',
            'acceso actualizar',
            'acceso eliminar'
        ];

        //======== CREAR PERMISOS EN BD

        foreach( $permissions as $permission){

            Permission::create([

                'name' => $permission
                
            ]);
        }
    }
}
