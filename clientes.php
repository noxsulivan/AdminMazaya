<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Cliente');
	
	if($usuario->tipos_de_usuarios->id == 1){
				$formulario->fieldset->simples('Representante', 'idcadastros');
	}
	
		if($db->campoExiste('nome_noiva','clientes')){
			//$formulario->fieldset->simples('Nome da noiva', 'nome_completo_noiva');
			$formulario->fieldset->simples('Nome da noiva', 'nome_noiva');
			$formulario->fieldset->simples('Sobrenome da noiva', 'sobrenome_noiva');
			$formulario->fieldset->separador();
			//$formulario->fieldset->simples('Nome do noivo', 'nome_completo_noivo');
			$formulario->fieldset->simples('Nome do noivo', 'nome_noivo');
			$formulario->fieldset->simples('Sobrenome do noivo', 'sobrenome_noivo');
			$formulario->fieldset->separador();
			$formulario->fieldset->simples('Tipo de evento', 'tipo');
			$formulario->fieldset->simples('Data do casamento', 'data_casamento');
			$formulario->fieldset->simples('Data do cha', 'data_cha');
			$formulario->fieldset->simples('Senha', 'senha');
		}else{
				$formulario->fieldset->simples('Nome', 'nome');
				$formulario->fieldset->simples('Fantasia', 'fantasia');
				$formulario->fieldset->simples('Sigla', 'sigla');
				$formulario->fieldset->simples('Plano de gerenciamento', 'idplanos');
				$formulario->fieldset->simples('Contato', 'responsavel');
				$formulario->fieldset->simples('Status', 'idstatus_clientes');
				$formulario->fieldset->simples('Data de cadastro', 'data_cadastro');
				$formulario->fieldset->separador();
				$formulario->fieldset->simples('CNPJ', 'cnpj');
				$formulario->fieldset->simples('Inscrição Estadual', 'inscricao');
				$formulario->fieldset->simples('IPI', 'ipi');
				$formulario->fieldset->simples('ICMS', 'icms');
				$formulario->fieldset->simples('Limite de crédito', 'limite');
				$formulario->fieldset->simples('E-mail para NFe', 'email_nfe');
		}
			
			$formulario->fieldset->simples('Data de evento', 'evento');
			$formulario->fieldset->simples('Data de vencimento dos boletos', 'data_vencimento');
			$formulario->fieldset->simples('Valor padrão', 'valor_cota');
			$formulario->fieldset->simples('Taxa de Planejamento', 'tpv');
		
		//if($usuario->tipos_de_usuarios->id > 1){
		if($db->campoExiste('idplanos','clientes')){
			$formulario->fieldset('Blog');
			$formulario->fieldset->simples('Tipo', 'idtipos_de_clientes');
			$formulario->fieldset->simples('Título do blog', 'titulo_blog');
			$formulario->fieldset->simples('Modelo', 'idmodelos');
			if($db->campoExiste('blog','clientes')) $formulario->fieldset->simples('Código de acesso', 'codigo_acesso');
			$formulario->fieldset->separador();
			$formulario->fieldset->simples('Login', 'login');
			$formulario->fieldset->simples('Senha', 'senha');
		}
		
		
		$formulario->fieldset('Dados de contato');
			$formulario->fieldset->simples('E-mail', 'email');
			$formulario->fieldset->simples('Telefone', 'telefone');
			$formulario->fieldset->simples('Celular', 'celular');
			$formulario->fieldset->simples('Site', 'site');
		
			$formulario->fieldset->separador();
			$formulario->fieldset->localidade();
			$formulario->fieldset->simples('Endereço', 'endereco');
			$formulario->fieldset->simples('Número', 'numero');
			$formulario->fieldset->simples('Complemento', 'complemento');
			
		if($db->tabelaExiste('contatos')){
			$formulario->fieldset('Contatos');
				$formulario->fieldset->filhos('contatos');
				
			$formulario->fieldset('Segmento');
				$formulario->fieldset->simples('Artigos produzidos pela empresa', 'produto');
				$formulario->fieldset->simples('Material consumido', 'material');
				$formulario->fieldset->simples('Volume consumido', 'consumo');
		}


		if($db->tabelaExiste('presentes')){
			$formulario->fieldset('Lista de Casamento');
				$formulario->fieldset->autocompleteTags("Presentes",'presentes','produtos');
				//$formulario->fieldset->filhos('presentes');
		}
		if($db->tabelaExiste('chas')){
			$formulario->fieldset('Lista de Chá de Panela');
				$formulario->fieldset->autocompleteTags("Presentes",'chas','produtos');
				//$formulario->fieldset->filhos('presentes');
		}
			
		if($db->campoExiste('transporte','pedidos')){
			$formulario->fieldset('Transporte');
				//$formulario->fieldset->simples('Transporte','transporte');
				$formulario->fieldset->simples('Descarga','descarga');
				$formulario->fieldset->simples('Entrada para carreta','entrada_carreta');
				$formulario->fieldset->simples('Frete','frete');
				$formulario->fieldset->simples('Horário para descarga','horario_carga');
		}
		
		$formulario->fieldset('Observações');
			//$formulario->fieldset->simples('Autorizado', 'autorizado');
			$formulario->fieldset->simples('Origem', 'origem');
			$formulario->fieldset->simples('Observações', 'obs');
			
		
		
		if($db->campoExiste('idclientes','fotos')){
			$formulario->fieldset("Convite");
			$formulario->fieldset->fotos();
		}
		
		if($db->campoExiste('idclientes','arquivos')){
			$formulario->fieldset("Anexo");
			$formulario->fieldset->arquivo();
		}
	
