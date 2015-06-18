<?
$timeIni = microtime(true);
$_tmp = explode("/",$_SERVER['REQUEST_URI']);

if(!eregi('admin',$_tmp[1])){
	$includeIni = $_SERVER['DOCUMENT_ROOT']."/".$_tmp[1]."/ini.php";
}else{
	$includeIni = $_SERVER['DOCUMENT_ROOT']."/ini.php";
}
include($includeIni);
define ('COOKIE_NAME', diretorio('usuarioAdmin_'.$_SERVER['HTTP_HOST']."_".$_SERVER['SCRIPT_FILENAME']."_".date("Ymd")."_reset2"));



set_time_limit ( 0 );

$_t = array();
function limpaPost($v,$k){
	global $_t;
	$_t[str_replace("amp;","",$k)] = is_array($v) ? $v : utf8_decode($v);
}
$_POST = array_walk($_POST,"limpaPost");
$_POST = $_t;

$queryString = ereg('public_html',$_SERVER["QUERY_STRING"]) ? '' : $_SERVER["QUERY_STRING"];
//die("|".$_SERVER["QUERY_STRING"]."|".$queryString."|");
$admin = new admin($queryString);

			$tipos_de_situacao['01'] = 1;
			$tipos_de_situacao['02'] = 2;
			$tipos_de_situacao['03'] = 3;
			$tipos_de_situacao['04'] = 4;
			$tipos_de_situacao['00'] = 5;
			$tipos_de_situacao['05'] = 6;
			$tipos_de_situacao['06'] = 7;

	$db->query('select * from boletos where status_mensagem = "nao" and valor > 0 and data_vencimento > 0 order by idboletos desc');
	//$db->query('select * from boletos where valor > 0 and data_vencimento > 0 order by idboletos desc');
	
	pre("Boletos a serem analizados ".$db->rows);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://shopline.itau.com.br/shopline/consulta.asp");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
	$Itau = new Itaucripto;
	while($res = $db->fetch()){
			$dados = $Itau->geraDados
			(
					$codEmp = 'J0046371390001000000011068',
				$pedido = $res['idboletos'],
				$valor = str_replace('.',',',$res['valor']),
					$observacao = 'Cota de viagem em favor de '.strtoupper($cli->nome).'Nao receber apos ',
					$chave = 'VIAGEM20COTA0709',
					$nomeSacado = $res['nome'],
					$codigoInscricao = 01,
					$numeroInscricao = $res['cpf'] ? str_replace('.','',str_replace('-','',$res['cpf'])) : '00000000000',
					$enderecoSacado = $res['endereco'] ? $res['endereco'] : '00000000000',
					$bairroSacado =  'CENTRO',$cepSacado = '00000000',
					$cidadeSacado = 'RIO DE JANEIRO',$estadoSacado = 'RJ',
					$dataVencimento = $res['data_vencimento'] ? str_replace('/','',ex_data($res['data_vencimento'])) : '10102010',
					$urlRetorna = 'https://viagememcotas.com.br',
					$obsAd1 = '',$obsAd2 = '',$obsAd3 = ''
			);
			
			//$Itau->decripto($dados, $chave);
			$consulta = $Itau->geraConsulta('J0046371390001000000011068', $res['idboletos'], 1, 'VIAGEM20COTA0709');
			$data = http_build_query(array("DC" => $consulta));
			//pre($data);			die();
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$xml = simplexml_load_string(curl_exec($ch));
			$_a = array();
			
			foreach($xml->PARAMETER->PARAM as $param){
				$t = $param->attributes();
				$_a[(string)$t['ID']] = (string)$t['VALUE'];
			}
			//pre($_a);			die();
			
			$sql = "update boletos set idtipos_de_situacao = '".$tipos_de_situacao[$_a['sitPag']]."', data_pagamento = '".substr($_a['dtPag'],4,4).'-'.substr($_a['dtPag'],2,2).'-'.substr($_a['dtPag'],0,2)."', retorno_shopline = '".serialize($_a)."', codAut = '".$_['codAut']."' where idboletos = '".$res['idboletos']."'";
			$db->query($sql);
			flush();
			
							
							
			$boleto = new objetoDb('boletos',$res['idboletos']);
			
							
			if($tipos_de_situacao[$_a['sitPag']] == 5){
							$corpo = '<table border="0" cellspacing="0" cellpadding="50"><tr>
							<td width="500" valign="top" background="http://www.viagememcotas.com.br/_imagens/fundo_email.jpg">
								<br /><br /><br /><br /><br /><br /><br /><br /><br /><h2>Agradecimento</h2>
								<p>Sr.(a) '.$boleto->nome.',</p>
								<p>Em nome de <strong>'.strtoupper($boleto->clientes->nome).'</strong> a VIA RIO VIAGEM EM COTAS agradece sua participação na lista de presente, e confirma o recebimento de R$'.number_format($boleto->valor,2,',','.').',
								através do documento de nº '.str_pad($boleto->id,8,0,STR_PAD_LEFT).'.</p>
							</td></tr></table>';
							
							mailClass($boleto->email.','.$admin->configs['email_suporte'].','.$boleto->clientes->email,
										"Agradecimento - ViaRio Viagem em Cotas - ".$boleto->clientes->sigla,
										$corpo,
										$admin->configs['email_suporte']);
							
							$db->query('update boletos set status_mensagem = "sim" where idboletos = "'.$boleto->id.'"');
							
							echo "o boleto ".$boleto->id." foi pago - foi enviado para ".$boleto->email.','.$admin->configs['email_suporte'].','.$boleto->clientes->email."\n\n";
			}elseif(compara_data(in_data($boleto->data_vencimento),date("Y-m-d")) < -2 and ($tipos_de_situacao[$_a['sitPag']] != 2 && $tipos_de_situacao[$_a['sitPag']] != 6)){
				
							$boleto = new objetoDb('boletos',$res['idboletos']);
							$corpo = '<table border="0" cellspacing="0" cellpadding="50"><tr>
							<td width="500" valign="top" background="http://www.viagememcotas.com.br/_imagens/fundo_email.jpg">
								<br /><br /><br /><br /><br /><br /><br /><br /><br /><h2>Informativo</h2>
								<p>Sr.(a) '.$boleto->nome.',</p>
								<p>Informamos que não foi detectado em nosso controle bancário, a quitação do pedido '.str_pad($boleto->id,8,0,STR_PAD_LEFT).' com vencimento para '.$boleto->data_vencimento.', referente a compra de cotas de viagem de <strong>'.strtoupper($boleto->clientes->nome).'</strong>.</p>
								<p>Caso tenha sido pago, favor contactar-nos; caso não, desconsidere tal documento bancário, pois nenhuma cobrança lhe será feita após a data do vencimento.</p>
								<p>Havendo ainda o interesse em presentear '.( $boleto->clientes->tipos_de_clientes->id == 1 ? 'o(a) Aniversariante' : 'os Noivos').', favor entrar novamente no blog,  no site <a href="http://www.viagememcotas.com.br">http://www.viagememcotas.com.br</a> e gerar nova boleta, ou para maiores esclarecimentos (21) 8445-9860 ou 8445-9861.</p>
								<p>Via Rio Viagem Em Cotas</p>
							</td></tr></table>';
							
							mailClass($admin->configs['email_suporte'].','.$boleto->email,
										"Informativo - ViaRio Viagem em Cotas - ".$boleto->clientes->sigla,
										$corpo,
										$admin->configs['email_suporte']);
							
							$db->query('update boletos set idtipos_de_situacao = 8, status_mensagem = "sim" where idboletos = "'.$boleto->id.'"');
							echo "o boleto ".$boleto->id." venceu - foi enviado para ".$admin->configs['email_suporte'].','.$boleto->email."\n\n";
			}else{
							echo "o boleto ".$boleto->id." está como ".$tipos_de_situacao[$_a['sitPag']]."\n\n";
			}
			flush();
	}
	curl_close($ch);
	die;
?>