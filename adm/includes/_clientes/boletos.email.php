<?
$boleto = new objetoDb("boletos",$admin->id);


$pagina = new layout($_SERVER['QUERY_STRING']);
		
		
			$arquivos[0] = geraBoleto($boleto);
			
				$corpo = '<p><strong>Sr(a) Sócio(a)</strong></p>
				<p>Em anexo segue o boleto referência '.$boleto->cobrancas->referencia.'</p>
				<p>Para pagamento online utilize a linha digitável:</p>
				<p>'.$linha_digitavel.'</p>
				<p>'.$demonstrativo.'</p>
				<p>Caso tenha algum problema na visualização do arquivo, solicite a Secretaria no e-mail secretaria@recantogolfville.com.br.<br>
				<p>Att<br>Secretaria Recanto Golf Ville</p>';
				
				
				
						

			//$db->query("update boletos set status = 1 where idboletos = '".$boleto->id."'");
			
			$result = mailClass($boleto->condominos->email,'Boleto '.ucwords(strtolower($boleto->cobrancas->referencia." - Q".str_pad($boleto->condominos->quadras->quadra,2,"0",STR_PAD_LEFT))." L".str_pad($boleto->condominos->lote,2,"0",STR_PAD_LEFT)),$corpo,"secretaria@recantogolfville.com.br",utf8_encode("Administração Recanto GolfVille"),$arquivos);


	$ret['status'] =  "ok";
	$ret['mensagem'] =  "<h3>A mensagem foi enviada com sucesso para ".$contador.".<br>Status Mandrill: ".pre($result,true)."</h3>";
	echo json_encode($ret);
	
?>