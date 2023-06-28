<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_afp;

class afpsSeeder extends Seeder
{
    public function run()
    {
        $afps = [
            ['nombre' => 'Capital'],
            ['nombre' => 'Habitat'],
            ['nombre' => 'Cuprum'],
            ['nombre' => 'Plan Vital'],
            ['nombre' => 'Provida'],
            ['nombre' => 'Modelo'],
        ];

        foreach($afps as $afp){
            $afp1 = SYS_afp::create($afp);
        };
    }
}