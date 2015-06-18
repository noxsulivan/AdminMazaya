<?
$timeIni = microtime(true);
$_tmp = explode("/",$_SERVER['REQUEST_URI']);

if(!preg_match('/admin/i',$_tmp[1])){
	$includeIni = $_SERVER['DOCUMENT_ROOT']."/".$_tmp[1]."/ini.php";
}else{
	$includeIni = $_SERVER['DOCUMENT_ROOT']."/ini.php";
}
include($includeIni);
define ('COOKIE_NAME', diretorio('usuarioAdmin_'.$_SERVER['HTTP_HOST']."_".$_SERVER['SCRIPT_FILENAME']."_".date("Ymd")."_reset2"));



set_time_limit ( 0 );

$_t = array();
function limpaPost($v,$k){
	global $_t;
	$_t[str_replace("amp;","",$k)] = is_array($v) ? $v : utf8_decode($v);
}
$_POST = array_walk($_POST,"limpaPost");
$_POST = $_t;

$queryString = ereg('public_html',$_SERVER["QUERY_STRING"]) ? '' : $_SERVER["QUERY_STRING"];
//die("|".$_SERVER["QUERY_STRING"]."|".$queryString."|");
$admin = new admin($queryString);

			$tipos_de_situacao['01'] = 1;
			$tipos_de_situacao['02'] = 2;
			$tipos_de_situacao['03'] = 3;
			$tipos_de_situacao['04'] = 4;
			$tipos_de_situacao['00'] = 5;
			$tipos_de_situacao['05'] = 6;
			$tipos_de_situacao['06'] = 7;

	//$db->query('select * from boletos order by idboletos desc limit 10');
	$db->query('select * from pedidos where tipo_pagamento like "%ITAUSHOPLINE%" and idestagios < 3 order by idpedidos desc limit 100');
	//$db->query('select * from boletos where valor > 0 and data_vencimento > 0 order by idboletos desc');
	
	pre("Boletos a serem analizados ".$db->rows);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://shopline.itau.com.br/shopline/consulta.asp");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
	$Itau = new Itaucripto;
	while($res = $db->fetch()){
			//$consulta = $Itau->geraConsulta('J0046371390001000000011068', $res['idboletos'], 1, 'VIAGEM20COTA0709');
			$consulta = $Itau->geraConsulta('J0043497660001370000012814', $res['idpedidos'], 1, 'MESACORTRA243101');
			$data = http_build_query(array("DC" => $consulta));
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$xml = simplexml_load_string(curl_exec($ch));
			$_a = array();
			
			foreach($xml->PARAMETER->PARAM as $param){
				$t = $param->attributes();
				$_a[(string)$t['ID']] = (string)$t['VALUE'];
			}
			pre($_a);
			flush();
	}
	curl_close($ch);
	die;
?>