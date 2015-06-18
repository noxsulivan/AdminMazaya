<?
include('../ini.php');


$pagina = new layout($_SERVER['QUERY_STRING']);


	$db->query("truncate materiais_consumidos");
	$db->query("select * from itens");
	
	
	while($res = $db->fetch()){
		
		$item = new objetoDb("itens",$res['iditens']);
		
		if($cliente[$item->clientes->id][$item->produtos->materiais->id]['media'] > 0){
			$cliente[$item->clientes->id][$item->produtos->materiais->id]['media'] = round(($cliente[$item->clientes->nome][$item->produtos->materiais->id]['media'] + $item->tons) /2,2);
			$cliente[$item->clientes->id][$item->produtos->materiais->id]['total'] += $item->tons;
			$cliente[$item->clientes->id][$item->produtos->materiais->id]['min'] = (float) min($cliente[$item->clientes->id][$item->produtos->materiais->id]['min'],$item->produtos->fluidez);
			$cliente[$item->clientes->id][$item->produtos->materiais->id]['max'] = (float) max($cliente[$item->clientes->id][$item->produtos->materiais->id]['max'],$item->produtos->fluidez);
		}else{
			$cliente[$item->clientes->id][$item->produtos->materiais->id]['media'] = $item->tons;
			$cliente[$item->clientes->id][$item->produtos->materiais->id]['total'] = $item->tons;
			$cliente[$item->clientes->id][$item->produtos->materiais->id]['min'] = min(0,$item->produtos->fluidez);
			$cliente[$item->clientes->id][$item->produtos->materiais->id]['max'] = min(0,$item->produtos->fluidez);
		}
		
	}
	
	foreach($cliente as $cliK=>$cliV){
		foreach($cliV as $matK=>$matV){
			$sql = "insert into materiais_consumidos values (null, $cliK, $matK, ".$matV['min'].", ".$matV['max'].", ".$matV['media'].", ".$matV['total'].")";
			$db->query($sql);
			
			pre($sql);
		}
	}
?>