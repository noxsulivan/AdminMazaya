<?

	$admin->id = ($admin->id > 0 ? $admin->id : $usuario->cadastros->id );
	$formulario->registro = new objetoDb($admin->tabela, $admin->id );
	
	
switch($admin->acao){
case "editar": case "abrir":

	
case "novo";
	
	
	
	  $formulario->fieldset('Identificação');
			$formulario->fieldset->simples('Nome completo', 'nome');
			$formulario->fieldset->simples('Sobrenome', 'sobrenome');
			$formulario->fieldset->simples('Data de nascimento', 'nascimento');
			$formulario->fieldset->simples('CPF', 'cpf');
			$formulario->fieldset->simples('RG', 'rg');
			$formulario->fieldset->simples('Razão Social', 'razao_social');
			$formulario->fieldset->simples('Nome Fantasia', 'nome_fantasia');
			$formulario->fieldset->simples('Pessoa de contato', 'contato');
			$formulario->fieldset->simples('CNPJ', 'cnpj');
			$formulario->fieldset->simples('Inscrição Estadual', 'inscricao');
			$formulario->fieldset->simples('Telefone', 'telefone');
			$formulario->fieldset->simples('Celular', 'celular');
			$formulario->fieldset->simples('Telefone 2', 'telefone_comercial');
			$formulario->fieldset->simples('Site', 'site');
			$formulario->fieldset->simples('E-mail', 'email');
		
	if($db->tabelaExiste('cadastros_has_tipos_de_cadastros')){
		$formulario->fieldset->separador();
	  $formulario->fieldset('Relacionamento');
		$formulario->fieldset->checkBox('Relacionamento', 'tipos_de_cadastros');
	}
	  $formulario->fieldset('Câmera');
	  	$formulario->fieldset->camera();
	  
	if($usuario->tipos_de_usuarios->id == 1){
	  	$formulario->fieldset->simples('Representante', 'idrepresentantes');
	}
	  
	  $formulario->fieldset('Observações');
	  	$formulario->fieldset->simples('Observações', 'obs');
		
	  
	  
	  $formulario->fieldset('Endereço');
	  	$formulario->fieldset->localidade();
	  	$formulario->fieldset->simples('Logradouro', 'endereco');
	  	$formulario->fieldset->simples('Número', 'numero');
	  	$formulario->fieldset->simples('Complemento', 'complemento');
		
	if($usuario->tipos_de_usuarios->id == 1){
	  $formulario->fieldset('Status no site');
	  	$formulario->fieldset->simples('Tipo', 'tipos_de_cadastros');
	  	$formulario->fieldset->simples('Login', 'login');
	  	$formulario->fieldset->simples('Senha', 'senha');
	  	$formulario->fieldset->simples('Autorizado', 'autorizado');
	  	$formulario->fieldset->simples('Data de cadastro', 'data_cadastro');
	}
		
		
	$formulario->fieldset('Membros da Família',false,true);
	$formulario->fieldset->filhos('dependentes');
	
	
	$formulario->fieldset('Veículos',false,true);
	$formulario->fieldset->filhos('veiculos_cadastros');

	
	
	
break;
case "inserir";
		$_POST["url"] = diretorio(trim($_REQUEST["nome"].' '.$_REQUEST["sobrenome"]));
		
		$_POST["senha"] = md5($_REQUEST["senha"]);
		$db->inserir('cadastros');
		$db->filhos('cadastros',$inserted_id);
		$_id = $db->inserted_id;
		$db->salvar_fotos('cadastros',$_id);
		if($db->tabelaExiste('cadastros_has_tipos_de_cadastros')){///////EMBREEX
			$db->tabela_link('cadastros','tipos_de_cadastros',$_id,$_POST['tipos_de_cadastros']);
		}else{////////////////////////////////////////////////////////////////////////REALPLASTIC
			$_POST['idcadastros'] = $_id;
			$_POST['idtipos_de_usuarios'] = $usuario->tipos_de_usuarios->id + 1;
			$db->inserir('usuarios');
		}
break;
case "salvar":
		$_POST["url"] = diretorio(trim($_REQUEST["nome"].' '.$_REQUEST["sobrenome"]));

		$admin->registro = new objetoDb($admin->tabela, $admin->id);
		if($admin->registro->senha != $_POST["senha"])
			$_POST["senha"] = md5($_REQUEST["senha"]);
		$db->editar('cadastros',$admin->id);
		$db->filhos('cadastros',$admin->id);
		$db->salvar_fotos('cadastros',$admin->id);
		if($db->tabelaExiste('cadastros_has_tipos_de_cadastros')){///////EMBREEX
			$db->tabela_link('cadastros','tipos_de_cadastros',$admin->id,$_POST['tipos_de_cadastros']);
		}else{////////////////////////////////////////////////////////////////////////REALPLASTIC
			$_id = $db->fetch("select idusuarios from usuarios where idcadastros = '".$admin->id."'","idusuarios");
			$db->editar('usuarios',$_id);
		}
		
		$admin->acao = 'editar';
		$admin->funcao = "formulario";
break;
default:


		if($db->campoExiste('exportado_sigep','cadastros')){
			$admin->campos_listagem = array('Nome' => "nome_completo",'Telefone'=>'telefone','Email' => "email", 'CPF' => 'cpf','RG' => 'rg', 'Cadastro' => 'data_cadastro', 'Origem' => 'origem');
		}elseif($db->campoExiste('nome_fantasia','cadastros')){
			$admin->campos_listagem = array('Nome Fantasia' => "nome_fantasia",'Razão Social' => "razao_social",'Nome' => "nome",'Telefone'=>'telefone','Email' => "email");
		}else{
				$admin->campos_listagem = array('Nome' => "nome",'Telefone'=>'telefone','Email' => "email");//,'Representante' => "representantes->nome");
		}

	
		if($db->campoExiste('exportado_sigep','cadastros')){
			$sql = "select * from cadastros";
		}else{
			if($db->tabelaExiste('cadastros_has_tipos_de_cadastros')) 	
				$admin->condicaoSql = "select * from cadastros where idcadastros in (select idcadastros from cadastros_has_tipos_de_cadastros) group by idcadastros";
			elseif($usuario->tipos_de_usuarios->id == 1){
				$sql = "select * from cadastros where idtipos_de_cadastros = 1";
			}else{
				$sql = "select * from cadastros where idtipos_de_cadastros > '".$usuario->cadastros->tipos_de_cadastros->id."' and idrepresentantes = '".$usuario->cadastros->representantes->id."'";
			}
		}
	
break;
}
?>