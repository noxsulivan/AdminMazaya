<?
						$busca = ($_REQUEST['q']);
						
						$_POST['termo'] = $busca;
						//$db->query("select * from buscasbkp");
						//while($res = $db->fetch()){
						if(!preg_match("/bot/i",$_SERVER['HTTP_USER_AGENT']))
						$db->query("insert into buscas ( termo) values ('".trim(strtolower(normaliza($busca)))."') on duplicate key update contagem = contagem + 1;");
						//}
						$busca = preg_replace("/\+/i"," +",$busca);
						$termosBusca = explode(" ",normaliza($busca));
						//mail('noxsulivan@gmail.com','busca mesacor '.$pagina->acao,print_r($busca,true).print_r($termosBusca,true).print_r($_SERVER,true));

						$whe = array();
						foreach($termosBusca as $_t){
							if(strlen($_t) > 2){
								$where[] = "(soundex(produto) like soundex('".preg_replace("/\+/i","",$_t)."')
																								or produto like '%".preg_replace("/\+/i","",$_t)."%'
																								or codigo like '%".preg_replace("/\+/i","",$_t)."%'
																								or linha like '%".preg_replace("/\+/i","",$_t)."%')";
								$db->query("insert into buscas (termo) values ('".trim(strtolower(normaliza($_t)))."') on duplicate key update contagem = contagem + 1;");
							}
						}
						
						
						
						
						$sql = "
						SELECT produtos.idprodutos, produtos.codigo, produtos.descricao_curta
						FROM produtos left join linhas on produtos.idlinhas = linhas.idlinhas
						where 
							produtos.ativo = 'sim' and ".implode(" and ",$where)."
						group by produtos.idprodutos";
							
							echo "<!-- $sql -->";
							
							
//		  $sql = "
//		  SELECT produtos.idprodutos, produtos.codigo, produtos.descricao_curta
//		  FROM produtos left join linhas on produtos.idlinhas = linhas.idlinhas
//		  where ".( preg_match("/^ALL /i",$busca) ?
//							   implode(" or ",$whe) :
//							   "(".implode(" and ",$whe).") or (".implode(" and ",$wheLinhas).")"
//				)."
//		  group by produtos.idprodutos
//			  ";
							
						$db->query($sql);
						$total = $db->rows;
						$tituloCateg = "Resultado da busca por: ".$busca."<br>".($total ? ($total == 1 ? "Apenas 1 ítem encontrado":$total." ítens encontrados" ) : "Nenhum ítem encontrado");
?>
