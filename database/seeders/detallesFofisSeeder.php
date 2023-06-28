<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class detallesFofisSeeder extends Seeder
{
    public function run()
    {
        $detallesFofis = [
            ['idFofi' => '1','idTipoDocumento' => '2', 'fecha' => '2023-02-02','fechaRetiro' => '2023-02-02','nroDocumento' => '22314423','rut' => '21.153.244-0','idTipoGasto' => '3','idGasto' => '4','monto' => '10000','foto' => 'A','observaciones' => 'Aaa'],
            ['idFofi' => '1','idTipoDocumento' => '2', 'fecha' => '2023-02-02','fechaRetiro' => '2023-02-02','nroDocumento' => '22314423','rut' => '21.153.244-0','idTipoGasto' => '3','idGasto' => '4','monto' => '10000','foto' => 'A','observaciones' => 'Aaa'],
            ['idFofi' => '2','idTipoDocumento' => '2', 'fecha' => '2023-02-02','fechaRetiro' => '2023-02-02','nroDocumento' => '22314423','rut' => '21.153.244-0','idTipoGasto' => '3','idGasto' => '4','monto' => '10000','foto' => 'A','observaciones' => 'Aaa'],
            ['idFofi' => '3','idTipoDocumento' => '2', 'fecha' => '2023-02-02','fechaRetiro' => '2023-02-02','nroDocumento' => '22314423','rut' => '21.153.244-0','idTipoGasto' => '3','idGasto' => '4','monto' => '7500','foto' => 'A','observaciones' => 'Aaa'],
            ['idFofi' => '4','idTipoDocumento' => '2', 'fecha' => '2023-02-02','fechaRetiro' => '2023-02-02','nroDocumento' => '22314423','rut' => '21.153.244-0','idTipoGasto' => '3','idGasto' => '4','monto' => '12000','foto' => 'A','observaciones' => 'Aaa'],
            ['idFofi' => '5','idTipoDocumento' => '2', 'fecha' => '2023-02-02','fechaRetiro' => '2023-02-02','nroDocumento' => '22314423','rut' => '21.153.244-0','idTipoGasto' => '3','idGasto' => '4','monto' => '5000','foto' => 'A','observaciones' => 'Aaa'],
            ['idFofi' => '5','idTipoDocumento' => '2', 'fecha' => '2023-02-02','fechaRetiro' => '2023-02-02','nroDocumento' => '22314423','rut' => '21.153.244-0','idTipoGasto' => '3','idGasto' => '4','monto' => '5000','foto' => 'A','observaciones' => 'Aaa'],
            ['idFofi' => '5','idTipoDocumento' => '2', 'fecha' => '2023-02-02','fechaRetiro' => '2023-02-02','nroDocumento' => '22314423','rut' => '21.153.244-0','idTipoGasto' => '3','idGasto' => '4','monto' => '5000','foto' => 'A','observaciones' => 'Aaa'],
            ['idFofi' => '6','idTipoDocumento' => '2', 'fecha' => '2023-02-02','fechaRetiro' => '2023-02-02','nroDocumento' => '22314423','rut' => '21.153.244-0','idTipoGasto' => '3','idGasto' => '4','monto' => '10000','foto' => 'A','observaciones' => 'Aaa'],
            ['idFofi' => '6','idTipoDocumento' => '2', 'fecha' => '2023-02-02','fechaRetiro' => '2023-02-02','nroDocumento' => '22314423','rut' => '21.153.244-0','idTipoGasto' => '3','idGasto' => '4','monto' => '11000','foto' => 'A','observaciones' => 'Aaa'],
            ['idFofi' => '7','idTipoDocumento' => '2', 'fecha' => '2023-02-02','fechaRetiro' => '2023-02-02','nroDocumento' => '22314423','rut' => '21.153.244-0','idTipoGasto' => '3','idGasto' => '4','monto' => '11000','foto' => 'A','observaciones' => 'Aaa'],
            ['idFofi' => '8','idTipoDocumento' => '2', 'fecha' => '2023-02-02','fechaRetiro' => '2023-02-02','nroDocumento' => '22314423','rut' => '21.153.244-0','idTipoGasto' => '3','idGasto' => '4','monto' => '13000','foto' => 'A','observaciones' => 'Aaa'],
            ['idFofi' => '9','idTipoDocumento' => '2', 'fecha' => '2023-02-02','fechaRetiro' => '2023-02-02','nroDocumento' => '22314423','rut' => '21.153.244-0','idTipoGasto' => '3','idGasto' => '4','monto' => '90000','foto' => 'A','observaciones' => 'Aaa'],
            ['idFofi' => '10','idTipoDocumento' => '2', 'fecha' => '2023-02-02','fechaRetiro' => '2023-02-02','nroDocumento' => '22314423','rut' => '21.153.244-0','idTipoGasto' => '3','idGasto' => '4','monto' => '8000','foto' => 'A','observaciones' => 'Aaa'],
        ];

        foreach($detallesFofis as $detalleFofi){
            DB::table('CTB_detalleFofis') -> insert([$detalleFofi]);
        };
    }
}