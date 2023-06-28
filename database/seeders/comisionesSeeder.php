<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ETC_comisiones;

class comisionesSeeder extends Seeder
{
    public function run()
    {
        $comisiones = [
            ['idUsuario' => '80', 'estado' => true, 'rut' => '21153244-0', 'nombre' => 'Comisión Falabella', 'correo' => 'comision.amphora@falabella.cl', 'telefono' => '+56931415526'],
            ['idUsuario' => '80', 'estado' => true, 'rut' => '21153244-0', 'nombre' => 'Comisión Ripley', 'correo' => 'comision.amphora@ripley.cl', 'telefono' => '+56931415526'],
            ['idUsuario' => '80', 'estado' => true, 'rut' => '21153244-0', 'nombre' => 'Comisión La Polar', 'correo' => 'comision.amphoram@lapolar.cl', 'telefono' => '+56931415526'],

        ];

        foreach($comisiones as $comision){
            $comision1 = ETC_comisiones::create($comision);
        };
    }
}