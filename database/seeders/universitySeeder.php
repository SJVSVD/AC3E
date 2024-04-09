<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\universities;

class universitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     public function run()
     {    
         $universidades = [
            ['name' =>'Pontificia Universidad Católica de Chile'],
            ['name' =>'UTFSM (Universidad Técnica Federico Santa María)'],
            ['name' =>'Universidad de Valparaíso'],
            ['name' =>'Universidad de Chile'],
            ['name' =>'Universidad de Concepción'],
            ['name' =>'Universidad de los Andes'] ,
            ['name' =>'Universidad de Maryland'],
            ['name' =>'Universidad de Manchester'],
            ['name' =>'Universidad de Sevilla'],
            ['name' =>'Universidad Diego Portales'],
            ['name' =>'Universidad de Ibagué'],
            ['name' =>'Universidad Politécnica de Madrid'],
            ['name' =>'Universidad de Viña del Mar'],
            ['name' =>'Universidad Nacional del Litoral'],
            ['name' =>'Universidad de Talca'],
            ['name' =>'Universidad de los Andes'],
            ['name' =>'Universidad Católica de la Santísima Concepción'],
            ['name' =>'Concordia University'],
            ['name' =>'Universidad de Cologne'],
         ];
 
         foreach($universidades as $universidad){
             $university = universities::create($universidad);
         };
     }

}