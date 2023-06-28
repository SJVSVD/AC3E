<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_empresas;

class empresasSeeder extends Seeder
{
    public function run()
    {    
        $empresas = [
            ['rut' => '965215506', 'razonSocial' => 'Interandina de Comercio Limitada', 'giro' => 'Venta al por menor de prendas y accesorios de vestir en comercios especializados.', 'direccion' => 'Cerro El Plomo # 3402, Parque Industrial Curauma', 'idPais' => '1', 'idRegion' => '6', 'idComuna' => '65', 'telefono' => '322182960', 'fax' => '322182979','tipo' => true, 'codigoPostal' => '2372547'],
            ['rut' => '768398526', 'razonSocial' => 'Retail Sudamericano Limitada', 'giro' => 'Venta al por menor de prendas y accesorios de vestir en comercios especializados.', 'direccion' => 'Cerro El Plomo # 3402, Parque Industrial Curauma', 'idPais' => '1', 'idRegion' => '6', 'idComuna' => '65', 'telefono' => '322182960', 'fax' => '322182979','tipo' => true, 'codigoPostal' => '2372547'],
        ];

        foreach($empresas as $empresa){
            $empresa1 = SYS_empresas::create($empresa);
        };
    }
}