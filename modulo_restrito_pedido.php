<?
	if(intval($pagina->id) > 0){
		
		//$_ids = explode(",",$_id);
		
		//foreach($_ids as $_i){
		list($_idP,$_qtd) = explode("|",$pagina->id);
		
		list($_id,$_con) = explode("-",$_idP);
		
		if($_qtd > 0){
			atualizarQuantidade( $_idP , $_qtd );
		}else{
			$sql = "select * from itens where idpedidos = '".$pedido->id."' and  idprodutos = '".$_id."' and  idclientes = '".$_con."'";
			//pre($sql);
			$db->query($sql);
			if($db->rows == 0){
				adicionarCarrinho($_id, 1, $_con);
			}
		}
		//}
	}
	$isento = false;
	
	//Cartao de teste Visa
	//Número do cartão: 4551 8700 0000 0183
	//Código de segurança: 123
?>

<div class="carrinhoDiv" id="passo_dados">
	<? include('modulo_restrito_pedido_carrinho.php'); ?>
</div>
<? include("modulo_restrito_pedido_instrucoes.php");?>
