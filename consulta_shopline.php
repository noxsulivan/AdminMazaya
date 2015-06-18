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



			$tipos_de_situacao['01'] = 1;
			$tipos_de_situacao['02'] = 2;
			$tipos_de_situacao['03'] = 3;
			$tipos_de_situacao['04'] = 4;
			$tipos_de_situacao['00'] = 5;
			$tipos_de_situacao['05'] = 6;
			$tipos_de_situacao['06'] = 7;
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://shopline.itau.com.br/shopline/consulta.asp");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
	$Itau = new Itaucripto;
			
echo "<h1>Conferência de boletos em aberto</h1>";
$sql = " select * from pedidos where tipo_pagamento like '%boleto%' and idestagios = 1 and data > ADDDATE(curdate(), -50) and data < ADDDATE(curdate(), -2) order by idpedidos DESC limit 15";
$db->query($sql);
while($res = $db->fetch()){

			$pedido = new objetoDb("pedidos",$res['idpedidos']);
			
			//$consulta = $Itau->geraConsulta('J0046371390001000000011068', $res['idboletos'], 1, 'VIAGEM20COTA0709');
			$consulta = $Itau->geraConsulta('J0043497660001370000012814', $res['idpedidos'], 1, 'MESACORTRA243101');
			$data = http_build_query(array("DC" => $consulta));
			
						
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$xml = simplexml_load_string(curl_exec($ch));
			$_a = array();
			
			foreach($xml->PARAMETER->PARAM as $param){
				$t = $param->attributes();
				$_a[(string)$t['ID']] = (string)$t['VALUE'];
			}
			
			//if($_a[] == ){
				
				
										$_POST['idestagios'] = 2;
										$_POST['status_transacao'] = 'Aguardando confirmação do pagamento';
										$_POST['data_transacao'] = date("d/m/Y H:I:s");
										$_POST['retorno_transacao'] = serialize($_REQUEST);
										$db->editar('pedidos',$pedido->id);
										
									
														
																	$corpo = '
																	<h2>'.$pedido->cadastros->nome.'</h2>
																	<h3>Falta apenas 1 passo para você finalizar a sua compra</h3>
																	<p>Imprima o boleto no link e pague o quanto antes.<br><a href="'.$pagina->localhost.'_Request/2viaBoleto/'.$pedido->id.'/'.md5($pedido->id).'">Clique para imprimir o boleto</a></p>
																	<p>O boleto <strong>não será</strong> enviado por correpondência</p>
																				Número do pedido: <strong>' . $pedido->id . '</strong><br>
																				Total: <strong>R$' . number_format($pedido->valor,2,",",".") . '</strong><br>
																				Valor do frete: <strong>R$ '.number_format($pedido->valor_frete,2,",",".").'</strong><br>
																				Data da transação: <strong>'.$pedido->data.'</strong><br>
																				Forma de pagamento escolhida: <strong>Boleto - '.$pedido->tipo_pagamento.'</strong><br>
																				Status da transação: <strong>Aguardando confirmação do pagamento</strong><br>
																				
																			<h3>Ítens solicitados</h3>';
																				$corpo .= $pedido->descricao;
																				$corpo .= $pedido->dados_entrega;
																			
																	$corpo .= '
																	<p>IMPORTANTE: O Prazo de entrega informado acima é válido a partir da data de confirmação do pagamento pelo banco<br>
																	ATENÇÃO: Tenha sempre com você a nota fiscal e a embalagem original dos produtos. Somente com estes itens serão possíveis operações como troca ou devolução de produtos.</p>
											
																	<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua preferência</p>
																	<p>Avalie a sua compra e ajude a Mesacor a melhorar o atendimento:
																	<a href="https://www.ebitempresa.com.br/bitrate/pesquisa1.asp?empresa=1476185"><img src="http://www.mesacor.com.br/_imagens/banner_ebit.jpg"></a>
																	</p>';
					
												ob_start();
												mailClass("noxsulivan@gmail.com","Registro do pedido nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),pre($pedido,true));
												@mailClass($pedido->cadastros->email,"Pedido nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");
												@mailClass($pagina->configs['email_suporte'],"Pedido nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pedido->cadastros->email,$pedido->cadastros->nome_completo);
												ob_end_clean();
			//}
			pre($_a);
			flush();
												
	
}
	curl_close($ch);
die();
							
?>