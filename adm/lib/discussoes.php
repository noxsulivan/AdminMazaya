<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Tema da discusssão');
	if($usuario->tipos_de_usuarios->id != 4) { $formulario->fieldset->simples('Autor','idusuarios');
	}else { $formulario->fieldset->explicacao($admin->registro->condominos->nome); }
	$formulario->fieldset->simples('Título', 'titulo');
	//$formulario->fieldset->simples('Data','data');
	
	
	$formulario->fieldset->simples('Descrição do tema','texto');
	
	$formulario->fieldset('Imagens');
	$formulario->fieldset->fotos();
	
		$formulario->fieldset('Registro de vizualização',false,true);
		$formulario->fieldset->filhos('discussoes_has_respostas');
	
	
	
	
break;
case "salvar":
	if($admin->id == ''){
	
		$usuario = new usuario();
		$_POST['idcondominos'] = $usuario->condominos->id;
		$_POST['data'] = date("d/m/Y H:i:s");
		$db->inserir('discussoes');
		$id = $inserted_id = $db->inserted_id;
		$db->salvar_fotos('discussoes',$inserted_id);
		$db->filhos('discussoes',$inserted_id);
		
	}else{
		$db->editar('discussoes',$admin->id);
		$db->salvar_fotos('discussoes',$admin->id);
		$id = $admin->id;
		$db->filhos('discussoes',$admin->id);
	
	}
	
	
	
			
break;
default:
	$admin->campos_listagem = array('Data' => "data",'Título' => "titulo",'Postado em' => "data");
break; 
}
?>