						<?
						
						if($canal->url == 'Ofertas/'){
						$tituloCateg = $titulo = " Ofertas";
						$sql = "
						select
							if(preco_promocional > 0 , 100 - (preco_promocional *100 / preco_venda),0) as c, 
							if(preco_venda > 0 , 1,0) as d, 
							produtos.*
						from
							produtos
						where
							produtos.ativo = 'sim'
						".(preg_match('/Ofertas/i',$canal->url) ? ' and preco_promocional > 0': '')."
						order by c desc, d desc, codigo";
						$db->query($sql);
						
						}else{
						$tituloCateg = $titulo = " TOP de VENDAS";
						$sql = "SELECT
							sum( 1 ) ,
							produtos . *
						FROM produtos, itens, pedidos
						WHERE
						preco_venda > 0 and
						produtos.idprodutos = itens.idprodutos
						AND itens.idpedidos = pedidos.idpedidos
						AND pedidos.idestagios =4
						GROUP BY produtos.idprodutos
						ORDER BY sum( 1 ) DESC
						LIMIT 0 , 100";
						
						
						$db->query($sql);
						}
						
						$total = $db->rows;
						?>