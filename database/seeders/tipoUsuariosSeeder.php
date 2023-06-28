<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_tipoUsuario;

class tipoUsuariosSeeder extends Seeder
{
    public function run()
    {
        $tiposUsuario = [
            ['nombre' => 'Tienda'],
            ['nombre' => 'Supervisor Concesion'],
            ['nombre' => 'Supervisor Tienda'],
            ['nombre' => 'Promotor Tienda'],
            ['nombre' => 'Vendedor Tienda'],
            ['nombre' => 'KAM'],
            ['nombre' => 'Encargado'],
            ['nombre' => 'Jefatura'],
        ];

        foreach($tiposUsuario as $tipoUsuario){
            $tipoUsuario1 = SYS_tipoUsuario::create($tipoUsuario);
        };
    }
}
