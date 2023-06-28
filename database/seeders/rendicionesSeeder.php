<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class rendicionesSeeder extends Seeder
{
    public function run()
    {
        $rendiciones = [
            ['estado' => 'PendRen', 'folio' => '1', 'colaborador' => 'Sebastian Jerez', 'montoSoli' => '10000', 'idUsuarioSoli' => '1', 'fechaSoli' => '2023-02-02', 'detalleSoli' => 'Almuerzos', 'idTipoMoneda' => '1', 'pendiente' => true],
            ['estado' => 'PendAutRen', 'folio' => '2', 'colaborador' => 'Bastian Contreras', 'montoSoli' => '20000', 'idUsuarioSoli' => '2', 'fechaSoli' => '2023-02-01', 'detalleSoli' => 'Viaje', 'idTipoMoneda' => '1', 'pendiente' => true],
            ['estado' => 'PendAutPag', 'folio' => '3', 'colaborador' => 'Marcos Aballay', 'montoSoli' => '15000', 'idUsuarioSoli' => '2', 'fechaSoli' => '2023-02-03', 'detalleSoli' => 'Arreglos varios', 'idTipoMoneda' => '1', 'pendiente' => true],
            ['estado' => 'Rendida', 'folio' => '4', 'colaborador' => 'Sebastian Jerez', 'montoSoli' => '7500', 'idUsuarioSoli' => '1', 'fechaSoli' => '2023-02-01', 'detalleSoli' => 'Arreglos varios', 'idTipoMoneda' => '1', 'pendiente' => true],
            ['estado' => 'PendRen', 'folio' => '5', 'colaborador' => 'Bastian Contreras', 'montoSoli' => '25000', 'idUsuarioSoli' => '1', 'fechaSoli' => '2023-02-02', 'detalleSoli' => 'Viaje', 'idTipoMoneda' => '1', 'pendiente' => true],
            ['estado' => 'PendAutRen', 'folio' => '6', 'colaborador' => 'Sebastian Jerez', 'montoSoli' => '30000', 'idUsuarioSoli' => '3', 'fechaSoli' => '2023-02-02', 'detalleSoli' => 'Reparacion', 'idTipoMoneda' => '1', 'pendiente' => true],
            ['estado' => 'PendAutPag', 'folio' => '7', 'colaborador' => 'Marcos Aballay', 'montoSoli' => '15000', 'idUsuarioSoli' => '4', 'fechaSoli' => '2023-02-03', 'detalleSoli' => 'Pagos', 'idTipoMoneda' => '1', 'pendiente' => true],
            ['estado' => 'Rendida', 'folio' => '8', 'colaborador' => 'Marcos Aballay', 'montoSoli' => '10000', 'idUsuarioSoli' => '2', 'fechaSoli' => '2023-02-01', 'detalleSoli' => 'Pizza', 'idTipoMoneda' => '1', 'pendiente' => true],
            ['estado' => 'PendRen', 'folio' => '9', 'colaborador' => 'Sebastian Jerez', 'montoSoli' => '20000', 'idUsuarioSoli' => '3', 'fechaSoli' => '2023-02-05', 'detalleSoli' => 'Almuerzos', 'idTipoMoneda' => '1', 'pendiente' => true],
            ['estado' => 'PendRen', 'folio' => '10', 'colaborador' => 'Bastian Contreras', 'montoSoli' => '12500', 'idUsuarioSoli' => '4', 'fechaSoli' => '2023-02-04', 'detalleSoli' => 'Almuerzos', 'idTipoMoneda' => '1', 'pendiente' => true],
        ];

        foreach($rendiciones as $rendicion){
            DB::table('CTB_rendicion') -> insert([$rendicion]);
        };
    }
}