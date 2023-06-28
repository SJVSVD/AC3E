<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class rolesSeeder extends Seeder
{
    public function run()
    {    
        $roles = [
            'Super-Administrador',
            'Administrador',
            'Sistema',
            'Invitado',
            'Colaborador',
            'Usuario',
            'Existencia_Jefatura',
            'Existencia',
            'Proveedores_Jefatura',
            'Proveedores',
            'Remuneraciones_Jefatura',
            'Remuneraciones',
            'Contabilidad_Jefatura',
            'Contabilidad',
            'Informática_Jefatura',
            'Informática',
            'Comercial_Jefatura',
            'Comercial',
            'Marketing_Jefatura',
            'Marketing',
            'Diseño_Jefatura',
            'Diseño',
            'Centro de Distribución_Jefatura',
            'Centro de Distribución',
            'Tiendas_Jefatura',
            'Tiendas',
        ];

    foreach($roles as $rol){
        Role::create(['name' => $rol]);
    };
    }
}