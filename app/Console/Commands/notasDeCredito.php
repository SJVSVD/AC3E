<?php

namespace App\Console\Commands;
use App\Models\TIE_NotasCredito;
use App\Models\TIE_productos;
use App\Models\TIE_DetallesPagoNCV;
use App\Models\SYS_sucursales;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class notasDeCredito extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notasDeCredito';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para registrar notas de creditas ingresadas ultimamente';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
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
            $idUsuario = SYS_sucursales::where('id',$idTienda)->pluck('idUsuario');
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
                    $this->info($tipoPago);
                    $this->info($cobroTarjeta);
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
            }else{

            }
        }

        return Command::SUCCESS;
    }
}
