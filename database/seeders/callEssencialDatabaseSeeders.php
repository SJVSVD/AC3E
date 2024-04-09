<?php

namespace Database\Seeders;

use App\Models\rolesUsers;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class callEssencialDatabaseSeeders extends Seeder
{
    public function run()
    {
        $this->call([
            rolesUsersSeeder::class,
            researchLinesSeeder::class,
            permisosSeeder::class,
            rolesSeeder::class,
            usersSeeder::class,
            extraSeeder::class,
            universitySeeder::class,
        ]);
    }
}
