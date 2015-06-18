<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	

	$formulario->fieldset('Dados do Pedido');
	
	
	if($db->campoExiste('padrinhos','pedidos')){
			
		$formulario->fieldset->autocompleteInput('Cliente','cadastros','cadastros');
		
		$formulario->fieldset->simples('Data','data');
		if($db->campoExiste('idestagios','pedidos') and $usuario->tipos_de_usuarios->id < 2)
			$formulario->fieldset->simples('Situação interna','idestagios');
			
			$formulario->fieldset->simples('Forma de pagamento','tipo_pagamento');
			$formulario->fieldset->simples('Parcelas','parcelas');
			$formulario->fieldset->simples('Peso','peso');
			$formulario->fieldset->simples('Total Geral','valor');
			$formulario->fieldset->simples('Cupom','idcupons');
		
		$formulario->fieldset->simples('Mensagem de status','status_transacao');
		
		//$formulario->fieldset->simples('Transação','transacaoid');
		
		$formulario->fieldset->simples('Atualização do status','data_transacao');
	
		$formulario->fieldset->separador();
	
		$formulario->fieldset->simples('Frete','tipo_frete');
		$formulario->fieldset->simples('Valor do frete','valor_frete');
		$formulario->fieldset->simples('Rastreamento','rastreamento');
		
		$formulario->fieldset->simples('Dados do comprador','dados_entrega');
			
		if($db->campoExiste('transporte','pedidos')){
			$formulario->fieldset('Transporte',true);
				$formulario->fieldset->simples('Transporte','transporte');
				$formulario->fieldset->simples('Descarga','descarga');
				$formulario->fieldset->simples('Frete','frete');
		}
	}else{
		$sql = "select referencia from pedidos where idcadastros = '".$usuario->cadastros->id."' order by idpedidos desc limit 1";
		$_ref = $db->fetch($sql);
		$_ref = (intval($_ref['referencia'])+1).$usuario->cadastros->sigla;
		$formulario->fieldset->simples('Referência','referencia',($admin->registro->referencia ? $admin->registro->referencia : $_ref));
		$formulario->fieldset->simples('Cliente','idclientes');
		//$formulario->fieldset->simples('IPI','clientes->ipi');
		//$formulario->fieldset->simples('ICMS','clientes->icms');
		//if($usuario->tipos_de_usuarios->id == 1)
			//$formulario->fieldset->simples('Representante','idcadastros');
			
		$formulario->fieldset->simples('Data','data');
		if($db->campoExiste('idestagios','pedidos') and $usuario->tipos_de_usuarios->id < 2)
			$formulario->fieldset->simples('Situação interna','idestagios');
		
		$formulario->fieldset->simples('Mensagem de status','status_transacao');
		
		//$formulario->fieldset->simples('Transação','transacaoid');
		$formulario->fieldset->simples('Mês de faturamento','mes_faturamento');
		$formulario->fieldset->simples('Faturamento','faturamento');		
		$formulario->fieldset->simples('# Nota Fiscal','nf');

		
		$formulario->fieldset->simples('Atualização do status','data_transacao');
	
		$formulario->fieldset->separador();
	
		$formulario->fieldset->simples('Frete','tipo_frete');
		$formulario->fieldset->simples('Valor do frete','valor_frete');
		$formulario->fieldset->simples('Rastreamento','rastreamento');
	
	
		if($db->tabelaExiste('itens')){
			$formulario->fieldset('Produtos');
			$formulario->fieldset->filhos('itens');
			
			$formulario->fieldset->simples('Forma de pagamento','tipo_pagamento');
			$formulario->fieldset->simples('Parcelas','parcelas');
			$formulario->fieldset->simples('Peso','peso');
			$formulario->fieldset->simples('Valor','valor');
			$formulario->fieldset->simples('IPI','ipi');
			$formulario->fieldset->simples('Total Geral','total');
		}
			
		if($db->campoExiste('transporte','pedidos')){
			$formulario->fieldset('Transporte');
//				$formulario->fieldset->simples('Transporte','transporte');
//				$formulario->fieldset->simples('Descarga','descarga');
//				$formulario->fieldset->simples('Frete','frete');
//				$formulario->fieldset->simples('Horário para descarga','horario_descarga');
				$formulario->fieldset->simples('Nota fiscal triangular','nota_fiscal_triangular');
		}
		
	}
	$formulario->fieldset('Detalhes do pedido');
		$formulario->fieldset->simples('Descrição','descricao');
		$formulario->fieldset->simples('Dados seriais','serialized');
	
	if($db->campoExiste('padrinhos','pedidos')){
		$formulario->fieldset('Mensagem anexa',true);
			$formulario->fieldset->simples('Padrinhos','padrinhos');
			$formulario->fieldset->simples('Dedicatória','dedicatoria');
	}
	
	if($db->campoExiste('retorno_transacao','pedidos')){
		$formulario->fieldset('Retorno da Transação',true);
			$formulario->fieldset->simples('Retorno da Transação','retorno_transacao');
	}
	
	/*
	
	*/
	

	
	
