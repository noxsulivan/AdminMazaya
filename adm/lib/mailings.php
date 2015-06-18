<?

//pre($admin);
//if($db->tabelaExiste('leitores')){
//		$sqldisp = "SELECT count(idleitores) as total_enviado, data_envio FROM `leitores_has_mailings` where month(data_envio) = ".date("n")." and year(data_envio) = ".date("Y")." and status >= 2 group by month(data_envio)";
//		$db->query($sqldisp);
//		$resdisp = $db->fetch();
//		$total_disponivel = 25000 - $resdisp["total_enviado"];
//}
		
		
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Dados da mensagem');
		$formulario->fieldset->simples('Título', 'titulo',$res["titulo"]);
		$formulario->fieldset->simples('Data de criação', 'data_criacao',date("d/m/Y h:i:s"));	
		$formulario->fieldset->simples('Corpo', 'corpo',$res["corpo"]);
	
	$formulario->fieldset('Anexos');
			//$admin->explicacao_formulario("Envie aqui as imaens para o servidor e depois insira no corpo da mensagem conforme desejar");
		$formulario->fieldset->fotos();
			//$admin->explicacao_formulario("Envie arquivos em anexo como documentos do Word, tabelas do Excell ou artigos em .PDF");
		$formulario->fieldset->arquivo();
	
	
	$formulario->fieldset('Distribuição');
		$formulario->fieldset->simples('Status', 'status');
		$formulario->fieldset->checkBox('Segmentos de destino','segmentos');
	
	
	
	
	
break;
case "salvar":
	if($admin->id == ''){
		$_POST["data_envio"] = $_POST["data_envio"] ? $_POST["data_envio"] : "00/00/0000 00:00:00";
		$db->inserir('mailings');
		$inserted_id = $db->inserted_id;
		$db->salvar_fotos('mailings',$inserted_id);
		$db->salvar_arquivos('mailings',$inserted_id);
		if($db->tabelaExiste('mailings_has_segmentos')){
			$db->tabela_link('mailings','segmentos',$inserted_id,$_POST["tabela_link"]);
		}
	}else{
		$db->editar('mailings',$admin->id);
		$db->salvar_fotos('mailings',$admin->id);
		$db->salvar_arquivos('mailings',$admin->id);
		$db->filhos('mailings',$admin->id);
		if($db->tabelaExiste('mailings_has_segmentos')){
			$db->tabela_link('mailings','segmentos',$admin->id,$_POST["tabela_link"]);
		}
	}
break;
case "inbox":

		$db->query("select * from editores");
		while($res = $db->fetch()){
			$editores[$res['email']] = $res['ideditores'];
		}
		define('DISPLAY_XPM4_ERRORS', false); // display XPM4 errors
		require_once '../_inc/XPM4/POP3.php';
		require_once '../_inc/XPM4/MIME.php';
		
			$h = explode('@', $_SERVER['SERVER_ADMIN']);
			
		// connect to 'pop.hostname.tld'
		$c = POP3::Connect('mail.'.$h[1], 'site@'.$h[1], '1nfo');
		// get the list of messages
		$l = POP3::pList($c) or die(pre($_RESULT));
		// or, specify only the old message
		// $l = POP3::pList($c, 1) or die(print_r($_RESULT));
		$_mensagensCriadas = 0;
		for($i = 1 ; $i <= count($l); $i++){
			$m = POP3::pRetr($c, $i) or die(print_r($_RESULT));
			MIME::split_mail($m, $h, $b);
			
			foreach($h as $_h){
				$msg[$_h['name']] = str_replace(array("<",">"),'',($_h['value']));
			}
			$db->query("select idmailings from mailings where message_id = '".$msg['Message-ID']."'");
			if($db->rows == 0){
				if(array_key_exists($msg['Return-path'],$editores)){
					foreach($b as $_b){
						//pre($_b); continue;
						pre($_b['type']['value']);
						pre($_b['id']['value']);
						
						if($_b['type']['value'] == 'text/html'){
							$corpo = $_b['content'];
							$_POST['ideditores'] = $editores[$msg['Return-path']];
							//$_POST['headers'] = print_r($h,true);
							$sub = MIME::decode_header($msg['Subject']);
							$_POST['titulo'] = mysql_real_escape_string($sub[0]['value']);
							$_POST['message_id'] = $msg['Message-ID'];
							$_POST['tamanho'] = strlen($m);
							$_POST['data_criacao'] = date("d/m/Y H:i:s");//fromRFC2822($msg['Date']);
							$db->inserir('mailings');
							unset($_POST);
							$idmailings = $db->inserted_id;
							pre('inserido o mailing '.$_POST['titulo']);
						}
							
						if(ereg('image',$_b['type']['value'])){
							pre('inserindo imagem...');
							$_POST['idmailings'] = $idmailings;
							$_POST['status'] = 'sim';
							$_POST['chave'] = $usuario->chave;
							$_POST['url'] = $_b['disposition']['extra']['filename'];
							$_POST['arquivo'] = mysql_real_escape_string($_b['content']);
							$_POST['size'] = strlen($_b['content']);
							$_POST['filetype'] = $_b['type']['value'];
							$db->inserir('fotos');
							unset($_POST);
							$corpo = str_replace(
												 "cid:".$_b['id']['value'],
												 $admin->localhost."imagem.php?id=".$db->inserted_id,
												 $corpo);
						}
						
					}
					$corpo = mysql_real_escape_string($corpo);
					$_POST['corpo'] = $corpo;
					unset($corpo);
					$db->editar('mailings',$idmailings);
					$_mensagensCriadas ++;
				}else{
					$_mensagensRejeitadas[] = $msg['X-Failed-Recipients'];
					POP3::pdele ($c, $i );
				}
			}
		}
		POP3::Disconnect($c);
		echo "<h2>".count($l)." mensagens processadas. $_mensagensCriadas nova(s)</h2>";
		if($_mensagensRejeitadas){
			echo "<h3>".count($_mensagensRejeitadas)." mensagens foram rejeitadas por terem sido enviadas de endere&ccedil;os de e-mail n&atilde;o autorizados</h3>";
			pre($_mensagensRejeitadas);
		}
break;
default:

	$admin->campos_listagem = array('Título' => "titulo", "data de criação" => 'data_criacao', "data de envio" => 'data_envio', "Enviados" => 'enviados', "Lidos" => 'lidos', "Falhos" => 'falhos');
	

	$sql = "select idmailings from mailings";
	
		$admin->ordenar = "data_criacao";
		$admin->extra = "desc";
	//$admin->html .= "<h2>Cota disponível para o mês de ".$mes[date("n")].": $total_disponivel e-mails</h2>";
	
break;
}
?>