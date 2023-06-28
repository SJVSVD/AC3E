<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CTB_tipoDocFactura;

class tipoDocFacturasSeeder extends Seeder
{
    public function run()
    { 
        $tiposDocFactura = [
            ['id' => '30' , 'name' => 'Factura'],
            ['id' => '32' , 'name' => 'Factura de ventas y servicios no afectos o exentos de IVA'],
            ['id' => '33' , 'name' => 'Factura electrónica'],
            ['id' => '34' , 'name' => 'Factura no afecta o exenta electrónica'],
            ['id' => '35' , 'name' => 'Boleta'],
            ['id' => '38' , 'name' => 'Boleta exenta'],
            ['id' => '39' , 'name' => 'Boleta electrónica'],
            ['id' => '40' , 'name' => 'Liquidación factura'],
            ['id' => '41' , 'name' => 'Boleta exenta electrónica'],
            ['id' => '43' , 'name' => 'Liquidación factura electrónica'],
            ['id' => '45' , 'name' => 'Factura de compra'],
            ['id' => '46' , 'name' => 'Factura de compra electrónica'],
            ['id' => '48' , 'name' => 'Pago electrónico'],
            ['id' => '50' , 'name' => 'Manual de despacho'],
            ['id' => '52' , 'name' => 'Manual de despacho electrónica'],
            ['id' => '55' , 'name' => 'Nota de débito'],
            ['id' => '56' , 'name' => 'Nota de débito electrónica'],
            ['id' => '60' , 'name' => 'Nota de crédito'],
            ['id' => '61' , 'name' => 'Nota de crédito electrónica'],
            ['id' => '103' , 'name' => 'Liquidación'],
            ['id' => '110' , 'name' => 'Factura de exportación electrónica'],
            ['id' => '111' , 'name' => 'Nota de débito de exportación electrónica'],
            ['id' => '112' , 'name' => 'Nota de crédito de exportación electrónica'],
        ];

        foreach($tiposDocFactura as $tipoDocFactura){
            $tipoDocFactura1 = CTB_tipoDocFactura::create($tipoDocFactura);
        };
    }
}