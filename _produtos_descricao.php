
    <div id="corpo"><?

//if(!$cache->get("Descricao_".$pagina->id)){
	
	
$cat = array();
$produto = new objetoDb('produtos',$pagina->id);
$pro = $produto;
		if((float)$produto->preco_promocional){
			$preco = $produto->preco_promocional;
		}else{
			$preco = $produto->preco_venda;
		}
		
if(isset($_SESSION['itensVisitados'])){
	$_itensVisitados = array_unique($_SESSION['itensVisitados']);
	if($_SESSION['itensVisitados'][0] != $produto->id){
		array_unshift($_SESSION['itensVisitados'],$produto->id);
	}
}
	
	
$desconto_vista = $pagina->configs['desconto_boleto'];

if(count($produto->categorias)){
	foreach($produto->categorias as $c){
		if($c->id == 33){
			$_DC = true;
		}
		switch($c->categorias->id){
			case 33: $estiloDC = true; break;
			case 41: $categoriaPanelas = $c->categoria;  $categoriaPanelasUrl = $c->url; $categoriaDesconto = $c->desconto_vista; break;
			case 85: $categoriaPanelas = $c->categoria;  $categoriaPanelasUrl = $c->url; $categoriaDesconto = $c->desconto_vista; break;
		}
		if($c->categorias->id == 48 or $c->id == 48){
			$lancamento = true;
		}
		
//		if($c->desconto_vista){
//			$categoriaDesconto = $c->categoria;  $catPanelasUrl = $c->url; $catDesconto = $c->desconto_vista;
//		}
		
//		$desconto_vista = max($desconto_vista,$c->desconto_vista);
		
		$cat[] = $c->id;
		$cat_nomes[] = $c->categoria;
	}
}

