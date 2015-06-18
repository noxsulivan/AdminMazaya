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
			include("modulo_lista_casamento_confirma.php");
			include("modulo_lista_casamento_presentes.php");
			break;
			
			
		case "ConfirmaConvite":
			include("modulo_lista_casamento_confirmaconvite.php");
			$pagina->id = $db->fetch("select idclientes from convites where chave = '".$pagina->id."'","idclientes");
		case "Presentes":
		
			if($pagina->id){
				$noiva = new objetoDb('clientes',$pagina->id);
				$_SESSION['convidado'] = $noiva;
				$convidado = $noiva;
			}
			$titulo = "Lista de presentes";
			include("modulo_lista_casamento_presentes.php");
			break;
			
		case "Meus-Presentes":
			if($cliente->conectado()){
				$titulo = "Lista de presentes";
				include("modulo_lista_casamento_meus_presentes.php");
				break;
			}
		case "Convidados":
			if($cliente->conectado()){
				$titulo = "Envio de convites online";
				include("modulo_lista_casamento_convidados.php");
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
				include("modulo_lista_casamento_cadastro.php");
			break;
		case "Categoria":
				$titulo = "Cadastro";
				include("_produtos_categoria.php");
			break;
			
		default:
			$titulo = "Área de Clientes";
			include("modulo_lista_casamento_home.php");
			break;
	}
	$buffer = ob_get_clean();
	
	//pre($cliente);
?>
<div id="sidebar14">
<? if($cliente and @$cliente->conectado()){?>
		<ul><li><h3>Lista de Presentes</h3></li>
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