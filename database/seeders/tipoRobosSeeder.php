<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_tipoRobo;

class tipoRobosSeeder extends Seeder
{
    public function run()
    {
        $tipoRobo = [
            ['nombre' => 'Producto'],
            ['nombre' => 'Insumo'],
            ['nombre' => 'Robo Colaborador'],
            ['nombre' => 'Robo Cliente'],
            ['nombre' => 'Dinero'],
        ];

        foreach($tipoRobo as $tipo){
            $tipo1 = SYS_tipoRobo::create($tipo);
        };        
    }
}