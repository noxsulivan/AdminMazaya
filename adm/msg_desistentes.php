<?
include('../ini.php');


$pagina = new layout($_SERVER['QUERY_STRING']);


	$db->query("select * from pedidos where idcadastros = 805 and idestagios <= 2 and idcadastros > 0 and mensagem_desistente = 'nao' and data < '".date("Y-m-d",time() - 60*60*24*5)."' order by idpedidos desc limit 2");
	
			  
	$resourceListagem = $db->resourceAtual;
	
	while($res = $db->fetch()){
		
			
			$pedido = new objetoDb('pedidos',$res['idpedidos']);
			
				pre($pedido);
	
					$corpo = '
					<h2>Bom dia, '.$pedido->cadastros->nome.'</h2>
					<p>H� alguns dias atr�s voc� esteve em nossa loja virtual escolhendo produtos Tramontina.<br>
					Mas por algum motivo voc� n�o concluiu sua compra.<br>
					Gostar�amos de saber como podemos ajudar, para que voc� possa ser cliente Mesacor e ter em sua casa o melhor.</p>
					
					<p>Voc� pode voltar ao carrinho de compras e tentar finalizar novamente ou escolher outra forma de pagamento.<br>
					� simples, basta <a href="'.$pagina->localhost.'Cliente/Recuperar/'.$pedido->id.'-'.md5($pedido->cadastros->email).'">clicar aqui</a>.</p>';
//					
					if(count($pedido->itens) == 1){
						$corpo .= '<h3>Este foi o tem selecionado</h3>';
						foreach($pedido->itens as $item){
							$corpo .= "<p>Produto: <strong>".$item->produtos->codigo.' '.$item->produtos->produto.'</strong>';
						}
					}elseif(count($pedido->itens) > 1){
						$corpo .= '<h3>Estes foram os �tens selecionados</h3>';
						foreach($pedido->itens as $item){
							$corpo .= "<p>Produto: <strong>".$item->produtos->codigo.' '.$item->produtos->produto.'</strong>';
						}
					}
//				
//				
//				
//				
					$corpo .= '

					<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua prefer�ncia</p>';
//			
					echo $corpo;
					$db->resource($resourceListagem);
				  
				//$_POST['ebit'] = "sim";
				//$db->editar('pedidos',$pedido->id);
				
				mailClass($pedido->cadastros->email,"Volte para a Mesacor!",$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");
	}
?>