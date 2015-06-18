<?
include('ini.php');

$db->query('select idprodutos, codigo, ficha_tecnica from produtos where idfabricantes = 1 order by idprodutos desc  limit 100');
while($res = $db->fetch()){
	pre($res);
	
	preg_match_all("/\(Compr\. X Larg\. X Alt\.\): (.+?) mm/",htmlentities($res['ficha_tecnica']),$matches);
	$dim = str_replace(" ","",$matches[1][count($matches[1])-1]);
	
	
	preg_match_all("/Bruto:(.+?)kg/",htmlentities($res['ficha_tecnica']),$matches);
	$peso = str_replace(",",".",trim($matches[1][count($matches[1])-1]));
	
	preg_match_all("/Metragem:(.+?)m/",htmlentities($res['ficha_tecnica']),$matches);
	$vol = str_replace(",",".",trim($matches[1][count($matches[1])-1])) * 100;
	
	//list($l,$a,$c) = explode("x",$dim);
	//$vol = max(((($l/100) * ($c/100) * ($a/100))),0.3);
	
	//if($dim){
		echo "$res[idprodutos] $dim $peso $vol<br>";
		$db->query('update produtos set dimensoes = "'.$dim.'", peso = "'.$peso.'", peso_volumetrico = "'.$vol.'" where idprodutos = "'.$res['idprodutos'].'"');
	//}
}

//$db->query('select idprodutos, dimensoes, produto from produtos');
//while($res = $db->fetch()){
//	list($l,$a,$c) = explode("x",$res['dimensoes']);
//	$pc = max(((($l/10) * ($c/10) * ($a/10)) /6000),0.3);
//	$dim = $l.' - '.$c.' - '.$a.' = '.$pc;
//	echo "$res[idprodutos] $pc  produto = ".normaliza($res['produto'])."<br>";
//	$db->query('update produtos set peso_volumetrico = "'.$pc.'", produto = "'.normaliza($res['produto']).'", url = "'.diretorio(normaliza($res['produto'])).'" where idprodutos = "'.$res['idprodutos'].'"');
//}
?>