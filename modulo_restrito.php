<?
ob_start();
	  
	switch($pagina->acao){
		case "Meus-dados":
		case "Meus-favoritos":
		case "Alterar-senha":
		case "Lembrar-senha":
		case "Chave":
		case "Sair":
		case "Salvar":
		case "Cadastro":
				$titulo = "Cadastro";
				include("modulo_restrito_cadastro.php");
			break;
				
		case "Confirma":
			$titulo = "Confirmação de Cadastro";
			include("modulo_restrito_confirma.php");
			break;
			
		case "Historico":
		case "Pedidos":
			$titulo = "Pedidos";
			include("modulo_restrito_historico.php");
			break;
			
		case "Recuperar":
			include("modulo_restrito_recuperar_pedido.php");
			break;
			
		case "Pedido":
		case "Processar":
		case "Carrinho":
			$titulo = "Pedidos";
			include("modulo_restrito_pedido.php");
			break;
			
		case "Pagamento":
			if($cadastro->id == 100){
				$titulo = "Confirmação dos dados para Pagamento";
				include("modulo_restrito_pagamento_cielo.php");
			}else{
				$titulo = "Confirmação dos dados para Pagamento";
				include("modulo_restrito_pagamento.php");
			}
			break;
					
		case "Retorno":
			$titulo = "Confirmação de transação";
			include("modulo_pagseguro.php");
			break;
			
		default:
			$titulo = "Área de Clientes";
			include("modulo_restrito_home.php");
			break;
	}
	$buffer = ob_get_clean();
?>
<?php /*?><div id="sidebar14">
<?
	if($cadastro->conectado() and ereg("49|52",$canal->canais->id))
		if($cadastro->tipos_de_restricoes->id == 1){
			$pagina->menu(array('pai'=>49,'nivel'=>2,'submenu'=>1,'proprio'=>49,'status'=>1));
		}else{
			$pagina->menu(array('pai'=>52,'nivel'=>2,'submenu'=>1,'proprio'=>52,'status'=>1));
		}
	else
		$pagina->menu(array('pai'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id),'nivel'=>2,'proprio'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id)))?>
</div><?php */?>
<div id="content44">
        <div class="float-right">
          <?PHP if($cadastro->conectado()){?>
          <a href="<?PHP echo $pagina->localhost?>Cliente/Cadastro" class="botao grey float-left"><strong>Meu cadastro</strong> </a>
		  <a href="<?PHP echo $pagina->localhost?>Cliente/Carrinho" class="botao grey float-left">Meu Carrinho <?PHP echo (count($_SESSION['itensCarrinho']) ? " <strong>(".array_sum($_SESSION['itensCarrinho']).")</strong>": '')?> </a>
		  <a href="<?PHP echo $pagina->localhost?>Cliente/Pedidos" class="botao grey float-left">Minhas Compras</a>
		  <a href="<?PHP echo $pagina->localhost?>Cliente/Sair" class="botao grey float-left">Sair</a>
          <?PHP }else{?>
          <a href="<?PHP echo $pagina->localhost?>Cliente/Cadastro" class="botao grey float-left">Cadastre-se </a>
		  <a href="<?PHP echo $pagina->localhost?>Cliente/Carrinho" class="botao grey float-left">Carrinho de Compras <?PHP echo (count($_SESSION['itensCarrinho']) ? " <strong>(".array_sum($_SESSION['itensCarrinho']).")</strong>": '')?> </a>
		  <a href="<?PHP echo $pagina->localhost?>Cliente" class="botao grey float-left">Login</a>
          <?PHP }?>
        </div>
    <?=$buffer?>
</div>