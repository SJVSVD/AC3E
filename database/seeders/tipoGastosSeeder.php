<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CTB_tipoGasto;

class tipoGastosSeeder extends Seeder
{
    public function run()
    { 
        $tipoGastos = [
            ['nombre' => 'Útiles de Aseo', 'detalle' => '', 'cuentaContable' => '6117002'],
            ['nombre' => 'Útiles de escritorio', 'detalle' => '', 'cuentaContable' => '6117003'],
            ['nombre' => 'Alimentación & viaticos', 'detalle' => '', 'cuentaContable' => '6111204'],
            ['nombre' => 'Notarios', 'detalle' => '', 'cuentaContable' => '6111304'],
            ['nombre' => 'Correspondencia', 'detalle' => '', 'cuentaContable' => '6118005'],
            ['nombre' => 'Gsts de representacion', 'detalle' => '', 'cuentaContable' => '6111209'],
            ['nombre' => 'Movil & Acerc. del personal', 'detalle' => '', 'cuentaContable' => '6111208'],
            ['nombre' => 'Public & elemserv promoc', 'detalle' => '', 'cuentaContable' => '6111502'],
            ['nombre' => 'Mant. Maquinas', 'detalle' => '', 'cuentaContable' => '6112005'],
            ['nombre' => 'Combustible & gas', 'detalle' => '', 'cuentaContable' => '6112006'],
            ['nombre' => 'Estacionamiento', 'detalle' => '', 'cuentaContable' => '6112015'],
            ['nombre' => 'Mant. tiendas oficinas', 'detalle' => '', 'cuentaContable' => '6114101'],
            ['nombre' => 'Fletes transferencia', 'detalle' => '', 'cuentaContable' => '6116001'],
            ['nombre' => 'Infor insumos', 'detalle' => '', 'cuentaContable' => '6115102'],
        ];

        foreach($tipoGastos as $tipoGasto){
            $tipoGasto1 = CTB_tipoGasto::create($tipoGasto);
        };
    }
}