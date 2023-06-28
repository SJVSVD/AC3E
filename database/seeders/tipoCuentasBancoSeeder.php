<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_tiposCuentaBanco;

class tipoCuentasBancoSeeder extends Seeder
{
    public function run()
    {
        $tiposCuentasBanco = [
            ['nombre' => 'Corriente'],
            ['nombre' => 'Vista'],
            ['nombre' => 'Ahorro'],
        ];

        foreach($tiposCuentasBanco as $tipoCuentaBanco){
            $tipoCuentaBanco1 = SYS_tiposCuentaBanco::create($tipoCuentaBanco);
        };
    }
}