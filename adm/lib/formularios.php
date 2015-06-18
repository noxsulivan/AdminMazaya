<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	$formulario->fieldset('Dados do Remetente');
		$formulario->fieldset->simples('Nome', 'remetente',$res["remetente"]);
		$formulario->fieldset->simples('E-mail', 'email',$res["email"]);
		$formulario->fieldset->simples('Telefone', 'fone',$res["fone"]);
		$formulario->fieldset->simples('Data','data',$res["data"]);
	
		$formulario->fieldset->separador();
		$formulario->fieldset->simples('Assunto', 'titulo',$res["titulo"]);
		$formulario->fieldset->simples('Mensagem', 'mensagem',$res["mensagem"]);
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('formularios');
	}else{
		$db->editar('formularios',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array("Data"=>"data","Remetente"=>"remetente","E-mail"=>"email");
	$sql = "select * from formularios";
	
break;
}
?>