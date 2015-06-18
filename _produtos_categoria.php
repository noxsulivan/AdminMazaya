						<?
						$categoria = new objetoDb('categorias',( $hotsite->url ? diretorio($hotsite->url)."-" : "").$pagina->id);
						$tituloCateg = $titulo." &raquo; ".( $categoria->categorias->categoria ? $categoria->categorias->categoria." - " : "").$categoria->categoria." Tramontina";
						$titulo = ( $categoria->categorias->categoria ? $categoria->categorias->categoria." - " : "").$categoria->categoria." Tramontina";
						$categoria->filhos('categorias');
						$cat[] = $categoria->id;
						
/*						if(rand(0,90) == 2)
							postarTwitter($categoria->categoria." #Tramontina com os melhores preços só aqui na http://mesacor.com.br/Tw/".$categoria->url."");
						if(rand(2,61) == 1)
							postarTwitter("Na @mesacor tem ".$categoria->categoria." #Tramontina  http://mesacor.com.br/Tw/".$categoria->url."");
*/						
						if(count($categoria->_hascategorias) > 1)
						foreach($categoria->_hascategorias as $c){
							$cat[] = $c->id;
						}
						
						
						if($categoria->id == 67 or $categoria->categorias->id == 67){
							$DN = true;
						}elseif($categoria->id == 33 or $categoria->categorias->id == 33){
							$estiloDC = true;
						}
							
						if($_o){
							switch($_o){
								case 'wc':$orderby = 'peso ASC';break;
								case 'wd':$orderby = 'peso DESC';break;
								case 'pc':$orderby = 'preco_venda ASC';break;
								case 'pd':$orderby = 'preco_venda DESC';break;
							}
						}else{
							$orderby = "codigo";
						}
						foreach($cat as $c){
							$cats[] = "produtos_has_categorias.idcategorias = ".$c;
						}
						$sql = "
						select
							if(preco_promocional > 0 , 100 - (preco_promocional *100 / preco_venda),0) as c, 
							if(preco_venda > 0 , 1,0) as d, 
							produtos.*
						from
							produtos, produtos_has_categorias
						where
							produtos.ativo = 'sim' and (".implode(" or \n",$cats).") and
							produtos.idprodutos = produtos_has_categorias.idprodutos
							".($_f ? 'and idfabricantes = "'.$_f.'"': '')."
						group by produtos.idprodutos
						order by d desc,codigo asc, c desc,  ".$orderby."";
						$db->query($sql);
						
						//echo "<!-- $sql -->";
						
						$total = $db->rows;
						echo "<!-- $total -->";
						?>