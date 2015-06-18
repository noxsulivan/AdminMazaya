<?
if($admin->acao == 'salvar'){
	
	if(file_exists("lib/".$admin->view.".php")){
		include($include."lib/".$admin->view.".php");
	}elseif(file_exists("lib/".$admin->tg.".php")){
		include($include."lib/".$admin->tg.".php");
	}else{
		if($db->tabelaExiste($admin->tg)){
			include($include."lib/_geral.php");
		}
	}
	$admin->acao = ( $admin->acao == 'salvar' ? 'listar' : $admin->acao );
	
	$ret["status"] = 'ok';
	$ret["tg"] = $admin->tg.'@'.$admin->sub_tg.'@'.$admin->sub_id;
	$ret["acao"] = $admin->acao.'@'.$admin->acao_pag.'@'.$admin->acao_busca.'@'.$admin->acao_ordem;
	$ret["id"] = $admin->id;
	$ret["inserted_id"] = $admin->inserted_id;
	$ret["erro"] = $db->erro;
	
	$ret["funcao"] = $admin->funcao;
	
}else{
	
	
	if(file_exists("lib/".$admin->view.".php")){
		$formulario = new formulario($admin);
		include($include."lib/".$admin->view.".php");
		$ret['titulo'] = $formulario->tit();
		$ret['barra'] = $formulario->barra();
		$ret['propriedades'] = $formulario->propriedades;
		$ret['fieldsets'] = $formulario->fieldsets();
		$ret['fieldsetsProps'] = $formulario->fieldsetsProps;
	}elseif(file_exists("lib/".$admin->tg.".php")){
		$formulario = new formulario($admin);
		include($include."lib/".$admin->tg.".php");
		$ret['titulo'] = $formulario->tit();
		$ret['barra'] = $formulario->barra();
		$ret['propriedades'] = $formulario->propriedades;
		$ret['fieldsets'] = $formulario->fieldsets();
		$ret['fieldsetsProps'] = $formulario->fieldsetsProps;
	}else{
		if($db->tabelaExiste($admin->tg)){
			$formulario = new formulario($admin);
			include($include."lib/_geral.php");
			$ret['titulo'] = $formulario->tit();
			$ret['barra'] = $formulario->barra();
			$ret['propriedades'] = $formulario->propriedades;
			$ret['fieldsets'] = $formulario->fieldsets();
			$ret['fieldsetsProps'] = $formulario->fieldsetsProps;
		}
	}

}
		array_walk($ret, 'sanitizaRet');
		if($admin->extra == "pre"){
			$buffer = pre($ret,true);
		}else{
			$buffer = json_encode($ret);
			//$admin->html = pre($ret,true);
		}
	echo $buffer;
?>