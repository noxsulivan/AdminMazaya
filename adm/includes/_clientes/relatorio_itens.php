<?

					$lista['propriedades']['grafico'] = 1;
					$_contadorRelatorio = 0;
					$lista['propriedades']['tituloRelatorio'] = utf8_decode("Relatório de cargas enviadas recentemente");
					
					$lista['relatorio'][0][0] = 'Data de faturamento';
					$lista['relatorio'][0][1] = 'Tons enviados';
					//$lista['relatorio'][0][2] = 'Comissão';
					
					
				$db->query("select * from itens where data_fatura >= '".date("Y-m-d",time() - (60*60*24*90))."' order by data_fatura");
				
				while($res = $db->fetch()){
					
					$vet[$res['data_fatura']]['data'] = $res['data_fatura'];
					$vet[$res['data_fatura']]['tons'] += (float) $res['tons'];
					
					
				}
				//pre($vet);die();
				$startTime = strtotime(date("Y-m-d",time() - (60*60*24*90)));
				$endTime = strtotime(date("Y-m-d",time() + (60*60*24*20)));
				
				
				//pre($startTime);pre($endTime);die();
				
				$j = 1;
				for ($i = $startTime; $i <= $endTime; $i = $i + (60*60*24)) {
					$thisDate = date('Y-m-d', $i); // 2010-05-01, 2010-05-02, etc
					$lista['relatorio'][$j][0] = date('d/m', $i);
					$lista['relatorio'][$j++][1] = (float) max($vet[$thisDate]['tons'],'0');
				}				
									
									
?>