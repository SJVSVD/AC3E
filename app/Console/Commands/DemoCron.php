<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return;
        $hoy = date("Y-m-d");
        $nuevafecha1 = strtotime("-10 day", strtotime($hoy));
        $antiguo = date("Y-m-d", $nuevafecha1);

        //

       // echo "<br>$id_sesion<br>";
        $ciudades = consultapacking(
            "select sap_documento.iddocumento, sap_documento.referencia, tienda_caja_registro.fecha_estado 
            from cajadespacho, sap_documento, tiendaprioridad, tienda_caja_registro
            where 
            sap_documento.idsapdoc = tiendaprioridad.idsapdoc 
            and tiendaprioridad.id = cajadespacho.tiendaprioridad_id
            and sap_documento.centrocosto ='802'
            and sap_documento.origenreferencia ='magento'
            and cajadespacho.id = tienda_caja_registro.id_caja 
            and tienda_caja_registro.estado = 'RECIBIDO' 
            and tienda_caja_registro.fecha_estado >='$antiguo'",
            null,
            "multiple"
        );
        //var_dump($ciudades);

        while ($data = pg_fetch_object($ciudades)) {
            //$data->referencia = "100476296";
            $existe = consulta(
                "select count(*) from retiroentienda_log where n_oc = " .
                    $data->referencia,
                "scriptlog",
                "single"
            );
            //var_dump($existe);
            if ($existe == "0") {
                //$data->referencia='100476296';
                $datos = sapquery(
                    'https://200.14.247.165:50000/b1s/v1/$crossjoin(Invoices,Invoices/DocumentLines)?$expand=Invoices($select=DocEntry,DocNum,CardCode,%20U_NX_MVname,%20DocDate,%20Indicator,%20FolioNumber,U_NX_MVon,U_NX_MVemail,U_NX_MVtel,%20U_GSP_WHSPICKUP,%20U_AMP_RETIRO_TIENDA),Invoices/DocumentLines($select=ItemCode,ItemDescription,Quantity)%20&$filter=Invoices/DocEntry%20eq%20Invoices/DocumentLines/DocEntry%20and%20Invoices/DocumentLines/ItemCode%20ne%20\'FLETE\'%20%20and%20Invoices/U_NX_TIPO_VTA%20eq%20\'802\'%20and%20U_NX_MVon%20eq%20\''.$data->referencia.'\'%20and%20Invoices/DocObjectCode%20eq%20\'oInvoices\'&$orderby=DocDate',
                    "PRD_INTERANDINA"
                );
                //var_dump($datos);
                $count = 0;
                $body = '';
                $mail ='';
                foreach ($datos as $dato) {
                    $urlprod = consultalinux(
                            "select multivende_photo from sap_productos where codigo = '" .$dato["Invoices/DocumentLines"]['ItemCode']."'",
                            "bd_sap",
                            "single"
                        );
                    if ($count == 0) {
                        $direccion = consultapacking(
                            "select tabsu.nokosu, tabsu.disu, tabcm.nokocm, tabci.nokoci from tabsu, tabci, tabcm where tabsu.cisu =tabci.koci and tabsu.cmsu = tabcm.kocm and tabsu.cisu = tabcm.koci and tabsu.kosu like '".$dato["Invoices"]['U_GSP_WHSPICKUP']."'",
                            null,
                            "multiple"
                        );
                        $direccion = pg_fetch_all($direccion);
                        $nombre = explode(" ",$dato["Invoices"]['U_NX_MVname']);
                        //var_dump($direccion);
                        $ShippingDate = date("d-m-Y",strtotime($data->fecha_estado));
                        $nuevafecha1 = strtotime('+5 day', strtotime($ShippingDate));
                        $PickUpToDate = date('d-m-Y', $nuevafecha1);

                        $body = $body.'{
                        "Name":"'.$nombre[0].'",
                        "LastName":"'.$nombre[1].' '.$nombre[2].' '.$nombre[3].'",
                        "OrderId":"'.$data->referencia.'",
                        "Courier":"",
                        "TrackingNumber":"",
                        "AddressStreet":"'.$direccion[0]['disu'].'",
                        "AddressDistrict":"'.$direccion[0]['nokocm'].'",
                        "AddressRegion":"'.$direccion[0]['nokoci'].'",
                        "ShippingDate":"'.$ShippingDate.'",
                        "PickUpToDate": "'.$PickUpToDate.'",
                        "ShippingType": "retiro",
                        "StoreName": "'.str_replace('LOC.', '',$direccion[0]['nokosu']).'",
                        "Details":[
                            {
                                "Sku":"'.$dato["Invoices/DocumentLines"]['ItemCode'].'",
                                "ProductName":"'.$dato["Invoices/DocumentLines"]['ItemDescription'].'",
                                "Qty": '.$dato["Invoices/DocumentLines"]['Quantity'].',
                                "Url": "'.$urlprod.'"
                            }
                        ';
                        $mail=$dato["Invoices"]['U_NX_MVemail'];
                    }else{
                        $body = $body.',{
                                "Sku":"'.$dato["Invoices/DocumentLines"]['ItemCode'].'",
                                "ProductName":"'.$dato["Invoices/DocumentLines"]['ItemDescription'].'",
                                "Qty": '.$dato["Invoices/DocumentLines"]['Quantity'].',
                                "Url": "'.$urlprod.'"
                            }                ';
                    }
                    $count++;
                }
                $body = $body.'],
                        "Products":[
                            {
                                "Id":2824
                            }
                        ]
                        }';
            }
            echo "<br>".$body."<br>";
            echo correo("maballay@amphora.cl",$body);
            //echo correo($mail,$body);
            //break;
        }
        return Command::SUCCESS;
    }
}
function sapquery($url, $company){
    $id_sesion = consultalinux(
        "select sessionid from servicelayer_sessions where companydb='$company'",
        "bd_sap",
        "single"
    );
    $curl = curl_init();
    echo "<br>";
    echo "<br>";
    echo $id_sesion;
    echo "<br>";
    echo "<br>";
    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Cookie: B1SESSION='.$id_sesion.'; CompanyDB='.$company.'; ROUTEID=.node2'
      ),
    ));
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    if(curl_exec($curl) === false)
    {
        echo 'Curl error: ' . curl_error($curl);
    }
    else
    {
        echo 'Operación completada sin errores';
    }
    $response = curl_exec($curl);
    $response = json_decode($response, true);
        $Arrglo_Sap = $response['value'];
        return $Arrglo_Sap;
    
    //echo $response;
    $info = curl_getinfo($curl);
    curl_close($curl);
    if($info["http_code"]==200){
        $response = json_decode($response, true);
        $Arrglo_Sap = $response['value'];
        return $Arrglo_Sap;
    }else{
        return null;
    }
}

