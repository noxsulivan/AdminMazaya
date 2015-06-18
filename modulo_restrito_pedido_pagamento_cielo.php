<? if($cadastro->conectado()){?>
<?

define('VERSAO', "1.1.0");

if(preg_match("/sulisva/i",$cadastro->nome)){
			// CONSTANTES
			define("ENDERECO_BASE", "https://qasecommerce.cielo.com.br");
			define("ENDERECO", ENDERECO_BASE."/servicos/ecommwsec.do");
						
			define("CIELO", "1006993069");
			define("CIELO_CHAVE", "25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3");
			
			echo "<h1>EXECUTANDO TESTE</h1>";


}else{
			// CONSTANTES
			define("ENDERECO_BASE", "https://ecommerce.cielo.com.br");
			define("ENDERECO", ENDERECO_BASE."/servicos/ecommwsec.do");
						
			define("CIELO", "1027957533");
			define("CIELO_CHAVE", "77451983034fecb456d0e27ee12909ea373a4227238e816534ec7caef6e86f2f");


}



foreach($_POST as $k=>$v){
	$_POST[$k] = htmlentities($v);
}

	$_POST = array_merge($_POST,$_POST);
	
	if(is_null($_POST['cpfcgc'])) $_POST['cpfcgc'] = $cadastro->cpf;
    if(is_null($_POST['endereco'])) $_POST['endereco'] = $cadastro->endereco;
    if(is_null($_POST['numero'])) $_POST['numero'] = $cadastro->numero;
    if(is_null($_POST['complemento'])) $_POST['complemento'] = $cadastro->complemento;
    if(is_null($_POST['bairro'])) $_POST['bairro'] = $cadastro->bairro;
    if(is_null($_POST['cep'])) $_POST['cep'] = $cadastro->cep;
    if(is_null($_POST['cidade'])) $_POST['cidade'] = $cadastro->cidades->id;
    if(is_null($_POST['estado'])) $_POST['estado'] = $cadastro->estados->id;
    if(is_null($_POST['parcelas'])) $_POST['parcelas'] = 1;
	
	
	$desconto = 0;
	
//	$_descontos = array();
//	foreach($pedido->itens as $item){
//		if(count($item->produtos->combos)){
//			foreach($item->produtos->combos as $c){
//				//pre($c);
//				$_descontos[] = $c->combo->id;
//			}
//		}
//	}
	
	$modulo = "Cielo";
	$forma = "Cartão de crédito ".strtoupper($_POST['codigoBandeira']);
	$bandeira = strtoupper($_POST['codigoBandeira']);
	$action = $pagina->localhost."Processar-Pedido/pages/novoPedidoAguarde.php";
	
	
	switch($_POST['formaPagamento']){
		case "boleto":
			$parcelas =  "À vista";
			$forma = "Boleto";
		break;
		case "A":
			$parcelas =  "Débito à vista";
			$forma = "Cartão de crédito ".strtoupper($_POST['codigoBandeira']);
		break;
		case "1":
			$parcelas =  "1 parcela";
			$forma = "Cartão de crédito ".strtoupper($_POST['codigoBandeira']);
		break;
		default:
			$parcelas =  $_POST['formaPagamento'] . " parcelas";
			$forma = "Cartão de crédito ".strtoupper($_POST['codigoBandeira']);
	}
	$_POST['parcelas'] = $_POST['formaPagamento'];
	$_POST['tipo_pagamento'] = trim($forma.' '.$parcelas);
			
			
			
			
			
	$descricao .= "<blockquote>";
	foreach($pedido->itens as $item){
		
		$desconto_vista = $pagina->configs['desconto_boleto'];
//		if(count($item->produtos->categorias)){
//			foreach($item->produtos->categorias as $c){
//				$desconto_vista = min($desconto_vista,$c->desconto_vista);
//			}
//		}
		
		
							
							
		if((float) $item->produtos->preco_venda > 0){
			
			$precoPro = //( in_array($item->produtos->id,$_descontos) ?
								   //(float)$item->produtos->preco_combo :
														 ((float)$item->produtos->preco_promocional > 0 ? (float)$item->produtos->preco_promocional : (float)$item->produtos->preco_venda);
			$precoItem = $item->quantidade * //( in_array($item->produtos->id,$_descontos) ?
													//(float)$item->produtos->preco_combo :
													 ((float)$item->produtos->preco_promocional > 0 ? (float)$item->produtos->preco_promocional : (float)$item->produtos->preco_venda);
		
			$descricao .= "Produto: <strong>".$item->produtos->codigo.' '.$item->produtos->produto.'</strong>';
			
			if($_SESSION['variacoes'][$item->produtos->id]){
				$variacao = new objetoDb("variacoes",$_SESSION['variacoes'][$item->produtos->id]);
				$descricao .= '<br />Variação: <strong>'.$variacao->opcoes->opcao.'</strong>';
			}
			$descricao .= '<br />Quantidade: <strong>'.$item->quantidade.'</strong>';
			$descricao .= ' Disponibilidade: '.($item->produtos->prazo_entrega ? "<strong>".$item->produtos->prazo_entrega." dias</strong>" : "<strong>Em estoque</strong>");
			$descricao .= '<br />Unitario: <strong>R$'.number_format($precoPro,2,',','.').'</strong>';
				
			if((float)$item->produtos->desconto > 1  && $forma != 'Boleto'){
				$subtotal = $precoItem;
			}elseif((float)$item->produtos->desconto && $forma == 'Boleto'){
				$descricao .= '<br />'.$item->produtos->desconto.'% de desconto = <strong>R$'.number_format($precoItem * (1 - ($item->produtos->desconto/100)),2,',','.').'</strong>';
				$subtotal = $precoItem * (1 - ($item->produtos->desconto/100));
			//}elseif($desconto_vista && $forma == 'Boleto'){
			}elseif($desconto_vista && $forma == 'Boleto'){
				$descricao .= '<br />'.$desconto_vista.'% de desconto = <strong>R$'.number_format($precoItem * (1 - ($desconto_vista/100)),2,',','.').'</strong>';
				$subtotal = $precoItem * (1 - ($desconto_vista/100));
			}else{
				$subtotal = $precoItem;
			}
		
			$descricao .= '<br />Subtotal do ítem: <strong>R$'.number_format($subtotal,2,',','.').'</strong><br>';
			$subtotal_produtos += $subtotal;
		}
			
		
		//$descricao .= ((in_array($item->produtos->id,$_descontos)) ? ' <em>(DESCONTO COMBO)</em>' : '' );
		
		if($item->presente)
			$descricao .= '<br /> Embrulhado para presente';
			
		
		if($item->produtos->brindes->id){
			$brinde = new objetoDb("produtos",$item->produtos->brindes->id);
			$descricao .= "<p>Brinde: <strong>".$brinde->codigo.' '.$brinde->produto.'</strong></p>';
		}
						
	}
	
	$descricao .= "</blockquote>";
	
	
	$_POST['descricao'] = $descricao;
	
	$_POST['cidade'] = $db->fetch('select cidade from cidades where idcidades = "'.$_POST['cidade'].'"','cidade'); 
	$_POST['estado'] = $db->fetch('select nome from estados where idestados = "'.$_POST['estado'].'"','nome'); 
	
	
	if($pedido->idcupons > 1){
		$valor_pedido = $subtotal_produtos + $_POST['frete'.$_POST['tipo_frete']] - $pedido->cupons->valor;
	}else{
		$valor_pedido = $subtotal_produtos + $_POST['frete'.$_POST['tipo_frete']];
	}
	
	$_POST['valor'] = number_format($valor_pedido,2,',','.');
			
	$_POST['valor_frete'] = number_format($_POST['frete'.$_POST['tipo_frete']],2,",","");
	$_POST['prazo'] = $_POST['prazo'.$_POST['tipo_frete']];
	$_POST['data'] = date("d/m/Y H:I:s");
	
	
	$dados = (object) $_POST;
	
	$dados_comprador = "<h3>Dados do comprador:</h3>";
							$dados_comprador .= "<blockquote>";
							$dados_comprador .= "<strong>".trim($cadastro->nome_completo.' '.($cadastro->responsavel ? "(".$cadastro->responsavel.")" : ""))." ".$cadastro->email."</strong><br />";
							$dados_comprador .= 'RG: <strong>'.$cadastro->rg.'</strong> - CPF/CNPJ: <strong>'.$cadastro->cpf."</strong> <br />";
							$dados_comprador .= 'Telefone: <strong>'.$cadastro->telefone.'</strong> Telefone alternativo: <strong>'.$cadastro->telefone_alternativo."</strong><br />";
							//$dados_entrega .= normaliza($cadastro->endereco.", ".trim($cadastro->numero.' '.$cadastro->complemento.' '.$cadastro->bairros->bairro)." - ".$cadastro->cidades->cidade.", ".$cadastro->estados->estado.", ".$cadastro->cep."<br />");
							$dados_comprador .= "</blockquote>";
														
	$dados_entrega = "<h3>Dados para entrega:</h3>";
	
			if($item->clientes->id > 0){
				
							$dados_entrega .= "<blockquote>";
							$dados_entrega .= '<strong>Os presentes serão entregues diretamente aos noivos</strong>';
							$dados_entrega .= '<br />Casal: '.$item->clientes->nome_noiva.' '.$item->clientes->sobrenome_noiva.' e '.$item->clientes->nome_noivo.' '.$item->clientes->sobrenome_noivo;
							$dados_entrega .= '<br />Dedicatória: '.$_POST['dedicatoria'].'<br />Assinatura: '.$_POST['padrinhos'];
							//$dados_entrega .= '<br />'.normaliza($item->clientes->endereco.", ".trim($item->clientes->numero.' '.$item->clientes->complemento.' '.$item->clientes->bairro))." - ".$item->clientes->cidades->cidade.", ".$item->clientes->estados->estado.", ".$item->clientes->cep;
							$dados_entrega .= "</blockquote>";

		
			}else{
							$dados_entrega .= "<blockquote>";
							
							if($dados->nome and 0){
								$dados_entrega .= trim($dados->nome_completo.' '.($dados->responsavel ? "(".$dados->responsavel.")" : ""))." ".$dados->email."<br />";
								$dados_entrega .= $dados->telefone."<br />";
							}
							$dados_entrega .= normaliza($cadastro->endereco.", ".trim($cadastro->numero.' '.$cadastro->complemento.' '.$cadastro->bairro))." - ".$cadastro->cidades->cidade.", ".$cadastro->estados->estado.", ".$cadastro->cep."<br />";
							$dados_entrega .= "Ponto de referência: ".normaliza($cadastro->referencia)."<br />";
							
							//$dados_entrega .= normaliza($dados->endereco.", ".trim($dados->numero.' '.$dados->complemento.' '.$dados->bairro))." - ".$dados->cidade.", ".$dados->estado.", ".$dados->cep."<br />";
							//$dados_entrega .= "Ponto de referência: ".normaliza($dados->referencia)."<br />";

							$dados_entrega .= "Forma de entrega <strong> ".( $dados->tipo_frete == "PAC" ? "PAC (Correios) Encomenda normal" : ( $dados->tipo_frete == "eSEDEX" ? "eSEDEX" : "SEDEX"))."</strong><br />";
							$dados_entrega .= "Prazo de entrega: <strong>".$dados->prazo." dias úteis</strong> a partir da data de confirmação do pagamento.";
							$dados_entrega .= "</blockquote>";
							//$dados_entrega .= "<small>Apenas para ítens que não constam como presente de Casamento</small>";
			}
	$_POST['dados_entrega'] = $dados_comprador.$dados_entrega;
	

														$__utmz = explode("utmcsr",$_REQUEST['__utmz']);
														$__utmz = parse_url(preg_replace("/\|/","&","utmcsr".$__utmz[1]));
														parse_str("&".$__utmz['path'],$__utmz);
                    
	$_POST['origem'] = $__utmz['utmcsr'].($__utmz['utmccn'] ? '|'.$__utmz['utmccn'] : "").($__utmz['utmcct'] ? '|'.$__utmz['utmcct'] : "").($__utmz['utmcmd'] ? '|'.$__utmz['utmcmd'] : "");
	
	$_POST['serialized'] = pre($_POST,true).pre($_SERVER,true);
	
	
	$db->editar('pedidos',$pedido->id);
	
	$pedido = new objetoDb('pedidos',$pedido->id,true);
	
?>

<h2>Finalização - Pedido:
  <?=$pedido->id?>
</h2>

<div class="clear espaco"></div>

<div class="formulario">
  <?=$dados_comprador?>
  <?=$dados_entrega?>
  <h3>Forma de Pagamento</h3>
  <blockquote>
    Forma de pagamento escolhida: <strong><?=$forma?></strong><br />
    Parcelamento: <strong><?=$parcelas?></strong><br />
  </strong></blockquote>
</div>
<div class="formularioGrd">
  <h3>Ítens solicitados</h3>
  
    <?=$descricao?>

  <h3>Total</h3>
  <blockquote>
    + Subtotal dos produtos: <strong>R$ <?=number_format($subtotal_produtos,2,',','.')?></strong><br />
    + Frete: <strong>R$ <?=number_format($_POST['frete'.$_POST['tipo_frete']],2,',','.')?></strong><br />
	<? if($pedido->idcupons > 1){ ?>- Cupom de desconto: <strong>R$<?=number_format($pedido->cupons->valor,2,',','.')?></strong><br /><? }?>
    = Total Geral: <strong>R$ <?=number_format($valor_pedido,2,',','.')?></strong>
  </blockquote>
</div>
<div class="clear espaco"></div>


<div class="clear espaco"></div>
                    
                    <a href="javascript:void(0);" onclick="segue('carrinho');" class="awesome orange float-left"> <img src="<?=$pagina->localhost?>_imagens/botao2013_anterior.png" width="36" height="36" alt="Compre este produto" />Voltar </a>
					
					

										<?
										
										
	switch($_POST['formaPagamento']){
		case "boleto":
											$Itau = new Itaucripto;
											$dados = $Itau->geraDados
											(
													$codEmp = 'J0043497660001370000012814',
													$pedido->id,
													$valor = number_format($valor_pedido,2,',',''),
													$observacao = '1',
													$chave = 'MESACORTRA243101',
													$nomeSacado = $cadastro->nome.' '.$cadastro->sobrenome,
													$codigoInscricao = 01,
													$numeroInscricao = str_replace('.','',str_replace('-','',$cadastro->cpf)),
													$enderecoSacado = $cadastro->endereco,
													$bairroSacado =  $cadastro->bairros->bairro,
													$cepSacado = $cadastro->cep,
													$cidadeSacado = $cadastro->cidades->cidade,
													$estadoSacado = $cadastro->estados->nome,
													$dataVencimento = date("dmY",time()+60*60*24*5),
													$urlRetorna = 'Retorno/Shopline/'.$pedido->id,
													$obsAd1 = 'NAO RECEBER APÓS O VENCIMENTO',
													$obsAd2 = '',$obsAd3 = ''
											);
											
											$Itau->decripto($dados, $chave); ?>
											
											<form action="https://shopline.itau.com.br/shopline/shopline.asp" method="post" target="_blank" id="1viaBoleto">
											<input type="hidden" name="DC" value="<?=$dados?>" />
											<?php /*?><button type="submit" class="awesome orange"><img src="<?=$pagina->localhost?>_imagens/botao2013_confirma.png" width="36" height="36" alt="Compre este produto" />Imprimir do boleto</button><?php */?>
											</form>
											<a href="#"  class="awesome orange float-right" onclick="$('#1viaBoleto').submit()"> <img src="<?=$pagina->localhost?>_imagens/botao2013_confirma.png" width="36" height="36" alt="Compre este produto" /> &raquo; Finalizar a compra &raquo;</a><small class="float-right clear espaco">(Acessar ambiente de Itaú Shopline para imprimir o boleto)</small>

											
											<?
		break;
		default:
											$PedidoCielo = new Pedido();
											
											// Lê dados do $_POST
											$PedidoCielo->formaPagamentoBandeira = $_POST["codigoBandeira"]; 
											if($_POST["formaPagamento"] != "A" && $_POST["formaPagamento"] != "1")
											{
												$PedidoCielo->formaPagamentoProduto = $_POST["tipoParcelamento"];
												$PedidoCielo->formaPagamentoParcelas = $_POST["formaPagamento"];
											} 
											else 
											{
												$PedidoCielo->formaPagamentoProduto = $_POST["formaPagamento"];
												$PedidoCielo->formaPagamentoParcelas = 1;
											}
											
											$PedidoCielo->dadosEcNumero = CIELO;
											$PedidoCielo->dadosEcChave = CIELO_CHAVE;
											
											//$PedidoCielo->dadosPortadorNome = $cadastro->nome.' '.$cadastro->sobrenome;
											
											$PedidoCielo->capturar = "false";//$_POST["capturarAutomaticamente"];	
											$PedidoCielo->autorizar = 3;//$_POST["indicadorAutorizacao"];
											
											$PedidoCielo->dadosPedidoNumero = $pedido->id;
											$PedidoCielo->dadosPedidoValor = number_format($valor_pedido,2,'','');
											
											$PedidoCielo->dadosPedidoDescricao = substr(utf8_decode(strip_tags($descricao)),0,1024);
											
											
											$PedidoCielo->urlRetorno = "https://www.mesacor.com.br/Retorno/CieloeCommerce/".$pedido->id;//ReturnURL();
											
											// ENVIA REQUISIÇÃO SITE CIELO
											$objResposta = $PedidoCielo->RequisicaoTransacao(false);
											
											$PedidoCielo->tid = $objResposta->tid;
											$PedidoCielo->pan = $objResposta->pan;
											$PedidoCielo->status = $objResposta->status;
											
											$urlAutenticacao = "url-autenticacao";
											$PedidoCielo->urlAutenticacao = $objResposta->$urlAutenticacao;
										
											// Serializa Pedido e guarda na SESSION
											$StrPedido = $PedidoCielo->ToString();
											
											$_POST['transacaoid'] = $PedidoCielo->tid;
											$_POST['status_transacao'] = $PedidoCielo->status;
											$_POST['cielo'] = $StrPedido;
											$db->editar('pedidos',$pedido->id);
											
											
										
											$url_cielo = $PedidoCielo->urlAutenticacao;
											if(preg_match("/suliva/i",$cadastro->nome)){
												pre($PedidoCielo);
												pre($objResposta);
											}
											?>
											
											<a href="<?=$url_cielo?>"  class="awesome orange float-right"> <img src="<?=$pagina->localhost?>_imagens/botao2013_confirma.png" width="36" height="36" alt="Compre este produto" /> &raquo; Finalizar a compra &raquo;</a><small class="float-right clear espaco">(Acessar ambiente de pagamento Cielo)</small>
											<?

	}
											
											
											
											
											
											
											
											
											?>
					

					
											
											
											
                    <div class="clear espaco"></div>
                    
<? }?>