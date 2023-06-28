<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_monedas;

class monedasSeeder extends Seeder
{
    public function run()
    { 
        $monedas = [
            ['nombre' => 'Peso Chileno', 'abreviacion' => 'CLP'],
            ['nombre' => 'Euro', 'abreviacion' => 'EUR'],
            ['nombre' => 'Dólar Americano', 'abreviacion' => 'USD'],

        ];

        foreach($monedas as $moneda){
            $moneda1 = SYS_monedas::create($moneda);
        };
    }
}