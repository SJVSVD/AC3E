<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\extraTables;

class extraSeeder extends Seeder
{
    public function run()
    {    
        $extras = [
            ['name' => 'progressReport', 'value' => '9'],
        ];

        foreach($extras as $extra){
            $extra1 = extraTables::create($extra);
        };
    }
}