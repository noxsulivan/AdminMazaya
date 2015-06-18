<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	
	  $formulario->fieldset('Dados do Entregador');
			$formulario->fieldset->simples('Nome', 'nome');
			$formulario->fieldset->simples('Empresa', 'empresa');
	  	$formulario->fieldset->simples('Telefone', 'telefone');
			
	
	
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
		
		
	  $formulario->fieldset('Observações');
	  	$formulario->fieldset->simples('Observações', 'obs');
		
	//$formulario->fieldset('Histórico de entradas');
		//$formulario->fieldset->filhos('entregas_has_entradas');
		
		
	//$formulario->fieldset('Veículos',false,false);
		//$formulario->fieldset->filhos('veiculos_entregas');
	  
	  $formulario->fieldset('Foto e documento');
	  	$formulario->fieldset->camera();

	
	
	
break;
case "salvar":
	$_POST["url"] = diretorio(trim($_REQUEST["nome"].' '.$_REQUEST["sobrenome"]));
	if($admin->id == ''){
		$_POST["senha"] = md5($_REQUEST["senha"]);
		$_POST['idcondominos'] = ($_REQUEST['idcondominos'] ? $_REQUEST['idcondominos'] : $usuario->condominos->id);
		$_POST['data_cadastro'] = date("d/m/Y H:i:s");
		$db->inserir('entregas');
		$inserted_id = $db->inserted_id;
		$db->salvar_fotos('entregas',$inserted_id);
		$db->filhos('entregas',$inserted_id);
		$_id = $db->inserted_id;
	}else{
		$db->editar('entregas',$admin->id);
		$db->filhos('entregas',$admin->id);
		$db->salvar_fotos('entregas',$admin->id);
	}
break;
default:

		$admin->campos_listagem = array('Nome' => "nome",'Empresa' => "empresa",'Telefone'=>'telefone', 'CPF' => 'cpf','RG' => 'rg', 'Data de Cadastro' => 'data_cadastro', 'Autorizado' => 'autorizado');
	
	
break;
}
?>