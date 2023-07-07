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
            'Usuario',
        ];

    foreach($roles as $rol){
        Role::create(['name' => $rol]);
    };
    }
}