<?php

include("db_conector.php");
/*error_reporting(E_ALL);
ini_set('display_errors', '1');*/
//Arreglo tiendas//
$Array_Tiendas[0] = 'T17';
$Array_Tiendas[1] = 'T56';
$Array_Tiendas[2] = 'T64';
$Array_Tiendas[3] = 'T67';
$Array_Tiendas[4] = 'T68';
$Array_Tiendas[5] = 'T69';
$Array_Tiendas[6] = 'T70';
$Array_Tiendas[7] = 'T72';
$Array_Tiendas[8] = 'T73';
$Array_Tiendas[9] = 'T74';
$Array_Tiendas[10] = 'T79';
$Array_Tiendas[11] = 'T82';
$Array_Tiendas[12] = 'T83';
$Array_Tiendas[13] = 'T87';
$Array_Tiendas[14] = 'T89';
$Array_Tiendas[15] ='T90' ;


// $Array_Tiendas[0] = 'T17';
// $Array_Tiendas[1] = 'T56';
// $Array_Tiendas[2] = 'T64';
// $Array_Tiendas[3] = 'T68';
// $Array_Tiendas[4] = 'T69';
// $Array_Tiendas[5] = 'T70';
// $Array_Tiendas[6] = 'T72';
// $Array_Tiendas[7] = 'T73';
// $Array_Tiendas[8] = 'T74';
// $Array_Tiendas[9] = 'T79';
// $Array_Tiendas[10] = 'T82';
// $Array_Tiendas[11] = 'T83';
// $Array_Tiendas[12] = 'T84';
// $Array_Tiendas[13] = 'T86';
// $Array_Tiendas[14] = 'T87';
// $Array_Tiendas[15] = 'T89';
// $Array_Tiendas[16] = 'T90';

// $Array_Tiendas[17] = 'ST1'; //casa costanera
// $Array_Tiendas[15] = 'T84'; //LOS DOMINICOS
// $Array_Tiendas[16] = 'T86'; //AHUMADA

