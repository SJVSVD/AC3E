<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class callAllDatabaseSeeders extends Seeder
{
    public function run()
    {
        $this->call([
            permisosSeeder::class,
            rolesSeeder::class,
            usersSeeder::class,
        ]);
    }
}
