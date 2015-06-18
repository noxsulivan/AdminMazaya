<?


	$ret['total'] =  $db->rows("select * from condominos ");
	$ret['done'] =  $db->rows("select * from condominos where enviado = 1");
	$ret['percent'] =  round(($ret['done'] * 100 )/ $ret['total']);



$db->query("select * from condominos where enviado = 0 order by idquadras, lote");
$res = $db->fetch();
$cond = new objetoDb("condominos",$res['idcondominos']);


$senha = substr(md5(strtolower($cond->email).time()),0,6);
		$corpo = "<p>Caro Sócio, ".ucwords(strtolower($cond->nome))."</p>
		<p>A partir de agora estaremos utilizando um novo sistema para o controle de acesso ao condomínio.</p>
		<p>Este sistema foi desenvolvido com foco nas necessidades específicas do Recanto Golf Ville baseadas nas observações
		que a diretoria tem feito e com a contribuição de muitos associados.</p>
		<p>Tem como foco principal a interação dos morados com a Diretoria, na resolução de problemas diários, e com a Portaria de
		modo a agilizar o dia-a-dia e controlar com mais eficiência o fluxo de pessoas.</p>
		<p>Nosso objetivo é trazer aos sócios uma ferramenta que possibilite facilitar o convívio e reforçar a segurança de todos
		visto que servirá como um canal online de comunicação rápida e confiável.</p>
		<p>Acesse já e comece a cadastrar seus funcionários e prestadores de serviço. Estas informações estarão disponíveis imediatamente para que a portaria controle o acesso.</p>
		
		<p>Para ter acesso a este sistema, salve em seus favoritos, e acesse o site:</p>
		<ul><li><h3><a href='http://www.recantogolfville.com.br/'>http://www.recantogolfville.com.br/</a></h3></li>
		<li><h3>Login/Email: ".strtolower($cond->email)."</h3></li>
		<li><h3>E senha: ".$senha."</h3></li></ul>
		
		Att<br>
		Secretaria";
if(email_ok(strtolower(trim($cond->email)))){

		//mailClass($cond->email,'Boas vindas ao Sistema Online do Recanto Golf Ville',$corpo,"secretaria@recantogolfville.com.br",utf8_encode("Administração Recanto GolfVille"),$arquivo);
		mailClass($cond->email,'Boas vindas ao Sistema Online do Recanto Golf Ville',$corpo,"secretaria@recantogolfville.com.br",utf8_encode("Administração Recanto GolfVille"),$arquivo);

		$_POST['idtipos_de_usuarios'] = 4;
		$_POST['idcondominos'] = $cond->id;
		$_POST['nome'] = $cond->nome;
		$_POST['senha'] = md5($senha);
		$_POST['email'] = $_POST['login'] = strtolower($cond->email);
		//$db->inserir('usuarios');
}
			$db->query($sql = "update condominos set enviado = 1 where idcondominos = '".$cond->id."'");


		$ret['now'] = "Enviado para ".$cond->nome;

	$ret['status'] =  "ok";
	echo json_encode($ret);
	
?>