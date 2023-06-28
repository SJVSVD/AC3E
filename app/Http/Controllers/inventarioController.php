<?php

namespace App\Http\Controllers;
use App\Models\ETC_inventario as Inventarios;
use Illuminate\Http\Request;

class inventarioController extends Controller
{
    public function index(){
        return Inventarios::with("tipoInv")->with("sucursal")->with("supervisor.usuario")->with("promotor.usuario")->with("comision.usuario")->get();
    }

    public function destroy($id)
    {
        Inventarios::find($id)->delete();
        return response()->json("Inventario Eliminado");
    }

    public function sendEmailSupervisor(Request $request){
        // Datos de Entrada:
        $robo = $request[0];
        $detallesRobo = $request[1];
        $supervisores = User::where('idTipoUsuario','3')->get();
        // Conseguir email y nombre del supervisor:
        $supervisorEmail = null;
        $supervisorName = null;
        foreach ($supervisores as $supervisor){
            if($robo['idSupervisor'] == $supervisor['id']){
                $supervisorEmail = $supervisor['email'];
                $supervisorName = $supervisor['name'];
            }
        }
        // Conseguir usuario que informa del robo:
        $usuarioInformante = User::find($robo['idUsuario']);
        // Conseguir tipo robo:
        $tipoRobo = TiposRobo::find($robo['idTipoRobo']);
        // Conseguir tienda:
        $tienda = Sucursales::find($robo['idTienda']);

        // Otras Variables: 
        $username = "Register_AMPHORAMKTCL";
        $password = '8634-E$6xO$$F$6Ex125';
        $userpwd = $username.":".$password;
        //Variables Especiales:
        $color = "#000000";
        
        // Creación Output
        $output = null;
        $output .='
        <tr>
            <td>'.$tienda['nombre'].'</td>
            <td>'.$usuarioInformante['name'].'</td>
            <td>'.$tipoRobo['nombre'].'</td>
            <td>'.$robo['fecha'].'</td>
            <td>'.$robo['hora'].'</td>
            <td>'.$robo['montoRobado'].'</td>
            <td>'.$supervisorName.'</td>
            <td>'.$robo['observacion'].'</td>
        </tr>';
       
        // Creación Output Detalle
        $outputDetalle = null;
        foreach ($detallesRobo as $detalle) {
            // Construcción Tabla:
            $outputDetalle .='
            <tr>
                <td>'.$detalle['idDetalle'].'</td>
                <td>'.$detalle['item'].'</td>
                <td>'.$detalle['descripcion'].'</td>
                <td>'.$detalle['cantidad'].'</td>
                <td>'.$detalle['monto'].'</td>
            </tr>';
        }

        // HTML: 
        $html = "
                <style>
                #customers {
                    font-family: Arial, Helvetica, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                    font-size: 12px;
                }
                #customers td, #customers th {
                    border: 1px solid #ddd;
                    padding: 8px;
                }
                #customers tr:nth-child(even){background-color: #f2f2f2;}
                #customers tr:hover {background-color: #ddd;}
                #customers th {
                    padding-top: 12px;
                    font-size: 14px;
                    padding-bottom: 12px;
                    text-align: left;
                    background-color: #8fa0a0;
                    color: white;
                }
                </style>
                <h3> Enlace directo: <a href='http://127.0.0.1:8000/gestion-robos' style='font-size: 14px;'>Revisar robos.</a> </h3>
                <h3> Robo Informado: </h3>";
                if($output != null){
                    $html.="<table id='customers'>
                        <thead>
                            <tr>
                                <th>Tienda</th>
                                <th>Usuario Informante</th>
                                <th>Tipo de Robo</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Monto Robado</th>
                                <th>Supervisor</th>
                                <th>Observación</th>
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
                if($outputDetalle != null){
                    $html.="<br>
                    <h3> Detalles del Robo: </h3>
                    <table id='customers'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Item</th>
                                <th>Descripción</th>
                                <th>Cantidad Robada</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            ".$outputDetalle."
                        </tbody>
                    </table>
                    <br>";
                } else {
                    $html.="<h5>No hay información disponible.</h5>";
                };

        $emailData = json_encode([
            "GeneralData" => [
                "FromName" => "No Reply - Amphora",
                "From" => "no_reply@amphora.cl",
                "To" => [
                    "Email" => [
                        "bcontreras@amphora.cl",
                        // $supervisorEmail,
                    ]
                ],
                "BCC" => [
                    "Email" => [
                        // "informatica@amphora.cl",
                        "maballay@amphora.cl",
                        // "pbocaz@amphora.cl",
                    ]
                ],
                "CC" => [
                    "Email" => [
                        // "rmontenegro@amphora.cl",
                        // "mtirado@amphora.cl",
                        // "gacostal@amphora.cl",
                    ]
                ],
                "Message" => [
                "Subject" => "Robo Informado. $supervisorEmail",
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
    }

    public function sendEmailComision(Request $request){
        // Datos de Entrada:
        $robo = $request[0];
        $detallesRobo = $request[1];
        $supervisores = User::where('idTipoUsuario','3')->get();
        // Conseguir email y nombre del supervisor:
        $supervisorEmail = null;
        $supervisorName = null;
        foreach ($supervisores as $supervisor){
            if($robo['idSupervisor'] == $supervisor['id']){
                $supervisorEmail = $supervisor['email'];
                $supervisorName = $supervisor['name'];
            }
        }
        // Conseguir usuario que informa del robo:
        $usuarioInformante = User::find($robo['idUsuario']);
        // Conseguir tipo robo:
        $tipoRobo = TiposRobo::find($robo['idTipoRobo']);
        // Conseguir tienda:
        $tienda = Sucursales::find($robo['idTienda']);

        // Otras Variables: 
        $username = "Register_AMPHORAMKTCL";
        $password = '8634-E$6xO$$F$6Ex125';
        $userpwd = $username.":".$password;
        //Variables Especiales:
        $color = "#000000";
        
        // Creación Output
        $output = null;
        $output .='
        <tr>
            <td>'.$tienda['nombre'].'</td>
            <td>'.$usuarioInformante['name'].'</td>
            <td>'.$tipoRobo['nombre'].'</td>
            <td>'.$robo['fecha'].'</td>
            <td>'.$robo['hora'].'</td>
            <td>'.$robo['montoRobado'].'</td>
            <td>'.$supervisorName.'</td>
            <td>'.$robo['observacion'].'</td>
        </tr>';
       
        // Creación Output Detalle
        $outputDetalle = null;
        foreach ($detallesRobo as $detalle) {
            // Construcción Tabla:
            $outputDetalle .='
            <tr>
                <td>'.$detalle['idDetalle'].'</td>
                <td>'.$detalle['item'].'</td>
                <td>'.$detalle['descripcion'].'</td>
                <td>'.$detalle['cantidad'].'</td>
                <td>'.$detalle['monto'].'</td>
            </tr>';
        }

        // HTML: 
        $html = "
                <style>
                #customers {
                    font-family: Arial, Helvetica, sans-serif;
                    border-collapse: collapse;
                    width: 100%;
                    font-size: 12px;
                }
                #customers td, #customers th {
                    border: 1px solid #ddd;
                    padding: 8px;
                }
                #customers tr:nth-child(even){background-color: #f2f2f2;}
                #customers tr:hover {background-color: #ddd;}
                #customers th {
                    padding-top: 12px;
                    font-size: 14px;
                    padding-bottom: 12px;
                    text-align: left;
                    background-color: #8fa0a0;
                    color: white;
                }
                </style>
                <h3> Enlace directo: <a href='http://127.0.0.1:8000/gestion-robos' style='font-size: 14px;'>Revisar robos.</a> </h3>
                <h3> Robo Informado: </h3>";
                if($output != null){
                    $html.="<table id='customers'>
                        <thead>
                            <tr>
                                <th>Tienda</th>
                                <th>Usuario Informante</th>
                                <th>Tipo de Robo</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Monto Robado</th>
                                <th>Supervisor</th>
                                <th>Observación</th>
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
                if($outputDetalle != null){
                    $html.="<br>
                    <h3> Detalles del Robo: </h3>
                    <table id='customers'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Item</th>
                                <th>Descripción</th>
                                <th>Cantidad Robada</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            ".$outputDetalle."
                        </tbody>
                    </table>
                    <br>";
                } else {
                    $html.="<h5>No hay información disponible.</h5>";
                };

        $emailData = json_encode([
            "GeneralData" => [
                "FromName" => "No Reply - Amphora",
                "From" => "no_reply@amphora.cl",
                "To" => [
                    "Email" => [
                        "bcontreras@amphora.cl",
                        // $supervisorEmail,
                    ]
                ],
                "BCC" => [
                    "Email" => [
                        // "informatica@amphora.cl",
                        "maballay@amphora.cl",
                        // "pbocaz@amphora.cl",
                    ]
                ],
                "CC" => [
                    "Email" => [
                        // "rmontenegro@amphora.cl",
                        // "mtirado@amphora.cl",
                        // "gacostal@amphora.cl",
                    ]
                ],
                "Message" => [
                "Subject" => "Robo Informado. $supervisorEmail",
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
    }
}
