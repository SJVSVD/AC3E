<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_regiones;

class regionesSeeder extends Seeder
{
    public function run()
    {
        $regiones = [
            ['region' => 'Arica y Parinacota', 'abreviatura' => 'AP', 'capital' => 'Arica', 'idPais' => '1', 'idPaisRegion' => '1Arica y Parinacota'],
            ['region' => 'Tarapacá', 'abreviatura' => 'TA', 'capital' => 'Iquique' , 'idPais' => '1', 'idPaisRegion' => '1Tarapacá'],
            ['region' => 'Antofagasta', 'abreviatura' => 'AN', 'capital' => 'Antofagasta' , 'idPais' => '1', 'idPaisRegion' => '1Antofagasta'],
            ['region' => 'Atacama', 'abreviatura' => 'AT', 'capital' => 'Copiapó' , 'idPais' => '1', 'idPaisRegion' => '1Atacama'],
            ['region' => 'Coquimbo', 'abreviatura' => 'CO', 'capital' => 'La Serena' , 'idPais' => '1', 'idPaisRegion' => '1Coquimbo'],
            ['region' => 'Valparaiso', 'abreviatura' => 'VA', 'capital' => 'Valparaiso' , 'idPais' => '1', 'idPaisRegion' => '1Valparaiso'],
            ['region' => 'Metropolitana de Santiago', 'abreviatura' => 'RM', 'capital' => 'Santiago' , 'idPais' => '1', 'idPaisRegion' => '1Metropolitana de Santiago'],
            ['region' => 'Libertador General Bernardo O\'Higgins', 'abreviatura' => 'OH', 'capital' => 'Rancagua' , 'idPais' => '1', 'idPaisRegion' => '1Libertador General Bernardo O\'Higgins'],
            ['region' => 'Maule', 'abreviatura' => 'MA', 'capital' => 'Talca' , 'idPais' => '1', 'idPaisRegion' => '1Maule'],
            ['region' => 'Ñuble', 'abreviatura' => 'NB', 'capital' => 'Chillán' , 'idPais' => '1', 'idPaisRegion' => '1Ñuble'],
            ['region' => 'Biobío', 'abreviatura' => 'BI', 'capital' => 'Concepción' , 'idPais' => '1', 'idPaisRegion' => '1Biobío'],
            ['region' => 'La Araucanía', 'abreviatura' => 'IAR', 'capital' => 'Temuco' , 'idPais' => '1', 'idPaisRegion' => '1La Araucanía'],
            ['region' => 'Los Ríos', 'abreviatura' => 'LR', 'capital' => 'Valdivia' , 'idPais' => '1', 'idPaisRegion' => '1Los Ríos'],
            ['region' => 'Los Lagos', 'abreviatura' => 'LL', 'capital' => 'Puerto Montt' , 'idPais' => '1', 'idPaisRegion' => '1Los Lagos'],
            ['region' => 'Aysén del General Carlos Ibáñez del Campo', 'abreviatura' => 'AI', 'capital' => 'Coyhaique' , 'idPais' => '1', 'idPaisRegion' => '1Aysén del General Carlos Ibáñez del Campo'],
            ['region' => 'Magallanes y de la Antártica Chilena', 'abreviatura' => 'MG', 'capital' => 'Punta Arenas' , 'idPais' => '1', 'idPaisRegion' => '1Magallanes y de la Antártica Chilena'],
        ];

        foreach($regiones as $region){
            $region1 = SYS_regiones::create($region);
        };
    }
}