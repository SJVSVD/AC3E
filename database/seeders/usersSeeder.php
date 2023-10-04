<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class usersSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['idResearchLine' => '1','idRole'=> '4','name' => 'Sebastián Jerez Vega', 'email' => 'seba.jerezm@gmail.com', 'password' => bcrypt('sjerez861')],
            ['idResearchLine' => '2','idRole'=> '1','name' => 'Juan Carlos Agüero', 'email' => 'juan.aguero@usm.cl', 'password' => bcrypt('jaguero123')],
            ['idResearchLine' => '2','idRole'=> '2','name' => 'María Coronel', 'email' => 'maria.coronelm@usm.cl','password' => bcrypt('mcoronel456')],
            ['idResearchLine' => '2','idRole'=> '1','name' => 'Hector Ramírez', 'email' => 'hector.ramireze@usm.cl','password' => bcrypt('hramirez789')],
            ['idResearchLine' => '2','idRole'=> '1','name' => 'Constanza Ahumada', 'email' => 'coahumad@uchile.cl','password' => bcrypt('cahumada012')],
            ['idResearchLine' => '2','idRole'=> '1','name' => 'Alejandro Rojas', 'email' => 'arojasn@udec.cl','password' => bcrypt('arojas345')],
            ['idResearchLine' => '2','idRole'=> '1','name' => 'Ronny Vallejos', 'email' => 'ronny.vallejos@usm.cl','password' => bcrypt('rvallejos678')],
            ['idResearchLine' => '3','idRole'=> '3','name' => 'Samir Kouro', 'email' => 'carolina.beckmann@sansano.usm.cl','password' => bcrypt('skouro901')],
            ['idResearchLine' => '3','idRole'=> '3','name' => 'Marcelo Pérez', 'email' => 'marcelo.perez@usm.cl','password' => bcrypt('mperez234')],
            ['idResearchLine' => '3','idRole'=> '1','name' => 'Roberto Cárdenas', 'email' => 'jesus.cardenas@uchile.cl','password' => bcrypt('rcardenas567')],
            ['idResearchLine' => '3','idRole'=> '1','name' => 'José Rodríguez', 'email' => 'gocafe1@gmail.com','password' => bcrypt('jrodriguez890')],
            ['idResearchLine' => '3','idRole'=> '1','name' => 'Christian Rojas', 'email' => 'christian.rojas@usm.cl','password' => bcrypt('crojas123')],
            ['idResearchLine' => '3','idRole'=> '1','name' => 'Sebastián Rivera', 'email' => 'sri@miuandes.cl','password' => bcrypt('srivera456')],
            ['idResearchLine' => '3','idRole'=> '1','name' => 'Hugues Renaudineau', 'email' => 'hugues.renaudineau@usm.cl','password' => bcrypt('hrenaudineau789')],
            ['idResearchLine' => '3','idRole'=> '2','name' => 'Jorge Marín', 'email' => 'jorge.marinn@usm.cl','password' => bcrypt('jmarin012')],
            ['idResearchLine' => '3','idRole'=> '2','name' => 'Alan Wilson', 'email' => 'alan.wilson@usm.cl','password' => bcrypt('awilson345')],
            ['idResearchLine' => '4','idRole'=> '3','name' => 'Jorge Silva', 'email' => 'josilva@ing.uchile.cl','password' => bcrypt('jsilva678')],
            ['idResearchLine' => '4','idRole'=> '1','name' => 'Mauricio Araya', 'email' => 'mauricio.araya@usm.cl','password' => bcrypt('maraya901')],
            ['idResearchLine' => '4','idRole'=> '1','name' => 'Ioannis Vourkas', 'email' => 'ioannis.vourkas@usm.cl','password' => bcrypt('ivourkas234')],
            ['idResearchLine' => '4','idRole'=> '1','name' => 'Sandra Céspedes', 'email' => 'scespedes@niclabs.cl','password' => bcrypt('scespedes567')],
            ['idResearchLine' => '4','idRole'=> '1','name' => 'Felipe Tobar', 'email' => 'ftobar@dim.uchile.cl','password' => bcrypt('ftobar890')],
            ['idResearchLine' => '4','idRole'=> '1','name' => 'María José Escobar', 'email' => 'mariajose.escobar@usm.cl','password' => bcrypt('mjescobar012')],
            ['idResearchLine' => '4','idRole'=> '2','name' => 'Sandy Bolufe', 'email' => 'sandy.bolufe@usm.cl','password' => bcrypt('sbolufe345')],
            ['idResearchLine' => '5','idRole'=> '3','name' => 'Fernando Aut', 'email' => 'fernando.auat@usm.cl','password' => bcrypt('faut678')],
            ['idResearchLine' => '5','idRole'=> '1','name' => 'Marcos Orchard', 'email' => 'morchard@ing.uchile.cl','password' => bcrypt('morchard901')],
            ['idResearchLine' => '5','idRole'=> '1','name' => 'Miguel Torres', 'email' => 'mtorrest@ing.puc.cl','password' => bcrypt('mtorres234')],
            ['idResearchLine' => '5','idRole'=> '1','name' => 'José Delpiano', 'email' => 'jfd@uandes.cl','password' => bcrypt('jdelpiano567')],
            ['idResearchLine' => '5','idRole'=> '1','name' => 'Marcelo Soto', 'email' => 'marcelo.sotoh@usm.cl','password' => bcrypt('msoto890')],
            ['idResearchLine' => '5','idRole'=> '1','name' => 'Nahuel Deniz', 'email' => 'nestor.deniz@usm.cl','password' => bcrypt('ndeniz012')],
            ['idResearchLine' => '6','idRole'=> '1','name' => 'Matías Zañartu', 'email' => 'matias.zanartu@usm.cl','password' => bcrypt('mzanartu345')],
            ['idResearchLine' => '6','idRole'=> '1','name' => 'Pamela Guevara', 'email' => 'pamela.guevara@biomedica.udec.cl','password' => bcrypt('pguevara678')],
            ['idResearchLine' => '6','idRole'=> '1','name' => 'Alejandro Weinstein', 'email' => 'alejandro.weinstein@uv.cl','password' => bcrypt('aweinstein901')],
            ['idResearchLine' => '6','idRole'=> '1','name' => 'Wael El-Deredy', 'email' => 'wael.el-deredy@uv.cl','password' => bcrypt('welderedy234')],
            ['idResearchLine' => '6','idRole'=> '1','name' => 'Patricio Orio', 'email' => 'patricio.orio@uv.cl','password' => bcrypt('porio567')],
            ['idResearchLine' => '6','idRole'=> '1','name' => 'Paul Delano', 'email' => 'pdelano@med.uchile.cl','password' => bcrypt('pdelano890')],
            ['idResearchLine' => '6','idRole'=> '2','name' => 'Grace Whitaker', 'email' => 'grace.a.whitaker@gmail.com','password' => bcrypt('gwhitaker012')],
            ['idResearchLine' => '7','idRole'=> '1','name' => 'Pablo Lezana', 'email' => 'pablo.lezana@usm.cl','password' => bcrypt('plezana345')],
            ['idResearchLine' => '7','idRole'=> '1','name' => 'Margarita Norambuena', 'email' => 'margarita.norambuena@usm.cl','password' => bcrypt('mnorambuena678')],
            ['idResearchLine' => '7','idRole'=> '1','name' => 'Esteban Gil', 'email' => 'esteban.gil@usm.cl','password' => bcrypt('egil901')],
            ['idResearchLine' => '7','idRole'=> '1','name' => 'Alejandro Angulo', 'email' => 'alejandro.angulo@usm.cl','password' => bcrypt('aangulo234')],
            ['idResearchLine' => '7','idRole'=> '1','name' => 'Pablo Acuña', 'email' => 'pablo.acuna@utalca.cl','password' => bcrypt('pacuna567')],
            ['idResearchLine' => '7','idRole'=> '1','name' => 'Andrés Mora', 'email' => 'andres.mora@usm.cl','password' => bcrypt('amora890')],
            ['idResearchLine' => '7','idRole'=> '1','name' => 'Cristian García', 'email' => 'cristian.garcia@utalca.cl','password' => bcrypt('cgarcia123')],
            ['idResearchLine' => '1','idRole'=> '4','name' => 'Osvaldo Campos', 'email' => 'osvaldo.camposf@usm.cl','password' => bcrypt('ocampos456')],
            ['idResearchLine' => '1','idRole'=> '4','name' => 'Renata Faúndez', 'email' => 'renata.faundez@usm.cl','password' => bcrypt('rfaundez789')],
        ];

        foreach($users as $user){
            $user1 = User::create($user);
        };
    }
}