<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_kam;

class empresasKamSeeder extends Seeder
{
    public function run()
    { 
        $empresasKam = [
        ];

        foreach($empresasKam as $empresaKam){
            $empresaKam1 = SYS_kam::create($empresaKam);
        };
    }
}