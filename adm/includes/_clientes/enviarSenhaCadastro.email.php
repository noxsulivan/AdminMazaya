<?
		$corpo = "<p>Caro cond�mino</p>
		<p>A partir dos pr�ximos dias estaremos utilizando um novo sistema para o controle de acesso ao condom�nio.</p>
		<p>Este sistema foi desenvolvido com foco nas necessidades espec�ficas do Recanto Golfville baseadas nas observa��es
		que a diretoria tem feito e cm a contribui��es de muitos associados.</p>
		<p>Tem como foco principal a intera��o dos morados com a diretoria, na resolu��o de problemas di�rios, e com a portaria de
		modo a agilizar o dia-a-dia e controlar com mais efici�ncia o fluxo de pessoas.</p>
		<p>Nosso objetivo � trazer aos cond�minos uma ferramenta que possibilite facilitar o conv�vio e refor�ar a seguran�a de todos
		visto que servir� como um canal online de comunica��o r�pida e confi�vel.</p>
		
		<p>Para ter acesso a este sistema, salve em seus favoritos, e acesse o site:<br>
		http://www.recantogolfville.com.br/adm<br>
		Utilizando o login: noxsulivan@gmail.com.br<br>
		E senha: 123</p>";
		
		mailClass("noxsulivan@gmail.com",'[GolfVille] Bem vindo ao sistema online do Recanto Golf Ville',$corpo,"admin@recantogolfville.com.br",utf8_encode("Administra��o Recanto GolfVille"));
		mailClass("noxsulivan@hotmail.com",'[GolfVille] Bem vindo ao sistema online do Recanto Golf Ville',$corpo,"admin@recantogolfville.com.br",utf8_encode("Administra��o Recanto GolfVille"));
					

	$ret['status'] =  "ok";
	$ret['mensagem'] =  utf8_encode('<img width="64" height="64" alt="" src="http://recantogolfville.com.br/1422650374_678134-sign-check-128.png" align="left">Suas informa��es de pr�-cadastro foram enviadas para a secretaria.<br>Em breve o sistema ser� liberado e voc� ser� notificado pelo e-mail informado, com detalhes de acesso.');
	$ret['arquivo'] =  $arquivo;
	echo json_encode($ret);
	
?>