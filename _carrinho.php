    <div id="menu">
      <div id="inner_menu">
        <h2 class="categorias">
		<? _e("Categorias");?></h2>
        <?
		
		
					function menu_categorias($id,$prof){
						global $pagina, $canal;
						$sql = "
						select *
						from categorias, produtos
						where
							idcategorias_2 = '".$id."' and
							produtos.idcategorias = categorias.idcategorias
						group by categorias.idcategorias
						order by categoria";
							//produtos.idcanais = '".$canal->id."' and
						$db->query($sql);
						if(!$db->rows) return;
						$ret = '
						<ul id="cat_'.$id.'"'.($prof > 0 ? ' style="display: none"':'').'>';
						while($res = $db->fetch()){
							$_tmp = menu_categorias($res[idcategorias],$prof+1);
							if($_tmp){
								$ret .= '
								<li><a href="#" onclick="Effect.toggle(\'cat_'.$res['idcategorias'].'\',\'blind\');">'.$res[categoria].'</a>';
								$ret .= $_tmp;
							}else{
							$ret .= '
							<li><a href="'.$pagina->localhost.'Produtos/'.$res[url].'">'.$res[categoria].'</a>';
							}
							$ret .= '
							</li>';
							}
						$ret .= "
						</ul>";
						return $ret;
					}
					echo menu_categorias(0,0);
					
				?>
        <!-- <h2 class="fabricantes"><? _e("Fabricantes");?></h2> -->
        <?	
					
						/*
						$sql = "select * from fabricantes order by fabricante";
						$db->query($sql);
						if(!$db->rows) return;
						echo "<ul>";
						while($res = $db->fetch()){
							echo '<li><a href="'.$pagina->localhost.'Fabricantes/'.$res[url].'">'.$res[fabricante].'</a></li>';
						}
						echo "</ul>";
						*/
		?>
        <h2 class="fabricantes"><? _e("Busca");?></h2>
		<form action="<?=$pagina->localhost.$canal->url?>Busca" method="post" id="formulario">
			<p><? _e('Palavra chave')?></p>
			<input name="busca" class="caixaBusca" />
			<p><input type="submit" value="Buscar" class="botaoBusca" /></p>
		  </form>
      </div>
    </div>
    <div id="content">
      <div id="inner_content">
	  
	  
	  
	  
	  
	  
	
	<?
