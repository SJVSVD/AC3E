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
            'Administrator',
            'Invitado',
            'Usuario',
        ];

    foreach($roles as $rol){
        Role::create(['name' => $rol]);
    };
    }
}