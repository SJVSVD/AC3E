<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_sistemasSalud;

class sistSaludSeeder extends Seeder
{
    public function run()
    {
        $sistemasSalud = [
            ['nombre' => 'Fonasa', 'porcentaje' => '7,0'],
            ['nombre' => 'Banmédica', 'porcentaje' => '7,3'],
            ['nombre' => 'Consalud', 'porcentaje' => '7,6'],
            ['nombre' => 'Cruz Blanca', 'porcentaje' => '11,0'],
            ['nombre' => 'Mas Vida', 'porcentaje' => '8,0'],
            ['nombre' => 'Colmena', 'porcentaje' => '9,0'],
        ];

        foreach($sistemasSalud as $sistemaSalud){
            $sistemaSalud1 = SYS_sistemasSalud::create($sistemaSalud);
        };
    }
}