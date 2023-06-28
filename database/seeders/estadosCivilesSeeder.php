<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_estadosCiviles;

class estadosCivilesSeeder extends Seeder
{
    public function run()
    {
        $estadosCiviles = [
            ['nombre' => 'Soltero/a'],
            ['nombre' => 'Conviviente'],
            ['nombre' => 'Casado/a'],
            ['nombre' => 'Anulado/a'],
            ['nombre' => 'Viudo/a'],
        ];

        foreach($estadosCiviles as $estadoCivil){
            $estadoCivil1 = SYS_estadosCiviles::create($estadoCivil);
        };

    }
}