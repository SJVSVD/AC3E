<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class permisosSeeder extends Seeder
{
    public function run()
    {
        $permisos = [
            // Facturas
            'Facturas_Ver',
            'Facturas_Crear',
            'Facturas_Modificar',
            'Facturas_Pagar',
            'Facturas_Autorizar_Gastos',
            'Facturas_Autorizar_Pagos',
            // Fondos
            'Fondos Fijos_Ver',
            'Fondos Fijos_Crear',
            'Fondos Fijos_Modificar',
            'Fondos Fijos_Autorizar',
            // Rendiciones
            'Rendiciones_Ver',
            'Rendiciones_Crear',
            'Rendiciones_Autorizar',
            'Rendiciones_Modificar',
            // Gestión Tienda
            'Gestión Tienda_Ver',
            'Gestión Tienda_Supervision',
            'Gestión Tienda_Crear',
            'Gestión Tienda_Modificar',
            'Gestión Tienda_Solicitar Precios',
            'Gestión Tienda_Solicitar Precios Borrar',
            'Gestión Tienda_Crear Vendedor',
            'Gestión Tienda_Conversion Ventas',
            'Gestión Tienda_Cierre Cajas',
            'Gestión Tienda_NCV',
            'Gestión Tienda_NCV_Crear',
            'Gestión Tienda_NCV_Autorizar',
            'Gestión Tienda_Subir Metas',
            'Gestión Tienda_Ventas por Vendedor',
            // Robos
            'Robos_Ver',
            'Robos_Crear',
            'Robos_Modificar',
            // Inventario Concesiones
            'Inventario Concesiones_Ver',
            'Inventario Concesiones_Crear',
            'Inventario Concesiones_Modificar',
            // Manuales
            'Manuales_Ver',
            'Manuales_Crear',
            'Manuales_Modificar',
        ];

        foreach($permisos as $permiso){
            Permission::create(['name' => $permiso]);
        };
    }
}