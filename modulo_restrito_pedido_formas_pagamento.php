<?php /*?><ol id="passos" class="clear"><li>1. Carrinho</li>
<li>2. Entrega</li>
<li>3. Frete</li>
<li class="current">4. Forma de pagamento</li>
<li>5. Confirma��o</li>
</ol><?php */?>


<form action="<?=$pagina->localhost.$canal->url.$pagina->acao?>" method="post" id="carrinhoForm" >

<?
foreach($_POST as $k=>$v){
	echo '
	<input type="hidden" name="'.$k.'" value="'.($v).'" />';
}
?>                                          <h2>Forma de pagamento</h2>
                                          <div class="content24">
                                            <h3>Cart�es de Cr�dito (� Vista ou Parcelado)</h3>
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
                                            <h3>Simula��o de parcelamento *</h3>
                                            <ul id="parcelamento">
											  <?=escolheParcelamento($_REQUEST['frete'.$_REQUEST['tipo_frete']],$_REQUEST['total'])?>
                                            </ul>
                                            * Parcela m�nima de R$ 30,00<br />
                                            ** Em caso de d�vida, <a href="<?=$pagina->localhost.$canal->url?>">atualize</a> a p�gina para que a simula��o seja recalculada.
                                            <small>Nos pedidos parcelados no cart�o de cr�dito, observe o valor de seu limite. Ele deve ser maior que o valor total deste pedido, n�o importando o valor de cada parcela.</small> </div>
                                          <div class="pagamento clear">
                                            <h3>Boleto (� vista com <?=$pagina->configs['desconto_boleto']?>% de desconto)</h3>
                                            <ul>
                                              <li>
                                                <input type="radio" name="metodo" value="ITAUSHOPLINE" />
                                                <img src="<?=$pagina->localhost?>_imagens/pagIcons/boleto.gif" width="35" height="23" /> </li>
                                            </ul>
                                          </div>
                                          Op��o v�lida apenas para pagamento � vista. Acrescente 3 dias �teis ao prazo de entrega informado no site, pois esse � o prazo m�ximo estipulado pelo banco para disponibilizar a confirma��o de pagamento. Se voc� pagar o boleto com cheque, acrescente 4 dias �teis.
                                          Sendo assim n�o � preciso entrar em contato via telefone ou enviar-nos qualquer notifica��o sobre o pagamento realizado.
<?php /*?>                                          <div class="pagamento clear">
                                            <h3>D�bito Online/TEF</h3>
                                            <ul>
                                              <li>
                                                <input type="radio" name="metodo" value="PAGSEGURO" />
                                                <img src="<?=$pagina->localhost?>_imagens/pagIcons/pagseguro.gif" width="35" height="23" /> Pagseguro<br />
                                                <small>Atrav�s desta op��o, oferecemos pagamento atrav�s de boleto e bankline do Bradesco, Banco do Brasil, Ita�, Unibanco, Banco Real e Banrisul.</small><br />
                                                <img src="<?=$pagina->localhost?>_imagens/pagIcons/amex.gif" width="35" height="23" /><img src="<?=$pagina->localhost?>_imagens/pagIcons/aurora.gif" width="35" height="23" /><img src="<?=$pagina->localhost?>_imagens/pagIcons/hipercard.gif" width="35" height="23" /> <img src="<?=$pagina->localhost?>_imagens/pagIcons/unibanco.gif" width="35" height="23" /><img src="<?=$pagina->localhost?>_imagens/pagIcons/itau.gif" width="35" height="23" /><img src="<?=$pagina->localhost?>_imagens/pagIcons/bb.gif" width="35" height="23" /><img src="<?=$pagina->localhost?>_imagens/pagIcons/bradesco.gif" width="35" height="23" /></li>
                                            </ul>
                                          </div><?php */?>
                                          <div class="clear espaco"></div>
                                          <a href="javascript:void(0);" onclick="segue('carrinho');" class="awesome orange float-left"> Voltar </a>
                                          <a href="javascript:void(0);" onclick="segue('pagamento');" class="awesome red float-right"> Confirmar &raquo; </a>

</form>
										<div class="clear"> </div>