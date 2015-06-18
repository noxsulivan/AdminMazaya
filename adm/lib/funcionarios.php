<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	
	  $formulario->fieldset('Dados do Colaborador');
			$formulario->fieldset->simples('Nome', 'nome');
			$formulario->fieldset->simples('Função', 'funcao');
			
	if($usuario->tipos_de_usuarios->id != 4){
	  	$formulario->fieldset->simples('Condômino', 'idcondominos');
	}
	  	$formulario->fieldset->simples('Permissão de acesso', 'idautorizacoes');
	
	
		$formulario->fieldset->simples('Data de nascimento', 'nascimento');
		$formulario->fieldset->simples('CPF', 'cpf');
		$formulario->fieldset->simples('RG', 'rg');
		$formulario->fieldset->simples('Razão Social', 'razao_social');
		$formulario->fieldset->simples('Nome Fantasia', 'nome_fantasia');
		$formulario->fieldset->simples('Pessoa de contato', 'nome');
		$formulario->fieldset->simples('CNPJ', 'cnpj');
		$formulario->fieldset->simples('Inscrição Estadual', 'inscricao');
		
	  	$formulario->fieldset->simples('Autorizado', 'autorizado');
	  	$formulario->fieldset->simples('Data de cadastro', 'data_cadastro');
		
	$formulario->fieldset('Histórico de entradas');
		$formulario->fieldset->filhos('funcionarios_has_entradas');
		
	  $formulario->fieldset('Dados de contato');
	  	$formulario->fieldset->simples('Telefone', 'telefone');
	  	$formulario->fieldset->simples('Celular', 'celular');
	  	$formulario->fieldset->simples('Telefone 2', 'telefone_comercial');
	  	$formulario->fieldset->localidade();
	  	$formulario->fieldset->simples('Endereço', 'endereco');
	  	$formulario->fieldset->simples('Número', 'numero');
	  	$formulario->fieldset->simples('Complemento', 'complemento');
		
	  $formulario->fieldset('Observações');
	  	$formulario->fieldset->simples('Observações', 'obs');
		
	$formulario->fieldset('Veículos',false,false);
		$formulario->fieldset->filhos('veiculos');
	  
	  $formulario->fieldset('Foto e documento');
	  	$formulario->fieldset->camera();

	
	
	
break;
case "salvar":
	$_POST["url"] = diretorio(trim($_REQUEST["nome"].' '.$_REQUEST["sobrenome"]));
	if($admin->id == ''){
		$_POST["senha"] = md5($_REQUEST["senha"]);
		$_POST['idcondominos'] = ($_REQUEST['idcondominos'] ? $_REQUEST['idcondominos'] : $usuario->condominos->id);
		$_POST['data_cadastro'] = date("d/m/Y H:i:s");
		$db->inserir('funcionarios');
		$inserted_id = $db->inserted_id;
		$db->salvar_fotos('funcionarios',$inserted_id);
		$db->filhos('funcionarios',$inserted_id);
		$_id = $db->inserted_id;
	}else{
		$db->editar('funcionarios',$admin->id);
		$db->filhos('funcionarios',$admin->id);
		$db->salvar_fotos('funcionarios',$admin->id);
	}
break;
default:

	
	if($usuario->tipos_de_usuarios->id == 4){
		$admin->campos_listagem = array('Nome' => "nome",'Função' => "funcao",'Telefone'=>'telefone','Permissão de acesso' => 'autorizacoes->autorizacao');
	}else{
		$admin->campos_listagem = array('Nome' => "nome",'Condômino' => "condominos->nome",'Relação' => "funcao", 'Permissão de acesso' => 'autorizacoes->autorizacao','Telefone'=>'telefone','Email' => "email", 'CPF' => 'cpf','RG' => 'rg', 'Data de Cadastro' => 'data_cadastro');
	}
	
	
break;
}
?>