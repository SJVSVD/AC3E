<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_marcas;

class marcasSeeder extends Seeder
{
    public function run()
    {
        $marcas = [
            ['nombre' => 'Amphora', 'estado' => true],
            ['nombre' => 'Scalpers', 'estado' => true],
            ['nombre' => 'Ziol', 'estado' => true],
        ];

        foreach($marcas as $marca){
            $marca1 = SYS_marcas::create($marca);
        };
    }
} 