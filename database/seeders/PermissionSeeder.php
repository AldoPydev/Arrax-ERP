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
            'Crear',
            'Ver',
            'Editar',
            'Eliminar'
        ];

        //======== CREAR PERMISOS EN BD

        foreach( $permissions as $permission){


            //======== firstOrCreate - verifica si ya existe y actualiza 
            Permission::firstOrCreate([

                'name' => $permission
                
            ]);
        }
    }
}
