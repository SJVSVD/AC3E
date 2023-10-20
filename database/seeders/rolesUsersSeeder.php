<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\rolesUsers;

class rolesUsersSeeder extends Seeder
{
    public function run()
    {    
        $roles = [
            ['name' => 'Investigador Asociado'],
            ['name' => 'Investigadora Postdoctoral'],
            ['name' => 'Investigador Titular'],
            ['name' => 'Administrador'],
            ['name' => 'Inactivo'],
        ];

        foreach($roles as $role){
            $role1 = rolesUsers::create($role);
        };
    }
}