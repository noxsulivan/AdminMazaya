<?
$aviso = new objetoDb("avisos",$admin->id);

	
	
	$ret['total'] =  $db->rows("select * from avisos_has_visualizacoes where idavisos = '".$aviso->id."'");
	$ret['done'] =  $db->rows("select * from avisos_has_visualizacoes where idavisos = '".$aviso->id."'  and enviado = '1'");
	$ret['percent'] =  round(($ret['done'] * 100 )/ $ret['total']);

	$sql = "select * from avisos_has_visualizacoes where idavisos = '".$aviso->id."'  and enviado = '0'";
	$db->query($sql);
			
	$res = $db->fetch();
	$av = new objetoDb("avisos_has_visualizacoes",$res['idavisos_has_visualizacoes']);	
		
	if(email_ok(strtolower(trim($av->condominos->email)))){
	
			$corpo = $aviso->descricao;
			$corpo .= '<p><img src="http://'.$_SERVER['HTTP_HOST'].'/confirma.php?m='.$aviso->id.'&l='.$av->condominos->id.'"></p>';
			
			$ret['now'] = "Enviado para ".$av->condominos->email;

			mailClass($av->condominos->email,'Comunicado: '.$aviso->titulo,$corpo,"secretaria@recantogolfville.com.br",utf8_encode("Administra��o Recanto GolfVille"),$arquivo);
			
							
	}else{
		$ret['now'] = "O cond�mino ".$av->condominos->nome." n�o possui email cadastrado";
	}

	$db->query($sql = "update avisos_has_visualizacoes set enviado = 1, data_envio = '".date("Y-m-d H:i:s")."' where idavisos_has_visualizacoes = '".$av->id."'");

	$ret['sql'] =  "ok";
	echo json_encode($ret);
	
?>