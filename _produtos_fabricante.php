						<?
						$fabricante = new objetoDb('fabricantes',$pagina->id);
						echo $titulo = "Fabricante: ".$fabricante->fabricante."";
						$pro->produto = $titulo;
						
						$sql = "
						select
							if(preco_promocional > 0 , 100 - (preco_promocional *100 / preco_venda),0) as c, 
							if(preco_venda > 0 , 1,0) as d, 
							produtos.*
						from
							produtos
						where
							produtos.idfabricantes = '".$fabricante->id."' and produtos.ativo = 'sim'
						order by idlinhas, d desc,codigo asc, c desc";
						$db->query($sql);
						
						
						$total = $db->rows;
						?>