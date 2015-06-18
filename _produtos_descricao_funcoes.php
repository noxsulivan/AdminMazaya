      
      <? if((float)$produto->preco_venda > 0){ ?>
          <div class="funcoesProduto comprasegura">
          <? if($convidado->id){ ?>
            <a href="<?=$pagina->localhost?>Cliente/Carrinho/<?=$produto->id?>-<?=$convidado->id?>" class="awesome orange"> <h1><img src="<?=$pagina->localhost?>_imagens/botao2013_comprar.png" width="36" height="36" alt="Compre este produto" />Presentear</h1> </a>
          <? }?>
            <a href="<?=$pagina->localhost?>Cliente/Carrinho/<?=$produto->id?>" class="awesome orange"> <h1><img src="<?=$pagina->localhost?>_imagens/botao2013_comprar.png" width="36" height="36" alt="Compre este produto" />Comprar</h1> </a>
            <a href="https://sslanalyzer.comodoca.com/?url=www.mesacor.com.br" target="_blank" class="compraseguraLink">Clique aqui para conferir autenticidade </a>

      <? //}elseif($convidado->id){ ?>
          <? //}else{?>
          		<?php /*?><h3 class="awesome grey"><img src="<?=$pagina->localhost?>_imagens/botao2013_atualizar.png" width="36" height="36" alt="Compre este produto" />Para visualizar o preço deste produto, selecione o casal a quem você deseja presentear</h3><?php */?>         <? //}?>
          
          </div>
	  <? }?>
    
           <? if($cliente->id){ ?>
              <a href="javascript:adicionarListaPresentes(<?=$produto->id?>,1);" class="awesome grey"> <img src="<?=$pagina->localhost?>_imagens/1279945747_bag_green.png" width="26" height="26" align="left" /> Por na minha lista de Casamento </a>
              <a href="javascript:adicionarListaChas(<?=$produto->id?>,1);" class="awesome grey"> <img src="<?=$pagina->localhost?>_imagens/1279945747_bag_green.png" width="26" height="26" align="left" /> Por na minha lista de Chá de Panela </a>
          <? }?>
          
          
    <? //if($cliente->id || $convidado->id){?>
    
      <div class="funcoesProduto">
        <?
                                if((float)$produto->preco_venda > 0){ //and $produto->quantidade > 0
                                    if((float)$produto->preco_promocional > 0){
                                        echo number_format(100 - ($produto->preco_promocional *100 /$produto->preco_venda) ,2,",",".").'% de desconto<br>';
										
                                        echo '<h3><span class="overline">de: R$ '.number_format($produto->preco_venda,2,",",".").' por:</span><br>';
										echo '<span class="precoGrd">R$ '.number_format($produto->preco_promocional,2,",",".")."</span></h3>";
										
										$preco = $produto->preco_promocional;
										
										if((float)$produto->desconto > 1){
                                        	echo '<h3>&raquo;  à vista no boleto por:</h3>';
											echo '<span class="precoGrd">R$ '.number_format($produto->preco_promocional - ($produto->preco_promocional * $produto->desconto/100) ,2,",",".")."</span><br>";
											echo 'com + '.$produto->desconto.'% de desconto<br>';
                                        	echo 'Economize R$ '.number_format($produto->preco_venda-($produto->preco_promocional - ($produto->preco_promocional * $produto->desconto/100) ),2,",",".").'</span></h3>';
										}elseif($desconto_vista > 1){
                                        	echo '<h3>&raquo; à vista no boleto por:</h3>';
											echo '<span class="precoGrd">R$ '.number_format($produto->preco_promocional - ($produto->preco_promocional * $pagina->configs['desconto_boleto']/100) ,2,",",".")."</span><br>";
											echo 'com + '.$desconto_vista.'% de desconto<br>';
                                        	echo 'Economize R$ '.number_format($produto->preco_venda-($produto->preco_promocional - ($produto->preco_promocional * $desconto_vista/100) ),2,",",".").'</span></h3>';
										}

										for( $i = 2 ; $i <= $pagina->configs['parcelas'] and $produto->preco_promocional/$i > $pagina->configs['parcela_minina']; $i++) 
												  $pre = $produto->preco_promocional / $i;
                                        echo '<h3>';
										if($i > 2){
										echo '&raquo; ou em até '.--$i.'x sem juros de<br>';
                                        echo '<span class="precoGrd">R$ '.number_format($pre,2,",",".")."</span><br>";
										}
										
                                    }else{
                                        echo '<h3 class="precoGrd">R$ '.number_format($produto->preco_venda,2,",",".")."</h3>";
										for( $i = 2 ; $i <= $pagina->configs['parcelas'] and $produto->preco_venda/$i > $pagina->configs['parcela_minina']; $i++) 
												  $pre = $produto->preco_venda / $i;
										$preco = $produto->preco_venda;
												  
                                        echo '<h3>';
										if($i > 2){
										echo '&raquo; ou em até '.--$i.'x sem juros de<br>';
										
                                        echo '<span class="precoGrd">R$ '.number_format($pre,2,",",".")."</span><br>";
										}
										
										if((float)$produto->desconto > 1){
                                        	echo '<h3>&raquo; à vista no boleto por:</h3>';
											echo '<span class="precoGrd">R$ '.number_format($produto->preco_venda - ($produto->preco_venda * $produto->desconto/100) ,2,",",".")."</span><br>";
											echo 'com + '.$produto->desconto.'% de desconto<br>';
                                        	echo 'Economize R$ '.number_format($produto->preco_venda-($produto->preco_venda - ($produto->preco_venda * $produto->desconto/100) ),2,",",".").'</span></h3>';
										}elseif($desconto_vista > 1){
                                        	echo '<h3>&raquo; à vista no boleto por:</h3>';
											echo '<span class="precoGrd">R$ '.number_format($produto->preco_venda - ($produto->preco_venda * $pagina->configs['desconto_boleto']/100) ,2,",",".")."</span><br>";
											echo $desconto_vista.'% de desconto<br>';
                                        	echo 'Economize R$ '.number_format($produto->preco_venda-($produto->preco_venda - ($produto->preco_venda * $desconto_vista/100) ),2,",",".").'</span></h3>';
										}
                                    }
									
                                }else{
                                        echo '<h3 class="awesome grey"><img src="'.$pagina->localhost.'_imagens/botao2013_atualizar.png" width="36" height="36" alt="Compre este produto" />Indisponível online<br>Visite nossa loja física.</h3>';

                                }?>
      </div>
      
      
      <? //}?>
    
    <div id="lista" style="display: <?=$_SESSION['itensLista'] ? 'none' : 'none';?>">
      <h3>Lista de Casamento</h3>
      <ul id="listaLista" class="listaFuncoes">
        <? //echo carregaListaMini();?>
      </ul>
      <a href="<?=$pagina->localhost?>Lista-de-Casamento/Meus-Presentes" class="awesome orange"> Abrir lista completa </a> </div>
      
      
      
      
      
    <div id="listaChas" style="display: <?=$_SESSION['itensLista'] ? 'none' : 'none';?>">
      <h3>Lista de Chá de Panela</h3>
      <ul id="listaListaChas" class="listaFuncoes">
        <? //echo carregaListaMini();?>
      </ul>
      <a href="<?=$pagina->localhost?>Cha-de-Panela/Meus-Presentes" class="awesome orange"> Abrir lista completa </a> </div>
      
      
      
      
      
    <div id="carrinho" style="display: <?=$_COOKIE['pedidoID'] ? 'block' : 'none';?>">
      <h3>Carrinho</h3>
      <ul id="carrinhoLista" class="listaFuncoes">
        <? echo carregaCarrinhoMini();?>
      </ul>
      <a href="<?=$pagina->localhost?>Cliente/Pedido" class="awesome orange"> Encerrar pedido </a> </div>
      
      
      
    <? if(count($produto->variacoes)){ ?>
    <div class="variacoesProduto">
      <h3>Opções disponíveis </h3>
      <ul class="listaFuncoes">
        <?
          foreach($produto->variacoes as $variacao) {
			  ?>
        <li>
          <label>
            <input name="variacao" value="<?=$variacao->id?>" type="radio" onchange="marcaVariacao(<?=$produto->id?>,<?=$variacao->id?>)" />
            <span style="color:#<?=$variacao->opcoes->hexa?>" ><img src="<?=$pagina->localhost.'img/'.$variacao->opcoes->fotos[0]['id']?>" width="24" align="left" />
            <?=$variacao->opcoes->opcao?>
            </span></label>
        </li>
        <? }?>
      </ul>
    </div>
    <? }?>
	
    

    <? if((float)$produto->preco_venda > 0){?>
    <div class="parcelamentoProduto">
      <h3>Simulação do frete</h3>
      CEP:
      <input id="consultaCEPInput" value="" class="inputQtd" alt="cep" />
      <a href="javascript:consultaFrete($('#consultaCEPInput').val(),'<?=$produto->peso?>@<?=$produto->peso_volumetrico?>@<?=$preco?>@<?=$produto->dimensoes?>')" class="awesome orange" id="botaoConsulta"> ok </a>
      <h3 id="resultFrete"></h3>
      <input id="totalGeral" type="hidden" />
      <input id="subTotal" type="hidden" />
      <strong>Para promoções de frete grátis, visualize no carrinho</strong></div>
    <? }?>
    