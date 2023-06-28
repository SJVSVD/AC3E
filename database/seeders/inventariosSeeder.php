<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ETC_inventario;

class inventariosSeeder extends Seeder
{
    public function run()
    {
        $inventarios = [
            ['idTipoInv' => '1', 'superFamilia' => 'Carteras', 'idSucursal' => '1', 'idSupervisor' => '1', 'idPromotor' => '1', 'idComision' => '1', 'fecha' => '2023-01-08', 'hora' => '04:05:06', 'estado' => 'Ingresado'],
            ['idTipoInv' => '2', 'superFamilia' => 'Marroquineria', 'idSucursal' => '1', 'idSupervisor' => '1', 'idPromotor' => '1', 'idComision' => '1', 'fecha' => '2023-01-08', 'hora' => '04:05:06', 'estado' => 'Ingresado'],
            ['idTipoInv' => '3', 'superFamilia' => 'Carteras', 'idSucursal' => '1', 'idSupervisor' => '1', 'idPromotor' => '1', 'idComision' => '1', 'fecha' => '2023-01-08', 'hora' => '04:05:06', 'estado' => 'Rechazado', 'autorizadoSupervisora' => false, 'fechaAutSup' => '2023-01-10'],
            ['idTipoInv' => '3', 'superFamilia' => 'Carteras', 'idSucursal' => '1', 'idSupervisor' => '1', 'idPromotor' => '1', 'idComision' => '1', 'fecha' => '2023-01-08', 'hora' => '04:05:06', 'estado' => 'Confirmado', 'autorizadoSupervisora' => true, 'fechaAutSup' => '2023-01-10'],
            ['idTipoInv' => '4', 'superFamilia' => 'Marroquineria', 'idSucursal' => '1', 'idSupervisor' => '1', 'idPromotor' => '1', 'idComision' => '1', 'fecha' => '2023-01-08', 'hora' => '04:05:06', 'estado' => 'Confirmado', 'autorizadoSupervisora' => true, 'fechaAutSup' => '2023-01-10'],
            ['idTipoInv' => '5', 'superFamilia' => 'Marroquineria', 'idSucursal' => '1', 'idSupervisor' => '1', 'idPromotor' => '1', 'idComision' => '1', 'fecha' => '2023-01-08', 'hora' => '04:05:06', 'estado' => 'En Proceso', 'autorizadoSupervisora' => true, 'fechaAutSup' => '2023-01-10', 'autorizadoComision' => true, 'fechaAutCom' => '2023-01-12'],
            ['idTipoInv' => '6', 'superFamilia' => 'Carteras', 'idSucursal' => '1', 'idSupervisor' => '1', 'idPromotor' => '1', 'idComision' => '1', 'fecha' => '2023-01-08', 'hora' => '04:05:06', 'estado' => 'Finalizado', 'autorizadoSupervisora' => true, 'fechaAutSup' => '2023-01-10', 'autorizadoComision' => true, 'fechaAutCom' => '2023-01-12', 'fechaFinalizacion' => '2023-01-21'],
        ];

        foreach($inventarios as $inventario){
            $inventario1 = ETC_inventario::create($inventario);
        };
    }
}