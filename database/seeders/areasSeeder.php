<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_areas;

class areasSeeder extends Seeder
{
    public function run()
    {
        $areas = [
            // Bases
            ['idDepartamento' => '1', 'area' => 'Gerencia', 'idDepArea' => '1Gerencia'],
            ['idDepartamento' => '2', 'area' => 'Administración', 'idDepArea' => '2Administración'],
            ['idDepartamento' => '3', 'area' => 'Marketing', 'idDepArea' => '3Marketing'],
            ['idDepartamento' => '4', 'area' => 'Comercial', 'idDepArea' => '4Comercial'],
            ['idDepartamento' => '5', 'area' => 'Inventario', 'idDepArea' => '5Inventario'],
            // Otras
            ['idDepartamento' => '2', 'area' => 'Informática', 'idDepArea' => '2Informática'],
            ['idDepartamento' => '2', 'area' => 'Contabilidad', 'idDepArea' => '2Contabilidad'],
            ['idDepartamento' => '2', 'area' => 'Remuneraciones', 'idDepArea' => '2Remuneraciones'],
            ['idDepartamento' => '5', 'area' => 'Gestión', 'idDepArea' => '5Gestión'],
            ['idDepartamento' => '5', 'area' => 'Seguridad', 'idDepArea' => '5Seguridad'],
        ];

        foreach($areas as $area){
            $area1 = SYS_areas::create($area);
        };
    }
}