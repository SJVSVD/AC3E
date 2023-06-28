<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TIE_metasTienda;

class metasTiendaSeeder extends Seeder
{
    public function run()
    { 
        $metasTienda = [
            ['idTienda' => '1', 'meta_total' => 100000000, 'meta_carteras' => 20000000, 'meta_marroquineria' => 20000000, 'meta_calzado' => 20000000, 'meta_perfume' => 10000000, 'meta_vestimenta' => 30000000, 'año' => 2023, 'mes' => 06,],
            ['idTienda' => '1', 'meta_total' => 100000000, 'meta_carteras' => 20000000, 'meta_marroquineria' => 20000000, 'meta_calzado' => 20000000, 'meta_perfume' => 10000000, 'meta_vestimenta' => 30000000, 'año' => 2023, 'mes' => 07,],
            ['idTienda' => '1', 'meta_total' => 100000000, 'meta_carteras' => 20000000, 'meta_marroquineria' => 20000000, 'meta_calzado' => 20000000, 'meta_perfume' => 10000000, 'meta_vestimenta' => 30000000, 'año' => 2023, 'mes' => 05,],
            ['idTienda' => '2', 'meta_total' => 100000000, 'meta_carteras' => 20000000, 'meta_marroquineria' => 20000000, 'meta_calzado' => 20000000, 'meta_perfume' => 10000000, 'meta_vestimenta' => 30000000, 'año' => 2023, 'mes' => 06,],
            ['idTienda' => '2', 'meta_total' => 100000000, 'meta_carteras' => 20000000, 'meta_marroquineria' => 20000000, 'meta_calzado' => 20000000, 'meta_perfume' => 10000000, 'meta_vestimenta' => 30000000, 'año' => 2023, 'mes' => 07,],
            ['idTienda' => '2', 'meta_total' => 100000000, 'meta_carteras' => 20000000, 'meta_marroquineria' => 20000000, 'meta_calzado' => 20000000, 'meta_perfume' => 10000000, 'meta_vestimenta' => 30000000, 'año' => 2023, 'mes' => 05,],
        ];

        foreach($metasTienda as $metaTienda){
            $metaTienda1 = TIE_metasTienda::create($metaTienda);
        };
    }
}