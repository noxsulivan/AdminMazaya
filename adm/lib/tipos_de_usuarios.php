<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset("Classifica��o de usu�rio");
		$formulario->fieldset->simples('Classifica��o', 'tipo');
		$formulario->fieldset->simples('Restri��o de acesso', 'condicao');
		
		$formulario->fieldset("Tabelas acess�veis");
		$formulario->fieldset->checkBox("Tabelas acess�veis","tabelas");
		$formulario->fieldset("Menus acess�veis");
		$formulario->fieldset->checkBox("Menus acess�veis","menus");
	
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