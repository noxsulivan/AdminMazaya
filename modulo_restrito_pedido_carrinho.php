<? //echo '<h2>Pedido - #'. $pedido->id.' - <img src="'.$pagina->localhost.'_imagens/1271297555_geschenk_box_1.png" width="48" height="48" />Presente(s) para '.$convidado->nome_noiva." & ".$convidado->nome_noivo.'</h2>'; ?>
<? echo '<h2>Pedido - #'. $pedido->id."</h2>"; ?>


<? if(!$cadastro->conectado()){ ?>
			<div id="carrinhoLista">
				<?=carregaCarrinhoFull();?>
			</div>
			<div class="clear espaco"></div>
	<? include("modulo_restrito_pedido_identificacao.php");?>
<? }elseif($pedido->id){ ?>





<form action="<?=$pagina->localhost.$canal->url.$pagina->acao?>" method="post" id="carrinhoForm" >

	<?
	unset($_POST['valor']);
	foreach($_POST as $k=>$v){
		echo '<input type="hidden" name="'.$k.'" value="'.$v.'" />';
	}
	?>
	
	
	


	<? if(count($pedido->itens) > 0){?>
			<div id="carrinhoLista">
				<?=carregaCarrinhoFull();?>
			</div>
			<div class="clear espaco"></div>
	<? }else{ ?>
			<h3>Seu carrinho está vazio.</h3>
			<a href="<?=$pagina->localhost?>Produtos" class="awesome orange"> <img src="<?=$pagina->localhost?>_imagens/botao2013_anterior.png" width="36" height="36" alt="Compre este produto" />Continuar comprando </a>
			<div class="clear espaco"></div>
	<? }?>
								
								
								
			
				
<div id="carrinhoDetalhes">
			
            
								<div class="content24_free">
									<? if(count($pedido->itens) > 0){?>
                                            <? //=$cadastro->cep."- ".$pesoComposto."- ".$isento."- ".$dimensao;?>
                                            <?=escolheFrete($cadastro->cep,$pesoComposto,$isento,$dimensao);?>
                                    <? }else{ ?>
                                            <h3>Seu carrinho está vazio.</h3>
                                            <a href="<?=$pagina->localhost?>Produtos" class="botao orange float-left"> <img src="<?=$pagina->localhost?>_imagens/botao2013_anterior.png" width="36" height="36" alt="Compre este produto" />Continuar comprando </a>
                                            <div class="clear espaco"></div>
                                    <? }?>
                                            
                                </div>
								<div class="content24_free">
                                <?				echo '<h2>Total: R$ <span id="totalGeral">'.(number_format( $valor['pac_valor']+$total, 2, ',', '.' )).'</span></h2>';
												echo '<input type="hidden" name="totalpac_valor" id="subtotalpac_valor" value="'.number_format($valor['pac_valor']+$total,2,'.','').'" />';
?>
                                </div>
								
								
								
	</div>
	
	
	<?
	if($cupom){
	?>
	<h2>Cupom de desconto</h2>
								<div class="formulario">
									<p>Se você tem um cupom, insira o código para receber o desconto</p>
									<div class="campo">
										<label for="cupom">Cupom</label>
										<input id="codigoCupom" name="codigoCupom" type="text" value="<?=$cupom->codigo;?>" class=" inputGrande cupom">
									</div>
								</div>
								<div class="formularioGrd">
								<div class="clear espaco"></div>
									<a href="javascript:void(0);" onclick="recuperarCupom($('#codigoCupom').val());" class="botao red"> <img src="<?=$pagina->localhost?>_imagens/botao2013_cupom.png" width="36" height="36" alt="Compre este produto" />Recuperar cupom</a>
								</div>
								<div class="clear espaco"></div>
	<?
	}
	?>
    
    
    <div class="clear espaco"></div>

	<h2>Forma de pagamento</h2>
	<div class="carrinhoSub">
								<div class="content24_free">
									<? if(0){?>
												<h3>Boleto</h3>
												<label class="awesome orange">
													<input type="radio" name="formaPagamento" value="10" />
													<img src="<?=$pagina->localhost?>_imagens/pagIcons/boleto.gif" width="35" height="23" /> Boleto</label>
													
													
												<h3>Transferência Online</h3>			
												<label class="awesome orange">
													<input type="radio" name="formaPagamento" value="58" />
													<img src="<?=$pagina->localhost?>_imagens/pagIcons/bb.gif" width="35" height="23" /> Banco do Brasil</label>
												<label class="awesome orange">
													<input type="radio" name="formaPagamento" value="59" />
													<img src="<?=$pagina->localhost?>_imagens/pagIcons/bradesco.gif" width="35" height="23" /> Banco Bradesco</label>
												<label class="awesome orange">
													<input type="radio" name="formaPagamento" value="60" />
													<img src="<?=$pagina->localhost?>_imagens/pagIcons/itau.gif" width="35" height="23" /> Banco Itaú</label>
												<label class="awesome orange">
													<input type="radio" name="formaPagamento" value="61" />
													<img src="<?=$pagina->localhost?>_imagens/pagIcons/banrisul.gif" width="35" height="23" /> Banco Banrisul</label>
												<label class="awesome orange">
													<input type="radio" name="formaPagamento" value="62" />
													<img src="<?=$pagina->localhost?>_imagens/pagIcons/hsbc.gif" width="35" height="23" /> Banco HSBC</label>
													
													

									<? }else{ ?>
												<h3>Boleto</h3>
												<div id="bolPAC" class="boleto">
													<label class="awesome grey">
														<input type="radio" name="formaPagamento" value="boleto" checked="checked" onchange="$('#parcelamento').hide()" />
														<img src="<?=$pagina->localhost?>_imagens/pagIcons/boleto.gif" width="35" height="23" /> À vista por R$ <? echo number_format($totalDesconto + $valor['pac_valor'],2,',','.'); ?></label>
												</div>
												<div id="bolSEDEX" style="display:none" class="boleto">
													<label class="awesome grey">
														<input type="radio" name="formaPagamento" value="boleto" checked="checked" onchange="$('#parcelamento').hide()" />
														<img src="<?=$pagina->localhost?>_imagens/pagIcons/boleto.gif" width="35" height="23" /> À vista vista por R$ <? echo number_format($totalDesconto + $valor['sed_valor'],2,',','.'); ?></label>
												</div>
												<div id="boleSEDEX" style="display:none" class="boleto">
													<label class="awesome grey">
														<input type="radio" name="formaPagamento" value="boleto" checked="checked" onchange="$('#parcelamento').hide()" />
														<img src="<?=$pagina->localhost?>_imagens/pagIcons/boleto.gif" width="35" height="23" /> À vista vista por R$ <? echo number_format($totalDesconto + $valor['ese_valor'],2,',','.'); ?></label>
												</div>
												<? if($desconto_vista){?><h4>Desconto de <?=$desconto_vista;?>% (R$<? echo number_format($totalDesconto,2,',','.');?>) + Frete</h4><? }?>
									
									<? }?>
<?php /*?>									<small class="clear espaco">Opção válida apenas para pagamento à vista.<br />
									Acrescente 3 dias úteis ao prazo de entrega informado no site, pois esse é o prazo máximo estipulado pelo banco para disponibilizar a confirmação de pagamento.<br />
									Se você pagar o boleto com cheque, acrescente 4 dias úteis.<br />
									Sendo assim não é preciso entrar em contato via telefone ou enviar-nos qualquer notificação sobre o pagamento realizado.</small>
<?php */?>									
									</div>
								<div class="content24_free">
									<? if(0){?>
									

												
												<h3>Cartões de Crédito/Débito</h3>
												<label class="botao grey">
													<input type="radio" name="formaPagamento" value="1" />
													<img src="<?=$pagina->localhost?>_imagens/pagIcons/visa.gif" width="35" height="23" /> Visa</label>
												<label class="botao grey">
													<input type="radio" name="formaPagamento" value="2" />
													<img src="<?=$pagina->localhost?>_imagens/pagIcons/mastercard.gif" width="35" height="23" /> Mastercard</label>
												<label class="botao grey">
													<input type="radio" name="formaPagamento" value="37" />
													<img src="<?=$pagina->localhost?>_imagens/pagIcons/amex.gif" width="35" height="23" /> American Express</label>
												<label class="botao grey">
													<input type="radio" name="formaPagamento" value="45" />
													<img src="<?=$pagina->localhost?>_imagens/pagIcons/aurora.gif" width="35" height="23" /> Aura</label>
												<label class="botao grey">
													<input type="radio" name="formaPagamento" value="55" />
													<img src="<?=$pagina->localhost?>_imagens/pagIcons/diners.gif" width="35" height="23" /> Diners</label>
												<label class="botao grey">
													<input type="radio" name="formaPagamento" value="56" />
													<img src="<?=$pagina->localhost?>_imagens/pagIcons/hipercard.gif" width="35" height="23" /> Hipercard</label>
												<label class="awesome orange">
													<input type="radio" name="formaPagamento" value="63" />
													<img src="<?=$pagina->localhost?>_imagens/pagIcons/elo.gif" width="35" height="23" /> Elo</label>
												<label class="awesome orange">
													<input type="radio" name="formaPagamento" value="63" />
													<img src="<?=$pagina->localhost?>_imagens/pagIcons/jcb.gif" width="35" height="23" /> JCB</label>
												<label class="awesome orange">
													<input type="radio" name="formaPagamento" value="63" />
													<img src="<?=$pagina->localhost?>_imagens/pagIcons/aura.gif" width="35" height="23" /> Aura</label>
									
									<? }else{ ?>
									
															<input name="tipoParcelamento" type="hidden" value="2" />
															<?php /*?><input name="capturarAutomaticamente" type="hidden" value="false" /><?php */?>
															<?php /*?><input name="indicadorAutorizacao" type="hidden" value="2" /><?php */?>
															
															
															
															
															
												<h3>Cartões de Crédito/Débito</h3>
												<label class="awesome grey">
													<input type="radio" name="codigoBandeira" value="visa" onchange="$('#parcelamento').show();$('.pagm').hide();$('.pagVisa').show();" />
													<img src="<?=$pagina->localhost?>_imagens/card_visa.png" width="35" height="23" /> Visa</label>
												<label class="awesome grey">
													<input type="radio" name="codigoBandeira" value="mastercard"  onchange="$('#parcelamento').show();$('.pagm').hide();$('.pagAll').show();"/>
													<img src="<?=$pagina->localhost?>_imagens/card_master.png" width="35" height="23" /> Master</label>
												<label class="awesome grey">
													<input type="radio" name="codigoBandeira" value="elo" onchange="$('#parcelamento').show();$('.pagm').hide();$('.pagAll').show();" />
													<img src="<?=$pagina->localhost?>_imagens/card_elo.png" width="35" height="23" /> Elo</label>
												<label class="awesome grey">
													<input type="radio" name="codigoBandeira" value="diners" onchange="$('#parcelamento').show();$('.pagm').hide();$('.pagAll').show();" />
													<img src="<?=$pagina->localhost?>_imagens/card_diners.png" width="35" height="23" /> Diners</label>
												<label class="awesome grey">
													<input type="radio" name="codigoBandeira" value="amex" onchange="$('#parcelamento').show();$('.pagm').hide();$('.pagAmex').show();" />
													<img src="<?=$pagina->localhost?>_imagens/card_amex.png" width="35" height="23" /> Amex</label>
<?php /*?>												<label class="awesome grey">
													<input type="radio" name="codigoBandeira" value="jcb" onchange="$('#parcelamento').show();$('.pagm').hide();$('.pagAll').show();" />
													<img src="<?=$pagina->localhost?>_imagens/card_jcb.png" width="35" height="23" /> JCB</label>
												<label class="awesome grey">
													<input type="radio" name="codigoBandeira" value="aura" onchange="$('#parcelamento').show();$('.pagm').hide();$('.pagAll').show();" />
													<img src="<?=$pagina->localhost?>_imagens/card_aura.png" width="35" height="23" /> Aura</label>
<?php */?>												<label class="awesome grey">
													<input type="radio" name="codigoBandeira" value="discover" onchange="$('#parcelamentoDiscover').show();$('.pagm').hide();$('.pagDisc').show();" />
													<img src="<?=$pagina->localhost?>_imagens/card_discover.png" width="35" height="23" /> Discover (somente à vista)</label>
												<div id="parcelamento" style="display:none;">
													<h3>Parcelamento sem acréscimo *</h3>
													<div id="parcPAC" class="parc EN">
															<?=escolheParcelamentoCielo($valor['pac_valor'],$total)?>
													</div>
													<div id="parcSEDEX" style="display:none" class="parc SEDEX">
															<?=escolheParcelamentoCielo($valor['sed_valor'],$total)?>
													</div>
													<div id="parceSEDEX" style="display:none" class="parc eSEDEX">
															<?=escolheParcelamentoCielo($valor['ese_valor'],$total)?>
													</div>
													<div class="clear espaco"></div>
													* Parcela mínima de R$ 30,00<br />
													<small>Nos pedidos parcelados no cartão de crédito, observe o valor de seu limite. Ele deve ser maior que o valor total deste pedido, não importando o valor de cada parcela.</small> </div>
												</div>

									<? }?>
									</div>
									
									
									
	<? if($convidado->id > 0){ ?>
	<h2>Mensagem aos Noivos</h2>
								<div class="content24_free">
									<p>Junto a cada presente entregue pela Mesacor aos noivos, é anexado um cartão especial escrito a mão com uma dedicatória.</p>
									<p>Aproveite e escolha as suas palavras</p>
								</div>
								<div class="formularioGrd">
									<div class="campo">
										<label for="dedicatoria">Dedicatória</label>
										<textarea id="dedicatoria" name="dedicatoria" cols="" rows="" class="inputGrande"><?=$pedido->dedicatoria;?></textarea>
									</div>
									<div class="clear"></div>
									<div class="campo">
										<label for="padrinhos">Assinado por</label>
										<input id="padrinhos" name="padrinhos" type="text" value="<?=$pedido->padrinhos;?>" class=" inputGrande">
									</div>
								</div>
								<div class="clear espaco"></div>
	<? }?>
									
	<div class="content24_free"> <a href="<?=$pagina->localhost?>Produtos" class="awesome orange"><img src="<?=$pagina->localhost?>_imagens/botao2013_anterior.png" width="36" height="36" alt="Compre este produto" /> Continuar comprando</a> </div>
	
	<? if(0){?>
	<div class="content24_free"> <a href="javascript:void(0);" onclick="segue('pagamento_bcash');" class="awesome orange"> <img src="<?=$pagina->localhost?>_imagens/botao2013_proximo.png" width="36" height="36" alt="Compre este produto" />Próximo Passo &raquo; Confirmar</a> </div>
	<? }else{//$cadastro->id == 100){?>
	<div class="content24_free"> <a href="javascript:void(0);" onclick="segue('pagamento_cielo');" class="awesome orange"> <img src="<?=$pagina->localhost?>_imagens/botao2013_proximo.png" width="36" height="36" alt="Compre este produto" />Próximo Passo &raquo; Confirmar</a> </div>
	<? }?>
	
	<br clear="all">
	<? }?>
</form>