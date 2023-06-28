<?php

namespace App\Http\Controllers;
use App\Models\TIE_metasColaborador as MetaColaborador;
use App\Models\SYS_sucursales as Sucursales;
use App\Models\TIE_vendedores as Vendedores;
use App\Models\SYS_colaboradores as Colaboradores;
use App\Models\TIE_contadorPersonas as ContadorPersonas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class metasColaboradorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MetaColaborador::with("tienda")->with("vendedor")->get();
    }

    public function getMetasColaboradoresTienda(Request $request)
    {
        $idTienda = $request['idTienda'];
        $mes = $request['mes'];
        $año = $request['año'];
        $tienda = Sucursales::where('id', $idTienda)->first();
        $centroCosto = $tienda['centroCosto'];
        $fechaInicial = $año.'-'.$mes.'-01';
        $fechaFinal = $año.'-'.$mes.'-'.cal_days_in_month(CAL_GREGORIAN,$mes,$año);
        $metas = MetaColaborador::where('idTienda', $idTienda)->where('año', $año)->where('mes', $mes)->with("tienda")->with("vendedor")->get();

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

        // CURL VENTAS MENSUALES COLABORADOR
        // $urlOriginal = 'https://200.14.247.165:50000/b1s/v1/sml.svc/VENTASXVENDEDOR?$apply=filter(fecha ge '.$fechaInicial.' and fecha le '.$fechaFinal.' and centroCosto ne '174' and centroCosto eq '.$centroCosto.')/groupby((centroCosto,codigoVendedor, nombreVendedor), aggregate(precioProducto with sum as totalPrecioProducto))&$orderby=centroCosto';
        $url = 'https://200.14.247.165:50000/b1s/v1/sml.svc/VENTASXVENDEDOR?%24apply=filter(fecha%20ge%20%27'.$fechaInicial.'%27%20and%20fecha%20le%20%27'.$fechaFinal.'%27%20and%20centroCosto%20ne%20%27174%27%20and%20centroCosto%20eq%20%27'.$centroCosto.'%27)%2Fgroupby((centroCosto%2CcodigoVendedor%2C%20nombreVendedor)%2C%20aggregate(precioProducto%20with%20sum%20as%20totalPrecioProducto))&%24orderby=centroCosto';
        $curlVentasMensualesColaborador = curl_init();
        curl_setopt_array($curlVentasMensualesColaborador, array(
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
        $responseVentasMensualesColaborador = curl_exec($curlVentasMensualesColaborador);
        curl_close($curlVentasMensualesColaborador);
        $decodeVentasMensualesColaborador = json_decode($responseVentasMensualesColaborador, true);

        // VENTAS MENSUALES COLABORADOR
        foreach ($metas as $meta) {
            $meta['meta_total_acumulada_vendedor'] = 0;
            foreach ($decodeVentasMensualesColaborador['value'] as $vendedor) {
                if ($meta['vendedor']['codigoVendedor'] == $vendedor['codigoVendedor']){
                    $meta['meta_total_acumulada_vendedor'] = $vendedor['totalPrecioProducto'];
                }
            }
        }
        return $metas;
    }

    public function getMetasColaboradoresTiendasRango(Request $request)
    {
        $idTienda = $request['idTienda'];
        $mesInicio = $request['mesInicio'];
        $añoInicio = $request['añoInicio'];
        $mesTermino = $request['mesTermino'];
        $añoTermino = $request['añoTermino'];

        $metas = MetaColaborador::where('idTienda', $idTienda)->whereBetween('año', [$añoInicio, $añoTermino])->whereBetween('mes', [$mesInicio, $mesTermino])->with("tienda")->with("vendedor")->get();
        foreach ($metas as $meta) {
            $tienda = Sucursales::where('id', $meta['idTienda'])->first();
            $centroCosto = $tienda['centroCosto'];
            $codigoVendedor = $meta['vendedor']['codigoVendedor'];
            $fechaInicial = $meta['año'].'-'.sprintf("%02d", $meta['mes']).'-01';
            $fechaFinal = $meta['año'].'-'.sprintf("%02d", $meta['mes']).'-'.cal_days_in_month(CAL_GREGORIAN,sprintf("%02d", $meta['mes']),$meta['año']);

            // CONTADOR PERSONAS
            $contadoresPersonas = ContadorPersonas::where('tipoEntrada', 'Entrada')->where('idTienda', $idTienda)->whereBetween('fecha', [$fechaInicial, $fechaFinal])->get();
            $entradaPersonas = 0;
            foreach ($contadoresPersonas as $contadorPersonas) {
                $entradaPersonas += array_sum([$contadorPersonas['h8'], $contadorPersonas['h9'], $contadorPersonas['h10'], $contadorPersonas['h11'], $contadorPersonas['h12'], $contadorPersonas['h13'], $contadorPersonas['h14'], $contadorPersonas['h15'], $contadorPersonas['h16'], $contadorPersonas['h17'], $contadorPersonas['h18'], $contadorPersonas['h19'], $contadorPersonas['h20'], $contadorPersonas['h21'], $contadorPersonas['h22']]);
            }
            $meta['entradas'] = $entradaPersonas;

            // META ACUMULADA MENSUAL COLABORADOR
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
            // CURL VENTAS MENSUALES COLABORADOR
            // $urlOriginal = 'https://200.14.247.165:50000/b1s/v1/sml.svc/VENTASXVENDEDOR?$apply=filter(fecha ge '.$fechaInicial.' and fecha le '.$fechaFinal.' and centroCosto ne '174' and centroCosto eq '.$centroCosto.' and codigoVendedor eq '.$codigoVendedor.')/groupby((centroCosto,codigoVendedor, nombreVendedor), aggregate(precioProducto with sum as totalPrecioProducto))&$orderby=centroCosto';
            $url = 'https://200.14.247.165:50000/b1s/v1/sml.svc/VENTASXVENDEDOR?%24apply=filter(fecha%20ge%20%27'.$fechaInicial.'%27%20and%20fecha%20le%20%27'.$fechaFinal.'%27%20and%20centroCosto%20ne%20%27174%27%20and%20centroCosto%20eq%20%27'.$centroCosto.'%27%20and%20codigoVendedor%20eq%20%27'.$codigoVendedor.'%27)%2Fgroupby((centroCosto%2CcodigoVendedor%2C%20nombreVendedor)%2C%20aggregate(precioProducto%20with%20sum%20as%20totalPrecioProducto))&%24orderby=centroCosto';
            $curlVentasMensualesColaborador = curl_init();
            curl_setopt_array($curlVentasMensualesColaborador, array(
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
            $responseVentasMensualesColaborador = curl_exec($curlVentasMensualesColaborador);
            curl_close($curlVentasMensualesColaborador);
            $decodeVentasMensualesColaborador = json_decode($responseVentasMensualesColaborador, true);
            $meta['meta_total_acumulada_vendedor'] = $decodeVentasMensualesColaborador['value'][0]['totalPrecioProducto'];

            // DOCUMENTOS
            
            // ARTÍCULOS

        }
        return $metas;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campos = [
            'idTienda' => 'required',
            'idVendedor' => 'required',
            'meta_total' => 'required',
            'año' => 'required',
            'mes' => 'required',
        ];
        $mensaje = [
            'idTienda.required' => 'El campo "Tienda" es obligatorio.',
            'idVendedor.required' => 'El campo "Vendedor" es obligatorio.',
            'meta_total.required' => 'El campo "Meta Total" es obligatorio.',
            'año.required' => 'El campo "Año" es obligatorio.',
            'mes.required' => 'El campo "Mes" es obligatorio.',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $metaColaborador = MetaColaborador::create($input);
        return response()->json("Meta Colaborador Creada");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $campos = [
            'idTienda' => 'required',
            'idVendedor' => 'required',
            'meta_total' => 'required',
            'año' => 'required',
            'mes' => 'required',
        ];
        $mensaje = [
            'idTienda.required' => 'El campo "Tienda" es obligatorio.',
            'idVendedor.required' => 'El campo "Vendedor" es obligatorio.',
            'meta_total.required' => 'El campo "Meta Total" es obligatorio.',
            'año.required' => 'El campo "Año" es obligatorio.',
            'mes.required' => 'El campo "Mes" es obligatorio.',
        ];
        $this->validate($request, $campos, $mensaje);
        $metaColaborador = MetaColaborador::find($id);
        $metaColaborador->idTienda = $request->input('idTienda');
        $metaColaborador->idVendedor = $request->input('idVendedor');
        $metaColaborador->meta_total = $request->input('meta_total');
        $metaColaborador->meta_carteras = $request->input('meta_carteras');
        $metaColaborador->meta_marroquineria = $request->input('meta_marroquineria');
        $metaColaborador->meta_calzado = $request->input('meta_calzado');
        $metaColaborador->meta_perfume = $request->input('meta_perfume');
        $metaColaborador->meta_vestimenta = $request->input('meta_vestimenta');
        $metaColaborador->año = $request->input('año');
        $metaColaborador->mes = $request->input('mes');
        $metaColaborador->save();
        return response()->json("Meta Colaborador Editada");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MetaColaborador::find($id)->delete();
        return response()->json("Meta Colaborador Eliminada");
    }

    public function metasColaboradorImport(Request $request)
    {
        $rows = $request->all();
        $tiendas = Sucursales::all();
        $vendedores = Vendedores::all();
        $tiendasInexistentes = [];
        $vendedoresInexistentes = [];
        $rowsDefinitivas = [];
        foreach ($rows as $row){
            $row['idTienda'] = null;
            $row['idVendedor'] = null;
            foreach ($tiendas as $tienda){
                if ($row['Código'] == $tienda['codigo']){
                    $row['idTienda'] = $tienda['id'];
                }
            }
            foreach ($vendedores as $vendedor){
                if ($row['Rut'] == $vendedor['identificador']){
                    $row['idVendedor'] = $vendedor['id'];
                }
            }
            if($row['idTienda'] == null) {
                $existe = false;
                foreach ($tiendasInexistentes as $codigo) {
                    if($codigo == $row['Código']){
                        $existe = true;
                        break;
                    }
                }
                if ($existe == false) {
                    array_push($tiendasInexistentes, $row['Código']);
                }
            }
            if($row['idVendedor'] == null) {
                $existe2 = false;
                foreach ($vendedoresInexistentes as $rut) {
                    if($rut == $row['Rut']){
                        $existe2 = true;
                        break;
                    }
                }
                if ($existe2 == false) {
                    array_push($vendedoresInexistentes, $row['Rut']);
                }
            }
            if ($row['idVendedor'] != null && $row['idTienda'] != null) {
                array_push($rowsDefinitivas, $row);
            }
        }
        $inexistentes = [
            'tiendasInexistentes' => $tiendasInexistentes,
            'vendedoresInexistentes'=> $vendedoresInexistentes,
        ];
        foreach ($rowsDefinitivas as $row) {
            $inputRow = [
                'idTienda' => $row['idTienda'],
                'idVendedor'=> $row['idVendedor'],
                'meta_total' => $row['Meta'],
                'año'=> Carbon::now()->year,
                'mes'=> Carbon::now()->month,
                //'dia': $row['Dia'],
            ];
            MetaColaborador::create($inputRow);
        }
        return $inexistentes;
    }
}
