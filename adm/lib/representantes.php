<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Empresa Representante');
	
				$formulario->fieldset->simples('Razao Social', 'nome');
				$formulario->fieldset->simples('Pessoa de contato', 'idcadastros');
			$formulario->fieldset->separador();
				$formulario->fieldset->simples('CNPJ', 'cnpj');
				$formulario->fieldset->simples('Inscrição Estadual', 'inscricao');
			$formulario->fieldset->simples('Observações', 'obs');
			
		
		
		$formulario->fieldset('Dados de contato');
			$formulario->fieldset->simples('E-mail', 'email');
			$formulario->fieldset->simples('Telefone', 'telefone');
			$formulario->fieldset->simples('Celular', 'celular');
			$formulario->fieldset->simples('Site', 'site');
		
			$formulario->fieldset->separador();
			$formulario->fieldset->CEP();
			$formulario->fieldset->cidadeEstado();
			$formulario->fieldset->simples('Endereço', 'endereco');
			$formulario->fieldset->simples('Número', 'numero');
			$formulario->fieldset->simples('Complemento', 'complemento');
		
		
		$formulario->fieldset('Status no site');
			$formulario->fieldset->simples('Login', 'login');
			$formulario->fieldset->simples('Senha', 'senha');
			$formulario->fieldset->simples('Autorizado', 'autorizado');
			$formulario->fieldset->simples('Data de cadastro', 'data_cadastro');
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('representantes');
		$db->tabela_link('representantes','estados',$db->inserted_id,$_POST["tabela_link"]);
	}else{
		$db->editar('representantes',$admin->id);
		$db->tabela_link('representantes','estados',$admin->id,$_POST["tabela_link"]);
	}
break;
default:
	$admin->campos_listagem = array('Nome' => "nome",'E-mail' => "email",'Telefone' => "telefone");
	$sql = "select * from representantes";
	
break;
}
?>