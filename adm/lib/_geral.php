<?
$campos = $db->campos_da_tabela($admin->tg);

switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	
	$formulario->fieldset('Campos do banco de dados');
	foreach($campos as $k=>$v){
		if($v["Key"] != 'PRI' and ($k !== 'ordem' and $k != 'url'))
				$formulario->fieldset->simples(normaliza(preg_replace("/^(id)/i","",$k)),$k);
	}
	
	if($db->campoExiste('id'.$admin->tg,'fotos')){
		$formulario->fieldset('Imagens');
			$formulario->fieldset->fotos();
	}
	if($db->campoExiste('id'.$admin->tg,'arquivos')){
		$formulario->fieldset('Anexos');
			$formulario->fieldset->arquivo();
	}
	
	
break;
case "salvar":

	if($admin->id == ''){
		$db->inserir($admin->tg);
		$db->salvar_fotos($admin->tg,$inserted_id);
		$db->salvar_arquivos($admin->tg,$inserted_id);
	
	}else{
		$db->editar($admin->tg,$admin->id);
		$db->salvar_fotos($admin->tg,$admin->id);
		$db->salvar_arquivos($admin->tg,$admin->id);
	}
break;
default:
	$admin->campos_listagem = array();
	reset($campos);
	foreach($campos as $k=>$v){
		if($v["Key"] == 'PRI' or $k == 'id'.$admin->tg){
		}elseif($v["Key"] == 'MUL'){
			$_tab = preg_replace("/_2/i","",preg_replace("/^(id)/i","",$k));
			$_pri = $db->primeiroCampo($_tab);
			$_temp[normaliza($_tab)] = $_tab.'->'.$_pri;
		}elseif(preg_match("/^id/i",$k)){
			$_tab = preg_replace("/_2/i","",preg_replace("/^(id)/i","",$k));
			$_pri = $db->primeiroCampo($_tab);
			$_temp[normaliza($_tab)] = $_tab.'->'.$_pri;
		}else{
			if($k != 'ordem')
				$_temp[normaliza($k)] = $k;
		}
	}
	//pre($_temp);die();
	$admin->campos_listagem = $_temp;
	$sql = "select id".$admin->tg." from ".$admin->tg;
	
break;
}
?>
