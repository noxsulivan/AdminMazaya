<?
$timeIni = microtime(true);
include('../ini.php');
$pagina = new layout($_SERVER['QUERY_STRING']);
$corpo = '';
$acao = $pagina->acao ? $pagina->acao : $_POST['acao'];
$ret['acao'] = $acao;
if(!preg_match("/TRAMONTINA|TDC/i",$acao)){
	
	
	if(isset($_COOKIE['Mesacor_cadastroID'])){
		$cadastro = new cadastro($_COOKIE['Mesacor_cadastroID']);
	}else{
		$cadastro = new cadastro();
	}
	
	if(!$_SESSION["cliente"]){
		$cliente = new cliente();
	}else{
		$cliente = $_SESSION["cliente"];
	}
	//if($cadastro->conectado()){
		//$_SESSION['convidado'] = new objetoDb('clientes',$cadastro->clientes->id);
	//}
	if($_SESSION["convidado"]){
		$convidado = $_SESSION["convidado"];
	}
	
			if(isset($_COOKIE['pedidoID'])){
				
				$pedido = new objetoDb('pedidos',$_COOKIE['pedidoID']);
				
				if($pedido->estagios->id > 1){
					setcookie("pedidoID",$_COOKIE['idpedidos'],time()-(60*60*24*30),'/');
				}
			}
	
				
	$_t = array();
	function limpaPost($v,$k){
		global $_t;
		$_t[str_replace("amp;","",$k)] = is_array($v) ? $v : utf8_decode($v);
	}
	$_POST = array_walk($_POST,"limpaPost");
	$_POST = $_t;
}
$echo = NULL;

	$encodings = array();
	$supportsGzip = false;
	$enc = "";
	$cacheKey = "";
	if (isset($_SERVER['HTTP_ACCEPT_ENCODING']))
		$encodings = explode(',', strtolower(preg_replace("/\s+/", "", $_SERVER['HTTP_ACCEPT_ENCODING'])));

	if ((in_array('gzip', $encodings) || in_array('x-gzip', $encodings) || isset($_SERVER['---------------'])) && function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')) {
		$enc = in_array('x-gzip', $encodings) ? "x-gzip" : "gzip";
		$supportsGzip = true;
	}

ob_start();


		//mail("noxsulivan@gmail.com","Ajax TUDO ".microtime(),pre($_REQUEST,true).pre($_SERVER,true));
