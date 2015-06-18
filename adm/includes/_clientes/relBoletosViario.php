<?
list($extra,$ordem,$sentido) = explode("@",$admin->extra);

		if($sentido == 'ASC'){ $sentido = "DESC";}
		else{$sentido = "ASC";}
		
$_id = $usuario->clientes->id ? $usuario->clientes->id : $extra ;

//pre($usuario);
//pre($admin);
if(!$_id) die("<h2>Impossível consultar dados para o relatório</h2>");
$cliente = new objetoDb('clientes',$_id);

		$ret .= '<div style="background:url(http://www.viagememcotas.com.br/_imagens/papel-de-carta.jpg) no-repeat top right; padding:20px;">';

		$ret .= "<h1><br>Relat&oacute;rio de cotas</h1>";
		$ret .= "<h2>Cliente: ".normaliza(htmlentities($cliente->nome))."</h2>";
		
//		numero - convidado - Documento/recibo - F. Pag - Itens - Total Solicitado - Vencimento - Data Pagamento - Andamento
//		1 - fulano - (id boleto) A 3 157,00 15/08/09 14/08/09 Q
//		2 - fulano2 - (id boleto) B 2 101,00 12/08/09 I
//		3 - fulano3 - (id boleto) B 2 115,00 12/08/09 D
//		4 - fulano3 - (id boleto) B 2 115,00 12/08/09 13/08/09 Q 
		  
		$ret .= '<table width="100%">';
		$ret .= '
		<tr>
			<td><strong>Nº</strong></td>
			<td><strong><a href="'.$admin->localhost.$admin->admin.$admin->tg.'/'.$admin->acao.'/'.$admin->id."/".$extra.'@nome@'.$sentido.'/'.'">Convidado</a></strong></td>
			<td><strong><a href="'.$admin->localhost.$admin->admin.$admin->tg.'/'.$admin->acao.'/'.$admin->id."/".$extra.'@idboletos@'.$sentido.'/'.'">Doc/Recibo</a></strong></td>
			<td><strong><a href="'.$admin->localhost.$admin->admin.$admin->tg.'/'.$admin->acao.'/'.$admin->id."/".$extra.'@'.'">Forma Pagamento</a></strong></td>
			<td><strong><a href="'.$admin->localhost.$admin->admin.$admin->tg.'/'.$admin->acao.'/'.$admin->id."/".$extra.'@itens@'.$sentido.'/'.'">Ítens</a></strong></td>
			<td><strong><a href="'.$admin->localhost.$admin->admin.$admin->tg.'/'.$admin->acao.'/'.$admin->id."/".$extra.'@valor@'.$sentido.'/'.'">Valor</a></strong></td>
			<td><strong><a href="'.$admin->localhost.$admin->admin.$admin->tg.'/'.$admin->acao.'/'.$admin->id."/".$extra.'@data_vencimento@'.$sentido.'/'.'">Vencimento</a></strong></td>
			<td><strong><a href="'.$admin->localhost.$admin->admin.$admin->tg.'/'.$admin->acao.'/'.$admin->id."/".$extra.'@data_pagamento@'.$sentido.'/'.'">Data Pagamento</a></strong></td>
			<td><strong><a href="'.$admin->localhost.$admin->admin.$admin->tg.'/'.$admin->acao.'/'.$admin->id."/".$extra.'@tipos_de_situacao.situacao@'.$sentido.'/'.'">Quitações</a></strong></td>
		</tr>';
		
		$db->query('select idboletos from boletos,tipos_de_situacao where idclientes="'.$cliente->id.'" and boletos.idtipos_de_situacao = tipos_de_situacao.idtipos_de_situacao and status_cliente = "sim" order by '.($ordem ? $ordem : 'nome').' '.$sentido);
		  
		  if($db->rows){
		  
				while($res = $db->fetch()){
					$boleto = new objetoDb('boletos',$res['idboletos']);
					
					
					$ret .= '
					<tr>
						<td>'.++$k.'</td>
						<td><strong>'.normaliza(htmlentities($boleto->nome)).'</strong></td>
						<td>'.($boleto->nosso_numero ? $boleto->nosso_numero : $boleto->id).'</td>
						<td>'.($boleto->nosso_numero ? '<strong>Á vista</strong>' : 'Boleto').'</td>
						<td>'.$boleto->itens.'</td>
						<td>R$ '.number_format($boleto->valor,2,',','.').'</td>
						<td>'.$boleto->data_vencimento.'</td>
						<td>'.$boleto->data_pagamento.'</td>
						<td>';
					if($boleto->tipos_de_situacao->codigo_itau == '00' || $boleto->nosso_numero){
						$ret .= '<span style="color:#009900">Liquidado</span>';
						$pagos++;
					}elseif($boleto->tipos_de_situacao->id == 5){
						$ret .= '<span style="color:#009900">Em aberto</span>';
						$pagos++;
					}else{
						$ret .= '<span style="color:#990000">Sem pagamento</span>';
					}
					$ret .= '</td>
					</tr>';
					//$totalPago += ($boleto->tipos_de_situacao->codigo_itau == '00' || $boleto->nosso_numero ? $boleto->valor - $cliente->tpv : 0);
					$totalPago += ($boleto->tipos_de_situacao->codigo_itau == '00' || $boleto->nosso_numero ? $boleto->valor : 0);
					$totalGerado += $boleto->valor ;// - $cliente->tpv
				}
			  
			  
		  }else{
					$ret .= '
					<tr>
						<td colspan="9"><strong>Nenhum boleto registrado</strong></td>
					</tr>';
		  }
		  
		
		
		$ret .= '
					<tr>
						<td colspan="5">&nbsp;</td>
						<td><strong>Total de solicitados:<br>R$'.number_format($totalGerado,2,',','.').'</strong></td>
						<td colspan="2">&nbsp;</td>
						<td><strong>Total recolhido:<br>R$'.number_format($totalPago,2,',','.').'</strong></td>
					</tr>';
		
		$ret .= '</table>';
		$totalDespesa = 0;
			$ret .= '<h2>Outras Despesas ('. (count($cliente->despesas)+1) .')</h2>';
			
			$tpv = $cliente->tpv * $pagos;
			
			$ret .= '<table width="100%">';
						$ret .= '
						<tr>
							<td>Taxa de Planejamento de Viagens</td>
							<td>R$'.number_format($tpv,2,',','.').'</td>
						</tr>';
				if($cliente->despesas){
					foreach($cliente->despesas as $despesa){
						$ret .= '
						<tr>
							<td>'.htmlentities($despesa->despesa).'</td>
							<td>R$'.number_format($despesa->valor,2,',','.').'</td>
						</tr>';
						$totalDespesa += $despesa->valor;
					}
				}
				$totalDespesa += $tpv;
						$ret .= '
						<tr>
							<td>&nbsp;</td>
							<td><strong>Total de despesas: R$'.number_format($totalDespesa,2,',','.').'</strong></td>
						</tr>';
						$ret .= '
						<tr>
							<td>&nbsp;</td>
							<td><h2>Total Geral: R$'.number_format($totalPago-$totalDespesa,2,',','.').'</h2></td>
						</tr>';
			$ret .= '</table>';
			
			//header('Content-type: application/vnd.ms-excel');
			//header('Content-Disposition: attachment; filename="'.$admin->tg.'-'.$admin->acao.'-'.$admin->id.'-'.date("dmY_Hi").'.csv"');
			
		$ret .= '</div>';
		  echo utf8_decode($ret);
		//pre($cliente);
?>