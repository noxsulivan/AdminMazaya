<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Dados da mensagem');
		$formulario->fieldset->simples('Vendedor', 'idvendedores');
		$formulario->fieldset->separador();
	
		$formulario->fieldset->simples('T�tulo', 'titulo');
		$formulario->fieldset->simples('Destinat�rio', 'destinatario');
		$formulario->fieldset->simples('E-mail', 'email');
	if($usuario->id < 21)
			$formulario->fieldset->simples('Autorizado', 'autorizado');
		$formulario->fieldset->simples('Mensagem', 'mensagem');
		$formulario->fieldset->separador();
	$admin->explicacao_formulario("As informa��es no campo Observa��es n�o ser�o enviadas, s�o �teis apenas para controle interno");
		$formulario->fieldset->simples('Observa��es', 'obs');
	
	$formulario->fieldset('Anexos');
		$formulario->fieldset->fotos();
	
break;
case "salvar":
	if($admin->id == ''){
		$_POST["data_envio"] = $_POST["data_envio"] ? $_POST["data_envio"] : "00/00/0000 00:00:00";
		$db->inserir('mensagens');
		$inserted_id = $db->inserted_id;
		$db->salvar_fotos('mensagens',$inserted_id);
	}else{
		$db->editar('mensagens',$admin->id);
		$db->salvar_fotos('mensagens',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array('Destinat�rio' => "destinatario",'E-mail' => "email", "Data de envio" => 'data', "Vendedor" => 'vendedores->vendedor');
	$sql = "select idmensagens from mensagens";
	
break;
}
?>