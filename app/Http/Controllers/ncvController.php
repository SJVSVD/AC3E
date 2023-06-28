<?php

namespace App\Http\Controllers;

use App\Models\SYS_sucursales;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\TIE_productos;
use App\Models\TIE_NotasCredito;
use App\Models\TIE_DetallesPagoNCV;
use App\Models\User;
use Carbon\Carbon;

class ncvController extends Controller
{
    public function añadirImagen(Request $request){
        $imagen = $request[0];
        $userID = $request[1];
        
        $ncv = TIE_NotasCredito::where('id', $userID)->first();
        $contenidoBinario = file_get_contents($imagen);
        $imagen = base64_encode($contenidoBinario);

        $ncv->imagen = $imagen;
        $ncv->save();
        return response()->json($imagen);
    }

    public function autorizarPago(Request $request)
    {
        $data = $request[0];
        $userID = $request[1];
        foreach ($data as $row) {
            $ncv = TIE_NotasCredito::where('id', $row['id'])->first();
            // Autorizador == FALSE
            if ($row['autorizadoPago'] === false || $row['autorizadoPago'] === "false"){
                $ncv->idAutorizadorPago = $userID;
                $ncv->autorizadoPago = filter_var($row['autorizadoPago'], FILTER_VALIDATE_BOOLEAN);
                $ncv->estado = 'Rechazado';
                $ncv->fechaAuthPago = Carbon::now();
            }
            // Autorizador == TRUE
            if ($row['autorizadoPago'] === true || $row['autorizadoPago'] === "true"){
                $ncv->idAutorizadorPago = $userID;
                $ncv->autorizadoPago = filter_var($row['autorizadoPago'], FILTER_VALIDATE_BOOLEAN);
                $ncv->estado = 'Pagado';
                $ncv->fechaAuthPago = Carbon::now();
            }
            $ncv->save();
        }
        return $ncv;
    }

    public function editarProductos(Request $request){
        // Iterar sobre los elementos del arreglo
        $datos = $request->request->all();
        foreach ($datos as $dato) {
            // Obtener el ID del elemento
            // return response()->json($dato);
            $id = $dato['id'];
            
            $ncv = TIE_productos::find($id);
            $ncv->motivo = $dato['motivo'];
            $ncv->save();

        }
        return response()->json('Se ha completado');
        
    }

