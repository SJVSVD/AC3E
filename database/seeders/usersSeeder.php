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
            ['idResearchLine' => '5','idRole'=> '3','name' => 'Fernando Auat', 'email' => 'fernando.auat@usm.cl','password' => bcrypt('fauat678')],
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
            // Inactivos
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Ahmed Elmesiry', 'password' => bcrypt('aelmesi392')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Ana Maria LLOR CARRASCO', 'password' => bcrypt('allor567')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Andres Eduardo Alonso Rivas', 'password' => bcrypt('aalonso815')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Cristian Andrés Acevedo Gutiérrez', 'password' => bcrypt('aacevedo724')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Eduardo Esteban Cerpa Jeria', 'password' => bcrypt('ecerpa990')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Francisco David Muñoz Espinoza', 'password' => bcrypt('fmunoz487')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Francisco Javier Vargas Parra', 'password' => bcrypt('fvargas962')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Gonzalo Carvajal Barrera', 'password' => bcrypt('gcarvajal374')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Héctor Mario Carrasco Espinosa', 'password' => bcrypt('hcarrasco899')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'John Anthony Atkinson Abutridy', 'password' => bcrypt('jatkinson531')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'José Rubén Espinoza Castro', 'password' => bcrypt('jespinoza890')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Juan Ignacio Yuz Eissmann', 'password' => bcrypt('jyuz211')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Luciano Sebastian Ahumada Fierro', 'password' => bcrypt('lahumada773')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Luis Carlos Pérez Pozo', 'password' => bcrypt('lperez488')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Milan Stefan Derpich Musa', 'password' => bcrypt('mderpich654')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Pablo Andrés Prieto Cabrera', 'password' => bcrypt('pprieto731')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Pedro Antonio Escárate Monetta', 'password' => bcrypt('pescarate488')],
            ['idResearchLine' => '1', 'idRole'=> '5', 'name' => 'Victor Hugo Hugo Hinojosa Mateus', 'password' => bcrypt('vhinojosa628')],
            // Nuevos
            ['idResearchLine' => '2','idRole'=> '1','name' => 'Angel Cedeño', 'email' => 'angel.cedeno@sansano.usm.cl','password' => bcrypt('acedeño456')],
            ['idResearchLine' => '2','idRole'=> '1','name' => 'Melissa Diago', 'email' => 'melissa.diago@usm.cl','password' => bcrypt('mdiag789')],
            ['idResearchLine' => '2','idRole'=> '5','name' => 'Rodrigo Carvajal', 'email' => 'rodrigo.carvajalg@usm.cl','password' => bcrypt('rcarv912')],
            ['idResearchLine' => '4','idRole'=> '5','name' => 'Christopher Ley', 'email' => 'christopher.ley@usm.cl','password' => bcrypt('cley345')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Rodrigo Parra', 'email' => 'parrar@eso.org','password' => bcrypt('rparr678')],
            ['idResearchLine' => '3','idRole'=> '5','name' => 'Pat Wheeler', 'email' => 'pat.wheeler@nottingham.ac.uk','password' => bcrypt('pwheel012')],
            ['idResearchLine' => '3','idRole'=> '5','name' => 'Freddy Flores', 'email' => 'freddy.flores@usm.cl','password' => bcrypt('fflore345')],
            ['idResearchLine' => '2','idRole'=> '5','name' => 'Lucio Salinas','password' => bcrypt('lsalin678')],
            ['idResearchLine' => '2','idRole'=> '5','name' => 'Felipe Arrate','password' => bcrypt('farrat012')],
            ['idResearchLine' => '5','idRole'=> '5','name' => 'Lucas Veronese','password' => bcrypt('lveron345')],
            ['idResearchLine' => '2','idRole'=> '5','name' => 'Andres Peters', 'email' => 'andres.peters@usm.cl','password' => bcrypt('apeter678')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Gabriel Alzamendi', 'email' => 'gabriel.alzamendi@usm.cl','password' => bcrypt('galzam012')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Pavel Prado', 'email' => 'pavel.prado@usm.cl','password' => bcrypt('pprad345')],
            ['idResearchLine' => '2','idRole'=> '5','name' => 'Francisco Cuevas','password' => bcrypt('fcueva678')],
            ['idResearchLine' => '2','idRole'=> '5','name' => 'Claudia Sanchez','password' => bcrypt('csanch012')],
            ['idResearchLine' => '5','idRole'=> '5','name' => 'Oswaldo Menendez', 'email' => 'oswaldo.menendez@usm.cl','password' => bcrypt('omenen345')],
            ['idResearchLine' => '2','idRole'=> '5','name' => 'Patricio Guzman', 'email' => 'aupato@gmail.com','password' => bcrypt('pguzma678')],
            ['idResearchLine' => '3','idRole'=> '5','name' => 'Christian Alcota', 'email' => 'christian.alcota@alumnos.usm.cl','password' => bcrypt('calcot012')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Diego González', 'email' => 'diego.gonzalezbe@usm.cl','password' => bcrypt('dgonza345')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Fabian Rubilar', 'email' => 'fabian.rubilar@usm.cl','password' => bcrypt('frubil678')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Jaime Arnaiz', 'email' => 'jaime.arnaiz@usm.cl','password' => bcrypt('jarnai012')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Jaime Ramirez', 'email' => 'jaime.ramirez@usm.cl','password' => bcrypt('jramir345')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Javier Robledo', 'email' => 'javier.robledon@usm.cl','password' => bcrypt('jroble678')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Miguel López', 'email' => 'miguel.lopezg@usm.cl','password' => bcrypt('mlope012')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Pablo Ríos', 'email' => 'pablo.riosm@usm.cl','password' => bcrypt('prios345')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Valeria Fernandez', 'email' => 'valeria.fernandeza@usm.cl','password' => bcrypt('vferna678')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Yarko Rocha', 'email' => 'yarko.rocha@usm.cl','password' => bcrypt('yrocha012')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Kesheng Xu', 'email' => 'keshengxuu@gmail.com','password' => bcrypt('kxu345')],
            ['idResearchLine' => '3','idRole'=> '5','name' => 'Juan Terrazas','password' => bcrypt('jterra678')],
            ['idResearchLine' => '2','idRole'=> '5','name' => 'Mokhtar Aly','password' => bcrypt('maly012')],
            ['idResearchLine' => '2','idRole'=> '5','name' => 'Mamadou Gueye','password' => bcrypt('mguaye345')],
            ['idResearchLine' => '3','idRole'=> '5','name' => 'Nicolas Carreño','password' => bcrypt('ncarre678')],
            ['idResearchLine' => '4','idRole'=> '5','name' => 'Andrii Chub','password' => bcrypt('achub012')],
            ['idResearchLine' => '4','idRole'=> '5','name' => 'Andrew Morrison','password' => bcrypt('amorri345')],
            ['idResearchLine' => '3','idRole'=> '5','name' => 'Boris Vidal','password' => bcrypt('bvidal678')],
            ['idResearchLine' => '6','idRole'=> '5','name' => 'Diana Lopez','password' => bcrypt('dlopez012')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Monica Otero','password' => bcrypt('motero345')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Esteban Hernandez','password' => bcrypt('eherna678')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Fernando Lehue','password' => bcrypt('flehue012')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Francisco Yandun','password' => bcrypt('fyandu345')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Jose Rojas','password' => bcrypt('jrojas678')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Kevin Pizarro','password' => bcrypt('kpizar012')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Luis Mora','password' => bcrypt('lmora345')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Marco Encina','password' => bcrypt('mencin678')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Matias Aguirre','password' => bcrypt('magüir012')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Paola Nazate','password' => bcrypt('pnazate345')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Sebastian Espinosa','password' => bcrypt('sespin678')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Tomás Lara','password' => bcrypt('tlara012')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Francisc Gutierrez','password' => bcrypt('fgutie345')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Javier Rosas','password' => bcrypt('jrosas678')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Gonzalo Carrasco','password' => bcrypt('gcarra012')],
            ['idResearchLine' => '1','idRole'=> '5','name' => 'Victor Santana','password' => bcrypt('vsanta345')],
        ];

        foreach($users as $user){
            $user1 = User::create($user);
        };
    }
}