break;
case "salvar":
	if($admin->id == ''){
		$_POST['idcadastros'] = $usuario->cadastros->id;
		$db->inserir('pedidos');
		$inserted_id = $db->inserted_id;
		$db->filhos('pedidos',$inserted_id);
	
	}else{
		$db->editar('pedidos',$admin->id);
		$db->filhos('pedidos',$admin->id);
	}
break;
default:
	//$admin->campos_listagem = array('Código' => "referencia",'Cliente' => "clientes->nome",'Representante' => "cadastros->nome",'Valor' => "valor",'Peso' => 'peso','Situação'=>'estagios->estagio','Frete'=>'frete','MesFaturamento'=>'faturamento','Mes'=>'meses->mes','Ano'=>'anos->ano');
	$admin->campos_listagem = array('Código' => "referencia",'Cliente' => "clientes->fantasia",'Representante' => "cadastros->nome",'Valor' => "valor",'Peso' => 'peso','Situação'=>'estagios->estagio');
	//$admin->campos_listagem = array('Pedido' => "idpedidos",'Cliente' => "cadastros->nome_completo",'Valor' => "valor",'Frete' => "valor_frete",'Data' => "data",'Tipo de pagamento' => "tipo_pagamento",'Situação'=>'status_transacao','Estágio'=>'estagios->estagio','Rastreamento'=>'rastreamento','Origem'=>'origem');
	$admin->ordenar = "data";
	$admin->extra = "DESC";
	$admin->listagemLink('enviarEmail','<img src="imagens/icons/16x16/yellow_mail_send.png" align="absmiddle">Email','');
	$admin->listagemLink('imprimirPDF','<img src="imagens/icons/16x16/pdf_file.png" align="absmiddle">PDF','');
break;
}
/*

				$header['classe'] = 'f_link';
				$header['campo'] = 'pdf';
				$header['visor'] = '<img src="imagens/icons/16x16/pdf_file.png" align="absmiddle">.PDiF';
				$header['funcao'] = 'imprimirPDF';
				$lista['headers'][] = $header;

CREATE VIEW

`rel_itens` AS select `itens`.`iditens` AS `idrel_itens`,
`itens`.`idpedidos` AS `idpedidos`,
`itens`.`idprodutos` AS `idprodutos`,
`clientes`.`idclientes` AS `idclientes`,
`itens`.`tons` AS `tons`,
`itens`.`preco_ex` AS `preco_ex`,
`itens`.`unitario` AS `unitario`,
`pedidos`.`faturamento` AS `faturamento`,
`itens`.`data_entrega` AS `data_entrega`,
`itens`.`prazo_condicoes` AS `prazo_condicoes`,
`itens`.`subtotal` AS `subtotal`,
replace(replace(replace(format(((`itens`.`subtotal` * `cadastros`.`comissao`) / 100),2),'.','|'),',','.'),'|',',') AS `valor_comissao`
from itens,pedidos,clientes,cadastros
where
((`itens`.`idpedidos` = `pedidos`.`idpedidos`) and
  (`pedidos`.`idclientes` = `clientes`.`idclientes`) and
  (`pedidos`.`idcadastros` = `cadastros`.`idcadastros`));




*/
?>
