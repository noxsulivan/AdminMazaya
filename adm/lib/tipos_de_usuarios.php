<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset("Classificaзгo de usuбrio");
		$formulario->fieldset->simples('Classificaзгo', 'tipo');
		$formulario->fieldset->simples('Restriзгo de acesso', 'condicao');
		
		$formulario->fieldset("Tabelas acessнveis");
		$formulario->fieldset->checkBox("Tabelas acessнveis","tabelas");
		$formulario->fieldset("Menus acessнveis");
		$formulario->fieldset->checkBox("Menus acessнveis","menus");
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('tipos_de_usuarios');
		$inserted_id = $db->inserted_id;
		$db->tabela_link('tipos_de_usuarios','tabelas',$inserted_id,$_POST["tabelas"]);
		$db->tabela_link('tipos_de_usuarios','menus',$inserted_id,$_POST["menus"]);
		
		
	}else{
		$db->editar('tipos_de_usuarios',$admin->id);
		$db->tabela_link('tipos_de_usuarios','tabelas',$admin->id,$_POST["tabelas"]);
		$db->tabela_link('tipos_de_usuarios','menus',$admin->id,$_POST["menus"]);
	}
break;
default:
	$admin->campos_listagem = array('Tipo' => "tipo");
	$sql = "select * from tipos_de_usuarios";
	
break;
}
?>