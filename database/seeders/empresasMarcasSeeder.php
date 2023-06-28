<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_empresa_marca;

class empresasMarcasSeeder extends Seeder
{
    public function run()
    {
        $empresasMarcas = [
            ['nombre' => 'Interandina de Comercio Limitada - Amphora', 'idEmpresa' => '1', 'idMarca' => '1'],
            ['nombre' => 'Interandina de Comercio Limitada - Ziol', 'idEmpresa' => '1', 'idMarca' => '3'],
            ['nombre' => 'Retail Sudamericano Limitada - Scalpers', 'idEmpresa' => '2', 'idMarca' => '2'],
        ];

        foreach($empresasMarcas as $empresaMarca){
            $empresaMarca1 = SYS_empresa_marca::create($empresaMarca);
        };
    }
}