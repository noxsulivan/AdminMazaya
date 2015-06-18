<?php /*?><ol id="passos" class="clear"><li>1. Carrinho</li>
<li>2. Entrega</li>
<li>3. Frete</li>
<li class="current">4. Forma de pagamento</li>
<li>5. Confirmação</li>
</ol><?php */?>


<form action="<?=$pagina->localhost.$canal->url.$pagina->acao?>" method="post" id="carrinhoForm" >

<?
foreach($_POST as $k=>$v){
	echo '
	<input type="hidden" name="'.$k.'" value="'.($v).'" />';
}
?>                                          <h2>Forma de pagamento</h2>
                                          <div class="content24">
                                            <h3>Cartões de Crédito (À Vista ou Parcelado)</h3>
                                            <ul>
                                            <? if(0 and $_REQUEST['total'] < 2){?>
											<? }else{ ?>
                                              <li>
                                                <input type="radio" name="metodo" value="CIELOVISA" checked="checked" />
                                                <img src="<?=$pagina->localhost?>_imagens/pagIcons/visa.gif" width="35" height="23" /> Visa</li>
<?php /*?>                                              <li>
                                                <input type="radio" name="metodo" value="VISAVBV" />
                                                <img src="<?=$pagina->localhost?>_imagens/pagIcons/visa.gif" width="35" height="23" /> Visa</li>
<?php */?>                                              <li>
                                                <input type="radio" name="metodo" value="MASTERCARD" />
                                                <img src="<?=$pagina->localhost?>_imagens/pagIcons/mastercard.gif" width="35" height="23" /> Master</li>
                                              <li>
                                                <input type="radio" name="metodo" value="DINERS" />
                                                <img src="<?=$pagina->localhost?>_imagens/pagIcons/diners.gif" width="35" height="23" /> Diners</li>
                                            <? }?>
                                            </ul>
                                          </div>
                                          <div class="sidebar24">
                                            <h3>Simulação de parcelamento *</h3>
                                            <ul id="parcelamento">
											  <?=escolheParcelamento($_REQUEST['frete'.$_REQUEST['tipo_frete']],$_REQUEST['total'])?>
                                            </ul>
                                            * Parcela mínima de R$ 30,00<br />
                                            ** Em caso de dúvida, <a href="<?=$pagina->localhost.$canal->url?>">atualize</a> a página para que a simulação seja recalculada.
                                            <small>Nos pedidos parcelados no cartão de crédito, observe o valor de seu limite. Ele deve ser maior que o valor total deste pedido, não importando o valor de cada parcela.</small> </div>
                                          <div class="pagamento clear">
                                            <h3>Boleto (À vista com <?=$pagina->configs['desconto_boleto']?>% de desconto)</h3>
                                            <ul>
                                              <li>
                                                <input type="radio" name="metodo" value="ITAUSHOPLINE" />
                                                <img src="<?=$pagina->localhost?>_imagens/pagIcons/boleto.gif" width="35" height="23" /> </li>
                                            </ul>
                                          </div>
                                          Opção válida apenas para pagamento à vista. Acrescente 3 dias úteis ao prazo de entrega informado no site, pois esse é o prazo máximo estipulado pelo banco para disponibilizar a confirmação de pagamento. Se você pagar o boleto com cheque, acrescente 4 dias úteis.
                                          Sendo assim não é preciso entrar em contato via telefone ou enviar-nos qualquer notificação sobre o pagamento realizado.
<?php /*?>                                          <div class="pagamento clear">
                                            <h3>Débito Online/TEF</h3>
                                            <ul>
                                              <li>
                                                <input type="radio" name="metodo" value="PAGSEGURO" />
                                                <img src="<?=$pagina->localhost?>_imagens/pagIcons/pagseguro.gif" width="35" height="23" /> Pagseguro<br />
                                                <small>Através desta opção, oferecemos pagamento através de boleto e bankline do Bradesco, Banco do Brasil, Itaú, Unibanco, Banco Real e Banrisul.</small><br />
                                                <img src="<?=$pagina->localhost?>_imagens/pagIcons/amex.gif" width="35" height="23" /><img src="<?=$pagina->localhost?>_imagens/pagIcons/aurora.gif" width="35" height="23" /><img src="<?=$pagina->localhost?>_imagens/pagIcons/hipercard.gif" width="35" height="23" /> <img src="<?=$pagina->localhost?>_imagens/pagIcons/unibanco.gif" width="35" height="23" /><img src="<?=$pagina->localhost?>_imagens/pagIcons/itau.gif" width="35" height="23" /><img src="<?=$pagina->localhost?>_imagens/pagIcons/bb.gif" width="35" height="23" /><img src="<?=$pagina->localhost?>_imagens/pagIcons/bradesco.gif" width="35" height="23" /></li>
                                            </ul>
                                          </div><?php */?>
                                          <div class="clear espaco"></div>
                                          <a href="javascript:void(0);" onclick="segue('carrinho');" class="awesome orange float-left"> Voltar </a>
                                          <a href="javascript:void(0);" onclick="segue('pagamento');" class="awesome red float-right"> Confirmar &raquo; </a>

</form>
										<div class="clear"> </div>