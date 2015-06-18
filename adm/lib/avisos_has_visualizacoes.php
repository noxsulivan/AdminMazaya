<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Dados do avisos_has_visualizacoes');
	
	
	if($usuario->tipos_de_usuarios->id != 4){
		$formulario->fieldset('Registro de vizualização',false,true);
		$formulario->fieldset->filhos('avisos_has_visualizacoes_has_visualizacoes');
	}else{
		
		if(!$db->fetch("select idavisos_has_visualizacoes_has_visualizacoes from avisos_has_visualizacoes_has_visualizacoes where idcondominos = '".$usuario->condominos->id."' and idavisos_has_visualizacoes = '".$admin->id."'")){
			$_POST['idcondominos'] = $usuario->condominos->id;
			$_POST['data'] = date("d/m/Y H:i:s");
			$_POST['idavisos_has_visualizacoes'] = $admin->id;
			$db->inserir('avisos_has_visualizacoes_has_visualizacoes');
		}
	}
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('avisos_has_visualizacoes');
		$id = $inserted_id = $db->inserted_id;
	}else{
		$db->editar('avisos_has_visualizacoes',$admin->id);
		$id = $admin->id;
	
	}
	
			
break;
default:
	$admin->campos_listagem = array('Aviso' => "avisos->titulo",'Condomino' => "condominos->nome",'Enviado' => "enviado");
	
break; 
}
?>