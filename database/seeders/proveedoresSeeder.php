<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_proveedores;

class proveedoresSeeder extends Seeder
{
    public function run()
    {
        $proveedores = [
            ['rut' => '78408990-8', 'razonSocial' => 'Cencosud Admin. de Centros Comeciales SPA.', 'codSocioNegocio' => '41251A', 'idAuthAso1' => null, 'idAuthAso2' => null ],
            ['rut' => '76433310-1', 'razonSocial' => 'Cencosud Shopping S.A.', 'codSocioNegocio' => '41251B', 'idAuthAso1' => null, 'idAuthAso2' => null ],
            ['rut' => '81201000-K', 'razonSocial' => 'Cencosud Retail S.A.', 'codSocioNegocio' => '41251C', 'idAuthAso1' => null, 'idAuthAso2' => null ],
            ['rut' => '76203299-6', 'razonSocial' => 'Comercializadora Costanera Center SPA.', 'codSocioNegocio' => '41251', 'idAuthAso1' => null, 'idAuthAso2' => null ],
            ['rut' => '77398220-1', 'razonSocial' => 'MercadorLibre Chile Ltda.', 'codSocioNegocio' => '41251F', 'idAuthAso1' => null, 'idAuthAso2' => null ],
            ['rut' => '90635000-9', 'razonSocial' => 'Telefónica Chile S.A.', 'codSocioNegocio' => '41251G', 'idAuthAso1' => null, 'idAuthAso2' => null ],
            ['rut' => '92580000-7', 'razonSocial' => 'Entel S.A.', 'codSocioNegocio' => '41251H', 'idAuthAso1' => null, 'idAuthAso2' => null ],
            ['rut' => '76069366-9', 'razonSocial' => 'Masterbase S.A.', 'codSocioNegocio' => '41251Y', 'idAuthAso1' => null, 'idAuthAso2' => null ],
        ];

        foreach($proveedores as $proveedor){
            $proveedor1 = SYS_proveedores::create($proveedor);
        };
    }
}