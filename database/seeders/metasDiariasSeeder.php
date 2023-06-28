<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TIE_metasDiarias;

class metasDiariasSeeder extends Seeder
{
    public function run()
    { 
        $metasDiarias = [
            ['idTienda' => '1', 'meta_total' => 850000, 'meta_carteras' => 500000, 'meta_marroquineria' => 100000, 'meta_calzado' => 100000, 'meta_perfume' => 50000, 'meta_vestimenta' => 100000, 'año' => 2023, 'mes' => 06, 'dia' => 12,],
            ['idTienda' => '1', 'meta_total' => 850000, 'meta_carteras' => 500000, 'meta_marroquineria' => 100000, 'meta_calzado' => 100000, 'meta_perfume' => 50000, 'meta_vestimenta' => 100000, 'año' => 2023, 'mes' => 05, 'dia' => 10,],
            ['idTienda' => '1', 'meta_total' => 850000, 'meta_carteras' => 500000, 'meta_marroquineria' => 100000, 'meta_calzado' => 100000, 'meta_perfume' => 50000, 'meta_vestimenta' => 100000, 'año' => 2023, 'mes' => 07, 'dia' => 15,],
            ['idTienda' => '2', 'meta_total' => 850000, 'meta_carteras' => 500000, 'meta_marroquineria' => 100000, 'meta_calzado' => 100000, 'meta_perfume' => 50000, 'meta_vestimenta' => 100000, 'año' => 2023, 'mes' => 06, 'dia' => 12,],
            ['idTienda' => '2', 'meta_total' => 850000, 'meta_carteras' => 500000, 'meta_marroquineria' => 100000, 'meta_calzado' => 100000, 'meta_perfume' => 50000, 'meta_vestimenta' => 100000, 'año' => 2023, 'mes' => 05, 'dia' => 10,],
            ['idTienda' => '2', 'meta_total' => 850000, 'meta_carteras' => 500000, 'meta_marroquineria' => 100000, 'meta_calzado' => 100000, 'meta_perfume' => 50000, 'meta_vestimenta' => 100000, 'año' => 2023, 'mes' => 07, 'dia' => 15,],
        ];

        foreach($metasDiarias as $metaDiaria){
            $metaDiaria1 = TIE_metasDiarias::create($metaDiaria);
        };
    }
}