<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_grupos;

class gruposSeeder extends Seeder
{
    public function run()
    {
        $grupos = [
            ['nombre' => 'Contabilidad'],
            ['nombre' => 'Informatica'],
            ['nombre' => 'Existencias'],
            ['nombre' => 'Tiendas_Amphora'],
            ['nombre' => 'Tiendas_Scalpers'],
            ['nombre' => 'Maria_Ines_Morales'],
            ['nombre' => 'Centro_Distribucion'],
        ];
        
        foreach($grupos as $grupo){
            $grupo1 = SYS_grupos::create($grupo);
        };
    }
}