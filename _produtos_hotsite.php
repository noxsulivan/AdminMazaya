						<?
						$tituloCateg = $titulo." &raquo; ".$hotsite->hotsite;
						$titulo = ' - '.$titulo." &raquo; ".$hotsite->hotsite;
						
						
						
							
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
							produtos, produtos_has_hotsites
						where
							produtos.ativo = 'sim' and
							produtos.idprodutos = produtos_has_hotsites.idprodutos and
							produtos_has_hotsites.idhotsites = '".$hotsite->id."'
						group by produtos.idprodutos
						
						order by d desc,codigo asc, c desc,  ".$orderby."";
						
						
						$db->query($sql);
						
						echo "<!-- $sql -->";
						
						$total = $db->rows;
						?>