<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_afp;

class robosSeeder extends Seeder
{
    public function run()
    {
        $robos = [
            ['autorizado' => null, 'estado' => 'Ingresado' ,'idTipoRobo' => '1', 'idTienda' => '1', 'idUsuario' => '2','fecha' => '2023-01-08','hora' => '04:05:06', 'observacion' => 'robaron un gato', 'idSupervisor' => '3', 'montoRobado' => '30000'],
            ['autorizado' => null, 'estado' => 'Ingresado' ,'idTipoRobo' => '1', 'idTienda' => '1', 'idUsuario' => '2','fecha' => '2023-01-08','hora' => '04:05:06', 'observacion' => 'robaron un gato', 'idSupervisor' => '3', 'montoRobado' => '30000'],
            ['autorizado' => null, 'estado' => 'Ingresado' ,'idTipoRobo' => '1', 'idTienda' => '1', 'idUsuario' => '4','fecha' => '2023-01-08','hora' => '04:05:06', 'observacion' => 'robaron un gato', 'idSupervisor' => '3', 'montoRobado' => '30000'],
            ['autorizado' => null, 'estado' => 'Ingresado' ,'idTipoRobo' => '1', 'idTienda' => '1', 'idUsuario' => '2','fecha' => '2023-01-08','hora' => '04:05:06', 'observacion' => 'robaron un gato', 'idSupervisor' => '2', 'montoRobado' => '30000'],
            ['autorizado' => true, 'estado' => 'Cerrado' ,'idTipoRobo' => '1', 'idTienda' => '1', 'idUsuario' => '2','fecha' => '2023-01-08','hora' => '04:05:06', 'observacion' => 'robaron un gato', 'idSupervisor' => '4', 'montoRobado' => '30000'],
            ['autorizado' => true, 'estado' => 'Cerrado' ,'idTipoRobo' => '1', 'idTienda' => '1', 'idUsuario' => '5','fecha' => '2023-01-08','hora' => '04:05:06', 'observacion' => 'robaron un gato', 'idSupervisor' => '2', 'montoRobado' => '30000'],
            ['autorizado' => false, 'estado' => 'Cerrado' ,'idTipoRobo' => '1', 'idTienda' => '1', 'idUsuario' => '3','fecha' => '2023-01-08','hora' => '04:05:06', 'observacion' => 'robaron un gato', 'idSupervisor' => '2', 'montoRobado' => '30000'],
        ];

        foreach($robos as $robo){
            DB::table('TIE_robos') -> insert([$robo]);
        };
    }
}