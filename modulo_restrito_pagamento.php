<? if($cadastro->conectado()){?>
<?

//J0043497660001370000012814
//MESACORTRA243101


	//Cartao de teste Visa
	//Número do cartão: 4551 8700 0000 0183
	//Código de segurança: 123
	
	
	//Mastercard - 5555666677778884
	//Diners - 30111122223331

	$_POST = array_merge($_POST,$_REQUEST);
	
	if(is_null($_REQUEST['cpfcgc'])) $_REQUEST['cpfcgc'] = $cadastro->cpf;
    if(is_null($_REQUEST['endereco'])) $_REQUEST['endereco'] = $cadastro->endereco;
    if(is_null($_REQUEST['numero'])) $_REQUEST['numero'] = $cadastro->numero;
    if(is_null($_REQUEST['complemento'])) $_REQUEST['complemento'] = $cadastro->complemento;
    if(is_null($_REQUEST['bairro'])) $_REQUEST['bairro'] = $cadastro->bairro;
    if(is_null($_REQUEST['cep'])) $_REQUEST['cep'] = $cadastro->cep;
    if(is_null($_REQUEST['cidade'])) $_REQUEST['cidade'] = $cadastro->cidades->id;
    if(is_null($_REQUEST['estado'])) $_REQUEST['estado'] = $cadastro->estados->id;
    if(is_null($_REQUEST['parcelas'])) $_REQUEST['parcelas'] = 1;
	
	$action = "https://comercio.locaweb.com.br/comercio.comp";
	
	$desconto = 0;
	
	$valor_pedido = $_REQUEST['pretotal']/100;
	
	switch($_REQUEST['metodo']){
		case "MASTERCARD": $modulo = "REDECARD"; $bandeira = "MASTERCARD"; $forma = "Cartão de crédito MasterCard"; break;
		case "DINERS": $modulo = "REDECARD"; $bandeira = "DINERS"; $forma = "Cartão de crédito Diners"; break;
		case "VISAVBV": $modulo = "VISAVBV"; $forma = "Cartão de crédito Visa"; break;
		case "PAGSEGURO": $modulo = "PAGSEGURO"; $forma = "Pagseguro";$action = "https://pagseguro.uol.com.br/security/webpagamentos/webpagto.aspx"; break;
		case "ITAUSHOPLINE": default:
			$modulo = "ITAUSHOPLINE";
			$forma = "Boleto - via Itaú Shopline";
			$desconto = $pagina->configs['desconto_boleto']/100;
			$_POST['valor'] = number_format((($_REQUEST['pretotal'] * (1 - $desconto)))/100,2,',','.');
			break;
	}
	if($_REQUEST['parcelas'] > 1) $vbv_parcelas = "200".$_REQUEST['parcelas'];
	else $vbv_parcelas = "1001";
	
	$tid = GerarTid(1027957533,$vbv_parcelas);
	
	$_REQUEST['cidade'] = $db->fetch('select cidade from cidades where idcidades = "'.$_REQUEST['cidade'].'"','cidade'); 
	$_REQUEST['estado'] = $db->fetch('select nome from estados where idestados = "'.$_REQUEST['estado'].'"','nome'); 
	
	$_POST['transacaoid'] = $tid;
	//$_POST['valor'] = number_format((($_REQUEST['pretotal'] * (1 - $desconto))+$_REQUEST['item_frete_1'])/100,2,',','.');
	$_POST['parcelas'] = $_REQUEST['parcelas'];
	$_POST['valor_frete'] = $_REQUEST['item_frete_1'];
	$_POST['prazo'] = $_REQUEST['prazo'][$_POST['tipo_frete']];
	$_POST['data'] = date("d/m/Y H:I:s");
	$_POST['tipo_pagamento'] = trim($modulo.' '.$bandeira);
	//pre($_POST);
	
	$_descontos = array();
	foreach($pedido->itens as $item){
		if(count($item->produtos->combos)){
			foreach($item->produtos->combos as $c){
				//pre($c);
				$_descontos[] = $c->combo->id;
			}
		}
	}
	foreach($pedido->itens as $item){
		$precoItem = $item->quantidade * ( in_array($item->produtos->id,$_descontos) ? (float)$item->produtos->preco_combo : ((float)$item->produtos->preco_promocional > 0 ? (float)$item->produtos->preco_promocional : (float)$item->produtos->preco_venda));
		
		$desconto_vista = 0;
		
		foreach($item->produtos->categorias as $c){
			
		pre($c);
			$desconto_vista = max($desconto_vista,$c->desconto_vista);
			
		}
			$desconto_vista = max($pagina->configs['desconto_boleto'],$desconto_vista);
		
		


		$descricao .= "<p>Produto: <strong>";
		$descricao .= $item->produtos->codigo.' '.$item->produtos->produto.'</strong><br>';
		$descricao .= "Quantidade: <strong>".$item->quantidade."</strong>";
		if($_SESSION['variacoes'][$item->produtos->id]){
			$variacao = new objetoDb("variacoes",$_SESSION['variacoes'][$item->produtos->id]);
			$descricao .= '<br>Variação: '.$variacao->opcoes->opcao.'';
		}
		$descricao .= '<br>Subtotal: <strong>R$'.number_format($precoItem,2,',','.').'</strong>';
		
		
		$descricao .= ((float) $desconto_vista ? '<br>'.$desconto_vista.'% de desconto = <strong>R$'.number_format($precoItem * (1 - $desconto_vista),2,',','.').'</strong>' : '');
		
		
		$descricao .= ((in_array($item->produtos->id,$_descontos)) ? ' <em>(DESCONTO COMBO)</em>' : '' );
			if($item->presente)
				$descricao .= '<br /> Embrulhado para presente';
			if($item->clientes->id > 0)
				$descricao .= '<br />Casal: '.$item->clientes->nome_noiva.' '.$item->clientes->sobrenome_noiva.' & '.$item->clientes->nome_noivo.' '.$item->clientes->sobrenome_noivo;
		
		$descricao .= "</p>";
		
		$subtotal += $precoItem * (1 - $desconto);
	}
	pre($descricao);
	if($item->clientes->id > 0){
		$descricao .= '<p>Dedicatória: '.$_REQUEST['dedicatoria'].'<br>Assinatura: '.$_REQUEST['padrinhos'].'</p>';
	}
	$_POST['descricao'] = $descricao;
	$_POST['serialized'] = serialize($_REQUEST);
	
	
	$db->editar('pedidos',$pedido->id);
	
	$pedido = new objetoDb('pedidos',$pedido->id,true);
	$_POST['valor_frete'] = str_replace(",",".",$_POST['valor_frete']);
	
	$dados = (object) $_REQUEST;
	
?>

<h2>Finalização - Pedido:
  <?=$pedido->id?>
</h2>
<form action="<?=$action?>" method="post" id="formPagamento" />
<?php /*?> onsubmit="carrinho.processaCarrinho('formPagamento')"<?php */?>
<div class="formularioGrd"><button type="submit" class="awesome red float-left"><strong>Finalizar a compra</strong></button></div>
<div class="formulario">
  <h3>Dados do comprador:</h3>
  <blockquote>
    <?=$cadastro->nome.' '.$cadastro->sobrenome?><br />
	<?=$cadastro->email?><br />
	<?=$cadastro->telefone?>
  </blockquote>
  <h3>Dados para entrega:</h3><small>Apenas para ítens que não constam como presente de Casamento</small>
  <blockquote>
    <?=$dados->endereco?>, <?=trim($dados->numero.' '.$dados->complemento.' '.$dados->bairro)?>
    <br />
    <?=$dados->cidade?>, <?=$dados->estado?>, <?=$dados->cep?>
    <br />
    Via <strong> <?=( $dados->tipo_frete == "EN" ? "PAC - Encomenda normal - Correios" : "Sedex - Correios")?></strong><br />
    Prazo de entrega: <strong><?=$_POST['prazo']?> dias úteis</strong> a partir da data de confirmação do pagamento.</blockquote>
  <h3>Forma de Pagamento</h3>
  <blockquote>
  	Código da transação: <strong> <?=$tid?> </strong><br />
    Forma de pagamento escolhida: <strong><?=$forma?></strong><br />
    Parcelamento: <strong><?=($_REQUEST['parcelas'] > 1 ? $_REQUEST['parcelas'].' parcelas' : ' À vista')?></strong><br />
  </strong></blockquote>
</div>
<div class="formularioGrd">
  <h3>Ítens solicitados</h3>
  <blockquote>
    <?=$descricao?>
  </blockquote>
  <h3>Total</h3>
  <blockquote>
    + Subtotal: <strong>R$ <?=number_format($subtotal,2,',','.')?></strong><br />
    <?
    if($pedido->cupons->id){
		if($pedido->cupons->valor > 0){
			echo ' - Cupon de desconto: <strong>R$ '.(number_format( $pedido->cupons->valor, 2, ',', '.' )).' - R$'.number_format($subtotal - $pedido->cupons->valor,2,',','.').'</strong><br>';
		}else{
			echo ' - Cupon de desconto: <strong>'.$pedido->cupons->percentual.'% - R$'.number_format($subtotal * (1 - ($pedido->cupons->percentual/100)),2,',','.').'</strong><br>';
		}
	}
	?>
    + Frete: <strong>R$ <?=number_format($_POST['valor_frete'],2,',','.')?></strong><br />
    = Total Geral: <strong>R$ <?=number_format(($valor_pedido * (1 - $desconto)) + $_POST['valor_frete'],2,',','.')?></strong>
  </blockquote>
</div>
<div class="clear espaco"></div>
<input type="hidden" name="identificacao" value="4087631" />
<input type="hidden" name="ambiente" value="producao" />
<input type="hidden" name="modulo" value="<?=$modulo?>" />
<input type="hidden" name="visa_antipopup" value="1" />
<input type="hidden" name="bandeira" value="<?=$bandeira?>" />
<input type="hidden" name="operacao" value="Pagamento" />
<input type="hidden" name="tid" value="<?=$tid?>" />
<input type="hidden" name="orderid" value="<?=$_SESSION['idpedidos']?>" />
<input type="hidden" name="pedido" value="<?=$_SESSION['idpedidos']?>" />
<input type="hidden" name="order" value="Dados do comprador: <?=$cadastro->nome.' '.$cadastro->sobrenome?>, <?=$cadastro->email?>, <?=$cadastro->telefone?>. Dados para entrega: <?=$dados->endereco?>,<?=trim($dados->numero.' '.$dados->complemento.' '.$dados->bairro)?>, <?=$dados->cidade?>, <?=$dados->estado?>, <?=$dados->cep?>" />
<input type="hidden" name="price" value="<?=number_format(($valor_pedido * (1 - $desconto)) + $_POST['valor_frete'],2,'','')?>" />
<input type="hidden" name="valor" value="<?=number_format(($valor_pedido * (1 - $desconto)) + $_POST['valor_frete'],2,'','')?>" />
<input type="hidden" name="damount" value="R$ <?=number_format(($valor_pedido * (1 - $desconto)) + $_POST['valor_frete'],2,',','.')?>" />
<input type="hidden" name="parcelas" value="<?=$_REQUEST['parcelas']?>" />
<input type="hidden" name="juros" value="0" />
<input type="hidden" name="authenttype" value="0" />
<input type="hidden" name="vencimento" value="<?=date("d/m/Y",time()+(60*60*24*5))?>" />
<input type="hidden" name="idclientes" value="<?=$cadastro->id?>" />
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
<input type="hidden" name="cpfcgc" value="<?=str_replace(".",'',str_replace("-",'',$cadastro->cpf))?>">
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
			if(ereg('item',$k)){?>
<input type="hidden" name="<?=$k?>" value="<?=$v?>" />
<? }} ?>
<div class="formulario">
<h2 class="precoGrd">Importante!</h2>
<h3 class="preco">Não feche esta janela.<br />
  Para que o pedido possa ser processado pelo nosso sistema, não feche as janelas antes que o recibo seja visualizado na tela do nosso site.</h3>
</div><div class="formularioGrd"><button type="submit" class="awesome red float-left"><strong>Finalizar a compra</strong></button></div>
</form>
<? }?>
<!-- Google Code for Compra Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1013932022;
var google_conversion_language = "pt";
var google_conversion_format = "1";
var google_conversion_color = "ffffff";
var google_conversion_label = "HcS1CNq4zQEQ9r-94wM";
var google_conversion_value = 0;
if (5,00) {
  google_conversion_value = 5,00;
}
/* ]]> */
</script>
<script type="text/javascript" src="https://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;"> <img height="1" width="1" style="border-style:none;" alt="" src="https://www.googleadservices.com/pagead/conversion/1013932022/?value=5,00&amp;label=HcS1CNq4zQEQ9r-94wM&amp;guid=ON&amp;script=0"/> </div>
</noscript>
