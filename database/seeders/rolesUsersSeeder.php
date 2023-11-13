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
            ['name' => 'Associative researcher'],
            ['name' => 'Postdoctoral researcher'],
            ['name' => 'Main researcher'],
            ['name' => 'Administrator'],
            ['name' => 'Inactive'],
        ];

        foreach($roles as $role){
            $role1 = rolesUsers::create($role);
        };
    }
}