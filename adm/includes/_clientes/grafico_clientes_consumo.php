<?

	/*			$this->campo[$grafico]['sql'] = "select * from itens where idclientes = ".$admin->id." and data_fatura >= '".date("Y-m-d",time() - (60*60*24*90))."' order by data_fatura";	
				$db->query($this->campo[$grafico]['sql']);
				
					
				global $ret;
				
				$ret['relatorio'][$grafico][0][] = 'Data';
				$ret['relatorio'][$grafico][0][] = 'Consumo';
				
				$i=0;
				while($res = $db->fetch()){
					$item = new objetoDb("itens",$res['iditens']);
					$vet[$item->data_fatura] += (float) $res['tons'];
					
				}
				
				
				foreach($vet as $k=>$v){
					++$i;
					$ret['relatorio'][$grafico][$i][] = $k;
					$ret['relatorio'][$grafico][$i][] += $v;
				}*/
									
?>

<?php

				$this->campo[$grafico]['sql'] = "select *, month(data_fatura) as M, year(data_fatura) as Y from itens where idclientes = ".$admin->id." and data_fatura >= '".date("Y-m-d",time() - (60*60*24*180))."' order by data_fatura";	
				$db->query($this->campo[$grafico]['sql']);
				
					
				global $ret;
				
				$ret['relatorio'][$grafico][0][] = 'Data';
				$ret['relatorio'][$grafico][0][] = 'Consumo';
				
				$i=0;
				while($res = $db->fetch()){
					$vet[$res['M']."/".$res['Y']]['data'] = $res['M']."/".$res['Y'];
					$vet[$res['M']."/".$res['Y']]['tons'] += (float) $res['tons'];
					
				}
				
				//pre($vet);
				//die();
				$startTime = strtotime(date("Y-m-d",time() - (60*60*24*180)));
				$endTime = strtotime(date("Y-m-d",time()));
				
				
				//pre($startTime);pre($endTime);die();
				
				$j = 1;
				// Loop between timestamps, 24 hours at a time
				for ($i = $startTime; $i <= $endTime; $i = $i + (date("t",$i) * (60*60*24))) {
					$thisDate = date('n/Y', $i); // 2010-05-01, 2010-05-02, etc
					$ret['relatorio'][$grafico][$j][0] = max($vet[$thisDate]['data'],$thisDate);
					$ret['relatorio'][$grafico][$j++][1] = (float) max($vet[$thisDate]['tons'],'0');
				}
?>