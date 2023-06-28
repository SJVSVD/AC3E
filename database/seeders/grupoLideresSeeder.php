<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_grupoLideres;

class grupoLideresSeeder extends Seeder
{
    public function run()
    {
        $grupoLideres= [
            ['idGrupo' => '1', 'idUsuario' => '3'],
            ['idGrupo' => '2', 'idUsuario' => '69'],
            ['idGrupo' => '3', 'idUsuario' => '63'],
            ['idGrupo' => '4', 'idUsuario' => '53'],
            ['idGrupo' => '4', 'idUsuario' => '7'],
            ['idGrupo' => '4', 'idUsuario' => '71'],
            ['idGrupo' => '4', 'idUsuario' => '20'],
            ['idGrupo' => '4', 'idUsuario' => '14'],
            ['idGrupo' => '5', 'idUsuario' => '5'],
            ['idGrupo' => '6', 'idUsuario' => '53'],
            ['idGrupo' => '7', 'idUsuario' => '23'],
            ['idGrupo' => '7', 'idUsuario' => '79'],
        ];

        foreach($grupoLideres as $grupoLider){
            $grupoLider1 = SYS_grupoLideres::create($grupoLider);
        };
    }
}