switch($acao){
	case "Buscape":	
	case "Buscape.xml":	
			header('Content-type: text/xml');
			$echo .= '<?xml version="1.0" encoding="iso-8859-1"?>';
						$echo .= '<produtos>';
						$db->query(" select produtos.idprodutos from produtos where buscape = 'sim'");
						while($res = $db->fetch()){
							$produto = new objetoDb('produtos',$res['idprodutos']);
							$echo .= '
							<produto>';
							$echo .= '<descricao>'.normaliza(str_replace(" Tramontina","",$produto->produto)).' - Tramontina - '.$produto->codigo.'</descricao>';
							$echo .= '<preco>'.( (float) $produto->preco_promocional > 0 ? $produto->preco_promocional : $produto->preco_venda).'</preco>';
							$echo .= '<id_produto>'.$produto->codigo.'</id_produto>';
							$echo .= '<link_prod>https://www.mesacor.com.br/Produtos/Ver/'.$produto->id.'/?utm_source=Buscape&amp;utm_medium=Busca&amp;utm_term='.$produto->codigo.'&amp;utm_campaign=Buscape</link_prod>';
							$echo .= '<imagem>https://www.mesacor.com.br/img/'.$produto->fotos[0]['id'].'/200/200/</imagem>';
							$echo .= '<categ>'.$produto->categ_buscape.'</categ>';
						  //if($preco/$i > $pagina->configs['parcela_minina'] and $i <= 5){
							  
							if((float)$produto->preco_promocional){
								$preco = $produto->preco_promocional;
							}else{
								$preco = $produto->preco_venda;
							}
							for( $i = 1 ; $i <=6; $i++) {
								if($preco/$i > $pagina->configs['parcela_minina']){
                              		$parc = $i.'x de R$'.number_format($preco / $i ,2,",",".");
								}
							}
                          
                          
							$echo .= '<parcel>'.$parc.'</parcel>';
							$echo .= '</produto>';
						}
						$echo .= '</produtos>';
						$iso = true;
	break;
	case "TDC":	
	case "TRAMONTINA":
	case "TRAMONTINA.xml":
			//header('Content-type: text/xml; charset=iso-8859-1');
			//$echo = file_get_contents("../TRAMONTINA.xml");
			set_time_limit(0);
			$echo .= '<?xml version="1.0" encoding="iso-8859-1"?>';
						$db->query("
								   SELECT idprodutos FROM produtos  order by idprodutos desc");
						$echo .= '<produtos q="'.$db->rows.'">';
						
						$resourceListagem = $db->resourceAtual;
						while($res = $db->fetch()){
							$produto = new objetoDb('produtos',$res['idprodutos']);
								$echo .= '<PRODUTO>';
								$echo .= '<NOME_PRODUTO>'.substr(str_replace("&quot;","\"",$produto->produto),0,15).'</NOME_PRODUTO>';
								$echo .= '<URL_DETALHES>http://www.mesacor.com.br/T/'.$produto->id.'</URL_DETALHES>';
								//$echo .= '<URL_DETALHES>https://www.mesacor.com.br/Produtos/Ver/'.$produto->id.'/?utm_source=TramontinaGeral&amp;utm_campaign=Tramontina&amp;utm_medium=Site</URL_DETALHES>';
								$echo .= '<PRECO>'.$produto->preco_venda.'</PRECO>';
								//$echo .= '<PRECO_PROMOCAO>'.( (float) $produto->preco_promocional > 0 ? $produto->preco_promocional : '').'</PRECO_PROMOCAO>';
								//$echo .= '<FORMA_DE_PAGAMENTO></FORMA_DE_PAGAMENTO>';
								$echo .= '<CODIGO_PRODUTO>'.$produto->codigo.'</CODIGO_PRODUTO>';
								$echo .= '</PRODUTO>';
							$db->resource($resourceListagem);

						}
						$echo .= '</produtos>';
			file_put_contents("../xml/TRAMONTINA.xml",$echo);
			file_put_contents("../xml/TDC.xml",$echo);		
						$iso = true;
			$timeEnd = microtime(true);
	break;
	case "fotoGrd":
		list($_id, $_foto) = explode('@',$pagina->id);
		$produto = new objetoDb('produtos',$_id);
		$foto = new objetoDb('fotos',$_foto);
			if($foto->height == 0){
				$img_db = @imagecreatefromstring( $foto->arquivo );
				$foto->width	=	imagesx( $img_db );
				$foto->height	=	imagesy( $img_db );
			}
			$w = intval(($foto->width * 300) / $foto->height);
			if($foto->width > $foto->height and $w > 450){
				$h = intval(($foto->height * 450) / $foto->width);
				$t = intval((300-$h)/2);
			}else{
				$h = 300;
				$t = 0;
			}
			
        $echo = '<a href="'.$pagina->localhost.'img/'.$_foto.'/800/600"';
        $echo .= ' title="'.htmlentities($produto->produto).'" alt="'.$produto->produto.'"';
        $echo .= ' id="fotoGrd'.$_foto.'" gCat="Imagem" gAction="Mais informações" gLabel="'.$produto->codigo.'" gValue="'.$_foto.'"';
        $echo .= ' onclick="return expandir(this,{img: true})">';
        $echo .= '<img style="margin-top:'.$t.'px" src="'.$pagina->localhost.'img/'.$_foto.'/450/300/0/0/'.diretorio($_foto.'-'.$produto->referencia).".jpg".'" title="'.htmlentities($produto->referencia).'" alt="'.$produto->id.'" /> </a>';
	break;
	case "slideshow":	

			header('Content-type: text/xml');
			$echo = '<images>';
			$db->query('select * from galerias where ativo = "nao" and idtipos_de_banners = 1 order by ordem asc');
			while($res = $db->fetch()){
				$obj = new objetoDb('galerias',$res['idgalerias']);
					if(count($obj->arquivos)){
						foreach($obj->arquivos as $arquivo){
										$echo .= '<image src="'.$pagina->localhost.'down/'.$arquivo->id.'" name="'.$obj->referencia.'" href="'.$obj->href.'"/>';
						}
					}else{
						foreach($obj->fotos as $foto){
										$echo .= '<image src="'.$pagina->localhost.'img/'.$foto['id'].'/960/200/1/q=100" name="'.$obj->referencia.'" href="'.$obj->href.'"/>';
						}
					}
			}
			$echo .= '</images>';
	break;
	case "loginCliente":	
		if($cliente->conectado()){
					$ret['status'] = 'ok';
					$ret['mensagem'] = '<p>Você está conectado!</p><strong>Para visualizar a lista de presentes <a href="'.$pagina->localhost.'Lista-de-Casamento/Presentes">clique aqui</a></strong>';
					$ret['script'] = 'window.location="'.$pagina->localhost.'Lista-de-Casamento"';
		}else{
					$ret['status'] = 'erro';
					$ret['mensagem'] = 'Usuário não cadastrado ou senha incorreta, tente novamente'.$cliente->erro;
		}
	break;
	case "salvarDadosCliente":
	
								$_POST['idcidades'] = $_POST['cidade'];
								$_POST['idestados'] = $_POST['estado'];
								$_POST['idbairros']= $_POST['bairro'];
				
				if($cliente->conectado()){
					$db->editar("clientes",$cliente->id);
					if($db->erro){
						$ret['status'] = 'erro';
						$ret['mensagem'] = $db->erro;
					}else{
						$cliente->atualizarSession();
						$ret['status'] = 'ok';
						$ret['mensagem'] = "<h2>Meus dados</h2><h3>Seu cadastro foi alterado com sucesso</h3>";
					}
				}else{
					
					
					$db->query('select idclientes from clientes where email = "'.$_REQUEST['email'].'"');
					if($db->rows){
							$ret['status'] = 'erro';
							$ret['mensagem'] = utf8_decode('O e-mail '.$_REQUEST['email'].' já está cadastrado em nosso sistema');
							break;
					}
//					$db->query('select idcadastros from cadastros where cpf = "'.$_REQUEST['cpf'].'"');
//					if($db->rows){
//							$ret['status'] = 'erro';
//							$ret['mensagem'] = 'O CPF '.$_REQUEST['cpf'].' já está cadastrado em nosso sistema';
//							break;
//					}

				list($nome_noiva) = explode(" ",normaliza($_POST['nome_noiva']));
				$_POST['sobrenome_noiva'] = trim(preg_replace("/".$nome_noiva."(.)?/","${1}",normaliza($_POST['nome_noiva'])));
				$_POST['nome_noiva'] = $nome_noiva;
				$_POST['nome_completo_noiva'] = $_POST['nome_noiva'].' '.$_POST['sobrenome_noiva'];
				
				list($nome_noivo) = explode(" ",normaliza($_POST['nome_noivo']));
				$_POST['sobrenome_noivo'] = trim(preg_replace("/".$nome_noivo."(.)?/","${1}",normaliza($_POST['nome_noivo'])));
				$_POST['nome_noivo'] = $nome_noivo;
				$_POST['nome_completo_noivo'] = $_POST['nome_noivo'].' '.$_POST['sobrenome_noivo'];
				
				
					$__utmz = explode("utmcsr",$_REQUEST['__utmz']);
					$__utmz = parse_url(preg_replace("/\|/","&","utmcsr".$__utmz[1]));
					parse_str("&".$__utmz['path'],$__utmz);
                    
					$_POST['origem'] = $__utmz['utmcsr'].'|'.$__utmz['utmcct'];
				$_POST['endereco'] = trim(normaliza($_POST['endereco']));
				
				
					$_POST['data_cadastro'] = date("d/m/Y");
					$chave = $_POST['chave'] = md5($_REQUEST['email'].date("d/m/Y h:i:s"));
					$_POST['senha'] = md5($_POST['senha']);
					
					if($_POST['nome_fantasia'] )$_POST['nome'] = $_POST['nome_fantasia'];
					if($_POST['razao_social'] )$_POST['sobrenome'] = $_POST['razao_social'];
					
					$_POST['autorizado'] = 0;
					
					$__utmz = preg_replace("/([0-9]?\.?)+(.)?/i","\\2",$_REQUEST['__utmz']);
					$_POST['origem'] = $__utmz;
					
					$db->inserir("clientes");
					
					if($db->erro){ 
						$ret['status'] = 'erro';
						$ret['mensagem'] = "<h2>Ocorreu um erro</h2>
								<p>Não foi possível inserir em nosso sistema o seu cadastro de cliente.</p>
								<p>Por favor, volte à tela anterior e verifique se todos os seus dados foram digitados corretamente e todos os campos obrigatórios foram informados.</p>";
								//echo $db->erro;
					}else{
								$corpo = utf8_decode('
								<h2>Bem vindos '.$_POST['nome_noiva'].' e '.$_POST['nome_noivo'].'</h2>
								<p>&nbsp;</p>
								<p>O e-mail <strong>'.$_REQUEST['email'].'</strong> foi cadastrado em nosso sistema, aproveite os recursos que disponibilizamos para fazer da sua lista um sucesso<p>
								<p>Montou sua lista na loja?</p>
								<p>Se você montou sua lista de presentes diretamente na loja e não criou sua senha na ocasião, entre em contato e solicite agora mesmo.</p>
								<p>Crie sua lista de presentes</p>
								<p>Navegue pelo site escolhendo os presentes que gostaria de ganhar, depois, divulgue para seus convidados. É bastante simples em alguns minutos fica pronto.</p>
								<p>A Equipe '.$pagina->configs[titulo_site].', agradece a sua participação</p>
								');
								
								$ret['status'] = 'ok';
								$ret['mensagem'] = '<h2>Seu cadastro foi criado com sucesso</h2>';
								
								if(!ereg('nox',$_SERVER['HTTP_HOST'])){
									if(mailClass($_REQUEST['email'],"Cadastro na Lista de Casamento ".$pagina->localhost,$corpo,$pagina->configs['email_suporte'],"Mesacor - Tramontina"))
										$ret['mensagem'] .= utf8_decode('Você receberá uma mensagem de confirmação e ativação no e-mail <strong>'.$_POST['email'].'</strong>. Siga as instruções contidas nele para acessar o conteúdo da área restrita.');
										if($pedido->id) $ret['mensagem'] .= '<a href="'.$pagina->localhost.'Cliente/Carrinho" class="awesome orange float-right"> Voltar para o carrinho &raquo;</a>';
									else
										$ret['mensagem'] .= utf8_decode('Não foi possível enviar neste momento o e-mail de ativação de seu cadastro, por favor entre em contato com a administração do site e solicite que seu cadastro seja validado.');
	
								}
								
	
								$corpo = '<h2>Cadastro na Lista de Casamento</h2><p>&nbsp;</p>';
								$cidade = new objetoDb('cidades',$_POST['cidade']);
								$estado = new objetoDb('estados',$_POST['estado']);
								$bairro = new objetoDb('bairros',$_POST['bairro']);
								
								
								$_POST['bairro'] = $bairro->bairro;
								$_POST['cidade'] = $cidade->cidade;
								$_POST['estado'] = $estado->estado;
								
								while(list($k,$v) = each($_POST)){
									switch($k){
										case "acao":
										case "senha":
										case "confirmacao":
										case "chave":
										case "autorizado":
										case "idcidades":
										case "idestados":
										case "idbairros":
										break;
										default:
											$corpo .= str_replace("_"," ",ucfirst($k)).": <strong>".$v."</strong><br>";
									}
								}
								$_SESSION[formulario] = $_POST;
											
								
								$corpo .= utf8_decode('<h3>Informações de segurança</h3>
								<p>
								Este e-mail foi enviado através do site '.$pagina->localhost.'</strong><br>
								Página: <strong>"'.$canal->canal.'"</strong><br>
								Data e hora: <strong>'.date("d/m/Y h:i:s").'</strong><br>
								IP: <strong>'.$_SERVER['REMOTE_ADDR'].'</strong>
								</p>');
								mailClass($pagina->configs["email_suporte"],"Cadastro na Lista de Casamento",$corpo,$_REQUEST['email'],$_REQUEST['nome_completo']);
							}
				}
	break;
	case "consultaEmail":
					$db->query('select idcadastros from cadastros where email = "'.$_REQUEST['email'].'"');
					if($db->rows){
							$echo = false;
							break;
					}else{
							$echo = true;
					}
	break;
	case "salvarDadosCadastro":
			$ret = salvarDadosCadastro();
	break;
	case "enviarConvites":
				foreach($_POST['par'] as $par){
					if($par['email']){
						
						$_POST['data'] = date("d/m/Y h:i:s");
						$_POST['nome'] = $par['nome'];
						$_POST['email'] = $par['email'];
						$_POST['chave'] = md5($par['email'].date("d/m/Y h:i:s"));
						$_POST['idclientes'] = $cliente->id;
						$db->inserir('convites');
						
						$corpo = utf8_decode('<h2>Olá, '.normaliza($par['nome']).'</h2>
						<p>&nbsp;</p>
						<p>Os noivos <strong>'.$cliente->nome_noiva.' e '.$cliente->nome_noivo.'</strong> criaram uma lista de presentes que serão muito bem vindos nessa próxima fase de suas vidas, e que serão muito mais especiais se tiverem associados a lembrança do seu nome.</p>
						<p>Acesse a lista disponível no site: </p>
						<p><a href="'.$pagina->localhost.'Lista-de-Casamento/ConfirmaConvite/'.$_POST['chave'].'">'.$pagina->localhost.'Lista-de-Casamento/ConfirmaConvite/'.$_POST['chave'].'</a></p>
						<p>Compre online, é fácil, seguro e cômodo e os presentes serão entregues diretamente aos noivos junto com sua dedicatória. Aproveite a oportunidade.</p>
						<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua participação</p>');
						
						mailClass(trim($par['email']),utf8_decode("Nossa lista de casamento está pronta"),$corpo,$cliente->email,$cliente->nome);
					}
					
				}
				$ret['status'] =  "ok";
				$ret['mensagem'] =  "<h3>A mensagem foi enviada com sucesso.</h3>";
	break;
	case "enviarSenha":
						
				$id = $db->fetch("select idclientes from clientes where email = '".trim($_REQUEST['email'])."'",'idclientes');
				if($db->rows){
					$cliente = new objetoDb('clientes',$id);
					$_POST['chave'] = md5($cliente->email.date("d/m/Y h:i:s"));
					$db->editar('clientes',$cliente->id);
					
					$corpo = utf8_decode('<h2>Olá, '.$cliente->nome_noiva.' & '.$cliente->nome_noivo.'</h2>
					<p>&nbsp;</p><p>Recuperação de senha</p>
					<p>Acesse o seguinte endereço para reconfigurar sua senha: </p>
					<p><a href="'.$pagina->localhost.'Lista-de-Casamento/Chave/'.$_POST['chave'].'">'.$pagina->localhost.'Lista-de-Casamento/Chave/'.$_POST['chave'].'</a></p>
					<p>Compre online, é fácil, seguro e cômodo. Aproveite a oportunidade.</p>
					<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua participação</p>');
					
					mailClass($cliente->email,utf8_decode("Recuperação de senha"),$corpo,$pagina->configs["email_suporte"],$pagina->configs['titulo_site']);
					$ret['status'] =  "ok";
					$ret['mensagem'] =  "<h3>A mensagem foi enviada com sucesso.</h3>";
					$ret['sql'] =  "select idclientes from clientes where email = '".trim($_REQUEST['email'])."'";
					$ret['id'] =  $id;
				}else{
					$ret['status'] =  "ok";
					$ret['mensagem'] =  "<h3>Este e-mail não está cadastrado em nosso sistema</h3>";
				}
						
	break;
	case "enviarSenhaCadastro":
						
				$id = $db->fetch("select idcadastros from cadastros where email = '".trim($_REQUEST['email'])."'",'idcadastros');
				if($db->rows){
					$cadastro = new objetoDb('cadastros',$id);
					$_POST['chave'] = md5($cliente->cadastro.date("d/m/Y h:i:s"));
					$db->editar('cadastros',$cadastro->id);
					
					$corpo = utf8_decode('<h2>Olá, '.$cadastro->nome.'</h2>
					<p>&nbsp;</p><p>Recuperação de senha</p>
					<p>Acesse o seguinte endereço para reconfigurar sua senha: </p>
					<p><a href="'.$pagina->localhost.'Cliente/Chave/'.$_POST['chave'].'">'.$pagina->localhost.'Cliente/Chave/'.$_POST['chave'].'</a></p>
					<p>Compre online, é fácil, seguro e cômodo. Aproveite a oportunidade.</p>
					<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua participação</p>');
					
					mailClass($cadastro->email,utf8_decode("Recuperação de senha"),$corpo,$pagina->configs["email_suporte"],$pagina->configs['titulo_site']);
					$ret['status'] =  "ok";
					$ret['mensagem'] =  "<h3>A mensagem foi enviada com sucesso.</h3>";
					$ret['id'] =  $id;
				}else{
					$ret['status'] =  "ok";
					$ret['mensagem'] =  "<h3>Este e-mail não está cadastrado em nosso sistema</h3>";
				}
						
	break;
	case "registrarConvidado":
		$iso = true;
			//if($_REQUEST['nome'] and $_REQUEST['codigo_acesso']){
			if($_REQUEST['nome_noiva'] or $_REQUEST['nome_noivo']){
				//$sql = 'select idclientes from clientes where idclientes = "'.$_REQUEST['nome'].'" and codigo_acesso = "'.$_REQUEST['codigo_acesso'].'" ';
				$sql = 'select idclientes from clientes where idclientes = "'.($_REQUEST['nome_noiva'] ? $_REQUEST['nome_noiva'] : $_REQUEST['nome_noivo']).'" ';
				$db->query($sql);
				if($db->rows){
					$res = $db->fetch();
					$obj = new objetoDb('clientes',$res['idclientes']);
					$_SESSION['convidado'] = $obj;
					
					$ret['status'] = 'ok';
					
					$_tmp .= '<h2>Área reservada de '.$_SESSION['convidado']->nome.'</h2>';
					$ret['mensagem'] = '<p>Voc&ecirc; est&aacute; conectado!</p><strong>Redirecionando</strong>';
					$ret['script'] = 'window.location="'.$pagina->localhost.'Lista-de-Casamento/Presentes"';
					
				}else{
					$ret['status'] = 'error';
					$ret['mensagem'] = '<p>O código de acesso informado não corresponde ao casal de noivos que você selecionou, tente novamente</p>';
				}
			}else{
					$ret['status'] = 'error';
					$_tmp = '<p>Todos os campos são necessários</p>';
					$ret['mensagem'] = $_tmp;
			}
	break;
	case "registrarConvidadoCha":
		$iso = true;
			//if($_REQUEST['nome'] and $_REQUEST['codigo_acesso']){
			if($_REQUEST['nome_noiva'] or $_REQUEST['nome_noivo']){
				//$sql = 'select idclientes from clientes where idclientes = "'.$_REQUEST['nome'].'" and codigo_acesso = "'.$_REQUEST['codigo_acesso'].'" ';
				$sql = 'select idclientes from clientes where idclientes = "'.($_REQUEST['nome_noiva'] ? $_REQUEST['nome_noiva'] : $_REQUEST['nome_noivo']).'" ';
				$db->query($sql);
				if($db->rows){
					$res = $db->fetch();
					$obj = new objetoDb('clientes',$res['idclientes']);
					$_SESSION['convidado'] = $obj;
					
					$ret['status'] = 'ok';
					
					$_tmp .= '<h2>Área reservada de '.$_SESSION['convidado']->nome.'</h2>';
					$ret['mensagem'] = '<p>Você está conectado!</p><strong>Redirecionando</strong>';
					$ret['script'] = 'window.location="'.$pagina->localhost.'Cha-de-Panela/Presentes"';
					
				}else{
					$ret['status'] = 'error';
					$ret['mensagem'] = '<p>O código de acesso informado não corresponde ao casal de noivos que você selecionou, tente novamente</p>';
				}
			}else{
					$ret['status'] = 'error';
					$_tmp = '<p>Todos os campos são necessários</p>';
					$ret['mensagem'] = $_tmp;
			}
	break;
	case "galeria":
			$canal = new objetoDb('canais',$pagina->id);
			$ret = array();
			if(count($canal->fotos)){
				foreach($canal->fotos as $foto){
					array_push($ret, array("title"=>'',"body"=>$foto['legenda'],"image"=>$pagina->localhost.'img/'.$foto['id'].'/750/300/1',"url"=>$pagina->localhost.'img/'.$foto['id'],"thumb"=>$pagina->localhost.'img/'.$foto['id']."/30/30"));
				}
				//pre($ret);
			}else{
				$ret['erro'] = "nenhuma imagem disponível";
			}
	break;
	case "carregaEndereco":
		$ret = carregaEndereco(str_replace("-","",$pagina->id));
	break;
	case "carregaCEP":	
			$cep = preg_replace("/[^0-9]/","",$pagina->id);
			$sql = "select * from cidades where cep = '".$cep."'";
			$db->query($sql);
			if($db->rows){
				$res = $db->fetch();
				$ret['cidade'] = $res['idcidades'];
				$ret['estado'] = $res['idestados'];
			}else{
				$sql = "select * from logradouros where cep = '".$cep."'";
				$db->query($sql);
				if($db->rows){
					$res = $db->fetch();
					$log = new objetoDb('logradouros',$res['idlogradouros']);
					$ret['logradouro'] = utf8_decode($log->tipo." ".$log->logradouro);
					$ret['estado'] = $log->estados->id;
					$ret['cidade'] = $log->cidades->id;
					$ret['bairro'] = $log->bairros->bairro;
					$ret['status'] = 'ok';
					$ret['sql'] = $sql;
				}else{
					$local = carregaCEP($cep);
					if($local){
						$ret['logradouro'] = $local['logradouro'];
						$uf = new objetoDb("estados",$local['uf']);
						$ret['estado'] = $uf->id;
						$ret['cidade'] = $db->fetch("select idcidades from cidades where cidade like '%".utf8_decode($local['cidade'])."%'",'idcidades');
						$ret['cod'] = "select idcidades from cidades where cidade like '%".utf8_decode($local['cidade'])."%'";
						$ret['bairro'] = $local['bairro'];
						$ret['status'] = 'ok';
					}else{
						$ret['status'] = 'erro';
						$ret['mensagem'] = "<small>CEP n&atilde;o encontrado em nosso banco de dados.</small>";
					}
				}
			}
	break;
	case "carregaCidades":	
			$sql = "select * from cidades where idestados = '".$pagina->id."' order by cidade";
			$echo .= '<option>Selecione uma cidade</option>';
			$db->query($sql);
				while($res = $db->fetch())
					$echo .= '<option value="'.$res['idcidades'].'"'.(($res['idcidades']==$pagina->extra)?' selected':'').'>'.htmlentities($res['cidade']).'</option>';
	break;
	case "carregaBairros":	
			$sql = "select * from bairros where idcidades = '".$pagina->id."' order by bairro";
			$ret .= '<option>Selecione um bairro</option>';
			$db->query($sql);
				while($res = $db->fetch())
					$echo .= '<option value="'.$res['idbairros'].'"'.(($res['idbairros']==$pagina->extra)?' selected':'').'>'.htmlentities($res['bairro']).'</option>';
	break;
	case "consultaFrete":
		consultaFrete($pagina->id,$pagina->extra);
	break;
	case "atualizaFrete":
		$ret['mensagem'] = atualizaFrete();
		$ret['status'] = 'ok';
	break;
	case "mudaFormatoFrete":
		$ret['mensagem'] = $pedido->id;
		$_POST['valor_frete'] = $pagina->id;
		$db->editar('pedidos',$pedido->id);
		$ret['status'] = 'ok';
	break;
	case "atualizaParcelamento":
		$ret['mensagem'] = atualizaParcelamento();
		$ret['status'] = 'ok';
	break;
	case "recuperarCupom":
		if($cupom = recuperarCupom($pagina->id)){
			if($cupom->valor > 0){
				$ret['mensagem'] .= utf8_decode('Cupon de desconto: R$'.(number_format( $_result->valor, 2, ',', '.' )).'<br>Atualizando carrinho..');
			}else{
				$ret['mensagem'] .= utf8_decode('<h3>Cupon de desconto: <span id="totalGeral">'.$_result->percentual.'%</span></h3>');
			}
			$ret['cupom_valor'] = $_result->valor;
			$ret['cupom_percentual'] = $_result->percentual;
			$ret['status'] = 'ok';
		}else{
			$ret['mensagem'] = utf8_decode("O número informado não corresponde a um cupom válido");
			$ret['cupom_valor'] = 0;
			$ret['cupom_percentual'] = 0;
			$ret['status'] = 'erro';
		}
	break;
	case "enviarDedicatoria":
					salvarDedicatoria();	
	break;
	case "adicionarCarrinho":
	
		list($_qtd,$_noiva) = split(',',$pagina->extra);
		adicionarCarrinho($pagina->id,$_qtd,$_noiva);
	break;
	case "adicionarListaPresentes":
		adicionarListaPresentes($pagina->id,$pagina->extra);
		$ret['status'] = 'ok';
			
	break;
	case "adicionarListaChas":
		adicionarListaChas($pagina->id,$pagina->extra);
		$ret['status'] = 'ok';
			
	break;
	case "acrescentarLista":
		$ret['mensagem'] = acrescentarLista($pagina->id,$pagina->extra);
		$ret['status'] = 'ok';
			
	break;
	case "acrescentarListaCha":
		$ret['mensagem'] = acrescentarListaCha($pagina->id,$pagina->extra);
		$ret['status'] = 'ok';
			
	break;
	case "carregaCarrinhoFull":
					$echo = carregaCarrinhoFull();	
	break;
	case "carregaCarrinhoMini":
					$echo = carregaCarrinhoMini();	
	break;
	case "carregaListaMini":
					$echo = carregaListaMini();	
	break;
	case "carregaListaMiniChas":
					$echo = carregaListaMiniChas();	
	break;
	case "atualizarQuantidade":
					atualizarQuantidade($pagina->id,$pagina->extra);
					$ret['status'] = 'ok';
	break;
	case "marcarPresente":
		list($_id,$idclientes) = explode('@',$pagina->id);
		marcarPresente($_id,$idclientes);
	break;
	case "marcaVariacao":
		marcaVariacao($pagina->id,$pagina->extra);
	break;
	case "removerItemCarrinho":
		list($_id,$idclientes) = explode('-',$pagina->id);
		
		$ret['sql'] = $sql = "delete from itens  where idpedidos = '".$_COOKIE['pedidoID']."' and idprodutos = '".$_id."' and idclientes = '".$idclientes."'";
		$db->query($sql);
		$ret['status'] = 'ok';
	break;
	case "removerItemLista":
		$index = $pagina->id.'-'.$cliente->id;
		unset($_SESSION['itensLista'][$index]);
		$ret['sql'] = $sql = "delete from presentes  where idpresentes = '".$pagina->id."' and idclientes = '".$cliente->id."'";
		$db->query($sql);
		$ret['status'] = 'ok';
	break;
	case "removerItemListaCha":
		$index = $pagina->id.'-'.$cliente->id;
		unset($_SESSION['itensListaCha'][$pagina->id]);
		$ret['sql'] = $sql = "delete from chas  where idchas = '".$pagina->id."'";
		$db->query($sql);
		$ret['status'] = 'ok';
	break;
	case "segueCarrinho":
		include("../modulo_restrito_pedido_".$pagina->id.".php");
			$echo = ' ';
	break;
	case "processaCarrinho":
		$ret = processarCarrinho();
		$ret['status'] = 'ok';
	break;
	case "Retorno":
						$pedido = new objetoDb('pedidos',$_REQUEST['orderid']);
						
						$echo = "N&uacute;mero do pedido: <strong>". $_REQUEST['orderid'] ."</strong><br>";
						$echo .= "Total: <strong>R$". number_format($_REQUEST['price']/100,2,",",".") ."</strong><br>";
						$echo .= "Data: <strong>". date("d/m/Y H:i:s")."</strong><br>";
						$echo .= "C&oacute;digo da transa&ccedil;&atilde;o (TID): <strong>" . $_REQUEST['tid'] . "</strong><br>";
						$echo .= "Status da transa&ccedil;&atilde;o: <strong>".trim($_REQUEST['lr'] .  " " . htmlentities($_REQUEST['ars'])). "</strong><br>";
						$echo .= $_REQUEST['arp'] ? "C&oacute;digo de autoriza&ccedil;&atilde;o: <strong>" . $_REQUEST['arp'] . "</strong><br>" : "";
						//$echo .= htmlentities("Campo livre: " . $_REQUEST['free']) . "<br>";
						//$echo .= htmlentities("Tipo de Autenticação: " . $_REQUEST['authenttype']) . "<br>";
						//$echo .= htmlentities("HASH do n.º do cartão (criptografado): " .$_REQUEST['pan']) . "<br>";
						$echo .= "C&oacute;digo do banco emissor do cart&atilde;o: " . $_REQUEST['bank']. "<br>";
						
						if((int) $_REQUEST['lr'] < 1){
						$echo .= "Valor do frete: <strong>R$".number_format($pedido->valor_frete,2,",",".")."</strong><br>";
						$echo .= "Forma de pagamento escolhida: <strong>Visa</strong><br>";
						$echo .= "Parcelas: <strong>".$pedido->parcelas."</strong><br>";
						$echo .= "Forma de envio: <strong>".$pedido->tipo_frete." - Prazo de entrega: ".$pedido->prazo."</strong>";
						$_POST['idestagios'] = 3;
						}else{
						$_POST['idestagios'] = 6;
						}

						
						$_POST['anotacao'] = $echo;
						$_POST['status_transacao'] = trim($_REQUEST['lr'].' '.$_REQUEST['ars']);
						$_POST['data_transacao'] = date("d/m/Y H:I:s");
						$_POST['retorno_transacao'] = serialize($_REQUEST);
						$db->editar('pedidos',$_REQUEST['orderid']);
						
															
															
	break;
	case "BradescoConfirma":
		//mail("noxsulivan@gmail.com","BradescoConfirma ".microtime(),pre($_REQUEST,true).pre($_SERVER,true));
		$echo = pre($_REQUEST,true);
	break;
	case "BradescoFalha":
		//mail("noxsulivan@gmail.com","BradescoFalha ".microtime(),pre($_REQUEST,true).pre($_SERVER,true));
		$echo = pre($_REQUEST,true);
	break;
	case "BradescoNotificaBoleto":	
						$pedido = new objetoDb('pedidos',$_REQUEST['numOrder']);
						
							$echo .= '<BEGIN_ORDER_DESCRIPTION><orderid>=('.$pedido->id.')' . chr(13) . chr(10);
							
		foreach($pedido->itens as $item){
							$precoItem = $item->quantidade * ((float)$item->produtos->preco_promocional > 0 ?
																	(float)$item->produtos->preco_promocional : (float)$item->produtos->preco_venda);
							$echo .= '<descritivo>=('.$item->produtos->codigo.' '.$item->produtos->produto.')' . chr(13) . chr(10);
							$echo .= '<quantidade>=('.$item->quantidade.')' . chr(13) . chr(10);
							$echo .= '<unidade>=(un)' . chr(13) . chr(10);
							$echo .= '<valor>=('.number_format($precoItem * (1 - (10/100)),2,'','').')' . chr(13) . chr(10);
		}
		if($pedido->valor_frete > 0){
							$echo .= '<adicional>=(frete)' . chr(13) . chr(10);
							$echo .= '<valorAdicional>=('.number_format('0' . $pedido->valor_frete ,2,'','').')' . chr(13) . chr(10);
		}
							$echo .= '<END_ORDER_DESCRIPTION>' . chr(13) . chr(10);
							$echo .= '<BEGIN_BOLETO_DESCRIPTION><CEDENTE>=(Mesacor.com.br)' . chr(13) . chr(10);
							$echo .= '<BANCO>=(237)' . chr(13) . chr(10);
							$echo .= '<NUMEROAGENCIA>=(0001)' . chr(13) . chr(10);
							$echo .= '<NUMEROCONTA>=(1234567)' . chr(13) . chr(10);
							$echo .= '<ASSINATURA>=(233542AD8CA027BA56B63C2E5A530029F68AACD5E152234BFA1446836220CAA53BD3EA92B296CA94A313E4E438AD64C1E4CF2CBAD6C67DAA00DE7AC2C907A99979A5AB53BFEF1FD6DD3D3A24B278536929F7F747907F7F922C6C0F3553F8C6E29D68E1F6E0CA2566C46C63A2DD65AFF7DF4802FBF4811CA58619B33989B8DDF8)' . chr(13) . chr(10);
							$echo .= '<DATAEMISSAO>=('.date("d/m/Y").')' . chr(13) . chr(10);
							$echo .= '<DATAPROCESSAMENTO>=('.date("d/m/Y").')' . chr(13) . chr(10);
							$echo .= '<DATAVENCIMENTO>=('.date("d/m/Y",mktime(0, 0, 0, date("m"), date("d") + 5, date("Y")) ).')' . chr(13) . chr(10);
							$echo .= '<NOMESACADO>=('.$pedido->cadastros->nome_completo.')' . chr(13) . chr(10);
							$echo .= '<ENDERECOSACADO>=('.normaliza($pedido->cadastros->endereco.", ".trim($pedido->cadastros->numero.' '.$pedido->cadastros->complemento.' '.$pedido->cadastros->bairros->bairro)).')' . chr(13) . chr(10);
							$echo .= '<CIDADESACADO>=('.$pedido->cadastros->cidades->cidade.')' . chr(13) . chr(10);
							$echo .= '<UFSACADO>=('.$pedido->cadastros->estados->nome.')' . chr(13) . chr(10);
							$echo .= '<CEPSACADO>=('.preg_replace("/[^0-9]/i","",$pedido->cadastros->cep).') '. chr(13) . chr(10);
							$echo .= '<CPFSACADO>=('.preg_replace("/[^0-9]/","",$pedido->cadastros->cpf).')' . chr(13) . chr(10);
							$echo .= '<NUMEROPEDIDO>=('.$pedido->id.')' . chr(13) . chr(10);
							$echo .= '<VALORDOCUMENTOFORMATADO>=(R$'.number_format(($pedido->valor * (1 - $desconto))+ $pedido->frete,2,',','.').')' . chr(13) . chr(10);
							$echo .= '<SHOPPINGID>=(0)' . chr(13) . chr(10);
							$echo .= '<NUMDOC>=('.$pedido->id.')' . chr(13) . chr(10);
							$echo .= '<CARTEIRA>=(25)' . chr(13) . chr(10);
							//$echo .= '<ANONOSSONUMERO>=(97)<END_BOLETO_DESCRIPTION>';
							$echo .= '<END_BOLETO_DESCRIPTION>';
						$iso = true;
		mail("noxsulivan@gmail.com","BradescoNotificaBoleto ".microtime(),$echo.pre($_REQUEST,true).pre($_SERVER,true));
	break;
	case "notificacaoEstoque":
				$produto = new objetoDb('produtos',$pagina->id);
				
				
		$echo .=  utf8_decode('
		  <h2>Aviso de disponibilidade</h2>
		  <h4>Informe seus dados para envio do e-mail</h4>
			<div id="formEnviar">
			   <form action="'.$pagina->localhost.$canal->url.'" method="post" class="formulario" id="formEnviarForm" onsubmit="return sendWindowForm(\'formEnviar\' ,\'formEnviarForm\')">
				<input name="acao" type="hidden" value="enviarAviso" />
				<input name="id" type="hidden" value="'.$pagina->id.'" />
					<div class="campo">
					  <label for="nome">Produto: </label>
					  <input id="nome" name="nome" type="text" value="'.$produto->codigo.' - '.$produto->produto.'" class="required inputField inputGrande" />
					</div>
					<div class="campo">
					  <label for="nome">Seu nome</label>
					  <input id="nome" name="nome" type="text" value="'.$formulario->nome.'" class="required inputField inputGrande" />
					</div>
					<div class="campo">
					  <label for="email">E-mail</label>
					  <input id="email" name="email" type="text" value="'.$formulario->email.'" class="inputField validate-email inputGrande" />
					</div>
					<div class="campo">
					  <label for="telefone">Telefone</label>
					  <input id="telefone" name="telefone" type="text" value="'.$formulario->telefone.'" class="inputField inputMedio" />
					</div>
					<div class="campo">
						<label for="newsletter">Newsletter</label>
						<input id="newsletter" name="newsletter" type="radio" checked="checked" value="sim" /> Sim, desejo receber!<br />
						<input id="newsletter" name="newsletter" type="radio" value="nao" /> N&atilde;o, obrigado.
					</div>
					<div class="campo">
					  <label for="mensagem">Mensagem</label>
					  <textarea id="mensagem" name="mensagem" class="required inputField inputGrande">'.$formulario->mensagem.'</textarea>
					</div>
			  </form>
			</div>');
	break;
	case "cadastrarNewsletter":
		$echo .= utf8_decode('
		  <h2>Newsletter</h2>
		  <h4>Confirme o e-mail que deseja receber as nossas novidades</h4>
			<div id="formEnviar" class="formulario">
			   <form action="'.$pagina->localhost.$canal->url.'" method="post" class="formulario" id="formEnviarForm" onsubmit="return sendWindowForm(\'formEnviar\' ,\'formEnviarForm\')">
				<input name="acao" type="hidden" value="enviarNewsletter" />
				<input id="nome" name="nome" type="hidden" value="'.base64_decode($pagina->id).'" class="required inputField inputMedio" />
					<div class="campo">
					  <label for="email">E-mail</label>
					  <input id="email" name="email" type="text" value="'.base64_decode($pagina->extra).'" class="required inputField validate-email inputGrande" />
					</div>
					<div class="campo">
						<label for="">&nbsp;</label>
						<button type="submit" class="awesome blue">Cadastrar</button>
					</div>
			  </form>
			</div>
		  <h4>N&atilde;o se esque&ccedil;a de verificar sua caixa de mensagens e clicar no link que ser&aacute; enviado.<br>
		  Adicione tamb&eacute;m nosso e-mail em sua lista e contatos para evitar que as mensagens caiam no spam.</h4>
	');
	break;

	case "indicarAmigos":
		$echo .= utf8_decode('
		  <h2>Enviar para amigo</h2>
		  <h4>Informe seus dados para envio do e-mail</h4>
			<div id="formEnviar" class="formulario">
			   <form action="'.$pagina->localhost.$canal->url.'" method="post" class="formulario" id="formEnviarForm" onsubmit="return sendWindowForm(\'formEnviar\' ,\'formEnviarForm\')">
				<input name="acao" type="hidden" value="enviarEmail" />
				<input name="id" type="hidden" value="'.$pagina->id.'" />
					<div class="campo">
					  <label for="nome">Seu nome</label>
					  <input id="nome" name="nome" type="text" value="'.$formulario->nome.'" class="required inputField inputMedio" />
					</div>
					<div class="campo">
					  <label for="email">E-mail</label>
					  <input id="email" name="email" type="text" value="'.$formulario->email.'" class="required inputField validate-email inputMedio" />
					</div>
					<div class="campo">
					  <label for="para">Para</label>
					  <input id="para" name="para" type="text" value="'.$formulario->para.'" class="required inputField inputMedio" />
					</div>
					<div class="campo">
						<label for="newsletter">Newsletter</label>
						<input id="newsletter" name="newsletter" type="radio" checked="checked" value="sim" /> Sim, desejo receber!<br />
						<input id="newsletter" name="newsletter" type="radio" value="nao" /> Não, obrigado.
					</div>
					<div class="campo">
					  <label for="mensagem">Mensagem</label>
					  <textarea id="mensagem" name="mensagem" class="required inputField">'.$formulario->mensagem.'</textarea>
					</div>
					<div class="campo">
						<label for="">&nbsp;</label>
						<button type="submit" class="awesome orange">Enviar</button>
					</div>
			  </form>
			</div>
	');
	break;
	case "vote":
		list($score,$id) = explode("@",$pagina->id);
		$db->query("update produtos set votos = (votos + ".$score.") / 2 where idprodutos = '".$id."' ");
	break;
	case "enviarEmail":
				$produto = new objetoDb('produtos',$_POST['id']);
				
				$emails = split(',',$_POST['para']);
				
				foreach($emails as $email){
					$corpo = '<h2>Ol&aacute;, '.$email.'</h2>
					<p>&nbsp;</p>
					<p><strong>'.$_POST['nome'].'</strong> esteve em nosso site e indicou para voc&ecirc; a seguinte p&aacute;gina:<p>
					<p><img src="'.$pagina->localhost.'img/'.$produto->fotos[0]['id'].'/200/200/" align="left" vspace="5" hspace="5">
					<strong>Ref:'.$produto->codigo.' - '.$produto->produto.'</strong> '.
					$info.'</p>'.
					($_POST['mensagem'] ? '<p>Disse tamb&eacute;m: <em><strong>'.utf8_decode($_POST['mensagem']).'</strong></em></p>' : '').
					'<p>Link do produto</p>
					<p><a href="'.$pagina->localhost.'Produtos/Ver/'.$produto->id.'?utm_content=IndicacaoProduto&utm_source=Interacao&utm_campaign=Interno&utm_medium=Site">'.$pagina->localhost.'Produtos/Ver/'.$produto->id.'/</a></p>
					<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua participa&ccedil;&atilde;o</p>';
					
					mailClass(trim($email),$_POST['nome'].utf8_decode(" indica uma página para você"),$corpo,$_POST['email'],$_POST['nome']);
				}
	
				$ret['status'] =  "ok";
				$ret['mensagem'] =  "<h3>A mensagem foi enviada com sucesso.</h3>";
				
				
				$_POST['data'] = date("d/m/Y h:i:s");
				$_POST['remetente'] = $_REQUEST['nome']." ".$_REQUEST['sobrenome'];
				$_POST['email'] = $_REQUEST['email'];
				$_POST['fone'] = $_REQUEST['telefone'];
				$_POST['titulo'] = 'Indicação de página';
				$_POST['mensagem'] = $corpo;
				$db->inserir('formularios');
	break;
	case "busca":
	case "Busca":
						$busca = ($_REQUEST['term']);
						
						$_POST['termo'] = $busca;
						if(!preg_match("/bot/i",$_SERVER['HTTP_USER_AGENT']))
						$busca = preg_replace("/\+/i"," +",$busca);
						$termosBusca = explode(" ",normaliza($busca));

						$whe = array();
						foreach($termosBusca as $_t){
							if(strlen($_t) > 2){
								if(preg_match("/\+/i",$_t))
									$wheAND[] = "(soundex(produto) like soundex('".preg_replace("/\+/i","",$_t)."') or produto like '%".preg_replace("/\+/i","",$_t)."%' or codigo like '%".preg_replace("/\+/i","",$_t)."%')";
								else
									$wheOR[] = "(soundex(produto) like soundex('".$_t."') or produto like '%".$_t."%' or codigo like '%".$_t."%')";
								$db->query("insert into buscas (termo) values ('".trim(strtolower(normaliza($_t)))."') on duplicate key update contagem = contagem + 1;");
							}
						}
						
						
						
						
						$sql = "
						SELECT produtos.idprodutos, produtos.codigo, produtos.descricao_curta
						FROM produtos, linhas
						where 
							produtos.ativo = 'sim' and
							(".
						( count($wheOR) ? " (".implode(" and ",$wheOR).")" : "").
						( count($wheOR) && count($wheAND)? " or " : "").
						( count($wheAND) ? " (".implode(" or ",$wheAND).")" : "").") or
							(linhas.linha like '".$busca."' and
							produtos.idlinhas = linhas.idlinhas)
						group by produtos.idprodutos
						order by produtos.codigo, produtos.preco_venda
						limit 20
							";
							
						$db->query($sql);
						unset($ret);
						while($res = $db->fetch()){
							$produto = new objetoDb("produtos",$res['idprodutos']);
								$result[] = array("label" => $produto->produto , "codigo" => $produto->codigo ,
												  "img" => $pagina->img($produto->fotos[0]['id'].'/90/90') ,
												  "value" => $pagina->localhost."Produtos/Ver/".$produto->id ,
												  "desc" => $produto->linhas->linha);
						}
						$ret = $result;

	break;
	case "1viaBoleto";
	
						$pedido = new objetoDb('pedidos',$pagina->id);
						$Itau = new Itaucripto;
						$dados = $Itau->geraDados
						(
								$codEmp = 'J0043497660001370000012814',
								$pedido->id,
								$valor = number_format($pedido->valor,2,',',''),
								$observacao = '',
								$chave = 'MESACORTRA243101',
								$nomeSacado = $pedido->$cadastros->nome.' '.$pedido->$cadastros->sobrenome,
								$codigoInscricao = 01,
								$numeroInscricao = str_replace('.','',str_replace('-','',$pedido->$cadastros->cpf)),
								$enderecoSacado = $pedido->$cadastros->endereco,
								$bairroSacado =  $pedido->$cadastros->bairros->bairro,
								$cepSacado = $pedido->$cadastros->cep,
								$cidadeSacado = $pedido->$cadastros->cidades->cidade,
								$estadoSacado = $pedido->$cadastros->estados->nome,
								$dataVencimento = date("dmY",time()+60*60*24*5),
								$urlRetorna = 'https://www.mesacor.com.br',
								$obsAd1 = '',$obsAd2 = '',$obsAd3 = ''
						);
						
						$Itau->decripto($dados, $chave);
						 $echo ='
						<form action="https://shopline.itau.com.br/shopline/shopline.asp" method="post" id="1viaBoleto">
						<input type="hidden" name="DC" value="'.$dados.'" />
						<button type="submit" class="awesome grey"><strong>2ª via do boleto</strong></button>
						</form>
						<script>document.forms[\'1viaBoleto\'].submit()</script>';
	break;
	case "2viaBoleto";
	
					$sql = " select * from pedidos where idpedidos ='".$pedido->id."'";
					$db->query($sql);
					$res = $db->fetch();
	
	
						$pedido = new objetoDb('pedidos',$pagina->id);
						$Itau = new Itaucripto;
						
									
									$date = date( "dmY", strtotime( $res['data']." +4 days" ) );
									
									
						$dados = array
						(
								$codEmp = 'J0043497660001370000012814',
								$pedido->id,
								$valor = number_format($pedido->valor,2,',',''),
								$observacao = '',
								$chave = 'MESACORTRA243101',
								$nomeSacado = $cadastro->nome.' '.$cadastro->sobrenome,
								$codigoInscricao = 01,
								$numeroInscricao = str_replace('.','',str_replace('-','',$cadastro->cpf)),
								$enderecoSacado = $cadastro->endereco,
								$bairroSacado =  $cadastro->bairros->bairro,
								$cepSacado = $cadastro->cep,
								$cidadeSacado = $cadastro->cidades->cidade,
								$estadoSacado = $cadastro->estados->nome,
								$dataVencimento = $date,
								$urlRetorna = 'https://www.mesacor.com.br',
								$obsAd1 = '',$obsAd2 = '',$obsAd3 = ''
						);
						
						pre($dados );
						$dados = $Itau->geraDados
						(
								$codEmp = 'J0043497660001370000012814',
								$pedido->id,
								$valor = number_format($pedido->valor,2,',',''),
								$observacao = 'Pague somente até o vencimento',
								$chave = 'MESACORTRA243101',
								$nomeSacado = $cadastro->nome.' '.$cadastro->sobrenome,
								$codigoInscricao = 01,
								$numeroInscricao = str_replace('.','',str_replace('-','',$cadastro->cpf)),
								$enderecoSacado = $cadastro->endereco,
								$bairroSacado =  $cadastro->bairros->bairro,
								$cepSacado = $cadastro->cep,
								$cidadeSacado = $cadastro->cidades->cidade,
								$estadoSacado = $cadastro->estados->nome,
								$dataVencimento = $date,
								$urlRetorna = 'https://www.mesacor.com.br',
								$obsAd1 = '',$obsAd2 = '',$obsAd3 = ''
						);
						
						
						$Itau->decripto($dados, $chave);
						 $echo ='
						<form action="https://shopline.itau.com.br/shopline/reemissao.asp" method="post" id="2viaBoleto">
						<input type="hidden" name="DC" value="'.$dados.'" />
						<button type="submit" class="awesome grey"><strong>2ª via do boleto</strong></button>
						</form>
						<script>ssdocument.forms[\'2viaBoleto\'].submit()</script>';
	break;
	
	case "enviarAviso":
				$corpo_header = '
					<h2>Avise-me</h2>
					<p><strong>'.$_POST['nome'].' '.$_POST['email'].'</strong> esteve em nosso site e deseja ser avisa quando o produto estiver disponível.<p>
					';
	
				$titulo = "Pedido de aviso de disponibilidade";
				
				
				$_POST['idprodutos'] = $_POST['id'];
				$_POST['data'] = date("d/m/Y h:i:s");
				$db->inserir('interessados');
				
	case "enviarTelevendas":
				$corpo_header = $corpo_header ? $corpo_header : '
					<h2>Pedido de contato de Televendas</h2>
					<p>O visitante a seguir esteve em nosso site e deseja receber contato do televendas.<p>
					<p>Link do produto</p>
					<p><a href="'.$pagina->localhost.'Produtos/Ver/'.$produto->id.'">'.$pagina->localhost.'Produtos/Ver/'.$produto->id.'/</a></p>
					';
	
				$titulo = $titulo ? $titulo : "Pedido de contato de Televendas";
	case "enviarDados":
	
				if($_POST['id']){
					$produto = new objetoDb('produtos',$_POST['id']);
					$_POST['Produto'] = utf8_decode($produto->codigo.' - '.$produto->produto);
				}
				
				
				$corpo_header = $corpo_header ? $corpo_header : '
					<h2>Mensagem enviada pelo site</h2>';
				
				while(list($k,$v) = each($_POST)){
					switch($k){
						case "acao":
						break;
						default:
							$corpo .= str_replace("_"," ",ucfirst($k)).": <strong>".$v."</strong><br>";
					}
				}
				$_SESSION[formulario] = $_POST;
							

				$corpo .= utf8_decode('<h3>Informações de segurança</h3>
				<p>
				Este e-mail foi enviado através do site '.$pagina->localhost.'</strong><br>
				Data e hora: <strong>'.date("d/m/Y h:i:s").'</strong><br>
				IP: <strong>'.$_SERVER['REMOTE_ADDR'].'</strong>
				</p>');
				
				//$corpo .= pre($_SERVER,true).pre($_REQUEST,true);
				
				$titulo = $titulo ? $titulo : "Mensagem do site" ;
				
				if(mailClass($pagina->configs["email_suporte"],$titulo,$corpo,$_REQUEST['email'],$_POST['nome'])){
				//if(mailClass("noxsulivan@gmail.com","SITE. ".$titulo,$corpo,$_REQUEST['email'],$_POST['nome'])){
					$ret['status'] =  "ok";
					$ret['mensagem'] =  "A mensagem foi enviada com sucesso. Em breve entraremos em contato";
				}else{
					$ret['status'] =  "error";
					$ret['mensagem'] =  "Este não é um e-mail válido";
				}
		
				$_POST['data'] = date("d/m/Y h:i:s");
				$_POST['remetente'] = $_REQUEST['nome']." ".$_REQUEST['sobrenome'];
				$_POST['email'] = $_REQUEST['email'];
				$_POST['fone'] = $_REQUEST['telefone'];
				$_POST['titulo'] = 'Mensagem do site';
				$_POST['mensagem'] = $corpo;
				$db->inserir('formularios');
				
				if($_POST['newsletter'] != 'sim'){
					break;
				}
	case "enviarNewsletter":
				$_POST['data'] = date("d/m/Y h:i:s");
				$_POST['nome'] = normaliza(trim(str_replace("Nome","",$_REQUEST['nome'])));
				$_POST['email'] = trim(str_replace("E-mail","",$_REQUEST['email']));
				$_POST['chave'] = md5($_POST['email'].date("d/m/Y H:i:s"));
				$_POST['ip'] = $_SERVER['REMOTE_ADDR'];
				$_POST['idclientes'] = 1;
				$db->inserir('leitores');
				
				$_POST['idleitores'] = $inserted_id;
				$_POST['idsegmentos'] = 4;
				//$db->inserir('leitores_has_mailings');
				
				$ret['status'] =  "ok";
				$ret['mensagem'] =  $corpo = utf8_decode('
				<h2>Cadastro na newsletter '.$pagina->configs['titulo_site'].'</h2>
				<p>Seu e-mail <strong>'.$_REQUEST['email'].'</strong> foi cadastrado em nosso Newsletter<p>
				<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua participação</p>');
//				
//				mailClass($_POST['email'],"Cadastro na newsletter ".$pagina->localhost,$corpo,$pagina->configs["email_suporte"],$pagina->configs['titulo_site'],$_FILES);
				
//														include_once('../php-sdk/facebook.php');
//														$api = new MCAPI('5606c401a8622f22e341ef31f1859692-us5');
//														$list_id = "9a8d868c94";
//														
//														$merges = array('FNAME'=>$_POST['nome'], 'MMERGE5'=>$pagina->localhost,'OPTINIP'=>$_SERVER['REMOTE_ADDR']);
//														
//														if($api->listSubscribe($list_id, $_REQUEST['email'], $merges)){
//															$ret['mensagem'] .=  "<h2>Seu e-mail foi inscrito em nossa newsletter. </h2>Confirme seu e-mail para confirmar o endereço.";
//														}else{
//															$ret['mensagem'] .=  $api->errorMessage;
//														}
				//if(rand(0,1) == 1)
				//@postarTwitter("Cadastre o seu e-mail e receba a nossa newsletter contendo sempre novidades e PROMO&Ccedil;&Otilde;ES!!! http://mesacor.com.br/ #Tramontina");

	break;
	case "sitemap":	
			header('Content-type: text/xml');
			$echo = '<?xml version="1.0" encoding="UTF-8"?>
						<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
						$db->query("select idcanais from canais");
						while($res = $db->fetch()){
							$canal = new objetoDb('canais',$res['idcanais']);
							$echo .= '
							<url>
								<loc>'.$pagina->localhost.$canal->url.'</loc>
								<lastmod>'.date("Y-m-d").'</lastmod>
								<changefreq>monthly</changefreq>
								<priority>0.6</priority>
							</url>';
						}
						$db->query("select idcategorias from categorias");
						while($res = $db->fetch()){
							$categoria = new objetoDb('categorias',$res['idcategorias']);
							$echo .= '
							<url>
								<loc>'.$pagina->localhost.'Produtos/Categoria/'.$categoria->url.'</loc>
								<lastmod>'.date("Y-m-d").'</lastmod>
								<changefreq>monthly</changefreq>
								<priority>0.8</priority>
							</url>';
						}
						$db->query("select idprodutos from produtos");
						while($res = $db->fetch()){
							$produto = new objetoDb('produtos',$res['idprodutos']);
							$echo .= '
							<url>
								<loc>'.$pagina->localhost.'Produtos/Ver/'.$produto->id.'</loc>
								<lastmod>'.date("Y-m-d").'</lastmod>
								<changefreq>daily</changefreq>
								<priority>1</priority>
							</url>';
						}
						
			$echo .= '</urlset>';
	break;
	default:
		mail("noxsulivan@gmail.com","teste ".microtime(),pre($_REQUEST,true).pre($_SERVER,true));
			$canal = new objetoDb('canais',$pagina->acao);
			if($canal->tipos_de_canais->arquivo)
				include("../".$canal->tipos_de_canais->arquivo);
			else
				include($root."../_inexistente.php");
			$ret = '';
	break;
	
}

if($echo){
	if($iso)
	echo $echo;
	else
	echo utf8_encode($echo);
}else{
	@array_walk($ret, 'sanitizaRet');
	echo json_encode($ret);
}

$timeEnd = microtime(true);

if($iso)
$buffer = ob_get_clean();
else
$buffer = utf8_encode(ob_get_clean());
//$buffer = utf8_encode(ob_get_clean());

//header('Content-Length: ' . strlen($buffer));
//header('X-Server-Elapsed-Time: '.($timeEnd-$timeIni));
//header('X-Server-Memory-Usage: '.round(memory_get_usage()/1024));
//header('X-Server-Memory-Peak-Usage: '.round(memory_get_peak_usage()/1024));

	if ($supportsGzip and 0) {
			//header("Content-Encoding: " . $enc);
			$cacheData = gzencode($buffer, 9, FORCE_GZIP);
			
			$timeEnd = microtime(true);
			//header('X-Server-Elapsed-Time: '.round($timeEnd-$timeIni,2));
			
		// Stream to client
		echo $cacheData;
		flush();
	} else {
		// Stream uncompressed content
			$timeEnd = microtime(true);
			//header('X-Server-Elapsed-Time: '.round($timeEnd-$timeIni,2));
		echo $buffer;
		flush();
	}
?>