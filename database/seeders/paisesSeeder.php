<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_paises;

class paisesSeeder extends Seeder
{
    public function run()
    {
        $paises = [
            ['pais' => 'Chile', 'capital' => 'Santiago'],
            ['pais' => 'Mexico', 'capital' => 'Ciudad de México'],
            ['pais' => 'Colombia', 'capital' => 'Bogotá'],
            ['pais' => 'España', 'capital' => 'Madrid'],
            ['pais' => 'Argentina', 'capital' => 'Buenos Aires'],
            ['pais' => 'Peru', 'capital' => 'Lima'],
            ['pais' => 'Venezuela', 'capital' => 'Caracas'],
            ['pais' => 'Guatemala', 'capital' => 'Ciudad de Guatemala'],
            ['pais' => 'Ecuador', 'capital' => 'Quito'],
            ['pais' => 'Cuba', 'capital' => 'La Habana'],
            ['pais' => 'Bolivia', 'capital' => 'Sucre'],
            ['pais' => 'Republica Dominicana', 'capital' => 'Santo Domingo'],
            ['pais' => 'Honduras', 'capital' => 'Tegucigalpa'],
            ['pais' => 'El Salvador', 'capital' => 'San Salvador'],
            ['pais' => 'Paraguay', 'capital' => 'Asunción'],
            ['pais' => 'Nicaragua', 'capital' => 'Managua'],
            ['pais' => 'Costa Rica', 'capital' => 'San José'],
            ['pais' => 'Panama', 'capital' => 'Ciudad de Panamá'],
            ['pais' => 'Puerto Rico', 'capital' => 'San Juan'],
            ['pais' => 'Uruguay', 'capital' => 'Montevideo'],
            ['pais' => 'Brasil', 'capital' => 'Brasilia'],
        ];

        foreach($paises as $pais){
            $pais1 = SYS_paises::create($pais);
        };
    }
}