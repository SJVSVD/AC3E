<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_autorizadores;

class autorizadoresSeeder extends Seeder
{
    public function run()
    {    
        $autorizadores = [
            // FACTURAS
            ['identificador' => 'IGA', 'idUsuario' => '35', 'levelName' => 'facturas', 'level' => false],
            ['identificador' => 'IGS', 'idUsuario' => '36', 'levelName' => 'facturas', 'level' => false],
            ['identificador' => 'MMB', 'idUsuario' => '53', 'levelName' => 'facturas', 'level' => false],
            ['identificador' => 'PBA', 'idUsuario' => '69', 'levelName' => 'facturas', 'level' => false],
            ['identificador' => 'CCA', 'idUsuario' => '10', 'levelName' => 'facturas', 'level' => false],
            ['identificador' => 'VCA', 'idUsuario' => '116', 'levelName' => 'facturas', 'level' => false],
            // ['identificador' => 'PS?', 'idUsuario' => '', 'levelName' => 'facturas', 'level' => false],
            ['identificador' => 'MBG', 'idUsuario' => '49', 'levelName' => 'facturas', 'level' => false],
            ['identificador' => 'BUV', 'idUsuario' => '6', 'levelName' => 'facturas', 'level' => false],
            ['identificador' => 'DLB', 'idUsuario' => '15', 'levelName' => 'facturas', 'level' => false],
            ['identificador' => 'EMC', 'idUsuario' => '19', 'levelName' => 'facturas', 'level' => true],
            ['identificador' => 'FDM', 'idUsuario' => '24', 'levelName' => 'facturas', 'level' => true],
            ['identificador' => 'GAL', 'idUsuario' => '32', 'levelName' => 'facturas', 'level' => true],
            ['identificador' => 'BDP', 'idUsuario' => '5', 'levelName' => 'facturas', 'level' => true],

            // FONDOS FIJOS
            ['identificador' => 'IGA', 'idUsuario' => '35', 'levelName' => 'fondos fijos', 'level' => false],
            ['identificador' => 'IGS', 'idUsuario' => '36', 'levelName' => 'fondos fijos', 'level' => false],
            ['identificador' => 'MMB', 'idUsuario' => '53', 'levelName' => 'fondos fijos', 'level' => false],
            ['identificador' => 'PBA', 'idUsuario' => '69', 'levelName' => 'fondos fijos', 'level' => false],
            ['identificador' => 'CCA', 'idUsuario' => '10', 'levelName' => 'fondos fijos', 'level' => false],
            ['identificador' => 'VCA', 'idUsuario' => '116', 'levelName' => 'fondos fijos', 'level' => false],
            // ['identificador' => 'PS?', 'idUsuario' => '', 'levelName' => 'fondos fijos', 'level' => false],
            ['identificador' => 'MBG', 'idUsuario' => '49', 'levelName' => 'fondos fijos', 'level' => false],
            ['identificador' => 'BUV', 'idUsuario' => '6', 'levelName' => 'fondos fijos', 'level' => false],
            ['identificador' => 'DLB', 'idUsuario' => '15', 'levelName' => 'fondos fijos', 'level' => false],
            ['identificador' => 'EMC', 'idUsuario' => '19', 'levelName' => 'fondos fijos', 'level' => true],
            ['identificador' => 'FDM', 'idUsuario' => '24', 'levelName' => 'fondos fijos', 'level' => true],
            ['identificador' => 'GAL', 'idUsuario' => '32', 'levelName' => 'fondos fijos', 'level' => true],
            ['identificador' => 'BDP', 'idUsuario' => '5', 'levelName' => 'fondos fijos', 'level' => true],

            // ROBOS
            ['identificador' => 'IGA', 'idUsuario' => '35', 'levelName' => 'robos', 'level' => false],
            ['identificador' => 'IGS', 'idUsuario' => '36', 'levelName' => 'robos', 'level' => false],
            ['identificador' => 'MMB', 'idUsuario' => '53', 'levelName' => 'robos', 'level' => false],
            ['identificador' => 'PBA', 'idUsuario' => '69', 'levelName' => 'robos', 'level' => false],
            ['identificador' => 'CCA', 'idUsuario' => '10', 'levelName' => 'robos', 'level' => false],
            ['identificador' => 'VCA', 'idUsuario' => '116', 'levelName' => 'robos', 'level' => false],
            // ['identificador' => 'PS?', 'idUsuario' => '', 'levelName' => 'robos', 'level' => false],
            ['identificador' => 'MBG', 'idUsuario' => '49', 'levelName' => 'robos', 'level' => false],
            ['identificador' => 'BUV', 'idUsuario' => '6', 'levelName' => 'robos', 'level' => false],
            ['identificador' => 'DLB', 'idUsuario' => '15', 'levelName' => 'robos', 'level' => false],
            ['identificador' => 'EMC', 'idUsuario' => '19', 'levelName' => 'robos', 'level' => true],
            ['identificador' => 'FDM', 'idUsuario' => '24', 'levelName' => 'robos', 'level' => true],
            ['identificador' => 'GAL', 'idUsuario' => '32', 'levelName' => 'robos', 'level' => true],
            ['identificador' => 'BDP', 'idUsuario' => '5', 'levelName' => 'robos', 'level' => true],
        ];

        foreach($autorizadores as $autorizador){
            $autorizador1 = SYS_autorizadores::create($autorizador);
        };
    }
}