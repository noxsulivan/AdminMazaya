<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	
	$formulario->fieldset('Leitura');
	
	if($usuario->tipos_de_usuarios->id != 4){
			$formulario->fieldset->simples('Condômino','idcondominos');
	}

	$formulario->fieldset->simples('Referente a', 'data_leitura');
	$formulario->fieldset->simples('Leitura', 'leitura');
		
	
	
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('hidrometros');
		$inserted_id = $db->inserted_id;
	}else{
		$db->editar('hidrometros',$admin->id);
	}
break;
default:

	
				$admin->campos_listagem = array('Data' => "data_leitura",'Leitura' => "leitura",'Condômino' => "condominos->nome");
				$admin->ordenavel = true;
				$admin->ordenar = "idhidrometros";
				$admin->extra = "DESC";
		
break;
}



?>