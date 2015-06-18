<?
		$corpo = "<p>Caro condômino</p>
		<p>A partir dos próximos dias estaremos utilizando um novo sistema para o controle de acesso ao condomínio.</p>
		<p>Este sistema foi desenvolvido com foco nas necessidades específicas do Recanto Golfville baseadas nas observações
		que a diretoria tem feito e cm a contribuições de muitos associados.</p>
		<p>Tem como foco principal a interação dos morados com a diretoria, na resolução de problemas diários, e com a portaria de
		modo a agilizar o dia-a-dia e controlar com mais eficiência o fluxo de pessoas.</p>
		<p>Nosso objetivo é trazer aos condôminos uma ferramenta que possibilite facilitar o convívio e reforçar a segurança de todos
		visto que servirá como um canal online de comunicação rápida e confiável.</p>
		
		<p>Para ter acesso a este sistema, salve em seus favoritos, e acesse o site:<br>
		http://www.recantogolfville.com.br/adm<br>
		Utilizando o login: noxsulivan@gmail.com.br<br>
		E senha: 123</p>";
		
		mailClass("noxsulivan@gmail.com",'[GolfVille] Bem vindo ao sistema online do Recanto Golf Ville',$corpo,"admin@recantogolfville.com.br",utf8_encode("Administração Recanto GolfVille"));
		mailClass("noxsulivan@hotmail.com",'[GolfVille] Bem vindo ao sistema online do Recanto Golf Ville',$corpo,"admin@recantogolfville.com.br",utf8_encode("Administração Recanto GolfVille"));
					

	$ret['status'] =  "ok";
	$ret['mensagem'] =  utf8_encode('<img width="64" height="64" alt="" src="http://recantogolfville.com.br/1422650374_678134-sign-check-128.png" align="left">Suas informações de pré-cadastro foram enviadas para a secretaria.<br>Em breve o sistema será liberado e você será notificado pelo e-mail informado, com detalhes de acesso.');
	$ret['arquivo'] =  $arquivo;
	echo json_encode($ret);
	
?>