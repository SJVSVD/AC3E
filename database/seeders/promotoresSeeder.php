<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_promotores;

class promotoresSeeder extends Seeder
{
    public function run()
    {
        $promotores = [
            ['estado' => true, 'idUsuario' => '80', 'idSucursal' => '1'],
            ['estado' => true, 'idUsuario' => '80', 'idSucursal' => '2'],
            ['estado' => true, 'idUsuario' => '80', 'idSucursal' => '3'],
            ['estado' => true, 'idUsuario' => '80', 'idSucursal' => '4'],
            ['estado' => true, 'idUsuario' => '80', 'idSucursal' => '5'],
        ];

        foreach($promotores as $promotor){
            $promotor1 = SYS_promotores::create($promotor);
        };
    }
}