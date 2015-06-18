<?
$boleto = new objetoDb("boletos",$admin->id);


$pagina = new layout($_SERVER['QUERY_STRING']);
		

		$vencimento_atualizado = dias_uteis(2);
		
		$dias_vencidos = compara_data(  in_data($vencimento_atualizado) , in_data($boleto->vencimento));
		
		$valor = $boleto->valor;
		$juros_dia = round(($boleto->valor/30) * 0.01,2);
		
		$juros_vencido = $juros_dia * $dias_vencidos;
		
		$multa = round($boleto->valor * 0.02,2);
		
		$acrescimos = $juros_vencido + $multa;
		
		$total = $boleto->valor + $acrescimos;

		$db->query("update boletos set vencimento_atualizado = '".in_data($vencimento_atualizado)."', valor_atualizado = '".$total."' where idboletos = '".$boleto->id."'");

	$ret['status'] =  "ok";
	$ret['mensagem'] =  utf8_encode("O boleto foi alterado com sucesso:<br> Data de vencimento: <strong>".$vencimento_atualizado." (Dias vencidos: ".$dias_vencidos.")</strong>.<br>Valor corrigido: <strong>R$".number_format($total,2,",",".")." (Acréscimos: R$".number_format($acrescimos,2,",",".").")</strong>");
	$ret['arquivo'] =  $arquivo;
	echo json_encode($ret);
	
?>