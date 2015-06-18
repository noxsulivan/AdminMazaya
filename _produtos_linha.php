						<?
						
						$linhas = explode("+",$pagina->id);
						$linha = new objetoDb('linhas',$linhas[0]);
						
						foreach($linhas as $_l){
							$_linha = new objetoDb('linhas',$_l);
							$_linhas['id'][] = $_linha->id;
							$_linhas['linha'][] = $_linha->linha;
						}
						
						
						$titulo = $titulo." &raquo; Linha ".implode(", ", $_linhas['linha']);
						
						
						
						
						if(rand(2,20) == 1)
							postarTwitter("".implode(", ", $_linhas['linha'])." #Tramontina só na @mesacor http://mesacor.com.br/Tw/".$pagina->id."");
						
						
							
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
						
						$sql = "
						select
							if(preco_promocional > 0 , 100 - (preco_promocional *100 / preco_venda),0) as c, 
							if(preco_venda > 0 , 1,0) as d, 
							produtos.*
						from
							produtos
						where
							produtos.ativo = 'sim' and
							produtos.idlinhas in (".implode(", ", $_linhas['id']).")
						group by produtos.idprodutos
						order by c desc, d desc, ".$orderby."";
						$db->query($sql);
						
						$total = $db->rows;
						$titulo = $titulo." (".$total." ítens)";
						$tituloCateg = $titulo;
						?>