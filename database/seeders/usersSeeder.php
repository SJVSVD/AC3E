<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class usersSeeder extends Seeder
{
    public function run()
    {
        $users = [
            // Usuario de Prueba:
            ['name' => 'Test', 'email' => 'test@ac3e.com', 'password' => bcrypt('test')],
            ['name' => 'Sebastián Jerez Vega', 'email' => 'sjerez@ac3e.com', 'password' => bcrypt('sjerez861')],
        ];

        foreach($users as $user){
            $user1 = User::create($user);
        };
    }
}