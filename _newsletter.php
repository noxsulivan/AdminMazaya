<div id="sidebar14">
<?
	if($cadastro->conectado() and ereg("49|52",$canal->canais->id))
		if($cadastro->tipos_de_restricoes->id == 1){
			$pagina->menu(array('pai'=>49,'nivel'=>2,'submenu'=>1,'proprio'=>49,'status'=>1));
		}else{
			$pagina->menu(array('pai'=>52,'nivel'=>2,'submenu'=>1,'proprio'=>52,'status'=>1));
		}
	else
		$pagina->menu(array('pai'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id),'nivel'=>2,'proprio'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id)))?>
</div>
<div id="content34">
		<?
		if($pagina->acao == 'Remover'){
		?>
			<h3>Confirmação</h3>
			<h4>Você tem certeza de que deseja remover seu e-mail de nosso newsletter?
			<a href="<?=$pagina->localhost.$canal->url?>ConfirmarRemocao/<?=$pagina->id?>">Sim</a> ou <a href="<?=$pagina->localhost?>">Não</a></h4>
		<?
		}elseif($pagina->acao == 'ConfirmarRemocao' and $pagina->id){
			mysql_query("update leitores set ativo = 'nao', temp = 6 where idleitores = '".$pagina->id."'");
			echo "<h3>Seu e-mail foi removido de nosso newsletter. Agradecemos a participação e desejamos que você possa um dia se interessar novamente em receber nossas novidades.</h3>";
		}elseif($pagina->acao == 'Confirma'){
			mysql_query("update leitores set ativo = 'sim' where chave = '".$pagina->id."'");
			echo "<h3>Seu e-mail foi confirmado com sucesso. Agradecemos pelo interesse.</h3>";

		}elseif($pagina->acao == 'Ver'){
			$news = new objetoDb("mailings",$pagina->id);
			echo $news->corpo;
		}
		?>
</div>