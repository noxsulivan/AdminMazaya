<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Dados da mensagem');
			$formulario->fieldset->simples('Nome', 'nome');
			$formulario->fieldset->simples('Contratipo', 'idprodutos');
			$formulario->fieldset->simples('Material', 'idmateriais');
			$formulario->fieldset->simples('Fluidez', 'fluidez');
			$formulario->fieldset->simples('Consumo', 'consumo');
			$formulario->fieldset->simples('E-mail', 'email');
			$formulario->fieldset->simples('Telefone', 'telefone');
			$formulario->fieldset->simples('Data de contato', 'data');
			$formulario->fieldset->simples('Mensagem adicional', 'mensagem');
		
	
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('interessados');
	}else{
		$db->editar('interessados',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array('Data de contato' => "data", "Interessado" => 'nome', "Produto" => 'produto','E-mail' => "email");
	$admin->campos_listagem = array("Interessado" => 'nome', "Contratipo" => 'produtos->grade', "Material" => 'materiais->material','E-mail' => "email");
	
break;
}
?>