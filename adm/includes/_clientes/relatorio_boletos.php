<?

					$lista['propriedades']['grafico'] = 1;
					$_contadorRelatorio = 0;
					$lista['propriedades']['tituloRelatorio'] = utf8_decode("Relação de Receita/Inadimplência");
					
					$lista['relatorio']['itens'][0][0] = utf8_decode("Mês/Total");
					$lista['relatorio']['itens'][0][1] = utf8_decode("Receita");
					$lista['relatorio']['itens'][0][2] = utf8_decode("Inadimplência");
					//$lista['relatorio'][0][2] = 'Comissão';
					
					
				$db->query("select
							concat(month(vencimento),'/',year(vencimento)) as m,
							SUM(valor) as total,
							SUM(case when idstatus_de_boletos = 4 then valor else 0 end) as r,
							SUM(case when idstatus_de_boletos != 4 then valor else 0 end) as i, sum(valor) as v
							from boletos
							group by month(vencimento),year(vencimento)
							order by vencimento asc");
				
				$j = 1;
				$lista['relatorio']['total'] = $db->rows();
				while($res = $db->fetch()){
					
					$lista['relatorio']['itens'][$j][0] = (string) $res['m'];//." - R$".number_format( $res['v'],2,",",".");
					$lista['relatorio']['itens'][$j][1] = (float) $res['r'];
					$lista['relatorio']['itens'][$j][2] = (float) $res['i'];
					$j++;
					
				}			
?>