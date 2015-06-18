<?
switch($admin->acao){
case "editar":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	$admin->ini_formulario();
	
	$admin->tit_formulario('Dados da coordenada');
	
	$admin->campo_simples('Cidade', 'nome');
	$admin->campo_simples('Longitude', 'lon');
	$admin->campo_simples('Latidude', 'lat');
	
	$admin->end_formulario();
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('cidades_marcadas');
		$inserted_id = $db->inserted_id;
	}else{
		$admin->registro = new objetoDb($admin->tabela, $admin->id);
		$db->editar('cidades_marcadas',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array('Cidade' => "nome",'Longitude' => "lon",'Latitude' => "lat");
	
	$sql = "select * from cidades_marcadas";
	
	$admin->listagem($sql);
	
/*	if( ereg('nox',$_SERVER['HTTP_HOST'])){
		$admin->html .='<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAbJ1kffsMyFnMaZ4PDuDS0BS8bL9WxrU-k0L4G5IvCPcunoAueBQ_pv3WPnbx5bBmDv4i6N9CcHkagg" type="text/javascript"></script>';
	}else{
		$admin->html .='<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAbJ1kffsMyFnMaZ4PDuDS0BQNffg96WvmVoMHc4cAPpVyd0kTIhRicsFPxqKhy7aJtutQCsoV7cJ_TA" type="text/javascript"></script>';
	}
		$admin->html .='<div id="mapa" style=" position:relative; width: 100%; height: 500px; background:#000;"></div>';
		$admin->html .='<script>';
		$admin->html .='document.observe("dom:loaded",loadMaps);';
		$admin->html .='</script>';*/
break;
}
?>

