<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	
	$formulario->fieldset('Boleto');
	
	if($db->tabelaExiste('cadastros'))	$formulario->fieldset->simples('Associado','idcadastros');
	if(!$usuario->clientes->id){
			$formulario->fieldset->simples('Cliente','idclientes',$admin->sub_tg);
	}
	if($usuario->tipos_de_usuarios->id != 4){
			$formulario->fieldset->simples('Condômino','idcondominos');
	}

	$formulario->fieldset->simples('Referente a', 'referencia');
	$formulario->fieldset->simples('Grupo de Cobrança', 'idcobrancas');
		
		$formulario->fieldset->simples('Número', 'nosso_numero',$admin->registro->id);
		$formulario->fieldset->simples('Data do documento', 'data_documento',date('d/m/Y H:i:s'));
		
		$formulario->fieldset->simples('Vencimento', 'vencimento');
	
		$formulario->fieldset->simples('Valor do documento', 'valor');
		$formulario->fieldset->simples('Valor creditado', 'pago');
		
		$formulario->fieldset->simples('Situação', 'idstatus_de_boletos');
		$formulario->fieldset->simples('Data do pagamento', 'data_pagamento');
	
	
	$formulario->fieldset('Atualização');
		$formulario->fieldset->simples('Vencimento extendido', 'vencimento_atualizado');
		$formulario->fieldset->simples('Valor autalizado', 'valor_atualizado');
	
	
	$formulario->fieldset('Outros');
		$formulario->fieldset->simples('Internet', 'internet');
		$formulario->fieldset->simples('Interfone', 'interfone');
		$formulario->fieldset->simples('Taxa de manutenção', 'taxa_manutencao');
		$formulario->fieldset->simples('Fundo de Reserva', 'fundo_reserva');
		$formulario->fieldset->simples('Roçagem', 'rocagem');
		$formulario->fieldset->simples('Chamada de capital', 'chamada');
		$formulario->fieldset->simples('Benfeitorias', 'benfeitoria');
		$formulario->fieldset->simples('Salão de festas', 'salao');
		$formulario->fieldset->simples('Outros', 'outros');
		
		
		
	
	if($admin->registro->situacao == 1){
			//$formulario->fieldset->simples('Nosso número', 'idboletos');
			//$formulario->fieldset->simples('Data do documento', 'data_documento');
			//$formulario->fieldset->simples('Data de processamento', 'data_processamento');
	}
	
	
	if($db->campoExiste('nome','boletos')){
	
	$formulario->fieldset('Dados do comprador');
			$formulario->fieldset->simples('Nome', 'nome');
			$formulario->fieldset->simples('CPF', 'cpf');
			$formulario->fieldset->simples('Endereço', 'endereco');
			$formulario->fieldset->simples('Nº', 'numero');
			$formulario->fieldset->simples('Complemento', 'complemento');
			$formulario->fieldset->simples('Telefone', 'telefone');
			$formulario->fieldset->simples('Telefone2', 'telefone2');
			$formulario->fieldset->simples('FAX', 'fax');
			$formulario->fieldset->simples('E-mail', 'email');
	}
	
	
break;
case "salvar":
	if($admin->id == ''){
		if(!$_POST['nosso_numero'])
			$_POST['nosso_numero'] = str_pad($_REQUEST['idclientes'].$_REQUEST['idcondominos'], 4, "0", STR_PAD_LEFT).str_pad($db->tabelas['boletos']['Auto_increment'], 4, "0", STR_PAD_LEFT);
		$_POST['numero_documento'] =	substr(str_replace("-","",in_data($_REQUEST['data_vencimento'])), 4, 4).
										str_pad($_REQUEST['idclientes'], 2, "0", STR_PAD_LEFT).str_pad($db->tabelas['boletos']['Auto_increment'], 4, "0", STR_PAD_LEFT);
		$db->inserir('boletos');
		$inserted_id = $db->inserted_id;
	}else{
		$db->editar('boletos',$admin->id);
	}
break;
default:

		$admin->listagemLink('popup','<img src="imagens/buttons/print.png" width="16" height="16" align="absmiddle"> Imprimir',$admin->localhost.'_Boleto/');
		$admin->listagemLink('enviarEmail','<img src="imagens/buttons/mail_send.png" align="absmiddle"> Enviar por email','');
		$admin->listagemLink('atualizarBoleto','<img src="imagens/buttons/refresh.png" width="16" height="16" align="absmiddle">Atualizar data','');
	
				$admin->campos_listagem = array('Vencimento' => "vencimento",'Valor' => "valor",'Valor Atual.' => "valor_atualizado",'Condômino' => "condominos->nome",'Grupo' => "cobrancas->referencia", 'Número' => "nosso_numero",'Situação'=>'status_de_boletos->status');
				
				$admin->ordenar = "vencimento";
				$admin->extra = "DESC";
		
break;
}



?>