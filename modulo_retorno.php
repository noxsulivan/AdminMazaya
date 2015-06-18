<?
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


if($_POST['pedido']){
	$pedido = new objetoDb("pedidos",(int)$_POST['pedido']);
}
if($pagina->id){
	$pedido = new objetoDb("pedidos",$pagina->id);
}

if($pedido->id){

$echo = '';
switch($pagina->acao){
	case "CieloeCommerce":
	
						
						$PedidoCielo = new Pedido();
						$PedidoCielo->FromString($pedido->cielo);
						
if(preg_match("/suliva/i",$cadastro->nome)){
						pre($PedidoCielo);
}
						
						// Consulta situa��o da transa��o
						$objResposta = $PedidoCielo->RequisicaoConsulta();
						
						// Atualiza status
						$PedidoCielo->status = $objResposta->status;
						
						if($PedidoCielo->status == '4' || $PedidoCielo->status == '6'){
							//autorizada ou capturada
							$finalizacao = true;
							$_POST['idestagios'] = 3;
						}elseif($PedidoCielo->status == '3' || $PedidoCielo->status == '5' || $PedidoCielo->status == '9' || $PedidoCielo->status == '12'){
							//nao autenticada, nao autorizada, cancelada ou em cancelamento
							$finalizacao = false;
							$_POST['idestagios'] = 6;
						}elseif($PedidoCielo->status == '0' || $PedidoCielo->status == '2' || $PedidoCielo->status == '10'){
							$finalizacao = false;
							$_POST['idestagios'] = 2;
						}else{
							//criada
							$finalizacao = false;
							$_POST['idestagios'] = 1;
						}
							

							
								$corpo = '
								<h2>Ol�, '.$pedido->cadastros->nome.'</h2>
								<p>&nbsp;</p>
								<p>Este � o resultado do processamento do pedido de No. '.str_pad($pedido->id,6,0,STR_PAD_LEFT).'. Leia essa mensagem com muita ATEN��O.</p>
								
										<h3>Dados do pedido</h3>
										
										N�mero do pedido: <strong>' . $pedido->id . '</strong><br>
										Total: <strong>R$' . number_format($pedido->valor,2,",",".") . '</strong><br>
										Transa��o: <strong>'.$pedido->transacaoid.'</strong> - Data da transa��o: <strong>' . $pedido->data . '</strong><br>
										Forma de pagamento escolhida: <strong>'.normaliza($pedido->tipo_pagamento).'</strong><br>
										Status da transa��o: <strong>' . $PedidoCielo->getStatus() . '</strong><br>
										Parcelas: <strong>'.($pedido->parcelas == 1 ? '1 vez': $pedido->parcelas." vezes").'</strong><br>
										Forma de envio: <strong>'.( $pedido->tipo_frete == "PAC" ? "Encomenda normal" : ( $pedido->tipo_frete == "eSEDEX" ? "eSEDEX" : "SEDEX")).'</strong> - Prazo de entrega: <strong>'.$pedido->prazo.' dias �teis</strong><br>
										Valor do frete: <strong>R$'.number_format($pedido->valor_frete,2,",",".").'</strong>';
										if($pedido->idcupons > 1){
											$corpo = 'Cupom de desconto: <strong>R$'.number_format($pedido->cupons->valor,2,',','.').'</strong><br />';
										}
										
								$corpo .= '					
										<h3>�tens solicitados</h3>';
										$corpo .= $pedido->descricao;
										$corpo .= $pedido->dados_entrega;
								$corpo .= '
								
								<p>IMPORTANTE: O Prazo de entrega informado acima � v�lido para compras efetuadas at� 20:00 horas com cart�o de cr�dito aprovado na 1� tentativa.<br>
								Caso a data prevista para entrega corresponder a algum feriado na regi�o de entrega, pedimos gentilmente que acrescente 1 dia �til ao prazo mencionado acima.<br>
								ATEN��O: Tenha sempre com voc� a nota fiscal e a embalagem original dos produtos. Somente com estes itens ser�o poss�veis opera��es como troca ou devolu��o de produtos.</p>
		
								<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua prefer�ncia</p>
												<p>Avalie a sua compra e ajude a Mesacor a melhorar o atendimento:
												<a href="https://www.ebitempresa.com.br/bitrate/pesquisa1.asp?empresa=1476185"><img src="http://www.mesacor.com.br/_imagens/banner_ebit.jpg"></a>
												</p>';
												
												
								//unset($_SESSION['itensCarrinho']);
								//unset($_COOKIE['pedidoID']);
								//setcookie("pedidoID",$_COOKIE['pedidoID'],time()-(60*60*24*30),'/');
						
							
							$_POST['status_transacao'] = $PedidoCielo->getStatus();
							$_POST['data_transacao'] = date("d/m/Y H:I:s");
							$_POST['retorno_transacao'] = serialize($_REQUEST);
							$db->editar('pedidos',$pedido->id);
							
if(preg_match("/sulsssiva/i",$cadastro->nome)){
							ob_start();
							//mailClass("noxsulivan@gmail.com","Registro do pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),pre($PedidoCielo,true).pre($pedido,true));
							@mailClass($pedido->cadastros->email,"Pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");
							ob_end_clean();
}else{
							ob_start();
							//mailClass("noxsulivan@gmail.com","Registro do pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),pre($PedidoCielo,true).pre($pedido,true));
							@mailClass($pedido->cadastros->email,"Pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");
							@mailClass($pagina->configs['email_suporte'],"Pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pedido->cadastros->email,$pedido->cadastros->nome_completo);
							ob_end_clean();
}
												?>
                            
                        
                    <div id="sidebar14">
                    <? //$pagina->menu(array('pai'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id),'nivel'=>2,'proprio'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id)))?>
                    </div>
                    <div id="content34">
                    
                    <h2>Status do Pedido</h2>
                    <?=$corpo?>
					
					</div>
                    
                        <?
	break;
	case "Cielo":
	//4551870000000183
		//pre($_REQUEST);
		
		//pre($pedido);
						$XMLtransacao = getXMLCielo( "4087631", "CIELO", "Consulta", "PRODUCAO",
											  number_format($pedido->valor,2,'',''),
											  $pedido->id, '',
											  "visa", $forma_pagamento = 1, $parcelas = 1, $autorizar = 2, $capturar = 'false', $tid = $pedido->transacaoid);
						
						// Carrega o XML
						
						$_statusCielo['0'] = 'Criada';
						$_statusCielo['1'] = 'Em andamento';
						$_statusCielo['2'] = 'Autenticada';
						$_statusCielo['3'] = 'N�o autenticada';
						$_statusCielo['4'] = 'Autorizada';
						$_statusCielo['5'] = 'N�o autorizada';
						$_statusCielo['6'] = 'Capturada';
						$_statusCielo['8'] = 'N�o capturada';
						$_statusCielo['9'] = 'Cancelada';
						$_statusCielo['10'] = 'Em autentica��o';

						$objDom = new DomDocument();
						$loadDom = $objDom->loadXML($XMLtransacao);
						
						$nodeErro = $objDom->getElementsByTagName('erro')->item(0);
						if ($nodeErro != '') {
							$nodeCodigoErro = $nodeErro->getElementsByTagName('codigo')->item(0);
							$retorno_codigo_erro = $nodeCodigoErro->nodeValue;
						
							$nodeMensagemErro = $nodeErro->getElementsByTagName('mensagem')->item(0);
							$retorno_mensagem_erro = $nodeMensagemErro->nodeValue;
						}
						
						$nodeTransacao = $objDom->getElementsByTagName('transacao')->item(0);
						if ($nodeTransacao != '') {
							$nodeTID = $nodeTransacao->getElementsByTagName('tid')->item(0);
							$retorno_tid = $nodeTID->nodeValue;
						
							$nodePAN = $nodeTransacao->getElementsByTagName('pan')->item(0);
							$retorno_pan = $nodePAN->nodeValue;
						
							$nodeDadosPedido = $nodeTransacao->getElementsByTagName('dados-pedido')->item(0);
							if ($nodeTransacao != '') {
								$nodeNumero = $nodeDadosPedido->getElementsByTagName('numero')->item(0);
								$retorno_pedido = $nodeNumero->nodeValue;
						
								$nodeValor = $nodeDadosPedido->getElementsByTagName('valor')->item(0);
								$retorno_valor = $nodeValor->nodeValue;
						
								$nodeMoeda = $nodeDadosPedido->getElementsByTagName('moeda')->item(0);
								$retorno_moeda = $nodeMoeda->nodeValue;
						
								$nodeDataHora = $nodeDadosPedido->getElementsByTagName('data-hora')->item(0);
								$retorno_data_hora = $nodeDataHora->nodeValue;
						
								$nodeDescricao = $nodeDadosPedido->getElementsByTagName('descricao')->item(0);
								$retorno_descricao = $nodeDescricao->nodeValue;
						
								$nodeIdioma = $nodeDadosPedido->getElementsByTagName('idioma')->item(0);
								$retorno_idioma = $nodeIdioma->nodeValue;
							}
						
							$nodeFormaPagamento = $nodeTransacao->getElementsByTagName('forma-pagamento')->item(0);
							if ($nodeFormaPagamento != '') {
								$nodeBandeira = $nodeFormaPagamento->getElementsByTagName('bandeira')->item(0);
								$retorno_bandeira = $nodeBandeira->nodeValue;
						
								$nodeProduto = $nodeFormaPagamento->getElementsByTagName('produto')->item(0);
								$retorno_produto = $nodeProduto->nodeValue;
						
								$nodeParcelas = $nodeFormaPagamento->getElementsByTagName('parcelas')->item(0);
								$retorno_parcelas = $nodeParcelas->nodeValue;
							}
						
							$nodeStatus = $nodeTransacao->getElementsByTagName('status')->item(0);
							$retorno_status = $nodeStatus->nodeValue;
						
							$nodeAutenticacao = $nodeTransacao->getElementsByTagName('autenticacao')->item(0);
							if ($nodeAutenticacao != '') {
								$nodeCodigoAutenticacao = $nodeAutenticacao->getElementsByTagName('codigo')->item(0);
								$retorno_codigo_autenticacao = $nodeCodigoAutenticacao->nodeValue;
						
								$nodeMensagemAutenticacao = $nodeAutenticacao->getElementsByTagName('mensagem')->item(0);
								$retorno_mensagem_autenticacao = $nodeMensagemAutenticacao->nodeValue;
						
								$nodeDataHoraAutenticacao = $nodeAutenticacao->getElementsByTagName('data-hora')->item(0);
								$retorno_data_hora_autenticacao = $nodeDataHoraAutenticacao->nodeValue;
						
								$nodeValorAutenticacao = $nodeAutenticacao->getElementsByTagName('valor')->item(0);
								$retorno_valor_autenticacao = $nodeValorAutenticacao->nodeValue;
						
								$nodeECIAutenticacao = $nodeAutenticacao->getElementsByTagName('eci')->item(0);
								$retorno_eci_autenticacao = $nodeECIAutenticacao->nodeValue;
							}
						
							$nodeAutorizacao = $nodeTransacao->getElementsByTagName('autorizacao')->item(0);
							if ($nodeAutorizacao != '') {
								$nodeCodigoAutorizacao = $nodeAutorizacao->getElementsByTagName('codigo')->item(0);
								$retorno_codigo_autorizacao = $nodeCodigoAutorizacao->nodeValue;
						
								$nodeMensagemAutorizacao = $nodeAutorizacao->getElementsByTagName('mensagem')->item(0);
								$retorno_mensagem_autorizacao = $nodeMensagemAutorizacao->nodeValue;
						
								$nodeDataHoraAutorizacao = $nodeAutorizacao->getElementsByTagName('data-hora')->item(0);
								$retorno_data_hora_autorizacao = $nodeDataHoraAutorizacao->nodeValue;
						
								$nodeValorAutorizacao = $nodeAutorizacao->getElementsByTagName('valor')->item(0);
								$retorno_valor_autorizacao = $nodeValorAutorizacao->nodeValue;
						
								$nodeLRAutorizacao = $nodeAutorizacao->getElementsByTagName('lr')->item(0);
								$retorno_lr_autorizacao = $nodeLRAutorizacao->nodeValue;
						
								$nodeARPAutorizacao = $nodeAutorizacao->getElementsByTagName('arp')->item(0);
								$retorno_arp_autorizacao = $nodeARPAutorizacao->nodeValue;
							}
						
							$nodeCancelamento = $nodeTransacao->getElementsByTagName('cancelamento')->item(0);
							if ($nodeCancelamento != '') {
								$nodeCodigoCancelamento = $nodeCancelamento->getElementsByTagName('codigo')->item(0);
								$retorno_codigo_cancelamento = $nodeCodigoCancelamento->nodeValue;
						
								$nodeMensagemCancelamento = $nodeCancelamento->getElementsByTagName('mensagem')->item(0);
								$retorno_mensagem_cancelamento = $nodeMensagemCancelamento->nodeValue;
						
								$nodeDataHoraCancelamento = $nodeCancelamento->getElementsByTagName('data-hora')->item(0);
								$retorno_data_hora_cancelamento = $nodeDataHoraCancelamento->nodeValue;
						
								$nodeValorCancelamento = $nodeCancelamento->getElementsByTagName('valor')->item(0);
								$retorno_valor_cancelamento = $nodeValorCancelamento->nodeValue;
							}
						
							$nodeCaptura = $nodeTransacao->getElementsByTagName('captura')->item(0);
							if ($nodeCaptura != '') {
								$nodeCodigoCaptura = $nodeCaptura->getElementsByTagName('codigo')->item(0);
								$retorno_codigo_captura = $nodeCodigoCaptura->nodeValue;
						
								$nodeMensagemCaptura = $nodeCaptura->getElementsByTagName('mensagem')->item(0);
								$retorno_mensagem_captura = $nodeMensagemCaptura->nodeValue;
						
								$nodeDataHoraCaptura = $nodeCaptura->getElementsByTagName('data-hora')->item(0);
								$retorno_data_hora_captura = $nodeDataHoraCaptura->nodeValue;
						
								$nodeValorCaptura = $nodeCaptura->getElementsByTagName('valor')->item(0);
								$retorno_valor_captura = $nodeValorCaptura->nodeValue;
							}
						
							$nodeURLAutenticacao = $nodeTransacao->getElementsByTagName('url-autenticacao')->item(0);
							$retorno_url_autenticacao = $nodeURLAutenticacao->nodeValue;
						}
						
						// Se n�o ocorreu erro exibe par�metros
						if ($retorno_status == 0 || $retorno_status == 1 || $retorno_status == 2 || $retorno_status == 4 || $retorno_status == 5 || $retorno_status == 10 ) {
							$_POST['idestagios'] = 3;
						} else {
							$_POST['idestagios'] = 6;
						}

							
								$corpo = '
								<h2>Ol�, '.$pedido->cadastros->nome.'</h2>
								<p>&nbsp;</p>
								<p>Este � o resultado do processamento do pedido de No. '.str_pad($pedido->id,6,0,STR_PAD_LEFT).'. Leia essa mensagem com muita ATEN��O.</p>
								
										<h3>Dados do pedido</h3>
										
										N�mero do pedido: <strong>' . $pedido->id . '</strong><br>
										Total: <strong>R$' . number_format($pedido->valor,2,",",".") . '</strong><br>
										Transa��o: '.$pedido->transacaoid.'<br>	 
										Data da transa��o: <strong>' . $pedido->data . '</strong><br>
										Forma de pagamento escolhida: <strong>'.normaliza($pedido->tipo_pagamento).'</strong><br>
										Status da transa��o: <strong>' . $_statusCielo[$retorno_status] . ' ('.$retorno_status.')' . '</strong><br>
										Parcelas: <strong>'.($pedido->parcelas == 1 ? '1 vez': $pedido->parcelas." vezes").'</strong><br>
										Forma de envio: <strong>'.( $pedido->tipo_frete == "EN" ? "Encomenda normal" : ( $pedido->tipo_frete == "eSEDEX" ? "eSEDEX" : "SEDEX")).'</strong> - Prazo de entrega: <strong>'.$pedido->prazo.' dias �teis</strong><br>
										Valor do frete: <strong>R$ '.number_format($pedido->valor_frete,2,",",".").'</strong>
															
										<h3>�tens solicitados</h3>';
										$corpo .= $pedido->descricao;
										$corpo .= $pedido->dados_entrega;
								$corpo .= '
								
								<p>IMPORTANTE: O Prazo de entrega informado acima � v�lido para compras efetuadas at� 20:00 horas com cart�o de cr�dito aprovado na 1� tentativa.<br>
								Caso a data prevista para entrega corresponder a algum feriado na regi�o de entrega, pedimos gentilmente que acrescente 1 dia �til ao prazo mencionado acima.<br>
								ATEN��O: Tenha sempre com voc� a nota fiscal e a embalagem original dos produtos. Somente com estes itens ser�o poss�veis opera��es como troca ou devolu��o de produtos.</p>
		
								<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua prefer�ncia</p>
								
										
								<p>Avalie a sua compra e ajude a Mesacor a melhorar o atendimento:
								<a href="https://www.ebitempresa.com.br/bitrate/pesquisa1.asp?empresa=1476185"><img src="http://www.mesacor.com.br/_imagens/banner_ebit.jpg"></a>
								</p>';
												
												
								unset($_SESSION['itensCarrinho']);
								unset($_COOKIE['pedidoID']);
								setcookie("pedidoID",$_COOKIE['pedidoID'],time()-(60*60*24*30),'/');
						
							
							$_POST['anotacao'] = $response;
							$_POST['status_transacao'] = $_statusCielo[$retorno_status];
							$_POST['data_transacao'] = date("d/m/Y H:I:s");
							$_POST['retorno_transacao'] = serialize($_REQUEST);
							$db->editar('pedidos',$pedido->id);
							
							mailClass($pedido->cadastros->email,"Pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");
							mailClass($pagina->configs['email_suporte'],"Pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pedido->cadastros->email,$pedido->cadastros->nome_completo);
												?>
                            
                        
                    <div id="sidebar14">
                    <? //$pagina->menu(array('pai'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id),'nivel'=>2,'proprio'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id)))?>
                    </div>
                    <div id="content34">
                    
                    <?=$corpo?>
            
                    </div>
                        <?
	break;
	case "Redecard":
	
	
					$_msgRD[0] = "Confirma��o com sucesso. Transa��o Aprovada";
					$_msgRD[1] = "Ja confirmada";
					$_msgRD[2] = "Transa��o negada";
					$_msgRD[3] = "Transa��o desfeita";
					$_msgRD[4] = "Transa��o estornada";
					$_msgRD[5] = "Transa��o estornada";
					$_msgRD[8] = "Dados n�o coincidem";
					$_msgRD[9] = "Transa��o n�o encontrada";
					$_msgRD[88] = "Dados ausentes. Transa��o n�o pode ser conclu�da";
					
					$pedido = new objetoDb('pedidos',$NUMPEDIDO);
					//$pedido = new objetoDb('pedidos',$_COOKIE['pedidoID']);
					
					
					$total = $pedido->valor;
					$numparcelas = $pedido->parcelas;
					$numafiliacao = "031179983";
					$RedeCardJurosParcelado = 0;
					
					$cravs = $RESPAVS;
					$mensavs = $MSGAVS;
					
					// status transacao
					if ($CODRET != "" && $CODRET != "0"){
					   $status = $CODRET;
					   $autent = $MSGRET;
					} else {
					   $status = 0;
					   $autent = $NUMAUTENT;
					}
	//Retorno/Redecard/?NUMAUTOR=014168&NUMSQN=997578964&NUMCV=997578964&NUMAUTENT=756668&NUMPEDIDO=464&DATA=20100609&PAX1=&NR_CARTAO=364901******8795&ORIGEM_BIN=BRA&NUMPRG=0&NR_HASH_CARTAO=04C7EDFB1F8068AE534FA52EAC9FFFE4&COD_BANCO=

					// ************************ Confirma transa��o com a Redecard ***************
					if ($status == 0) {
						$valores = "DATA=" . $DATA;
						$valores = $valores . "&TRANSACAO=203";
						
							$parcelas = str_pad($numparcelas,2,0,STR_PAD_LEFT);
					
							if ($parcelas == "01" || $parcelas == "00" || $numparcelas == ""){
							   $parcelas = "00";
							   $trans_orig = "04";
							} else {
								if ($RedeCardJurosParcelado == 1){
								   $trans_orig = "06";
								} else {
								   $trans_orig = "08";
								}
							}
						$valores = $valores . "&TRANSORIG=" . $trans_orig;
						$valores = $valores . "&PARCELAS=" . $parcelas;
						$valores = $valores . "&FILIACAO=" . $numafiliacao;
						$valores = $valores . "&DISTRIBUIDOR="; // este campo deve ser nulo
						$valores = $valores . "&TOTAL=" . number_format($pedido->valor + $pedido->valor_frete,2,'.','');
						$valores = $valores . "&NUMPEDIDO=" . $NUMPEDIDO;
						$valores = $valores . "&NUMAUTOR=" . $NUMAUTOR;
						$valores = $valores . "&NUMCV=" . $NUMCV;
						$valores = $valores . "&NUMSQN=" . $NUMSQN;
					
						// contacta RedeCard e confirma transa��o
						$filename="http://ecommerce.redecard.com.br/pos_virtual/confirma.asp?" . $valores;
					
						$file = file($filename); 
						$retorna = $file[0]; 
						parse_str($retorna);
 
					
						$status = $CODRET;
						if ($status > 1) {
						   $autent = $MSGRET;
						}
					}	
					
					// ************************** Monta o cupom *********************************
					$URLCupom = "https://ecommerce.redecard.com.br/pos_virtual/cupom.asp?DATA=" . $DATA . "&TRANSACAO=201&NUMAUTOR=" . $NUMAUTOR . "&NUMCV=" . $NUMCV;
					
					switch($status){
						case 0:
						
								unset($_SESSION['itensCarrinho']);
								unset($_COOKIE['pedidoID']);
								setcookie("pedidoID",$_COOKIE['pedidoID'],time()-(60*60*24*30),'/');
						
							$response .= "Transa��o aprovada." . "<br>";
							$response .= $NUMPEDIDO . "<br>";
							$response .= $DATA . "<br>";
							$response .= $NUMCV;
							
							$_POST['anotacao'] = $response;
							$_POST['transacaoid'] = $NUMCV;
							$_POST['idestagios'] = 3;
							$_POST['status_transacao'] = $_msgRD[$status];
							$_POST['data_transacao'] = date("d/m/Y H:I:s");
							$_POST['retorno_transacao'] = serialize($_REQUEST);
							$db->editar('pedidos',$NUMPEDIDO);

							$response .= "<SCRIPT LANGUAGE=javascript>
											<!--
													vpos=window.open('".$URLCupom."','vpos','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=auto,resizable=no,copyhistory=no,width=260,height=440');
											//-->
											</SCRIPT>";

											$pedido = new objetoDb('pedidos',$NUMPEDIDO);
											$dados = (object) unserialize($pedido->serialized);
									
												$corpo = '
												<h2>Ol�, '.$pedido->cadastros->nome.'</h2>
												<p>&nbsp;</p>
												<p>Este � o resultado do processamento do pedido de No. '.str_pad($pedido->id,6,0,STR_PAD_LEFT).'. Leia essa mensagem com muita ATEN��O.</p>
												
														<h3>Dados do pedido</h3>
														
															N�mero do pedido: <strong>' . $pedido->id . '</strong><br>
															Total: <strong>R$' . number_format($pedido->valor,2,",",".") . '</strong><br>
															Transa��o: '.$pedido->transacaoid.'<br>	 
															Data da transa��o: '.$DATA.'<br>
															Forma de pagamento escolhida: '.$pedido->tipo_pagamento.'<br>
															Status da transa��o: '.$_msgRD[$status].'<br>
															Parcelas: '.$pedido->parcelas.'<br>
															Forma de envio: '.( $pedido->tipo_frete == "EN" ? "Encomenda normal" : ( $pedido->tipo_frete == "eSEDEX" ? "eSEDEX" : "SEDEX")).' - Prazo de entrega: '.$pedido->prazo.'<br>
															Valor do frete: R$ '.number_format($pedido->valor_frete,2,",",".").'
															
														<h3>�tens solicitados</h3>';
														$corpo .= $pedido->descricao;
														$corpo .= $pedido->dados_entrega;
												$corpo .= '
								
												<p>IMPORTANTE: O Prazo de entrega informado acima � v�lido para compras efetuadas at� 20:00 horas com cart�o de cr�dito aprovado na 1� tentativa.<br>
												Caso a data prevista para entrega corresponder a algum feriado na regi�o de entrega, pedimos gentilmente que acrescente 1 dia �til ao prazo mencionado acima.<br>
												ATEN��O: Tenha sempre com voc� a nota fiscal e a embalagem original dos produtos. Somente com estes itens ser�o poss�veis opera��es como troca ou devolu��o de produtos.</p>
						
												<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua prefer�ncia</p>
												
														
												<p>Avalie a sua compra e ajude a Mesacor a melhorar o atendimento:
												<a href="https://www.ebitempresa.com.br/bitrate/pesquisa1.asp?empresa=1476185"><img src="http://www.mesacor.com.br/_imagens/banner_ebit.jpg"></a>
												</p>';
												
												
												mailClass($pedido->cadastros->email,"Pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");
												mailClass($pagina->configs['email_suporte'],"Pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pedido->cadastros->email,$pedido->cadastros->nome_completo);

						break;
						case 1: 
							$response .= "Transa��o j� confirmada: " . "<br>";
							$response .= "N�mero do pedido: " . $NUMPEDIDO . "<br>";
							$response .= "Data da transa��o: " . $DATA . "<br>";
							$response .= "N�mero do comprovante de venda: " . $NUMCV;
							
							$_POST['anotacao'] = $response;
							$_POST['transacaoid'] = $NUMCV;
							$_POST['idestagios'] = 3;
							$_POST['status_transacao'] = $_msgRD[$status];
							$_POST['data_transacao'] = date("d/m/Y H:I:s");
							$_POST['retorno_transacao'] = serialize($_REQUEST);
							$db->editar('pedidos',$NUMPEDIDO);
							
								$pedido = new objetoDb('pedidos',$NUMPEDIDO);
							
								$corpo = ' <p>O pedido realizado no site '.$pagina->localhost.' foi atualizado no sistema, seguem mais detalhes:</p>';
								
								$corpo .= $pedido->dados_entrega;
										
								$corpo .= '<h3>Dados do pedido</h3>
								
									Transa��o: '.$pedido->transacaoid.'<br>	 	
									Anota��o: '.$pedido->anotacao.'<br>
									Data da transa��o: '.$pedido->data.'<br>
									Forma de pagamento escolhida: '.$pedido->tipo_pagamento.'<br>
									Status da transa��o: '.$pedido->status_transacao.'<br>
									Parcelas: '.$pedido->parcelas;
																
												mailClass($pedido->cadastros->email,"Pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");
												mailClass($pagina->configs['email_suporte'],"Pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pedido->cadastros->email,$pedido->cadastros->nome_completo);
							
						break;
						default:
							$response .= "C�digo de erro: ".$status . "<br>";
							$response .= "Mensagem: ".htmlspecialchars(urldecode($autent)). "<br>";
							$response .= "N�mero do pedido: " . $NUMPEDIDO . "<br>";
							$response .= "Data da transa��o: " . $DATA . "<br>";
							//$response .= "N�mero do comprovante de venda: " . $NUMCV;
							
							$_POST['anotacao'] = $response;
							$_POST['transacaoid'] = $NUMCV;
							$_POST['idestagios'] = 6;
							$_POST['status_transacao'] = $_msgRD[$status];
							$_POST['data_transacao'] = date("d/m/Y H:I:s");
							$_POST['retorno_transacao'] = serialize($_REQUEST);
							$db->editar('pedidos',$NUMPEDIDO);
							
								$pedido = new objetoDb('pedidos',$NUMPEDIDO);
							
								$corpo = '<p>O pedido realizado no site '.$pagina->localhost.' foi atualizado no sistema, seguem mais detalhes:</p>';
								
								$corpo .= $pedido->dados_entrega;
										
								$corpo .= '<h3>Dados do pedido</h3>
								
									Transa��o: '.$pedido->transacaoid.'<br>	 	
									Anota��o: '.$pedido->anotacao.'<br>
									Data da transa��o: '.$pedido->data.'<br>
									Forma de pagamento escolhida: '.$pedido->tipo_pagamento.'<br>
									Status da transa��o: '.$pedido->status_transacao.'<br>
									Parcelas: '.$pedido->parcelas.'';
																
												mailClass($pedido->cadastros->email,"Pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");
												mailClass($pagina->configs['email_suporte'],"Pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pedido->cadastros->email,$pedido->cadastros->nome_completo);
						break;
					}
					
					
					?>
					<div id="sidebar14">
					<? $pagina->menu(array('pai'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id),'nivel'=>2,'proprio'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id)))?>
					</div>
					<div id="content34">
					<h2>Recibo</h2>
					
                    <?=$response?>
                     
					</div>
                    <?
	break;
	case "Shopline":
	default:
			//pre($_REQUEST);
			if($_REQUEST['DC'] and $_REQUEST['pedido']){
			
				//$_REQUEST['DC'] = "Y128H74N61M98D244J199K32N221A251Z98A250P129O86M86I187K36C209F70K248Q20G55Q158Y83B194C52S120A69G105T253P202F187C181V182N74B3B232S";
				$Itau = new Itaucripto;
				$Itau->decripto($_REQUEST['DC'], $pagina->configs['codigo_empresa_shopline']);
				//pre($Itau);
				
					
					$_POST['anotacao'] = $_REQUEST['DC'];
					$_POST['idestagios'] = 2;
					$_POST['status_transacao'] = 'Aguardando confirma��o do pagamento';
					$_POST['data_transacao'] = date("d/m/Y H:I:s");
					$_POST['retorno_transacao'] = serialize($_REQUEST);
					$db->editar('pedidos',(int) $_REQUEST['pedido']);
					
				$id_pedido = (int)  $_REQUEST['pedido'];
				
				
				
				
				if(!isset($_REQUEST['pedido'])){
					$id_pedido = 379;
				}
				$pedido = new objetoDb('pedidos',$id_pedido);
				
				if($pedido->estagios->id == 2){	
					unset($_SESSION['itensCarrinho']);
					unset($_COOKIE['pedidoID']);
					setcookie("pedidoID",(int) $_REQUEST['pedido'],time()-(60*60*24*30),'/');
				}
				
				$dados = (object) unserialize($pedido->serialized);
									
												$corpo = '
												<h2>'.$pedido->cadastros->nome.'</h2>
												<h3>Falta apenas 1 passo para voc� finalizar a sua compra</h3>
												<p>Imprima o boleto no link e pague o quanto antes.<br><a href="'.$pagina->localhost.'_Request/1viaBoleto/'.$pedido->id.'/'.md5($pedido->id).'">Clique para imprimir o boleto</a></p>
												<p>O boleto <strong>n�o ser�</strong> enviado por correpond�ncia</p>
															N�mero do pedido: <strong>' . $pedido->id . '</strong><br>
															Total: <strong>R$' . number_format($pedido->valor,2,",",".") . '</strong><br>
															Valor do frete: <strong>R$ '.number_format($pedido->valor_frete,2,",",".").'</strong><br>
															Data da transa��o: <strong>'.$pedido->data.'</strong><br>
															Forma de pagamento escolhida: <strong>Boleto - '.$pedido->tipo_pagamento.'</strong><br>
															Status da transa��o: <strong>Aguardando confirma��o do pagamento</strong><br>
															
														<h3>�tens solicitados</h3>';
															$corpo .= $pedido->descricao;
															$corpo .= $pedido->dados_entrega;
														
												$corpo .= '
								
												<p>IMPORTANTE: O Prazo de entrega informado acima � v�lido a partir da data de confirma��o do pagamento pelo banco<br>
												ATEN��O: Tenha sempre com voc� a nota fiscal e a embalagem original dos produtos. Somente com estes itens ser�o poss�veis opera��es como troca ou devolu��o de produtos.</p>
						
												<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua prefer�ncia</p>
												<p>Avalie a sua compra e ajude a Mesacor a melhorar o atendimento:
												<a href="https://www.ebitempresa.com.br/bitrate/pesquisa1.asp?empresa=1476185"><img src="http://www.mesacor.com.br/_imagens/banner_ebit.jpg"></a>
												</p>';

							ob_start();
							//mailClass("noxsulivan@gmail.com","Registro do pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),pre($pedido,true));
							@mailClass($pedido->cadastros->email,"Pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");
							@mailClass($pagina->configs['email_suporte'],"Pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pedido->cadastros->email,$pedido->cadastros->nome_completo);
							ob_end_clean();
							
				if($pedido->estagios->id == 2){
					unset($_COOKIE['pedidoID']);
				}

					?>
					<div id="sidebar14">
					<? //$pagina->menu(array('pai'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id),'nivel'=>2,'proprio'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id)))?>
					</div>
					<div id="content34">
                    
                    <h2>Status do Pedido</h2>
						<?=$corpo?>
															
					</div>
                    <?

			}else{
					?>
					<div id="sidebar14">
					<? $pagina->menu(array('pai'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id),'nivel'=>2,'proprio'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id)))?>
					</div>
					<div id="content34">
					<h2>S� mais um passo</h2>
					<p>Para concluir a sua compra s� falta 1 passo. Pague o boleto antes do vencimento e receba o quanto antes o seu pedido em casa.</p>
					<p>As informa��es do pedido j� foram processadas. Confira o resultado na mensagem enviada para seu e-mail ou acessando o hist�rico no menu <a href="<?=$pagina->localhost?>Cliente/Pedidos">Minhas Compras</a></p>						
					</div>
                    <?
			}
	break;
}

?>
<?
	if($cadastro->conectado()){
	
		ob_start();?>
		  
			<?
				$dados = (object) unserialize($pedido->serialized);
				?>
				  _gaq.push(['_addTrans',
					"<?=$pedido->id?>",                   // Order ID
					"<?=$pagina->configs['titulo_site']?>",
					"<?=$pedido->valor?>",
					"",                   // Tax
					"<?=$pedido->valor_frete?>",                    // Shipping
					"<?=$dados->cidade?>",                 // City
					"<?=$dados->estado?>",                // State
					"BRA"                    // Country
				  ]);
				<?
				echo "//".count($pedido->itens);
				foreach($pedido->itens as $item){
					$precoItem = $item->quantidade * ( (float)$item->produtos->preco_promocional > 0 ? (float)$item->produtos->preco_promocional : (float)$item->produtos->preco_venda);
				?>
				  _gaq.push(['_addItem',
					  "<?=$pedido->id?>",                   // Order ID
					  "<?=$item->produtos->codigo?>",                   // SKU
					  "<?=$item->produtos->produto?>",                 // Product Name
					  "",               // Category
					  "<?=( (float)$item->produtos->preco_promocional > 0 ? (float)$item->produtos->preco_promocional : (float)$item->produtos->preco_venda)?>",                  // Price
					  "<?=$item->quantidade?>"                     // Quantity
				  ]);
				<? }?>
				
				
				  _gaq.push(['_trackTrans']); //submits transaction to the Analytics servers
		<?
		$analytics = ob_get_clean();
							//ob_start();
							//mailClass("noxsulivan@gmail.com","Analytics do pedido n� ".str_pad($pedido->id,6,0,STR_PAD_LEFT),pre($analytics,true));
							//ob_end_clean();
	}
}else{
							//ob_start();
							//mailClass("noxsulivan@gmail.com","Acesso � tela de retorno ".time(),pre($_REQUEST,true).pre($_SERVER,true));
							//ob_end_clean();
								?>
								
                    <h2>Status do Pedido</h2>
                    <h3>Pedido em processamento</h3>
					Obrigado por escolher a mesacor. Seu pedido est� em processamento e logo voc� ser� informado sobre a atualiza��o do status.
								<?

	}?>


                    <h3>Pesquisa de opini�o</h3>
                    <p>Preencha o formul�rio de consulta sobre sua compra em nossa loja virtual, � muito importante para n�s para que possamos melhor o atendimento e corrigir poss�veis falhas. Voc� ainda concorre a pr�mios patrocinados pela eBit, empresa que monitora o com�rcio eletr�nico no Brasil</p>
            
                    <form name="formebit" method="get" target="_top"  action="https://www.ebitempresa.com.br/bitrate/pesquisa1.asp"> 
                    <input type=hidden name='empresa' value='1476185'> 
                    <input type="image" style="height:80px" border="0" name="banner"  src="http://www.mesacor.com.br/_imagens/banner_ebit.jpg" alt="O que voc&ecirc; achou desta loja?" width="468" height="60"> 
                    </form> 