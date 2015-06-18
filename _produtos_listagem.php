<? if($pagina->acao != 'Ver' and $pagina->tg == 'Produtos'){?>
    <div class="tituloPrimario"><?
    if(count($categoria->fotos) > 0){ ?>
        <div id="categoriaTitulo" style="background-image:url(<?=$pagina->img($categoria->fotos[0]['id'].'/1920/180/1')?>)">
            <h2><?=$tituloCateg?></h2>
        </div>

<?
}else{
	echo "<h2>$tituloCateg</h2>";
}?>
  <div class="clear espaco sort">
    <ul id="sort-by" class="option-set clearfix" data-option-key="sortBy">
      <li><a href="#sortBy=original-order" data-option-value="original-order" class="selected  float-left"   onClick="_gaq.push(['_trackEvent', 'Ordenacao', 'Origina', '']);" data>Ordenar por:</a></li>
      <li><a href="#sortBy=produto" data-option-value="produto" data-option-asc="true" class=" float-left"   onClick="_gaq.push(['_trackEvent', 'Ordenacao', 'produto', '']);">Nome</a></li>
      <li><a href="#sortBy=codigo" data-option-value="codigo" data-option-asc="true" class=" float-left"   onClick="_gaq.push(['_trackEvent', 'Ordenacao', 'codigo', '']);">Codigo</a></li>
      <li><a href="#sortBy=preco" data-option-value="preco" data-option-asc="true" class=" float-left"   onClick="_gaq.push(['_trackEvent', 'Ordenacao', 'preco', 'menor']);">Menor Preco</a></li>
      <li><a href="#sortBy=preco" data-option-value="preco" data-option-asc="false" class=" float-left"   onClick="_gaq.push(['_trackEvent', 'Ordenacao', 'preco', 'maior']);">Maior Preco</a></li>
      <li><a href="#sortBy=produtosDestaquesLinha" data-option-value="produtosDestaquesLinha" class=" float-left"   onClick="_gaq.push(['_trackEvent', 'Ordenacao', 'linha', '']);">Linha</a></li>
    </ul>
  </div>
</div>
<? }?>




<div class="listagemProdutos<? echo ($estiloDC ? '' : 'Geral')?>" id="listadeProdutos">
  <? 		
	$limite = 10000;
	$indice = $_p;
	$limite = $limite ? $limite : ($estiloDC ? 25 : 100);//$pagina->configs['limite_listagem_produtos']);
	$bloco = ($_SERVER['QUERY_STRING']);
	$k = 0;
		
		if($total){
			
			$db->moveFetch( $indice ? $indice * $limite : 0 );
			$resourceListagem = $db->resourceAtual;
			while($res = $db->fetch() and ++$j <= $limite){
				$produto = new produto($res['idprodutos']);
				echo $produto->item();
				$db->resource($resourceListagem);
			}?>
	  <? if($total > $limite){?>
	  <div class="<? echo ( $k++ == 10 ? "": "produtosDestaques ").$k ?> <?=($HOME? 'pad20': 'pad2')?>" id="produtoDestaque_<?=$produto->id?>">
		<div class="produtosDestaquesFoto"> <a href="<? echo $pagina->localhost.$canal->url.($pagina->acao ? $pagina->acao : "Categorias" ).'/'.($pagina->id ? $pagina->id : "Geral" ).'/_p='.($indice + 1).'&_f='.$_f.'&_o='.$_o?>"> <img src="<?=$pagina->localhost?>_imagens/mais_produtos.jpg" alt="Próximos produtos Tramontina" /> </a> </div>
	  </div>
	  <? }?>
  <? }else{?>
  <h3>Selecione uma subcategoria</h3>
  <?
						$db->query('select idcategorias from categorias where idcategorias_2 = "'.$categoria->id.'"');
						while($res = $db->fetch()){
							  $cat = new objetoDb('categorias',$res['idcategorias']);?>
  <div class="produtosCategorias" id="produtoCategorias_<?=$cat->id?>"> <a href="<?=$pagina->localhost.$canal->url.'Categoria/'.$cat->url?>" class="awesome magenta" title="<?=normaliza($cat->categoria)?>"><img src="<?=$pagina->localhost?>_imagens/24/promotion.png" width="24" height="24" align="left" />
    <?=$cat->categoria?>
    </a> </div>
  <? }?>
  <? }?>
  <?php /*?>
<div id="bannerListagem">
        <?PHP if(!$estiloDC){
          $db->query("select * from galerias where idtipos_de_banners = 2 order by ordem");
          if($db->rows){
              $_rows = $db->rows;
              ?>
        <?PHP
                  $i = 1;
                  while($res = $db->fetch()){
                      $obj = new objetoDb('galerias',$res['idgalerias']);
                      foreach($obj->fotos as $foto){ ?>
        <a href="<?PHP echo $obj->link?>"  onClick="_gaq.push(['_trackEvent', 'Banner', 'Click', '<?PHP echo $obj->referencia?>']);"> <img src="<?PHP echo $pagina->img($foto['id'].'/988/30/1')?>" /> </a>
        <?PHP
                      }
                  }
              }
      }?>
</div><?php */?>
</div>








<div class="clear">
  <?
						if($total > $limite){
								if($indice > 0) $titulo = $titulo." &raquo; Página ".($indice + 1);
								echo '<div id="navegacao"><h3>Páginas:</h3>';
								if($indice > 3) echo '<a href="'.$pagina->localhost.$canal->url.$pagina->acao.'/'.$pagina->id.'/0" class="awesome blue">1 &laquo;</a>...';
								
								for($p = ( $indice > 2 ? $indice-2 : 0) ; $p < $indice+3 and $p < (($total/$limite)); ++$p){
								//for($p = 0 ; $p < ($total/$limite); ++$p){
								
									echo '<a href="'.$pagina->localhost.$canal->url.($pagina->acao ? $pagina->acao : "Categorias" ).'/'.($pagina->id ? $pagina->id : "Geral" ).'/_p='.$p.'&_f='.$_f.'&_o='.$_o.'" class="awesome '.( $indice == $p ? " grey":' orange').'">'. ($p+1) .'</a>';
								}
								
								//if($indice < intval($total/$limite) - 3) echo '<a href="'.$pagina->localhost.$canal->url.$pagina->acao.'/'.$pagina->id.'/_p='.$p.'&_f='.$_f.'&_o='.$_o.'" class="awesome blue'.( $indice == $p ? " strong":'').'">&raquo; '.(intval($total/$limite) + 1).'</a>';
								
								echo '</div>';
						}?>
</div>







<div class="clear">
  <? if(isset($_itensVisitados) and count($_itensVisitados)){?>
  <h3 class="tituloSecundario awesome grey">Você já olhou os seguintes produtos</h3>
  <div class="listagemProdutosMini clear">
    <? for($i=1; $i <=6 and $_id = next($_itensVisitados);$i++){
		$produto = new objetoDb('produtos',$_id);?>
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
      <? if($cadastro->conectado()){
				if((float)$produto->preco_promocional > 0){
						echo 'R$ '.$produto->preco_promocional;
				}elseif((float)$produto->preco_venda > 0){
						echo 'R$ '.$produto->preco_venda;
				}else{
						echo 'Indisponível';
				}
			}?>
      <div class="produtosMiniTexto icone"> <a href="<?=$pagina->localhost.$canal->url."Ver/".$produto->id?>" alt="<?=$imgs[legenda]?>">
        <?
      $_pro = explode("-",$produto->produto);
	  echo $_pro[0];
	  ?>
        </a> </div>
    </div>
    <? }?>
  </div>
  <? }?>
</div>
