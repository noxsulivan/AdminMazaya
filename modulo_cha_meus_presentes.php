
	<?
	$sql = "select * from presentes where idclientes = '".$cliente->id."'";
	echo "<!-- $sql -->";
	$db->query($sql);
	if($db->rows){
	?>
	<h2>Minhas Lista de Casamento</h2>
	
						<div class="listagemProdutos">
							<?
                           $resource = $db->resourceAtual;
							while($res = $db->fetch($resource)){
								$presente = new objetoDb('presentes',$res['idpresentes']);?>
								
								<div class="produtosNoivas" id="produtosNoivas_<?=$presente->id?>">
									<div class="produtosNoivasFoto">
									<? if($presente->produtos->fotos) { ?>
									<a href="<?=$pagina->localhost."Produtos/Ver/".$presente->produtos->id?>" title="<?=normaliza($presente->produtos->produto)?>"><img src="<?=$pagina->localhost."img/".$presente->produtos->fotos[0]['id']?>/140/140" alt="<?=$presente->produtos->fotos[0]['legenda']?>" /></a>
									<? }?>
									</div>
									<div class="produtosNoivasTitulo"><a href="<?=$pagina->localhost."Produtos/Ver/".$presente->produtos->id?>" title="<?=normaliza($presente->produtos->produto)?>">Cod.: <?=$presente->produtos->codigo?><strong><?=$presente->produtos->produto?></strong></a> <?=($presente->produtos->fabricantes ? $presente->produtos->fabricantes->fabricante : "")?>
										<div class="produtosNoivasPreco">
											<? if((float)$presente->produtos->preco_promocional > 0){
												echo '<span class="overline">de R$ '.number_format($presente->produtos->preco_venda,2,",",".").'</span>';
												echo '<div class="preco">por R$ '.number_format($presente->produtos->preco_promocional,2,",",".")."</div>";
											}else{
												if((float)$presente->produtos->preco_venda > 0)
													echo '<div class="preco">por R$ '.number_format($presente->produtos->preco_venda,2,",",".")."</div>";
												else
													echo '<div class="preco">Indisponível para compra online</div>';
											}?>
										</div>
										<?
                                        if($presente->ganhados > 0){
                                            $sql = "select iditens from itens, pedidos where itens.idpedidos = pedidos.idpedidos and (pedidos.idestagios > 3 and pedidos.idestagios < 6) and idprodutos = '".$presente->produtos->id."' and idclientes = '".$cliente->id."'";
                                            $db->query($sql);
                                            echo "";
                                            if($db->rows > 0){
                                                while($_res = $db->fetch()){
                                                    $item = new objetoDb('itens',$_res['iditens']);
                                                    //pre($item);
                                                    echo "Presente de:<h3>".$item->pedidos->padrinhos."</h3> &lt;".$item->pedidos->dedicatoria."&gt;";
                                                }
                                            }else{
                                                    echo "<em>Comprado diretamente na loja</em>";
                                            }
                                        }
                                        ?>
									</div>
									<div class="produtosNoivasNumero">
                                    <ul><li>Pediu <span id="produtosNoivasQtd_<?=$presente->id?>"><?=$presente->quantidade?></span></li>
                                    <li>Ganhou <span id="produtosNoivasGnd_<?=$presente->id?>"><?=$presente->ganhados?></span></li></ul>
									</div>
									<div class="produtosNoivasFuncoes">
											<a href="<?=$pagina->localhost."img/".$presente->produtos->fotos[0]['id'].'/400'?>"
                                                title="<?=htmlentities($produto->produto)?>" alt="<?=$produto->produto?>"
                                                gcat="Imagem" gaction="ZoomIn" glabel="<?=$produto->codigo?>" gvalue="<?=$foto['id']?>"
                                                onclick="return expandir(this, {img: true})"
                                                class="bt_zoom">Ver</a>
											<a href="javascript:acrescentarLista('<?=$presente->id?>',1)" class="bt_add">Adicionar</a>
											<a href="javascript:removerItemLista('<?=$presente->id?>')" class="bt_remove">Excluir</a>
									</div>
								</div>
								<? if(++$listagemBreak%3==0){?><div class="clear"></div><? }?>
							<? }?>
						
						</div>
	<? }else{?>
        <h3>A lista de presentes de casamento ainda não está pronta, para adicionar ítens, navegue pela loja e clique no botão "Por na minha lista" quando encontrar um produto de sua escolha.</h3>
    <? }?>
	
    
    
    
    
    
    
    
	<?
	$sql = "select * from chas where idclientes = '".$cliente->id."'";
	echo "<!-- $sql -->";
	$db->query($sql);
	if($db->rows){
	?>
	<h2>Minhas Lista de Chá de Panela</h2>
	
						<div class="listagemProdutos">
							<?
                           $resource = $db->resourceAtual;
							while($res = $db->fetch($resource)){
								$presente = new objetoDb('chas',$res['idchas']);?>
								
								<div class="produtosNoivas" id="produtosNoivas_<?=$presente->id?>">
									<div class="produtosNoivasFoto">
									<? if($presente->produtos->fotos) { ?>
									<a href="<?=$pagina->localhost."Produtos/Ver/".$presente->produtos->id?>" title="<?=normaliza($presente->produtos->produto)?>"><img src="<?=$pagina->localhost."img/".$presente->produtos->fotos[0]['id']?>/140/140" alt="<?=$presente->produtos->fotos[0]['legenda']?>" /></a>
									<? }?>
									</div>
									<div class="produtosNoivasTitulo"><a href="<?=$pagina->localhost."Produtos/Ver/".$presente->produtos->id?>" title="<?=normaliza($presente->produtos->produto)?>">Cod.: <?=$presente->produtos->codigo?><strong><?=$presente->produtos->produto?></strong></a> <?=($presente->produtos->fabricantes ? $presente->produtos->fabricantes->fabricante : "")?>
										<div class="produtosNoivasPreco">
											<? if((float)$presente->produtos->preco_promocional > 0){
												echo '<span class="overline">de R$ '.number_format($presente->produtos->preco_venda,2,",",".").'</span>';
												echo '<div class="preco">por R$ '.number_format($presente->produtos->preco_promocional,2,",",".")."</div>";
											}else{
												if((float)$presente->produtos->preco_venda > 0)
													echo '<div class="preco">por R$ '.number_format($presente->produtos->preco_venda,2,",",".")."</div>";
												else
													echo '<div class="preco">Indisponível para compra online</div>';
											}?>
										</div>
										<?
                                        if($presente->ganhados > 0){
                                            $sql = "select iditens from itens, pedidos where itens.idpedidos = pedidos.idpedidos and (pedidos.idestagios > 3 and pedidos.idestagios < 6) and idprodutos = '".$presente->produtos->id."' and idclientes = '".$cliente->id."'";
                                            $db->query($sql);
                                            echo "";
                                            if($db->rows > 0){
                                                while($_res = $db->fetch()){
                                                    $item = new objetoDb('itens',$_res['iditens']);
                                                    //pre($item);
                                                    echo "Presente de:<h3>".$item->pedidos->padrinhos."</h3> &lt;".$item->pedidos->dedicatoria."&gt;";
                                                }
                                            }else{
                                                    echo "<em>Comprado diretamente na loja</em>";
                                            }
                                        }
                                        ?>
									</div>
									<div class="produtosNoivasNumero">
                                    <ul><li>Pediu <span id="produtosNoivasQtd_<?=$presente->id?>"><?=$presente->quantidade?></span></li>
                                    <li>Ganhou <span id="produtosNoivasGnd_<?=$presente->id?>"><?=$presente->ganhados?></span></li></ul>
									</div>
									<div class="produtosNoivasFuncoes">
											<a href="<?=$pagina->localhost."img/".$presente->produtos->fotos[0]['id'].'/400'?>"
                                                title="<?=htmlentities($produto->produto)?>" alt="<?=$produto->produto?>"
                                                gcat="Imagem" gaction="ZoomIn" glabel="<?=$produto->codigo?>" gvalue="<?=$foto['id']?>"
                                                onclick="return expandir(this, {img: true})"
                                                class="bt_zoom">Ver</a>
											<a href="javascript:acrescentarListaCha('<?=$presente->id?>',1)" class="bt_add">Adicionar</a>
											<a href="javascript:removerItemListaCha('<?=$presente->id?>')" class="bt_remove">Excluir</a>
									</div>
								</div>
								<? if(++$listagemBreak%3==0){?><div class="clear"></div><? }?>
							<? }?>
						
						</div>
	<? }else{?>
        <h3>A lista de presentes do chá de panela ainda não está pronta, para adicionar ítens, navegue pela loja e clique no botão "Por na minha lista" quando encontrar um produto de sua escolha.</h3>
    <? }?>