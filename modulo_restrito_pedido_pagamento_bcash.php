<? if($cadastro->conectado()){?>
<?

define('VERSAO', "1.1.0");


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
	
	$_descontos = array();
	foreach($pedido->itens as $item){
		if(count($item->produtos->combos)){
			foreach($item->produtos->combos as $c){
				//pre($c);
				$_descontos[] = $c->combo->id;
			}
		}
	}
	
	$modulo = "bcash";
	$forma = "Cartão de crédito ".strtoupper($_POST['codigoBandeira']);
	$bandeira = strtoupper($_POST['codigoBandeira']);
	$action = $pagina->localhost."Processar-Pedido/pages/novoPedidoAguarde.php";
	
	
	$codigo_formas = array(10,58,59,60,61,62);
	
	
	switch($_POST['formaPagamento']){
		case 10:
			$parcelas =  "À vista";
			$forma = "Boleto";
		break;
		case 58:
			$parcelas =  "Transferência Bancária";
			$forma = "Banco do Brasil";
		break;
		case 59:
			$parcelas =  "Transferência Bancária";
			$forma = "Bradesco";
		break;
		case 60:
			$parcelas =  "Transferência Bancária";
			$forma = "Banco Itaú";
		break;
		case 61:
			$parcelas =  "Transferência Bancária";
			$forma = "Banco Banrisul";
		break;
		case 62:
			$parcelas =  "Transferência Bancária";
			$forma = "Banco HSBC";
		break;
		case 1:
			$parcelas =  "Sim";
			$forma = "Cartão de crédito - Visa";
		break;
		case 2:
			$parcelas =  "Sim";
			$forma = "Cartão de crédito - Mastercard";
		break;
		case 37:
			$parcelas =  "Sim";
			$forma = "Cartão de crédito - American Express";
		break;
		case 45:
			$parcelas =  "Sim";
			$forma = "Cartão de crédito - Aura";
		break;
		case 55:
			$parcelas =  "Sim";
			$forma = "Cartão de crédito - Diners";
		break;
		case 56:
			$parcelas =  "Sim";
			$forma = "Cartão de crédito - Hipercard";
		break;
		case 63:
			$parcelas =  "Sim";
			$forma = "Cartão de crédito - Elo";
		break;
		default:
			$parcelas =  "Sim";
			$forma = "Cartão de crédito ".strtoupper($_POST['codigoBandeira']);
	}
	$_POST['parcelas'] = $_POST['formaPagamento'];
	$_POST['tipo_pagamento'] = trim($bandeira.' '.$parcelas);
			
			
	$campos_produtos = '';
			
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
							}elseif((float)$item->produtos->desconto && $forma == 'Boleto'){
								$descricao .= '<br />'.$item->produtos->desconto.'% de desconto = <strong>R$'.number_format($precoItem * (1 - ($item->produtos->desconto/100)),2,',','.').'</strong>';
								$subtotal = $precoItem * (1 - ($item->produtos->desconto/100));
							}elseif($desconto_vista && $forma == 'Boleto'){
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
								$descricao .= '<br />Casal: '.$item->clientes->nome_noiva.' '.$item->clientes->sobrenome_noiva.' e '.$item->clientes->nome_noivo.' '.$item->clientes->sobrenome_noivo;
						$descricao .= "</p>";
						if($item->produtos->brindes->id){
							$brinde = new objetoDb("produtos",$item->produtos->brindes->id);
							$descricao .= "<p>Brinde: <strong>".$brinde->codigo.' '.$brinde->produto.'</strong></p>';
						}
						
						
						$precoBcash = (in_array($_POST['formaPagamento'],$codigo_formas))? $subtotal : $precoPro;
						$campos_produtos .= '
							<input name="produto_codigo_'.++$j.'" type="hidden" value="'.$item->produtos->codigo.'"> 
							<input name="produto_descricao_'.$j.'" type="hidden" value="'.$item->produtos->produto.'">
							<input name="produto_qtde_'.$j.'" type="hidden" value="'.$item->quantidade.'"> 
							<input name="produto_valor_'.$j.'" type="hidden" value="'.$precoBcash.'" >';
	}
	
	if($item->clientes->id > 0){
		$descricao .= '<p>Dedicatória: '.$_POST['dedicatoria'].'<br />Assinatura: '.$_POST['padrinhos'].'</p>';
	}
	
	$_POST['descricao'] = $descricao;
	
	$_POST['cidade'] = $db->fetch('select cidade from cidades where idcidades = "'.$_POST['cidade'].'"','cidade'); 
	$_POST['estado'] = $db->fetch('select nome from estados where idestados = "'.$_POST['estado'].'"','nome'); 
	
	
	
	$_POST['valor'] = number_format($valor_pedido,2,',','.');
			
	$_POST['valor_frete'] = number_format($_POST['frete'.$_POST['tipo_frete']],2,",","");
	$_POST['prazo'] = $_POST['prazo'.$_POST['tipo_frete']];
	$_POST['data'] = date("d/m/Y H:I:s");
	
	
	$dados = (object) $_POST;
	
	$dados_entrega = "<h3>Dados do comprador:</h3>";
																														$dados_entrega .= "<blockquote>";
																														$dados_entrega .= "<strong>".trim($cadastro->nome_completo.' '.($cadastro->responsavel ? "(".$cadastro->responsavel.")" : ""))." ".$cadastro->email."</strong><br />";
																														$dados_entrega .= 'RG: <strong>'.$cadastro->rg.'</strong> - CPF/CNPJ: <strong>'.$cadastro->cpf."</strong> <br />";
																														$dados_entrega .= 'Telefone: <strong>'.$cadastro->telefone.'</strong> Telefone alternativo: <strong>'.$cadastro->telefone_alternativo."</strong><br />";
																														
																														
																														//$dados_entrega .= normaliza($cadastro->endereco.", ".trim($cadastro->numero.' '.$cadastro->complemento.' '.$cadastro->bairros->bairro)." - ".$cadastro->cidades->cidade.", ".$cadastro->estados->estado.", ".$cadastro->cep."<br />");
																														$dados_entrega .= "</blockquote>";
																														
																														$dados_entrega .= "<h3>Dados para entrega:</h3>";
																														$dados_entrega .= "<blockquote>";
																														
																														if($dados->nome and 0){
																															$dados_entrega .= trim($dados->nome_completo.' '.($dados->responsavel ? "(".$dados->responsavel.")" : ""))." ".$dados->email."<br />";
																															$dados_entrega .= $dados->telefone."<br />";
																														}
																														$dados_entrega .= normaliza($cadastro->endereco.", ".trim($cadastro->numero.' '.$cadastro->complemento.' '.$cadastro->bairro))." - ".$cadastro->cidades->cidade.", ".$cadastro->estados->estado.", ".$cadastro->cep."<br />";
																														$dados_entrega .= "Ponto de referência: ".normaliza($cadastro->referencia)."<br />";
																														
																														$dados_entrega .= "Via <strong> ".( $dados->tipo_frete == "EN" ? "Encomenda normal" : ( $dados->tipo_frete == "eSEDEX" ? "eSEDEX" : "SEDEX"))."</strong><br />";
																														$dados_entrega .= "Prazo de entrega: <strong>".$_POST['prazo']." dias úteis</strong> a partir da data de confirmação do pagamento.";
																														$dados_entrega .= "</blockquote>";
																														//$dados_entrega .= "<small>Apenas para ítens que não constam como presente de Casamento</small>";
	
	$_POST['dados_entrega'] = $dados_entrega;
	

																														$__utmz = explode("utmcsr",$_REQUEST['__utmz']);
																														$__utmz = parse_url(preg_replace("/\|/","&","utmcsr".$__utmz[1]));
																														parse_str("&".$__utmz['path'],$__utmz);
                    
	$_POST['origem'] = $__utmz['utmcsr'].($__utmz['utmccn'] ? '|'.$__utmz['utmccn'] : "").($__utmz['utmcct'] ? '|'.$__utmz['utmcct'] : "").($__utmz['utmcmd'] ? '|'.$__utmz['utmcmd'] : "");
	
	$_POST['serialized'] = serialize($_POST);
	
	
	$db->editar('pedidos',$pedido->id);
	
	$pedido = new objetoDb('pedidos',$pedido->id,true);
	
	
	for( $i = 2 ; $i <= 24 and $_POST['valor']/$i > $pagina->configs['parcela_minina']; $i++) 
			$parcela_maxima = $i;
	
