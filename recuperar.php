<?
include('ini.php');


$pagina = new layout($_SERVER['QUERY_STRING']);

$sql = "select idpedidos from pedidos where idestagios = 6 and convite_recuperacao < 1 order by idpedidos desc limit 20";//
$sql = "select idpedidos from pedidos where idestagios = 1 and idcadastros > 0 and convite_recuperacao < 1 order by idpedidos desc limit 30";//
$db->query($sql);

$resourceListagem = $db->resourceAtual;

while($res = $db->fetch()){
	
	pre($res['idpedidos']);
	
	$pedido = new objetoDb("pedidos",$res['idpedidos']);
	
	

	
					$corpo = '
					<h2>Bom dia, '.$pedido->cadastros->nome.'</h2>
					<p>H� alguns dias atr�s voc� esteve em nossa loja virtual escolhendo produtos Tramontina.<br>
					Mas por algum motivo voc� n�o concluiu sua compra.<br>
					Gostar�amos de saber como podemos ajudar, para que voc� possa ser cliente Mesacor e ter em sua casa o melhor.</p>
					
					<p>Voc� pode voltar ao carrinho de compras e tentar finalizar novamente ou escolher outra forma de pagamento.<br>
					� simples, basta <a href="https://www.mesacor.com.br/Cliente/Recuperar/'.$pedido->id.'-'.md5($pedido->cadastros->email).'">clicar aqui</a>.</p>';
//					
					if(count($pedido->itens) == 1){
						$corpo .= '<h3>Este foi os tem selecionados</h3>';
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
					
				
				$_POST['convite_recuperacao'] = 1;
				$db->editar('pedidos',$pedido->id);
//				
				mailClass($pedido->cadastros->email,"Volte para a Mesacor!",$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");

}
					
?>