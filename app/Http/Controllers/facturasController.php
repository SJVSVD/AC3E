<?php

namespace App\Http\Controllers;
use App\Models\CTB_facturas as Facturas;
use App\Models\SYS_autorizadores as Autorizadores;
use App\Models\SYS_proveedores as Proveedores;
use App\Models\User;
use Illuminate\Http\Request;
use League\Csv\Reader;
use Carbon\Carbon;
use App\Exports\FacturasExport;
use App\Exports\FacturasExportGastoAutorizado;
use App\Exports\FacturasExportPropuesta;
use App\Imports\ReferencesImport;
use Maatwebsite\Excel\Facades\Excel;

class facturasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function manipularCSV(Request $request)
    {
        // FILE:
        $file = $request->file('csv_file');

        // DATA:
        $csv_data = $file->get();
        
        // PATH:
        $csv_path = $file->getRealPath();
        
        // CSV READER:
        $csv = Reader::createFromPath($csv_path);
        $csv->setHeaderOffset(0);
        $csv->setDelimiter(';');
        // Header
        $header = $csv->getHeader();
        // Records
        $data = array();
        // Almacenar anterior:
        $anterior = [];
        $anterior['idFactura'] = null;
        // Añadir datos y eliminar duplicados:
        foreach ($csv as $record) {
            $record['idFactura'] = $record['RUT Proveedor'].$record['Folio'];
            if($anterior['idFactura'] != $record['idFactura']){
                $record['Identificador'] = null;
                $record['Estado'] = null;
                $record['Autorizable'] = null;
                $record['Autorizador1'] = null;
                $record['Autorizador2'] = null;
                $record['Observaciones'] = null;
                $record['Usuario'] = null;
                $anterior = $record;
                array_push($data, $record);
            }
        }
        return json_encode($data);
    }
    
    public function manipularXLS(Request $request)
    {
        // FILE:
        $file = $request->file('xls_file');

        foreach ($csv as $record) {
            $record['idFactura'] = $record['rutEmisor'].$record['folioDTE'];
            return ($record['idFactura']);
        }
        return json_encode($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request[0];
        $userID = $request[1];
        $empresa = $request[2];
        foreach ($data as $row) {
            $rowrequest = new Request($row);
            $campos=[
                'Autorizador1' => 'required_if:Autorizable,==,true',
            ];
            $mensaje = [
                'Autorizador1.required_if' => 'El Autorizador 1 es un campo obligatorio.',
            ];
            $this->validate($rowrequest, $campos, $mensaje);
        }
        $retorno = [];
        foreach ($data as $row) {
            //return($row);
            $creada = Facturas::create([
                'identificador' => '',
                'idFactura' => $row['idFactura'],
                'estado' => '-----',
                'rutProveedor' => $row['RUT Proveedor'],
                'razonSocial' => $row['Razon Social'],
                'folio' => $row['Folio'],
                'tipoDocumento' => $row['Tipo Doc'],
                'tipoCompra' => $row['Tipo Compra'],
                'fechaDcto' => $row['Fecha Docto'],
                'fechaRecepcion' => $row['Fecha Recepcion'],
                'fechaAcuse' => $row['Fecha Acuse'],
                'montoExento' => $row['Monto Exento'],
                'montoNeto' => $row['Monto Neto'],
                'montoIVARec' => $row['Monto IVA Recuperable'],
                'montoIVANoRec' => $row['Monto Iva No Recuperable'],
                'montoTotal' => $row['Monto Total'],
                'codigoIVANoRec' => $row['Codigo IVA No Rec.'],
                'montoNetoAF' => $row['Monto Neto Activo Fijo'],
                'IVAAF' => $row['IVA Activo Fijo'],
                'IVAUsoComun' => $row['IVA uso Comun'],
                'impSinCred' => $row['Impto. Sin Derecho a Credito'],
                'IVANoRetenido' => $row['IVA No Retenido'],
                'NCEoNDE' => $row['NCE o NDE sobre Fact. de Compra'],
                'otroImpuesto' => $row['Codigo Otro Impuesto'],
                'valorOtroImpuesto' => $row['Valor Otro Impuesto'],
                'tasaOtroImpuesto' => $row['Tasa Otro Impuesto'],
                'autorizable' => $row['Autorizable'],
                'idAutorizador1' => $row['Autorizador1'],
                'idAutorizador2' => $row['Autorizador2'],
                'observaciones' => $row['Observaciones'],
                'objeciones' => null,
                'fechaReq' => null,
                'autorizado' => null,
                'gastoAutorizado' => null,
                'pagoAutorizado' => null,
                'autorizadoAuth1' => null,
                'autorizadoAuth2' => null,
                'fechaAuthGasto' => null,
                'fechaAuthPago' => null,
                'idUsuarioSolicitante' => $userID,
                'idEmpresa' => $empresa,
            ]);
            $factura = Facturas::where('idFactura', $row['idFactura'])->first();
            if ($row['Autorizable'] == true){
                $factura->estado = 'Pendiente';
                if($row['Autorizador2'] !== null){
                    $authorizador2 = Autorizadores::find($row['Autorizador2']);
                    $factura->identificador = str_pad($factura['id'], 7, "0", STR_PAD_LEFT).$authorizador2['identificador'];
                }
                else{
                    $authorizador1 = Autorizadores::find($row['Autorizador1']);
                    $factura->identificador = str_pad($factura['id'], 7, "0", STR_PAD_LEFT).$authorizador1['identificador'];
                }
            }
            else{
                $factura->estado = 'No Autorizable';
                $factura->identificador = str_pad($factura['id'], 7, "0", STR_PAD_LEFT)."FNA";
            }
            $factura->save();
            array_push($retorno, $factura);
            
            // Revisar existencia del Proveedor, si no existe crear:
            $proveedor = Proveedores::where('rut', $factura['rutProveedor'])->first();
            if(!$proveedor){
                $creada = Proveedores::create([
                    'rut' => $factura['rutProveedor'],
                    'razonSocial' => $factura['razonSocial'],
                    'codSocioNegocio' => null,
                    'idAuthAso1' => null,
                    'idAuthAso2' => null,
                ]);
            }
        }
        return response()->json($retorno);
    }

    public function autorizar(Request $request)
    {
        $data = $request->all();
        foreach ($data as $row) {
            $factura = Facturas::where('id', $row['id'])->first();
            if(is_null($factura['gastoAutorizado'])){
                if(!is_null($row['autorizadoAuth1'])){
                    // Autorizador 1 == FALSE
                    if ($row['autorizadoAuth1'] === false || $row['autorizadoAuth1'] === "false"){
                        $factura->objeciones = $row['objeciones'];
                        $factura->autorizadoAuth1 = filter_var($row['autorizadoAuth1'], FILTER_VALIDATE_BOOLEAN);
                        $factura->estado = 'Gasto Rechazado';
                        $factura->gastoAutorizado = false;
                        $factura->fechaAuthGasto = Carbon::now();
                    }
                    // Autorizador 1 == TRUE
                    if ($row['autorizadoAuth1'] === true || $row['autorizadoAuth1'] === "true"){
                        $factura->objeciones = $row['objeciones'];
                        $factura->autorizadoAuth1 = filter_var($row['autorizadoAuth1'], FILTER_VALIDATE_BOOLEAN);
                        // SI POSEE AUTORIZADOR 2
                        if (!is_null($factura['idAutorizador2'])) {
                            // Autorizador 2 == FALSE
                            if ($row['autorizadoAuth1'] === false || $row['autorizadoAuth1'] === "false"){
                                $factura->objeciones = $row['objeciones'];
                                $factura->autorizadoAuth2 = filter_var($row['autorizadoAuth2'], FILTER_VALIDATE_BOOLEAN);
                                $factura->estado = 'Gasto Rechazado';
                                $factura->gastoAutorizado = false;
                                $factura->fechaAuthGasto = Carbon::now();
                            }
                            // Autorizador 2 == TRUE
                            if ($row['autorizadoAuth2'] === true || $row['autorizadoAuth2'] === "true"){
                                $factura->objeciones = $row['objeciones'];
                                $factura->autorizadoAuth2 = filter_var($row['autorizadoAuth2'], FILTER_VALIDATE_BOOLEAN);
                                $factura->estado = 'Gasto Autorizado';
                                $factura->gastoAutorizado = true;
                                $factura->autorizadoAuth1 = null;
                                $factura->autorizadoAuth2 = null;
                                $factura->fechaAuthGasto = Carbon::now();
                            }
                        }
                        // SI NO POSEE AUTORIZADOR 2
                        else{
                            $factura->estado = 'Gasto Autorizado';
                            $factura->gastoAutorizado = true;
                            $factura->autorizadoAuth1 = null;
                            $factura->autorizadoAuth2 = null;
                            $factura->fechaAuthGasto = Carbon::now();
                        }
                    }
                }
            }
            else if(is_null($factura['pagoAutorizado'])){
                if(!is_null($row['autorizadoAuth1'])){
                    // Autorizador 1 == FALSE
                    if ($row['autorizadoAuth1'] === false || $row['autorizadoAuth1'] === "false"){
                        $factura->objeciones = $row['objeciones'];
                        $factura->autorizadoAuth1 = filter_var($row['autorizadoAuth1'], FILTER_VALIDATE_BOOLEAN);
                        $factura->estado = 'Pago Rechazado';
                        $factura->pagoAutorizado = false;
                        $factura->fechaAuthPago = Carbon::now();
                    }
                    // Autorizador 1 == TRUE
                    if ($row['autorizadoAuth1'] === true || $row['autorizadoAuth1'] === "true"){
                        $factura->objeciones = $row['objeciones'];
                        $factura->autorizadoAuth1 = filter_var($row['autorizadoAuth1'], FILTER_VALIDATE_BOOLEAN);
                        $factura->fechaAuthPago = Carbon::now();
                        // SI POSEE AUTORIZADOR 2
                        if (!is_null($factura['idAutorizador2'])) {
                            // Autorizador 2 == FALSE
                            if ($row['autorizadoAuth1'] === false || $row['autorizadoAuth1'] === "false"){
                                $factura->objeciones = $row['objeciones'];
                                $factura->autorizadoAuth2 = filter_var($row['autorizadoAuth2'], FILTER_VALIDATE_BOOLEAN);
                                $factura->estado = 'Pago Rechazado';
                                $factura->pagoAutorizado = false;
                                $factura->fechaAuthPago = Carbon::now();
                            }
                            // Autorizador 2 == TRUE
                            if ($row['autorizadoAuth2'] === true || $row['autorizadoAuth2'] === "true"){
                                $factura->objeciones = $row['objeciones'];
                                $factura->autorizadoAuth2 = filter_var($row['autorizadoAuth2'], FILTER_VALIDATE_BOOLEAN);
                                $factura->estado = 'Pago Autorizado';
                                $factura->pagoAutorizado = true;
                                $factura->autorizado = true;
                                $factura->fechaAuthPago = Carbon::now();
                            }
                        }
                        // SI NO POSEE AUTORIZADOR 2
                        else{
                            $factura->estado = 'Pago Autorizado';
                            $factura->pagoAutorizado = true;
                            $factura->autorizado = true;
                            $factura->fechaAuthPago = Carbon::now();
                        }
                    }
                }
            }
            $factura->save();
        }
        return $factura;
        // return response()->json("Factura Autorizada");
    }
    
    public function autorizables(Request $request){
        $userID = $request["userID"];
        $tipoAuth = $request["tipoAuth"];
        $user = null;
        $facturas = [];
        $facturas_auth1 = [];
        $facturas_auth2 = [];
        // AUTORIZACIÓN DE GASTOS
        if ($tipoAuth == "GASTOS"){
            // Seleccionar datos relacionados con el usuario:
            $facturas_auth1 = Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->select('CTB_facturas.*')->where('CTB_facturas.estado','Pendiente')->where('autorizable',true)->where('autorizadoAuth1','!=',true)->orWhereNull('autorizadoAuth1')->leftJoin('SYS_autorizadores', 'SYS_autorizadores.id', '=', 'idAutorizador1')->leftJoin('users', 'users.id', '=', 'idUsuario')->where('users.id', $userID)->where('CTB_facturas.estado','Pendiente')->get();
            $facturas_auth2 = Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->select('CTB_facturas.*')->where('CTB_facturas.estado','Pendiente')->where('autorizable',true)->where('autorizadoAuth1',true)->leftJoin('SYS_autorizadores', 'SYS_autorizadores.id', '=', 'idAutorizador2')->leftJoin('users', 'users.id', '=', 'idUsuario')->where('users.id', $userID)->where('CTB_facturas.estado','Pendiente')->get();
            foreach ($facturas_auth1 as $factura){
                array_push($facturas, $factura);
            }
            foreach ($facturas_auth2 as $factura){
                array_push($facturas, $factura);
            }
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
                $facturas = Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->where('autorizable',true)->where('estado','Pendiente')->get();
            }
            if($administrador == true){
                $facturas = Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->where('autorizable',true)->where('estado','Pendiente')->get();
            }
        }
        
        // AUTORIZACIÓN DE PAGOS
        if ($tipoAuth == "PAGOS"){
            // Seleccionar datos relacionados con el usuario:
            $facturas_auth1 = Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->select('CTB_facturas.*')->where('CTB_facturas.estado','Gasto Autorizado')->where('autorizable',true)->where('autorizadoAuth1','!=',true)->orWhereNull('autorizadoAuth1')->leftJoin('SYS_autorizadores', 'SYS_autorizadores.id', '=', 'idAutorizador1')->leftJoin('users', 'users.id', '=', 'idUsuario')->where('users.id', $userID)->where('CTB_facturas.estado','Gasto Autorizado')->get();
            $facturas_auth2 = Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->select('CTB_facturas.*')->where('CTB_facturas.estado','Gasto Autorizado')->where('autorizable',true)->where('autorizadoAuth1',true)->leftJoin('SYS_autorizadores', 'SYS_autorizadores.id', '=', 'idAutorizador2')->leftJoin('users', 'users.id', '=', 'idUsuario')->where('users.id', $userID)->where('CTB_facturas.estado','Gasto Autorizado')->get();
            foreach ($facturas_auth1 as $factura){
                array_push($facturas, $factura);
            }
            foreach ($facturas_auth2 as $factura){
                array_push($facturas, $factura);
            }
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
                $facturas = Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->where('autorizable',true)->where('estado','Gasto Autorizado')->get();
            }
            if($administrador == true){
                $facturas = Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->where('autorizable',true)->where('estado','Gasto Autorizado')->get();
            }
        }
        return $facturas;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($userID){
        // $facturas = [];
        // if(soy administrador de este sistema/departamento){
        //     $facturas = Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->get();
        // }
        // else{
        //     // get datos  mios
        //     $facturasPropias = Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->select('CTB_facturas.*')->where('autorizable',true)->leftJoin('SYS_autorizadores', function ($join) {$join->on('SYS_autorizadores.id', '=', 'idAutorizador1')->orOn('SYS_autorizadores.id', '=', 'idAutorizador2');})->leftJoin('users', 'users.id', '=', 'idUsuario')->where('users.id', $userID)->get();
        //     if(soy lider){
        //         $grupo = this.user.grupo;
        //         // get datos colaboradores del grupo
        //         $facturas = Facturas::with("empresa")->with("user.grupo", function ($where) {$where->where("user.grupo.nombre, =, $grupo");}))->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->get();
        //         $facturasColaboradores = Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->select('CTB_facturas.*')->where('autorizable',true)->leftJoin('SYS_autorizadores', function ($join) {$join->on('SYS_autorizadores.id', '=', 'idAutorizador1')->orOn('SYS_autorizadores.id', '=', 'idAutorizador2');})->leftJoin('users', 'users.id', '=', 'idUsuario')->where('users.id', $userID)->get();
        //     }
        //     foreach ($facturasPropias as $factura){
        //         array_push($facturas, $factura);
        //     }
        //     foreach ($facturasColaboradores as $factura){
        //         array_push($facturas, $factura);
        //     }
        // }
        // return json_encode($facturas);


        // Seleccionar datos relacionados con el usuario:
        // ->select('CTB_facturas.id AS id', 'users.id AS user_id')
        $facturasPropiasAut = Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->select('CTB_facturas.*')->where('autorizable',true)->leftJoin('SYS_autorizadores', function ($join) {$join->on('SYS_autorizadores.id', '=', 'idAutorizador1')->orOn('SYS_autorizadores.id', '=', 'idAutorizador2');})->leftJoin('users', 'users.id', '=', 'idUsuario')->where('users.id', $userID)->get();

        $facturasPropiasSol = Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->select('CTB_facturas.*')->where('autorizable',true)->where('idUsuarioSolicitante', '=', $userID)->get();

        $facturasDefinitivas = [];
        foreach ($facturasPropiasAut as $factura){
            array_push($facturasDefinitivas, $factura);
        }
        foreach ($facturasPropiasSol as $factura){
            array_push($facturasDefinitivas, $factura);
        }

        $facturas = [];
        foreach ($facturasDefinitivas as $facturaDef){
            $esta = false;
            foreach ($facturas as $factura){
                if ($factura['idFactura'] == $facturaDef['idFactura']){
                    $esta = true;
                    break;
                }
            }
            if ($esta == false){
                array_push($facturas, $facturaDef);
            }
        }

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
            $facturas = Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->get();
        }
        if($administrador == true){
            $facturas = Facturas::with("empresa")->with("user")->with("autorizador1.usuario")->with("autorizador2.usuario")->with("pagos")->get();
        }
        return json_encode($facturas);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $factura = Facturas::find($id);
        $factura->idAutorizador1 = $request->input('idAutorizador1');
        $factura->idAutorizador2 = $request->input('idAutorizador2');
        if($request->input('idAutorizador2') !== null){
            $authorizador2 = Autorizadores::find($request->input('idAutorizador2'));
            $factura->identificador = str_pad($factura['id'], 7, "0", STR_PAD_LEFT).$authorizador2['identificador'];
        }
        else{
            $authorizador1 = Autorizadores::find($request->input('idAutorizador1'));
            $factura->identificador = str_pad($factura['id'], 7, "0", STR_PAD_LEFT).$authorizador1['identificador'];
        }
        $factura->save();
        return response()->json("Factura Editada");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Facturas::find($id)->delete();
        return response()->json("Factura Eliminada");
    }

    public function sendEmailAdministrador(Request $request){
        // Datos de Entrada:
        $facturas = $request->all();
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
        foreach ($facturas as $factura) {
            // Construcción Tabla:
            $output .='
            <tr>
                <td style="color: '.$color.'; font-weight: 800;">'.$factura['RUT Proveedor'].'</td>
                <td>'.$factura['Razon Social'].'</td>
                <td style="color: '.$color.'; font-weight: 800;">'.$factura['Folio'].'</td>
                <td>'.$factura['tipoDocNombre'].'</td>
                <td>'.$factura['Monto Total'].'</td>
                <td>'.$factura['Observaciones'].'</td>
            </tr>';
        }

        // HTML: 
        $html = "
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 16px;
                        line-height: 1.5;
                        color: #333333;
                        background-color: #f4f4f4;
                        padding: 20px;
                    }
                    
                    /* Estilos para los encabezados */
                    h1, h2, h4, h4, h5, h6 {
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
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 16px;
                        line-height: 1.5;
                        color: #333333;
                        background-color: #f4f4f4;
                        padding: 20px;
                    }
                    
                    /* Estilos para los encabezados */
                    h1, h2, h4, h4, h5, h6 {
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
                <a class='btn' href='".$domain."/facturas'>Revisar Facturas</a>
                <br>
                <h4> Facturas por Aprobar: </h4>";
                if($output != null){
                    $html.="<table id='customers'>
                        <thead>
                            <tr>
                                <th>Rut</th>
                                <th>Razón Social</th>
                                <th>Folio</th>
                                <th>Tipo Documento</th>
                                <th>Monto Total</th>
                                <th>Observaciones</th>
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
                "From" => "no_reply@amphora.cl",
                "To" => [
                    "Email" => [
                        "bcontreras@amphora.cl",
                        // "proveedores@amphora.cl",
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
                        // "gacostal@amphora.cl",
                    ]
                ],
                "Message" => [
                "Subject" => "Detalle de nuevas Facturas por Autorizar.",
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

    public function sendEmailAutorizadorCSV(Request $request){
        // Datos de Entrada:
        $facturas = $request[0];
        $variableAuth = $request[1];
        $autorizadores = Autorizadores::with("usuario")->get();
        // Conseguir email del usuario:
        $autorizadorEmail = null;
        foreach ($autorizadores as $autorizador){
            if($facturas[0][$variableAuth] == $autorizador['id']){
                $autorizadorEmail = $autorizador['usuario']['email'];
            }
        }
        // Variables: 
        $username = "Register_AMPHORAMKTCL";
        $password = '8634-E$6xO$$F$6Ex125';
        $userpwd = $username.":".$password;
        //  Variables Especiales:
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
        foreach ($facturas as $factura) {
            // Construcción Tabla:
            $output .='
            <tr>
                <td style="color: '.$color.'; font-weight: 800;">'.$factura['RUT Proveedor'].'</td>
                <td>'.$factura['Razon Social'].'</td>
                <td style="color: '.$color.'; font-weight: 800;">'.$factura['Folio'].'</td>
                <td>'.$factura['tipoDocNombre'].'</td>
                <td>'.$factura['Monto Total'].'</td>
                <td>'.$factura['Observaciones'].'</td>
            </tr>';
        }

        // HTML: 
        $html = "
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 16px;
                        line-height: 1.5;
                        color: #333333;
                        background-color: #f4f4f4;
                        padding: 20px;
                    }
                    
                    /* Estilos para los encabezados */
                    h1, h2, h4, h4, h5, h6 {
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
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 16px;
                        line-height: 1.5;
                        color: #333333;
                        background-color: #f4f4f4;
                        padding: 20px;
                    }
                    
                    /* Estilos para los encabezados */
                    h1, h2, h4, h4, h5, h6 {
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
                <a class='btn' href='".$domain."/facturas'>Revisar Facturas</a>
                <br>
                <h4> Facturas por Aprobar: </h4>";
                if($output != null){
                    $html.="<table id='customers'>
                        <thead>
                            <tr>
                                <th>Rut</th>
                                <th>Razón Social</th>
                                <th>Folio</th>
                                <th>Tipo Documento</th>
                                <th>Monto Total</th>
                                <th>Observaciones</th>
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
                "From" => "no_reply@amphora.cl",
                "To" => [
                    "Email" => [
                        "bcontreras@amphora.cl",
                        // $autorizadorEmail,
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
                        // "gacostal@amphora.cl",
                    ]
                ],
                "Message" => [
                "Subject" => "Detalle de Facturas por Autorizar.",
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

    public function sendEmailAutorizador2(Request $request){
        // Datos de Entrada:
        $facturas = $request[0];
        $variableAuth = $request[1];
        $autorizadores = Autorizadores::with("usuario")->get();
        // Conseguir email del usuario:
        $autorizadorEmail = null;
        foreach ($autorizadores as $autorizador){
            if($facturas[0][$variableAuth] == $autorizador['id']){
                $autorizadorEmail = $autorizador['usuario']['email'];
            }
        }
        // Variables: 
        $username = "Register_AMPHORAMKTCL";
        $password = '8634-E$6xO$$F$6Ex125';
        $userpwd = $username.":".$password;
        //  Variables Especiales:
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
        foreach ($facturas as $factura) {
            // Construcción Tabla:
            $output .='
            <tr>
                <td style="color: '.$color.'; font-weight: 800;">'.$factura['rutProveedor'].'</td>
                <td>'.$factura['razonSocial'].'</td>
                <td style="color: '.$color.'; font-weight: 800;">'.$factura['folio'].'</td>
                <td>'.$factura['tipoDocNombre'].'</td>
                <td>'.$factura['montoTotal'].'</td>
                <td>'.$factura['observaciones'].'</td>
            </tr>';
        }

        // HTML: 
        $html = "
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 16px;
                        line-height: 1.5;
                        color: #333333;
                        background-color: #f4f4f4;
                        padding: 20px;
                    }
                    
                    /* Estilos para los encabezados */
                    h1, h2, h4, h4, h5, h6 {
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
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 16px;
                        line-height: 1.5;
                        color: #333333;
                        background-color: #f4f4f4;
                        padding: 20px;
                    }
                    
                    /* Estilos para los encabezados */
                    h1, h2, h4, h4, h5, h6 {
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
                <a class='btn' href='".$domain."/facturas'>Revisar Facturas</a>
                <br>
                <h4> Facturas por Aprobar: </h4>";
                if($output != null){
                    $html.="<table id='customers'>
                        <thead>
                            <tr>
                                <th>Rut</th>
                                <th>Razón Social</th>
                                <th>Folio</th>
                                <th>Tipo Documento</th>
                                <th>Monto Total</th>
                                <th>Observaciones</th>
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
                "From" => "no_reply@amphora.cl",
                "To" => [
                    "Email" => [
                        "bcontreras@amphora.cl",
                        // $autorizadorEmail,
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
                        // "gacostal@amphora.cl",
                    ]
                ],
                "Message" => [
                "Subject" => "Detalle de Facturas por Autorizar.",
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

    public function sendEmailInsistencia(Request $request){
        // Datos de Entrada:
        $factura = $request[0];
        $variableAuth = $request[1];
        $tipoDocNombre = $request[2];
        $autorizadores = Autorizadores::with("usuario")->get();
        // Conseguir email del usuario:
        $autorizadorEmail = null;
        foreach ($autorizadores as $autorizador){
            if($factura[$variableAuth] == $autorizador['id']){
                $autorizadorEmail = $autorizador['usuario']['email'];
            }
        }
        // Variables: 
        $username = "Register_AMPHORAMKTCL";
        $password = '8634-E$6xO$$F$6Ex125';
        $userpwd = $username.":".$password;
        //  Variables Especiales:
        $color = "#000000";
        // URL
        $url = url()->current(); // Obtiene la URL actual
        $parts = parse_url($url); // Analiza los componentes de la URL
        $domain = $parts['scheme'] . '://' . $parts['host']; // Obtiene el dominio
        if (isset($parts['port'])) {
            $domain .= ':' . $parts['port']; // Si hay puerto, lo concatena al dominio
        }
        // URL
        $url = url()->current(); // Obtiene la URL actual
        $parts = parse_url($url); // Analiza los componentes de la URL
        $domain = $parts['scheme'] . '://' . $parts['host']; // Obtiene el dominio
        if (isset($parts['port'])) {
            $domain .= ':' . $parts['port']; // Si hay puerto, lo concatena al dominio
        }

        // Construcción Tabla:
        $output = null;
        $output .='
        <tr>
            <td style="color: '.$color.'; font-weight: 800;">'.$factura['rutProveedor'].'</td>
            <td>'.$factura['razonSocial'].'</td>
            <td style="color: '.$color.'; font-weight: 800;">'.$factura['folio'].'</td>
            <td>'.$tipoDocNombre.'</td>
            <td>'.$factura['montoTotal'].'</td>
            <td>'.$factura['observaciones'].'</td>
        </tr>';

        // HTML: 
        $html = "
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 16px;
                        line-height: 1.5;
                        color: #333333;
                        background-color: #f4f4f4;
                        padding: 20px;
                    }
                    
                    /* Estilos para los encabezados */
                    h1, h2, h4, h4, h5, h6 {
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
                    body {
                        font-family: Arial, sans-serif;
                        font-size: 16px;
                        line-height: 1.5;
                        color: #333333;
                        background-color: #f4f4f4;
                        padding: 20px;
                    }
                    
                    /* Estilos para los encabezados */
                    h1, h2, h4, h4, h5, h6 {
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
                <a class='btn' href='".$domain."/facturas'>Revisar Facturas</a>
                <br>
                <h4> Factura: </h4>";
                if($output != null){
                    $html.="<table id='customers'>
                        <thead>
                            <tr>
                                <th>Rut</th>
                                <th>Razón Social</th>
                                <th>Folio</th>
                                <th>Tipo Documento</th>
                                <th>Monto Total</th>
                                <th>Observaciones</th>
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
                "From" => "no_reply@amphora.cl",
                "To" => [
                    "Email" => [
                        "bcontreras@amphora.cl",
                        // $autorizadorEmail,
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
                        // "gacostal@amphora.cl",
                    ]
                ],
                "Message" => [
                "Subject" => "Recodatorio de Autorización Importante!",
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
    public function sendEmailsInsistenciaMasivo(Request $request){
        // Datos de Entrada:
        $facturas = $request->all();
        $facturasPendientes = [];
        foreach ($facturas as $factura){
            if($factura['estado'] == "Pendiente"){
                array_push($facturasPendientes, $factura);
            }
        }
        $autorizadores = Autorizadores::with("usuario")->get();
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
        // Conseguir email del usuario:
        $autorizadorEmail = null;
        foreach ($autorizadores as $autorizador){
            $autorizadorEmail = $autorizador['usuario']['email'];
            // Conseguir facturas del usuario:
            $facturasParaEnviar = [];
            foreach ($facturasPendientes as $factura){
                if($autorizador['id'] == $factura['idAutorizador1'] && $factura['autorizadoAuth1'] === null){
                    array_push($facturasParaEnviar, $factura);
                }
                if($autorizador['id'] == $factura['idAutorizador2'] && $factura['autorizadoAuth1'] === true && $factura['autorizadoAuth2'] === null){
                    array_push($facturasParaEnviar, $factura);
                }
            }

            // Construcción Tabla:
            if($facturasParaEnviar != []){
                $output = null;
                foreach ($facturasParaEnviar as $factura){
                    $output .='
                    <tr>
                        <td style="color: '.$color.'; font-weight: 800;">'.$factura['rutProveedor'].'</td>
                        <td>'.$factura['razonSocial'].'</td>
                        <td style="color: '.$color.'; font-weight: 800;">'.$factura['folio'].'</td>
                        <td>'.$factura['tipoDocumento'].'</td>
                        <td>'.$factura['montoTotal'].'</td>
                        <td>'.$factura['observaciones'].'</td>
                    </tr>';
                }

                // HTML: 
                $html = "
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                font-size: 16px;
                                line-height: 1.5;
                                color: #333333;
                                background-color: #f4f4f4;
                                padding: 20px;
                            }
                            
                            /* Estilos para los encabezados */
                            h1, h2, h4, h4, h5, h6 {
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
                        <a class='btn' href='".$domain."/facturas'>Revisar Facturas</a>
                        <br>
                        <h4> Facturas por Aprobar: </h4>";
                        if($output != null){
                            $html.="<table id='customers'>
                                <thead>
                                    <tr>
                                        <th>Rut</th>
                                        <th>Razón Social</th>
                                        <th>Folio</th>
                                        <th>Tipo Documento</th>
                                        <th>Monto Total</th>
                                        <th>Observaciones</th>
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
                        "From" => "no_reply@amphora.cl",
                        "To" => [
                            "Email" => [
                                "bcontreras@amphora.cl",
                                // $autorizadorEmail,
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
                                // "gacostal@amphora.cl",
                            ]
                        ],
                        "Message" => [
                        "Subject" => "Recodatorio de Autorización Importante!",
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
    }
    
    public function facturasExport(Request $request)
    {
        $data = $request->all();
        $idUsuarioDescarga = $data['userID'];
        $idEmpresaSeleccionada = $data['selectedCompany'];
        return Excel::download(new FacturasExport($idUsuarioDescarga, $idEmpresaSeleccionada),'Facturas.xlsx');
    }

    public function facturasExportGastoAutorizado(Request $request)
    {
        $data = $request->all();
        $idUsuarioDescarga = $data['userID'];
        $idEmpresaSeleccionada = $data['selectedCompany'];
        return Excel::download(new FacturasExportGastoAutorizado($idUsuarioDescarga, $idEmpresaSeleccionada),'Facturas en Proceso de Autorización de Pagos.xlsx');
    }

    public function facturasExportPropuesta(Request $request)
    {
        $data = $request->all();
        $idUsuarioDescarga = $data['userID'];
        $idEmpresaSeleccionada = $data['selectedCompany'];
        return Excel::download(new FacturasExportPropuesta($idUsuarioDescarga, $idEmpresaSeleccionada),'Propuesta.xlsx');
    }

    public function referencesImport(Request $request)
    {
        $rows = $request->all();
        foreach ($rows as $row) {
            if(array_key_exists("rutEmisor",$row) && $row['rutEmisor'] != null){
                $row['idFactura'] = $row['rutEmisor'].$row['folioDTE'];
                $factura = Facturas::where('idFactura', $row['idFactura'])->first();
                $user = User::where('email', '=', 'superadministrador@amphora.cl')->first();
                if ($factura) {
                    $factura->url = $row['url'];
                    $factura->save();
                }
            }
        }
        return response()->json($rows);
    }
}
