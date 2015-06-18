<?

if($cliente){
	//unset($cliente);
	//unset($_SESSION['cliente']);
//pre($cliente);
//die();
}
ob_start();
	switch($pagina->acao){
				
			
			
		case "Confirma":
			include("modulo_cha_confirma.php");
			break;
			
			
		case "ConfirmaConvite":
			include("modulo_cha_confirmaconvite.php");
			include("modulo_lista_casamento_confirmaconvite.php");
			$pagina->id = $db->fetch("select idclientes from convites where chave = '".$pagina->id."'","idclientes");
		case "Presentes":
			$titulo = "Lista de presentes";
			include("modulo_cha_presentes.php");
			break;
			
		case "Meus-Presentes":
			if($cliente->conectado()){
				$titulo = "Lista de presentes";
				include("modulo_cha_meus_presentes.php");
				break;
			}
		case "Convidados":
			if($cliente->conectado()){
				$titulo = "Envio de convites online";
				include("modulo_cha_convidados.php");
				break;
			}
			
		case "Sair":
		case "Meus-dados":
		case "Meus-Dados":
		case "Meus-favoritos":
		case "Alterar-senha":
		case "Lembrar-senha":
		case "Chave":
		case "Cadastro":
				$titulo = "Cadastro";
				include("modulo_cha_cadastro.php");
			break;
			
		default:
			$titulo = "Área de Clientes";
			include("modulo_cha_home.php");
			break;
	}
	$buffer = ob_get_clean();
?>
<div id="sidebar14">
<? if($cliente and @$cliente->conectado()){?>
		<ul><li><h3>Lista de Casamento</h3></li>
        <li><a class="menuA" href="<?=$pagina->localhost.$canal->url?>" >Início</a></li>
        <li><a class="menuA" href="<?=$pagina->localhost.$canal->url?>Meus-Dados" >Meus dados</a></li>
        <li><a class="menuA" href="<?=$pagina->localhost.$canal->url?>Meus-Presentes" >Meus presentes</a></li>
        <li><a class="menuA" href="<?=$pagina->localhost.$canal->url?>Convidados" >Indique aos convidados</a></li>
        <li><a class="menuA" href="<?=$pagina->localhost.$canal->url?>Sair" >Sair</a></li></ul>
<? }else{
		$pagina->menu(array('pai'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id),'nivel'=>2,'proprio'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id)));
	}?>
</div>
<div id="content34">
    <?=$buffer?>
</div>
<?
if(rand(0,80) == 1 ) postarTwitter("NOIVA! Monte sua lista de casamento pelo site, organize, divulgue e concorra a um presente LINDO! http://goo.gl/9JWMq #Tramontina");
if(rand(0,70) == 1 ) postarTwitter("Toda noiva que traz sua #ListadeCasamento para a @mesacor ganha um brinde especial http://www.mesacor.com.br/Lista-de-Casamento/");
?>
