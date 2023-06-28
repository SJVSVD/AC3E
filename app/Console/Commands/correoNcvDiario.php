<?php

namespace App\Console\Commands;
use App\Models\TIE_NotasCredito;
use App\Models\SYS_sucursales;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class correoNcvDiario extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'correoNcvDiario';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para lanzar correo diario de notas de creditos generadas en el dia';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ayer = Carbon::yesterday()->startOfDay();
        $hoy = Carbon::today()->endOfDay();

        // Consulta para obtener las facturas entre ayer y hoy
        $ncvs = TIE_NotasCredito::whereBetween('fecha', [$ayer, $hoy])->get();
        // $hoy = Carbon::today();

        // Consulta para obtener las facturas de hoy
        // $ncvs = TIE_NotasCredito::whereDate('fecha', $hoy)->get();

        // Variables: 
        $username = "Register_AMPHORAMKTCL";
        $password = '8634-E$6xO$$F$6Ex125';
        $userpwd = $username.":".$password;
        //Variables Especiales:
        $color = "#000000";

        $url = url()->current(); // Obtiene la URL actual

        $parts = parse_url($url); // Analiza los componentes de la URL
        
        $domain = $parts['scheme'] . '://' . $parts['host']; // Obtiene el dominio
        
        if (isset($parts['port'])) {
            $domain .= ':' . $parts['port']; // Si hay puerto, lo concatena al dominio
        }

        // Creación Output

        $output = null;
        foreach ($ncvs as $ncv) {
            $montoNCV_formateado = '$ ' . number_format($ncv['montoNotaCredito'], 0, '', '.');
            $montoBoleta_formateado = '$ ' . number_format($ncv['montoBoleta'], 0, '', '.');
            $tienda = SYS_sucursales::where('id',$ncv['idTienda'])->pluck('nombre');
            $usuario = User::where('id',$ncv['idUsuario'])->pluck('name');
            // Construcción Tabla:
            
            $output .='
            <tr>
                <td>'.$ncv['id'].'</td>
                <td>'.$ncv['fecha'].'</td>
                <td>'.$tienda[0].'</td>
                <td>'.$usuario[0].'</td>
                <td>'.$ncv['notaCredito'].'</td>
                <td>'.$ncv['boleta'].'</td>
                <td>'.$montoNCV_formateado.'</td>
                <td>'.$montoBoleta_formateado.'</td>
                <td>'.$ncv['cantidadArtNCV'].'</td>
                <td>'.$ncv['cantidadArtBoleta'].'</td>
                <td>'.$ncv['rutCliente'].'</td>
                <td>'.$ncv['nomCliente'].'</td>
            </tr>';
        }
        
        // HTML: 
        $html = "<style>
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 16px;
                        line-height: 1.5;
                        color: #333333;
                        background-color: #f4f4f4;
                        padding: 20px;
                    }
                    
                    /* Estilos para los encabezados */
                    h1, h2, h3, h4, h5, h6 {
                        font-family: Arial, sans-serif;
                        font-weight: 600;
                        color: #333333;
                        margin-bottom: 10px;
                    }
                    
                    /* Estilos para los enlaces */
                    a {
                        color: #337ab7;
                    }
                    
                    /* Estilos para las imágenes */
                    img {
                        display: block;
                        max-width: 100%;
                        height: auto;
                        margin: 20px 0;
                    }
                    
                    /* Estilos para los botones */
                    .btn {
                        display: inline-block;
                        padding: 8px 16px;
                        font-size: 18px;
                        font-weight: 600;
                        line-height: 1.5;
                        color: #ffffff;
                        background-color: #337ab7;
                        border-radius: 4px;
                        text-align: center;
                        text-decoration: none;
                        cursor: pointer;
                    }
                    
                    .btn:hover {
                        background-color: #23527c;
                    }
                    
                    /* Estilos para las tablas */
                    #customers table {
                        border-collapse: collapse;
                        width: 100%;
                        max-width: 600px;
                        margin-bottom: 20px;
                    }
                    
                    #customers th, #customers td {
                        padding: 8px;
                        text-align: center;
                        border-bottom: 1px solid #dddddd;
                    }
                    
                    #customers th {
                        background-color: #d4d4d4;
                        font-weight: 600;
                    }
                </style>
                <h2> Hoy se han registrado las siguientes Notas de Crédito: </h2>
                <h4> Enlace directo: </h4>
                <a class='btn' href='".$domain."/rendiciones'>Ver Notas de Crédito</a>
                <br>
                <h4> Notas de Crédito: </h4>";

                if($output != null){
                    $html.="<table id='customers'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha</th>
                                <th>Tienda</th>
                                <th>Usuario</th>
                                <th>Nº NCV</th>
                                <th>Nº Boleta</th>
                                <th>Monto NCV</th>
                                <th>Monto Boleta</th>
                                <th>Cantidad Art NCV</th>
                                <th>Cantidad Art Boleta</th>
                                <th>Rut Cliente</th>
                                <th>Nombre Cliente</th>
                            </tr>
                        </thead>
                        <tbody>
                            ".$output."
                        </tbody>
                    </table>
                    <br>";
                } else {
                    $html.="<h5>No hay información disponible.</h5>";
                };

                //<th>Foto</th>

        $emailData = json_encode([
            "GeneralData" => [
                "FromName" => "No Reply - Amphora",
                "From" => "noreply@amphora.cl",
                "To" => [
                "Email" => [
                        "sjerez@amphora.cl"
                        // $usuarioSoli[0]['email'],
                        // $usuarioRendi[0]['email'],
                    ]
                ],
                "BCC" => [
                    "Email" => [
                        // "informatica@amphora.cl",
                        // "maballay@amphora.cl",
                        // "pbocaz@amphora.cl",
                    ]
                ],
                "CC" => [
                    "Email" => [
                        // "proveedores@amphora.cl",
                        // "gacostal@amphora.cl",
                    ]
                ],
                "Message" => [
                "Subject" => "Notas de Crédito ".$hoy." - Amphora",
                "Classification" => "C",
                "Body"=> [
                    "Format" => "html",
                    "Value" => $html
                    ]
                ],
                "Options" => [
                "OpenTracking" => "true",
                "ClickTracking" => "true",
                "TextHtmlTracking" => "true",
                "AutoTextBody" => "false"
                ]
            ]

        ]);

        // CURL PARA ENVIAR CORREOS:
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api2019.masterbase.com/UniqueMail/v3/amphoramktcl',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => array( 'Content-Type: application/json'),
            CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
            CURLOPT_USERPWD => $userpwd,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $emailData
        ));
        $chresults = curl_exec($curl);
        $response = json_decode($chresults, true);
        $info = curl_getinfo($curl);
        $errorMsg = curl_error($curl);
        curl_close($curl);
        if($info["http_code"] == 200 || $info["http_code"] == 204){
            echo "\nCorrecto\n";
        }
        else {
            echo "\nERROR! ERROR! ERROR!\n";
            echo $errorMsg;
        }
        return Command::SUCCESS;
    }
}
