<?
//if($convidado->id || $cadastro->conectado()){ 
//if($convidado->id){ 
?>
<div class="tituloPrimario">
	<h3>Lista de presentes</h3>
	<h2><?=$convidado->nome_noiva.' '.$convidado->sobrenome_noiva.' &amp; '.$convidado->nome_noivo.' '.$convidado->sobrenome_noivo?><br />
    Dia do casamento: <?=$convidado->data_casamento?></h2>
	<?
	
	
	$sql = "select presentes.* from presentes where idclientes = '".$convidado->id."'";
	
	$sql = "
	select
		presentes.*,
		if(preco_promocional > 0 , 100 - (preco_promocional *100 / preco_venda),0) as c, 
		if(preco_venda > 0 , 1,0) as d
	from
		presentes, produtos
	where
		presentes.idprodutos = produtos.idprodutos and idclientes = '".$convidado->id."'
	order by c desc, d desc, codigo";
	
	$sql = "
	select
		presentes.*,
		if(preco_venda > 0 , 1,0) as d
	from
		presentes, produtos
	where
		presentes.idprodutos = produtos.idprodutos and idclientes = '".$convidado->id."'
	order by d desc, codigo";
	
	
	$db->query($sql);
	
	
						$total = $db->rows;
	?>
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
			
				$presente = new objetoDb('presentes',$res['idpresentes']);
				$produto = $presente->produtos; ?>
				<?
				


										
												
												
												if((float)$produto->preco_promocional){
													$preco = $produto->preco_promocional;
												}else{
													$preco = $produto->preco_venda;
												}
												
												
											$ret = '<div class="produtosDestaques pad20"
														id="produtoDestaque_'.$produto->id.'"
														data-produto="'.$produto->produto.'"
														data-codigo="'.$produto->codigo.'"
														data-preco="'.$preco.'"
														data-linha="'.$produto->linhas->linha.'">';
								  
								  
								  
								  
											$desconto_vista = 0;
											
											if(count($produto->categorias)){
												foreach($produto->categorias as $c){
													if($c->id == 33){
														$_DC = true;
													}
													if($c->id == 48){
														$lancamento = true;
													}
													if($c->desconto_vista){
														$categoriaDesconto = $c->categoria;  $catPanelasUrl = $c->url; $catDesconto = $c->desconto_vista;
													}
													
													$desconto_vista = max($desconto_vista,$c->desconto_vista);
												}
											}
											
												$ret .= '<div class="produtosDestaquesFoto">';
													if($produto->fotos) {
														$ret .= '<a href="'.$pagina->localhost.$canal->url."Ver/".$produto->id."/".$produto->url.'" title="'.normaliza($produto->produto).'">';
														$foto = $produto->fotos[0];
														
														if(!$foto['height']){
															preg_match_all('/width="(.+?)" height="(.+?)"/',$foto[dim],$matches); $foto['width']	=	$matches[1][0]; $foto['height']	=	$matches[2][0];
														}
														
														
														if($foto['width'] > $foto['height']){
															$h = intval(($foto['height'] * 228) / $foto['width']);
															$t = intval(124 - ($h/2));
														}else{
															$h = 228; $t = 0;
														};
														$ret .= '<img src="'.$pagina->img($produto->fotos[0]['id'].'/228/228').'" alt="'.$produto->fotos[0]['legenda'].'" style="margin-top:'.$t.'px" /></a>';
													}
												$ret .= '</div>';
										
												if($produto->vendas > 2){
															$ret .= '<div class="produtosDestaquesBoxTop" title="Top de Vendas">Top de Vendas</div>';
												}
												
												//$ret .= '<div class="produtosDestaquesBoxSocial"><div class="fb-like" data-href="http://www.mesacor.com.br/Ver/"'.$produto->id."/".$produto->url.'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div></div>';
															
															
												if($presente->ganhados > 0){
															$ret .= '<a href="'.$pagina->localhost.$canal->url."Ver/".$produto->id."/".$produto->url.'" title="'.normaliza($produto->produto).'" class="produtosDestaquesBoxGanhou">Já Ganhou</a>';
												}else{
												
												$ret .= '<a href="'.$pagina->localhost.$canal->url."Ver/".$produto->id."/".$produto->url.'" title="'.normaliza($produto->produto).'">';
												
												
												if(isset($busca)){
													$busca = preg_replace("/\+/i"," ",$busca);
													$termosBusca = explode(" ",normaliza($busca));
													foreach($termosBusca as $_t){
														$_cod = preg_replace('/('.$_t.')/i', '<span class="destaqueBusca">${1}</span>', $produto->codigo);
													}
													$ret .= $_cod;
												}//else{
													//if(!preg_match('/mailing/i',$_SERVER['QUERY_STRING']))
														//$ret .= '<span class="codigo">'.$produto->codigo.'</span>';
												//}
												
							                    
												$ret .= '<strong class="produto">'.normaliza(preg_replace("/".$produto->linhas->linha."/i","", preg_replace("/ - /i"," ", preg_replace("/\"/i"," ", $produto->produto)))).'</strong>';
												
												if($produto->brindes->id > 0){
													$ret .= '<strong class="brinde"><img src="'.$pagina->localhost.'_imagens/24/promotion.png" width="24" height="24" /> Brinde: '.$produto->brindes->produto.'</strong>';
												}
												
												$ret .= '</a>';
												
												if($produto->linhas->id > 1){
													$ret .= '<a href="'.$pagina->localhost.$canal->url.'Linha/'.$produto->linhas->url.'" class="produtosDestaquesLinha"> &raquo; '.$produto->linhas->linha.'</a>';
								
												}
												
												
												for( $i = 2 ; $i <=12; $i++) {
													if($preco/$i > 100 and $i <= 5){
														$pre = $preco / $i;
														$_x = $i;
													}
												}
												
												
												//if($cliente->id || $convidado->id){
													if($produto->apenas_televenda == 'sim'){
														$ret .= '<h3><img src="'.$pagina->localhost.'_imagens/telephone.png" width="24" height="24" />Televendas</h3>';
														$ret .= '<div class="botaoComprar"><a class="awesome orange">ENTRE EM CONTATO</a></div>';
													}elseif((float)$produto->preco_promocional > 0){
														$ret .= '<a href="'.$pagina->localhost.$canal->url."Ver/".$produto->id."/".$produto->url.'" title="'.normaliza($produto->produto).'">';
														$_pre = explode(",",number_format($produto->preco_promocional,2,",","."));
														$ret .= '<div class="preco"><span class="overline">de R$ '.number_format($produto->preco_venda,2,",",".").'</span> por R$'.$_pre[0].','.$_pre[1].'</div></a>';
														$ret .= '<a href="'.$pagina->localhost.$canal->url.'Ver/'.$produto->id."/".$produto->url.'" class="destaque">
																<img src="'.$pagina->localhost.'_imagens/1334723428_bonus.png" width="24" height="24" alt="Promoção" />'.number_format(100 - ($produto->preco_promocional *100 /$produto->preco_venda) ,0,",",".").'% de desconto</a>';
								
														if($cliente->id){
															$ret .= '<div class="botaoComprar">
																	<a class="awesome orange" href="javascript:adicionarListaPresentes('.$produto->id.',1);"> Adicionar Casamento</a>
																	<a class="awesome orange" href="javascript:adicionarListaChas('.$produto->id.',1);" /> Adicionar Chá de Panela</a>
																	<a class="awesome orange" href="'.$pagina->localhost.$canal->url."Ver/".$produto->id."/".$produto->url.'">MAIS DETALHES</a></DIV>';
														}else{
															$ret .= '<div class="botaoComprar"><a class="awesome orange">APROVEITE!!!</a></DIV>';
														}
													}elseif((float)$produto->preco_venda > 0){
														$ret .= ' <div class="preco">R$'.number_format($produto->preco_venda,2,",",".").'</div>';
														
														if((float)$produto->desconto < 1){
														}elseif((float)$produto->desconto > 0){
															$ret .= '<span>&raquo; ou à vista no boleto:</span><br />';
															$ret .= '<div class="preco">R$ '.number_format($produto->preco_venda - ($produto->preco_venda * $produto->desconto/100) ,2,",",".")."</div>";
														}elseif($desconto_vista){
															$ret .= '<span>&raquo; ou à vista no boleto:</span><br />';
															$ret .= '<div class="preco">R$ '.number_format($produto->preco_venda - ($produto->preco_venda * $pagina->configs['desconto_boleto']/100) ,2,",",".")."</div>";
														}
								
															$ret .= '<div class="botaoPresentear"><a class="awesome orange" href="'.$pagina->localhost."Cliente/Carrinho/".$produto->id."-".$convidado->id."/".$produto->url.'"><img src="'.$pagina->localhost.'_imagens/botao2013_comprar.png" width="25" height="25" alt="Compre este produto" />Presentear</a></DIV>';
								
													}else{
														$ret .= '<h3>Indisponível Online</h3>';
							
														if($cliente->id){
															$ret .= '<div class="botaoComprar">
																	<a class="awesome orange" href="javascript:adicionarListaPresentes('.$produto->id.',1);"><img src="'.$pagina->localhost.'_imagens/botao2013_cupom.png" width="25" height="25" alt="Compre este produto" />Lista Casamento</a>
																	<a class="awesome orange" href="javascript:adicionarListaChas('.$produto->id.',1);" /><img src="'.$pagina->localhost.'_imagens/botao2013_cupom.png" width="25" height="25" alt="Compre este produto" />Chá de Panela</a>
																	<a class="awesome orange" href="'.$pagina->localhost.$canal->url."Ver/".$produto->id."/".$produto->url.'"><img src="'.$pagina->localhost.'_imagens/botao2013_zoom.png" width="25" height="25" alt="Compre este produto" />Mais Detalhes</a></DIV>';
														}						}
													$pre = $_x = 0;
							//					}else{
							//						$ret .= '<div class="botaoComprar"><a class="awesome orange" href="'.$pagina->localhost.$canal->url."Ver/".$produto->id."/".$produto->url.'"><img src="'.$pagina->localhost.'_imagens/botao2013_zoom.png" width="25" height="25" alt="Compre este produto" />Mais Detalhes</a></DIV>';
							//					}
												
												
												 }
												 $ret .= '</div>';
												 
												 echo $ret;
							?>
				<?
				$db->resource($resourceListagem);
			}?>
	  <? if($total > $limite){?>
	  <div class="<? echo ( $k++ == 10 ? "": "produtosDestaques ").$k ?> <?=($HOME? 'pad20': 'pad2')?>" id="produtoDestaque_<?=$produto->id?>">
		<div class="produtosDestaquesFoto"> <a href="<? echo $pagina->localhost.$canal->url.($pagina->acao ? $pagina->acao : "Categorias" ).'/'.($pagina->id ? $pagina->id : "Geral" ).'/_p='.($indice + 1).'&_f='.$_f.'&_o='.$_o?>"> <img src="<?=$pagina->localhost?>_imagens/mais_produtos.jpg" alt="Próximos produtos Tramontina" /> </a> </div>
	  </div>
	  <? }?>
  <? }?>
</div>
