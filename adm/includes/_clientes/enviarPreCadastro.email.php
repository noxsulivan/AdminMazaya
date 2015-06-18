<?
		$corpo = "<p>Nome: <strong>".$_POST['Nome']."</strong><br>
		Quadra:<strong>".$_POST['Quadra']."</strong> Lote:<strong>".$_POST['Lote']."</strong><br>
		Telefone: <strong>".$_POST['Telefone']."</strong> Celular: <strong>".$_POST['Celular']."</strong><br>
		E-mail: <strong>".$_POST['E-mail']."</strong></p>
		<h3>HTTP_USER_AGENT: ".$_SERVER['HTTP_USER_AGENT']."</h3>
		<h3>REMOTE_ADDR: ".$_SERVER['REMOTE_ADDR']."</h3>";
		
		mailClass("noxsulivan@gmail.com",'Pré-Cadastro: '.$_POST['Nome'],$corpo,"admin@recantogolfville.com.br",utf8_encode("Administração Recanto GolfVille"));
					

	$ret['status'] =  "ok";
	$ret['mensagem'] =  utf8_encode('<img width="64" height="64" alt="" src="http://recantogolfville.com.br/1422650374_678134-sign-check-128.png" align="left">Suas informações de pré-cadastro foram enviadas para a secretaria.<br>Em breve o sistema será liberado e você será notificado pelo e-mail informado, com detalhes de acesso.');
	$ret['arquivo'] =  $arquivo;
	echo json_encode($ret);
	
?>