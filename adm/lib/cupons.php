<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	$formulario->fieldset('Dados do Remetente');
		$formulario->fieldset->simples('C�digo', 'codigo',( $admin->id ? null : strtoupper(implode("-",str_split(substr(md5(time()),0,15),5)))));
		$formulario->fieldset->simples('Valor', 'valor');
		$formulario->fieldset->simples('Percentual', 'percentual');
		$formulario->fieldset->simples('Validade', 'validade');
		$formulario->fieldset->simples('Limite m�nimo', 'limite');
		$formulario->fieldset->simples('Ativo', 'status');
		$formulario->fieldset->simples('Multiplo', 'multiplo');
	
	$formulario->fieldset("Emiss�o");
		$formulario->fieldset->simples('Destinat�rio', 'idcadastros');
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
	$admin->campos_listagem = array("C�digo"=>"codigo","Valor"=>"valor","Percentual"=>"percentual","Validade"=>"validade","Ativo"=>"status","Multiplo"=>"multiplo","M�nimo"=>"limite");
	$sql = "select * from cupons";
	
break;
}
?>