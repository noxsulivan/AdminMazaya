<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	
	  $formulario->fieldset('Dados do Colaborador');
			$formulario->fieldset->simples('Nome', 'nome');
			$formulario->fieldset->simples('Fun��o', 'funcao');
			
	if($usuario->tipos_de_usuarios->id != 4){
	  	$formulario->fieldset->simples('Cond�mino', 'idcondominos');
	}
	  	$formulario->fieldset->simples('Permiss�o de acesso', 'idautorizacoes');
	
	
		$formulario->fieldset->simples('Data de nascimento', 'nascimento');
		$formulario->fieldset->simples('CPF', 'cpf');
		$formulario->fieldset->simples('RG', 'rg');
		$formulario->fieldset->simples('Raz�o Social', 'razao_social');
		$formulario->fieldset->simples('Nome Fantasia', 'nome_fantasia');
		$formulario->fieldset->simples('Pessoa de contato', 'nome');
		$formulario->fieldset->simples('CNPJ', 'cnpj');
		$formulario->fieldset->simples('Inscri��o Estadual', 'inscricao');
		
	  	$formulario->fieldset->simples('Autorizado', 'autorizado');
	  	$formulario->fieldset->simples('Data de cadastro', 'data_cadastro');
		
	$formulario->fieldset('Hist�rico de entradas');
		$formulario->fieldset->filhos('funcionarios_has_entradas');
		
	  $formulario->fieldset('Dados de contato');
	  	$formulario->fieldset->simples('Telefone', 'telefone');
	  	$formulario->fieldset->simples('Celular', 'celular');
	  	$formulario->fieldset->simples('Telefone 2', 'telefone_comercial');
	  	$formulario->fieldset->localidade();
	  	$formulario->fieldset->simples('Endere�o', 'endereco');
	  	$formulario->fieldset->simples('N�mero', 'numero');
	  	$formulario->fieldset->simples('Complemento', 'complemento');
		
	  $formulario->fieldset('Observa��es');
	  	$formulario->fieldset->simples('Observa��es', 'obs');
		
	$formulario->fieldset('Ve�culos',false,false);
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
		$admin->campos_listagem = array('Nome' => "nome",'Fun��o' => "funcao",'Telefone'=>'telefone','Permiss�o de acesso' => 'autorizacoes->autorizacao');
	}else{
		$admin->campos_listagem = array('Nome' => "nome",'Cond�mino' => "condominos->nome",'Rela��o' => "funcao", 'Permiss�o de acesso' => 'autorizacoes->autorizacao','Telefone'=>'telefone','Email' => "email", 'CPF' => 'cpf','RG' => 'rg', 'Data de Cadastro' => 'data_cadastro');
	}
	
	
break;
}
?>