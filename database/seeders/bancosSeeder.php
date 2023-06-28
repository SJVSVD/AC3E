<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SYS_bancos;

class bancosSeeder extends Seeder
{
    public function run()
    {
        $bancos = [
            ['nombre' => 'BancoEstado'],
            ['nombre' => 'Mercado Pago'],
            ['nombre' => 'Copec Pay'],
            ['nombre' => 'Tapp Caja Los Andes'],
            ['nombre' => 'Prepago Tenpo'],
            ['nombre' => 'Prepago Los Heroes'],
            ['nombre' => 'Coopeuch'],
            ['nombre' => 'Banco BBVA'],
            ['nombre' => 'Banco Consorcio'],
            ['nombre' => 'Banco Ripley/Chek'],
            ['nombre' => 'Banco Falabella'],
            ['nombre' => 'Banco Security'],
            ['nombre' => 'MUFG Bank,LTD.'],
            ['nombre' => 'Banco Itau'],
            ['nombre' => 'Banco Santander'],
            ['nombre' => 'HSBC Bank'],
            ['nombre' => 'BICE'],
            ['nombre' => 'Corp-Banca'],
            ['nombre' => 'BCI'],
            ['nombre' => 'Scotiabank-Desarrollo'],
            ['nombre' => 'Banco Internacional'],
            ['nombre' => 'Banco De Chile'],
        ];

        foreach($bancos as $banco){
            $banco1 = SYS_bancos::create($banco);
        };
    }
}