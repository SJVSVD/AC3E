<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_tipoSucursal;

class tipoSucursalesSeeder extends Seeder
{
    public function run()
    {
        $tiposSucursales = [
            ['nombre' => 'Interno'], // Directamente de la empresa (tiendas por ejemplo)
            ['nombre' => 'Externo'], // A través de empresas externas (falabella, ripley, etc..)
        ];
        
        foreach($tiposSucursales as $tipoSucursal){
            $tipoSucursal1 = SYS_tipoSucursal::create($tipoSucursal);
        };
    }
}