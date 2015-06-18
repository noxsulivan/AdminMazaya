<?
	//foreach($_COOKIE as $k => $v){
		//if(preg_match("/idpedidos/i",$k))
			//setcookie("pedidoID",$v,time()-(60*60*24*30),'/');
	//}

	//$pedido = new objetoDb("pedidos",intval($pagina->id));
	
	//setcookie("pedidoID",$pedido->id,time()+(60*60*24*30),'/');
	
	
	
?>



<div class="carrinhoDiv" id="passo_dados">
	<? include('modulo_restrito_pedido_carrinho.php'); ?>
</div>