<?

function salvarDadosCadastro(){
	global $cadastro,$db,$pagina;
	list($nome) = explode(" ",normaliza($_POST['nome']));
				$_POST['sobrenome'] = trim(preg_replace("/".$nome."(.)?/","${1}",normaliza($_POST['nome'])));
				$_POST['nome'] = $nome;
				
				
				$_POST['nome_completo'] = trim($_POST['nome'].' '.$_POST['sobrenome'].' '.$_POST['nome_fantasia']);
				
				$_POST['endereco'] = trim(normaliza($_POST['endereco']));
				
				$_POST['rg'] = $_POST['inscricao'] ? $_POST['inscricao'] : $_POST['rg'];
				
				$_POST['ip'] = $_SERVER['REMOTE_ADDR'];
				
				
				$_POST['OBS'] = pre($_SERVER,true);
				
				
				if($_POST['nome_fantasia'] ) $_POST['nome'] = $_POST['razao_social'];
				if($_POST['responsavel'] )$_POST['sobrenome'] = $_POST['responsavel'];
				if($_POST['cnpj'] )$_POST['cpf'] = $_POST['cnpj'];
				
				$_POST['idcidades'] = $_POST['cidade'];
				$_POST['idestados'] = $_POST['estado'];
				$_POST['idbairros'] = $_POST['bairro'];
				

					$__utmz = explode("utmcsr",$_REQUEST['__utmz']);
					$__utmz = parse_url(preg_replace("/\|/","&","utmcsr".$__utmz[1]));
					parse_str("&".$__utmz['path'],$__utmz);
                    
					$_POST['origem'] = $__utmz['utmcsr'].
											($__utmz['utmccn'] ? '|'.$__utmz['utmccn'] : "").
											($__utmz['utmcct'] ? '|'.$__utmz['utmcct'] : "").
											($__utmz['utmcmd'] ? '|'.$__utmz['utmcmd'] : "");
					
				if($cadastro->conectado()){
					unset($_POST['senha']);
					$db->editar("cadastros",$cadastro->id);
					if($db->erro){
						$ret['status'] = 'erro';
						$ret['mensagem'] = $db->erro;
					}else{
						$cadastro->atualizarSession();
						$_SESSION['convidado'] = new objetoDb('clientes',$cadastro->clientes->id);
						$convidado = $_SESSION['convidado'];
						$ret['status'] = 'ok';
						$ret['mensagem'] = "<h3>Seu dados foram registrados com sucesso</h3>";
						//pre($_SESSION['objetos'][$cadastro->id.'@'.$cadastro->tabela]);
					}
				}else{
//					if($convidado->id){
//						$_POST['idclientes'] = $convidado->id;
//					}else{
//						$db->query('select idclientes from clientes where idclientes = "'.$_REQUEST['idclientes'].'" and codigo_acesso = "'.$_REQUEST['codigo_acesso'].'"');
//						if($db->rows == 0){
//								$ret['status'] = 'erro';
//								$ret['mensagem'] = 'O código de acesso não corresponde aos noivos selecionados.';
//								break;
//						}
//					}

					  require_once('recaptchalib.php');
					  $privatekey = "6LdMHPMSAAAAAKK9L7O9tuypR0S5mc110AyCwDH-";
					  $resp = recaptcha_check_answer ($privatekey,
													$_SERVER["REMOTE_ADDR"],
													$_POST["recaptcha_challenge_field"],
													$_POST["recaptcha_response_field"]);
					
					  if (!$resp->is_valid) {
							$ret['status'] = 'erro';
							$ret['mensagem'] = 'O código de verificaçao não foi digitado corretamente';
							return $ret;
					  }
 
					$db->query('select idcadastros from cadastros where email = "'.$_REQUEST['email'].'"');
					if($db->rows){
							$ret['status'] = 'erro';
							$ret['mensagem'] = 'O e-mail '.$_REQUEST['email'].' já está cadastrado em nosso sistema';
							return $ret;
					}
//					$db->query('select idcadastros from cadastros where email = "'.$_REQUEST['email'].'"');
//					if($db->rows){
//							$ret['status'] = 'erro';
//							$ret['mensagem'] = 'O e-mail '.$_REQUEST['email'].' já está cadastrado em nosso sistema';
//							break;
//					}
//					$db->query('select idcadastros from cadastros where cpf = "'.$_REQUEST['cpf'].'"');
//					if($db->rows){
//							$ret['status'] = 'erro';
//							$ret['mensagem'] = 'O CPF '.$_REQUEST['cpf'].' já está cadastrado em nosso sistema';
//							break;
//					}
					
								
								
					$_POST['data_cadastro'] = date("d/m/Y");
					$chave = $_POST['chave'] = md5($_REQUEST['email'].date("d/m/Y h:i:s"));
					$_POST['_senha'] = $_POST['senha'];
					$_POST['senha'] = md5($_POST['senha']);
					$_POST['_email'] = $_POST['email'];
					$_POST['autorizado'] = 0;
					$_POST['cep'] = preg_replace("/\.|-/i","",$_POST['cep']);
					$db->inserir("cadastros");
					
					$cadastro = new cadastro($db->inserted_id);
					
					$ret['cadastro'] = $cadastro->sessao;
					
					if($db->erro){ 
						$ret['status'] = 'erro';
						$ret['mensagem'] = "<h2>Ocorreu um erro</h2>
								<p>Não foi possível inserir em nosso sistema o seu cadastro de cliente.</p>
								<p>Por favor, volte à tela anterior e verifique se todos os seus dados foram digitados corretamente e todos os campos obrigatórios foram informados.</p>".$db->erro;
					}else{
								$corpo = ('
								<h2>Caro(a), '.$cadastro->nome.' seja bemvindo(a)</h2>
								<p>Seu e-mail <strong>'.$_REQUEST['email'].'</strong> foi cadastrado em nosso sistema, mas para garantir que está correto é preciso que você confirme sua intenção acessando o link:<p>
								<p><a href="'.$pagina->localhost.'Cliente/Confirma/'.$chave.'">'.$pagina->localhost.'Cliente/Confirma/'.$chave.'</a></p>
								<p>Se você não consegue clicar no link, copie e cole na barra de endereço:</p>
								<p>'.$pagina->localhost.'Cliente/Confirma/'.$chave.'</p>
								<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua participação</p>
								');
								
								$ret['status'] = 'ok';
								$ret['mensagem'] = '<h3>Seus dados foram registrados com sucesso</h3>Transferindo para próximo passo';
								
								if(!ereg('nox',$_SERVER['HTTP_HOST'])){
									if(mailClass($_REQUEST['email'], "Cadastro na Área Restrita ".$pagina->localhost,$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina")){
//										  if($pedido->id){
											  //$ret['script'] = 'window.location="'.$_POST['referer'].'Cliente/Carrinho"';
											  $ret['script'] = 'window.location="'.$pagina->localhost.'Cliente/Carrinho"';
//										  }else{
//											  $ret['mensagem'] .= 'Você receberá uma mensagem de confirmação e ativação no e-mail <strong>'.$_POST['email'].'</strong>. Siga as instruções contidas nele para acessar o conteúdo da área restrita.';
//										  }

									}else{
										$ret['mensagem'] .= 'Não foi possível enviar neste momento o e-mail de ativação de seu cadastro, por favor entre em contato com a administração do site e solicite que seu cadastro seja validado.';
									}
	
								}
							}
							mailClass("noxsulivan@gmail.com","Dados Cadastrais mesacor","<h2>Segue</h2>".pre($_REQUEST,true),$_REQUEST['email'],"Site Mesacor");
				}
				return $ret;
}

function salvarDedicatoria( $_id = NULL, $_qtd = NULL, $idclientes = NULL){
	global $pagina,$cadastro,$db,$ret;
	
			if($db->query("update pedidos set padrinhos = '".$_POST['padrinhos']."', dedicatoria = '".nl2br($_POST['dedicatoria'])."' where idpedidos = '".$_COOKIE['pedidoID']."'")){
				$ret['status'] =  "ok";
				$ret['mensagem'] =  "A mensagem foi anexada ao pedido.";
			}else{
				$ret['status'] =  "erro";
				$ret['mensagem'] =  $db->erro;
			}
}
function adicionarCarrinho( $_id = NULL, $_qtd = NULL, $_cliente = NULL){
	global $pagina,$cadastro,$db,$ret,$pedido;
	
	if(preg_match("/{baidu|googlebot|spider}/i",$_SERVER['HTTP_USER_AGENT']) or !isset($_COOKIE['primeiraVisitaMesacor'])){
		echo "<h1>Certifique-se de que seu navegador está com a opção de COOKIES habilitada.</h1>";
		//mail("noxsulivan@gmail.com","BOT",pre($_REQUEST,true).pre($_SERVER,true));
 
		return;
	}
		
		$_cliente = !is_null($_cliente) ?  $_cliente : 0;
		$produto = new objetoDb('produtos',$_id);
		
		$index = $_id.'-'.$_cliente;
			if((float) $produto->preco_promocional > 0){
				$preco = number_format($produto->preco_promocional,2,",",".");
			}else{
				$preco = number_format($produto->preco_venda,2,",",".");
			}		
				
			
		if(!$pedido->id){
			$var = array();
					$__utmz = explode("utmcsr",$_REQUEST['__utmz']);
					$__utmz = parse_url(preg_replace("/\|/","&","utmcsr".$__utmz[1]));
					parse_str("&".$__utmz['path'],$__utmz);
                    
					$origem = $__utmz['utmcsr'].
											($__utmz['utmccn'] ? '|'.$__utmz['utmccn'] : "").
											($__utmz['utmcct'] ? '|'.$__utmz['utmcct'] : "").
											($__utmz['utmcmd'] ? '|'.$__utmz['utmcmd'] : "");
			$db->query("insert into pedidos set idcadastros = '".($cadastro->conectado() ? $cadastro->id : '0')."', sessionid = '".session_id()."', origem = '".$origem."'");

			setcookie("pedidoID",$db->inserted_id,time()+(60*60*24*30),'/');
			$pedido = new objetoDb('pedidos',$db->inserted_id,true);
			$ret['status'] =  "ok";
			$ret['mensagem'] =  "Carrinho criado";
			
		}
					
		
		$sql = "select * from itens where idpedidos = '".$_COOKIE['pedidoID']."' and  idprodutos = '".$_id."' and  idclientes = '".$_cliente."'";
		$db->query($sql);
		//while($res = $db->fetch()){
				//$item = new objetoDb('itens',$res['iditens']);
		
		if($db->rows){
			//$_transporte = serialize(array('quantidade'=>$_SESSION['itensCarrinho'][$_id],'valor'=>$preco,'variacao'=>$variacao->id,'cliente'=>$idclientes,'cliente'=>$_cliente));
			$sql = "update itens set quantidade = quantidade + '".$_qtd."' where idpedidos = '".$pedido->id."' and idprodutos = '".$_id."' and idclientes = '".$_cliente."'";
			if($db->query($sql)){
				$ret['status'] =  "ok";
				$ret['mensagem'] =  "Item atualizado";
			}else{
				$ret['status'] =  "erro";
				$ret['mensagem'] =  $db->erro;
			}
		}else{
			//$_transporte = serialize(array('quantidade'=>$_SESSION['itensCarrinho'][$_id],'valor'=>$preco,'variacao'=>$variacao->id,'cliente'=>$idclientes,'cliente'=>$_cliente));
			$sql = "insert into itens set idpedidos = '".$pedido->id."', idprodutos = '".$_id."', idclientes = '".$_cliente."', quantidade = '".$_qtd."'";
			if($db->query($sql)){
				$ret['status'] =  "ok";
				$ret['mensagem'] =  "Item Adicionado";
			}else{
				$ret['status'] =  "erro";
				$ret['mensagem'] =  $db->erro;
			}
		}
		//echo $db->erro;
		//echo $sql;
}
function adicionarListaPresentes( $_id = NULL, $_qtd = NULL){
	global $pagina,$cliente,$db,$ret;
			
		$produto = new objetoDb('produtos',$_id);
		
		$index = $_id.'-'.$cliente->id;
			if((float) $produto->preco_promocional > 0){
				$preco = number_format($produto->preco_promocional,2,",",".");
			}else{
				$preco = number_format($produto->preco_venda,2,",",".");
			}		
										
		if($db->rows("select * from presentes where idprodutos = '".$_id."' and idclientes = '".$cliente->id."'")){
			$_SESSION['itensLista'][$index] += $_qtd;
			if($db->query("update presentes set quantidade = '".$_SESSION['itensLista'][$index]."' where idprodutos = '".$_id."' and idclientes = '".$cliente->id."'")){
				$ret['status'] =  "ok";
				$ret['mensagem'] =  "Item atualizado";
			}else{
				$ret['status'] =  "erro";
				$ret['mensagem'] =  $db->erro;
			}
		}else{
			$_SESSION['itensLista'][$index] = $_qtd;
			if($db->query("insert into presentes set idprodutos = '".$_id."', idclientes = '".$cliente->id."', quantidade = '".$_qtd."'")){
				$ret['status'] =  "ok";
				$ret['mensagem'] =  "Item Adicionado";
			}else{
				$ret['status'] =  "erro";
				$ret['mensagem'] =  $db->erro;
			}
		}
}
function adicionarListaChas( $_id = NULL, $_qtd = NULL){
	global $pagina,$cliente,$db,$ret;
			
		$produto = new objetoDb('produtos',$_id);
		
		$index = $_id.'-'.$cliente->id;
			if((float) $produto->preco_promocional > 0){
				$preco = number_format($produto->preco_promocional,2,",",".");
			}else{
				$preco = number_format($produto->preco_venda,2,",",".");
			}		
							
		if(!isset($_SESSION['itensListaCha'])){
			$var = array();
			$_SESSION['itensListaCha'] = $var;
			$ret['status'] =  "ok";
			$ret['mensagem'] =  "Lista criada";
		}
					
		if(array_key_exists($index,$_SESSION['itensListaCha'])){
			$_SESSION['itensLista'][$index] += $_qtd;
			if($db->query("update chas set quantidade = '".$_SESSION['itensListaCha'][$index]."' where idprodutos = '".$_id."' and idclientes = '".$cliente->id."'")){
				$ret['status'] =  "ok";
				$ret['mensagem'] =  "Item atualizado";
			}else{
				$ret['status'] =  "erro";
				$ret['mensagem'] =  $db->erro;
			}
		}else{
			$_SESSION['itensListaCha'][$index] = $_qtd;
			if($db->query("insert into chas set idprodutos = '".$_id."', idclientes = '".$cliente->id."', quantidade = '".$_qtd."'")){
				$ret['status'] =  "ok";
				$ret['mensagem'] =  "Item Adicionado";
			}else{
				$ret['status'] =  "erro";
				$ret['mensagem'] =  $db->erro;
			}
		}
}
function atualizarQuantidade( $_id = NULL, $_qtd = NULL){
	global $db;
	list($_id,$_cliente) = explode("-",$_id);
	$index = $_id.'-'.$_cliente;
	$db->query("update itens set quantidade = 0 where idpedidos = '".$_COOKIE['pedidoID']."' and idprodutos = '".$_id."' and idclientes = '".$_cliente."'");
	adicionarCarrinho( $_id, $_qtd, $_cliente);
}
function marcarPresente( $_id = NULL, $_cliente = NULL ){
	global $db;
	$db->query("update itens set presente = 'sim' where idpedidos = '".$_COOKIE['pedidoID']."' and idprodutos = '".$_id."' and idclientes = '".$_cliente."'");
}
function marcaVariacao( $produto, $variacao ){
	global $ret;
	$_SESSION['variacoes'][$produto] = $variacao;
	$ret['status'] = $produto.'-'.$variacao;
	
}
function atualizarQuantidadeLista( $_id = NULL, $_qtd = NULL){
	$index = $_id.'-'.$cliente->id;
	$_SESSION['itensLista'][$index] = 0;
	adicionarLista( $_id, $_qtd);
}
function acrescentarLista( $_id = NULL, $_qtd = NULL){
	global $pagina,$cadastro,$db,$ret;
	$db->query("update presentes set quantidade = quantidade + '".$_qtd."' where idpresentes = '".$_id."'");
	return $db->fetch("select quantidade from presentes where idpresentes = '".$_id."'",'quantidade');
	
}
function acrescentarListaCha( $_id = NULL, $_qtd = NULL){
	global $pagina,$cadastro,$db,$ret;
	$db->query("update chas set quantidade = quantidade + '".$_qtd."' where idchas = '".$_id."'");
	return $db->fetch("select quantidade from chas where idchas = '".$_id."'",'quantidade');
	
}
function consultaFrete( $cep, $extra ){
	
	global $pagina,$cadastro,$db,$ret,$xmlFrete,$pedido;
	
	$isento = false;
	
				if(count($pedido->itens) > 0){
						foreach($pedido->itens as $item){							
							if($item->produtos->frete_gratis == 'sim'){
								$isento = true;
							}
							
							//if($item->produtos->has("categorias",array(31,35,70)))
								//$_TEEC = true;
							
						}
				}
				
		
		$cep = preg_replace("/[^0-9]/","",$cep);
		
		list($peso, $peso_vol, $valor, $dimensoes) = explode("@",$extra);
		
		
        $local = carregaCEP($cep);
			
		$ret['local'] = $local;
			
		if(ereg("SC",$local['uf'])){
				$isento = true;
		}
		
		if($valor = calculaFrete($pagina->configs['cep'] , $cep ,$peso_vol, $isento, $dimensoes)){
			$ret['status'] =  "ok";
			$ret = array_merge($ret,$valor);
			//$ret['extra'] = pre(max($peso,$peso_vol),true);
			//$ret['xmlFrete'] = pre($xmlFrete,true);
		}else{
			$ret['status'] =  "erro";
			$ret['mensagem'] =  "Não foi possível conectar com o servidor dos Correios, tente novamente. (". $cep. ' - '.$peso. " - ".$valor.")" ;
			$ret['total'] =  '20.00' ;
		}
}
function atualizaFrete( $_cep = null , $_peso = null, $isento = null, $dimensao ){
	//echo $_cep.' = '.$_peso;
	global $pagina,$cadastro,$db,$ret,$xmlFrete,$pedido,$isento;
	
						foreach($pedido->itens as $item){
							$_DC = false;
							//foreach($item->produtos->categorias as $c){
								//if($c->id == 33)
									//$_DC = true;
							//}
							//if($item->produtos->has("categorias",array(31,35,70)))
								//$_TEEC = true;
								
							if($item->produtos->frete_gratis != 'nao' and count($pedido->itens) == 1){
								$frete_gratis = true;
							}
							//if($_DC or $item->produtos->frete_gratis == 'sim' ){
								//$peso += 0.01;
							//}
							//else
							if($item->clientes->id > 1){
								$peso += 0.01;
							}else{
								$peso += max(($item->produtos->peso + $item->produtos->peso_volumetrico)/2,0.3)*$item->quantidade;
							}
							
						}
				$ret['peso'] =  $peso;
	if(count($pedido->itens) > 0){
		
		$cep = preg_replace("/[^0-9]/","",$_cep);
        $local = carregaCEP($cep);
			
		if($local['uf'] == 'SC'){
					//$isento = true;
		}
		
		
		if($pedido->valor > $pagina->configs['limite_frete_gratis']){
			if(ereg("SC",$local['uf'])){
					//$isento = true;
			}
		}
			
		if($frete_gratis){
					$isento = true;
		}
					
		//echo "$peso . $_log . $_cid . $isento . $FC". $item->produtos->frete_gratis;									
		$valor = calculaFrete($pagina->configs['cep'] , $_cep ,$peso, $isento, $dimensao);
		
		
		if(!is_array($ret)) $ret = array();
		
		$ret = array_merge($ret,$valor);
		if($valor['isento'] == true or $isento == true){
			$valor['pac_valor'] = $_POST['valor_frete'] = "0,00";
			$_POST['prazo'] = $valor['pac_valor'];
		
			$db->editar('pedidos',$pedido->id);
			unset($pedido);
			$pedido = new objetoDb('pedidos',$pedido->id,true);
			return '
			Peso: <strong>'.$peso.'kg</strong><br>
			<div class="pedidoSubTotal">
			<input type="radio" format="EN" checked="checked" name="item_frete_1" value="0" />
			<input type="hidden" name="prazo[EN]" value="'.$valor['pac_prazo'].'" />
			<strong>'.$valor['mensagem'].'</strong><br>Prazo de Entrega: <strong>'.$valor['pac_prazo'].' dias</strong></div>
			
			
			<input type="hidden" name="tipo_frete" id="tipo_frete" value="EN" checked="true" />
			<input type="hidden" name="item_peso_1" value="'.$_peso.'" />';
		}else{
			$_POST['valor_frete'] = number_format($valor['pac_valor'],2,',','.');
			$_POST['prazo'] = $valor['pac_valor'];
		
			$db->editar('pedidos',$pedido->id);
			unset($pedido);
			$pedido = new objetoDb('pedidos',$pedido->id,true);
			//pre($pedido->valor_frete);
			
			$ret = '
			Peso: <strong>'.$_peso.'kg</strong><small>Peso com embalagem ou Peso Cúbico</small><br>
			<div class="pedidoSubTotal">
			<input type="radio" onchange="carrinho.mudaFormatoFrete(this)" format="EN" checked="checked" name="item_frete_1" value="'.number_format($valor['pac_valor'],2,',','').'" />
			<input type="hidden" name="prazo[EN]" value="'.$valor['pac_prazo'].'" />
			Encomenda normal: <strong>R$'.number_format($valor['pac_valor'],2,',','.').'</strong><br>Prazo de Entrega: <strong>'.$valor['pac_prazo'].' dias</strong></div>';
			
			if ((float)$valor['sed_valor'] > 0) $ret .= '
			<div class="pedidoSubTotal">
			<input type="radio" onchange="carrinho.mudaFormatoFrete(this)" format="SEDEX" name="item_frete_1" value="'.number_format($valor['sed_valor'],2,',','').'" />
			<input type="hidden" name="prazo[SEDEX]" value="'.$valor['sed_prazo'].'" />
			Sedex: <strong>R$'.number_format($valor['sed_valor'],2,',','.').'</strong><br>Prazo de Entrega: <strong>'.$valor['sed_prazo'].' dias</strong></div>';
			
			if ((float)$valor['ese_valor'] > 0) $ret .= '
			<div class="pedidoSubTotal">
			<input type="radio" onchange="carrinho.mudaFormatoFrete(this)" format="e-SEDEX" name="item_frete_1" value="'.number_format($valor['ese_valor'],2,',','').'" />
			<input type="hidden" name="prazo[e-SEDEX]" value="'.$valor['ese_prazo'].'" />
			Via e-Sedex: <strong>R$'.number_format($valor['ese_valor'],2,',','.').'</strong><br>Prazo de Entrega: <strong>'.$valor['ese_prazo'].' dias</strong></div>';
			
			return $ret;
		}
	}else{
		  return "<h3>O carrinho está vazio</h3>";
	}
}
function escolheFrete( $_cep = null , $_peso = null, $isento = null, $dimensao ){
	global $pagina,$cadastro,$db,$ret,$xmlFrete,$pedido,$isento, $valor;
	
						foreach($pedido->itens as $item){
							//$_DC = false;
//							if(count($item->produtos->categorias)){
//								foreach($item->produtos->categorias as $c){
//									if($c->id == 33)
//										$_DC = true;
//								}
//							}
							if($item->produtos->frete_gratis == 'sim' and count($pedido->itens) == 1){
								$isento = true;
							}
						}
						//pre("1. $isento");
	
	if(count($pedido->itens) > 0){
							$cep = preg_replace("/[^0-9]/","",$_cep);
							//$local = carregaCEP($cep);
								
//							if($local['uf'] == 'SC'){
//									$isento = true;
//							}
//									

//pre($cadastro->idestados);
							if(in_array($cadastro->idestados,array(24))){
									$local_isento = true;
							}
							
						//pre("2. $isento");
							
		
//													//////////////////////////cidades da regiao ABSOLUTAMENTE GRATIS
//													$db->query("select idcidades, idestados from logradouros where cep = '".$destino."'");
//													if($db->rows){
//														$res = $db->fetch();
//														if(preg_match("/8357|8396|8507|8521|8509|8390|8377/i",$res['idcidades'])){
//																$isento = true;
//														}
//													}else{
//														$db->query("select idcidades, idestados from cidades where cep = '".$destino."'");
//														$res = $db->fetch();
//														if(preg_match("/8357|8396|8507|8521|8509|8390|8377/i",$res['idcidades'])){
//																$isento = true;
//														}
//													}
													
													////////////////////////Estados com frete gratis acima de $pagina->configs['limite_frete_gratis'] reais
//													$sql = "select idestados from logradouros where cep = '".str_replace("-","",$_cep)."'";
//													$_log = $db->fetch($sql,"idestados");
//													if($db->rows){
//														if(preg_match("/16|19|21|24|25/i",$_log)){
//																$local_isento = true;
//														}
//													}else{
//														$sql = "select idestados from cidades where cep = '".str_replace("-","",$_cep)."'";
//														$_cid = $db->fetch($sql,"idestados");
//														if(preg_match("/16|19|21|24|25/i",$_cid)){
//																$local_isento = true;
//														}
////														if((ereg("8|13",$_log) or ereg("8|13",$_cid) ) and $FC){
////																	$local_isento = true;
////														}
//													}
				
//		if(($pedido->valor > $pagina->configs['limite_frete_gratis']) and $local_isento){// and !$_TEEC){
//			$isento = true;
//		}
//		if($FC or $_DC){
//			$local_isento = $isento = true;
//		}



		$CAIXA_COM = 100;
		$CAIXA_LAR = 100;
		$CAIXA_ALT = 100;	
		foreach($pedido->itens as $item){
			if($item->clientes->id > 1){
				
				$pesoR += ($item->quantidade > 1) ? max($item->produtos->peso*$item->quantidade,0.3) : max($item->produtos->peso,0.3);
				$pesoV += ($item->quantidade > 1) ? max($item->produtos->peso_volumetrico*$item->quantidade,0.3) :  max($item->produtos->peso_volumetrico,0.3);									
				$peso = $pesoR.'@'.$pesoV;
				$_cliente = TRUE;
			}elseif($local_isento or $item->produtos->frete_gratis == 'sim'){
				$pesoR += ($item->quantidade > 1) ? max($item->produtos->peso*$item->quantidade,0.3) : max($item->produtos->peso,0.3);
				$pesoV += ($item->quantidade > 1) ? max($item->produtos->peso_volumetrico*$item->quantidade,0.3) :  max($item->produtos->peso_volumetrico,0.3);	
				//pre($item->produtos->peso_volumetrico." - ".$pesoV);
				$peso = $pesoR.'@'.$pesoV;
				$isento = true;
			}else{
				$pesoR += ($item->quantidade > 1) ? max($item->produtos->peso*$item->quantidade,0.3) :  max($item->produtos->peso,0.3);
				$pesoV += ($item->quantidade > 1) ? max($item->produtos->peso_volumetrico*$item->quantidade,0.3) :  max($item->produtos->peso_volumetrico,0.3);									
				$peso = $pesoR.'@'.$pesoV;
			}
				
				$dimensoes = explode("-",$item->produtos->dimensoes);
				$_dim = explode("x",$dimensoes[0]);
				sort($_dim);
				
				$_COM = $_dim[2];
				$_LAR = $_dim[1];
				$_ALT = $_dim[0];
				
				//pre($_COM."x".$_LAR."x".$_ALT);
				
				$CAIXA_COM = max($CAIXA_COM,$_COM);
				$CAIXA_LAR = max($CAIXA_LAR,$_COM);
				
				$CAIXA_ALT += $_ALT;
		
		}
		
		
		//pre("peso - volume: ". eregi_replace("/@/i"," - ",$peso));
		if(count($pedido->itens) > 1){
			$valor = calculaFrete($pagina->configs['cep'] , $_cep ,$pesoR."@".(($CAIXA_COM * $CAIXA_LAR * $CAIXA_ALT ) / 6000000), $isento, $CAIXA_COM."x".$CAIXA_LAR."x".$CAIXA_ALT);
		}else{
			$valor = calculaFrete($pagina->configs['cep'] , $_cep ,$peso, $isento, $dimensao);
		}
		
		//pre($isento);
		//pre($peso);
		
		//if(!is_array($ret)) $ret = array();
		
		//$ret = array_merge($ret,$valor);
		
		//pre($valor['isento'].' - '.$isento.' - '.$pesoV);
		
		if($pesoR == 0){
		//if($valor['isento'] == true or $isento == true or $pesoV == 0){
			
			
			$ret = '<div class="sidebar24">';
							
			if($_cliente){
				$ret .= '
					<h2 class="float-right" style="text-align:right">Frete Gr&aacute;tis</h2><h3 class="float-right" style="text-align:right">O pedido será entregue diretamente aos noivos</h3>
					<input type="hidden" name="prazoPAC" value="'.$valor['pac_prazo'].'" />
					<input type="hidden" name="fretePAC" value="'.$valor['pac_valor'].'" />
					';	
			}else{
				$ret .= '<h3 class="float-right">Para: '.utf8_decode($cadastro->cidades->cidade."/".$cadastro->estados->url.'</h3><br><br>');
				$ret .= '<label  class="awesome grey">
					<input type="radio" name="tipo_frete" value="PAC" checked="checked" onchange="$(\'.parc\').hide();$(\'#parcPAC\').show();$(\'.pedidototal\').hide();$(\'#pedPAC\').show();$(\'.boleto\').hide();$(\'#bolPAC\').show();" />Frete Gr&aacute;tis - '.$valor['pac_prazo'].' dias
					<input type="hidden" name="prazoPAC" value="'.$valor['pac_prazo'].'" />
					<input type="hidden" name="fretePAC" value="'.$valor['pac_valor'].'" /></label>
					';
					
				if((float)$valor['sed_valor'] > 0){
					$ret .= '<label  class="awesome grey">
					<input type="radio" name="tipo_frete" value="SEDEX" onchange="$(\'.parc\').hide();$(\'#parcSED\').show();$(\'.pedidototal\').hide();$(\'#pedSEDEX\').show();$(\'.boleto\').hide();$(\'#bolSEDEX\').show();" />SEDEX
					R$'.number_format($valor['sed_valor'],2,',','.').' - '.$valor['sed_prazo'].($valor['sed_prazo']<2 ? "dia" : "dias").'
					<input type="hidden" name="prazoSEDEX" value="'.$valor['sed_prazo'].'" />
					<input type="hidden" name="freteSEDEX" value="'.$valor['sed_valor'].'" /></label>';
				}
				
				if((float)$valor['ese_prazo'] > 0){
					$ret .= '<label  class="awesome grey">
					<input type="radio" name="tipo_frete" value="eSEDEX" onchange="$(\'.parc\').hide();$(\'#parcESE\').show();$(\'.pedidototal\').hide();$(\'#pedeSEDEX\').show();$(\'.boleto\').hide();$(\'#boleSEDEX\').show();" />e-Sedex
					R$'.number_format($valor['ese_valor'],2,',','.').' - '.$valor['ese_prazo'].($valor['ese_prazo']<2 ? "dia" : "dias").'
					<input type="hidden" name="prazoeSEDEX" value="'.$valor['ese_prazo'].'" />
					<input type="hidden" name="freteeSEDEX" value="'.$valor['ese_valor'].'" /></label>
					';
				}
				
				$ret .= '<a href="javascript:void(0);" class="botao grey float-right" onClick="segue(\'dados\');"> Ou informar outro endereço para entrega &raquo;</a>';
			
			}
			$ret .= '</div>';
			//$ret .= 'Peso: <strong>'.$peso.'kg</strong><small><br>Peso com embalagem ou Peso Cúbico considerado pelo ECT-Correios</small>';
			
			return $ret;
		}else{
			
			$ret .= '<h3 class="float-right">Para: '.utf8_decode($cadastro->cidades->cidade."/".$cadastro->estados->url.'</h3><br><br>');
									
			if((float)$valor['pac_valor'] == 0){
				$ret .= '<label class="awesome grey">
				<input type="radio" checked="checked" name="tipo_frete" value="PAC" class="tipo_frete"/>Fréte Grátis - '.$valor['pac_prazo'].' dias - PAC (Encomenda normal)</label>
				<input type="hidden" name="prazoPAC" value="'.$valor['pac_prazo'].'" />
				<input type="hidden" name="fretePAC" value="'.$valor['pac_valor'].'" />';
			}elseif((float)$valor['pac_valor'] > 0){
				$ret .= '<label class="awesome grey">
				<input type="radio" checked="checked" name="tipo_frete" value="PAC" class="tipo_frete"/>R$'.number_format($valor['pac_valor'],2,',','.').' - '.$valor['pac_prazo'].' dias - PAC (Encomenda normal)</label>
				<input type="hidden" name="prazoPAC" value="'.$valor['pac_prazo'].'" />
				<input type="hidden" name="fretePAC" value="'.$valor['pac_valor'].'" />';
			}else{
				$ret .= '<li>Não foi possível calcular o frete via Encomenda no momento devido a falta de comunicação com a trasnportadora, tente novamente.</li>';
			}

			if((float)$valor['sed_prazo'] > 0){
				$ret .= '<label class="awesome grey">
				<input type="radio" name="tipo_frete" value="SEDEX" class="tipo_frete" />R$'.number_format($valor['sed_valor'],2,',','.').' - '.$valor['sed_prazo'].($valor['sed_prazo']<2 ? "dia" : "dias").' - SEDEX</label>
				<input type="hidden" name="prazoSEDEX" value="'.$valor['sed_prazo'].'" />
				<input type="hidden" name="freteSEDEX" value="'.$valor['sed_valor'].'" />';
			}
			
			if((float)$valor['ese_prazo'] > 0){
				$ret .= '<label class="awesome grey">
				<input type="radio" name="tipo_frete" value="eSEDEX" class="tipo_frete" />R$'.number_format($valor['ese_valor'],2,',','.').' - '.$valor['ese_prazo'].($valor['ese_prazo']<2 ? "dia" : "dias").' - e-Sedex</label>
				<input type="hidden" name="prazoeSEDEX" value="'.$valor['ese_prazo'].'" />
				<input type="hidden" name="freteeSEDEX" value="'.$valor['ese_valor'].'" />';
			}
			
			//$ret .= '<a href="javascript:void(0);" onClick="segue(\'dados\');"> Ou informar outro endereço para entrega &raquo;</a>';
			//$ret .= 'Peso: <strong>'.$peso.'kg</strong><small>Peso com embalagem ou Peso Cúbico considerado pelo ECT-Correios</small>';
			//pre($valor);
			return $ret;
		}
	}else{
		  return "<h3>O carrinho está vazio</h3>";
	}
}

function atualizaParcelamento(){
	global $pedido,$isento,$xmlFrete;
	$_frete = $_SESSION['frete'];
	
	if(count($pedido->itens) > 0){
//		$ret .= '<div id="normal">';
//		$preco = $pedido->valor + (($isento) ? 0  :  $pedido->valor_frete );
//		  for( $i = 1 ; $i <=6; $i++) {
//			  $pre = $preco / $i;
//			  if($pre > 30){
//                  $ret .= ' <li><label><input type="radio" name="parcelas" value="'.$i.'"/><strong>'.$i.'x</strong> de R$ '.number_format($pre ,2,",",".").' = R$'.number_format($pre * $i ,2,",",".").' sem acréscimo</label></li>';
//			  }
//		  }
//		$ret .= '</div>';
		//pre($pedido->valor);
		//pre($xml1->Valor);
		$ret .= '<div id="parc_pac">';
		$preco = $pedido->valor + (($isento) ? 0  : str_replace(",",".",$_frete['pac_valor']) );
		  for( $i = 1 ; $i <=6; $i++) {
			  $pre = $preco / $i;
			  if($pre > 30){
                  $ret .= ' <li><label><input type="radio" name="parcelas" value="'.$i.'"/><strong>'.$i.'x</strong> de R$ '.number_format($pre ,2,",",".").' = R$'.number_format($pre * $i ,2,",",".").' sem acréscimo</label></li>';
			  }
		  }
		$ret .= '</div>';
		
		$ret .= '<div id="parc_sedex" style="display:none">';
		$preco = $pedido->valor + (($isento) ? 0  : str_replace(",",".",$_frete['sed_valor']) );
		  for( $i = 1 ; $i <=6; $i++) {
			  $pre = $preco / $i;
			  if($pre > 30){
                  $ret .= ' <li><label><input type="radio" name="parcelas" value="'.$i.'"/><strong>'.$i.'x</strong> de R$ '.number_format($pre ,2,",",".").' = R$'.number_format($pre * $i ,2,",",".").' sem acréscimo</label></li>';
			  }
		  }
		$ret .= '</div>';
		  return $ret;
	}else{
		  return "<h3>O carrinho está vazio</h3>";
	}
}
function escolheParcelamento($frete = 0, $valor = 0){
	global $pedido;
	
	if(count($pedido->itens) > 0){
		$preco = $valor + $frete;
		if($preco <= 30){
			  $pre = $preco ;
			$ret .= ' <li><label class="awesome grey"><input type="radio" checked="checked" name="parcelas" value="1"/><strong>Parcela única</strong> de R$ '.number_format($pre ,2,",",".").' *</label></li>';
		}else{
		  for( $i = 1 ; $i <=6; $i++) {
			  $pre = $preco / $i;
			  if($pre > 30){
                  $ret .= ' <li><label class="awesome grey"><input type="radio" name="parcelas" value="'.$i.'"/><strong>'.$i.'x</strong> de R$ '.number_format($pre ,2,",",".").' = R$'.number_format($pre * $i ,2,",",".").' sem acréscimo</label></li>';
			  }
		  }
		}
		return $ret;
	}else{
		  return "<h3>O carrinho está vazio</h3>";
	}
}
function escolheParcelamentoCielo($frete = 0, $valor = 0){
	global $pedido,$pagina;
	                  //$ret .= ' <li class="pagm pagVisa"><label class="botao blue float-right"><input type="radio" name="formaPagamento" value="A"/><strong>Débito à vista (Cartões Bradesco)</strong></label></li>';
	                  $ret .= ' <label class="awesome grey pagm pagDisc"><input type="radio" name="formaPagamento" value="A"/><strong>Somente Crédito à vista</strong></label>';
	                  $ret .= ' <label class="awesome grey pagm pagAmex"><input type="radio" name="formaPagamento" value="1"/><strong>Somente Crédito à vista</strong></label>';

	if(count($pedido->itens) > 0){
		$preco = $valor + $frete;
		if($preco <= $pagina->configs['parcela_minina']){
			  $pre = $preco ;
			$ret .= ' <label class="awesome grey pagm pagVisa pagAll"><input type="radio" checked="checked" name="formaPagamento" value="1"/><strong>Parcela única</strong> de R$ '.number_format($pre ,2,",",".").' *</label></li>';
		}else{
		  for( $i = 1 ; $i <=$pagina->configs['parcelas']; $i++) {
			  $pre = $preco / $i;
			  if($i == 1){
                  $ret .= ' <label class="awesome grey pagm pagVisa pagAll"><input type="radio" name="formaPagamento" value="'.$i.'"/>'.$i.' vez - R$ '.number_format($pre ,2,",",".").'</label>';
			  }elseif($pre > $pagina->configs['parcela_minina']){
                  $ret .= ' <label class="awesome grey pagm pagVisa pagAll"><input type="radio" name="formaPagamento" value="'.$i.'"/>'.$i.' vezes - R$ '.number_format($pre ,2,",",".").'</label>';
			  }
		  }
		}
		return $ret;
	}else{
		  return "<h3>O carrinho está vazio</h3>";
	}
}
function recuperarCupom($codigo){
	global $pagina,$cadastro,$db,$pedido,$cupom;
		  //$db->query("select idcupons from cupons where codigo = '".$cupom."' and validade <= today() and status = 'sim' ");
		  $db->query("select idcupons from cupons where codigo = '".$codigo."' and status = 'sim'");
		  if($db->rows){
			  $res = $db->fetch();
			  $cupom = new objetoDb('cupons',$res['idcupons']);
			  $db->query("update pedidos set idcupons = '".$cupom->id."' where idpedidos = '".$pedido->id."'");
			  setcookie("cupomID",$cupom->id,time()+(60*60*24*1),'/');
		  }
		  
		  
		  return $cupom;
}
function carregaCarrinhoMini(){
	global $pagina,$cadastro,$db;
					if($_COOKIE['pedidoID']){
						$total = 0;
						$db->query("select * from itens where idpedidos = '".$_COOKIE['pedidoID']."'");
						while($res = $db->fetch()){
							$item = new objetoDb('itens',$res['iditens']);
							
							if($item->produtos->preco_promocional > 0){
								$totalNormal	= ($item->produtos->preco_promocional * $item->quantidade);
								$precoUnitario	= $item->produtos->preco_promocional;
								if((float)$item->produtos->desconto > 0){
									$subDesconto = ($item->produtos->preco_promocional * $item->quantidade) - (($item->produtos->preco_promocional * $_qtd) * $item->produtos->desconto) / 100;
								}else{
									$subDesconto = ($item->produtos->preco_promocional * $item->quantidade) - (($item->produtos->preco_promocional * $_qtd) * $desconto_vista) / 100;
								}
								$preco = ($item->produtos->preco_venda * $_qtd);
							}else{
								$totalNormal	= (float)$item->produtos->preco_venda * $item->quantidade;
								$precoUnitario	= $item->produtos->preco_venda;
								if((float)$item->produtos->desconto > 0){
									$subDesconto = ($item->produtos->preco_venda * $item->quantidade) - (($item->produtos->preco_venda * $item->quantidade) * $item->produtos->desconto) / 100;
								}else{
									$subDesconto = ($item->produtos->preco_venda * $item->quantidade) - (($item->produtos->preco_venda * $item->quantidade) * $desconto_vista) / 100;
								}
								$preco = ($item->produtos->preco_venda * $item->quantidade);
							}
							$total += $totalNormal;
							
							
							$ret .= '
							<li id="itemCarrinho_'.$item->produtos->id.'">
							<a href="'.$pagina->localhost.'img/'.$item->produtos->fotos[0]['id'].'" class="funcaoAmpliar funcao" onclick="return hs.expand( this , {captionId: \'caption'.$item->produtos->id.'\'} );">';
							//$ret .= $produto->codigo;
							$ret .= '<strong>'.$item->produtos->produto.'</strong>
							</a>
							</li>';
						}
							$ret .= '<strong>Total: R$'.$total.'</strong>';
					}else{
						$ret = 'Nenhum item foi adicionado ao seu carrinho de compras';
					}
					
			return $ret;
}
function carregaListaMini(){
	global $pagina,$cliente,$db;
					$db->query("select * from presentes where idclientes = '".$cliente->id."'");
					if($db->rows){
						while($res = $db->fetch()){
							$produtos = new objetoDb('produtos',$res['idprodutos']);
							
							$ret .= utf8_encode($produtos->produto).'</br>';
						}
					}
			return $ret;
}
function carregaListaMiniChas(){
	global $pagina,$cliente,$db;
					$db->query("select * from chas where idclientes = '".$cliente->id."'");
					if($db->rows){
						while($res = $db->fetch()){
							$produtos = new objetoDb('produtos',$res['idprodutos']);
							
							$ret .= utf8_encode($produtos->produto).'</br>';
						}
					}
			return $ret;
}
function carregaCarrinhoFull(){
	global $pagina,$cadastro,$db,$total,$valorFrete,$totalGeral,$pesoTotal,$pesoComposto,$dimensao,$isento,$pedido,$totalDesconto,$cupom,$desconto_vista;
					//pre($_SESSION['itensCarrinho']);
					unset($_POST);
					
					$totalDesconto	= 0;
					
					if(count($pedido->itens) > 0){
						$total = 0;
						
						foreach($pedido->itens as $item){
										$obj = unserialize($res['transporte']);
										$_id =		$item->produtos->id;
										$_cliente =	$item->clientes->id ? $item->clientes->id : '0';
										$_qtd = 	$item->quantidade;
							
							
							
							
							$index = $_id.'-'.$_cliente;
							
//							$_DC = false;

							$desconto_vista = $pagina->configs['desconto_boleto'];
							
							if(count($item->produtos->categorias)){
								foreach($item->produtos->categorias as $c){
//									if($c->id == 33)
//										$_DC = true;
									//$desconto_vista = max($desconto_vista,$c->desconto_vista);
									
									$cat[] = $c->id;
								}
							}
							
							
							
							//pre($item->produtos->categorias);
							
							
							
							if($item->clientes->id < 1){
									$pesoR = max($item->produtos->peso,0.01)*$_qtd;
									$pesoV = max($item->produtos->peso_volumetrico,0.01)*$_qtd;									
									$pesoTotalSemTDCR += $pesoR;
									$pesoTotalSemTDCV += $pesoV;
									
									$dimensao .= $item->produtos->dimensoes."-";
							}
							if($item->clientes->id > 0){
								$isento = TRUE;
							}
							$prazo_total = 0;
							$prazo_total = max($prazo_total,$item->produtos->prazo_entrega);
							
							if($item->produtos->preco_promocional > 0){
								$totalNormal	= ($item->produtos->preco_promocional * $_qtd);
								$precoUnitario	= $item->produtos->preco_promocional;
								if((float)$item->produtos->desconto > 0){
									$subDesconto = ($item->produtos->preco_promocional * $_qtd) - (($item->produtos->preco_promocional * $_qtd) * $item->produtos->desconto) / 100;
								}else{
									$subDesconto = ($item->produtos->preco_promocional * $_qtd) - (($item->produtos->preco_promocional * $_qtd) * $desconto_vista) / 100;
								}
								$preco = ($item->produtos->preco_venda * $_qtd);
							}else{
								$totalNormal	= (float)$item->produtos->preco_venda * $_qtd;
								$precoUnitario	= $item->produtos->preco_venda;
								if((float)$item->produtos->desconto > 0){
									$subDesconto = ($item->produtos->preco_venda * $_qtd) - (($item->produtos->preco_venda * $_qtd) * $item->produtos->desconto) / 100;
								}else{
									$subDesconto = ($item->produtos->preco_venda * $_qtd) - (($item->produtos->preco_venda * $_qtd) * $desconto_vista) / 100;
								}
								$preco = ($item->produtos->preco_venda * $_qtd);
							}
							$total += $totalNormal;
							$desconto[] = $item->produtos->desconto;
							
							
							$totalDesconto += $subDesconto;
							
							
							
							
							if((float) $preco > 0){
								$ret .= '<input type="hidden" name="item_id_'.++$i.'" value="'.$item->produtos->id.'" />';
								$ret .= '<input type="hidden" name="item_descr_'.$i.'" value="'.$item->produtos->codigo."-".$item->produtos->produto.'" />';
								$ret .= '<input type="hidden" name="item_quant_'.$i.'" value="'.$_qtd.'" />';
								$ret .= '<input type="hidden" name="item_valor_'.$i.'" value="'.number_format( $precoUnitario , 2, '.', '' ).'" />';
							}
							
							$ret .= '
								<table border="0" id="itemCarrinho_'.$index.'" class="carrinhoListaLi">
									<tr>
										<td width="15%">
												<a href="'.$pagina->localhost."Produtos/Ver/".$item->produtos->url.'" alt="'.$item->produtos->id.'			">
												<img src="'.$pagina->localhost."img/".$item->produtos->fotos[0]['id'].'/110" alt="'.$item->produtos->fotos[0]['legenda'].'" /></a>
										</td>
										<td width="30%">
										
										<h2>'.$item->produtos->produto;
										
										if($_SESSION['variacoes'][$item->produtos->id]){
											$variacao = new objetoDb("variacoes",$_SESSION['variacoes'][$item->produtos->id]);
											$ret .= ' ('.$variacao->opcoes->opcao.')';
										}
										
							$ret .= '</h2></td>
										<td width="25%">';
												if((float) $preco > 0){
													$ret .= '<input id="qtd_'.$index.'" value="'.$_qtd.'" class="inputQtd float-left" /><a href="javascript:atualizarQuantidadeCarrinho(\''.$index.'\',$(\'#qtd_'.$index.'\').val())" class="botao grey float-left">Alterar</a>
													<a href="javascript:removerItem(\''.$index.'\')" class="botao grey float-right">Excluir</a>';
														}
										$ret .= '</td>
										<td width="30%" align="right">';
												if((float) $preco > 0){
													if($item->produtos->promocao == 'sim'){
														$ret .= 'de R$ <span class="overline">'.number_format($item->produtos->preco_venda,2,",",".").'</span>
														por <h3>R$ '.number_format($item->produtos->preco_venda - ($item->produtos->preco_venda * $item->produtos->desconto / 100) ,2,",",".").'</h3>';
													}elseif((float)$item->produtos->preco_promocional > 0){
														$ret .= '<span class="overline">de R$ '.number_format($item->produtos->preco_venda,2,",",".").'</span>
														por <h3>R$ '.number_format($item->produtos->preco_promocional,2,",",".").'</h3>';
													}else{
														$ret .= '<h3>R$ '.number_format($item->produtos->preco_venda,2,",",".").'</h3>';
														if((float)$item->produtos->desconto  > 1){
															$ret .= '<span>Produto com '.$item->produtos->desconto.'% para pagamento à vista no boleto.</span>
															<small>Desconto calculado na finalização.</small>';
														}
													}
													if($item->produtos->prazo_entrega){
															$ret .= '<em>Disponibilidade: '.$item->produtos->prazo_entrega.' dias</em>';
													}else{
															$ret .= '<em>Em estoque</em>';
													}
													
													$ret .= '<em>Peso/Volume</em>'.$item->produtos->peso.'/'.$item->produtos->peso_volumetrico;
												}else{
													$ret .= 'INDISPONÍVEL';
												}
											//$ret .= '</strong><br>Peso: '.($item->produtos->peso * $_qtd).'kg';
											
											
											/*if($freteGratis){
													$ret .= '<br><em>FRETE GRÁTIS</em>';
											}else*/if($item->clientes->id > 0){
													$ret .= '<br><em>Isento de frete. Entregue pela loja direto para os noivos</em>';
											}/*else{
												if($_DC){
													$ret .= '<h4>FRETE GRÁTIS</h4><small>Para todo o Brasil</small>';
												}
											}*/
											$ret .= '
										</td>
									</tr>
								</table>';
								
								
								if($item->produtos->brindes->id){
									$brinde = new objetoDb("produtos",$item->produtos->brindes->id);
									
												$ret .= '
													<table border="0" id="itemCarrinho_'.$index.'" class="carrinhoListaLi">
														<tr>
															<td rowspan="2" width="20%">
																	<a href="'.$pagina->localhost."Produtos/Ver/".$brinde->url.'" alt="'.$brinde->id.'			">
																	<img src="'.$pagina->localhost."img/".$brinde->fotos[0]['id'].'/120/90/1" alt="'.$item->produtos->fotos[0]['legenda'].'" /></a>
															</td>
															<td colspan="3" bgcolor="#efefef">
															
															<h3><a href="'.$pagina->localhost."img/".$brinde->fotos[0]['id'].'/300" alt="'.$brinde->id.'" onclick="return hs.expand( this , {captionId: \'caption'.$brinde->id.'\'} );">'.$brinde->codigo.'</a> '.$brinde->produto;
															
															
												$ret .= '</h3></td>
															<td colspan="2" bgcolor="#efefef" align="right">'.($item->clientes->id > 0 ? '<h3 class="precoGrd float-right"> <img src="'.$pagina->localhost.'_imagens/1271297555_geschenk_box_1.png" width="48" height="48" style="position:absolute; top:-10px; left:-45px;" />Presente para '.$item->clientes->nome_noiva." & ".$item->clientes->nome_noivo.'</h3>': '').'</td>
														</tr>
														<tr>
															<td width="20%">&nbsp;</td>
															<td width="30%">&nbsp;</td>
															<td width="30%" align="right"><h3>BRINDE</h3></td>
														</tr>
													</table>';
								}
								
								
								
						}
						
								//<a href="'.$pagina->localhost.'Produtos/Ver/'.$item->produtos->id.'" class="botao grey float-left">Visualizar</a>
								
							$pesoTotalR = $pesoTotalSemTDCR;
							$pesoTotalV = $pesoTotalSemTDCV;
						
								$ret .= '
								<table border="0">';
								
								
								if(!$cadastro->conectado()){
								$ret .= '
								<tr>
									<td align="right" bgcolor="#efefef">
									<h4>Subtotal: R$'.number_format( $total, 2, ',', '.' ).'</h4>
									<input type="hidden" id="subTotal" value="'.$total.'" />
									</td>
								</tr>';
								}
								
								$ret .= '
								<tr>
									<td align="right">';
								
								if(!$freteGratis){
								}else{
												$ret .= '
									</td>
								</tr>
								<tr>
									<td align="right"><strong>Frete grátis nas condições acima.</strong>';
								}
											$ret .= '
										</td>
									</tr>
									<tr>
										<td align="right" bgcolor="#efefef">
												<h3>Subtotal: R$'.(number_format( $total, 2, ',', '.' )).'</h3>';
												$ret .= '<input type="hidden" name="total" id="subtotal" value="'.number_format($total,2,'.','').'" />';
												$ret .= '<input type="hidden" name="pesoTotal" id="pesoTotal" value="'.$pesoTotalR.'@'.$pesoTotalV.'" />';
												$ret .= '<input type="hidden" name="dimensao" id="dimensao" value="'.$dimensao.'" />';
								if($cadastro->conectado()){
									if(isset($_COOKIE['cupomID'])){
										$cupom = new objetoDb('cupons',$_COOKIE['cupomID']);
												$ret .= '
											</td>
										</tr>
										<tr>
											<td align="right" bgcolor="#efefef">' ;
											
											 //if(compara_data($cupom->validade,date("Y-m-d")) > 0){
												 if($cupom->limite < $pedido->valor){
													$ret .= '
														<h3>- Cupon de desconto: R$ <span id="totalGeral">'.(number_format( $cupom->valor, 2, ',', '.' )).'</span></h3>
														Código: <strong>'.$cupom->codigo.'</strong>';
													$total = (float)$total - $cupom->valor;
												 }else{
													$ret .= '
														<h3>Cupon de desconto: <span id="totalGeral">'."O cupom inserido é válido apenas para pedidos acima de R$".number_format($cupom->limite,2,",","").'</span></h3>';
												}
											  //}else{
													//$ret .= '<h3>Cupon de desconto: <span id="totalGeral">O cupom não é mais válido, o prazo para recuperação expirou</span></h3>';
											  //}
											$ret .= '
										</td>
									</tr>
									<tr>
										<td align="right" bgcolor="#efefef">
												<h3>Subtotal: R$ <span id="totalGeral">'.(number_format( $total, 2, ',', '.' )).'</span></h3>';
												$ret .= '<input type="hidden" name="total" id="subtotal" value="'.number_format($total,2,'.','').'" />';
												$ret .= '<input type="hidden" name="pesoTotal" id="pesoTotal" value="'.$pesoTotalR.'@'.$pesoTotalV.'" />';
									}
								}
								$ret .= '
									</td>
								</tr>
								</table>';
								
							$ret .= $ret2;
							
						$ret .= '
						</table>';
						
						
							if($pedido->idcupons > 1)
								$totalDesconto = $totalDesconto - $pedido->cupons->valor;
					
						foreach($pedido->itens as $item){
							$precoItem = $item->quantidade * ( (float)$item->produtos->preco_promocional > 0 ? (float)$item->produtos->preco_promocional : (float)$item->produtos->preco_venda);
							
							$descricao .= "<p>Produto: ";
							$descricao .= $item->produtos->codigo.' <strong>'.$item->produtos->produto.'</strong><br>';
							$descricao .= "Quantidade: <strong>".$item->quantidade."</strong><br>";
							$descricao .= 'Subtotal: <strong>R$'.number_format($precoItem,2,',','.').'</strong>';
							
								$descricao .= '<br />Prazo de entrega para este ítem: <strong>'.$$item->produtos->prazo_entrega.' dias</strong>';
								if($item->presente)
									$descricao .= '<br /> Embrulhado para presente';
								if($item->clientes->id > 0)
									$descricao .= '<br />Casal: '.$item->clientes->nome;
							$descricao .= "</p>";
						 }
						$_POST['idcadastro'] = $cadastro->id;
						$_POST['descricao'] = $descricao;
						$pesoComposto = $_POST['peso'] = $pesoTotalR.'@'.$pesoTotalV;
						$pesoComposto = max($pesoTotalR,$pesoTotalV);
						$_POST['valor'] = number_format( $total, 2, ',', '.' );
						$_POST['data'] = date("d/m/Y H:I:s");
						$_POST['serialized'] = serialize($_SERVER);
						$db->editar('pedidos',$pedido->id);	
						$pedido = new objetoDb('pedidos',$pedido->id,true);

						
					}else{
						$ret = '<h3>Nenhum item foi adicionado ao seu carrinho de compras</h3>';
					}
					
			return $ret;
}
function segueCarrinho(){
}
function processarCarrinho(){
	global $pagina,$cadastro,$db;
					if($_COOKIE['pedidoID']){
						$total = 0;
						$db->query("select * from itens where idpedidos = '".$_COOKIE['pedidoID']."'");
						while($res = $db->fetch()){
								$item = new objetoDb('itens',$res['iditens']);
											$obj = unserialize($res['transporte']);
											$_id =		$item->produtos->id;
											$_cliente =	$item->clientes->id;
											$_qtd = 	$item->quantidade;
											
											
								//vê se é DC
								$_DC = false;
								foreach($item->produtos->categorias as $c){
									if($c->id == 33)
										$_DC = true;
									$_cat[] = $c->categoria;
								}
								
								
								if($item->clientes->id < 1){//se não é presente, soma o peso
									if(!$_DC){
										$peso = max($item->produtos->peso *1000,300)*$_qtd;
										$pesoTotalSemTDC += $peso;
									}
									$peso = max($item->produtos->peso *1000,300)*$_qtd;
									$pesoTotalComTDC += $peso;
								}
								
								
								if($item->produtos->preco_promocional > 0){//se é promocional
									$totalItem	= (float)$item->produtos->preco_promocional * $_qtd;
									$preco = $item->produtos->preco_promocional;
								}else{
									$totalItem	= (float)$item->produtos->preco_venda * $_qtd;
									$preco= $item->produtos->preco_venda;
								}
								$total += (float)$totalItem;
								
								
								$r[] = array($item->pedidos->id,$item->produtos->codigo,$item->produtos->produto,implode(', ',$_cat),number_format($preco,2,'.',''),$item->quantidade);
							
						}
						$ret['itens'] = $r;
								
									if((float)$total > $pagina->configs['limite_frete_gratis'])
										$pesoTotal = $pesoTotalSemTDC;
									else
										$pesoTotal = $pesoTotalComTDC;
									
									$resto = $pesoTotal % 30000;
									$pacotes = intval($pesoTotal / 30000);
									
										if( $pesoTotal /30000 > 1){
											$pacotes2 = $pacotes + 1;
										}else{
											$pacotes2 = 1;
										}
								
								
										if( $pacotes > 1){
											$valorFrete = (calculaFrete($pagina->configs['cep'], $cadastro->cep , 30) * $pacotes) + calculaFrete($pagina->configs['cep'], $cadastro->cep , $resto/1000);
											$pacotes++;
										}else{
											$pacotes = 1;
											$valorFrete = calculaFrete($pagina->configs['cep'], $cadastro->cep , $pesoTotal/1000 );
										}
										;
											
							   $ret['trans'] = array(
								  $item->pedidos->id,           // order ID - required
								  'Mesacor'.($estiloDC ? ' TDC' : ''), // affiliation or store name
								  number_format($total,2,'.',''),          // total - required
								  number_format(0,2,'.',''),           // tax
								  number_format($valorFrete,2,'.',''),          // shipping
								  $cadastro->cidades->cidade,       // city
								  $cadastro->estados->nome,     // state or province
								  'BRAZIL'             // country
							   );
						
					}else{
						$ret = 'Nenhum item foi adicionado ao seu carrinho de compras';
					}
					
			return $ret;
}


									function VerificaErro($vmPost, $vmResposta)
									{
										$error_msg = null;
										
										try 
										{
											if(stripos($vmResposta, "SSL certificate problem") !== false)
											{
												throw new Exception("CERTIFICADO INVÁLIDO - O certificado da transação não foi aprovado", "099");
											}
											
											$objResposta = simplexml_load_string($vmResposta, null, LIBXML_NOERROR);
											if($objResposta == null)
											{
												throw new Exception("HTTP READ TIMEOUT - o Limite de Tempo da transação foi estourado", "099");
											}
										}
										catch (Exception $ex)
										{
											$error_msg = "     Código do erro: " . $ex->getCode() . "\n";
											$error_msg .= "     Mensagem: " . $ex->getMessage() . "\n";
											
											// Gera página HTML
											echo '<span style="color:red;, font-weight:bold;">Ocorreu um erro em sua transação!</span>' . '<br />';
											echo '<span style="font-weight:bold;">Detalhes do erro:</span>' . '<br />';
											echo '<pre>' . $error_msg . '<br /><br />';
											//echo "     XML de envio: " . "<br />" . htmlentities($vmPost);
											echo '</pre><p>';
											$error_msg .= "     XML de envio: " . "\n" . $vmPost;
											
											return true;
										}
										
										if($objResposta->getName() == "erro")
										{
											$error_msg = "     Código do erro: " . $objResposta->codigo . "\n";
											$error_msg .= "     Mensagem: " . utf8_decode($objResposta->mensagem) . "\n";
											// Gera página HTML
											echo '<span style="color:red;, font-weight:bold;">Ocorreu um erro em sua transação!</span>' . '<br />';
											echo '<span style="font-weight:bold;">Detalhes do erro:</span>' . '<br />';
											echo '<pre>' . $error_msg . '<br /><br />';
											//echo "     XML de envio: " . "<br />" . htmlentities($vmPost);
											echo '</pre>';
											$error_msg .= "     XML de envio: " . "\n" . $vmPost;
										}
									}
									function httprequest($paEndereco, $paPost){
									
										$sessao_curl = curl_init();
										curl_setopt($sessao_curl, CURLOPT_URL, $paEndereco);
										
										
										curl_setopt($sessao_curl, CURLOPT_FAILONERROR, true);
									
										//  CURLOPT_SSL_VERIFYPEER
										//  verifica a validade do certificado
										//curl_setopt($sessao_curl, CURLOPT_SSL_VERIFYPEER, true);
										//  CURLOPPT_SSL_VERIFYHOST
										//  verifica se a identidade do servidor bate com aquela informada no certificado
										//curl_setopt($sessao_curl, CURLOPT_SSL_VERIFYHOST, 2);
									
										//  CURLOPT_SSL_CAINFO
										//  informa a localização do certificado para verificação com o peer
										//curl_setopt($sessao_curl, CURLOPT_CAINFO, $_SERVER['DOCUMENT_ROOT'] .
										//		"ssl/VeriSignClass3PublicPrimaryCertificationAuthority-G5.crt");
										//curl_setopt($sessao_curl, CURLOPT_SSLVERSION, 3);
										
									
										//  CURLOPT_CONNECTTIMEOUT
										//  o tempo em segundos de espera para obter uma conexão
										curl_setopt($sessao_curl, CURLOPT_CONNECTTIMEOUT, 10);
									
										//  CURLOPT_TIMEOUT
										//  o tempo máximo em segundos de espera para a execução da requisição (curl_exec)
										curl_setopt($sessao_curl, CURLOPT_TIMEOUT, 40);
									
										//  CURLOPT_RETURNTRANSFER
										//  TRUE para curl_exec retornar uma string de resultado em caso de sucesso, ao
										//  invés de imprimir o resultado na tela. Retorna FALSE se há problemas na requisição
										curl_setopt($sessao_curl, CURLOPT_RETURNTRANSFER, true);
									
										curl_setopt($sessao_curl, CURLOPT_POST, true);
										
										curl_setopt($sessao_curl, CURLOPT_POSTFIELDS, $paPost );
									
										$resultado = curl_exec($sessao_curl);
										
										
										curl_close($sessao_curl);
									
										if ($resultado)
										{
											return $resultado;
										}
										else
										{
											return curl_error($sessao_curl);
										}
									}
																					
																					
function retorno_automatico ( $VendedorEmail, $TransacaoID, 
  $Referencia, $TipoFrete, $ValorFrete, $Anotacao, $DataTransacao,
  $TipoPagamento, $StatusTransacao, $CliNome, $CliEmail, 
  $CliEndereco, $CliNumero, $CliComplemento, $CliBairro, $CliCidade,
  $CliEstado, $CliCEP, $CliTelefone, $produtos, $NumItens) {
	
global $db,$pagina;

  // AQUI VOCÊ TEM OS DADOS RECEBIDOS DO PAGSEGURO, JÁ VERIFICADOS.
  // CONFIRA A LISTA DE PRODUTOS E O VALOR COM O QUE VOCÊ TEM NO
  // BANCO DE DADOS E, SE ESTIVER TUDO CERTO, ATUALIZE O STATUS
  // DO PEDIDO.
  
				
				
				if($StatusTransacao == 'Aguardando Pagto'){
								$sql = "update pedidos set
											idestagios = '2',
											transacaoid = '".$TransacaoID."',
											tipo_frete = '".$TipoFrete."',
											valor_frete = '".$ValorFrete."',
											extras = '".$Referencia."',
											anotacao = '".$Anotacao."',
											data_transacao = '".$DataTransacao."',
											tipo_pagamento = '".$TipoPagamento."',
											status_transacao = '".$StatusTransacao."',
											parcelas = '".$_POST['Parcelas']."',
											where idpedidos = '".str_replace("pedido_","",$Referencia)."'";
								$db->query($sql);
								
								
				}else{
								$pedido = new objetoDb('pedidos',str_replace("pedido_","",$Referencia));
								$sql = "update pedidos set
											idestagios = '2',
											transacaoid = '".$TransacaoID."',
											tipo_frete = '".$TipoFrete."',
											valor_frete = '".$ValorFrete."',
											referencia = '".$Referencia."',
											anotacao = '".$Anotacao."',
											data_transacao = '".$DataTransacao."',
											tipo_pagamento = '".$TipoPagamento."',
											status_transacao = '".$StatusTransacao."',
											parcelas = '".$_POST['Parcelas']."',
											where idpedidos = '".str_replace("pedido_","",$Referencia)."'";
								$db->query($sql);
								if($StatusTransacao == 'Aprovado'){
									foreach($pedido->itens as $item){
										$db->query("update presentes set ganhado = ganhado + ".$item->quantidade." where idclientes = '".$item->clientes->id."' and idprodutos = '".$item->produtos->id."' ");
									}
									$db->query("update pedidos set idestagios = 3 where idpedidos = '".str_replace("pedido_","",$Referencia)."'");
									
								}
								
				}
				
				n2_mail("noxsulivan@gmail.com","acesso servidor pagseguro ".str_pad($pedido->id,6,0,STR_PAD_LEFT),print_r(func_get_args(),true).print_r($_REQUEST,true).print_r($_SERVER,true),$pedido->cadastros->email);

}
function GerarTid ($shopid,$pagamento) {

    if(strlen($shopid) != 10) {
        echo "Tamanho do shopid deve ser 10 dígitos";
        exit;
	}
	
	if(is_numeric($shopid) != 1) {
        echo "Shopid deve ser numérico";
        exit;
	}

    if(strlen($pagamento) != 4) {
        echo "Tamanho do código de pagamento deve ser 4 dígitos.";
        exit;
	}

    //Número da Maquineta
    $shopid_formatado = substr($shopid, 4, 5);

    //Hora Minuto Segundo e Décimo de Segundo
    $hhmmssd = date("His").substr(sprintf("%0.1f",microtime()),-1);

    //Obter Data Juliana
    $datajuliana = sprintf("%03d",(date("z")+1));

	//Último dígito do ano
    $dig_ano = substr(date("y"), 1, 1);


    return $shopid_formatado.$dig_ano.$datajuliana.$hhmmssd.$pagamento;
  }
  
  
function getXMLCielo( $identificacao, $modulo, $operacao, $ambiente, $valor, $pedido, $descricao, $bandeira, $forma_pagamento, $parcelas, $autorizar, $capturar, $tid = NULL){

    $campo_livre = '';


    // Monta a variável com os dados para postagem
    $request = 'identificacao=' . $identificacao;
    $request .= '&modulo=' . $modulo;
    $request .= '&operacao=' . $operacao;
    $request .= '&ambiente=' . $ambiente;

    $request .= '&bin_cartao=' . $bin_cartao;

    $request .= '&idioma=' . $idioma;
    $request .= '&valor=' . $valor;
    $request .= '&pedido=' . $pedido;
    $request .= '&descricao=' . $descricao;

    $request .= '&bandeira=' . $bandeira;
    $request .= '&forma_pagamento=' . $forma_pagamento;
    $request .= '&parcelas=' . $parcelas;
    $request .= '&autorizar=' . $autorizar;
    $request .= '&capturar=' . $capturar;

    $request .= '&campo_livre=' . $campo_livre;
	
    $request .= '&tid=' . $tid;

    // Faz a postagem para a Cielo
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://comercio.locaweb.com.br/comercio.comp');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
	
	//pre($response);
	
    return $response;
}

function getCielo($identificacao, $modulo, $operacao, $ambiente, $valor, $pedido, $descricao, $bandeira, $forma_pagamento, $parcelas, $autorizar, $capturar){
	$XMLtransacao = getXMLCielo($identificacao, $modulo, $operacao, $ambiente, $valor, $pedido, $descricao, $bandeira, $forma_pagamento, $parcelas, $autorizar, $capturar);
	
	// Carrega o XML
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
		$retorno->tid = $nodeTID->nodeValue;
	
		$nodeDadosPedido = $nodeTransacao->getElementsByTagName('dados-pedido')->item(0);
		if ($nodeDadosPedido != '') {
			$nodeNumero = $nodeDadosPedido->getElementsByTagName('numero')->item(0);
			$retorno->pedido = $nodeNumero->nodeValue;
	
			$nodeValor = $nodeDadosPedido->getElementsByTagName('valor')->item(0);
			$retorno->valor = $nodeValor->nodeValue;
	
			$nodeMoeda = $nodeDadosPedido->getElementsByTagName('moeda')->item(0);
			$retorno->moeda = $nodeMoeda->nodeValue;
	
			$nodeDataHora = $nodeDadosPedido->getElementsByTagName('data-hora')->item(0);
			$retorno->data_hora = $nodeDataHora->nodeValue;
	
			$nodeDescricao = $nodeDadosPedido->getElementsByTagName('descricao')->item(0);
			$retorno->descricao = $nodeDescricao->nodeValue;
	
			$nodeIdioma = $nodeDadosPedido->getElementsByTagName('idioma')->item(0);
			$retorno->idioma = $nodeIdioma->nodeValue;
		}
	
		$nodeFormaPagamento = $nodeTransacao->getElementsByTagName('forma-pagamento')->item(0);
		if ($nodeFormaPagamento != '') {
			$nodeBandeira = $nodeFormaPagamento->getElementsByTagName('bandeira')->item(0);
			$retorno->bandeira = $nodeBandeira->nodeValue;
	
			$nodeProduto = $nodeFormaPagamento->getElementsByTagName('produto')->item(0);
			$retorno->produto = $nodeProduto->nodeValue;
	
			$nodeParcelas = $nodeFormaPagamento->getElementsByTagName('parcelas')->item(0);
			$retorno->parcelas = $nodeParcelas->nodeValue;
		}
	
		$nodeStatus = $nodeTransacao->getElementsByTagName('status')->item(0);
		$retorno->status = $nodeStatus->nodeValue;
	
		$nodeURLAutenticacao = $nodeTransacao->getElementsByTagName('url-autenticacao')->item(0);
		$retorno->url_autenticacao = $nodeURLAutenticacao->nodeValue;
	}
	
	return $retorno;
}

?>
