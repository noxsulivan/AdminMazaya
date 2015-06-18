<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Documento');
	
		
		$formulario->fieldset->simples('Enviado por', 'idusuarios');
		
		$formulario->fieldset->simples('Título', 'documento');
		$formulario->fieldset->simples('Tipo', 'idtipos_de_documentos');
		$formulario->fieldset->simples('Data', 'data');
		$formulario->fieldset->simples('Descrição', 'descricao');
		
		$formulario->fieldset("Anexo");
		$formulario->fieldset->arquivo();
		
	if($usuario->tipos_de_usuarios->id < 2 and $db->tabelaExiste('documentos_has_condominos')) {
		$formulario->fieldset('Destinatários');
		$formulario->fieldset->checkBox('Nome do Condômino','condominos');
	}
	
	
	if($usuario->tipos_de_usuarios->id != 4){
		$formulario->fieldset('Registro de vizualização');
		$formulario->fieldset->filhos('documentos_has_visualizacoes');
	}
	
//	
//	if(!$db->fetch("select iddocumentos_has_visualizacoes from documentos_has_visualizacoes where idcondominos = '".$usuario->condominos->id."' and iddocumentos = '".$admin->id."'")){
//		$_POST['idcondominos'] = $usuario->condominos->id;
//		$_POST['data'] = date("d/m/Y H:i:s");
//		$_POST['iddocumentos'] = $admin->id;
//		$_POST['via'] = "SISTEMA";
//		$db->inserir('documentos_has_visualizacoes');
//	}
		
		
break;
case "salvar":

	if($admin->id == ''){
		$_POST['idcondominos'] = $usuario->condominos->id;
		$_POST['data'] = date("d/m/Y H:i:s");
		$db->inserir('documentos');
		$inserted_id = $db->inserted_id;
		$db->salvar_arquivos('documentos',$inserted_id);
		$db->tabela_link('documentos','condominos',$inserted_id,$_POST['condominos']);
		
		
	}else{
		$db->editar('documentos',$admin->id);
		$db->salvar_arquivos('documentos',$admin->id);
		$db->tabela_link('documentos','condominos',$admin->id,$_POST['condominos']);
		
	}
break;
default:
	$admin->campos_listagem = array('Documento' => "documento",'Disnponibilizado por' => "usuarios->nome",'Data' => "data",'Tipo' => "tipos_de_documentos->tipo");
	$admin->listagemLink('enviarEmail','<img src="imagens/buttons/mail_send.png" align="absmiddle"> Enviar','');
	
	
break;
}
?>