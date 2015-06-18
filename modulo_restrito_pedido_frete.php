<?php /*?><ol id="passos" class="clear"><li>1. Carrinho</li>
<li>2. Entrega</li>
<li class="current">3. Frete</li>
<li>4. Forma de pagamento</li>
<li>5. Confirmação</li>
</ol><?php */?>


<form action="<?=$pagina->localhost.$canal->url.$pagina->acao?>" method="post" id="carrinhoForm" >
<?
foreach($_POST as $k=>$v){
	echo '
	<input type="hidden" name="'.$k.'" value="'.htmlentities($v).'" />';
}
?>
                      <h2>Forma de Entrega - Frete via Correios</h2>
                      <div id="formatoFrete">
                        <?
                        //echo @atualizaFrete($cadastro->cep,$pesoTotal);
                        echo escolheFrete($_REQUEST['cep'],$_REQUEST['pesoTotal']);
                        ?>
                      </div>
                    <div class="clear espaco"></div>
                    <a href="javascript:void(0);" onclick="segue('dados');" class="awesome orange float-left"> Voltar </a>
                    <a href="javascript:void(0);" onclick="segue('formas_pagamento');" class="awesome red float-right"> Próximo Passo &raquo; Forma de Pagamento &raquo;</a>

</form>