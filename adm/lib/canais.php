<?
//pre($db);
if(!$db->campoExiste('locked','canais')){
	$db->query("ALTER TABLE `canais` ADD `locked` ENUM( 'nao', 'sim' ) NULL DEFAULT 'nao' AFTER `status` ;");
}

switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
		
	$formulario->fieldset('Dados da pбgina');
	
		$formulario->fieldset->simples('Tнtulo', 'canal');
		$formulario->fieldset->simples('Sub-pбgina de','idcanais_2');
	if($db->tabelaExiste('idiomas'))					$formulario->fieldset->simples('Idioma','ididiomas');
	if($db->tabelaExiste('tipos_de_restricoes'))					$formulario->fieldset->simples('Restriзгo de acesso','idtipos_de_restricoes');
	
	if($db->tabelaExiste('icones_de_canais'))					$formulario->fieldset->separador();
	if($db->tabelaExiste('icones_de_canais'))				$admin->icone_canal();
	
	if($db->campoExiste('chamada','canais'))					$formulario->fieldset->simples('Chamada','chamada');
	if($db->campoExiste('flash','canais'))						$formulario->fieldset->simples('Flash','flash');
	if($db->campoExiste('video','canais'))						$formulario->fieldset->simples('Video - Youtube','video');
		$formulario->fieldset->simples('Texto','texto');
	
	$formulario->fieldset('Imagens');
		$formulario->fieldset->fotos();
	
	if($db->campoExiste('idcanais','arquivos')){
		$formulario->fieldset("Arquivo - Video (.flv ou .mp4), Flash (.swf)");
			$formulario->fieldset->arquivo();
	}
		
	$formulario->fieldset('Atributos');
		$formulario->fieldset->tipo("canais");
		$formulario->fieldset->simples('Ativo', 'status');
	
	//$formulario->fieldset('Posicionamento');
	//	$formulario->fieldset->tabela_select('Menu','idposicoes_do_menu','idposicoes_do_menu','posicao','select * from posicoes_do_menu',$res["idposicoes_do_menu"]);
	//	$formulario->fieldset->simples('Link', 'link',$link,"Preencha este campo caso o destino seja em outro site.");
	//	$formulario->fieldset->tabela_select('Depois de','ordem','ordem','canal','select idcanais, canal, ordem from canais order by ordem',$res["ordem"]-1);
	
	
	
	
	
break;
case "salvar":
	if($_POST['idcanais'] == 1) $_POST['idcanais_2'] = '0';
	
	if($admin->id == ''){
	
		$db->inserir('canais');
		$_id = $db->inserted_id;
		$db->inserir_atributos('canais',$_id);
		$db->salvar_fotos('canais',$_id);
		$db->salvar_arquivos('canais',$inserted_id);
	
	}else{
		$db->editar('canais',$admin->id);
		//pre($_POST);
		$db->salvar_fotos('canais',$admin->id);
		$db->inserir_atributos('canais',$admin->id);
		$db->salvar_arquivos('canais',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array('Pбgina' => "canal",'Canal Anterior' => "canais->canal","Tipo" => 'tipos_de_canais->tipo',"Ativo" => 'status',"URL" => 'url',"Idioma" => 'idiomas->idioma');
	
	if($admin->sub_tg){
			$admin->titulo = " &raquo; Paginas em : ".$admin->sub_tg;
			$sql = "select idcanais	from canais where locked = 'nao' and ididiomas = '".$admin->sub_tg."'";
	}else{
			$sql = "select idcanais	from canais where locked = 'nao'";
	}
	
break;
}
?>