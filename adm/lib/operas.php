<?
switch($admin->acao){
case "editar":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	$admin->ini_formulario();
	$admin->tit_formulario('Opera');
	
	if($db->tabelaExiste('tags'))	$admin->campo_tags();
	
	$admin->campo_simples('cd', 'cd',$res["cd"]);
	$admin->campo_simples('opera', 'opera',$res["opera"]);
	$admin->campo_simples('artista', 'artista',$res["artista"]);
	$admin->campo_simples('compositor', 'compositor',$res["compositor"]);
	$admin->campo_simples('condutor', 'condutor',$res["condutor"]);
	$admin->campo_simples('ano', 'ano',$res["ano"]);
	$admin->campo_simples('duracao', 'duracao',$res["duracao"]);
	$admin->campo_simples('Texto', 'texto',$res["texto"]);
	$admin->campo_fotos();
	
	$admin->end_formulario();
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('operas');
		$_id = $db->inserted_id;
		$db->salvar_fotos('operas',$_id);
		$db->inserir_tags('operas',$_id);
	}else{
		$admin->registro = new objetoDb($admin->tabela, $admin->id);
		$db->editar('operas',$admin->id);
		$db->salvar_fotos('operas',$admin->id);
		$db->inserir_tags('operas',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array('Opera' => "opera",'Artista' => "artista",'Compositor' => "compositor",'Condutor' => "condutor",'Ano' => "ano",'CD' => "cd");
	$sql = "select idoperas	from operas";
	$admin->listagem($sql);
break;
}
?>