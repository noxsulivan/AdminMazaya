<?
$cobranca = new objetoDb("cobrancas",$admin->id);

set_time_limit(0);



$pagina = new layout($_SERVER['QUERY_STRING']);

//pre($cobranca);
//pre($cobranca->arquivos);

//die();

	$ret['total'] =  $db->rows("select * from boletos where idcobrancas = '".$cobranca->id."'");
	
	$ret['done'] =  $db->rows("select * from boletos where idcobrancas = '".$cobranca->id."'  and enviado = '1'");
	$ret['percent'] =  round(($ret['done'] * 100 )/ $ret['total']);
	
	
	$sql = "select * from boletos where idcobrancas = '".$cobranca->id."'  and enviado = '0'";
	//idcondominos = '124'";// 
	$db->query($sql);
			
		$res = $db->fetch();
		$boleto = new  objetoDb("boletos",$res['idboletos']);

				$arquivos[0] = geraBoleto($boleto);
				
				$corpo = '<p><strong>Sr(a) Sócio(a) '.$sacado.'</strong></p>
				<p>Em anexo segue o boleto referência '.$cobranca->referencia.'</p>
				<h3>Para pagamento online utilize a linha digitável:</h3>
				<h3>'.$linha_digitavel.'</h3>
				<h3>'.$demonstrativo.'</h3>
				<p>Caso tenha algum problema na visualização do arquivo, solicite a Secretaria no e-mail secretaria@recantogolfville.com.br.<br>
				<p>Att<br>Secretaria Recanto Golf Ville</p>';
				
				
				
				foreach($cobranca->arquivos as $arq){
					$j++;
					$arquivos[$j][tmp_name] =  $arq->path;
					$arquivos[$j][name] = $arq->arquivo;
				}
		
				
												
				if(email_ok(strtolower(trim($boleto->condominos->email)))){
					mailClass($boleto->condominos->email,'Boleto '.ucwords(strtolower($cobranca->referencia." - Q".str_pad($boleto->condominos->quadras->quadra,2,"0",STR_PAD_LEFT))." L".str_pad($boleto->condominos->lote,2,"0",STR_PAD_LEFT)),$corpo,"secretaria@recantogolfville.com.br",utf8_encode("Administração Recanto GolfVille"),$arquivos);
					//mailClass("noxsulivan@gmail.com",'Boleto '.ucwords(strtolower($cobranca->referencia." - Q".str_pad($boleto->condominos->quadras->quadra,2,"0",STR_PAD_LEFT))." L".str_pad($boleto->condominos->lote,2,"0",STR_PAD_LEFT)),$corpo,"secretaria@recantogolfville.com.br",utf8_encode("Administração Recanto GolfVille"),$arquivos);
					$ret['now'] = "Enviado para ".$boleto->condominos->email;
					//echo "<script>location.reload();< /script>";
					flush();
					++$contador;

				}else{
					$ret['now'] = "O condômino ".$boleto->condominos->nome." não possui email cadastrado";
				}
					
				$db->query("update boletos set enviado = 1 where idboletos = '".$boleto->id."'");



	$ret['status'] =  "ok";
	echo json_encode($ret);
	
	
?>