?>

<h2>Finalização - Pedido:
  <?=$pedido->id?>
</h2>

<div class="clear espaco"></div>

<div class="formulario">
  <?=$dados_entrega?>
  <h3>Forma de Pagamento</h3>
  <blockquote>
    Forma de pagamento escolhida: <strong><?=$forma?></strong><br />
    Parcelamento: <strong><?=$parcelas?></strong><br />
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
    + Frete: <strong>R$ <?=number_format($_POST['frete'.$_POST['tipo_frete']],2,',','.')?></strong><br />
    = Total Geral: <strong>R$ <?=number_format($valor_pedido + $_POST['frete'.$_POST['tipo_frete']],2,',','.')?></strong>
  </blockquote>
</div>
<div class="clear espaco"></div>


<div class="clear espaco"></div>
                    
                    <a href="javascript:void(0);" onclick="segue('carrinho');" class="awesome orange float-left"> Voltar </a>
					
					
<a href="#" onclick="$('#formBcash').submit()" class="awesome red float-right"> &raquo; Finalizar a compra &raquo;</a><small class="float-right clear espaco">(Acessar ambiente de pagamento Bcash)</small>
	
					

					
							<form name="bcash" id="formBcash" action="https://www.bcash.com.br/checkout/pay/" method="post">

							<input name="email_loja" type="hidden" value="pedro@mesacor.com.br">
							<input name="id_pedido" type="hidden" value="<?=$pedido->id?>">
							<input name="email" type="hidden" value="<?=$cadastro->email?>">
							<input name="nome" type="hidden" value="<?=$cadastro->nome_completo?>">
							<input name="rg" type="hidden" value="<?=$cadastro->rg?>">
							<input name="cpf" type="hidden" value="<?=$cadastro->cpf.$cadastro->cnpj?>">
							<input name="telefone" type="hidden" value="<?=$cadastro->telefone?>">
							<input name="endereco" type="hidden" value="<?=$cadastro->endereco.", ".$cadastro->numero?>">
							<input name="complemento" type="hidden" value="<?=$cadastro->email?>">
							<input name="bairro" type="hidden" value="<?=$cadastro->bairro?>">
							<input name="cidade" type="hidden" value="<?=$cadastro->cidades->cidade?>">
							<input name="estado" type="hidden" value="<?=$cadastro->estads->uf?>">
							<input name="cep" type="hidden" value="<?=$cadastro->cep?>">
							<input name="desconto" type="hidden" value=""> 
							<input name="tipo_frete" type="hidden" value="<?=( $dados->tipo_frete == "EN" ? "Encomenda normal" : ( $dados->tipo_frete == "eSEDEX" ? "eSEDEX" : "SEDEX"))?>">
							<input name="frete" type="hidden" value="<?=number_format($_POST['frete'.$_POST['tipo_frete']],2,'.','')?>">  
							<input name="meio_pagamento" type="hidden" value="<?=$_POST['formaPagamento']?>">
							<input name="url_retorno" type="hidden" value="https://www.mesacor.com.br/Retorno/Bcash/">
							<input name="redirect" type="hidden" value="true">
							<input name="parcela_maxima" type="hidden" value="<?=$parcela_maxima?>">
							
							<?=$campos_produtos?>
							
							<input name="tipo_integracao" type="hidden" value="PAD">
							
							</form> 				
											
											
                    <div class="clear espaco"></div>
                    
<h2 class="precoGrd">Importante!</h2>
<h3 class="preco">Não feche esta janela.<br />
  Para que o pedido possa ser processado pelo nosso sistema, não feche as janelas antes que o recibo seja visualizado na tela do nosso site.</h3>

                    <div class="clear espaco"></div>

<? }?>