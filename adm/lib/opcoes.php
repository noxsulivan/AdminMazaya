<?

switch($admin->acao){
case "editar":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$admin->ini_formulario();
	
	$admin->tit_formulario('Dados da op��o');
	$admin->campo_simples('Caracter�stica geral', 'idcaracteristicas',$admin->sub_id);
	$admin->campo_simples('Op��o', 'opcao',$res["opcao"]);
	$admin->campo_fotos();
	
	
	$admin->end_formulario();
break;
case "salvar":iente.$url_titulo;

	if($admin->id == ''){
		$db->inserir('opcoes');
		$db->salvar_fotos('opcoes',$inserted_id);
	
	}else{
		$db->editar('opcoes',$admin->id);
		$db->salvar_fotos('opcoes',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array('Op��o'=>'opcao','Caracteristica'=>'caracteristicas->caracteristica');
		switch($admin->sub_tg){
			case "caracteristicas":
			$sql = "select idopcoes from opcoes where idcaracteristicas = '".$admin->sub_id."'";
			break;
			default:
				$sql = "select idopcoes from opcoes";
			break;
		}
	$admin->listagem($sql);
break;
}
?>
