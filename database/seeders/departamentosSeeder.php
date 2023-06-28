<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_departamentos;

class departamentosSeeder extends Seeder
{
    public function run()
    {
        $departamentos = [
            ['departamento' => 'Gerencia'],
            ['departamento' => 'Administración'],
            ['departamento' => 'Marketing'],
            ['departamento' => 'Comercial'],
            ['departamento' => 'Inventario'],
        ];

        foreach($departamentos as $departamento){
            $departamento1 = SYS_departamentos::create($departamento);
        };

    }
}