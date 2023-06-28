<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TIE_metasColaborador;

class metasColaboradorSeeder extends Seeder
{
    public function run()
    { 
        $metasColaborador = [
            ['idTienda' => '1', 'idVendedor' => '1', 'meta_total' => 350000, 'meta_carteras' => 150000, 'meta_marroquineria' => 50000, 'meta_calzado' => 50000, 'meta_perfume' => 50000, 'meta_vestimenta' => 50000, 'año' => 2023, 'mes' => 06],
            ['idTienda' => '1', 'idVendedor' => '2', 'meta_total' => 500000, 'meta_carteras' => 350000, 'meta_marroquineria' => 50000, 'meta_calzado' => 50000, 'meta_perfume' => 0, 'meta_vestimenta' => 50000, 'año' => 2023, 'mes' => 06],
            ['idTienda' => '1', 'idVendedor' => '1', 'meta_total' => 350000, 'meta_carteras' => 150000, 'meta_marroquineria' => 50000, 'meta_calzado' => 50000, 'meta_perfume' => 50000, 'meta_vestimenta' => 50000, 'año' => 2023, 'mes' => 07],
            ['idTienda' => '1', 'idVendedor' => '2', 'meta_total' => 500000, 'meta_carteras' => 350000, 'meta_marroquineria' => 50000, 'meta_calzado' => 50000, 'meta_perfume' => 0, 'meta_vestimenta' => 50000, 'año' => 2023, 'mes' => 05],
            ['idTienda' => '2', 'idVendedor' => '1', 'meta_total' => 350000, 'meta_carteras' => 150000, 'meta_marroquineria' => 50000, 'meta_calzado' => 50000, 'meta_perfume' => 50000, 'meta_vestimenta' => 50000, 'año' => 2023, 'mes' => 06],
            ['idTienda' => '2', 'idVendedor' => '2', 'meta_total' => 500000, 'meta_carteras' => 350000, 'meta_marroquineria' => 50000, 'meta_calzado' => 50000, 'meta_perfume' => 0, 'meta_vestimenta' => 50000, 'año' => 2023, 'mes' => 06],
            ['idTienda' => '2', 'idVendedor' => '1', 'meta_total' => 350000, 'meta_carteras' => 150000, 'meta_marroquineria' => 50000, 'meta_calzado' => 50000, 'meta_perfume' => 50000, 'meta_vestimenta' => 50000, 'año' => 2023, 'mes' => 07],
            ['idTienda' => '2', 'idVendedor' => '2', 'meta_total' => 500000, 'meta_carteras' => 350000, 'meta_marroquineria' => 50000, 'meta_calzado' => 50000, 'meta_perfume' => 0, 'meta_vestimenta' => 50000, 'año' => 2023, 'mes' => 05],
        ];

        foreach($metasColaborador as $metaColaborador){
            $metaColaborador1 = TIE_metasColaborador::create($metaColaborador);
        };
    }
}