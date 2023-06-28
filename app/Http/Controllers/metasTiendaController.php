<?php

namespace App\Http\Controllers;
use App\Models\TIE_metasTienda as MetaTienda;
use App\Models\SYS_sucursales as Sucursales;
use App\Models\TIE_contadorPersonas as ContadorPersonas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class metasTiendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MetaTienda::with("tienda")->get();
    }

    public function getMetasMensualesTienda(Request $request)
    {
        $idTienda = $request['idTienda'];
        $mes = $request['mes'];
        $año = $request['año'];
        $tienda = Sucursales::where('id', $idTienda)->first();
        $centroCosto = $tienda['centroCosto'];
        $fechaInicial = $año.'-'.$mes.'-01';
        $fechaFinal = $año.'-'.$mes.'-'.cal_days_in_month(CAL_GREGORIAN,$mes,$año);
        $meta = MetaTienda::where('idTienda', $idTienda)->where('año', $año)->where('mes', $mes)->with("tienda")->get();

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

        // CURL VENTAS MENSUALES
        // $urlOriginal = 'https://200.14.247.165:50000/b1s/v1/sml.svc/VENTASXVENDEDOR?$apply=filter(fecha ge '.$fechaInicial.' and fecha le '.$fechaFinal.' and centroCosto ne '.'174'.' and centroCosto eq '.$centroCosto.')/groupby((centroCosto), aggregate(precioProducto with sum as totalPrecioProducto))&$orderby=centroCosto';
        $url = 'https://200.14.247.165:50000/b1s/v1/sml.svc/VENTASXVENDEDOR?%24apply=filter(fecha%20ge%20%27'.$fechaInicial.'%27%20and%20fecha%20le%20%27'.$fechaFinal.'%27%20and%20centroCosto%20ne%20%27174%27%20and%20centroCosto%20eq%20%27'.$centroCosto.'%27)%2Fgroupby((centroCosto)%2C%20aggregate(precioProducto%20with%20sum%20as%20totalPrecioProducto))&%24orderby=centroCosto';
        $curlVentasMensuales = curl_init();
        curl_setopt_array($curlVentasMensuales, array(
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
        $responseVentasMensuales = curl_exec($curlVentasMensuales);
        curl_close($curlVentasMensuales);
        $decodeVentasMensuales = json_decode($responseVentasMensuales, true);
        

        // CURL VENTAS MENSUALES POR CATEGORIA
        // $urlOriginal = 'https://200.14.247.165:50000/b1s/v1/sml.svc/VENTASXVENDEDOR?$apply=filter(fecha ge '.$fechaInicial.' and fecha le '.$fechaFinal.' and centroCosto ne '174' and centroCosto eq '.$centroCosto.')/groupby((centroCosto,agrupacion), aggregate(precioProducto with sum as totalPrecioProducto))&$orderby=centroCosto';
        $url = 'https://200.14.247.165:50000/b1s/v1/sml.svc/VENTASXVENDEDOR?%24apply=filter(fecha%20ge%20%27'.$fechaInicial.'%27%20and%20fecha%20le%20%27'.$fechaFinal.'%27%20and%20centroCosto%20ne%20%27174%27%20and%20centroCosto%20eq%20%27'.$centroCosto.'%27)%2Fgroupby((centroCosto%2Cagrupacion)%2C%20aggregate(precioProducto%20with%20sum%20as%20totalPrecioProducto))&%24orderby=centroCosto';
        $curlVentasMensualesCategorias = curl_init();
        curl_setopt_array($curlVentasMensualesCategorias, array(
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
        $responseVentasMensualesCategorias = curl_exec($curlVentasMensualesCategorias);
        curl_close($curlVentasMensualesCategorias);
        $decodeVentasMensualesCategorias = json_decode($responseVentasMensualesCategorias, true);

        // VENTAS MENSUALES
        $meta[0]['meta_total_acumulada_mensual'] = $decodeVentasMensuales['value'][0]['totalPrecioProducto'];
        // VENTAS POR CATEGORÍA
        $meta[0]['meta_carteras_acumulada'] = 0;
        $meta[0]['meta_marroquineria_acumulada'] = 0;
        $meta[0]['meta_calzado_acumulada'] = 0;
        $meta[0]['meta_perfumes_acumulada'] = 0;
        $meta[0]['meta_vestimenta_acumulada'] = 0;
        $meta[0]['meta_otros_acumulada'] = 0;
        foreach ($decodeVentasMensualesCategorias['value'] as $ventaCategoria) {
            if ($ventaCategoria['agrupacion'] == "CARTERAS") {
                $meta[0]['meta_carteras_acumulada'] = $ventaCategoria['totalPrecioProducto'];
            }
            else if ($ventaCategoria['agrupacion'] == "MARROQUINERIA") {
                $meta[0]['meta_marroquineria_acumulada'] = $ventaCategoria['totalPrecioProducto'];
            }
            else if ($ventaCategoria['agrupacion'] == "CALZADO") {
                $meta[0]['meta_calzado_acumulada'] = $ventaCategoria['totalPrecioProducto'];
            }
            else if ($ventaCategoria['agrupacion'] == "PERFUME") {
                $meta[0]['meta_perfumes_acumulada'] = $ventaCategoria['totalPrecioProducto'];
            }
            else if ($ventaCategoria['agrupacion'] == "VESTIMENTA") {
                $meta[0]['meta_vestimenta_acumulada'] = $ventaCategoria['totalPrecioProducto'];
            }
            else{
                $meta[0]['meta_otros_acumulada'] = $ventaCategoria['totalPrecioProducto'];
            }
        }
        return $meta;
    }

    public function getMetasMensualesTiendasRango(Request $request)
    {
        $idTienda = $request['idTienda'];
        $mesInicio = $request['mesInicio'];
        $añoInicio = $request['añoInicio'];
        $mesTermino = $request['mesTermino'];
        $añoTermino = $request['añoTermino'];
        
        if ($idTienda != null) {
            $metas = MetaTienda::where('idTienda', $idTienda)->whereBetween('año', [$añoInicio, $añoTermino])->whereBetween('mes', [$mesInicio, $mesTermino])->with("tienda")->get();
        }
        else{
            $metas = MetaTienda::whereBetween('año', [$añoInicio, $añoTermino])->whereBetween('mes', [$mesInicio, $mesTermino])->with("tienda")->get();
        }
        foreach ($metas as $meta) {
            $tienda = Sucursales::where('id', $meta['idTienda'])->first();
            $idTienda = $tienda['id'];
            $centroCosto = $tienda['centroCosto'];
            $fechaInicial = $meta['año'].'-'.sprintf("%02d", $meta['mes']).'-01';
            $fechaFinal = $meta['año'].'-'.sprintf("%02d", $meta['mes']).'-'.cal_days_in_month(CAL_GREGORIAN,sprintf("%02d", $meta['mes']),$meta['año']);

            // CONTADOR PERSONAS
            $contadoresPersonas = ContadorPersonas::where('tipoEntrada', 'Entrada')->where('idTienda', $idTienda)->whereBetween('fecha', [$fechaInicial, $fechaFinal])->get();
            $entradaPersonas = 0;
            foreach ($contadoresPersonas as $contadorPersonas) {
                $entradaPersonas += array_sum([$contadorPersonas['h8'], $contadorPersonas['h9'], $contadorPersonas['h10'], $contadorPersonas['h11'], $contadorPersonas['h12'], $contadorPersonas['h13'], $contadorPersonas['h14'], $contadorPersonas['h15'], $contadorPersonas['h16'], $contadorPersonas['h17'], $contadorPersonas['h18'], $contadorPersonas['h19'], $contadorPersonas['h20'], $contadorPersonas['h21'], $contadorPersonas['h22']]);
            }
            $meta['entradas'] = $entradaPersonas;

            // META ACUMULADA MENSUAL
            // CURL INICIO DE SESIÓN
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

            // CURL VENTAS MENSUALES
            // $urlOriginal = 'https://200.14.247.165:50000/b1s/v1/sml.svc/VENTASXVENDEDOR?$apply=filter(fecha ge '.$fechaInicial.' and fecha le '.$fechaFinal.' and centroCosto ne '.'174'.' and centroCosto eq '.$centroCosto.')/groupby((centroCosto), aggregate(precioProducto with sum as totalPrecioProducto))&$orderby=centroCosto';
            $url = 'https://200.14.247.165:50000/b1s/v1/sml.svc/VENTASXVENDEDOR?%24apply=filter(fecha%20ge%20%27'.$fechaInicial.'%27%20and%20fecha%20le%20%27'.$fechaFinal.'%27%20and%20centroCosto%20ne%20%27174%27%20and%20centroCosto%20eq%20%27'.$centroCosto.'%27)%2Fgroupby((centroCosto)%2C%20aggregate(precioProducto%20with%20sum%20as%20totalPrecioProducto))&%24orderby=centroCosto';
            $curlVentasMensuales = curl_init();
            curl_setopt_array($curlVentasMensuales, array(
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
            $responseVentasMensuales = curl_exec($curlVentasMensuales);
            curl_close($curlVentasMensuales);
            $decodeVentasMensuales = json_decode($responseVentasMensuales, true);
            $meta['meta_total_acumulada_mensual'] = $decodeVentasMensuales['value'][0]['totalPrecioProducto'];
            
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
            'meta_total' => 'required',
            'meta_carteras' => 'required',
            'meta_marroquineria' => 'required',
            'meta_calzado' => 'required',
            'meta_perfume' => 'required',
            'meta_vestimenta' => 'required',
            'año' => 'required',
            'mes' => 'required',
        ];
        $mensaje = [
            'idTienda.required' => 'El campo "Tienda" es obligatorio.',
            'meta_total.required' => 'El campo "Meta Total" es obligatorio.',
            'meta_carteras.required' => 'El campo "Meta Carteras" es obligatorio.',
            'meta_marroquineria.required' => 'El campo "Meta Marroquineria" es obligatorio.',
            'meta_calzado.required' => 'El campo "Meta Calzado" es obligatorio.',
            'meta_perfume.required' => 'El campo "Meta Perfume" es obligatorio.',
            'meta_vestimenta.required' => 'El campo "Meta Vestimenta" es obligatorio.',
            'año.required' => 'El campo "Año" es obligatorio.',
            'mes.required' => 'El campo "Mes" es obligatorio.',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $metaTienda = MetaTienda::create($input);
        return response()->json("Meta Tienda Creada");
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
            'meta_total' => 'required',
            'meta_carteras' => 'required',
            'meta_marroquineria' => 'required',
            'meta_calzado' => 'required',
            'meta_perfume' => 'required',
            'meta_vestimenta' => 'required',
            'año' => 'required',
            'mes' => 'required',
        ];
        $mensaje = [
            'idTienda.required' => 'El campo "Tienda" es obligatorio.',
            'meta_total.required' => 'El campo "Meta Total" es obligatorio.',
            'meta_carteras.required' => 'El campo "Meta Carteras" es obligatorio.',
            'meta_marroquineria.required' => 'El campo "Meta Marroquineria" es obligatorio.',
            'meta_calzado.required' => 'El campo "Meta Calzado" es obligatorio.',
            'meta_perfume.required' => 'El campo "Meta Perfume" es obligatorio.',
            'meta_vestimenta.required' => 'El campo "Meta Vestimenta" es obligatorio.',
            'año.required' => 'El campo "Año" es obligatorio.',
            'mes.required' => 'El campo "Mes" es obligatorio.',
        ];
        $this->validate($request, $campos, $mensaje);
        $metaTienda = MetaTienda::find($id);
        $metaTienda->idTienda = $request->input('idTienda');
        $metaTienda->meta_total = $request->input('meta_total');
        $metaTienda->meta_carteras = $request->input('meta_carteras');
        $metaTienda->meta_marroquineria = $request->input('meta_marroquineria');
        $metaTienda->meta_calzado = $request->input('meta_calzado');
        $metaTienda->meta_perfume = $request->input('meta_perfume');
        $metaTienda->meta_vestimenta = $request->input('meta_vestimenta');
        $metaTienda->año = $request->input('año');
        $metaTienda->mes = $request->input('mes');
        $metaTienda->save();
        return response()->json("Meta Tienda Editada");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MetaTienda::find($id)->delete();
        return response()->json("Meta Tienda Eliminada");
    }

    public function metasMensualesImport(Request $request)
    {
        $rows = $request->all();
        $tiendas = Sucursales::all();
        $tiendasInexistentes = [];
        $rowsDefinitivas = [];
        foreach ($rows as $row){
            $row['idTienda'] = null;
            foreach ($tiendas as $tienda){
                if ($row['Código'] == $tienda['codigo']){
                    $row['idTienda'] = $tienda['id'];
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
            else{
                array_push($rowsDefinitivas, $row);
            }
        }
        $inexistentes = [
            'tiendasInexistentes' => $tiendasInexistentes,
        ];
        foreach ($rowsDefinitivas as $row) {
            $inputRow = [
                'idTienda' => $row['idTienda'],
                'meta_total' => $row['Carteras'] + $row['Marroquinería'] + $row['Calzado'] + $row['Perfumería'] + $row['Vestimenta'],
                'meta_carteras' => $row['Carteras'],
                'meta_marroquineria' => $row['Marroquinería'],
                'meta_calzado' => $row['Calzado'],
                'meta_perfume' => $row['Perfumería'],
                'meta_vestimenta' => $row['Vestimenta'],
                'año' => Carbon::now()->year,
                'mes' => Carbon::now()->month,
            ];
            MetaTienda::create($inputRow);
        }
        return $inexistentes;
    }
}
