<? if($cadastro->conectado()){?>
<?

define('VERSAO', "1.1.0");



// CONSTANTES
define("ENDERECO_BASE", "https://qasecommerce.cielo.com.br");
define("ENDERECO", ENDERECO_BASE."/servicos/ecommwsec.do");

define("LOJA", "1006993069");
define("LOJA_CHAVE", "25fbb99741c739dd84d7b06ec78c9bac718838630f30b112d033ce2e621b34f3");
define("CIELO", "1001734898");
define("CIELO_CHAVE", "e84827130b9837473681c2787007da5914d6359947015a5cdb2b8843db0fa832");




foreach($_POST as $k=>$v){
	$_POST[$k] = htmlentities($v);
}
//J0043497660001370000012814
//MESACORTRA243101


	//Cartao de teste Visa
	//Número do cartão: 4551870000000183
	//Código de segurança: 123
	
	
	//Mastercard - 5555666677778884
	//Diners - 30111122223331

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
	
	$action = "https://comercio.locaweb.com.br/comercio.comp";
	
	$operacao = "Pagamento";
	$desconto = 0;
	
	$_descontos = array();
	foreach($pedido->itens as $item){
		if(count($item->produtos->combos)){
			foreach($item->produtos->combos as $c){
				//pre($c);
				$_descontos[] = $c->combo->id;
			}
		}
	}
	
	if($_POST['codigoBandeira'] != ''){
		$_POST['metodo'] = "CIELONOVO";
	}
	
	switch($_POST['metodo']){
		case "MASTERCARD": $modulo = "REDECARD"; $bandeira = "MASTERCARD"; $forma = "Cartão de crédito MasterCard"; break;
		case "DINERS": $modulo = "REDECARD"; $bandeira = "DINERS"; $forma = "Cartão de crédito Diners"; break;
		case "VISAVBV": $modulo = "VISAVBV"; $forma = "Cartão de crédito Visa"; break;
		case "CIELOVISA":
			$modulo = "CIELO"; $bandeira = "visa";
			$forma = "Cartão de crédito Visa";
			$operacao = 'Registro'; $ambiente = 'PRODUCAO'; break;
		case "CIELOMASTER":
			$modulo = "CIELO"; $bandeira = "mastercard";
			$forma = "Cartão de crédito MasterCard";
			$operacao = 'Registro'; $ambiente = 'PRODUCAO'; break;
		case "PAGSEGURO":
			$modulo = "PAGSEGURO"; $forma = "Pagseguro";
			$action = "https://pagseguro.uol.com.br/security/webpagamentos/webpagto.aspx"; break;
		case "CIELONOVO":
			$modulo = "CIELONOVO";
			$forma = "Cartão de crédito ".strtoupper($_POST['codigoBandeira']);
			$operacao = 'Registro'; $ambiente = 'PRODUCAO';
			$bandeira = strtoupper($_POST['codigoBandeira']);
			$action = $pagina->localhost."Processar-Pedido/pages/novoPedidoAguarde.php"; break;
		case "ITAUSHOPLINE": default:
			$modulo = "ITAUSHOPLINE"; $forma = "Boleto - via Itaú Shopline";
			$_POST['parcelas'] = 1;
			
					
					
			break;
	}
	foreach($pedido->itens as $item){
		
		$desconto_vista = $pagina->configs['desconto_boleto'];
		if(count($item->produtos->categorias)){
			foreach($item->produtos->categorias as $c){
				$desconto_vista = min($desconto_vista,$c->desconto_vista);
			}
		}
		
		
		$precoPro = ( in_array($item->produtos->id,$_descontos) ?
							   (float)$item->produtos->preco_combo : ((float)$item->produtos->preco_promocional > 0 ?
																	   (float)$item->produtos->preco_promocional : (float)$item->produtos->preco_venda));
		$precoItem = $item->quantidade * ( in_array($item->produtos->id,$_descontos) ?
													(float)$item->produtos->preco_combo : ((float)$item->produtos->preco_promocional > 0 ?
																							(float)$item->produtos->preco_promocional : (float)$item->produtos->preco_venda));
		
		$descricao .= "<p>Produto: <strong>".$item->produtos->codigo.' '.$item->produtos->produto.'</strong>';
				if($_SESSION['variacoes'][$item->produtos->id]){
					$variacao = new objetoDb("variacoes",$_SESSION['variacoes'][$item->produtos->id]);
					$descricao .= '<br />Variação: <strong>'.$variacao->opcoes->opcao.'</strong>';
				}
				$descricao .= '<br />Quantidade: <strong>'.$item->quantidade.'</strong>';
				$descricao .= ' Disponibilidade: '.($item->produtos->prazo_entrega ? "<strong>".$item->produtos->prazo_entrega." dias</strong>" : "<strong>Em estoque</strong>");
				$descricao .= '<br />Unitario: <strong>R$'.number_format($precoPro,2,',','.').'</strong> - Subtotal: <strong>R$'.number_format($precoItem,2,',','.').'</strong> ';
					
					if((float)$item->produtos->desconto < 1){
						$subtotal = $precoItem;
					}elseif((float)$item->produtos->desconto && $modulo == 'ITAUSHOPLINE'){
						$descricao .= '<br />'.$item->produtos->desconto.'% de desconto = <strong>R$'.number_format($precoItem * (1 - ($item->produtos->desconto/100)),2,',','.').'</strong>';
						$subtotal = $precoItem * (1 - ($item->produtos->desconto/100));
					}elseif($desconto_vista && $modulo == 'ITAUSHOPLINE'){
						$descricao .= '<br />'.$desconto_vista.'% de desconto = <strong>R$'.number_format($precoItem * (1 - ($desconto_vista/100)),2,',','.').'</strong>';
						$subtotal = $precoItem * (1 - ($desconto_vista/100));
					}else{
						$subtotal = $precoItem;
					}
					
				$valor_pedido += $subtotal;
				
				$descricao .= ((in_array($item->produtos->id,$_descontos)) ? ' <em>(DESCONTO COMBO)</em>' : '' );
					if($item->presente)
						$descricao .= '<br /> Embrulhado para presente';
					if($item->clientes->id > 0)
						$descricao .= '<br />Casal: '.$item->clientes->nome_noiva.' '.$item->clientes->sobrenome_noiva.' & '.$item->clientes->nome_noivo.' '.$item->clientes->sobrenome_noivo;
				$descricao .= "</p>";
				if($item->produtos->brindes->id){
					$brinde = new objetoDb("produtos",$item->produtos->brindes->id);
					$descricao .= "<p>Brinde: <strong>".$brinde->codigo.' '.$brinde->produto.'</strong></p>';
				}
	}
	
	if($item->clientes->id > 0){
		$descricao .= '<p>Dedicatória: '.$_POST['dedicatoria'].'<br />Assinatura: '.$_POST['padrinhos'].'</p>';
	}
	if($_POST['parcelas'] > 1) $vbv_parcelas = "200".$_POST['parcelas'];
	else $vbv_parcelas = "1001";
	
	$tid = GerarTid(1027957533,$vbv_parcelas);
	
	$_POST['cidade'] = $db->fetch('select cidade from cidades where idcidades = "'.$_POST['cidade'].'"','cidade'); 
	$_POST['estado'] = $db->fetch('select nome from estados where idestados = "'.$_POST['estado'].'"','nome'); 
	//$_POST['bairro'] = $db->fetch('select bairro from bairros where idbairros = "'.$_POST['bairro'].'"','bairro'); 
	
	$_POST['transacaoid'] = $tid;
	
if($cadastro->id == 100){
	$_POST['parcelas'] = $_POST['formaPagamento'];
}else{
	$_POST['parcelas'] = $_POST['parcelas'];
}
	
	$_POST['valor'] = number_format($valor_pedido,2,',','.');
			
	$_POST['valor_frete'] = number_format($_POST['frete'.$_POST['tipo_frete']],2,",","");
	$_POST['prazo'] = $_POST['prazo'.$_POST['tipo_frete']];
	$_POST['data'] = date("d/m/Y H:I:s");
	$_POST['tipo_pagamento'] = trim($modulo.' '.$bandeira);
	
	
	$dados = (object) $_POST;
	
	$dados_entrega = "<h3>Dados do comprador:</h3>";
	$dados_entrega .= "<blockquote>";
	$dados_cartao .= "<strong>".trim($cadastro->nome_completo.' '.($cadastro->responsavel ? "(".$cadastro->responsavel.")" : ""))." ".$cadastro->email."</strong>\r\n";
    $dados_entrega .= "<strong>".trim($cadastro->nome_completo.' '.($cadastro->responsavel ? "(".$cadastro->responsavel.")" : ""))." ".$cadastro->email."</strong><br />";
    $dados_cartao .=  'RG: <strong>'.$cadastro->rg.'</strong> - CPF/CNPJ: <strong>'.$cadastro->cpf."</strong>\n\n";
    $dados_entrega .= 'RG: <strong>'.$cadastro->rg.'</strong> - CPF/CNPJ: <strong>'.$cadastro->cpf."</strong> <br />";
	$dados_cartao .=  'Fone: <strong>'.$cadastro->telefone."</strong>\r\n";
	$dados_entrega .= 'Telefone: <strong>'.$cadastro->telefone.'</strong> Telefone alternativo: <strong>'.$cadastro->telefone_alternativo."</strong><br />";
	
	
    //$dados_entrega .= normaliza($cadastro->endereco.", ".trim($cadastro->numero.' '.$cadastro->complemento.' '.$cadastro->bairros->bairro)." - ".$cadastro->cidades->cidade.", ".$cadastro->estados->estado.", ".$cadastro->cep."<br />");
    $dados_entrega .= "</blockquote>";
	
	$dados_entrega .= "<h3>Dados para entrega:</h3>";
	$dados_entrega .= "<blockquote>";
	
	if($dados->nome and 0){
		$dados_entrega .= trim($dados->nome_completo.' '.($dados->responsavel ? "(".$dados->responsavel.")" : ""))." ".$dados->email."<br />";
		$dados_entrega .= $dados->telefone."<br />";
	}
	$dados_cartao .= normaliza($cadastro->endereco.", ".trim($cadastro->numero.' '.$cadastro->complemento.' '.$cadastro->bairro))." - ".$cadastro->cidades->cidade.", ".$cadastro->estados->estado.", ".$cadastro->cep."\r\n";
	$dados_cartao .= "Ponto de referência: ".normaliza($cadastro->referencia)."<br />";
	$dados_entrega .= normaliza($cadastro->endereco.", ".trim($cadastro->numero.' '.$cadastro->complemento.' '.$cadastro->bairro))." - ".$cadastro->cidades->cidade.", ".$cadastro->estados->estado.", ".$cadastro->cep."<br />";
	$dados_entrega .= "Ponto de referência: ".normaliza($cadastro->referencia)."<br />";
	
	$dados_entrega .= "Via <strong> ".( $dados->tipo_frete == "EN" ? "Encomenda normal" : ( $dados->tipo_frete == "eSEDEX" ? "eSEDEX" : "SEDEX"))."</strong><br />";
	$dados_entrega .= "Prazo de entrega: <strong>".$_POST['prazo']." dias úteis</strong> a partir da data de confirmação do pagamento.";
	$dados_entrega .= "</blockquote>";
    //$dados_entrega .= "<small>Apenas para ítens que não constam como presente de Casamento</small>";
	
	$_POST['dados_entrega'] = $dados_entrega;
	$_POST['descricao'] = $descricao;
	

					$__utmz = explode("utmcsr",$_REQUEST['__utmz']);
					$__utmz = parse_url(preg_replace("/\|/","&","utmcsr".$__utmz[1]));
					parse_str("&".$__utmz['path'],$__utmz);
                    
					$_POST['origem'] = $__utmz['utmcsr'].
											($__utmz['utmccn'] ? '|'.$__utmz['utmccn'] : "").
											($__utmz['utmcct'] ? '|'.$__utmz['utmcct'] : "").
											($__utmz['utmcmd'] ? '|'.$__utmz['utmcmd'] : "");
	$_POST['serialized'] = serialize($_POST);
	
	
	$db->editar('pedidos',$pedido->id);
	
	$pedido = new objetoDb('pedidos',$pedido->id,true);
	
?>

<h2>Finalização - Pedido:
  <?=$pedido->id?>
</h2>
<form action="<?=$action?>" method="post" id="carrinhoForm">

<div class="clear espaco"></div>

<div class="formulario">
  <?=$dados_entrega?>
  <h3>Forma de Pagamento</h3>
  <blockquote>
  	Código da transação: <strong> <?=$tid?> </strong><br />
    Forma de pagamento escolhida: <strong><?=$forma?></strong><br />
    Parcelamento: <strong><?=($_POST['parcelas'] > 1 ? $_POST['parcelas'].' parcelas' : ' À vista')?></strong><br />
  </strong></blockquote>
</div>
<div class="formularioGrd">
  <h3>Ítens solicitados</h3>
  <blockquote>
    <?=$descricao?>
  </blockquote>
  <h3>Total</h3>
  <blockquote>
    + Subtotal: <strong>R$ <?=number_format($valor_pedido,2,',','.')?></strong><br />
    <?
    if($pedido->cupons->id){
		if($pedido->cupons->valor > 0){
			echo ' - Cupon de desconto: <strong>R$ '.(number_format( $pedido->cupons->valor, 2, ',', '.' )).' - R$'.number_format($subtotal - $pedido->cupons->valor,2,',','.').'</strong><br />';
		}else{
			echo ' - Cupon de desconto: <strong>'.$pedido->cupons->percentual.'% - R$'.number_format($subtotal * (1 - ($pedido->cupons->percentual/100)),2,',','.').'</strong><br />';
		}
	}
	?>
    + Frete: <strong>R$ <?=number_format($_POST['frete'.$_POST['tipo_frete']],2,',','.')?></strong><br />
    = Total Geral: <strong>R$ <?=number_format($valor_pedido + $_POST['frete'.$_POST['tipo_frete']],2,',','.')?></strong>
  </blockquote>
</div>
<div class="clear espaco"></div>

<? if($cadastro->id == 100){?>
										<?
											
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
											
											$PedidoCielo->capturar = $_POST["capturarAutomaticamente"];	
											$PedidoCielo->autorizar = $_POST["indicadorAutorizacao"];
											
											$PedidoCielo->dadosPedidoNumero = $pedido->id;
											$PedidoCielo->dadosPedidoValor = number_format(($valor_pedido * (1 - $desconto)) + $_POST['frete'.$_POST['tipo_frete']],2,'','');
											
											$PedidoCielo->urlRetorno = "https://www.mesacor.com.br/Retorno/CieloeCommerce/";//ReturnURL();
											
											// ENVIA REQUISIÇÃO SITE CIELO
											$objResposta = $PedidoCielo->RequisicaoTransacao(false);
											
											$PedidoCielo->tid = $objResposta->tid;
											$PedidoCielo->pan = $objResposta->pan;
											$PedidoCielo->status = $objResposta->status;
											
											$urlAutenticacao = "url-autenticacao";
											$PedidoCielo->urlAutenticacao = $objResposta->$urlAutenticacao;
										
											// Serializa Pedido e guarda na SESSION
											$StrPedido = $PedidoCielo->ToString();
											
											pre($StrPedido);
											
											$_POST['transacaoid'] = $PedidoCielo->tid;
											$_POST['status_transacao'] = $PedidoCielo->status;
											$_POST['cielo'] = $StrPedido;
											$db->editar('pedidos',$pedido->id);
											
											
										
											$url_cielo = $PedidoCielo->urlAutenticacao;
											
											?>

											<input name="tipoParcelamento" type="hidden" value="<?=$_POST['tipoParcelamento']?>" />
											<input name="formaPagamento" type="hidden" value="<?=$_POST['formaPagamento']?>" />
											<input name="capturarAutomaticamente" type="hidden" value="<?=$_POST['capturarAutomaticamente']?>" />
											<input name="indicadorAutorizacao" type="hidden" value="<?=$_POST['indicadorAutorizacao']?>" />
											<input name="codigoBandeira" value="<?=$_REQUEST['codigoBandeira']?>" type="hidden" />
											<input name="produto" value="A" type="hidden"/>

											<input type="hidden" name="pedido" value="<?=$pedido->id?>" />

<? }else{ ?>
											<input type="hidden" name="identificacao" value="4087631" />
														<? if($modulo == "CIELO"){?>
											<input type="hidden" name="ambiente" value="TESTE" />
											<input type="hidden" name="operacao" value="<?=$operacao?>" />
											<input type="hidden" name="forma_pagamento" value="1" />
											<input type="hidden" name="autorizar" value="3" />
											<input type="hidden" name="capturar" value="false" />
														<? }else{?>
											<input type="hidden" name="ambiente" value="PRODUCAO" />
														<? }?>
											<input type="hidden" name="modulo" value="<?=$modulo?>" />
											<input type="hidden" name="visa_antipopup" value="1" />
											<input type="hidden" name="bandeira" value="<?=$bandeira?>" />
											<input type="hidden" name="tid" value="<?=$tid?>" />
											<input type="hidden" name="orderid" value="<?=($pedido->estagios->id == 7 ? "10": "").$pedido->id?>" />
											<input type="hidden" name="pedido" value="<?=($pedido->estagios->id == 7 ? "10": "").$pedido->id?>" />
											<input type="hidden" name="order" value="Dados do comprador: <?=$cadastro->nome.' '.$cadastro->sobrenome?>, <?=$cadastro->email?>, <?=$cadastro->telefone?>. Dados para entrega: <?=$dados->endereco?>,<?=trim($dados->numero.' '.$dados->complemento.' '.$dados->bairro)?>, <?=$dados->cidade?>, <?=$dados->estado?>, <?=$dados->cep?>" />
											<input type="hidden" name="price" value="<?=number_format(($valor_pedido * (1 - $desconto)) + $_POST['frete'.$_POST['tipo_frete']],2,'','')?>" />
											<input type="hidden" name="valor" value="<?=number_format(($valor_pedido * (1 - $desconto)) + $_POST['frete'.$_POST['tipo_frete']],2,'','')?>" />
											<input type="hidden" name="damount" value="R$ <?=number_format(($valor_pedido * (1 - $desconto)) + $_POST['frete'.$_POST['tipo_frete']],2,',','.')?>" />
											<input type="hidden" name="parcelas" value="<?=$_POST['parcelas']?>" />
											<input type="hidden" name="juros" value="0" />
											<input type="hidden" name="authenttype" value="0" />
											<input type="hidden" name="vencimento" value="<?=date("d/m/Y",time()+(60*60*24*5))?>" />
											<input type="hidden" name="idclientes" value="<?=$cadastro->id?>" />
											<input type="hidden" name="item_frete_1" value="<?=number_format($_POST['frete'.$_POST['tipo_frete']],2,',','.')?>" />
											<input type="hidden" name="email_cobranca" value="<?=$pagina->configs['email_pagseguro']?>" />
											<input type="hidden" name="tipo_frete" value="<?=$_POST['frete_formato']?>" />
											<input type="hidden" name="tipo" value="CP" />
											<input type="hidden" name="moeda" value="BRL" />
											<input type="hidden" name="ref_transacao" value="pedido_<?=$_SESSION['idpedidos']?>" />
											<input type="hidden" name="cliente_nome" value="<?=$cadastro->nome.' '.$cadastro->sobrenome?>" />
											<input type="hidden" name="cliente_cep" value="<?=str_replace("-",'',$dados->cep)?>" />
											<input type="hidden" name="cliente_end" value="<?=$dados->endereco?>" />
											<input type="hidden" name="cliente_num" value="<?=$dados->numero?>" />
											<input type="hidden" name="cliente_compl" value="<?=$dados->complemento?>" />
											<input type="hidden" name="cliente_bairro" value="<?=$dados->bairro?>" />
											<input type="hidden" name="cliente_cidade" value="<?=$dados->cidade?>" />
											<input type="hidden" name="cliente_uf" value="<?=strtoupper($dados->estado)?>" />
											<input type="hidden" name="cliente_pais" value="BRA" />
											<input type="hidden" name="nome" value="<?=$cadastro->nome.' '.$cadastro->sobrenome?>">
											<input type="hidden" name="cpfcgc" value="<?=str_replace("/",'',str_replace(".",'',str_replace("-",'',$cadastro->cpf)))?>">
											<input type="hidden" name="endereco" value="<?=$dados->endereco?>, <?=$dados->numero?> <?=$dados->complemento?>">
											<input type="hidden" name="bairro" value="<?=$dado->bairro?>">
											<input type="hidden" name="cep" value="<?=str_replace("-",'',$dado->cep)?>">
											<input type="hidden" name="cidade" value="<?=$dados->cidade?>">
											<input type="hidden" name="estado" value="<?=$dados->estado?>">
											<input type="hidden" name="obs" value="">
											<input type="hidden" name="OBSAdicional1" value="">
											<input type="hidden" name="OBSAdicional2" value="">
											<input type="hidden" name="OBSAdicional3" value="">
											<?
													$ddd = substr($cadastro->telefone,1,2);
													$telefone = substr($cadastro->telefone,5);
													?>
											<input type="hidden" name="cliente_ddd" value="<?=$ddd?>" />
											<input type="hidden" name="cliente_tel" value="<?=$telefone?>" />
											<input type="hidden" name="cliente_email" value="<?=$cadastro->email?>" />
											<? foreach($_POST as $k => $v){
														if(preg_match('/item/i',$k)){?>
											<input type="hidden" name="<?=$k?>" value="<?=$v?>" />
											<? }} ?>


<? }?>
<div class="clear espaco"></div>
                    
                    <a href="javascript:void(0);" onclick="segue('carrinho');" class="awesome orange float-left"> Voltar </a>
                    <? if($modulo == "CIELO"){?>
						<?
                            $retorno = getCielo( "4087631", $modulo, $operacao, $ambiente,
                                                  $valor = number_format(($valor_pedido * (1 - $desconto)) + $_POST['frete'.$_POST['tipo_frete']],2,'',''),
                                                  $pedido->id, addslashes(substr(strip_tags($dados_entrega),0,1024)),
                                                  $bandeira, $forma_pagamento = ($_POST['parcelas'] > 1 ? 2 : 1 ), $_POST['parcelas'], $autorizar = 2, $capturar = 'false');
                            
                            $url_cielo = $retorno->url_autenticacao;
							
							
                            $db->query("update pedidos set transacaoid = '".$retorno->tid."' where idpedidos = '".$pedido->id."'");
                        ?>
                        <a href="<?=$url_cielo?>" class="awesome red float-right"> &raquo; Finalizar a compra &raquo;</a><small class="float-right clear espaco">(Acessar ambiente de pagamento Cielo)</small>
                    <? }elseif(($modulo == "CIELONOVO")){ ?>
                        <a href="<?=$url_cielo?>" class="awesome red float-right"> &raquo; Finalizar a compra &raquo;</a><small class="float-right clear espaco">(Acessar ambiente de pagamento Cielo)</small>
                    <? }else{ ?>
                        <a href="javascript:void(0);" onclick="$('#carrinhoForm').submit()" class="awesome red float-right"> &raquo; Finalizar a compra </a>
                    <? }?>
</form>

                    <div class="clear espaco"></div>
                    
<h2 class="precoGrd">Importante!</h2>
<h3 class="preco">Não feche esta janela.<br />
  Para que o pedido possa ser processado pelo nosso sistema, não feche as janelas antes que o recibo seja visualizado na tela do nosso site.</h3>

                    <div class="clear espaco"></div>

<? }?>