    public function verificarRegistros(){

        // CURL INICIO DE SESIÓN:
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_URL => "https://200.14.247.165:50000/b1s/v1/Login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"{\"CompanyDB\":\"PRD_INTERANDINA\",\"UserName\":\"manager\",\"Password\":\"Amphora.20\"}\n",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response,true);
    
        $id_sesion = $response['SessionId'];

        curl_close($curl);

        $fecha_ayer = date('Y-m-d', strtotime('-1 day'));

        // $url = 'https://200.14.247.165:50000/b1s/v1/$crossjoin(Invoices,U_GSP_TPVCAP,U_GSP_TPVLIN,U_GSP_TPVDOCREL)?$expand=U_GSP_TPVCAP($select=U_GSP_CABOTI,%20U_GSP_CADATA,%20U_GSP_CACLIE,U_GSP_CACLNOM,%20U_GSP_CANIF,%20U_GSP_BOLETA,%20U_GSP_CATOTA),U_GSP_TPVLIN($select=U_GSP_LIARTI,U_GSP_LIDES1,U_GSP_LIQUAN,U_GSP_LIIMPRML,U_GSP_LIVENE),U_GSP_TPVDOCREL($select=U_GSP_WHSCODE,U_GSP_BASEDOCTYPE,U_GSP_BASETABLE,U_GSP_BASEBOLETA),%20Invoices($select=CardCode,DocDate,FolioNumber,%20DocTotal)%20&$filter=U_GSP_TPVCAP/Code%20eq%20U_GSP_TPVLIN/U_GSP_DOCCODE%20and%20U_GSP_TPVCAP/Code%20eq%20U_GSP_TPVDOCREL/U_GSP_DOCCODE%20and%20U_GSP_TPVCAP/U_GSP_CADOCU%20eq%20%27VTD%27%20and%20U_GSP_TPVCAP/U_GSP_CADATA%20ge%20%27'.$fecha_ayer.'%27%20and%20Invoices/FolioNumber%20eq%20U_GSP_TPVDOCREL/U_GSP_BASEBOLETA';

        $url = 'https://200.14.247.165:50000/b1s/v1/$crossjoin(Invoices,U_GSP_TPVCAP,U_GSP_TPVLIN,U_GSP_TPVDOCREL)?$expand=U_GSP_TPVCAP($select=U_GSP_CABOTI,%20U_GSP_CADATA,%20U_GSP_CACLIE,U_GSP_CACLNOM,%20U_GSP_CANIF,%20U_GSP_BOLETA,%20U_GSP_CATOTA),U_GSP_TPVLIN($select=U_GSP_LIARTI,U_GSP_LIDES1,U_GSP_LIQUAN,U_GSP_LIIMPRML,U_GSP_LIVENE),U_GSP_TPVDOCREL($select=U_GSP_WHSCODE,U_GSP_BASEDOCTYPE,U_GSP_BASETABLE,U_GSP_BASEBOLETA),%20Invoices($select=CardCode,DocDate,FolioNumber,%20DocTotal)%20&$filter=U_GSP_TPVCAP/Code%20eq%20U_GSP_TPVLIN/U_GSP_DOCCODE%20and%20U_GSP_TPVCAP/Code%20eq%20U_GSP_TPVDOCREL/U_GSP_DOCCODE%20and%20%28U_GSP_TPVCAP/U_GSP_CADOCU%20eq%20%27VTD%27%20or%20U_GSP_TPVCAP/U_GSP_CADOCU%20eq%20%27VAB%27%29%20and%20U_GSP_TPVCAP/U_GSP_CADATA%20ge%20%27'.$fecha_ayer.'%27%20and%20Invoices/FolioNumber%20eq%20U_GSP_TPVDOCREL/U_GSP_BASEBOLETA';

        $curl1 = curl_init();
        curl_setopt_array($curl1, array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Accept: */*",
            "Cache-Control: no-cache",
            "Connection: keep-alive",
            "Cookie: B1SESSION=".$id_sesion."; CompanyDB=PRDSTGO; ROUTEID=.node1",
            "cache-control: no-cache",
        ),));
        $response1 = curl_exec($curl1);
        curl_close($curl1);
        // $this->info($response1['value']);
        // Decodificar la respuesta JSON en un arreglo o un objeto
        $data = json_decode($response1, true);
        // Convertir el arreglo en formato JSON
        $contadorRegistros = 0;
        foreach ($data['value'] as $info) {
            $contadorArticulos = 0;

            foreach ($data['value'] as $aux){
                if ($info['U_GSP_TPVCAP']['U_GSP_BOLETA'] == $aux['U_GSP_TPVCAP']['U_GSP_BOLETA']){
                    $contadorArticulos = $contadorArticulos + 1;
                }
            }

            // $this->info($contadorArticulos);
            $existentes = 0;
            $existentes = TIE_NotasCredito::where('notaCredito',$info['U_GSP_TPVCAP']['U_GSP_BOLETA'])->count();
            // $this->info(print_r($info['Invoices']['CardCode'], true));
            $idTienda = SYS_sucursales::where('bodega',$info['U_GSP_TPVCAP']['U_GSP_CABOTI'])->pluck('id');
            $idUsuario = SYS_sucursales::where('id',$idTienda[0])->pluck('idUsuario');
            // $info['U_GSP_TPVDOCREL']['U_GSP_BASEDOCTYPE'];
            $tipoPago = '';
            $credito = false;
            if ($info['U_GSP_TPVDOCREL']['U_GSP_BASEDOCTYPE'] == 'VTIR'){
                $tipoPago = 'Mercado Pago';
                $credito = true;
            }else{
                $cobroTarjeta = 0;
                $url2 = 'https://200.14.247.165:50000/b1s/v1/$crossjoin(U_GSP_TPVCAP,U_GSP_TPVCOB,CreditCards)?$expand=U_GSP_TPVCOB($select=U_GSP_PAYMETHOD,U_GSP_AMOUNT),CreditCards($select=CompanyID)&$filter=U_GSP_TPVCAP/Code%20eq%20U_GSP_TPVCOB/U_GSP_DOCCODE%20and%20U_GSP_TPVCAP/U_GSP_BOLETA%20eq%20%27'.$info["Invoices"]["FolioNumber"].'%27%20and%20U_GSP_TPVCOB/U_GSP_TCCREDITCARD%20eq%20CreditCards/CreditCardCode';

                $curl2 = curl_init();
                curl_setopt_array($curl2, array(
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                    CURLOPT_URL => $url2,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Accept: */*",
                    "Cache-Control: no-cache",
                    "Connection: keep-alive",
                    "Cookie: B1SESSION=".$id_sesion."; CompanyDB=PRDSTGO; ROUTEID=.node1",
                    "cache-control: no-cache",
                ),));
                $response2 = curl_exec($curl2);
                curl_close($curl2);

                $data2 = json_decode($response2, true);

                if(count($data2['value']) == 0){
                    $tipoPago = 'Efectivo';
                }else{
                    foreach ($data2['value'] as $aux){
                        if($tipoPago == ''){
                            $tipoPago = $tipoPago.$aux['U_GSP_TPVCOB']['U_GSP_PAYMETHOD'].'-'.$aux['CreditCards']['CompanyID'];
                        }else{
                            $tipoPago = $tipoPago.','.$aux['U_GSP_TPVCOB']['U_GSP_PAYMETHOD'].'-'.$aux['CreditCards']['CompanyID'];
                        }
                        $credito = true;
                        $cobroTarjeta = $cobroTarjeta  + (int)$aux['U_GSP_TPVCOB']['U_GSP_AMOUNT'];
                    }
                }

            }

            if ($existentes == 0){
                $notaCredito = TIE_NotasCredito::create([
                    'estado' => 'Ingresado',
                    'fecha' => $info['U_GSP_TPVCAP']['U_GSP_CADATA'],
                    'idTienda' => $idTienda[0],
                    'idUsuario' => $idUsuario[0],
                    'notaCredito' => $info['U_GSP_TPVCAP']['U_GSP_BOLETA'],
                    'boleta' => $info['Invoices']['FolioNumber'],
                    'montoNotaCredito' => $info['U_GSP_TPVCAP']['U_GSP_CATOTA'],
                    'montoBoleta' => $info['Invoices']['DocTotal'],
                    'cantidadArtNCV' => $contadorArticulos,
                    'cantidadArtBoleta' => $contadorArticulos,
                    'metodoPago' => $tipoPago,
                    'rutCliente' => $info['U_GSP_TPVCAP']['U_GSP_CANIF'],
                    'nomCliente' => $info['U_GSP_TPVCAP']['U_GSP_CACLNOM'],
                    'credito' => $credito,
                ]);
                foreach ($data['value'] as $aux){
                    if ($info['U_GSP_TPVCAP']['U_GSP_BOLETA'] == $aux['U_GSP_TPVCAP']['U_GSP_BOLETA']){
                        $producto = TIE_productos::create([
                            'numNCV' => $info['U_GSP_TPVCAP']['U_GSP_BOLETA'],
                            'codigo' => $info['U_GSP_TPVLIN']['U_GSP_LIARTI'],
                            'descripcion' => $info['U_GSP_TPVLIN']['U_GSP_LIDES1'],
                            'cantidad' => $info['U_GSP_TPVLIN']['U_GSP_LIQUAN'],
                            'precio' => $info['U_GSP_TPVLIN']['U_GSP_LIIMPRML'],
                            'nroLinea' => $info['U_GSP_TPVLIN']['U_GSP_LIVENE'],
                        ]);
                    }
                }
                if ($credito == true){
                    $idNCV =  TIE_NotasCredito::where('notaCredito',$info['U_GSP_TPVCAP']['U_GSP_BOLETA'])->pluck('id');
                    $detallesPago = TIE_DetallesPagoNCV::create([
                        'idNCV' => $idNCV[0],
                        'tipoPago' => 'Devolución Credito',
                    ]);
                }
                $contadorRegistros = $contadorRegistros + 1;
            }else{
            }
        }
        return response()->json($contadorRegistros);
    }

    public function sendEmail(Request $request){
        // Datos de Entrada:

        $ncv = $request[0];
        $requestNCV = new Request($ncv);
        $montoNCV = '$ ' . number_format($requestNCV['montoNotaCredito'], 0, '', '.');
        $montoBoleta = '$ ' . number_format($requestNCV['montoBoleta'], 0, '', '.');
        // Variables: 
        $username = "Register_AMPHORAMKTCL";
        $password = '8634-E$6xO$$F$6Ex125';
        $userpwd = $username.":".$password;
        $usuarioNCV = User::where('id',$requestNCV['idUsuario'])->get();
        $tiendaNCV = SYS_sucursales::where('id',$requestNCV['idTienda'])->get();
        // Variables Especiales:
        $color = "#000000";
        
        // URL
        $url = url()->current(); // Obtiene la URL actual
        $parts = parse_url($url); // Analiza los componentes de la URL
        $domain = $parts['scheme'] . '://' . $parts['host']; // Obtiene el dominio
        if (isset($parts['port'])) {
            $domain .= ':' . $parts['port']; // Si hay puerto, lo concatena al dominio
        }

        $detallesNCV = $request[1];

        // Creación Output
        $output = null;
        foreach ($detallesNCV as $detalleNCV) {
            $id = $detalleNCV['idDetalle'] + 1;
            if($detalleNCV['banco'] == null){
                $banco = "---";
            }else{
                $banco = $detalleNCV['banco'];
            }

            if($detalleNCV['tipoCuenta'] == null){
                $tipoCuenta = "---";
            }else{
                $tipoCuenta = $detalleNCV['tipoCuenta'];
            }

            if($detalleNCV['cuenta'] == null){
                $cuenta = "---";
            }else{
                $cuenta = $detalleNCV['cuenta'];
            }

            if($detalleNCV['rut'] == null){
                $rut = "---";
            }else{
                $rut = $detalleNCV['rut'];
            }
            // Construcción Tabla:
            $output .='
            <tr>
                <td>'.$id.'</td>
                <td>'.$detalleNCV['tipoPago'].'</td>
                <td>'.$banco.'</td>
                <td>'.$tipoCuenta.'</td>
                <td>'.$cuenta.'</td>
                <td>'.$rut.'</td>
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
                <h2> Se ha registrado una nueva Nota de Credito: </h2>
                <h4> Enlace directo: </h4>
                <a class='btn pb-2' href='".$domain."/ncv'>Ver Notas de Credito</a>
                <br>
                <h4>Información General:</h4>
                    <h4 class='col-4 m-0 fs-8 pr-2'> Tienda: <label class='fw-normal' style='font-size: 14px;'> ". $tiendaNCV[0]['nombre'] ."</label></h4>
                    <h4 class='col-4 m-0 fs-8'> Usuario: <label class='fw-normal' style='font-size: 14px;'>". $usuarioNCV[0]['name'] ."</label></h4>
                    <h4 class='col-4 m-0 fs-8'> NºNCV: <label class='fw-normal' style='font-size: 14px;'>". $requestNCV['notaCredito'] ."</label></h4>
                    <h4 class='col-4 m-0 fs-8'> NºBoleta: <label class='fw-normal' style='font-size: 14px;'>". $requestNCV['boleta'] ."</label></h4>
                    <h4 class='col-4 m-0 fs-8'> Monto NCV: <label class='fw-normal' style='font-size: 14px;'>". $montoNCV ."</label></h4>
                    <h4 class='col-4 m-0 fs-8'> Monto Boleta: <label class='fw-normal' style='font-size: 14px;'>". $montoBoleta ."</label></h4>
                    <h4 class='col-4 m-0 fs-8'> Rut Cliente: <label class='fw-normal' style='font-size: 14px;'>". $requestNCV['rutCliente'] ."</label></h4>
                    <h4 class='col-4 m-0 fs-8'> Nombre Cliente: <label class='fw-normal' style='font-size: 14px;'>". $requestNCV['nomCliente'] ."</label></h4>
                    <h4 class='col-4 m-0 fs-8 pb-2'> Motivo: <label class='fw-normal' style='font-size: 14px;'>". $requestNCV['motivo'] ."</label></h4>
                <br>
                <h4> Detalles: </h4>";

                if($output != null){
                    $html.="<table id='customers'>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo Pago</th>
                                <th>Banco</th>
                                <th>Tipo Cuenta</th>
                                <th>Cuenta</th>
                                <th>Rut</th>
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
                        // "gestion_comercial@amphora.cl",
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
                "Subject" => "Nota de Credito creada exitosamente - Amphora",
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

    public function store(Request $request)
    {
        $ncv = $request[0];
        $requestNCV = new Request($ncv);
        $input = $requestNCV->all();
        TIE_NotasCredito::create($input);

        $detallesNCV = $request[1];
        
        foreach ($detallesNCV as $detalleNCV){

            $requestDetalleNCV = new Request($detalleNCV);
            $idNCV = TIE_NotasCredito::latest('id')->first()->id;

            $requestDetalleNCV['idNCV'] = $idNCV;

            $input = $requestDetalleNCV->all();

            TIE_DetallesPagoNCV::create($input);
        }
        return response()->json('Se ha guardado todo con exito');
    }

    public function show($userID){
        // Seleccionar datos relacionados con el usuario:
        $roles = [];
        $admin = false;
        $ncvs = TIE_NotasCredito::where('idUsuario', $userID)->with('productos')->with('usuario')->with('tienda')->with('detalles')->get();
        $user = User::where('id', $userID)->with('roles')->get();
        // Mantener aquellos que cumplen con los roles del usuario:
        if ($user[0]['roles'] == "[]"){
            array_push($roles,'');
        }
        else{
            foreach ($user[0]['roles'] as $rol){
                if ($rol['name'] == 'Administrador'){
                    array_push($roles, $rol['name']);
                    $admin = true;
                }
            }
        }
        if($admin == true){
            $ncvs = TIE_NotasCredito::with('productos')->with('usuario')->with('tienda')->with('detalles')->get();
        }
        return $ncvs;
    }

    public function dataNCV($id){
        $registros = TIE_NotasCredito::all();
        if ($registros == '[]'){
            $nNCV = 1;
        }else{
            $nNCV = TIE_NotasCredito::latest('id')->first()->id;
            $nNCV = $nNCV + 1;
        }
        $fecha = Carbon::now()->format('d-m-Y');
        $datos = [];
        $usuario = User::where('id',$id)->get('name');
        
        array_push($datos, $nNCV);
        array_push($datos, $fecha);
        array_push($datos, $usuario[0]['name']);

        return $datos;
    }
    
    public function datosNCV($nNCV){
        // CURL INICIO DE SESIÓN:
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_URL => "https://200.14.247.165:50000/b1s/v1/Login",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>"{\"CompanyDB\":\"PRD_INTERANDINA\",\"UserName\":\"manager\",\"Password\":\"Amphora.20\"}\n",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $response = json_decode($response,true);
    
        $id_sesion = $response['SessionId'];

        curl_close($curl);

        $url = 'https://200.14.247.165:50000/b1s/v1/$crossjoin(Invoices,U_GSP_TPVCAP,U_GSP_TPVLIN,U_GSP_TPVDOCREL)?$expand=U_GSP_TPVCAP($select=U_GSP_CABOTI,%20U_GSP_CADATA,%20U_GSP_CACLIE,U_GSP_CACLNOM,%20U_GSP_CANIF,%20U_GSP_BOLETA,%20U_GSP_CATOTA),U_GSP_TPVLIN($select=U_GSP_LIARTI,U_GSP_LIDES1,U_GSP_LIQUAN,U_GSP_LIIMPRML,U_GSP_LIVENE),U_GSP_TPVDOCREL($select=U_GSP_WHSCODE,U_GSP_BASEDOCTYPE,U_GSP_BASETABLE,U_GSP_BASEBOLETA),%20Invoices($select=CardCode,DocDate,FolioNumber,%20DocTotal)%20&$filter=U_GSP_TPVCAP/Code%20eq%20U_GSP_TPVLIN/U_GSP_DOCCODE%20and%20U_GSP_TPVCAP/Code%20eq%20U_GSP_TPVDOCREL/U_GSP_DOCCODE%20and%20U_GSP_TPVCAP/U_GSP_CADOCU%20eq%20%27VTD%27%20and%20U_GSP_TPVCAP/U_GSP_BOLETA%20eq%20%27'.$nNCV.'%27%20and%20Invoices/FolioNumber%20eq%20U_GSP_TPVDOCREL/U_GSP_BASEBOLETA';

        $curl1 = curl_init();
        curl_setopt_array($curl1, array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json",
            "Accept: */*",
            "Cache-Control: no-cache",
            "Connection: keep-alive",
            "Cookie: B1SESSION=".$id_sesion."; CompanyDB=PRDSTGO; ROUTEID=.node1",
            "cache-control: no-cache",
        ),));
        $response1 = curl_exec($curl1);
        curl_close($curl1);
        return response()->json($response1);
    }

    public function update(Request $request, $id)
    {
        $ncv = $request[0];
        $requestNCV = new Request($ncv);
        $input = $requestNCV->all();

        $ncv2 = TIE_NotasCredito::find($id);
        $ncv2->update($input);

        $detallesNCV = $request[1];
        
        foreach ($detallesNCV as $detalleNCV){

            $requestDetalleNCV = new Request($detalleNCV);

            $requestDetalleNCV['idNCV'] = $id;

            $input = $requestDetalleNCV->all();

            TIE_DetallesPagoNCV::create($input);
        }

        return response()->json("NCV EDITADA");
    }

    public function destroy($id)
    {
        TIE_NotasCredito::find($id)->delete();
        TIE_DetallesPagoNCV::where('idNCV',$id)->delete();
        return response()->json("Nota de credito Eliminada");
    }
}
