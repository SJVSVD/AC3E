<?php

namespace App\Http\Controllers;
use App\Models\TIE_metasDiarias as MetaDiaria;
use App\Models\SYS_sucursales as Sucursales;
use App\Models\TIE_contadorPersonas as ContadorPersonas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class metasDiariasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MetaDiaria::with("tienda")->get();
    }

    public function getMetasDiariasTienda(Request $request)
    {
        $idTienda = $request['idTienda'];
        $dia = $request['dia'];
        $mes = $request['mes'];
        $año = $request['año'];
        $tienda = Sucursales::where('id', $idTienda)->first();
        $centroCosto = $tienda['centroCosto'];
        $fecha = $año.'-'.$mes.'-'.$dia;
        $meta = MetaDiaria::where('idTienda', $idTienda)->where('año', $año)->where('dia', $dia)->where('mes', $mes)->with("tienda")->get();

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

        // CURL VENTAS DIARIAS
        // $urlOriginal = 'https://200.14.247.165:50000/b1s/v1/sml.svc/VENTASXVENDEDOR?$apply=filter(fecha eq '.$fecha.' and centroCosto ne '.'174'.' and centroCosto eq '.$centroCosto.')/groupby((centroCosto), aggregate(precioProducto with sum as totalPrecioProducto))&$orderby=centroCosto';
        $url = 'https://200.14.247.165:50000/b1s/v1/sml.svc/VENTASXVENDEDOR?%24apply=filter(fecha%20eq%20%27'.$fecha.'%27%20and%20centroCosto%20ne%20%27174%27%20and%20centroCosto%20eq%20%27'.$centroCosto.'%27)%2Fgroupby((centroCosto)%2C%20aggregate(precioProducto%20with%20sum%20as%20totalPrecioProducto))&%24orderby=centroCosto';
        $curlVentasDiarias = curl_init();
        curl_setopt_array($curlVentasDiarias, array(
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
        $responseVentasDiarias = curl_exec($curlVentasDiarias);
        curl_close($curlVentasDiarias);
        $decodeVentasDiarias = json_decode($responseVentasDiarias, true);

        // VENTA DIARIA
        $meta[0]['meta_total_acumulada_diaria'] = $decodeVentasDiarias['value'][0]['totalPrecioProducto'];
        return $meta;
    }

    public function getMetasDiariasTiendasRango(Request $request)
    {
        $idTienda = $request['idTienda'];
        $diaInicio = $request['diaInicio'];
        $mesInicio = $request['mesInicio'];
        $añoInicio = $request['añoInicio'];
        $diaTermino = $request['diaTermino'];
        $mesTermino = $request['mesTermino'];
        $añoTermino = $request['añoTermino'];

        if ($idTienda != null) {
            $metas = MetaDiaria::where('idTienda', $idTienda)->whereBetween('año', [$añoInicio, $añoTermino])->whereBetween('mes', [$mesInicio, $mesTermino])->whereBetween('dia', [$diaInicio, $diaTermino])->with("tienda")->get();
        }
        else{
            $metas = MetaDiaria::whereBetween('año', [$añoInicio, $añoTermino])->whereBetween('mes', [$mesInicio, $mesTermino])->whereBetween('dia', [$diaInicio, $diaTermino])->with("tienda")->get();
        }
        foreach ($metas as $meta) {
            $tienda = Sucursales::where('id', $meta['idTienda'])->first();
            $idTienda = $tienda['id'];
            $centroCosto = $tienda['centroCosto'];
            $fecha = $meta['año'].'-'.sprintf("%02d", $meta['mes']).'-'.sprintf("%02d", $meta['dia']);

            // CONTADOR PERSONAS
            $contadoresPersonas = ContadorPersonas::where('tipoEntrada', 'Entrada')->where('fecha', $fecha)->get();
            $entradaPersonas = 0;
            foreach ($contadoresPersonas as $contadorPersonas) {
                $entradaPersonas += array_sum([$contadorPersonas['h8'], $contadorPersonas['h9'], $contadorPersonas['h10'], $contadorPersonas['h11'], $contadorPersonas['h12'], $contadorPersonas['h13'], $contadorPersonas['h14'], $contadorPersonas['h15'], $contadorPersonas['h16'], $contadorPersonas['h17'], $contadorPersonas['h18'], $contadorPersonas['h19'], $contadorPersonas['h20'], $contadorPersonas['h21'], $contadorPersonas['h22']]);
            }
            $meta['entradas'] = $entradaPersonas;
            
            // META ACUMULADA DIARIA
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

            // CURL VENTAS DIARIAS
            // $urlOriginal = 'https://200.14.247.165:50000/b1s/v1/sml.svc/VENTASXVENDEDOR?$apply=filter(fecha eq '.$fecha.' and centroCosto ne '.'174'.' and centroCosto eq '.$centroCosto.')/groupby((centroCosto), aggregate(precioProducto with sum as totalPrecioProducto))&$orderby=centroCosto';
            $url = 'https://200.14.247.165:50000/b1s/v1/sml.svc/VENTASXVENDEDOR?%24apply=filter(fecha%20eq%20%27'.$fecha.'%27%20and%20centroCosto%20ne%20%27174%27%20and%20centroCosto%20eq%20%27'.$centroCosto.'%27)%2Fgroupby((centroCosto)%2C%20aggregate(precioProducto%20with%20sum%20as%20totalPrecioProducto))&%24orderby=centroCosto';
            $curlVentasDiarias = curl_init();
            curl_setopt_array($curlVentasDiarias, array(
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
            $responseVentasDiarias = curl_exec($curlVentasDiarias);
            curl_close($curlVentasDiarias);
            $decodeVentasDiarias = json_decode($responseVentasDiarias, true);
            $meta['meta_total_acumulada_diaria'] = $decodeVentasDiarias['value'][0]['totalPrecioProducto'];
    
            // DOCUMENTOS 

            // ARTICULOS

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
            'año' => 'required',
            'mes' => 'required',
            'dia' => 'required',
        ];
        $mensaje = [
            'idTienda.required' => 'El campo "Tienda" es obligatorio.',
            'meta_total.required' => 'El campo "Meta Total" es obligatorio.',
            'año.required' => 'El campo "Año" es obligatorio.',
            'mes.required' => 'El campo "Mes" es obligatorio.',
            'dia.required' => 'El campo "Dia" es obligatorio.',
        ];
        $this->validate($request, $campos, $mensaje);
        $input = $request->all();
        $metaDiaria = MetaDiaria::create($input);
        return response()->json("Meta Diaria Creada");
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
            'año' => 'required',
            'mes' => 'required',
            'dia' => 'required',
        ];
        $mensaje = [
            'idTienda.required' => 'El campo "Tienda" es obligatorio.',
            'meta_total.required' => 'El campo "Meta Total" es obligatorio.',
            'año.required' => 'El campo "Año" es obligatorio.',
            'mes.required' => 'El campo "Mes" es obligatorio.',
            'dia.required' => 'El campo "Dia" es obligatorio.',
        ];
        $this->validate($request, $campos, $mensaje);
        $metaDiaria = MetaDiaria::find($id);
        $metaDiaria->idTienda = $request->input('idTienda');
        $metaDiaria->meta_total = $request->input('meta_total');
        $metaDiaria->meta_carteras = $request->input('meta_carteras');
        $metaDiaria->meta_marroquineria = $request->input('meta_marroquineria');
        $metaDiaria->meta_calzado = $request->input('meta_calzado');
        $metaDiaria->meta_perfume = $request->input('meta_perfume');
        $metaDiaria->meta_vestimenta = $request->input('meta_vestimenta');
        $metaDiaria->año = $request->input('año');
        $metaDiaria->mes = $request->input('mes');
        $metaDiaria->dia = $request->input('dia');
        $metaDiaria->save();
        return response()->json("Meta Diaria Editada");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MetaDiaria::find($id)->delete();
        return response()->json("Meta Diaria Eliminada");
    }
    
    public function metasDiariasImport(Request $request)
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
                'meta_total' => $row['Meta'],
                'año' => Carbon::now()->year,
                'mes' => Carbon::now()->month,
                'dia' => $row['Día'],
            ];
            MetaDiaria::create($inputRow);
        }
        return $inexistentes;
    }
}