switch($pagina->acao){
	case !null :
			echo "<h2>"._r('Produtos')." &raquo; "._pegaCategoria($canal)."</h3>";	
			switch($pagina->id){
				case !null:
					if(!ereg("Listar", $pagina->id)){
						$sql = "select produtos.*,categoria
						from produtos
								left outer join categorias on produtos.idcategorias = categorias.idcategorias
						where produtos.idprodutos = '$pagina->id'  group by produtos.idprodutos";
						$db->query($sql);
						
						$res = $db->fetch();
						
						
						echo '<div class="produto">';
						$sql = "select * from fotos where idprodutos = '$pagina->id' order by fotos.ordem";
						$db->query($sql);
						$class = 'box';
						while($imgs = $db->fetch()){
							echo '
							<div class="'.$class.'">
							<a href="'.$pagina->localhost."imagem.php?id=".$imgs[0].'" rel="lightbox['.$pagina->id.']" title="'.htmlentities($produto).'" alt="'.$imgs[legenda].'">
							<img src="'.$pagina->localhost."imagem.php?id=".$imgs[0].'&width=250&height=250&force=1" width="250" height="250" alt="'.$imgs[legenda].'"/>
							'._r('Clique para ampliar').'</a>
							</div>';
							$class = 'box_oculto';
						}
						
						echo 
							( $res[codigo]	?	"<h3>"._r('Código').': '.$res[codigo]."</h3>": "" ).
							( $res[produto]	?	"<h3>"._r('Produto').': '.$res[produto]."</h3>": "" ).
							( $res[categoria]	?	"<h4>"._r('Categoria').': '.$res[categoria]."</h4>": "" ).
							( $res[descricao_curta]	?	_r('Destalhes').': '.$res[descricao_curta]."<br>": "" ).
							( $res[dimensoes]	?	_r('Dimensões').': '.$res[dimensoes]."<br>": "" );
							
						$sql = "
						SELECT *
						FROM caracteristicas, opcoes, produtos_has_opcoes
						WHERE
						caracteristicas.idcaracteristicas = opcoes.idcaracteristicas and
						produtos_has_opcoes.idopcoes = opcoes.idopcoes
						
						and idprodutos = '".$pagina->id."'
						ORDER BY caracteristica, opcao
						LIMIT 0 , 100 ";
						
						$db->query($sql);
						
						while($res = $db->fetch()){
							echo "$res[caracteristica]: $res[opcao]<br>";
						}
						
						
						echo '
						<div class="box">
							<form onsubmit="return adicionarCarrinho('.$pagina->id.', $(\'qtd\').value);">
							Quantidade <input name="qtd" id="qtd" class="inputQtd">
							<button class="linkMarcador" id="cart_'.$pagina->id.'">Adicionar ao carrinho de compras</button>
							</form>
						</div>';
						
						echo '
						<div class="comandos">
						<a href="#" class="linkMarcador" id="marker_'.$pagina->id.'" onclick="marcarComparacao('.$pagina->id.');">Comparar</a>
						<a href="#" class="linkRecomendar" onclick="recomendar();">Recomendar</a>
						<a href="#" class="linkNovajanela" onclick="popProduto('.$pagina->id.');">Abrir em nova janela</a>
						</div>
						</div>';
					}
			default:
					$busca = explode(",",$pagina->id);
						$sql = "
						select
							produtos.*,
							 categorias.url as cat_url, fotos.idfotos as img, preco_venda
						from
							produtos
								left outer join fotos on produtos.idprodutos = fotos.idprodutos
								left outer join categorias on produtos.idcategorias = categorias.idcategorias
						where
							categorias.url = '$pagina->acao'
						group by produtos.idprodutos
						order by md5(codigo)";
						$db->query($sql);
						$total = $db->rows;
						if($total){
							$j = 0;
							
							mysql_data_seek(, (isset($busca[1]) ? $busca[1]*$pagina->configs['limite_listagem_produtos'] : 0));
							while($res = $db->fetch() and ++$j <= $pagina->configs['limite_listagem_produtos']){
							
							
							
								$produto = 
									( $res[codigo]	?	"<h3>"._r('Código').': '.$res[codigo]."</h3>": "" ).
									( $res[produto]	?	"<h3>"._r('Produto').': '.$res[produto]."</h3>": "" ).
									( $res[categoria]	?	"<h4>"._r('Categoria').': '.$res[categoria]."</h4>": "" ).
									( $res[descricao_curta]	?	_r('Destalhes').': '.$res[descricao_curta]."<br>": "" ).
									( $res[dimensoes]	?	_r('Dimensões').': '.$res[dimensoes]."<br>": "" );
									
								/*
								$sql2 = "
								SELECT *
								FROM caracteristicas, opcoes, produtos_has_opcoes
								WHERE
								caracteristicas.idcaracteristicas = opcoes.idcaracteristicas and
								produtos_has_opcoes.idopcoes = opcoes.idopcoes
								
								and idprodutos = '".$res[idprodutos]."'
								ORDER BY caracteristica, opcao
								LIMIT 0 , 100 ";
								
								$db->query($sql2);
								
								while($res2 = $db->fetch()){
									$car .= "$res2[caracteristica]: $res2[opcao]<br>";
								}
							
								$produto .= $car;
								$car = '';
								*/
								echo '
									<div class="produtos'.($i == 2+(3 * intval($i++/3)) ? " ultimo" : "" ).'">
								<a href="'.$pagina->localhost.$canal->url.$res['cat_url']."/".$res[idprodutos].'" title="'.htmlentities($produto).'" alt="'.$imgs[legenda].'">
									';
								if($res[img]) echo '
									<img src="'.$pagina->localhost."imagem.php?id=".$res[img].'&width=131&height=150&force=0" class="img">';
								echo '
									<p>Cod: '.$res[codigo].'
									</p>
									</a>
									</div>
								
							';//'._r('Mais detalhes').'
							}
									if($total > $pagina->configs['limite_listagem_produtos']){
										echo '<div id="navegacao">';
										if($busca[1] > 3){
											echo '<a href="'.$pagina->localhost.$canal->url.$pagina->acao.'/Listar,0" class="linkAnteriores">&laquo; Início</a>';
											echo '...';
										}
										for($p = ( $busca[1] > 3 ? $busca[1]-3 : 0) ; $p < ($total/9) and $p < $busca[1]+4; $p++){
											echo '<a href="'.$pagina->localhost.$canal->url.$pagina->acao.'/Listar,'.($p).'" class="linkPaginas'.( $busca[1] == $p ? " strong":'').'">'. ($p+1) .'</a>';
										}
										
										if($busca[1] != intval($total/$pagina->configs['limite_listagem_produtos'])){
											echo '...';
											echo '<a href="'.$pagina->localhost.$canal->url.$pagina->acao.'/Listar,'.(intval($total/9)).'" class="linkProximos">Fim &raquo;</a>';
										}
										echo '</div>';
									}
						}else{
						}
			}
	break;
	default:
						//echo $canal->texto;
						
								$in = implode(',',array_keys($_SESSION['itensCarrinho']));
								$sql = "
								SELECT produtos.idprodutos, produtos.codigo, produtos.preco_venda , categorias.categoria, categorias.url as cat_url,  fotos.idfotos AS img
								FROM produtos
								LEFT OUTER JOIN fotos ON produtos.idprodutos = fotos.idprodutos
								LEFT OUTER JOIN categorias ON produtos.idcategorias = categorias.idcategorias
								WHERE produtos.idprodutos in (".$in .")
								";
								
								$db->query($sql);
								if($db->rows){
									echo "<h2>"._r("Produtos no carrinho").'</h2>
									<table border="0">
									  <tr>
										<td>Foto</td>
										<td>Produto</td>
										<td>Quantidade</td>
										<td>Valor Unitário</td>
										<td>Total</td>
									  </tr>
									';
									
									
									while($res = $db->fetch()){
									
										if($res[preco_venda]){
											$tprod = number_format($res[preco_venda] * (int)$_SESSION['itensCarrinho'][$res[idprodutos]],2,",",".");
											$tcompra += $res[preco_venda] * (int)$_SESSION['itensCarrinho'][$res[idprodutos]];
										}else{
											$tprod = "Consulte";
										}
										echo '
									  <tr>
										<td><a href="'.$pagina->localhost.$canal->url.$res['cat_url']."/".$res[idprodutos].'" title="'.htmlentities($info).'" alt="'.$imgs[legenda].'"><img src="'.$pagina->localhost."imagem.php?id=".$res[img].'&width=100&height=100&force=1" class="img"></a></td>
										<td>'.$res[codigo].' '.$res[produto].'</td>
										<td valign="middle" align="center">'. (int)$_SESSION['itensCarrinho'][$res[idprodutos]].'</td>';
										
										
										if($res[preco_venda]){
											
											$tprod = number_format($res[preco_venda] * (int)$_SESSION['itensCarrinho'][$res[idprodutos]],2,",",".");
											$tcompra += $res[preco_venda] * (int)$_SESSION['itensCarrinho'][$res[idprodutos]];
											
											echo '
											<td valign="middle" align="center">'.number_format($res[preco_venda],2,",",".").'</td>
											<td valign="middle" align="center">'.$tprod.'</td>';
											
										}else{
											echo '
											<td valign="middle" align="center" colspan="2">Consulte</td>';
											
										}
										echo '
									  </tr>';
									}
									echo '
									  <tr>
										<td colspan="4">Total da compra</td>
										<td>'.number_format($tcompra,2,",",".").'</td>
									  </tr>
									</table>
									';
								}
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
?>
      </div>
    </div>
	<div id="sidebar">	
		
		<div id="profileDiv">
		<h4>Cadastro</h4>
		<ul id="profileUl">
		<li id="profileId_'.$k.'"><a href="javascript:popComparacao();" class="linkNovajanela">Login</a></li>
		</ul>
		</div>
		
		
		<div id="productCompListDiv" <? if(!isset($_SESSION['itensComparacao'])){?>style="display:none;"<? }?>>
		<h4>Comparação</h4>
		<a href="javascript:popComparacao();" class="linkNovajanela">Visualizar</a>
		<ul id="productCompListUl">
		<?
		if(isset($_SESSION['itensComparacao']))
			foreach($_SESSION['itensComparacao'] as $k => $item){
			
						echo '<li id="productCompId_'.$k.'">
						<a href="'.$pagina->localhost.'Produtos/'.$item[url]."/".$k.'">
						'.$item[codigo].' - '.$item[produto].'
						</a></li>';
			}
		?>
		</ul>
		</div>

	</div>
	<? //pre($_SESSION)?>