<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	$formulario->fieldset('Dados do Remetente');
		$formulario->fieldset->simples('Código', 'codigo',( $admin->id ? null : strtoupper(implode("-",str_split(substr(md5(time()),0,15),5)))));
		$formulario->fieldset->simples('Valor', 'valor');
		$formulario->fieldset->simples('Percentual', 'percentual');
		$formulario->fieldset->simples('Validade', 'validade');
		$formulario->fieldset->simples('Limite mínimo', 'limite');
		$formulario->fieldset->simples('Ativo', 'status');
		$formulario->fieldset->simples('Multiplo', 'multiplo');
	
	$formulario->fieldset("Emissão");
		$formulario->fieldset->simples('Destinatário', 'idcadastros');
		$formulario->fieldset->simples('Categoria', 'idcategorias');
	
	
	$formulario->fieldset("Resgate");
		$formulario->fieldset->simples('Data', 'data_resgate');
		$formulario->fieldset->simples('Email', 'email_resgate');
	
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('cupons');
	}else{
		$db->editar('cupons',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array("Código"=>"codigo","Valor"=>"valor","Percentual"=>"percentual","Validade"=>"validade","Ativo"=>"status","Multiplo"=>"multiplo","Mínimo"=>"limite");
	$sql = "select * from cupons";
	
break;
}
?>