<?

	$admin->id = ($admin->id > 0 ? $admin->id : $usuario->cadastros->id );
	$formulario->registro = new objetoDb($admin->tabela, $admin->id );
	
	
switch($admin->acao){
case "editar": case "abrir":

	
case "novo";
	
	  $formulario->fieldset('Status no site');
	  	$formulario->fieldset->simples('Login', 'login');
	  	$formulario->fieldset->simples('Senha', 'senha');

	
	
	
break;
case "salvar":
		$_POST["url"] = diretorio(trim($_REQUEST["nome"].' '.$_REQUEST["sobrenome"]));

		$admin->registro = new objetoDb($admin->tabela, $admin->id);
		if($admin->registro->senha != $_POST["senha"])
			$_POST["senha"] = md5($_REQUEST["senha"]);
		$db->editar('cadastros',$admin->id);
		$db->editar('usuarios',$usuario->cadastros->id);
		
		$admin->acao = 'editar';
		$admin->funcao = "formulario";
break;
default:


	
break;
}
?>