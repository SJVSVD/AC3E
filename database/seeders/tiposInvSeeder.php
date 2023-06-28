<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_tipoInv;

class tiposInvSeeder extends Seeder
{
    public function run()
    {
        $tiposInv = [
            ['nombre' => 'TEMPORAL', 'tipo' => 'Completo', 'idEmpresaMarca' => '1'],
            ['nombre' => 'TEMPORAL1', 'tipo' => 'Incompleto', 'idEmpresaMarca' => '1'],
            ['nombre' => 'TEMPORAL2', 'tipo' => 'Completo', 'idEmpresaMarca' => '2'],
            ['nombre' => 'TEMPORAL3', 'tipo' => 'Parcial', 'idEmpresaMarca' => '2'],
            ['nombre' => 'TEMPORAL4', 'tipo' => 'Incompleto', 'idEmpresaMarca' => '2'],
            ['nombre' => 'TEMPORAL5', 'tipo' => 'Completo', 'idEmpresaMarca' => '3'],
            ['nombre' => 'TEMPORAL6', 'tipo' => 'Completo', 'idEmpresaMarca' => '3'],
            ['nombre' => 'TEMPORAL7', 'tipo' => 'Completo', 'idEmpresaMarca' => '1'],
        ];

        foreach($tiposInv as $tipoInv){
            $tipoInv1 = SYS_tipoInv::create($tipoInv);
        };
    }
}