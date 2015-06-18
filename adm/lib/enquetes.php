<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Dados da enquete');
	if($usuario->tipos_de_usuarios->id == 1){
				$formulario->fieldset->simples('Postada por', 'idusuarios');
	}
	$formulario->fieldset->simples('Pergunta', 'enquete');
	$formulario->fieldset->separador();
	$formulario->fieldset->simples('Descrição','descricao');
	
	$formulario->fieldset('Respostas');
	$formulario->fieldset->filhos('enquetes_respostas');
			
	$formulario->fieldset('Fotos - Anexe se houver imagens disponíveis');
	$formulario->fieldset->fotos();
	
	
	
break;
case "salvar":
	if($admin->id == ''){
	
		$_POST['idcadastros'] = $usuario->cadastros->id;
		$_POST['data'] = date("dd/mm/YYYY H:i:s");
		$db->inserir('enquetes');
		$_id = $db->inserted_id;
		$db->salvar_fotos('enquetes',$_id);
		$db->filhos('enquetes',$_id);
	}else{
		$_POST['data'] = date("dd/mm/YYYY H:i:s");
		$db->editar('enquetes',$admin->id);
		$db->salvar_fotos('enquetes',$admin->id);
		$db->filhos('enquetes',$admin->id);
	
	}
break;
default:
	$admin->campos_listagem = array('Data' => "data",'Pergunta' => "enquete",'Postada por' => "usuarios->nome");
	if(in_array( $usuario->tipos_de_usuarios->id, array(1,2,3))){
		$admin->listagemLink('enviarEmail','<img src="imagens/buttons/mail_send.png" align="absmiddle">Enviar consulta por e-mail','');
	}
break; 
}
?>