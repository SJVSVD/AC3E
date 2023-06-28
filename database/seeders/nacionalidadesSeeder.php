<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_nacionalidades;

class nacionalidadesSeeder extends Seeder
{
    public function run()
    {
        $nacionalidades = [
                ['nacionalidad' => 'Chilena', 'idPais' => '1'],
                ['nacionalidad' => 'Mexicana', 'idPais' => '2'],
                ['nacionalidad' => 'Colombiana', 'idPais' => '3'],
                ['nacionalidad' => 'Española', 'idPais' => '4'],
                ['nacionalidad' => 'Argentina', 'idPais' => '5'],
                ['nacionalidad' => 'Peruana', 'idPais' => '6'],
                ['nacionalidad' => 'Venezolana', 'idPais' => '7'],
                ['nacionalidad' => 'Guatemalteca', 'idPais' => '8'],
                ['nacionalidad' => 'Ecuatoriana', 'idPais' => '9'],
                ['nacionalidad' => 'Cubana', 'idPais' => '10'],
                ['nacionalidad' => 'Boliviana', 'idPais' => '11'],
                ['nacionalidad' => 'Dominicana', 'idPais' => '12'],
                ['nacionalidad' => 'Hondureña', 'idPais' => '13'],
                ['nacionalidad' => 'Salvadoreña', 'idPais' => '14'],
                ['nacionalidad' => 'Paraguaya', 'idPais' => '15'],
                ['nacionalidad' => 'Nicaraguense', 'idPais' => '16'],
                ['nacionalidad' => 'Costarricense', 'idPais' => '17'],
                ['nacionalidad' => 'Panameña', 'idPais' => '18'],
                ['nacionalidad' => 'Puertorriqueña', 'idPais' => '19'],
                ['nacionalidad' => 'Uruguaya', 'idPais' => '20'],
                ['nacionalidad' => 'Brasileña', 'idPais' => '21'],
            ];

        foreach($nacionalidades as $nacionalidad){
            $nacionalidad1 = SYS_nacionalidades::create($nacionalidad);
        };
    }
}