<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\CTB_gasto;

class gastosSeeder extends Seeder
{
    public function run()
    { 
        $gastos = [
            ['idTipoGasto' => '1' , 'nombre' => 'Útiles de Aseo', 'detalle' => ''],
            ['idTipoGasto' => '2' , 'nombre' => 'Útiles de escritorio', 'detalle' => ''],
            ['idTipoGasto' => '3' , 'nombre' => 'Desayuno', 'detalle' => ''],
            ['idTipoGasto' => '3' , 'nombre' => 'Almuerzo', 'detalle' => ''],
            ['idTipoGasto' => '3' , 'nombre' => 'Cena', 'detalle' => ''],
            ['idTipoGasto' => '4' , 'nombre' => 'Notarios', 'detalle' => ''],
            ['idTipoGasto' => '5' , 'nombre' => 'Correspondencia', 'detalle' => ''],
            ['idTipoGasto' => '6' , 'nombre' => 'Regalos', 'detalle' => ''],
            ['idTipoGasto' => '6' , 'nombre' => 'Pasaje Avion', 'detalle' => ''],
            ['idTipoGasto' => '6' , 'nombre' => 'Peajes', 'detalle' => ''],
            ['idTipoGasto' => '7' , 'nombre' => 'Sobretiempo', 'detalle' => ''],
            ['idTipoGasto' => '7' , 'nombre' => 'Bus', 'detalle' => ''],
            ['idTipoGasto' => '7' , 'nombre' => 'Colectivo', 'detalle' => ''],
            ['idTipoGasto' => '7' , 'nombre' => 'Micro', 'detalle' => ''],
            ['idTipoGasto' => '8' , 'nombre' => 'Vitrinas', 'detalle' => ''],
            ['idTipoGasto' => '8' , 'nombre' => 'Inversion MKT', 'detalle' => ''],
            ['idTipoGasto' => '8' , 'nombre' => 'Graficas', 'detalle' => ''],
            ['idTipoGasto' => '8' , 'nombre' => 'Pop', 'detalle' => ''],
            ['idTipoGasto' => '9' , 'nombre' => 'Automoviles', 'detalle' => ''],
            ['idTipoGasto' => '9' , 'nombre' => 'Maquinaria', 'detalle' => ''],
            ['idTipoGasto' => '10' , 'nombre' => 'Combustible & gas', 'detalle' => ''],
            ['idTipoGasto' => '11' , 'nombre' => 'Estacionamiento', 'detalle' => ''],
            ['idTipoGasto' => '12' , 'nombre' => 'Insumos', 'detalle' => ''],
            ['idTipoGasto' => '12' , 'nombre' => 'Reparacion', 'detalle' => ''],
            ['idTipoGasto' => '13' , 'nombre' => 'Encomiendas', 'detalle' => ''],
            ['idTipoGasto' => '14' , 'nombre' => 'Hardware', 'detalle' => ''],
        ];

        foreach($gastos as $gasto){
            $gasto1 = CTB_gasto::create($gasto);
        };

    }
}