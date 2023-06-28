<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CTB_tipoDocumento;

class tiposDocFofiSeeder extends Seeder
{
    public function run()
    { 
        $tiposDocFofi = [
            ['nombre' => 'Sin documento', 'detalle' => '', 'rut' => false],
            ['nombre' => 'Boleta', 'detalle' => '', 'rut' => false],
            ['nombre' => 'Factura', 'detalle' => '', 'rut' => true],
            ['nombre' => 'Boleta honorario', 'detalle' => '', 'rut' => true],
            ['nombre' => 'Nota de credito', 'detalle' => '', 'rut' => true],

        ];

        foreach($tiposDocFofi as $tipoDocFofi){
            $tipoDocFofi1 = CTB_tipoDocumento::create($tipoDocFofi);
        };
    }
}