<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class callEssencialDatabaseSeeders extends Seeder
{
    public function run()
    {
        $this->call([
            tipoDocFacturasSeeder::class,
            tipoGastosSeeder::class,
            tipoRobosSeeder::class,
            tiposDocFofiSeeder::class,
            tipoUsuariosSeeder::class,
            tipoSucursalesSeeder::class,
            tipoCuentasBancoSeeder::class,
            afpsSeeder::class,
            departamentosSeeder::class,
            areasSeeder::class,
            paisesSeeder::class,
            nacionalidadesSeeder::class,
            zonasSeeder::class,
            regionesSeeder::class,
            comunasSeeder::class,
            empresasSeeder::class,
            marcasSeeder::class,
            empresasMarcasSeeder::class,
            tiposInvSeeder::class,
            estadosCivilesSeeder::class,
            monedasSeeder::class,
            sistSaludSeeder::class,
            bancosSeeder::class,
            permisosSeeder::class,
            rolesSeeder::class,
            colaboradoresSeeder::class,
            usersSeeder::class,
            sucursalesSeeder::class,
            autorizadoresSeeder::class,
            vendedoresSeeder::class,
            supervisoresSeeder::class,
            promotoresSeeder::class,
            proveedoresSeeder::class,
            empresasKamSeeder::class,
            comisionesSeeder::class,
            gastosSeeder::class,
            gruposSeeder::class,
            grupoMiembrosSeeder::class,
            grupoLideresSeeder::class,
        ]);
    }
}