if($produto->ativo != 'sim'){?>
<h2>
  <?=$produto->produto?>
  <?
					if($produto->vendas > 2){
								echo '<br><img src="'.$pagina->localhost.'_imagens/1334719819_rosette.png" width="32" height="32" align="left" />Top de Vendas';
					}
  ?>
</h2>
<small>Código:
<?=$produto->codigo?>
</small>
<h3>Este produto encontra-se indisponível ou no momento.</h3>
<?
}else{
$titulo = $produto->produto.' '.$produto->fabricantes->fabricante.' Cod:'.$produto->codigo;

?>
  <?
	if($produto->vendas > 2){
				echo '<h3 STYLE="height:32px; line-height:32px; margin:0px;"><img src="'.$pagina->localhost.'_imagens/1334719819_rosette.png" width="32" height="32" align="left" />Top de Vendas</h3>';
	}
  ?>
<h2>
  <?=normaliza(preg_replace("/".$produto->linhas->linha."/i","", preg_replace("/ - /i"," ", $produto->produto.' - '.$produto->fabricantes->fabricante)))?>
  <?=($produto->idlinhas > 1 ? ' <a href="'.$pagina->localhost.$canal->url.'Linha/'.$produto->linhas->url.'"> &raquo; '.$produto->linhas->linha.'</a>': "")?>
</h2>
<h3>Código:
  <?=$produto->codigo?>
</h3>
<?php /*?><img src="<?=$pagina->localhost?>_imagens/logo_tramontina.png" width="150" height="30" alt="Tramontina" />
<?php */?>








<div class="boxPrincipal">
    
    <div class="funcoes">
      <? include('_produtos_descricao_funcoes.php');?>
    </div>
  <div class="Produto clear">
  
  
  <div class="fotosProduto">
  
						<? if($produto->video and $produto->video != 'http://www.'){?>
  
    
                        <div class="tabs">
                            <ul>
                                <li><a href="#tabsImagens">Imagens</a></li>
                                <li><a href="#tabsVideo">Video</a></li>
                            </ul>
                            <div id="tabsImagens">
                                            <? if(count($produto->fotos)){?>
                                            <? $_imagem = $pagina->img($produto->fotos[0]['id'].'/800/600');?>
                                              <div id="fotoGrd"> <a href="<?=$pagina->img($produto->fotos[0]['id'].'/800/600')?>" title="<?=htmlentities($produto->produto)?>" alt="<?=$produto->produto?>" id="fotoGrd<?=$produto->fotos[0]['id']?>" class="nivoZoom" rel="example_group" onclick="_gaq.push(['_trackEvent', 'Imagens', 'Zoom', '<?=$obj->referencia?>']);">
                                              <img id="itemGrd_<?=$produto->fotos[0]['id']?>" src="<?=$pagina->img($produto->fotos[0]['id'].'/453/350/1')?>" alt="<?=$produto->fotos[0]['legenda']?>" title="<?=$produto->fotos[0]['legenda']?>" /> <small><img src="<?=$pagina->localhost?>_imagens/1279120862_zoom_in_32.ico" width="24" height="24" class="zoom" /> Clique para Ampliar</small> </a> </div>
                                              <? if(count($produto->fotos) > 1){?>
                                                  <? foreach($produto->fotos as $foto){//if(++$jfpqn>6) break; ?>
                                                  <a href="<?=$pagina->img($foto['id'].'/800/600')?>" id="fotoPqn_<?=$foto['id']?>" class="nivoZoom" rel="example_group" title="<?=htmlentities($produto->referencia)?>" alt="<?=$produto->referencia?>" onclick="_gaq.push(['_trackEvent', 'Imagens', 'Zoom', '<?=$obj->referencia?>']);">
                                                  <?
                                                                            if($foto['width'] > $foto['height']){
                                                                                $h = intval(($foto['height'] * 100) / $foto['width']);
                                                                                $t = intval(50 - ($h/2));
                                                                            }else{
                                                                                $h = 100;
                                                                                $t = 0;
                                                                            }?>
                                                  <img style="margin-top:<?=$t?>px" id="itemPqn_<?=$foto['id']?>" src="<?=$pagina->img($foto['id'].'/100/100/1/0/'.diretorio($foto['id'].'-'.$produto->referencia).".jpg")?>" title="<?=htmlentities($foto['legenda'])?>" alt="<?=$foto['legenda']?>" /> </a>
                                                  <? }?>
                                              <? }?>
                                            <? }?>
                                            <div class="clear espaco"></div>
                            </div>
                            <div id="tabsVideo">
                            
                                            <iframe width="564" height="412" src="<?=$produto->video?>" frameborder="0" allowfullscreen></iframe>
                                            <div class="clear espaco"></div>
                            </div>
                        </div>
  
						<? }else{ ?>
						<? if(count($produto->fotos)){?>
						<? $_imagem = $pagina->img($produto->fotos[0]['id'].'/800/600');?>
						  <div id="fotoGrd"> <a href="<?=$pagina->img($produto->fotos[0]['id'].'/1082/')?>" title="<?=htmlentities($produto->produto)?>" alt="<?=$produto->produto?>" id="fotoGrd<?=$produto->fotos[0]['id']?>" class="fancybox" rel="produto">
                          <img id="itemGrd_<?=$produto->fotos[0]['id']?>" src="<?=$pagina->img($produto->fotos[0]['id'].'/453/400/')?>" alt="<?=$produto->fotos[0]['legenda']?>" title="<?=$produto->fotos[0]['legenda']?>" /></a> </div>
						  <? if(count($produto->fotos) > 1){?>
							  <? foreach($produto->fotos as $foto){//if(++$jfpqn>6) break; ?>
							  <a href="<?=$pagina->img($foto['id'].'/1082/')?>" id="fotoPqn_<?=$foto['id']?>" class="fancybox" rel="produto" title="<?=htmlentities($produto->referencia)?>" alt="<?=$produto->referencia?>">
							  <?
														if($foto['width'] > $foto['height']){
															$h = intval(($foto['height'] * 100) / $foto['width']);
															$t = intval(50 - ($h/2));
														}else{
															$h = 100;
															$t = 0;
														}?>
							  <img style="margin-top:<?=$t?>px" id="itemPqn_<?=$foto['id']?>" src="<?=$pagina->img($foto['id'].'/80/80/1/0/'.diretorio($foto['id'].'-'.$produto->referencia).".jpg")?>" title="<?=htmlentities($foto['legenda'])?>" alt="<?=$foto['legenda']?>" /> </a>
							  <? }?>
						  <? }?>
						<? }?>
						<div class="clear espaco"></div>
						<? }?>
  
  </div>
  <div class="dadosProduto">
    
    <? if($produto->brindes->id){?>
						<? $brinde = new objetoDb("produtos",$produto->brindes->id);?>
						<? $_imagem = $pagina->img($brinde->fotos[0]['id'].'/800/600');?>
						<div id="brinde">
						<h3>Brinde: <?=$brinde->produto?></h3>
						<?
						//echo  ( $produto->brindes->descricao_curta	?	"<p>".nl2br(preg_replace("/(\d\. )/i","<br><strong>\\1</strong>",($produto->brindes->descricao_curta)))."</p>": "" );
						?>
						  <? if(count($brinde->fotos) > 0){?>
						  <div id="fotoPqnBrd">
							  <?
								echo '<div class="produtosDestaquesBoxBrinde">Ganhe um Brinde!</div>';
							  ?>
							  <? foreach($brinde->fotos as $foto){//if(++$jfpqn>6) break; ?>
							  <a href="<?=$pagina->img($foto['id'].'/800/600')?>" id="fotoPqn_<?=$foto['id']?>" class="imgZoom" rel="example_group" title="<?=htmlentities($brinde->referencia)?>" alt="<?=$brinde->referencia?>" onclick="_gaq.push(['_trackEvent', 'Imagens', 'Zoom', '<?=$brinde->referencia?>']);">
							  <?
														if($foto['width'] > $foto['height']){
															$h = intval(($foto['height'] * 100) / $foto['width']);
															$t = intval(50 - ($h/2));
														}else{
															$h = 100;
															$t = 0;
														}?>
							  <img style="margin-top:<?=$t?>px" id="itemPqn_<?=$foto['id']?>" src="<?=$pagina->img($foto['id'].'/100/100/0/0/'.diretorio($foto['id'].'-'.$brinde->referencia).".jpg")?>" title="<?=htmlentities($foto['legenda'])?>" alt="<?=$foto['legenda']?>" /> </a>
							  <? }?>
							  <?
							  echo  ( $produto->brindes->descricao_curta	?	"<p>".preg_replace("/(\d\. )/i","<br><strong>\\1</strong>",($produto->brindes->descricao_curta))."</p>": "" );
							  ?>

						  </div>
						  <? }?>
						</div>
    <? }?>
    
    
    
    <h3>Disponibilidade: <?
		if((float)$produto->preco_venda > 0){
			if($produto->prazo_entrega)
				echo $produto->prazo_entrega." dias";
			else
				echo "Em estoque";
		}else{
				echo "Em falta";
		}
		?> </h3>
    
	
    <div class="social">
      
      <iframe src="http://platform.twitter.com/widgets/tweet_button.html?url=http://www.mesacor.com.br&related=mesacor"
            allowtransparency="true" frameborder="0" scrolling="no" style="width:90px; height:21px;" class="float-left"></iframe>
      
      <iframe src="https://www.facebook.com/plugins/like.php?href=http://www.facebook.com/Mesacor&amp;layout=button_count&amp;show_faces=false&amp;width=1000&amp;send=false&amp;action=like&amp;font=tahoma&amp;colorscheme=light&amp;height=35"
            scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:110px; height:21px;" allowtransparency="true" class="float-left"></iframe>
      
      <iframe src="https://plusone.google.com/_/+1/fastbutton?url=http%3A%2F%2Fwww.mesacor.com.br%2F&amp;size=medium&amp;count=true&amp;hl=pt-BR&amp;jsh=m%3B%2F_%2Fapps-static%2F_%2Fjs%2Fgapi%2F__features__%2Frt%3Dj%2Fver%3D_MgyuzqnOvs.pt_BR.%2Fsv%3D1%2Fam%3D!VFq0t5HlHACIpDQvcw%2Fd%3D1%2Frs%3DAItRSTMoJBaGbFxQlnP6MBoZWgLnMhLcmQ#id=I1_1333660979220&amp;parent=http%3A%2F%2Fwww.mesacor.com.br&amp;rpctoken=616010782&amp;_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe%2C_renderstart"
      		style="position: static; left: 0px; top: 0px; width: 80px; margin: 0px; border-style: none; height: 21px; visibility: visible;" src="" name="I1_1333660979220" marginwidth="0" marginheight="0" id="I1_1333660979220" hspace="0" allowtransparency="true"></iframe>
      
      <a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.mesacor.com.br/Produtos/Ver/<?=$pro->id?>/<?=$pro->url?>&amp;utm_source=Pinterest&media=<?=urlencode($pagina->img($produto->fotos[0]['id'].'/800/600'))?>&description=<?=urlencode(utf8_encode($produto->produto." ".$produto->linhas->linha." Tramontina ".$produto->codigo))?>" class="pin-it-button" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
    </div>
		
	<div class="tabs">
		<ul>
			<li><a href="#tabs-1">Características</a></li>
			<li><a href="#tabs-2">Mais Detalhes</a></li>
			<li><a href="#tabs-3">Ficha Técnica</a></li>
		</ul>
		<div id="tabs-1">
						<?
						echo  ( $produto->descricao_curta	?	"<p>".nl2br(preg_replace("/(\d\. )/i","<br><strong>\\1</strong>",($produto->descricao_curta)))."</p>": "" );
						$descricao_curta = strip_tags(nl2br(preg_replace("/(\d\. )/i","",($produto->descricao_curta))));
						?>
		</div>
		<div id="tabs-2">
						<p><? echo nl2br($pro->descricao_longa); ?></p>
		</div>
		<div id="tabs-3">
					<p><? echo preg_replace("/(kW)/i","\\1<br>",
											   preg_replace("/(EAN13(<\/strong>)*: [0-9]*)!(<br>)/i","\\1<br>",
																	preg_replace("/({Dimensões(.?)|Dimensões Embalagem \(Compr\. X Larg\. X Alt\.\)|Dimensões Produto \(Compr\. X Larg\. X Alt\.\)|Quantidade de peças|Diâmetro|Freqüência|Tensão \(bi-volt automático\)|Potência dos queimadores|Espessura|Dimensão do corte de instalação \(L x P\)|Metragem|Peso|NCM|DUN14|EAN13|Material|Composição|Garantia|Capacidade|Cabo e pegador|Manual})/i","<br><strong>\\1</strong>",
																							preg_replace("/(Dimensões \(Compr. X Larg. X Alt.\):)/i","<strong>\\1</strong>",
																													preg_replace("/(Manual:)/i","<br><strong>\\1</strong>",
																																	   strip_tags(nl2br(normaliza($pro->ficha_tecnica)))))))); ?></p>
	
		







		<h4 class="clear espaco">Consulte nosso atendimento para saber mais informações sobre este produto.</h4>
		</div>
	</div>
    
    

	<? //if($hotsite){?>
      <div id="fb-root"></div>
		<script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=244455668952934";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        
        <div class="fb-comments" data-href="<?=$pagina->localhost.'Especial/Ver/'.$pagina->id.'/'.$produto->url?>" data-num-posts="10" data-width="453"></div>
	<? //}?>

</div>

<div class="clear espaco"></div>
<?
                        $cat = array_flip(@array_flip($cat));
                        $cats_nomes = implode(", ",array_flip(@array_flip($cat_nomes)));
						
						foreach($cat as $c){
							$cats[] = "produtos_has_categorias.idcategorias = ".$c;
						}
						
						if(is_array($cats) and !$estiloDC){
							$sql = "
							select
								produtos.*
							from
								produtos, produtos_has_categorias
							where
								 (".implode(" or ",$cats).")  and
								produtos.idprodutos = produtos_has_categorias.idprodutos
							group by produtos.idprodutos
							order by rand()
							limit 15";
							$db->query($sql);
							$total = $db->rows;
							//include('_produtos_listagem.php');
						}
						?>
<h3 class="tituloSecundario awesome grey">Outros produtos: <?=$cats_nomes;?></h3>
                        
                        <div class="listagemProdutosMiniBox">
                          <div class="listagemProdutosMini clear">
							<? while($res = $db->fetch() and ++$j <= 15){
								$produto = new produto($res['idprodutos']);?>
							<div class="produtosMini" id="produtoDestaque_<?=$produto->id?>">
							  <div class="produtosMiniFoto">
								<? if($produto->fotos) { ?>
								<a href="<?=$pagina->localhost.$canal->url."Ver/".$produto->id?>" alt="<?=$imgs[legenda]?>">
								<?
									$foto = $produto->fotos[0];
									if($foto['width'] > $foto['height']){
										$h = intval(($foto['height'] * 100) / $foto['width']);
										$t = intval(50 - ($h/2));
									}else{
										$h = 100;
										$t = 0;
									}?>
								<img src="<?=$pagina->localhost."img/".$produto->fotos[0]['id']?>/100/100" alt="<?=$produto->fotos[0]['legenda']?>" style="margin-top:<?=$t?>px" /></a>
								<? } ?>
							  </div>
							  <div class="produtosMiniTexto icone"> <a href="<?=$pagina->localhost.$canal->url."Ver/".$produto->id?>" alt="<?=$imgs[legenda]?>">
								<?
							  $_pro = explode("-",$produto->produto);
							  echo $_pro[0];
							  ?>
								</a> </div>
							</div>
							<? }?>
						  </div>
							<div class="clear"></div>
							<a class="prev" id="foo2_prev" href="#"><span>anteriores</span></a>
							<a class="next" id="foo2_next" href="#"><span>próximos</span></a>
						  </div>

                        
                        
<div class="clear"></div>


						<? }?>


    
    
  </div>
</div>

    </div>
