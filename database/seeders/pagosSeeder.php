<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CTB_pagos;

class pagosSeeder extends Seeder
{
    public function run()
    { 
        $pagos = [
            ['idFactura' => '76203299-651203', 'fechaPago' => '2022-12-01', 'montoPagado' => '10000', 'usuarioPago' => 'Juan Rodriguez'],
            ['idFactura' => '90635000-948930364', 'fechaPago' => '2022-12-01', 'montoPagado' => '12000', 'usuarioPago' => 'Juan Rodriguez'],
            ['idFactura' => '76203299-651203', 'fechaPago' => '2022-12-01', 'montoPagado' => '12500', 'usuarioPago' => 'Fernando Villegas'],
            ['idFactura' => '90635000-948930362', 'fechaPago' => '2022-12-01', 'montoPagado' => '15000', 'usuarioPago' => 'Iván Rojas'],
            ['idFactura' => '90635000-948930362', 'fechaPago' => '2022-12-01', 'montoPagado' => '120000', 'usuarioPago' => 'Iván Rojas'],
            ['idFactura' => '90635000-948930362', 'fechaPago' => '2022-12-01', 'montoPagado' => '14000', 'usuarioPago' => 'Manuel Astudillo'],
            ['idFactura' => '90635000-948930362', 'fechaPago' => '2022-12-01', 'montoPagado' => '105000', 'usuarioPago' => 'Fernando Villegas'],
            ['idFactura' => '90635000-948930363', 'fechaPago' => '2022-12-01', 'montoPagado' => '120000', 'usuarioPago' => 'Juan Rodriguez'],
            ['idFactura' => '90635000-948930363', 'fechaPago' => '2022-12-01', 'montoPagado' => '110500', 'usuarioPago' => 'Iván Rojas'],
            ['idFactura' => '90635000-948930363', 'fechaPago' => '2022-12-01', 'montoPagado' => '235000', 'usuarioPago' => 'Guillermo Vargas'],
        ];

        foreach($pagos as $pago){
            $pago1 = CTB_pagos::create($pago);
        };
    }
}