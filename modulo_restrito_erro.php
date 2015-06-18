<?=$pagina->caminho(array($canal->canal => $canal->url))?>
<? if($cadastro->erro){?>

	<h2>Ocorreu um erro </h2>
	<h3>
	  <?=$cadastro->erro?>
	</h3>
<? }else{?>
	<h2>
	  <?=$canal->canal?>
	</h2>
	<?=$canal->texto?>
	<h3> Login </h3>
	<div id="formulario">
	  <form action="<?=$pagina->localhost.$canal->url?>Login" method="post" id="formularioForm">
		<label for="_email">E-mail</label>
		<input id="_email" name="_email" type="text" value="" class="inputField inputField" />
		<label for="senha">Senha</label>
		<input id="_senha" name="_senha" type="password" value="" class="inputField inputField" /><br />

		<button type="submit" class="submitButton" id="submitButton">Entrar</button>
	  </form>
	  <script type="text/javascript">
		new Validation('formularioForm');
		</script>
	</div>
<? }?>
	<h3>Cadastro</h3>
	<ul  class="listaFuncoes">
	  <li><a class="funcaoDados" href="<?=$pagina->localhost.$canal->url?>Cadastro"> Para se cadastrar, clique aqui! </a></li>
	</ul>
