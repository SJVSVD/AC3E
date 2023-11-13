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
            ['name' => 'Undefined'],
            ['name' => 'Control and Automation'],
            ['name' => 'Energy Conversion and Power Systems'],
            ['name' => 'Data Analytics and Artificial Intelligence'],
            ['name' => 'Robotics'],
            ['name' => 'Biomedical Systems'],
            ['name' => 'Electrical Systems'],
        ];

        foreach($researchLines as $researchLine){
            $researchLine1 = researchLines::create($researchLine);
        };
    }
}