<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class permisosSeeder extends Seeder
{
    public function run()
    {
        $permisos = [
            // Usuarios
            'Usuarios_Ver',
            'Usuarios_Crear',
            'Usuarios_Modificar',
            'Usuarios_Deshabilitar',
        ];

        foreach($permisos as $permiso){
            Permission::create(['name' => $permiso]);
        };
    }
}