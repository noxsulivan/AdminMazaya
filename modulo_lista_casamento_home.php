
    <div id="corpo">
    
    
    <? if($cliente->erro){?>

<h2>Ocorreu um erro </h2>
<h3>
  <?=$cliente->erro?>
</h3>
<? } ?>
<? if($cliente and @$cliente->conectado()){ ?>
<h2>Lista de casamento da noiva
  <?=$cliente->nome_noiva?>
  <?=$cliente->sobrenome_noiva?>
  <br />
  Seja bem-vinda</h2>
<div class="content24">
  <h3><a href="<?=$pagina->localhost.$canal->url?>Meus-Dados">Confira seus dados</a></h3>
  <p>No menu <a href="<?=$pagina->localhost.$canal->url?>Meus-Dados">"Meus dados"</a> constam as suas informações de cadastro, confira se estão corretas pois precisaremos entrar em contato com vocês posteriormente para realizarmos a entrega de seus presentes de casamento adquiridos</p>
  <h3><a href="<?=$pagina->localhost.$canal->url?>Meus-Presentes">Visualize sua Lista de Casamento</a></h3>
  <p>Gerencie os itens que gostaria de ganhar de presente e acompanhe o andamento, sabendo quem comprou online, através da <a href="<?=$pagina->localhost.$canal->url?>Meus-Presentes">Lista de Presentes</a></p>
  <h3><a href="<?=$pagina->localhost?>Cha-de-Panela/Meus-Presentes">Visualize sua Lista de Chá e Panela</a></h3>
  <p>Gerencie os itens que gostaria de ganhar de presente e acompanhe o andamento, sabendo quem comprou online, através da <a href="<?=$pagina->localhost?>Cha-de-Panela/Meus-Presentes">Lista de Presentes</a></p>
</div>
<div class="sidebar24">
  <h3>Adicione ítens em sua lista</h3>
  <p>Para montar sua lista de presentes, navegue agora pelo site e em cada página de produtos, no submenu ao lado da foto, constará o botão "Por em minha lista", onde onde o produto poderá ser adicionado facilmente, clique nele quantas vezes desejar até atingir o total de unidades desejadas de cada produto.</p>
  <h3><a href="<?=$pagina->localhost.$canal->url?>Convidados">Divulgue para seus convidados</a></h3>
  <p>Após estar tudo ok com sua lista, convide seus amigos para acessar a sua lista e comprar seus presentes com muito mais comodidade em nossa loja virtual. No menu <a href="<?=$pagina->localhost.$canal->url?>Convidados">"Indique aos convidados"</a>, informe os endereços de e-mail para onde quiser enviar esse lembrete. É rapidinho e estimulante.</p>
</div>
  <div class="clear"></div>
<? }else{?>
<?=$canal->texto?>
<div class="formulario">
<h2>Convidados</h2>
  <h3>Selecione a noiva ou o noivo para visualizar a lista</h3>
  <p>Se você chegou até nossa loja virtual através da indicação de<br />uma lista de casamento, selecione a seguir o casal.</p>
  <div id="formConvidado">
						<form action="http://mesacorpresentes.com.br/Lista-de-Casamento" method="post"
						onsubmit="return redirecionaNoiva($('#nome_noiva_home').val())">
						  <input name="acao" id="acao" type="hidden" value="registrarConvidado" />
						  <div class="campo">
							<label for="nome_noiva_home">Nome dos noivos</label>
							<select id="nome_noiva_home" name="nome_noiva">
							  <?
											  $db->query("select * from clientes order by nome_noiva");
											  while($res = $db->fetch()){
												  $_noivos[$res['nome_completo_noiva']] = '<option value="'.$res['url'].'">'.$res['nome_completo_noiva'].' & '.$res['nome_completo_noivo'].'</option>';
											  }
											  $db->query("select * from clientes order by nome_noivo");
											  while($res = $db->fetch()){
												  $_noivos[$res['nome_completo_noivo']] = '<option value="'.$res['url'].'">'.$res['nome_completo_noivo'].' & '.$res['nome_completo_noiva'].'</option>';
											  }
											  
											  ksort($_noivos);
											  
											  foreach($_noivos as $n)
											  	echo $n;
											  ?>
							</select>
									<label for="">&nbsp;</label>
									<button type="submit" class="awesome orange">Entrar</button>
						  </div>
						</form>
  </div>
  <div class="clear espaco"></div>
  <? if($convidado->id){?>
  <h3>Você já está conectado a lista de:</h3>
  <h4><?=$convidado->nome_noiva.' '.$convidado->sobrenome_noiva.' &amp; '.$convidado->nome_noivo.' '.$convidado->sobrenome_noivo?></h4>
  <a href="<?=$pagina->localhost.$canal->url?>Presentes" class="awesome magenta">Visualize lista de presentes</a>
  <? }?>
</div>
<div class="formularioGrd">
<h2>Área dos Noivos</h2>
  <h3>Já tem sua lista de casamento em nosso site?</h3>
  <div id="formLogin">
    <form action="<?=$pagina->localhost.$canal->url.$pagina->acao?>Meus-Presentes" method="post" id="formLoginForm">
	<?php /*?>onsubmit="return sendWindowForm('formLogin' ,'formLoginForm')"<?php */?>
      <input name="acao" type="hidden" value="loginCliente" />
      <div class="campo">
        <label for="_email">E-mail</label>
        <input name="_email" type="text" value="" class="required inputField inputMedio" />
      </div>
      <div class="campo">
        <label for="_senha">Senha</label>
        <input name="_senha" type="password" value="" class="required inputField inputMedio" />
      </div>
      <div class="campo">
				<label for="">&nbsp;</label>
				<button type="submit" class="awesome orange">Acessar painel</button>
      </div>
    </form>
  <h3>Não lembra sua senha?</h3>
  <p>Você pode refazer sua senha caso não esteja conseguindo acessar seu painel, é fácil!</p>

    <form action="<?=$pagina->localhost.$canal->url.$pagina->acao?>Lembrar-senha" method="post" id="formSenhaForm">
      <div class="campo">
				<label for="">&nbsp;</label>
				<button type="submit" class="awesome orange">Recuperar senha</button>
      </div>
    </form>
  <h3>Montou sua lista na loja?</h3>
    <p class="clear espaco">Se você montou sua lista de presentes diretamente na loja e não criou sua senha na ocasião, <a href="<?=$pagina->localhost?>Contato">entre em contato</a> e solicite agora mesmo.</p>
  </div>
  <div class="clear"></div>
  <h3>Crie sua lista de presentes</h3>
  <p>Faça seu cadastro e navegue pelo site escolhendo os presentes que gostaria de ganhar, depois, divulgue para seus convidados. É bastante simples em alguns minutos fica pronto.</p>
  
    <form action="<?=$pagina->localhost.$canal->url.$pagina->acao?>Cadastro" method="post" id="formSenhaForm">
      <div class="campo">
				<label for="">&nbsp;</label>
				<button type="submit" class="awesome orange">Criar meu cadastro e minha lista! </button>
      </div>
    </form>
	
<? } ?>

    </div>