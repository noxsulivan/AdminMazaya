<?

include("ini.php");
$pagina = new layout($_SERVER['QUERY_STRING']);
$_tmp = explode("?",$_SERVER['REQUEST_URI']);

parse_str($_tmp[1]);
parse_str($pagina->extra);
ini_set("allow_url_fopen", 1); // Ativa a diretiva 'allow_url_fopen' para uso do 'fsockopen'
extract($_POST);

define('TOKEN','46B4716026244020A6D522C01BF41918');


define('VERSAO', "1.1.0");



define("ENDERECO_BASE", "https://ecommerce.cielo.com.br");
define("ENDERECO", ENDERECO_BASE."/servicos/ecommwsec.do");

//https://ecommerce.cielo.com.br/servicos/ecommwsec.do

//define("LOJA", "1027957533");
//define("LOJA_CHAVE", "77451983034fecb456d0e27ee12909ea373a4227238e816534ec7caef6e86f2f");
define("CIELO", "1027957533");
define("CIELO_CHAVE", "77451983034fecb456d0e27ee12909ea373a4227238e816534ec7caef6e86f2f");

echo "<h1>Conferência de pedidos em aberto</h1>";
$sql = " select * from pedidos where tipo_pagamento like '%parcela%' and idestagios = 1 and data > ADDDATE(curdate(), -40) and data < ADDDATE(curdate(), -2) order by idpedidos DESC limit 15";
$db->query($sql);
while($res = $db->fetch()){

						$pedido = new objetoDb("pedidos",$res['idpedidos']);	
						
											
						$PedidoCielo = new Pedido();
						
						$PedidoCielo->FromString($pedido->cielo);
						
						pre($PedidoCielo->dadosPedidoNumero." - ".$PedidoCielo->dadosPedidoValor." - ".$PedidoCielo->status);
						
						// Consulta situação da transação
						$objResposta = $PedidoCielo->RequisicaoConsulta();
						
						
						pre($objResposta);
						
						if($objResposta->status == 5){
							$_POST['status_transacao'] = utf8_decode($objResposta->autorizacao->mensagem);
							$_POST['idestagios'] = 6;
						}elseif($objResposta->status == 6){
							$_POST['status_transacao'] = utf8_decode($objResposta->captura->mensagem);
						}elseif($objResposta->status == 9){
							$_POST['status_transacao'] = utf8_decode($objResposta->cancelamento->mensagem);
							$_POST['idestagios'] = 6;
						}
						
							
							
							
						
						// Atualiza status
						$PedidoCielo->status = $objResposta->status;
						
						if($PedidoCielo->status == '4' || $PedidoCielo->status == '6'){
							//autorizada ou capturada
							$finalizacao = true;
							$_POST['idestagios'] = 3;
							

							
								$corpo = '
								<h2>Olá, '.$pedido->cadastros->nome.'</h2>
								<p>&nbsp;</p>
								<p>Este é o resultado do processamento do pedido de No. '.str_pad($pedido->id,6,0,STR_PAD_LEFT).'. Leia essa mensagem com muita ATENÇÃO.</p>
								
										<h3>Dados do pedido</h3>
										
										Número do pedido: <strong>' . $pedido->id . '</strong><br>
										Total: <strong>R$' . number_format($pedido->valor,2,",",".") . '</strong><br>
										Transação: <strong>'.$pedido->transacaoid.'</strong> - Data da transação: <strong>' . $pedido->data . '</strong><br>
										Forma de pagamento escolhida: <strong>'.normaliza($pedido->tipo_pagamento).'</strong><br>
										Status da transação: <strong>' . $_POST['status_transacao'] . '</strong><br>
										Parcelas: <strong>'.($pedido->parcelas == 1 ? '1 vez': $pedido->parcelas." vezes").'</strong><br>
										Forma de envio: <strong>'.( $pedido->tipo_frete == "PAC" ? "Encomenda normal" : ( $pedido->tipo_frete == "eSEDEX" ? "eSEDEX" : "SEDEX")).'</strong> - Prazo de entrega: <strong>'.$pedido->prazo.' dias úteis</strong><br>
										Valor do frete: <strong>R$'.number_format($pedido->valor_frete,2,",",".").'</strong>';
										if($pedido->idcupons > 1){
											$corpo = 'Cupom de desconto: <strong>R$'.number_format($pedido->cupons->valor,2,',','.').'</strong><br />';
										}
										
								$corpo .= '					
										<h3>Ítens solicitados</h3>';
										$corpo .= $pedido->descricao;
										$corpo .= $pedido->dados_entrega;
								$corpo .= '
								<p>IMPORTANTE: O Prazo de entrega informado acima é válido para compras efetuadas até 20:00 horas com cartão de crédito aprovado na 1º tentativa.<br>
								Caso a data prevista para entrega corresponder a algum feriado na região de entrega, pedimos gentilmente que acrescente 1 dia útil ao prazo mencionado acima.<br>
								ATENÇÃO: Tenha sempre com você a nota fiscal e a embalagem original dos produtos. Somente com estes itens serão possíveis operações como troca ou devolução de produtos.</p>
		
								<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua preferência</p>
												<p>Avalie a sua compra e ajude a Mesacor a melhorar o atendimento:
												<a href="https://www.ebitempresa.com.br/bitrate/pesquisa1.asp?empresa=1476185"><img src="http://www.mesacor.com.br/_imagens/banner_ebit.jpg"></a>
												</p>';
												
												
								//unset($_SESSION['itensCarrinho']);
								//unset($_COOKIE['pedidoID']);
								//setcookie("pedidoID",$_COOKIE['pedidoID'],time()-(60*60*24*30),'/');
						
							$_POST['data_transacao'] = date("d/m/Y H:I:s");
							$_POST['retorno_transacao'] = serialize($_REQUEST);
							$db->editar('pedidos',$pedido->id);
							
							ob_start();
							mailClass("noxsulivan@gmail.com","Registro do pedido nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),pre($PedidoCielo,true).pre($pedido,true));
							@mailClass($pedido->cadastros->email,"Pedido nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");
							@mailClass($pagina->configs['email_suporte'],"Pedido nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pedido->cadastros->email,$pedido->cadastros->nome_completo);
							ob_end_clean();
						}elseif($PedidoCielo->status == '3' || $PedidoCielo->status == '5' || $PedidoCielo->status == '9' || $PedidoCielo->status == '12'){
							//nao autenticada, nao autorizada, cancelada ou em cancelamento
							$finalizacao = false;
							$_POST['idestagios'] = 6;
							

							
								$corpo = '
								<h2>Olá, '.$pedido->cadastros->nome.'</h2>
								<p>&nbsp;</p>
								<p>Este é o resultado do processamento do pedido de No. '.str_pad($pedido->id,6,0,STR_PAD_LEFT).'. Leia essa mensagem com muita ATENÇÃO.</p>
								
										<h3>Dados do pedido</h3>
										
										Número do pedido: <strong>' . $pedido->id . '</strong><br>
										Total: <strong>R$' . number_format($pedido->valor,2,",",".") . '</strong><br>
										Transação: <strong>'.$pedido->transacaoid.'</strong> - Data da transação: <strong>' . $pedido->data . '</strong><br>
										Forma de pagamento escolhida: <strong>'.normaliza($pedido->tipo_pagamento).'</strong><br>
										Status da transação: <strong>' . $_POST['status_transacao'] . '</strong><br>
										Parcelas: <strong>'.($pedido->parcelas == 1 ? '1 vez': $pedido->parcelas." vezes").'</strong><br>
										Forma de envio: <strong>'.( $pedido->tipo_frete == "PAC" ? "Encomenda normal" : ( $pedido->tipo_frete == "eSEDEX" ? "eSEDEX" : "SEDEX")).'</strong> - Prazo de entrega: <strong>'.$pedido->prazo.' dias úteis</strong><br>
										Valor do frete: <strong>R$'.number_format($pedido->valor_frete,2,",",".").'</strong>';
										if($pedido->idcupons > 1){
											$corpo = 'Cupom de desconto: <strong>R$'.number_format($pedido->cupons->valor,2,',','.').'</strong><br />';
										}
										
								$corpo .= '					
										<h3>Ítens solicitados</h3>';
										$corpo .= $pedido->descricao;
										$corpo .= $pedido->dados_entrega;
								$corpo .= '
								<p>IMPORTANTE: O Prazo de entrega informado acima é válido para compras efetuadas até 20:00 horas com cartão de crédito aprovado na 1º tentativa.<br>
								Caso a data prevista para entrega corresponder a algum feriado na região de entrega, pedimos gentilmente que acrescente 1 dia útil ao prazo mencionado acima.<br>
								ATENÇÃO: Tenha sempre com você a nota fiscal e a embalagem original dos produtos. Somente com estes itens serão possíveis operações como troca ou devolução de produtos.</p>
		
								<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua preferência</p>
												<p>Avalie a sua compra e ajude a Mesacor a melhorar o atendimento:
												<a href="https://www.ebitempresa.com.br/bitrate/pesquisa1.asp?empresa=1476185"><img src="http://www.mesacor.com.br/_imagens/banner_ebit.jpg"></a>
												</p>';
												
												
								//unset($_SESSION['itensCarrinho']);
								//unset($_COOKIE['pedidoID']);
								//setcookie("pedidoID",$_COOKIE['pedidoID'],time()-(60*60*24*30),'/');
						
							$_POST['data_transacao'] = date("d/m/Y H:I:s");
							$_POST['retorno_transacao'] = serialize($_REQUEST);
							$db->editar('pedidos',$pedido->id);
							
							ob_start();
							mailClass("noxsulivan@gmail.com","Registro do pedido nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),pre($PedidoCielo,true).pre($pedido,true));
							@mailClass($pedido->cadastros->email,"Pedido nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");
							@mailClass($pagina->configs['email_suporte'],"Pedido nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pedido->cadastros->email,$pedido->cadastros->nome_completo);
							ob_end_clean();
						}elseif($PedidoCielo->status == '0' || $PedidoCielo->status == '2' || $PedidoCielo->status == '10'){
							$finalizacao = false;
							$_POST['idestagios'] = 2;
						}else{
							//criada
							$finalizacao = false;
							$_POST['idestagios'] = 1;
						}
												
	
}
die();
							
?>