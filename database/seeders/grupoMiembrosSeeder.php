<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_grupoMiembros;

class grupoMiembrosSeeder extends Seeder
{
    public function run()
    {
        $grupoMiembros = [
            ['idGrupo' => '1', 'idUsuario' => '21'],
            ['idGrupo' => '1', 'idUsuario' => '118'],
            ['idGrupo' => '1', 'idUsuario' => '43'],
            ['idGrupo' => '2', 'idUsuario' => '52'],
            ['idGrupo' => '2', 'idUsuario' => '41'],
            ['idGrupo' => '2', 'idUsuario' => '82'],
            ['idGrupo' => '2', 'idUsuario' => '4'],
            ['idGrupo' => '3', 'idUsuario' => '25'],
            ['idGrupo' => '3', 'idUsuario' => '13'],
            ['idGrupo' => '3', 'idUsuario' => '7'],
            ['idGrupo' => '3', 'idUsuario' => '71'],
            ['idGrupo' => '4', 'idUsuario' => '87'],
            ['idGrupo' => '4', 'idUsuario' => '88'],
            ['idGrupo' => '4', 'idUsuario' => '89'],
            ['idGrupo' => '4', 'idUsuario' => '90'],
            ['idGrupo' => '4', 'idUsuario' => '91'],
            ['idGrupo' => '4', 'idUsuario' => '92'],
            ['idGrupo' => '4', 'idUsuario' => '93'],
            ['idGrupo' => '4', 'idUsuario' => '94'],
            ['idGrupo' => '4', 'idUsuario' => '95'],
            ['idGrupo' => '4', 'idUsuario' => '96'],
            ['idGrupo' => '4', 'idUsuario' => '97'],
            ['idGrupo' => '4', 'idUsuario' => '98'],
            ['idGrupo' => '4', 'idUsuario' => '99'],
            ['idGrupo' => '4', 'idUsuario' => '100'],
            ['idGrupo' => '4', 'idUsuario' => '101'],
            ['idGrupo' => '4', 'idUsuario' => '102'],
            ['idGrupo' => '4', 'idUsuario' => '103'],
            ['idGrupo' => '4', 'idUsuario' => '104'],
            ['idGrupo' => '4', 'idUsuario' => '105'],
            ['idGrupo' => '4', 'idUsuario' => '106'],
            ['idGrupo' => '4', 'idUsuario' => '107'],
            ['idGrupo' => '4', 'idUsuario' => '108'],
            ['idGrupo' => '4', 'idUsuario' => '109'],
            ['idGrupo' => '4', 'idUsuario' => '110'],
            ['idGrupo' => '4', 'idUsuario' => '111'],
            ['idGrupo' => '4', 'idUsuario' => '112'],
            ['idGrupo' => '4', 'idUsuario' => '113'],
            ['idGrupo' => '5', 'idUsuario' => '114'],
            ['idGrupo' => '5', 'idUsuario' => '115'],
            ['idGrupo' => '6', 'idUsuario' => '20'],
            ['idGrupo' => '6', 'idUsuario' => '14'],
            ['idGrupo' => '6', 'idUsuario' => '87'],
            ['idGrupo' => '6', 'idUsuario' => '88'],
            ['idGrupo' => '6', 'idUsuario' => '89'],
            ['idGrupo' => '6', 'idUsuario' => '90'],
            ['idGrupo' => '6', 'idUsuario' => '91'],
            ['idGrupo' => '6', 'idUsuario' => '92'],
            ['idGrupo' => '6', 'idUsuario' => '93'],
            ['idGrupo' => '6', 'idUsuario' => '94'],
            ['idGrupo' => '6', 'idUsuario' => '95'],
            ['idGrupo' => '6', 'idUsuario' => '96'],
            ['idGrupo' => '6', 'idUsuario' => '97'],
            ['idGrupo' => '6', 'idUsuario' => '98'],
            ['idGrupo' => '6', 'idUsuario' => '99'],
            ['idGrupo' => '6', 'idUsuario' => '100'],
            ['idGrupo' => '6', 'idUsuario' => '101'],
            ['idGrupo' => '6', 'idUsuario' => '102'],
            ['idGrupo' => '6', 'idUsuario' => '103'],
            ['idGrupo' => '6', 'idUsuario' => '104'],
            ['idGrupo' => '6', 'idUsuario' => '105'],
            ['idGrupo' => '6', 'idUsuario' => '106'],
            ['idGrupo' => '6', 'idUsuario' => '107'],
            ['idGrupo' => '6', 'idUsuario' => '108'],
            ['idGrupo' => '6', 'idUsuario' => '109'],
            ['idGrupo' => '6', 'idUsuario' => '110'],
            ['idGrupo' => '6', 'idUsuario' => '111'],
            ['idGrupo' => '6', 'idUsuario' => '112'],
            ['idGrupo' => '6', 'idUsuario' => '113'],
            ['idGrupo' => '6', 'idUsuario' => '114'],
            ['idGrupo' => '6', 'idUsuario' => '115'],
            ['idGrupo' => '7', 'idUsuario' => '16'],
            ['idGrupo' => '7', 'idUsuario' => '18'],
            ['idGrupo' => '7', 'idUsuario' => '72'],
            ['idGrupo' => '7', 'idUsuario' => '47'],
        ];

        foreach($grupoMiembros as $grupoMiembro){
            $grupoMiembro1 = SYS_grupoMiembros::create($grupoMiembro);
        };
    }
}