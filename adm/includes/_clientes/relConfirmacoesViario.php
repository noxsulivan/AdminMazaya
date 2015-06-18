<?
list($extra,$ordem,$sentido) = explode("@",$admin->extra);

		if($sentido == 'DESC'){ $sentido = "ASC";}
		else{$sentido = "DESC";}
		
$_id = $usuario->clientes->id ? $usuario->clientes->id : $extra ;

//pre($usuario);
//pre($admin);
if(!$_id) die("<h2>Impossível consultar dados para o relatório</h2>");
$cliente = new objetoDb('clientes',$_id);

		$ret .= '<div style="background:url(http://www.viagememcotas.com.br/_imagens/papel-de-carta.jpg) no-repeat top right; padding:20px;">';

		$ret .= "<h1><br>Relat&oacute;rio de Confirma&ccedil;&atilde;o de Presen&ccedil;a</h1>";
		$ret .= "<h2>Cliente: ".$cliente->nome."</h2>";
		
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
			<td><strong><a href="'.$admin->localhost.$admin->admin.$admin->tg.'/'.$admin->acao.'/'.$admin->id."/".$extra.'@confirma@'.$sentido.'/'.'">confirma</a></strong></td>
			<td><strong><a href="'.$admin->localhost.$admin->admin.$admin->tg.'/'.$admin->acao.'/'.$admin->id."/".$extra.'@adultos@'.$sentido.'/'.'">adultos</a></strong></td>
			<td><strong><a href="'.$admin->localhost.$admin->admin.$admin->tg.'/'.$admin->acao.'/'.$admin->id."/".$extra.'@criancas@'.$sentido.'/'.'">criancas</a></strong></td>
			<td><strong><a href="'.$admin->localhost.$admin->admin.$admin->tg.'/'.$admin->acao.'/'.$admin->id."/".$extra.'@telefone@'.$sentido.'/'.'">telefone</a></strong></td>
			<td><strong><a href="'.$admin->localhost.$admin->admin.$admin->tg.'/'.$admin->acao.'/'.$admin->id."/".$extra.'@email@'.$sentido.'/'.'">email</a></strong></td>
		</tr>';
	
	
		$db->query('select idconfirmacoes from confirmacoes where idclientes="'.$cliente->id.'" order by '.($ordem ? $ordem : 'nome').' '.$sentido);
		  
		  if($db->rows){
		  
				while($res = $db->fetch()){
					$boleto = new objetoDb('confirmacoes',$res['idconfirmacoes']);
					
					
					$ret .= '
					<tr>
						<td>'.++$k.'</td>
						<td>'.$boleto->nome.'</td>
						<td>'.$boleto->confirma.'</td>
						<td>'.$boleto->adultos.'</td>
						<td>'.$boleto->criancas.'</td>
						<td>'.$boleto->telefone.'</td>
						<td>'.$boleto->email.'</td>
					</tr>';
					$totaladultos += $boleto->adultos;
					$totalcriancas += $boleto->criancas ;
				}
			  
			  
		  }else{
					$ret .= '
					<tr>
						<td colspan="9"><strong>Nenhuma confirma&ccedil;&atilde;o</strong></td>
					</tr>';
		  }
		  
		$ret .= '</table>';
		
		
		
		$ret .= '<h2>Total: '.( $totaladultos + $totalcriancas).' - Adultos: '.$totaladultos.' - Crian&ccedil;as: '.$totalcriancas.'</h2>';
			//header('Content-type: application/vnd.ms-excel');
			//header('Content-Disposition: attachment; filename="'.$admin->tg.'-'.$admin->acao.'-'.$admin->id.'-'.date("dmY_Hi").'.csv"');
			
		$ret .= '</div>';
		  echo ($ret);
		  //pre($db);
?>