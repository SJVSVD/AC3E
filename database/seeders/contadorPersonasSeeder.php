<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TIE_contadorPersonas;

class contadorPersonasSeeder extends Seeder
{
    public function run()
    { 
        $contadoresPersonas = [
            ['idTienda' => '1', 'fecha' => '11-06-2023', 'tipoEntrada' => 'Entrada', 'h1' => 1, 'h2' => 5, 'h3' => 10, 'h4' => 2, 'h5' => 0, 'h6' => 0, 'h7' => 0, 'h8' => 0, 'h9' => 0, 'h10' => 10, 'h11' => 0, 'h12' => 0, 'h13' => 0, 'h14' => 0, 'h15' => 0, 'h16' => 0, 'h17' => 0, 'h18' => 0, 'h19' => 0, 'h20' => 0, 'h21' => 0, 'h22' => 0, 'h23' => 0, 'h24' => 0,],
            ['idTienda' => '1', 'fecha' => '11-06-2023', 'tipoEntrada' => 'Salida', 'h1' => 1, 'h2' => 3, 'h3' => 10, 'h4' => 4, 'h5' => 0, 'h6' => 0, 'h7' => 0, 'h8' => 0, 'h9' => 0, 'h10' => 20, 'h11' => 0, 'h12' => 0, 'h13' => 0, 'h14' => 0, 'h15' => 0, 'h16' => 0, 'h17' => 0, 'h18' => 0, 'h19' => 0, 'h20' => 0, 'h21' => 0, 'h22' => 0, 'h23' => 0, 'h24' => 0,],
            ['idTienda' => '1', 'fecha' => '12-06-2023', 'tipoEntrada' => 'Entrada', 'h1' => 1, 'h2' => 5, 'h3' => 10, 'h4' => 2, 'h5' => 0, 'h6' => 0, 'h7' => 0, 'h8' => 0, 'h9' => 0, 'h10' => 30, 'h11' => 0, 'h12' => 0, 'h13' => 0, 'h14' => 0, 'h15' => 0, 'h16' => 0, 'h17' => 0, 'h18' => 0, 'h19' => 0, 'h20' => 0, 'h21' => 0, 'h22' => 0, 'h23' => 0, 'h24' => 0,],
            ['idTienda' => '1', 'fecha' => '12-06-2023', 'tipoEntrada' => 'Salida', 'h1' => 1, 'h2' => 3, 'h3' => 10, 'h4' => 4, 'h5' => 0, 'h6' => 0, 'h7' => 0, 'h8' => 0, 'h9' => 0, 'h10' => 40, 'h11' => 0, 'h12' => 0, 'h13' => 0, 'h14' => 0, 'h15' => 0, 'h16' => 0, 'h17' => 0, 'h18' => 0, 'h19' => 0, 'h20' => 0, 'h21' => 0, 'h22' => 0, 'h23' => 0, 'h24' => 0,],
            ['idTienda' => '1', 'fecha' => '13-06-2023', 'tipoEntrada' => 'Entrada', 'h1' => 1, 'h2' => 5, 'h3' => 10, 'h4' => 2, 'h5' => 0, 'h6' => 0, 'h7' => 0, 'h8' => 0, 'h9' => 0, 'h10' => 50, 'h11' => 0, 'h12' => 0, 'h13' => 0, 'h14' => 0, 'h15' => 0, 'h16' => 0, 'h17' => 0, 'h18' => 0, 'h19' => 0, 'h20' => 0, 'h21' => 0, 'h22' => 0, 'h23' => 0, 'h24' => 0,],
            ['idTienda' => '1', 'fecha' => '13-06-2023', 'tipoEntrada' => 'Salida', 'h1' => 1, 'h2' => 3, 'h3' => 10, 'h4' => 4, 'h5' => 0, 'h6' => 0, 'h7' => 0, 'h8' => 0, 'h9' => 0, 'h10' => 60, 'h11' => 0, 'h12' => 0, 'h13' => 0, 'h14' => 0, 'h15' => 0, 'h16' => 0, 'h17' => 0, 'h18' => 0, 'h19' => 0, 'h20' => 0, 'h21' => 0, 'h22' => 0, 'h23' => 0, 'h24' => 0,],
        ];

        foreach($contadoresPersonas as $contadorPersonas){
            $contadorPersonas1 = TIE_contadorPersonas::create($contadorPersonas);
        };
    }
}