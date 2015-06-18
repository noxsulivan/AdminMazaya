						<?
						$sql = "
						select
							produtos.idprodutos
						from
							produtos
						where preco_promocional > 0
						order by destaque,codigo";
						$db->query($sql);
						
						$total = $db->rows;
						?>
<h2><?=$titulo?></h2>