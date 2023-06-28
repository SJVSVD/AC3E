<?php

namespace App\Http\Controllers;

use App\Models\CTB_fofi as FondosFijos;
use App\Models\CTB_detalleFofi;
use App\Models\TIE_cierreCajas as CierreCajas;
use App\Models\SYS_grupos as Grupos;
use App\Models\SYS_grupoMiembros;
use App\Models\SYS_grupoLideres;
use App\Models\CTB_tipoGasto;
use App\Models\CTB_gasto;
use App\Models\CTB_tipoDocumento;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Mail;

class FofiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FondosFijos::with('usuario')->with('autorizador')->get();
    }

    // Mandar lista de fofis
    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $administrador = false;
        $jefatura = false;
        $fofis = FondosFijos::where('idUsuario', $userID)->with('usuario')->with('autorizador')->with('detalles.tipoDoc', 'detalles.tipoGasto', 'detalles.gasto')->get();
        
        $user = User::where('id', $userID)->with('roles')->get();
        // Mantener aquellos que cumplen con los roles del usuario:
        if ($user[0]['roles'] == "[]"){
            array_push($roles,'');
        }
        else{
            foreach ($user[0]['roles'] as $rol){
                if ($rol['name'] == 'Super-Administrador'){
                    array_push($roles, $rol['name']);
                    $administrador = true;
                }
                if ($rol['name'] == 'Contabilidad_Jefatura'){
                    array_push($roles, $rol['name']);
                    $jefatura = true;
                }
            }
        }
        if($jefatura == true){
            $fofis = FondosFijos::with('usuario')->with('autorizador')->with('detalles.tipoDoc', 'detalles.tipoGasto', 'detalles.gasto')->get();
        }
        if($administrador == true){
            $fofis = FondosFijos::with('usuario')->with('autorizador')->with('detalles.tipoDoc', 'detalles.tipoGasto', 'detalles.gasto')->get();
        }
        
        if ($jefatura == false && $administrador == false){
            $gruposLider = SYS_grupoLideres::where('idUsuario',$userID)->pluck('idGrupo');
            $miembrosGrupo = SYS_grupoMiembros::whereIn('idGrupo',$gruposLider)->pluck('idUsuario');
            $miembrosGrupo->add($userID);
            $fofis = FondosFijos::whereIn('idUsuario', $miembrosGrupo)->with('usuario')->with('autorizador')->with('detalles.tipoDoc', 'detalles.tipoGasto', 'detalles.gasto')->get();
        }

        return $fofis;
    }

    //Extraer lista de gastos segun el tipo de gasto
    public function getGastos(){
        $gastos = CTB_gasto::all();
        return $gastos;
    }

    //Extraer lista de tipos de gastos

    public function getTipoGasto(){
        $tiposGastos = CTB_tipoGasto::all();
        return $tiposGastos;
    }

    //Extraer lista de tipos de documentos

    public function getTipoDoc(){
        $tiposDoc = CTB_tipoDocumento::all();
        return $tiposDoc;
    }

    //Extraer los detalles de un fofi en especifico

    public function getDetalleFofis($id){
        $detalles = CTB_detalleFofi::where('idFofi',$id)->with('tipoGasto')->with('gasto')->with('tipoDoc')->get();
        return $detalles;
    }

    //Guardar dentro de la base de datos el fofi y sus detalles

    public function store(Request $request)
    {
        $fofi = $request[0];
        $requestFofi = new Request($fofi);
        $input = $requestFofi->all();
        FondosFijos::create($input);

        $detallesFofi = $request[1];
        
        foreach ($detallesFofi as $detalleFofi){

            $requestDetallesFofi = new Request($detalleFofi);
            $idFofi = FondosFijos::latest('id')->first()->id;
            $contenidoBinario = file_get_contents($requestDetallesFofi['foto']);
            $imagen = base64_encode($contenidoBinario);

            $requestDetallesFofi['foto'] = $imagen;
            $requestDetallesFofi['idFofi'] = $idFofi;

            $input = $requestDetallesFofi->all();

            CTB_detalleFofi::create($input);
        }
        return response()->json('Se ha guardado todo con exito');
    }

    //Actualizar los fofi ** No funcional aun

    public function update(Request $request, $id)
    {
        $campos=[
            'idTipoDocumento' => 'required',
            'fecha' => 'required',
            'nroDocumento' => 'required',
            'rut' => 'required',
            'tipoGasto' => 'required',
            'gasto' => 'required',
            'monto' => 'required',
            'observaciones' => 'required',
        ];
        $mensaje = [
            'idTipoDocumento.required' => 'El campo "Tipo Documento" es obligatorio.',
            'fecha.required' => 'El campo "Fecha" es obligatorio.',
            'nroDocumento.required' => 'El campo "N° Documento" es obligatorio.',
            'rut.required' => 'El campo de "Rut" es obligatorio.',
            'tipoGasto.required' => 'El campo "Tipo Gasto" es obligatorio.',
            'gasto.required' => 'El campo "Gasto" es obligatorio.',
            'monto.required' => 'El campo "Monto" es obligatorio.',
            'observaciones.required' => 'El campo "Observaciones" es obligatorio.',
        ];
        $this->validate($request,$campos, $mensaje);

        $input = $request->all();
        return response()->json($input);
        if ($request->hasFile('foto')) {
            $url = $request->foto->store('imgs');
        }
        
        return response()->json($input);
        $input['foto'] = $url;
        $fofi = FondosFijos::find($id);
        $fofi->update($input);
        
    }

    //Obtener ultimo id de folio fofi y fecha actual

    public function dataFofi($id){
        $registros = FondosFijos::all();
        if ($registros == '[]'){
            $folioFofi = 1;
        }else{
            $folioFofi = FondosFijos::latest('id')->first()->id;
            $folioFofi = $folioFofi + 1;
        }
        $fecha = Carbon::now()->format('d-m-Y');
        $datos = [];
        $usuario = User::where('id',$id)->get('name');

        $fondoFijoAsignado = FondosFijos::where('estado','<>','Retirado')->where('estado','<>','Rechazado')->where('idUsuario',$id)->sum('montoTotal');
        array_push($datos, $folioFofi);
        array_push($datos, $fecha);
        array_push($datos, $usuario[0]['name']);
        array_push($datos, $fondoFijoAsignado);

        return $datos;
    }

    //Enviar correos

    public function sendEmailTienda(Request $request){
        // Datos de Entrada:
        $fofi = $request[0];
        $requestFofi = new Request($fofi);
        $fofiAsignado_formateado = '$ ' . number_format($requestFofi['fofiAsignado'], 0, '', '.');
        $montoTotal_formateado = '$ ' . number_format($requestFofi['montoTotal'], 0, '', '.');
        $diferencia_formateado = '$ ' . number_format($requestFofi['fofiAsignado'] - $requestFofi['montoTotal'], 0, '', '.');
        // Variables: 
        $username = "Register_AMPHORAMKTCL";
        $password = '8634-E$6xO$$F$6Ex125';
        $userpwd = $username.":".$password;
        $usuarioFofi = User::where('id',$requestFofi['idUsuario'])->get();
        //Variables Especiales:
        $color = "#000000";
        
        $url = url()->current(); // Obtiene la URL actual

        $parts = parse_url($url); // Analiza los componentes de la URL
        
        $domain = $parts['scheme'] . '://' . $parts['host']; // Obtiene el dominio
        
        if (isset($parts['port'])) {
            $domain .= ':' . $parts['port']; // Si hay puerto, lo concatena al dominio
        }

        $detallesFofi = $request[1];

        // Creación Output

        $output = null;
        foreach ($detallesFofi as $detalleFofi) {
            $contenidoBinario = file_get_contents($detalleFofi['foto']);
            $imagen = base64_encode($contenidoBinario);
            $id = $detalleFofi['idDetalle'] + 1;
            $nomTipoDoc = CTB_tipoDocumento::where('id',$detalleFofi['idTipoDocumento'])->get();
            $nomTipoGasto = CTB_tipoGasto::where('id',$detalleFofi['idTipoGasto'])->get();
            $nomGasto = CTB_gasto::where('id',$detalleFofi['idGasto'])->get();
            
            if($detalleFofi['nroDocumento'] == null){
                $numDocumento = "---";
            }else{
                $numDocumento = $detalleFofi['nroDocumento'];
            }

            if($detalleFofi['rut'] == null){
                $rutDocumento = "---";
            }else{
                $rutDocumento = $detalleFofi['rut'];
            }
            $monto_formateado = '$ ' . number_format($detalleFofi['monto'], 0, '', '.');
            // Construcción Tabla:
            $output .='
            <tr>
                <td>'.$id.'</td>
                <td>'.$nomTipoDoc[0]['nombre'].'</td>
                <td>'.$detalleFofi['fecha'].'</td>
                <td>'.$numDocumento.'</td>
                <td>'.$rutDocumento.'</td>
                <td>'.$nomTipoGasto[0]['nombre'].'</td>
                <td>'.$nomGasto[0]['nombre'].'</td>
                <td>'.$monto_formateado.'</td>
            </tr>';
            // <td><a href = "data:image/jpeg;base64,'.$imagen.'" target="_blank">Foto</a></td>
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
                <h2> Se ha registrado un nuevo fondo fijo: </h2>
                <h4> Enlace directo: </h4>
                <a class='btn' href='".$domain."/fondos-fijos'>Ver Fondos fijos</a>
                <br>
                <h4> Datos fondo fijo: </h4>
                <table id='customers'>
                    <thead>
                        <tr>
                            <th>N° Folio</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Cantidad Gastos</th>
                            <th>Fofi Asignado</th>
                            <th>Total Gastos</th>
                            <th>Diferencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>".$requestFofi['folioFofi']."</td>
                            <td>".$requestFofi['fecha']."</td>
                            <td>".$usuarioFofi[0]['name']."</td>
                            <td>".$requestFofi['cantidadGastos']."</td>
                            <td>".$fofiAsignado_formateado."</td>
                            <td>".$montoTotal_formateado."</td>
                            <td>".$diferencia_formateado."</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <h4> Detalles fondos fijos: </h4>";

                if($output != null){
                    $html.="<table id='customers'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo Doc.</th>
                                <th>Fecha</th>
                                <th>N° Doc.</th>
                                <th>Rut</th>
                                <th>Tipo Gasto</th>
                                <th>Gasto</th>
                                <th>Monto</th>
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
                        // "gestion_comercial@amphora.cl",
                        // $usuarioFofi[0]['email'],
                    ]
                ],
                "BCC" => [
                    "Email" => [
                        // "informatica@amphora.cl",
                        "maballay@amphora.cl",
                        // "pbocaz@amphora.cl"
                    ]
                ],
                "CC" => [
                    "Email" => [
                        // "proveedores@amphora.cl",
                        // "gacostal@amphora.cl",
                    ]
                ],
                "Message" => [
                "Subject" => "Fondo fijo creado exitosamente - Amphora",
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
        return response()->json("ENVIADO");
    }

    public function sendEmail(Request $request){
        // Datos de Entrada:
        $fofi = $request[0];
        $requestFofi = new Request($fofi);
        $fofiAsignado_formateado = '$ ' . number_format($requestFofi['fofiAsignado'], 0, '', '.');
        $montoTotal_formateado = '$ ' . number_format($requestFofi['montoTotal'], 0, '', '.');
        $diferencia_formateado = '$ ' . number_format($requestFofi['fofiAsignado'] - $requestFofi['montoTotal'], 0, '', '.');
        // Variables: 
        $username = "Register_AMPHORAMKTCL";
        $password = '8634-E$6xO$$F$6Ex125';
        $userpwd = $username.":".$password;
        $usuarioFofi = User::where('id',$requestFofi['idUsuario'])->get();
        // Variables Especiales:
        $color = "#000000";
        
        // URL
        $url = url()->current(); // Obtiene la URL actual
        $parts = parse_url($url); // Analiza los componentes de la URL
        $domain = $parts['scheme'] . '://' . $parts['host']; // Obtiene el dominio
        if (isset($parts['port'])) {
            $domain .= ':' . $parts['port']; // Si hay puerto, lo concatena al dominio
        }

        $detallesFofi = $request[1];

        // Creación Output
        $output = null;
        foreach ($detallesFofi as $detalleFofi) {
            $contenidoBinario = file_get_contents($detalleFofi['foto']);
            $imagen = base64_encode($contenidoBinario);
            $id = $detalleFofi['idDetalle'] + 1;
            $nomTipoDoc = CTB_tipoDocumento::where('id',$detalleFofi['idTipoDocumento'])->get();
            $nomTipoGasto = CTB_tipoGasto::where('id',$detalleFofi['idTipoGasto'])->get();
            $nomGasto = CTB_gasto::where('id',$detalleFofi['idGasto'])->get();
            // return response()->json($imagen);
            if($detalleFofi['nroDocumento'] == null){
                $numDocumento = "---";
            }else{
                $numDocumento = $detalleFofi['nroDocumento'];
            }

            if($detalleFofi['rut'] == null){
                $rutDocumento = "---";
            }else{
                $rutDocumento = $detalleFofi['rut'];
            }
            $monto_formateado = '$ ' . number_format($detalleFofi['monto'], 0, '', '.');
            // Construcción Tabla:
            $output .='
            <tr>
                <td>'.$id.'</td>
                <td>'.$nomTipoDoc[0]['nombre'].'</td>
                <td>'.$detalleFofi['fecha'].'</td>
                <td>'.$numDocumento.'</td>
                <td>'.$rutDocumento.'</td>
                <td>'.$nomTipoGasto[0]['nombre'].'</td>
                <td>'.$nomGasto[0]['nombre'].'</td>
                <td>'.$monto_formateado.'</td>

            </tr>';
            // <td><a href = "data:image/jpeg;base64,'.$imagen.'" target="_blank">Foto</a></td>
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
                <h2> Se ha registrado un nuevo fondo fijo: </h2>
                <h4> Enlace directo: </h4>
                <a class='btn' href='".$domain."/fondos-fijos'>Ver Fondos fijos</a>
                <br>
                <h4> Datos fondo fijo: </h4>
                <table id='customers'>
                    <thead>
                        <tr>
                            <th>N° Folio</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Cantidad Gastos</th>
                            <th>Fofi Asignado</th>
                            <th>Total Gastos</th>
                            <th>Diferencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>".$requestFofi['folioFofi']."</td>
                            <td>".$requestFofi['fecha']."</td>
                            <td>".$usuarioFofi[0]['name']."</td>
                            <td>".$requestFofi['cantidadGastos']."</td>
                            <td>".$fofiAsignado_formateado."</td>
                            <td>".$montoTotal_formateado."</td>
                            <td>".$diferencia_formateado."</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <h4> Detalles fondos fijos: </h4>";

                if($output != null){
                    $html.="<table id='customers'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo Doc.</th>
                                <th>Fecha</th>
                                <th>N° Doc.</th>
                                <th>Rut</th>
                                <th>Tipo Gasto</th>
                                <th>Gasto</th>
                                <th>Monto</th>
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
                        // $usuarioFofi[0]['email'],
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
                        // "proveedores@amphora.cl",
                        // "gacostal@amphora.cl",
                    ]
                ],
                "Message" => [
                "Subject" => "Fondo fijo creado exitosamente - Amphora",
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
            ],

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
            echo $response;
        }
        return response()->json("ENVIADO");
    }

    public function verificarDetalle(Request $request)
    {
        $nroDoc = $request[0];
        $rut = $request[1];
        $existentes = CTB_detalleFofi::where('nroDocumento',$nroDoc)->where('rut',$rut)->count();
        return response()->json($existentes); 
    }
    
    //Eliminar en cascada un fofi 
    public function destroy($id)
    {
        FondosFijos::find($id)->delete();
        CTB_detalleFofi::where('idFofi',$id)->delete();
        return response()->json("Fofi Eliminado");
    }

    public function autorizables(Request $request){
        $userID = $request["userID"];
        // Seleccionar datos relacionados con el usuario:
        $fondosFijos = FondosFijos::with("usuario")->with("autorizador.usuario")->where('estado','Pendiente')->get();
        $user = User::where('id', $userID)->with('roles')->get();
        // Mantener aquellos que cumplen con los roles del usuario:
        $administrador = false;
        $jefatura = false;
        $roles = [];
        if ($user[0]['roles'] == "[]"){
            array_push($roles,'');
        }
        else{
            foreach ($user[0]['roles'] as $rol){
                if ($rol['name'] == 'Super-Administrador'){
                    array_push($roles, $rol['name']);
                    $administrador = true;
                }
                if ($rol['name'] == 'Contabilidad_Jefatura'){
                    array_push($roles, $rol['name']);
                    $jefatura = true;
                }
            }
        }
        if($jefatura == true){
            $fondosFijos = FondosFijos::with("usuario")->with("autorizador.usuario")->where('estado','Pendiente')->get();
        }
        if($administrador == true){
            $fondosFijos = FondosFijos::with("usuario")->with("autorizador.usuario")->where('estado','Pendiente')->get();
        }
        return $fondosFijos;
    }

    public function autorizar(Request $request)
    {
        $data = $request[0];
        $userID = $request[1];
        foreach ($data as $row) {
            $fondoFijo = FondosFijos::where('id', $row['id'])->first();
            // Autorizador == FALSE
            if ($row['autorizado'] === false || $row['autorizado'] === "false"){
                $fondoFijo->idAutorizador = $userID;
                $fondoFijo->objeciones = $row['objeciones'];
                $fondoFijo->autorizado = filter_var($row['autorizado'], FILTER_VALIDATE_BOOLEAN);
                $fondoFijo->estado = 'Rechazado';
                $fondoFijo->fechaAuth = Carbon::now();
            }
            // Autorizador == TRUE
            if ($row['autorizado'] === true || $row['autorizado'] === "true"){
                $fondoFijo->idAutorizador = $userID;
                $fondoFijo->objeciones = $row['objeciones'];
                $fondoFijo->autorizado = filter_var($row['autorizado'], FILTER_VALIDATE_BOOLEAN);
                $fondoFijo->estado = 'Aprobado';
                $fondoFijo->fechaAuth = Carbon::now();
            }
            $fondoFijo->save();
        }
        return $fondoFijo;
    }

    public function sendEmailEstadoActualizado(Request $request){
        // Datos de Entrada:
        $fondoFijo = FondosFijos::find($request['fondoFijoID']);
        $cierreCaja = null;
        $cierresCajas = CierreCajas::all();
        foreach ($cierresCajas as $cierreCajaTemp) {
            if($cierreCajaTemp['idRefFondoFijo'] == $request['fondoFijoID']){
                $cierreCaja = $cierreCajaTemp;
                break;
            }
        }
        $usuarioFofi = User::find($fondoFijo['idUsuario']);
        // Variables: 
        $username = "Register_AMPHORAMKTCL";
        $password = '8634-E$6xO$$F$6Ex125';
        $userpwd = $username.":".$password;
        // Variables Especiales:
        $color = "#000000";
        // URL
        $url = url()->current(); // Obtiene la URL actual
        $parts = parse_url($url); // Analiza los componentes de la URL
        $domain = $parts['scheme'] . '://' . $parts['host']; // Obtiene el dominio
        if (isset($parts['port'])) {
            $domain .= ':' . $parts['port']; // Si hay puerto, lo concatena al dominio
        }

        // Creación Output
        $output = null;
        $output2 = null;
        // Construcción Tabla Fondo Fijo:
        $fofiAsignado_formateado = '$ ' . number_format($fondoFijo['fofiAsignado'], 0, '', '.');
        $montoTotal_formateado = '$ ' . number_format($fondoFijo['montoTotal'], 0, '', '.');
        $diferencia_formateado = '$ ' . number_format($fondoFijo['fofiAsignado'] - $fondoFijo['montoTotal'], 0, '', '.');
        $output .='
            <tr>
                <td>'.$fondoFijo['id'].'</td>
                <td>'.$fondoFijo['estado'].'</td>
                <td>'.$fondoFijo['folioFofi'].'</td>
                <td>'.$fondoFijo['fecha'].'</td>
                <td>'.$fondoFijo['cantidadGastos'].'</td>
                <td>'.$fofiAsignado_formateado.'</td>
                <td>'.$montoTotal_formateado.'</td>
                <td>'.$diferencia_formateado.'</td>
            </tr>';

        // Construcción Tabla Cierre de Caja:
        // NO ES UTILIZADO POR AHORA!    NO ES UTILIZADO POR AHORA!    NO ES UTILIZADO POR AHORA!
        $output2 .='
            <tr>
                <td>'.$cierreCaja['ID'].'</td>
                <td>'.$cierreCaja['TIENDA'].'</td>
                <td>'.$cierreCaja['FECHA DÍA CIERRE'].'</td>
                <td>'.$cierreCaja['TARJETA'].'</td>
                <td>'.$cierreCaja['EFECTIVO'].'</td>
                <td>'.$cierreCaja['FONDO FIJO'].'</td>
                <td>'.$cierreCaja['FOLIO FONDO FIJO REFERENCIAL'].'</td>
                <td>'.$cierreCaja['NOTA DE CRÉDITO'].'</td>
                <td>'.$cierreCaja['OTROS MONTOS'].'</td>
                <td>'.$cierreCaja['VENTA TOTAL'].'</td>
                <td>'.$cierreCaja['VENTA BRUTA'].'</td>
                <td>'.$cierreCaja['DEPÓSITO'].'</td>
                <td>'.$cierreCaja['FECHA DEPÓSITO'].'</td>
                <td>'.$cierreCaja['DIFERENCIA DÍAS'].'</td>
                <td>'.$cierreCaja['CONTROL'].'</td>
            </tr>';

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
                <h4> Enlace directo: </h4>
                <a class='btn' href='".$domain."/fondos-fijos'>Ver Fondos fijos</a>
                <br>
                <h4> Fondo Fijo: </h4>";

                if($output != null){
                    $html.="<table id='customers'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Estado</th>
                                <th>Folio</th>
                                <th>Fecha</th>
                                <th>Cantidad Gastos</th>
                                <th>Asignado</th>
                                <th>Monto Total</th>
                                <th>Diferencia</th>
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

        $emailData = json_encode([
            "GeneralData" => [
                "FromName" => "No Reply - Amphora",
                "From" => "noreply@amphora.cl",
                "To" => [
                    "Email" => [
                        "bcontreras@amphora.cl",
                        // $usuarioFofi[0]['email'],
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
                        // "proveedores@amphora.cl",
                        // "gacostal@amphora.cl",
                    ]
                ],
                "Message" => [
                "Subject" => "Fondo Fijo utilizado en Cierre de Caja.",
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
            ],

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
            echo $response;
        }
        return response()->json("ENVIADO");
    }

    public function sendEmailFondoFijoAutorizado(Request $request){
        // Datos de Entrada:
        $fondosFijos = $request[0];
        $usuarioFofi = User::find($request[1]);
        
        // Variables: 
        $username = "Register_AMPHORAMKTCL";
        $password = '8634-E$6xO$$F$6Ex125';
        $userpwd = $username.":".$password;
        // Variables Especiales:
        $color = "#000000";
        // URL
        $url = url()->current(); // Obtiene la URL actual
        $parts = parse_url($url); // Analiza los componentes de la URL
        $domain = $parts['scheme'] . '://' . $parts['host']; // Obtiene el dominio
        if (isset($parts['port'])) {
            $domain .= ':' . $parts['port']; // Si hay puerto, lo concatena al dominio
        }

        // Creación Output
        $output = null;
        // Construcción Tabla:
        foreach ($fondosFijos as $fondoFijoTemp) {
            $fondoFijo = FondosFijos::find($fondoFijoTemp['id']);
            $fofiAsignado_formateado = '$ ' . number_format($fondoFijo['fofiAsignado'], 0, '', '.');
            $montoTotal_formateado = '$ ' . number_format($fondoFijo['montoTotal'], 0, '', '.');
            $diferencia_formateado = '$ ' . number_format($fondoFijo['fofiAsignado'] - $fondoFijo['montoTotal'], 0, '', '.');
            $output .='
                <tr>
                    <td>'.$fondoFijo['id'].'</td>
                    <td>'.$fondoFijo['estado'].'</td>
                    <td>'.$fondoFijo['folioFofi'].'</td>
                    <td>'.$fondoFijo['fecha'].'</td>
                    <td>'.$fondoFijo['cantidadGastos'].'</td>
                    <td>'.$fofiAsignado_formateado.'</td>
                    <td>'.$montoTotal_formateado.'</td>
                    <td>'.$diferencia_formateado.'</td>
                    <td>'.$fondoFijo['objeciones'].'</td>
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
                <h4> Enlace directo: </h4>
                <a class='btn' href='".$domain."/fondos-fijos'>Ver Fondos fijos</a>
                <br>
                <h4> Fondos Fijos: </h4>";
                if($output != null){
                    $html.="<table id='customers'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Estado</th>
                                <th>Folio</th>
                                <th>Fecha</th>
                                <th>Cantidad Gastos</th>
                                <th>Asignado</th>
                                <th>Monto Total</th>
                                <th>Diferencia</th>
                                <th>Objeciones</th>
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

        $emailData = json_encode([
            "GeneralData" => [
                "FromName" => "No Reply - Amphora",
                "From" => "noreply@amphora.cl",
                "To" => [
                    "Email" => [
                        "bcontreras@amphora.cl",
                        // $usuarioFofi[0]['email'],
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
                        // "proveedores@amphora.cl",
                        // "gacostal@amphora.cl",
                    ]
                ],
                "Message" => [
                "Subject" => "Fondos Fijos revisados por autorizador.",
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
            ],

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
            echo $response;
        }
        return response()->json("ENVIADO");
    }
}
