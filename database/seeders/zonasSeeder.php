<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_zonas;

class zonasSeeder extends Seeder
{
    public function run()
    { 
        $zonas = [
            ['nombre' => 'Norte', 'idPais' => '1', 'idPaisZona' => '1Norte'],
            ['nombre' => 'Centro', 'idPais' => '1', 'idPaisZona' => '1Centro'],
            ['nombre' => 'Sur', 'idPais' => '1', 'idPaisZona' => '1Sur'],
        ];

        foreach($zonas as $zona){
            $zona1 = SYS_zonas::create($zona);
        };
    }
}