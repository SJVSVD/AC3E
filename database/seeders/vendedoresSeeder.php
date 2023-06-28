<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TIE_vendedores;

class vendedoresSeeder extends Seeder
{
    public function run()
    {
        $vendedores = [
            ['codigoVendedor' => '481', 'rut' => '20465789-1', 'nombre1' => 'Temporal1', 'nombre2' => 'Temporal1', 'apellido1' => 'Temporal1', 'apellido2' => 'Temporal1', 'idEmpresa' => '1',],
            ['codigoVendedor' => '635', 'rut' => '20465789-2', 'nombre1' => 'Temporal2', 'nombre2' => 'Temporal2', 'apellido1' => 'Temporal2', 'apellido2' => 'Temporal2', 'idEmpresa' => '1',],
            ['codigoVendedor' => '598', 'rut' => '20465789-3', 'nombre1' => 'Temporal3', 'nombre2' => 'Temporal3', 'apellido1' => 'Temporal3', 'apellido2' => 'Temporal3', 'idEmpresa' => '1',],
            ['codigoVendedor' => '631', 'rut' => '20465789-4', 'nombre1' => 'Temporal4', 'nombre2' => 'Temporal4', 'apellido1' => 'Temporal4', 'apellido2' => 'Temporal4', 'idEmpresa' => '1',],
            ['codigoVendedor' => '642', 'rut' => '20465789-5', 'nombre1' => 'Temporal5', 'nombre2' => 'Temporal5', 'apellido1' => 'Temporal5', 'apellido2' => 'Temporal5', 'idEmpresa' => '1',],
            ['codigoVendedor' => '302', 'rut' => '20465789-6', 'nombre1' => 'Temporal6', 'nombre2' => 'Temporal6', 'apellido1' => 'Temporal6', 'apellido2' => 'Temporal6', 'idEmpresa' => '1',],
            ['codigoVendedor' => '627', 'rut' => '20465789-7', 'nombre1' => 'Temporal7', 'nombre2' => 'Temporal7', 'apellido1' => 'Temporal7', 'apellido2' => 'Temporal7', 'idEmpresa' => '1',],
            ['codigoVendedor' => '66', 'rut' => '20465789-8', 'nombre1' => 'Temporal8', 'nombre2' => 'Temporal8', 'apellido1' => 'Temporal8', 'apellido2' => 'Temporal8', 'idEmpresa' => '1',],
        ];

        foreach($vendedores as $vendedor){
            TIE_vendedores::create($vendedor);
        };
    }
}