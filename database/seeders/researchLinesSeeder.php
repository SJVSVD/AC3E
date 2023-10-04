<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\researchLines;

class researchLinesSeeder extends Seeder
{
    public function run()
    {    
        $researchLines = [
            ['name' => 'Indefinida'],
            ['name' => 'Control'],
            ['name' => 'Energia'],
            ['name' => 'Int. Artificial'],
            ['name' => 'Robotica'],
            ['name' => 'Sist. Biomedicos'],
            ['name' => 'Sist. Electricos'],
        ];

        foreach($researchLines as $researchLine){
            $researchLine1 = researchLines::create($researchLine);
        };
    }
}