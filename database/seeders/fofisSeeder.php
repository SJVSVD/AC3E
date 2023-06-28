<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class fofisSeeder extends Seeder
{
    public function run()
    {
        $fofis = [
            ['folioFofi' => '1', 'fecha' => '2023-02-01', 'idUsuario' => '7', 'observacion' => 'Comida en general', 'cantidadGastos' => '2', 'fofiAsignado' => '50000', 'montoTotal' => '20000', 'estado' => 'Pendiente', 'idAutorizador' => null, 'autorizado' => null, 'fechaAuth' => null, 'objeciones' => null],
            ['folioFofi' => '2', 'fecha' => '2023-02-03', 'idUsuario' => '2', 'observacion' => 'Arreglos en la tienda', 'cantidadGastos' => '1', 'fofiAsignado' => '60000', 'montoTotal' => '10000', 'estado' => 'Pendiente', 'idAutorizador' => null, 'autorizado' => null, 'fechaAuth' => null, 'objeciones' => null],
            ['folioFofi' => '3', 'fecha' => '2023-02-02', 'idUsuario' => '5', 'observacion' => 'Cumpleaños', 'cantidadGastos' => '1', 'fofiAsignado' => '40000', 'montoTotal' => '7500', 'estado' => 'Pendiente', 'idAutorizador' => null, 'autorizado' => null, 'fechaAuth' => null, 'objeciones' => null],
            ['folioFofi' => '4', 'fecha' => '2023-02-05', 'idUsuario' => '3', 'observacion' => 'Viaje a santiago', 'cantidadGastos' => '1', 'fofiAsignado' => '40000', 'montoTotal' => '12000', 'estado' => 'Aprobado', 'idAutorizador' => '11', 'autorizado' => true, 'fechaAuth' => '2023-05-01', 'objeciones' => ''],
            ['folioFofi' => '5', 'fecha' => '2023-02-05', 'idUsuario' => '6', 'observacion' => 'Correspondencia', 'cantidadGastos' => '3', 'fofiAsignado' => '60000', 'montoTotal' => '15000', 'estado' => 'Aprobado', 'idAutorizador' => '12', 'autorizado' => true, 'fechaAuth' => '2023-05-01', 'objeciones' => ''],
            ['folioFofi' => '6', 'fecha' => '2023-02-05', 'idUsuario' => '7', 'observacion' => 'Bencina', 'cantidadGastos' => '2', 'fofiAsignado' => '70000', 'montoTotal' => '21000', 'estado' => 'Rechazado', 'idAutorizador' => '11', 'autorizado' => false, 'fechaAuth' => '2023-05-01', 'objeciones' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
            ['folioFofi' => '7', 'fecha' => '2023-02-04', 'idUsuario' => '4', 'observacion' => 'Se gasto en fletes', 'cantidadGastos' => '1', 'fofiAsignado' => '80000', 'montoTotal' => '11000', 'estado' => 'Rechazado', 'idAutorizador' => '12', 'autorizado' => false, 'fechaAuth' => '2023-05-01', 'objeciones' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'],
            ['folioFofi' => '8', 'fecha' => '2023-02-03', 'idUsuario' => '2', 'observacion' => 'Accidente en el personal', 'cantidadGastos' => '1', 'fofiAsignado' => '30000', 'montoTotal' => '13000', 'estado' => 'Pendiente', 'idAutorizador' => null, 'autorizado' => null, 'fechaAuth' => null, 'objeciones' => null],
            ['folioFofi' => '9', 'fecha' => '2023-02-02', 'idUsuario' => '4', 'observacion' => 'Gastos medicos', 'cantidadGastos' => '1', 'fofiAsignado' => '50000', 'montoTotal' => '90000', 'estado' => 'Pendiente', 'idAutorizador' => null, 'autorizado' => null, 'fechaAuth' => null, 'objeciones' => null],
            ['folioFofi' => '10', 'fecha' => '2023-02-02', 'idUsuario' => '7', 'observacion' => 'Estacionamiento', 'cantidadGastos' => '1', 'fofiAsignado' => '50000', 'montoTotal' => '8000', 'estado' => 'Pendiente', 'idAutorizador' => null, 'autorizado' => null, 'fechaAuth' => null, 'objeciones' => null],
        ];

        foreach($fofis as $fofi){
            DB::table('CTB_fondosFijos') -> insert([$fofi]);
        };
    }
}