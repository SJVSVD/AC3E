<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_afp;

class detallesRobosSeeder extends Seeder
{
    public function run()
    {
        $detallesRobos = [
            ['idRobo' => '1','item' => 'Cartera A','descripcion' => 'Nada que añadir.','cantidad' => 3,'monto' => 25000],
            ['idRobo' => '1','item' => 'Cartera B','descripcion' => 'Nada que añadir.','cantidad' => 5,'monto' => 30000],
            ['idRobo' => '2','item' => 'Cartera B','descripcion' => 'Nada que añadir.','cantidad' => 3,'monto' => 30000],
            ['idRobo' => '3','item' => 'Cartera A','descripcion' => 'Nada que añadir.','cantidad' => 4,'monto' => 25000],
            ['idRobo' => '4','item' => 'Cartera A','descripcion' => 'Nada que añadir.','cantidad' => 3,'monto' => 25000],
            ['idRobo' => '5','item' => 'Cartera D','descripcion' => 'Nada que añadir.','cantidad' => 1,'monto' => 12000],
            ['idRobo' => '5','item' => 'Cartera C','descripcion' => 'Nada que añadir.','cantidad' => 3,'monto' => 37000],
            ['idRobo' => '5','item' => 'Cartera B','descripcion' => 'Nada que añadir.','cantidad' => 2,'monto' => 30000],
            ['idRobo' => '6','item' => 'Cartera A','descripcion' => 'Nada que añadir.','cantidad' => 3,'monto' => 25000],
            ['idRobo' => '7','item' => 'Cartera C','descripcion' => 'Nada que añadir.','cantidad' => 6,'monto' => 37000],
        ];
        
        foreach($detallesRobos as $detalleRobo){
            DB::table('TIE_roboDetalles') -> insert([$detalleRobo]);
        };
    }
}