break;
case "salvar":

				$_POST['nome_completo_noiva'] = normaliza($_POST['nome_noiva'].' '.$_POST['sobrenome_noiva']);
				
				$_POST['nome_completo_noivo'] = normaliza($_POST['nome_noivo'].' '.$_POST['sobrenome_noivo']);
				
				
	if($admin->id == ''){
		$_POST['idcadastros'] = $usuario->cadastros->id;
		$db->inserir('clientes');
		$db->salvar_arquivos('clientes',$inserted_id);
		$inserted_id = $db->inserted_id;
		$db->filhos('clientes',$inserted_id);
	}else{
		$db->editar('clientes',$admin->id);
		$db->salvar_arquivos('clientes',$admin->id);
		$db->filhos('clientes',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array('Nome' => "nome",'E-mail' => "email",'Telefone' => "telefone");
	
	if($db->campoExiste('nome_noiva','clientes')){
			$admin->campos_listagem = array('Noiva' => "nome_completo_noiva",'Noivo' => "nome_completo_noivo",'Casamento' => "data_casamento",'Chá' => "data_cha",'Tipo' => "tipo");
	}elseif($usuario->tipos_de_usuarios->id == 1){
		$admin->campos_listagem = array('Fantasia' => "fantasia",'Razão social' => "nome",'E-mail' => "email",'Telefone' => "telefone",'Cidade' => "cidades->cidade",'UF' => "estados->nome","Status"=>"status_clientes->status","Representante"=>"cadastros->nome");
		$sql = "select * from clientes";
	}elseif($usuario->tipos_de_usuarios->id == 2){
		$admin->campos_listagem = array('Fantasia' => "fantasia",'Razão social' => "nome",'E-mail' => "email",'Telefone' => "telefone",'Cidade' => "cidades->cidade",'UF' => "estados->nome","Status"=>"status_clientes->status","Representante"=>"cadastros->nome");
		$sql = "select * from clientes where idcadastros = '".$usuario->cadastros->id."'";
	}else{
		$admin->campos_listagem = array('Nome' => "nome",'E-mail' => "email",'Telefone' => "telefone");
		$sql = "select * from clientes where idcadastros = '".$usuario->cadastros->id."'";
	}
		
	
break;
}
?>