//Arreglo Meses//
$Array_Meses[1] = 'ENERO';
$Array_Meses[2] = 'FEBRERO';
$Array_Meses[3] = 'MARZO';
$Array_Meses[4] = 'ABRIL';
$Array_Meses[5] = 'MAYO';
$Array_Meses[6] = 'JUNIO';
$Array_Meses[7] = 'JULIO';
$Array_Meses[8] = 'AGOSTO';
$Array_Meses[9] = 'SEPTIEMBRE';
$Array_Meses[10] = 'OCTUBRE';
$Array_Meses[11] = 'NOVIEMBRE';
$Array_Meses[12] = 'DICIEMBRE';
//
$fecha = date('Y-m-d');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//											 ______    ___    _____   _____ 
//											 | ___ \  / _ \  /  ___| |  ___|
//											 | |_/ / / /_\ \ \ `--.  | |__  
//											 | ___ \ |  _  |  `--. \ |  __| 
//											 | |_/ / | | | | /\__/ / | |___ 
//											 \____/  \_| |_/ \____/  \____/ 
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($_POST['consulta']=='buscar_cuenta_personas'){
	$mes=date("m");
	if ($mes!='null' ) {
		$mes_escrito=($mes*1);
		$mes_numero=$mes;
	} else {
		$mes_escrito=(date("m")*1);
		$mes_numero=date("m");
	}
	if ($mes>=date("m")) {
		$vigente=" AND mts_meta_tiendas.vigente = 'S' ";
	} else {
		$vigente='';
	}

	$sTexto = $sTexto .'<br><br><table id="tabla_mensual" cellpadding="3" cellspacing="0" border="1">
	<thead>
		<tr>
			<th colspan="10">DETALLE MENSUAL '.$Array_Meses[$mes_escrito].' '.date("Y") .'</th>
		</tr>
		<tr align="center">
			<th>TIENDA</th>
			<th>META TIENDA</th>
			<th>VENTA</th>
			<th>% CUMPLIMIENTO</th>
			<th>TASA CONVERSION</th>
			<th>ENTRADAS</th>
			<th>DOCUMENTOS</th>
			<th>TOTAL ART.</th>
			<th>ART. POR DOC.</th>
			<th>PROMEDIO DOC</th>
		</tr>
	</thead><tbody>	';
	
	for($i = 0;$i < count($Array_Tiendas) ; $i++){
		
		$nombre_tienda=consulta("SELECT tienda FROM tiendas WHERE cod_tienda ='".$Array_Tiendas[$i]."' ");

		$metas = consulta("SELECT meta FROM mts_meta_tiendas WHERE cod_tienda ='".$Array_Tiendas[$i]."' AND mes ='$Array_Meses[$mes_escrito]' AND annio = '".date("Y")."' ".$vigente." "); 
		
		if(count($metas) == 0 || $metas[0] == NULL){
			$metas = consulta_metas("SELECT (carteras+marroquineria+calzado+perfumeria) as metita FROM meta WHERE cod_tienda ='".$Array_Tiendas[$i]."' AND mes ='".$Array_Meses[$mes_escrito]."' AND annio = '".date("Y")."' AND vigente = 'S' " ); 
		}
		
		$venta = consulta("SELECT sum(s.price) from sap_vts_detalle s, tiendas t where t.cod_tienda ='".$Array_Tiendas[$i]."' AND s.costingcode = t.cenco 
		AND s.docdate >= '".date("Y")."-".$mes_numero."-01'"); 

		$documentos = consulta("SELECT  count(Distinct s.numero_folio) FROM sap_vts_detalle s, tiendas t where t.cod_tienda ='".$Array_Tiendas[$i]."' AND s.costingcode = t.cenco AND s.docdate >= '".date("Y")."-".$mes_numero."-01'"); 
		
		
		$articulos = consulta("SELECT sum(quantity) from sap_vts_detalle, tiendas where cod_tienda ='".$Array_Tiendas[$i]."' AND costingcode = cenco 
		AND docdate >= '".date("Y")."-".$mes_numero."-01'"); 
		
		$entradas = consulta("SELECT sum(h10 + h11 + h12 + h13 + h14 + h15 + h16 + h17 + h18 + h19 + h20 + h21 + h22 ) from contador_personas where id_tienda= '".$Array_Tiendas[$i]."' AND tipo = 'EI' AND fecha >= '".date("Y")."-".$mes_numero."-01'"); 

		$sTexto = $sTexto .'
		<tr>
			<td width="200" class="detalle_global" id="'.$i.'">'.$nombre_tienda[0].'</td>
			<td align="center" width="100">$'.number_format($metas[0],0,'','.').'</td>
			<td align="center" width="100">$'.number_format(round($venta[0]),0,'','.').'</td>
			<td align="center" width="90">'.number_format((($venta[0]*100)/$metas[0]),2,',','.').'%</td>
			<td align="center" width="90">'.number_format((($documentos[0]*100)/$entradas[0]),2,',','.').'%</td> 
			<td align="center" width="90">'.number_format($entradas[0],0,'','.').'</td>
			
			<td align="center" width="90">'.number_format($documentos[0],0,'','.').'</td>
			<td align="center" width="80">'.number_format($articulos[0],0,'','.').'</td>
			<td align="center" width="80">'.round(($articulos[0]/$documentos[0]),2).'</td>
			<td align="center" width="90">$'.number_format(($venta[0]/$documentos[0]),0,'','.').'</td>
		</tr>';
	}
	$sTexto = $sTexto.'</tbody></table>';


	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	if ($mes=='null' || $mes==(date("m"))) {
		$sTexto = $sTexto .'<br><br><table id="tabla_diario" cellpadding="3" cellspacing="0" border="1">
		<thead>
			<tr>
				<th colspan="10">DETALLE DIARIO '.date('d-m',strtotime('-1 day',strtotime(date($fecha)))).'</th>
			</tr>
			<tr align="center">
				<th>TIENDA</th>
				<th>META DÍA</th>
				<th>VENTA</th>
				<th>% CUMPLIMIENTO</th>
				<th>TASA CONVERSION</th>
				<th>ENTRADAS</th>
				<th>DOCUMENTOS</th>
				<th>TOTAL ART.</th>
				<th>ART. POR DOC.</th>
				<th>PROMEDIO DOC</th>
			</tr>
		</thead><tbody>	';
		
		for($i = 0;$i < count($Array_Tiendas) ; $i++){
			
			$nombre_tienda=consulta("SELECT tienda FROM tiendas WHERE cod_tienda ='".$Array_Tiendas[$i]."' ");
			
			$metas = consulta("SELECT meta FROM mts_metas_diarias, tiendas WHERE tiendas.cod_tienda = '".$Array_Tiendas[$i]."' AND mts_metas_diarias.id_tienda = tiendas.CENCO AND mts_metas_diarias.fecha = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."' " );
							
			if(count($metas) == 0 || $metas[0] == NULL){
				$metas = consulta_tiendas("SELECT meta FROM metas_diarias WHERE id_tienda ='".$Array_Tiendas[$i]."'  AND fecha = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."'");

			}
			
			$venta = consulta("SELECT sum(price) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$Array_Tiendas[$i]."' AND costingcode = cenco 
														AND docdate = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."'");
			
			$documentos = consulta("SELECT  count(Distinct numero_folio) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$Array_Tiendas[$i]."' AND costingcode = cenco 
														AND docdate = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."'");

			$articulos = consulta("SELECT sum(quantity) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$Array_Tiendas[$i]."' AND costingcode = cenco 
														AND docdate = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."'");

			$entradas = consulta("SELECT sum(h10 + h11 + h12 + h13 + h14 + h15 + h16 + h17 + h18 + h19 + h20 + h21 + h22 ) FROM contador_personas WHERE id_tienda= '".$Array_Tiendas[$i]."' AND tipo = 'EI' 
														AND fecha = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."'");

			$sTexto = $sTexto .'
			<tr>
				<td width="200">'.$nombre_tienda[0].'</td>
				<td align="center" width="100">$'.number_format($metas[0],0,'','.').'</td>
				<td align="center" width="100">$'.number_format($venta[0],0,'','.').'</td>
				<td align="center" width="90">'.round((($venta[0]*100)/($metas[0])),2).'%</td>
				<td align="center" width="90">'.round((($documentos[0]*100)/($entradas[0])),2).'%</td>';
				
				$sTexto = $sTexto .'<td align="center" width="90">'.number_format($entradas[0],0,'','.').'</td>';	
				
				$sTexto = $sTexto .'<td align="center" width="90">'.number_format($documentos[0],0,'','.').'</td>
				<td align="center" width="80">'.round(($articulos[0]),2).'</td>
				<td align="center" width="80">'.round(($articulos[0]/$documentos[0]),2).'</td>
				<td align="center" width="90">$'.number_format(($venta[0]/$documentos[0]),0,'','.').'</td>
			</tr>';
		}
		$sTexto = $sTexto."</tbody></table>"; 
	}
	echo $sTexto;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//											 ______    ___    _____   _____ 
//											 | ___ \  / _ \  /  ___| |  ___|
//											 | |_/ / / /_\ \ \ `--.  | |__  
//											 | ___ \ |  _  |  `--. \ |  __| 
//											 | |_/ / | | | | /\__/ / | |___ 
//											 \____/  \_| |_/ \____/  \____/ 

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($_POST['consulta']=='buscar_cuenta_personas_rango'){
	
	$fecha_ini=date('Y-m-d',strtotime($_POST['fecha_ini']));
	$fecha_fin=date('Y-m-d',strtotime($_POST['fecha_fin']));
	$mes_escrito=(date('m',strtotime($fecha_ini))*1);
	$mes_numero=date("m",strtotime($fecha_ini));
	$ano_numero=date("Y",strtotime($fecha_ini));
	if (date("m",strtotime($fecha_ini))>=date("m")) {
		$vigente=" and mts_meta_tiendas.vigente = 'S' ";
	} else {
		$vigente='';
	}

	if(date('m',strtotime($fecha_ini)) != date('m',strtotime($fecha_fin))){
		echo '<br><font color="red"><strong>Las Fechas Deben Corresponder a un Mismo Mes</strong></font>';
		return;	
	}else{
		$sTexto = $sTexto .'<br><br><table id="tabla_mensual" cellpadding="3" cellspacing="0" border="1">
		<thead>
			<tr>
				<th colspan="10">RANGO DE FECHA</th>
			</tr>
			<tr align="center">
				<th>TIENDA</th>
				<th>META TIENDA</th>
				<th>VENTA</th>
				<th>% CUMPLIMIENTO</th>
				<th>TASA CONVERSION</th>
				<th>ENTRADAS</th>
				<th>DOCUMENTOS</th>
				<th>TOTAL ART.</th>
				<th>ART. POR DOC.</th>
				<th>PROMEDIO DOC</th>
			</tr>
		</thead><tbody>	';

		for($i = 0;$i < count($Array_Tiendas) ; $i++){
			
			$nombre_tienda=consulta("SELECT tienda FROM tiendas WHERE cod_tienda ='".$Array_Tiendas[$i]."' ");
			
			$ventas = consulta("SELECT sum(price) from sap_vts_detalle, tiendas where cod_tienda ='".$Array_Tiendas[$i]."' AND costingcode = cenco 
			AND docdate >= '".$fecha_ini."'  AND docdate <= '".$fecha_fin."'");



			$metas = consulta("SELECT meta FROM mts_meta_tiendas WHERE cod_tienda ='".$Array_Tiendas[$i]."' AND mes ='$Array_Meses[$mes_escrito]' 
											AND annio = '".date("Y")."' ".$vigente." "); 
			//OJITO BD_SAP
			if(count($metas) == 0 || $metas[0] == NULL){

				$metas = consulta_metas("SELECT (carteras+marroquineria+calzado+perfumeria) as metita FROM meta WHERE cod_tienda ='".$Array_Tiendas[$i]."' 
				AND mes ='".$Array_Meses[$mes_escrito]."' AND annio = '".date("Y")."' AND vigente = 'S' " ); 

			}
					

			$documentos = consulta("SELECT count(Distinct numero_folio) FROM sap_vts_detalle, tiendas where cod_tienda ='".$Array_Tiendas[$i]."' AND costingcode = cenco 
												AND docdate >= '".$fecha_ini."'  AND docdate <= '".$fecha_fin."'");								

			$articulos = consulta("SELECT sum(quantity) from sap_vts_detalle, tiendas where cod_tienda ='".$Array_Tiendas[$i]."' AND costingcode = cenco 
												AND docdate >= '".$fecha_ini."'  AND docdate <= '".$fecha_fin."'");
			
			$entradas = consulta("SELECT sum(h10 + h11 + h12 + h13 + h14 + h15 + h16 + h17 + h18 + h19 + h20 + h21 + h22 ) from contador_personas where id_tienda= '".$Array_Tiendas[$i]."' AND tipo = 'EI' 
												AND fecha >= '".$fecha_ini."'  AND fecha <= '".$fecha_fin."'");

			$sTexto = $sTexto .'
			<tr >
				<td width="200" class="detalle_global_rango" id="'.$i.'">'.$nombre_tienda[0].'</td>
				<td align="center" width="100">$'.number_format($metas[0],0,'','.').'</td>
				<td align="center" width="100">$'.number_format($ventas[0],0,'','.').'</td>
				<td align="center" width="90">'.number_format((($ventas[0]*100)/$metas[0]),2,',','.').'%</td>
				<td align="center" width="90">'.number_format((($documentos[0]*100)/$entradas[0]),2,',','.').'%</td> ';
				$sTexto = $sTexto .'<td align="center" width="90">'.number_format($entradas[0],0,'','.').'</td>';	
				$sTexto = $sTexto .'<td align="center" width="90">'.number_format($documentos[0],0,'','.').'</td>
				<td align="center" width="80">'.round(($articulos[0]),2).'</td>
				<td align="center" width="80">'.round(($articulos[0]/$documentos[0]),2).'</td>
				<td align="center" width="90">$'.number_format(($ventas[0]/$documentos[0]),0,'','.').'</td>
			</tr>';
		}
		$sTexto = $sTexto.'</tbody></table>';
		echo $sTexto;
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//											   ____   _       ___    ____   _  __
//											  / ___| | |     |_ _|  / ___| | |/ /
//											 | |     | |      | |  | |     | ' / 
//											 | |___  | |___   | |  | |___  | . \ 
//											  \____| |_____| |___|  \____| |_|\_\
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($_POST['consulta']=='buscar_x_persona'){
	$mes=date("m");
	$year=date("Y");
	
	// $fecha=date('Y-m-d');
	$tienda=$_POST['tienda'];

	$nom_tienda=consulta("SELECT tienda FROM tiendas WHERE cod_tienda ='".$_POST['tienda']."'");
	if ($mes!='null' ) {
		$mes_escrito=($mes*1);
		$mes_numero=$mes;
	} else {
		$mes_escrito=(date("m")*1);
		$mes_numero=date("m");
	}
	if ($mes>=date("m")) {
		$vigente=" and vigente = 'S' ";
	} else {
		$vigente='';
	}
	

	$sTexto = $sTexto .'<br><h2>DETALLE POR COLABORADOR</h2><br><table id="tabla_mensual" cellpadding="3" cellspacing="0" border="1">
	<thead>
		<tr>
			<th colspan="10"> MENSUAL '.$Array_Meses[$mes_escrito].' '.date("Y") .'</th>
		</tr>
		<tr align="center">
			<th>COLABORADOR</th>
			<th>META TIENDA</th>
			<th>VENTA</th>
			<th>% CUMPLIMIENTO</th>
			<th>TASA CONVERSION</th>
			<th>ENTRADAS</th>
			<th>DOCUMENTOS</th>
			<th>TOTAL ART.</th>
			<th>ART. POR DOC.</th>
			<th>PROMEDIO DOC</th>
		</tr>
	</thead><tbody>	';


	$ventax = consulta("SELECT salesname, sum(price) as venta from sap_vts_detalle, tiendas where cod_tienda =  '".$Array_Tiendas[$tienda]."' 
	AND DATE_PART('month', docdate) = '".$mes_numero."' AND	 DATE_PART('year', docdate) = '".$year."' AND costingcode = cenco group by salesname	order by salesname DESC");

	// $ventax = consulta("SELECT salespersoncode, sum(price) from sap_vts_detalle, tiendas WHERE costingcode = cenco AND cod_tienda = '".$tienda."' 
	// 					AND docdate >= '".$fecha_ini."'  AND docdate <= '".$fecha_fin."' GROUP BY salespersoncode");


	for ($i=0; $i <count($ventax) ; $i=$i+2) {

		$meta_tienda = consulta("SELECT meta FROM mts_meta_tiendas WHERE cod_tienda ='".$Array_Tiendas[$tienda]."' AND mes ='$Array_Meses[$mes_escrito]' 
											AND annio = '".date("Y")."' ".$vigente." "); 


		if(count($meta_tienda) == 0 || $meta_tienda[0] == NULL){

			$meta_tienda = consulta_metas("SELECT (carteras+marroquineria+calzado+perfumeria) as metita FROM meta WHERE cod_tienda ='".$Array_Tiendas[$tienda]."' 
			AND mes ='".$Array_Meses[$mes_escrito]."' AND annio = '".date("Y")."' AND vigente = 'S' " ); 

		}

		

		$documentos = consulta("SELECT  count(Distinct numero_folio) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$Array_Tiendas[$tienda]."' AND costingcode = cenco  AND salesname = '".$ventax[$i]."' 
								AND to_char(docdate, 'YYYY-MM') LIKE '".date("Y",strtotime($fecha))."-".$mes_numero."'"); 


		$articulos = consulta("SELECT sum(quantity) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$Array_Tiendas[$tienda]."' AND costingcode = cenco  AND salesname = '".$ventax[$i]."' 
							AND to_char(docdate, 'YYYY-MM') LIKE '".date("Y",strtotime($fecha))."-".$mes_numero."'");

		$entradas = consulta("SELECT sum(h10 + h11 + h12 + h13 + h14 + h15 + h16 + h17 + h18 + h19 + h20 + h21 + h22 ) FROM contador_personas WHERE id_tienda= '".$Array_Tiendas[$tienda]."' AND tipo = 'EI' 
						AND to_char(fecha, 'YYYY-MM') LIKE '".date("Y",strtotime($fecha))."-".$mes_numero."'"); 
		
		$sTexto = $sTexto .'
		<tr>
			<td width="250">'.$ventax[$i].'</td>
			<td align="center" width="100">$'.number_format($meta_tienda[0],0,'','.').'</td>
			<td align="center" width="100">$'.number_format(round($ventax[$i+1]),0,'','.').'</td>
			<td align="center" width="90">'.number_format((($ventax[$i+1]*100)/$meta_tienda[0]),2,',','.').'%</td>
			<td align="center" width="90">'.number_format((($documentos[0]*100)/$entradas[0]),2,',','.').'%</td>
			<td align="center" width="90">'.number_format($entradas[0],0,'','.').'</td>
			<td align="center" width="90">'.number_format($documentos[0],0,'','.').'</td>
			<td align="center" width="80">'.number_format($articulos[0],0,'','.').'</td>
			<td align="center" width="80">'.round(($articulos[0]/$documentos[0]),2).'</td>
			<td align="center" width="90">$'.number_format(($ventax[$i+1]/$documentos[0]),0,'','.').'</td>
		</tr> ';
	}

	$sTexto = $sTexto.'</tbody></table>';
	//

	if ($mes=='null' || $mes==(date("m"))) {
		
	$sTexto = $sTexto .'<br><br><table id="tabla_diario" cellpadding="3" cellspacing="0" border="1">
		<thead>
			<tr>
				<th colspan="10">DIARIO '.date('d-m',strtotime('-1 day',strtotime(date($fecha)))).'</th>
			</tr>
			<tr align="center">
			<th>COLABORADOR</th>
			<th>META DÍA</th>
			<th>VENTA</th>
			<th>% CUMPLIMIENTO</th>
			<th>TASA CONVERSION</th>
			<th>ENTRADAS</th>
			<th>DOCUMENTOS</th>
			<th>TOTAL ART.</th>
			<th>ART. POR DOC.</th>
			<th>PROMEDIO DOC</th>
		</tr>
		</thead><tbody>	';
	

		
		$ventax = consulta("SELECT salesname, sum(price) as venta from sap_vts_detalle, tiendas WHERE costingcode = cenco AND cod_tienda = '".$Array_Tiendas[$tienda]."' 
													AND docdate = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."' GROUP BY salesname");

							
		if (empty($ventax)) {
			$sTexto=$sTexto.'<tr><td colspan="10" align="center">NO SE REGISTRAN VENTAS</td></tr></tbody></table>';
		} else {		
			for ($i=0; $i <count($ventax) ; $i=$i+2) {
			
				$metas = consulta("SELECT meta FROM mts_metas_diarias, tiendas WHERE tiendas.cod_tienda = '".$Array_Tiendas[$i]."' 
														AND mts_metas_diarias.id_tienda = tiendas.CENCO 
														AND mts_metas_diarias.fecha = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."' " );


														
				if(count($metas) == 0 || $metas[0] == NULL){

					$metas = consulta_tiendas("SELECT meta FROM metas_diarias WHERE id_tienda ='".$Array_Tiendas[$i]."' 
					AND fecha = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."'");

				}
				

				
				// $venta_dia = consulta("SELECT sum(price) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$Array_Tiendas[$i]."' AND costingcode = cenco 
				// 										AND docdate = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."'");




				
				$documentos = consulta("SELECT  count(Distinct numero_folio) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$Array_Tiendas[$tienda]."' AND costingcode = cenco and salesname = '".$ventax[$i]."' 
														AND docdate = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."'");
				
				$articulos = consulta("SELECT sum(quantity) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$Array_Tiendas[$tienda]."' AND costingcode = cenco and salesname = '".$ventax[$i]."' 
														AND docdate = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."'");
	
				$entradas = consulta("SELECT sum(h10 + h11 + h12 + h13 + h14 + h15 + h16 + h17 + h18 + h19 + h20 + h21 + h22 ) FROM contador_personas WHERE id_tienda= '".$Array_Tiendas[$tienda]."' AND tipo = 'EI' 
												AND fecha = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."'");
												
				$sTexto = $sTexto .'
				<tr>
					<td width="250">'.$ventax[$i].'</td>
					<td align="center" width="100">$'.number_format($metas[0],0,'','.').'</td>
					<td align="center" width="100">$'.number_format($ventax[$i+1],0,'','.').'</td>
					<td align="center" width="90">'.number_format(round((($ventax[$i+1]*100)/($metas[0])),2),2,',','.').'%</td>
					<td align="center" width="90">'.number_format(round((($documentos[0]*100)/($entradas[0])),2),2,',','.').'%</td>';
					
					$sTexto = $sTexto .'<td align="center" width="90">'.number_format($entradas[0],0,'','.').'</td>';	
					$sTexto = $sTexto .'<td align="center" width="90">'.number_format($documentos[0],0,'','.').'</td>
					
					<td align="center" width="80">'.number_format($articulos[0],0,'','.').'</td>
					<td align="center" width="80">'.round(($articulos[0]/$documentos[0]),2).'</td>
					<td align="center" width="90">$'.number_format(($ventax[$i+1]/$documentos[0]),0,'','.').'</td>
				</tr>';
			}
		$sTexto = $sTexto."</tbody></table> <br><br>";
		}
	}
	echo $sTexto;
		
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//											   ____   _       ___    ____   _  __
//											  / ___| | |     |_ _|  / ___| | |/ /
//											 | |     | |      | |  | |     | ' / 
//											 | |___  | |___   | |  | |___  | . \ 
//											  \____| |_____| |___|  \____| |_|\_\
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($_POST['consulta']=='buscar_x_persona_rango'){
	
	$tienda=$_POST['tienda'];
	$fecha_ini=date('Y-m-d',strtotime($_POST['fecha_ini']));
	$fecha_fin=date('Y-m-d',strtotime($_POST['fecha_fin']));
	$mes_escrito=(date('m',strtotime($fecha_ini))*1);
	$mes_numero=date("m",strtotime($fecha_ini));
	if (date("m",strtotime($fecha_ini))>=date("m")) {
		$vigente=" and vigente = 'S' ";
	} else {
		$vigente='';
	}

	$sTexto = $sTexto .'<br><h2>DETALLE POR COLABORADOR</h2><br><table id="tabla_mensual" cellpadding="3" cellspacing="0" border="1">
	<thead>
		<tr>
			<th colspan="10">RANGO DE FECHA</th>
		</tr>
		<tr align="center">
			<th>COLABORADOR</th>
			<th>META TIENDA</th>
			<th>VENTA</th>
			<th>% CUMPLIMIENTO</th>
			<th>TASA CONVERSION</th>
			<th>ENTRADAS</th>
			<th>DOCUMENTOS</th>
			<th>TOTAL ART.</th>
			<th>ART. POR DOC.</th>
			<th>PROMEDIO DOC</th>
		</tr>
	</thead><tbody>	';


	$ventax = consulta("SELECT salesname, sum(price) as venta from sap_vts_detalle, tiendas where cod_tienda =  '".$Array_Tiendas[$tienda]."'  AND docdate >= '".$fecha_ini."' 
	 AND docdate <= '".$fecha_fin."' AND costingcode = cenco group by salesname	order by salesname DESC");

	// $ventax = consulta("SELECT salespersoncode, sum(price) from sap_vts_detalle, tiendas WHERE costingcode = cenco AND cod_tienda = '".$tienda."' 
	// 					AND docdate >= '".$fecha_ini."'  AND docdate <= '".$fecha_fin."' GROUP BY salespersoncode");


	for ($i=0; $i <count($ventax) ; $i=$i+2) {


		$metas = consulta("SELECT meta FROM mts_meta_colaboradoras WHERE id_tienda ='".$Array_Tiendas[$tienda]."' AND mes ='".$Array_Meses[$mes_escrito]."' 
		AND annio = '".date("Y",strtotime($fecha_ini))."' AND nombre ='".$ventax[$i]."' ".$vigente_colab);

		if(count($metas) == 0 || $metas[0] == NULL){

			$metas = consulta_metas("SELECT (carteras+marroquineria+calzado+perfumeria) as metita FROM meta WHERE cod_tienda ='".$Array_Tiendas[$tienda]."' 
			AND mes ='".$Array_Meses[$mes_escrito]."' AND annio = '".date("Y")."' AND vigente = 'S' " ); 
		}

		$documentos = consulta("SELECT  count(Distinct numero_folio) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$Array_Tiendas[$tienda]."' AND costingcode = cenco 
		AND  docdate >= '".$fecha_ini."' and docdate <= '".$fecha_fin."' AND salesname = '".$ventax[$i]."' ");

		$articulos = consulta("SELECT sum(quantity) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$Array_Tiendas[$tienda]."' AND costingcode = cenco 
		AND  docdate >= '".$fecha_ini."' and docdate <= '".$fecha_fin."' AND salesname = '".$ventax[$i]."'");

		$entradas = consulta("SELECT sum(h10 + h11 + h12 + h13 + h14 + h15 + h16 + h17 + h18 + h19 + h20 + h21 + h22 ) FROM contador_personas WHERE id_tienda= '".$Array_Tiendas[$tienda]."' AND tipo = 'EI' 
		AND  fecha >= '".$fecha_ini."' and fecha <= '".$fecha_fin."' ");

									
		$sTexto = $sTexto .'
		<tr >
			<td width="250">'.$ventax[$i].'</td>
			<td align="center" width="100">$'.number_format($metas[0],0,'','.').'</td>
			<td align="center" width="100">$'.number_format(round($ventax[$i+1]),0,'','.').'</td>
			<td align="center" width="90">'.number_format(round((($ventax[$i+1]*100)/$metas[0]),2),2,',','.').'%</td>
			<td align="center" width="90">'.number_format(round((($documentos[0]*100)/$entradas[0]),2),2,',','.').'%</td> 
			
			<td align="center" width="90">'.number_format($entradas[0],0,'','.').'</td>
			<td align="center" width="90">'.number_format($documentos[0],0,'','.').'</td>
			
			<td align="center" width="80">'.number_format($articulos[0],0,'','.').'</td>
			<td align="center" width="80">'.round(($articulos[0]/$documentos[0]),2).'</td>
			<td align="center" width="90">$'.number_format(($ventax[$i+1]/$documentos[0]),0,'','.').'</td>
		</tr>';
	
	}
	
	$sTexto = $sTexto.'</tbody></table> <br><br>';
	echo $sTexto;	
}	



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//									 __  __   _____ ___ _____ _   _ ____    _    
//									 \ \/ /  |_   _|_ _| ____| \ | |  _ \  / \   
//									  \  /     | |  | ||  _| |  \| | | | |/ _ \  
//									  /  \     | |  | || |___| |\  | |_| / ___ \ 
//									 /_/\_\    |_| |___|_____|_| \_|____/_/   \_\

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($_POST['consulta']=='buscar_cuenta_personas_rango_tienda'){
	$tienda=$_POST['tienda'];	
	$fecha_ini=date('Y-m-d',strtotime($_POST['fecha_ini']));
	$fecha_fin=date('Y-m-d',strtotime($_POST['fecha_fin']));
	$mes_escrito=(date('m',strtotime($fecha_ini))*1);
	$mes_numero=date("m",strtotime($fecha_ini));
	if (date("m",strtotime($fecha_ini))>=date("m")) {
		$vigente=" and mts_meta_tiendas.vigente = 'S' ";
		$vigente_colab=" and mts_meta_colaboradoras.vigente = 'S' ";
	} else {
		$vigente='';
		$vigente_colab='';
	}
	if(date('m',strtotime($fecha_ini)) != date('m',strtotime($fecha_fin))){
		echo '<br><font color="red"><strong>Las Fechas Deben Corresponder a un Mismo Mes</strong></font>';
		return;
	}else{
		$sTexto = $sTexto .'<br><br><table id="tabla_mensual" cellpadding="3" cellspacing="0" border="1">
		<thead>
			<tr>
				<th colspan="10">DETALLE TIENDA x RANGO FECHA</th>
			</tr>
			<tr align="center">
				<th>TIENDA</th>
				<th>META TIENDA</th>
				<th>VENTA</th>
				<th>% CUMPLIMIENTO</th>
				<th>TASA CONVERSION</th>
				<th>ENTRADAS</th>
				<th>DOCUMENTOS</th>
				<th>TOTAL ART.</th>
				<th>ART. POR DOC.</th>
				<th>PROMEDIO DOC</th>
			</tr>
		</thead><tbody>	';
	
		$nombre_tienda=consulta("SELECT tienda FROM tiendas WHERE cod_tienda ='".$tienda."' ");	
	

		// BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP -----
		// $metas = consulta("SELECT meta FROM mts_meta_tiendas WHERE cod_tienda ='".$Array_Tiendas[$i]."' AND mes ='".$Array_Meses[$mes_escrito]."' 
		// 											AND annio = '".date("Y")."' ".$vigente." ");
		// BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP ----- BD_SAP -----

		$metas = consulta("SELECT meta FROM mts_meta_tiendas WHERE cod_tienda ='".$tienda."' AND mes ='".$Array_Meses[$mes_escrito]."' 
										AND annio = '".date("Y")."' ".$vigente." " ); //  AND annio = '2021' AND vigente = 'S'" );

		if(count($metas) == 0 || $metas[0] == NULL){

			$metas = consulta_metas("SELECT (carteras+marroquineria+calzado+perfumeria) as metita FROM meta WHERE cod_tienda ='".$tienda."' 
			AND mes ='".$Array_Meses[$mes_escrito]."' AND annio = '".date("Y")."' AND vigente = 'S' " ); 
		}
		
		$ventas = consulta("SELECT sum(price) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$tienda."' AND costingcode = cenco 
													AND  docdate >= '".$fecha_ini."' and docdate <= '".$fecha_fin."' ");
											
		$documentos = consulta("SELECT count(Distinct numero_folio) FROM sap_vts_detalle, tiendas where cod_tienda ='".$tienda."' AND costingcode = cenco 
													AND  docdate >= '".$fecha_ini."'  AND docdate <= '".$fecha_fin."'");								

		$articulos = consulta("SELECT sum(quantity) from sap_vts_detalle, tiendas where cod_tienda ='".$tienda."' AND costingcode = cenco 
													AND docdate >= '".$fecha_ini."'  AND docdate <= '".$fecha_fin."'");

		$entradas = consulta("SELECT sum(h10 + h11 + h12 + h13 + h14 + h15 + h16 + h17 + h18 + h19 + h20 + h21 + h22 ) from contador_personas where id_tienda= '".$tienda."' AND tipo = 'EI' 
													AND fecha >= '".$fecha_ini."'  AND fecha <= '".$fecha_fin."'");
						
		$sTexto = $sTexto .'
		<tr >
			<td width="250">'.$nombre_tienda[0].'</td>
			<td align="center" width="100">$'.number_format($metas[0],0,'','.').'</td>
			<td align="center" width="100">$'.number_format($ventas[0],0,'','.').'</td>
			<td align="center" width="90">'.number_format(round((($ventas[0]*100)/($metas[0])),2),2,',','.').'%</td>
			<td align="center" width="90">'.number_format(round((($documentos[0]*100)/($entradas[0])),2),2,',','.').'%</td>';
			$sTexto = $sTexto .'<td align="center" width="90">'.number_format($entradas[0],0,'','.').'</td>';	
			$sTexto = $sTexto .'<td align="center" width="80">'.number_format($documentos[0],0,'','.').'</td>
			
			<td align="center" width="80">'.number_format($articulos[0],0,'','.').'</td>
			<td align="center" width="80">'.round(($articulos[0]/$documentos[0]),2).'</td>
			<td align="center" width="90">$'.number_format(($ventas[0]/$documentos[0]),0,'','.').'</td>
		</tr>';

		$sTexto = $sTexto.'</tbody></table>';
		

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


		$sTexto = $sTexto .'<br><br><table id="tabla_mensual" cellpadding="3" cellspacing="0" border="1">
			<thead>
				<tr>
					<th colspan="10">DETALLE COLABORADOR</th>
				</tr>
				<tr align="center">
					<th>COLABORADOR</th>
					<th>META</th>
					<th>VENTA</th>
					<th>% CUMPLIMIENTO</th>
					<th>TASA CONVERSION</th>
					<th>ENTRADAS</th>
					<th>DOCUMENTOS</th>
					<th>TOTAL ART.</th>
					<th>ART. POR DOC.</th>
					<th>PROMEDIO DOC</th>
				</tr>
			</thead><tbody>	';

		$ventax = consulta("SELECT salesname, sum(price) as venta from sap_vts_detalle, tiendas where cod_tienda =  '".$tienda."'  AND docdate >= '".$fecha_ini."'  AND docdate <= '".$fecha_fin."'
		 												AND costingcode = cenco	group by salesname	order by salesname DESC");

		// $ventax = consulta("SELECT salespersoncode, sum(price) from sap_vts_detalle, tiendas WHERE costingcode = cenco AND cod_tienda = '".$tienda."' 
		// 					AND docdate >= '".$fecha_ini."'  AND docdate <= '".$fecha_fin."' GROUP BY salespersoncode");




		for ($i=0; $i <count($ventax) ; $i=$i+2) {
		

			$metas = consulta("SELECT meta FROM mts_meta_colaboradoras WHERE id_tienda ='".$tienda."' AND mes ='".$Array_Meses[$mes_escrito]."' 
											AND annio = '".date("Y",strtotime($fecha_ini))."' AND nombre ='".$ventax[$i]."' ".$vigente_colab);

			if(count($metas) == 0 || $metas[0] == NULL){

				$metas = consulta_metas("SELECT (carteras+marroquineria+calzado+perfumeria) as metita FROM meta WHERE cod_tienda ='".$tienda."' 
				AND mes ='".$Array_Meses[$mes_escrito]."' AND annio = '".date("Y")."' AND vigente = 'S' " ); 
			}


			
			$documentos = consulta("SELECT  count(Distinct numero_folio) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$tienda."' AND costingcode = cenco 
													AND  docdate >= '".$fecha_ini."' and docdate <= '".$fecha_fin."' AND salesname = '".$ventax[$i]."' ");
			
			$articulos = consulta("SELECT sum(quantity) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$tienda."' AND costingcode = cenco 
													AND  docdate >= '".$fecha_ini."' and docdate <= '".$fecha_fin."' AND salesname = '".$ventax[$i]."'");

			$entradas = consulta("SELECT sum(h10 + h11 + h12 + h13 + h14 + h15 + h16 + h17 + h18 + h19 + h20 + h21 + h22 ) FROM contador_personas WHERE id_tienda= '".$tienda."' AND tipo = 'EI' 
													AND  fecha >= '".$fecha_ini."' and fecha <= '".$fecha_fin."' ");
				
		$sTexto = $sTexto .'
		 	<tr>
		 		<td width="250">'.$ventax[$i].'</td>
		 		<td align="center" width="100">$'.number_format($metas[0],0,'','.').'</td>
		 		<td align="center" width="100">$'.number_format($ventax[$i+1],0,'','.').'</td>
		 		<td align="center" width="90">'.number_format(round((($ventax[$i+1]*100)/($metas[0])),2),2,',','.').'%</td>
		 		<td align="center" width="90">'.number_format(round((($documentos[0]*100)/($entradas[0])),2),2,',','.').'%</td>';				
		 		$sTexto = $sTexto .'<td align="center" width="90">'.number_format($entradas[0],0,'','.').'</td>';	
		 		$sTexto = $sTexto .'<td align="center" width="80">'.number_format($documentos[0],0,'','.').'</td>
				 <td align="center" width="80">'.number_format($articulos[0],0,'','.').'</td>
		 		<td align="center" width="80">'.round(($articulos[0]/$documentos[0]),2).'</td>
				<td align="center" width="90">$'.number_format(($ventax[$i+1]/$documentos[0]),0,'','.').'</td>
			</tr>';
		 } 
	$sTexto = $sTexto."</tbody></table>";
	}
	echo $sTexto;
}



//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//									 __  __   _____ ___ _____ _   _ ____    _    
//									 \ \/ /  |_   _|_ _| ____| \ | |  _ \  / \   
//									  \  /     | |  | ||  _| |  \| | | | |/ _ \  
//									  /  \     | |  | || |___| |\  | |_| / ___ \ 
//									 /_/\_\    |_| |___|_____|_| \_|____/_/   \_\

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($_POST['consulta']=='buscar_cuenta_personas_x_tienda'){
	
	$mes=date("m");
	$fecha=date('Y-m-d');
	$tienda=$_POST['tienda'];
	// $fecha_ini= $_POST['fecha_ini'];
	// $fecha_fin= $_POST['fecha_fin'];
	$mes_escrito=($mes*1);
	$mes_numero=$mes;
	$vigente=" and mts_meta_tiendas.vigente = 'S' ";

	$sTexto = $sTexto .'<br><br><table id="tabla_mensual" cellpadding="3" cellspacing="0" border="1">
	<thead>
		<tr>
			<th colspan="10">DETALLE MENSUAL '.$Array_Meses[$mes_escrito].' '.date("Y") .'</th>
		</tr>
		<tr align="center">
			<th>TIENDA</th>
			<th>META TIENDA</th>
			<th>VENTA</th>
			<th>% CUMPLIMIENTO</th>
			<th>TASA CONVERSION</th>
			<th>ENTRADAS</th>
			<th>DOCUMENTOS</th>
			<th>TOTAL ART.</th>
			<th>ART. POR DOC.</th>
			<th>PROMEDIO DOC</th>
		</tr>
	</thead><tbody>	';
			
	$nombre_tienda=consulta("SELECT tienda FROM tiendas WHERE cod_tienda ='".$tienda."' ");


	// BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------
	// $meta_tienda = consulta("SELECT meta FROM mts_meta_tiendas WHERE cod_tienda ='".$tienda."' AND mes ='".$Array_Meses[$mes_escrito]."' 
	// 									AND annio = '".date("Y")."' ".$vigente." " ); //  AND annio = '2021' AND vigente = 'S'" );
	// BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------ BD_SAP ------
	
	$meta_tienda = consulta("SELECT meta FROM mts_meta_tiendas WHERE cod_tienda ='".$tienda."' AND mes ='".$Array_Meses[$mes_escrito]."' 
										AND annio = '".date("Y")."' ".$vigente." " ); //  AND annio = '2021' AND vigente = 'S'" );

	if(count($meta_tienda) == 0 || $meta_tienda[0] == NULL){

		$meta_tienda = consulta_metas("SELECT (carteras+marroquineria+calzado+perfumeria) as metita FROM meta WHERE cod_tienda ='".$tienda."' 
		AND mes ='".$Array_Meses[$mes_escrito]."' AND annio = '".date("Y")."' AND vigente = 'S' " ); 
	}

	$venta = consulta("SELECT sum(price) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$tienda."' AND costingcode = cenco 
													AND to_char(docdate, 'YYYY-MM') LIKE '".date("Y",strtotime($fecha))."-".$mes_numero."'"); // LIKE 'date("Y",strtotime($fecha_ini))-$mes_numero'");

	$documentos = consulta("SELECT  count(Distinct numero_folio) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='$tienda' AND costingcode = cenco 
													AND to_char(docdate, 'YYYY-MM') LIKE '".date("Y",strtotime($fecha))."-".$mes_numero."'"); // LIKE '".date("Y",strtotime($fecha_ini))."-".$mes_numero."'");


	$articulos = consulta("SELECT sum(quantity) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$tienda."' AND costingcode = cenco 
												AND to_char(docdate, 'YYYY-MM') LIKE '".date("Y",strtotime($fecha))."-".$mes_numero."'");// LIKE '".date("Y",strtotime($fecha_ini))."-".$mes_numero."'");

	$entradas = consulta("SELECT sum(h10 + h11 + h12 + h13 + h14 + h15 + h16 + h17 + h18 + h19 + h20 + h21 + h22 ) FROM contador_personas WHERE id_tienda= '".$tienda."' AND tipo = 'EI' 
											AND to_char(fecha, 'YYYY-MM') LIKE '".date("Y",strtotime($fecha))."-".$mes_numero."'"); // LIKE '".date("Y",strtotime($fecha_ini))."-".$mes_numero."'");

	$sTexto = $sTexto .'
		<tr>
			<td width="250">'.$nombre_tienda[0].'</td>
			<td align="center" width="100">$'.number_format($meta_tienda[0],0,'','.').'</td>
			<td align="center" width="100">$'.number_format(round($venta[0]),0,'','.').'</td>
			<td align="center" width="90">'.number_format(round((($venta[0]*100)/$meta_tienda[0]),2),2,',','.').'%</td>
			<td align="center" width="90">'.number_format(round((($documentos[0]*100)/$entradas[0]),2),2,',','.').'%</td> 
			<td align="center" width="90">'.number_format($entradas[0],0,'','.').'</td>
			<td align="center" width="80">'.number_format($documentos[0],0,'','.').'</td>
			<td align="center" width="80">'.number_format($articulos[0],0,'','.').'</td>
			<td align="center" width="80">'.round(($articulos[0]/$documentos[0]),2).'</td>
			<td align="center" width="90">$'.number_format(($venta[0]/$documentos[0]),0,'','.').'</td>
		</tr>';
		
	$sTexto = $sTexto.'</tbody></table>';


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	$sTexto = $sTexto .'<br><br><table id="tabla_diario" cellpadding="3" cellspacing="0" border="1">
	<thead>
		<tr>
			<th colspan="10">DETALLE DIARIO '.date('d-m',strtotime('-1 day',strtotime(date($fecha)))).'</th>
		</tr>
		<tr align="center">
			<th>COLABORADOR</th>
			<th>META DÍA</th>
			<th>VENTA</th>
			<th>% CUMPLIMIENTO</th>
			<th>TASA CONVERSION</th>
			<th>ENTRADAS</th>
			<th>DOCUMENTOS</th>
			<th>TOTAL ART.</th>
			<th>ART. POR DOC.</th>
			<th>PROMEDIO DOC</th>
		</tr>
	</thead><tbody>	';

	$ventax = consulta("SELECT salesname, sum(price) as venta from sap_vts_detalle, tiendas WHERE cod_tienda = '".$tienda."' AND costingcode = cenco
							AND docdate = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."' GROUP BY salesname");

	if (empty($ventax)) {
		$sTexto=$sTexto.'<tr><td colspan="10" align="center">NO SE REGISTRAN VENTAS</td></tr></tbody></table>';
	} else {		
		for ($i=0; $i <count($ventax) ; $i=$i+2) {
		
			$nombre_tienda=consulta("SELECT tienda FROM tiendas WHERE cod_tienda ='".$Array_Tiendas[$i]."' ");


			$metas = consulta("SELECT meta FROM mts_metas_diarias, tiendas WHERE tiendas.cod_tienda = '".$Array_Tiendas[$i]."' 
														AND mts_metas_diarias.id_tienda = tiendas.CENCO 
														AND mts_metas_diarias.fecha = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."' " );


														
				if(count($metas) == 0 || $metas[0] == NULL){

					$metas = consulta_tiendas("SELECT meta FROM metas_diarias WHERE id_tienda ='".$Array_Tiendas[$i]."' 
					AND fecha = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."'");

				}

			// $venta_dia = consulta("SELECT sum(price) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$Array_Tiendas[$i]."' AND costingcode = cenco 
			// 										AND docdate = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."'");
			
			$documentos = consulta("SELECT  count(Distinct numero_folio) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$tienda."' AND costingcode = cenco and salesname = '".$ventax[$i]."' 
													AND docdate = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."'");
			
			$articulos = consulta("SELECT sum(quantity) FROM sap_vts_detalle, tiendas WHERE cod_tienda ='".$tienda."' AND costingcode = cenco and salesname = '".$ventax[$i]."' 
													AND docdate = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."'");

			$entradas = consulta("SELECT sum(h10 + h11 + h12 + h13 + h14 + h15 + h16 + h17 + h18 + h19 + h20 + h21 + h22 ) FROM contador_personas WHERE id_tienda= '".$tienda."' AND tipo = 'EI' 
											AND fecha = '".date('Y-m-d',strtotime('-1 day',strtotime(date($fecha))))."'");
											
			$sTexto = $sTexto .'
			<tr>
				<td width="250">'.$ventax[$i].'</td>
				<td align="center" width="100">$'.number_format($metas[0],0,'','.').'</td>
				<td align="center" width="100">$'.number_format($ventax[$i+1],0,'','.').'</td>
				<td align="center" width="90">'.number_format(round((($ventax[$i+1]*100)/($metas[0])),2),2,',','.').'%</td>
				<td align="center" width="90">'.number_format(round((($documentos[0]*100)/($entradas[0])),2),2,',','.').'%</td>';
				
				$sTexto = $sTexto .'<td align="center" width="90">'.number_format($entradas[0],0,'','.').'</td>';	
				
				$sTexto = $sTexto .'<td align="center" width="80">'.number_format($documentos[0],0,'','.').'</td>
	
				<td align="center" width="80">'.number_format($articulos[0],0,'','.').'</td>
				<td align="center" width="80">'.round(($articulos[0]/$documentos[0]),2).'</td>
				<td align="center" width="90">$'.number_format(($ventax[$i+1]/$documentos[0]),0,'','.').'</td>
			</tr>';
		}
	$sTexto = $sTexto."</tbody></table>";
	}
	echo $sTexto;
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>  








