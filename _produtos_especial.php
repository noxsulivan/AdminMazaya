<?
parse_str($pagina->extra);


ob_start();

	$titulo = "Produtos";
	//$DN = 1;
	if($pagina->tg == 'T' or $pagina->tg == 'M' or $pagina->tg == 'Twitter'){
		$pagina->id = $pagina->acao;
		$pagina->acao = "Ver";
		$canal = new objetoDb('canais','Produtos');
	}
	switch($pagina->acao){
		
		case "EnviarPedido":
			include('_produtos_pedido.php');
		break;
			
		case 'Ver' : 
			include("_produtos_descricao.php");
		break;
		case "Categoria":
			include('_produtos_categoria.php');
			include('_produtos_listagem.php');
		break;
		case "Linha":
			include('_produtos_linha.php');
			include('_produtos_listagem.php');
		break;
		case "Fabricante":
			include('_produtos_fabricante.php');
			include('_produtos_listagem.php');
		break;
		case "Buscar":case "Busca":
			include('_produtos_busca.php');
			include('_produtos_listagem.php');
		break;
		default:
			include('_produtos_hotsite.php');
			include('_produtos_listagem.php');
		break;
	}
$bufferProdutos = ob_get_clean();
?>


    <div id="corpoL">
    <?=$bufferProdutos?>
    </div>