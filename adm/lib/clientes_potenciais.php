<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Cliente');
				$formulario->fieldset->simples('Nome', 'nome');
				$formulario->fieldset->simples('Fantasia', 'fantasia');
				$formulario->fieldset->simples('Contato', 'responsavel');
			$formulario->fieldset->simples('E-mail', 'email');
			$formulario->fieldset->simples('Telefone', 'telefone');
				$formulario->fieldset->simples('Status', 'idstatus_clientes_potenciais');
				$formulario->fieldset->simples('Data de cadastro', 'data_cadastro');
				$formulario->fieldset->simples('OBS', 'obs');
				
			
		
		
			
			$formulario->fieldset('Segmento');
				$formulario->fieldset->simples('Artigos produzidos pela empresa', 'produto');
				$formulario->fieldset->simples('Material consumido', 'material');
				$formulario->fieldset->simples('Volume consumido', 'consumo');
			
		
		$formulario->fieldset('Mailing por Grupo');
		$formulario->fieldset->checkBox('Materiais','materiais_potenciais');
		
		
		$formulario->fieldset('Dados de contato');
			$formulario->fieldset->simples('Site', 'site');
		
			$formulario->fieldset->separador();
			$formulario->fieldset->localidade();
			$formulario->fieldset->simples('Endereço', 'endereco');
			$formulario->fieldset->simples('Número', 'numero');
			$formulario->fieldset->simples('Complemento', 'complemento');
	
break;
case "salvar":

				
	if($admin->id == ''){
		$_POST['idcadastros'] = $usuario->cadastros->id;
		$db->inserir('clientes_potenciais');
		$db->salvar_arquivos('clientes_potenciais',$inserted_id);
		$inserted_id = $db->inserted_id;
		$db->filhos('clientes_potenciais',$inserted_id);
		$db->tabela_link('clientes_potenciais','materiais_potenciais',$inserted_id,$_POST["materiais_potenciais"]);
		$db->tabela_link('clientes_potenciais','produtos',$inserted_id,$_POST["produtos"]);
		
		
		
	}else{
		$db->editar('clientes_potenciais',$admin->id);
		$db->salvar_arquivos('clientes_potenciais',$admin->id);
		$db->filhos('clientes_potenciais',$admin->id);	
		$db->tabela_link('clientes_potenciais','materiais_potenciais',$admin->id,$_POST["materiais_potenciais"]);
		$db->tabela_link('clientes_potenciais','produtos',$admin->id,$_POST["produtos"]);
	}
break;
default:
	$admin->campos_listagem = array('Fantasia' => "fantasia",'Razão social' => "nome",'Telefone' => "telefone",'E-mail' => "email",'UF' => "estados->nome",'Cidade' => "cidades->cidade","Status"=>"status_clientes_potenciais->status","OBS"=>'obs');
		
	$admin->listagemLink('enviarApresentacao','<img src="imagens/buttons/mail_send.png" align="absmiddle">Enviar apresentação','');
	
break;
}
?>