function correo($email, $body){
	echo "<br><br> mail <br>$email <br><br>body <br><br>$body";
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://api.retailrocket.net/api/1.0/partner/5d4af1b797a5252b6449bb89/transactionalemail/60a5310497a5280814d63074?apikey=5d4af1b797a5252b6449bb8a&email='.$email,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS =>$body,
	  CURLOPT_HTTPHEADER => array(
	    'Content-Type: application/json'
	  ),
	));
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	$response = curl_exec($curl);
	$info = curl_getinfo($curl);
	echo "<br>info<br>";
	var_dump($info);
	echo "<br>";
	curl_close($curl);
	echo "<br>error <br>";
	var_dump(curl_error($curl));
	if($info["http_code"]==200){
		return true;
	}else{
		return false;
	}
}
function consultapacking($sql, $db, $type){
    try {
        $conn = pg_connect("host=186.10.254.134 port=5477 dbname=packing user=wmsexterno password=3Xtern0.-")or die('No se ha podido conectar bd_tiendas: ' . pg_last_error());
        if (!$conn) {
            echo "An error occurred.\n";
            exit;
        }
        
        $result = pg_query($conn, $sql);
        if (!$result) {
            echo "An error occurred.\n";
            return null;
        }
        
        //$arr = pg_fetch_all($result);
        if ($type=="multiple") {
            return $result;
        }else{
            $val = pg_fetch_result($result, 0, 0);
            return $val;
        }
        
    } catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        return null;
    }
}
function consultalinux($sql, $database, $type){
    try {
        $connstring="host=186.10.254.134 port=5850 dbname=".$database." user=postgres password=4mPH0R4.2O20";
       //echo $connstring;
        $conn = pg_connect($connstring)or die('No se ha podido conectar bd_tiendas: ' . pg_last_error());
        if (!$conn) {
            echo "An error occurred.\n";
            exit;
        }
        
        $result = pg_query($conn, $sql);
        if (!$result) {
            echo "An error occurred.\n";
            return null;
        }
        
        if ($type=="multiple") {
            return $result;
        }else{
            $val = pg_fetch_result($result, 0, 0);
            return $val;
        }
    } catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        return null;
    }
}
function consulta($sql, $database, $type){
    try {
        $connstring="host=localhost port=5850 dbname=".$database." user=postgres password=4mPH0R4.2O2E";
       //echo $connstring;
        $conn = pg_connect($connstring)or die('No se ha podido conectar bd_tiendas: ' . pg_last_error());
        if (!$conn) {
            echo "An error occurred.\n";
            exit;
        }
        
        $result = pg_query($conn, $sql);
        if (!$result) {
            echo "An error occurred.\n";
            return null;
        }
        
        if ($type=="multiple") {
            return $result;
        }else{
            $val = pg_fetch_result($result, 0, 0);
            return $val;
        }
    } catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        return null;
    }
}