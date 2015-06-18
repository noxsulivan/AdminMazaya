<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	$formulario->fieldset('Dados do cadastro');
	if($db->campoExiste('idconstrutoras','contatos')) 	$formulario->fieldset->simples('Construtora', 'idconstrutoras');

			$formulario->fieldset->simples('Cliente', 'idclientes');
			$formulario->fieldset->simples('Nome', 'nome');
			$formulario->fieldset->simples('Departamento', 'departamento');
			$formulario->fieldset->simples('E-mail', 'email');
			$formulario->fieldset->simples('Telefone', 'telefone');
			$formulario->fieldset->simples('Celular', 'celular');
			$formulario->fieldset->simples('Nextel', 'nextel_id');
			$formulario->fieldset->simples('Aniversário', 'aniversario');
	//	$formulario->fieldset->fotos();
	
	
break;
case "salvar":
	if($admin->id == ''){
		$_POST['idcadastros'] = $usuario->cadastros->id;
		$db->inserir('contatos');
		$inserted_id = $db->inserted_id;
		$db->filhos('contatos',$inserted_id);
	}else{
		$db->editar('contatos',$admin->id);
		$db->filhos('contatos',$admin->id);
		
	}
break;
default:
	$admin->campos_listagem = array('Empresa' => "clientes->fantasia",'Nome'=>'nome','Departamento'=>'departamentos->departamento','Email' => "email",'E-mail' => "email",'Telefone' => "telefone",'Nextel' => "nextel_id");
	
	$sql = "select * from contatos";
	
	
break;
}
?>