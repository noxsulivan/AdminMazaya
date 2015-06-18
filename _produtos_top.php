						<?
						$tituloCateg = $titulo = " &raquo; TOP de VENDAS";
						
						
						
						$sql = "SELECT sum( 1 ) , produtos . *
						FROM produtos, itens, pedidos
						WHERE produtos.idprodutos = itens.idprodutos
						AND itens.idpedidos = pedidos.idpedidos
						AND pedidos.idestagios =4
						GROUP BY produtos.idprodutos
						ORDER BY sum( 1 ) DESC
						LIMIT 0 , 30";
						$db->query($sql);
						
						echo "<!-- $sql -->";
						
						$total = $db->rows;
						?>