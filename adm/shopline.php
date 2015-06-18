<?
include('../ini.php');


$pagina = new layout($_SERVER['QUERY_STRING']);


	$db->query('select * from pedidos where tipo_pagamento like "%vista%" and idestagios < 3 order by idpedidos desc limit 100');
	
	pre("Boletos a serem analizados ".$db->rows);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://shopline.itau.com.br/shopline/consulta.asp");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
	$Itau = new Itaucripto;
	while($res = $db->fetch()){
		
			unset($_POST['idestagios']);
			
			$pedido = new objetoDb('pedidos',$res['idpedidos']);
			
			pre($pedido->id);
			
			$consulta = $Itau->geraConsulta('J0043497660001370000012814', $pedido->id, 1, 'MESACORTRA243101');
			$dc = http_build_query(array("DC" => $consulta));
			
			pre($consulta);
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $dc);
			$exec = curl_exec($ch);
			
			//pre($exec);die;
			
			$xml = simplexml_load_string($exec);
			$_a = array();
			
			
			foreach($xml->PARAMETER->PARAM as $param){
				$t = $param->attributes();
				$_a[(string)$t['ID']] = (string)$t['VALUE'];
			}
			
			
			pre($_a);
			
			if($_a['tipPag'] == '00'){
				if(compara_data(date("Y-m-d"),in_data($pedido->data)) > 20){
					$_POST['idestagios'] = 6;
					$_POST['status_transacao'] = "Boleto concluído, vencido prazo do pedido";
					pre("Pedido ".$pedido->id." - expirado $dif");
					$db->editar('pedidos',$pedido->id);
				}
				pre("Pedido ".$pedido->id." inexistente ".in_data($pedido->data)." ".compara_data(in_data($pedido->data),date("Y-m-d")));
			}
				
			if($_a['tipPag'] == '01' and $_a['sitPag'] == '00'){
				pre("Pedido ".$pedido->id." - Pagamento à Vista (TEF e CDC) - pagamento efetuado");
				$_POST['status_transacao'] = "Tranferência efetuada";
				$_POST['idestagios'] = "3";
				$db->editar('pedidos',$pedido->id);
			}
			if($_a['tipPag'] == '01' and $_a['sitPag'] == '01'){
				pre("Pedido ".$pedido->id." - Pagamento à Vista (TEF e CDC) - situação de pagamento não finalizada (tente novamente)");
			}
			if($_a['tipPag'] == '01' and $_a['sitPag'] == '02'){
				pre("Pedido ".$pedido->id." - Pagamento à Vista (TEF e CDC) - erro no processamento da consulta (tente novamente)");
			}
			if($_a['tipPag'] == '01' and $_a['sitPag'] == '03'){
				pre("Pedido ".$pedido->id." - Pagamento à Vista (TEF e CDC) - pagamento não localizado (consulta fora de prazo ou pedido não registrado no banco)");
			}
			
			
			if($_REQUEST['teste'] == 'boleto' and $pedido->id == 49937){

					
				
					
										$data = explode(" ",$pedido->data);
										$data = explode("/",$data[0]);
										$data = $data[2].$data[1].$data[0];
										
										$date = strtotime(date("Y-m-d", strtotime($data)) . " +5 days");
										pre($date = date('dmY', $date));
										
										$Itau = new Itaucripto;
										$dados = $Itau->geraDados
										(
												$codEmp = 'J0043497660001370000012814',
												$pedido->id,
												$valor = number_format($pedido->valor + $pedido->valor_frete,2,',',''),
												$observacao = '',
												$chave = 'MESACORTRA243101',
												$nomeSacado = $pedido->cadastros->nome.' '.$pedido->cadastros->sobrenome,
												$codigoInscricao = 01,
												$numeroInscricao = str_replace('.','',str_replace('-','',$pedido->cadastros->cpf)),
												$enderecoSacado = $pedido->cadastros->endereco,
												$bairroSacado =  $pedido->cadastros->bairros->bairro,
												$cepSacado = $pedido->cadastros->cep,
												$cidadeSacado = $pedido->cadastros->cidades->cidade,
												$estadoSacado = $pedido->cadastros->estados->nome,
												$dataVencimento = $date,
												$urlRetorna = 'https://www.mesacor.com.br',
												$obsAd1 = '',$obsAd2 = '',$obsAd3 = ''
										);
						
						
										$corpo = '
										<h2>Boleto pendente</h2>
										
										<p>Olá, '.$pedido->cadastros->nome.', até o presente momento não recebemos do banco a confirmação do pagamento do seu pedido.</p>
										
										<p>Por isso, estamos reenviando o boleto para pagamento no link:</p>
										
										<form action="https://shopline.itau.com.br/shopline/reemissao.asp" method="post" target="_blank">
										<input type="hidden" name="DC" value="'.$dados.'" />
										<button type="submit" style="border:none"><strong>Clique aqui para 2ª via do boleto</strong></button>
										</form>
										
										<p>Lembramos que o pedido e reserva do(s) produto(s) será(ão) automaticamente cancelado(s) caso o pagamento não seja realizado.</p>

										<p>Por favor, caso identifique alguma divergência nessa informação, contate imediatamente nossa equipe através do e-mail mesacor@mesacor.com.br
										ou pelo telefone (47) 3367-4349. </p>
										
										<p>Importante: O prazo informado para entrega do seu pedido passa a valer a partir da aprovação pela
										administradora do cartão de crédito/instituição financeira. Para sua segurança, as informações contidas em seu
										cadastro são passíveis de confirmação, que poderá ser feita através de contato telefônico ou e-mail.
										Na necessidade de confirmação dos dados informados, o prazo para entrega do seu pedido pode sofrer alguma alteração.</p>
										
										<p>Informamos que para contagem do prazo de entrega consideramos como dias úteis de segunda a sexta-feira, das 8h às 21h, exceto feriados. </p>

										<p>Obs: Caso o pagamento já foi efetuado, por favor desconsiderar este e-mail.</p>
										
										<h3>Dados do pedido</h3>
										
										Número do pedido: <strong>' . $pedido->id . '</strong><br>
										Total: <strong>R$' . number_format($pedido->valor,2,",",".") . '</strong><br>
										Valor do frete: R$ '.number_format($pedido->valor_frete,2,",",".").'<br>
										Data da transação: '.$pedido->data.'<br>
										Forma de envio: '.$pedido->tipo_frete.' - Prazo de entrega: '.$pedido->prazo.'
										
										<h3>Ítens solicitados</h3>';
										$corpo .= $pedido->descricao;
										$corpo .= $pedido->dados_entrega;
														
										$corpo .= '
										<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua preferência</p>';
														
									
									mailClass($pedido->cadastros->email,"Pedido pendente",$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");
									
									
			}
			
			if($_a['tipPag'] == '02' and $_a['sitPag'] == '00'){
				pre("Pedido ".$pedido->id." - Boleto Bancário - pagamento efetuado");
				$_POST['status_transacao'] = "Boleto pago";
				$_POST['idestagios'] = "3";
				$db->editar('pedidos',$pedido->id);
				
										$corpo = '
										<h2>Pedido confirmado</h2>
										<p>&nbsp;</p>
										<p>Prezado(a), '.$pedido->cadastros->nome.' '.$pedido->cadastros->sobrenome.', recebemos a confirmação de seu pagamento referente ao pedido nº. '.str_pad($pedido->id,6,0,STR_PAD_LEFT).'.</p>
										

										<p>O Prazo de entrega informado acima é considerado a partir de agora, confirmado o pagamento.<br>
										Caso a data prevista para entrega corresponder a algum feriado na região de entrega, pedimos gentilmente que acrescente 1 dia útil ao prazo mencionado acima.<br>
										ATENÇÃO: Tenha sempre com você a nota fiscal e a embalagem original dos produtos. Somente com estes itens serão possíveis operações como troca ou devolução de produtos.</p>
				
										<p>Avalie a sua compra e ajude a Mesacor a melhorar o atendimento:
										<a href="https://www.ebitempresa.com.br/bitrate/pesquisa1.asp?empresa=1112665"><img src="http://www.mesacor.com.br/_imagens/banner_ebit.jpg"></a>
										</p>
										
										<h3>Dados do pedido</h3>
										
										Número do pedido: <strong>' . $pedido->id . '</strong><br>
										Total: <strong>R$' . number_format($pedido->valor,2,",",".") . '</strong><br>
										Valor do frete: R$ '.number_format($pedido->valor_frete,2,",",".").'<br>
										Data da transação: '.$pedido->data.'<br>
										Forma de envio: '.$pedido->tipo_frete.' - Prazo de entrega: '.$pedido->prazo.'
										
										<h3>Ítens solicitados</h3>';
										$corpo .= $pedido->descricao;
										$corpo .= $pedido->dados_entrega;
														
										$corpo .= '
										<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua preferência</p>';
														
									
									mailClass($pedido->cadastros->email,"Pedido confirmado nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");
									mailClass($pagina->configs['email_suporte'],"Pedido confirmado nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pedido->cadastros->email,$pedido->cadastros->nome_completo);

			}
			if($_a['tipPag'] == '02' and $_a['sitPag'] == '01'){
				pre("Pedido ".$pedido->id." - Boleto Bancário - situação de pagamento não finalizada (tente novamente)");
				$_POST['status_transacao'] = "situação de pagamento não finalizada";
				$db->editar('pedidos',$pedido->id);
			}
			if($_a['tipPag'] == '02' and $_a['sitPag'] == '02'){
				pre("Pedido ".$pedido->id." - Boleto Bancário - erro no processamento da consulta (tente novamente)");
			}
			if($_a['tipPag'] == '02' and $_a['sitPag'] == '03'){
				pre("Pedido ".$pedido->id." - Boleto Bancário - pagamento não localizado (consulta fora de prazo ou pedido não registrado no banco)");
			}
			if($_a['tipPag'] == '02' and $_a['sitPag'] == '04'){
				pre("Pedido ".$pedido->id." - Boleto Bancário - emitido com sucesso");
				$_POST['status_transacao'] = "Boleto emitido, aguardando pagamento";
				$_POST['idestagios'] = "2";
				if(!preg_match('/emitido/i',$pedido->status_transacao)){
					$db->editar('pedidos',$pedido->id);
				}
				if(compara_data(in_data($pedido->data),date("Y-m-d")) > 5){
					$_POST['idestagios'] = 6;
					$_POST['status_transacao'] = "Boleto vencido";
					pre("Pedido ".$pedido->id." - vencido");
					$db->editar('pedidos',$pedido->id);
					
										$data = explode(" ",$pedido->data);
										$data = explode("/",$data[0]);
										$data = $data[2].$data[1].$data[0];
										
										$date = strtotime(date("Y-m-d", strtotime($data)) . " +5 days");
										pre($date = date('dmY', $date));
										
										$Itau = new Itaucripto;
										$dados = $Itau->geraDados
										(
												$codEmp = 'J0043497660001370000012814',
												$pedido->id,
												$valor = number_format($pedido->valor + $pedido->valor_frete,2,',',''),
												$observacao = '',
												$chave = 'MESACORTRA243101',
												$nomeSacado = $pedido->cadastros->nome.' '.$pedido->cadastros->sobrenome,
												$codigoInscricao = 01,
												$numeroInscricao = str_replace('.','',str_replace('-','',$pedido->cadastros->cpf)),
												$enderecoSacado = $pedido->cadastros->endereco,
												$bairroSacado =  $pedido->cadastros->bairros->bairro,
												$cepSacado = $pedido->cadastros->cep,
												$cidadeSacado = $pedido->cadastros->cidades->cidade,
												$estadoSacado = $pedido->cadastros->estados->nome,
												$dataVencimento = $date,
												$urlRetorna = 'https://www.mesacor.com.br',
												$obsAd1 = '',$obsAd2 = '',$obsAd3 = ''
										);
						
						

				}elseif($pedido->notificado_email == "nao" and compara_data(in_data($pedido->data),date("Y-m-d")) == 0){
					$_POST['notificado_email'] = "sim";
					$_POST['anotacao'] = $_REQUEST['DC'];
					$_POST['status_transacao'] = 'Aguardando confirmação do pagamento';
					$_POST['data_transacao'] = date("d/m/Y H:I:s");
					$_POST['retorno_transacao'] = serialize($_REQUEST);
					$db->editar('pedidos',$pedido->id);
													$corpo = '
													<h2>'.$pedido->cadastros->nome.'</h2>
													<h3>Falta apenas 1 passo para você finalizar a sua compra</h3>
													<p>Imprima o boleto no link e pague o quanto antes.<br><a href="'.$pagina->localhost.'_Request/1viaBoleto/'.$pedido->id.'/'.md5($pedido->id).'">Clique para imprimir o boleto</a></p>
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
													//mailClass("noxsulivan@gmail.com","Registro do pedido nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),pre($pedido,true));
													@mailClass($pedido->cadastros->email,"Pedido nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");
													@mailClass($pagina->configs['email_suporte'],"Pedido nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pedido->cadastros->email,$pedido->cadastros->nome_completo);
													ob_end_clean();
													
													echo "<h1>EMAIL ENVIADO</h1>";
				}
			}
			if($_a['tipPag'] == '02' and $_a['sitPag'] == '05'){
				pre("Pedido ".$pedido->id." - Boleto Bancário - pagamento efetuado, aguardando compensação");
				$_POST['status_transacao'] = "Boleto pago, aguardando compensação";
				$_POST['idestagios'] = "3";
				$db->editar('pedidos',$pedido->id);
				
								
										$corpo = '
										<h2>Pedido confirmado</h2>
										
										<p>Prezado(a), '.$pedido->cadastros->nome.' '.$pedido->cadastros->sobrenome.', recebemos a confirmação de seu pagamento referente ao pedido nº. '.str_pad($pedido->id,6,0,STR_PAD_LEFT).'.</p>

										<p>O Prazo de entrega informado acima é considerado a partir de agora, confirmado o pagamento.<br>
										Caso a data prevista para entrega corresponder a algum feriado na região de entrega, pedimos gentilmente que acrescente 1 dia útil ao prazo mencionado acima.<br>
										ATENÇÃO: Tenha sempre com você a nota fiscal e a embalagem original dos produtos. Somente com estes itens serão possíveis operações como troca ou devolução de produtos.</p>
				
										<p>Avalie a sua compra e ajude a Mesacor a melhorar o atendimento:
										<a href="https://www.ebitempresa.com.br/bitrate/pesquisa1.asp?empresa=1112665"><img src="http://www.mesacor.com.br/_imagens/banner_ebit.jpg"></a>
										</p>
										
										<h3>Dados do pedido</h3>
										
										Número do pedido: <strong>' . $pedido->id . '</strong><br>
										Total: <strong>R$' . number_format($pedido->valor,2,",",".") . '</strong><br>
										Valor do frete: R$ '.number_format($pedido->valor_frete,2,",",".").'<br>
										Data da transação: '.$pedido->data.'<br>
										Forma de envio: '.$pedido->tipo_frete.' - Prazo de entrega: '.$pedido->prazo.'
										
										<h3>Ítens solicitados</h3>';
										$corpo .= $pedido->descricao;
										$corpo .= $pedido->dados_entrega;
														
										$corpo .= '
										<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua preferência</p>';
														
									
									mailClass($pedido->cadastros->email,"Pedido confirmado nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");

			}
			if($_a['tipPag'] == '02' and $_a['sitPag'] == '06'){
				pre("Pedido ".$pedido->id." - Boleto Bancário - pagamento não compensado");
				$_POST['status_transacao'] = "Boleto pagamento não compensado";
				$db->editar('pedidos',$pedido->id);
				
								
										$corpo = '
										<h2>Pedido confirmado</h2>
										
										<p>Prezado(a), '.$pedido->cadastros->nome.' '.$pedido->cadastros->sobrenome.', recebemos a confirmação de seu pagamento referente ao pedido nº. '.str_pad($pedido->id,6,0,STR_PAD_LEFT).'.</p>

										<p>O Prazo de entrega informado acima é considerado a partir de agora, confirmado o pagamento.<br>
										Caso a data prevista para entrega corresponder a algum feriado na região de entrega, pedimos gentilmente que acrescente 1 dia útil ao prazo mencionado acima.<br>
										ATENÇÃO: Tenha sempre com você a nota fiscal e a embalagem original dos produtos. Somente com estes itens serão possíveis operações como troca ou devolução de produtos.</p>
				
										<p>Avalie a sua compra e ajude a Mesacor a melhorar o atendimento:
										<a href="https://www.ebitempresa.com.br/bitrate/pesquisa1.asp?empresa=1112665"><img src="http://www.mesacor.com.br/_imagens/banner_ebit.jpg"></a>
										</p>
										
										<h3>Dados do pedido</h3>
										
										Número do pedido: <strong>' . $pedido->id . '</strong><br>
										Total: <strong>R$' . number_format($pedido->valor,2,",",".") . '</strong><br>
										Valor do frete: R$ '.number_format($pedido->valor_frete,2,",",".").'<br>
										Data da transação: '.$pedido->data.'<br>
										Forma de envio: '.$pedido->tipo_frete.' - Prazo de entrega: '.$pedido->prazo.'
										
										<h3>Ítens solicitados</h3>';
										$corpo .= $pedido->descricao;
										$corpo .= $pedido->dados_entrega;
														
										$corpo .= '
										<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua preferência</p>';
														
									
									mailClass($pedido->cadastros->email,"Pedido confirmado nº ".str_pad($pedido->id,6,0,STR_PAD_LEFT),$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina");

			}
			flush();
	}
	curl_close($ch);
	die;
?>