<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	$formulario->fieldset('Dados do visitante');
		$formulario->fieldset->simples('Nome', 'nome');
		$formulario->fieldset->simples('Rela��o/Parentesco', 'relacao');
		if($usuario->tipos_de_usuarios->id != 4){
			$formulario->fieldset->simples('Cond�mino', 'idcondominos');
		}
	//pre(time());die();
	  	$formulario->fieldset->simples('Permiss�o de acesso', 'idautorizacoes');
		$formulario->fieldset->simples('Data de nascimento', 'nascimento');
		$formulario->fieldset->simples('CPF', 'cpf');
		$formulario->fieldset->simples('RG', 'rg');
		$formulario->fieldset->simples('Raz�o Social', 'razao_social');
		$formulario->fieldset->simples('Nome Fantasia', 'nome_fantasia');
		$formulario->fieldset->simples('CNPJ', 'cnpj');
		$formulario->fieldset->simples('Inscri��o Estadual', 'inscricao');
	  	$formulario->fieldset->simples('Autorizado', 'autorizado');
	  	$formulario->fieldset->simples('Interfonar', 'interfonar');
	  	$formulario->fieldset->simples('Data de cadastro', 'data_cadastro');
		
	$formulario->fieldset('Hist�rico de visitas',false,false);
		$formulario->fieldset->filhos('visitantes_has_visitas');
		
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
	  
break;
case "salvar":
	$_POST["url"] = diretorio(trim($_REQUEST["nome"].' '.$_REQUEST["sobrenome"]));
	if($admin->id == ''){
		$_POST["senha"] = md5($_REQUEST["senha"]);
		$_POST['idcondominos'] = ($_REQUEST['idcondominos'] ? $_REQUEST['idcondominos'] : $usuario->condominos->id);
		$_POST['data_cadastro'] = date("d/m/Y H:i:s");
		$db->inserir('visitantes');
		$db->filhos('visitantes',$inserted_id);
		$_id = $db->inserted_id;
	}else{
		$db->editar('visitantes',$admin->id);
		$db->filhos('visitantes',$admin->id);
	}
break;
default:
	
	if($usuario->tipos_de_usuarios->id == 4){
		$admin->campos_listagem = array('Nome' => "nome",'Rela��o' => "relacao", 'Permiss�o de acesso' => 'autorizacoes->autorizacao','Telefone'=>'telefone','Email' => "email", 'CPF' => 'cpf','RG' => 'rg', 'Data de Cadastro' => 'data_cadastro', 'Autorizado' => 'autorizado');
	}else{
		$admin->campos_listagem = array('Nome' => "nome",'Cond�mino' => "condominos->nome",'Rela��o' => "relacao", 'Permiss�o de acesso' => 'autorizacoes->autorizacao','Telefone'=>'telefone','Email' => "email", 'CPF' => 'cpf','RG' => 'rg', 'Data de Cadastro' => 'data_cadastro');
	}

	
	
break;
}
?>