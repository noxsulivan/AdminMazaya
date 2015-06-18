<?



$mes = array (1=>"Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
$semana = array('Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado');
$cores = array('FF6600','3399FF','009966','CC3399','FFCC33','6699CC','CC3366','123456','33FF66','FF6600');

	function dbug() {
		static $output = '', $doc_root;
		$args = func_get_args();
		if (!empty($args) && $args[0] === 'print') {
			$_output = $output;
			$output = '';
			return $_output;
		}
		// do not repeat the obvious (matter of taste)
		if (!isset($doc_root)) {
			$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
		}
		$backtrace = debug_backtrace();
		// you may want not to htmlspecialchars here
		$line = htmlspecialchars($backtrace[0]['line']);
		$file = htmlspecialchars(str_replace(array('\\', $doc_root), array('/', ''), $backtrace[0]['file']));
		$class = !empty($backtrace[1]['class']) ? htmlspecialchars($backtrace[1]['class']) . '::' : '';
		$function = !empty($backtrace[1]['function']) ? htmlspecialchars($backtrace[1]['function']) . '() ' : '';
		$output .= "<b>$class$function =&gt;$file #$line</b><pre>";
		ob_start();
		foreach ($args as $arg) {
			var_dump($arg);
		}
		$output .= htmlspecialchars(ob_get_contents(), ENT_COMPAT, 'UTF-8');
		ob_end_clean();
		$output .= '</pre>';
	}

  function postarTwitter($post){
	  
		define('TWITTER_CONSUMER_KEY', 'fax4tnC7BVCJRzdFLktvA');
		define('TWITTER_CONSUMER_SECRET', 'Q9HjQwcZurOi1w6Ach5rPxV2zJtmWpdnJ1VxZacRHUk');
		  
		$consumer_key = 'fax4tnC7BVCJRzdFLktvA';
		$consumer_secret = 'Q9HjQwcZurOi1w6Ach5rPxV2zJtmWpdnJ1VxZacRHUk';
		
		//Energia_BC
		$oauth_token= '371557704-BE6ZRdEhvMGVHCnQBCirjNdpll21V0SOT9mZg4wV';
		$oauthSecret = 'vyxXBCWMFCmXsnxYmtw6olCk18s9YMgSea9LRHV0egg';
		$user_id = '371557704';
		
		
		//mesacor
		$oauth_token= '124257282-KjB6AzoGB25rFxMVNldXuwpN7nvmfI6KInBslwho';
		$oauthSecret = 'dLoqmUAZn3R8kgk8eRctZ5Ws1H7hFo4gQXsYYUvFXdU';
		$user_id = '124257282';
		
		
		$twitterObj = new EpiTwitter($consumer_key, $consumer_secret, $oauth_token, $oauthSecret);
		$userInfo = $twitterObj->get_accountVerify_credentials();
  
		$twitterObj->post_statusesUpdate(array('status' => urldecode(utf8_encode($post))));
  }
  
  function twitpic($produto,$id_foto,$id){
	  
		global $db;
		
		$sql = "select twitpic from fotos where idfotos = ".$id_foto." and twitpic is not null";
		
		//pre($sql);
		$db->query($sql);
		if(!$db->rows){
			define("twitpicApiKey", "ecd96d4ea588cde120cb7bf5c464c00a");
			define("consumerKey", "fax4tnC7BVCJRzdFLktvA");
			define("consumerSecret", 'Q9HjQwcZurOi1w6Ach5rPxV2zJtmWpdnJ1VxZacRHUk');
			define("oauthToken", "124257282-KjB6AzoGB25rFxMVNldXuwpN7nvmfI6KInBslwho");
			define("oauthTokenSecret", "dLoqmUAZn3R8kgk8eRctZ5Ws1H7hFo4gQXsYYUvFXdU");
			
			define("twitpicUploadUrl", "http://api.twitpic.com/2/upload.json");
			
			# This is the end point at Twitter that we want to Echo
			define("twitterEchoUrl", "https://api.twitter.com/1/account/verify_credentials.json");
			
			$fileToUpload = "@".$_SERVER["DOCUMENT_ROOT"] . "/_cache/".$id_foto."-800-600.jpg";
			
			# Prepare an OAuth Consumer
			$consumer = new OAuthConsumer(consumerKey, consumerSecret, NULL);
			# Prepare an Access Token to represent the user
			$access_token = new OAuthToken(oauthToken, oauthTokenSecret);
			
			# Setup the mock request object so we can sign the request
			$request = OAuthRequest::from_consumer_and_token($consumer, $access_token, 'GET', twitterEchoUrl, NULL);
			
			# Sign the constructed OAuth request using HMAC-SHA1
			$request->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $consumer, $access_token);
			
			# We need to chop the header a bit to get it in the exact format
			# that TwitPic likes best.
			
			$header = $request->to_header();
			# Snip out the "Authorization: " part, and add a realm to be polite
			$real_header = explode("Authorization: OAuth", $header);
			$header = 'OAuth realm="http://api.twitter.com/",' . $real_header[1];
			# Keep the spaces between elements, even though we shouldn't have to
			$header = str_replace(",", ", ", $header);
			
			$post["key"] = twitpicApiKey;
			$post["message"] = urldecode(utf8_encode($produto.' http://mesacor.com.br/Twitter/'.$id));
			$post["media"] = $fileToUpload;
			
			$response = send_echo_request("POST", twitpicUploadUrl, $header, $post, twitterEchoUrl);
			
			# Evaluate the response
			$twitpic = json_decode($response);
			
			//pre($twitpic);
			
			$_POST['twitpic'] = $twitpic->id;
			$sql = 'update fotos set twitpic = "'.$twitpic->id.'" where idfotos = '.$id_foto;
			//pre($sql);
			$db->query($sql);
			return $twitpic->id;
		}else{
			$res = $db->fetch();
			return $res['twitpic'];
		}
		
	}

	function send_echo_request($http_method, $url, $auth_header, $postData, $echo_url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FAILONERROR, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		
		// Set our OAuth Echo headers
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'X-Verify-Credentials-Authorization: ' . $auth_header,
		'X-Auth-Service-Provider: ' . $echo_url
		));
		
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
		
		
		$response = curl_exec($curl);
		if (!$response) {
		$response = curl_error($curl);
		}
		curl_close($curl);
		return $response;
	}
	
	function pdf($corpo,$nome_arquivo,$enviar_navegador = FALSE,$arquivo){
		require_once($_SERVER['DOCUMENT_ROOT'].'/_shared/dompdf/dompdf_config.inc.php');

		
		$dompdf = new DOMPDF();
		
		$dompdf->load_html($corpo);
		
		$dompdf->render();
		
		if($enviar_navegador == TRUE){
		  $dompdf->stream($arquivo, array("Attachment" => false));
		}else{
		  $pdf = $dompdf->output();
		  file_put_contents($nome_arquivo, $pdf);
		}
			  
	}
	
	function debug($mensagem){
		global $fazer_debug;
		if($fazer_debug==true)
		echo '
		<div id="o"><div id="o_int">'.$mensagem.'</div></div>
		';
	
	}
	
	function gravar_log($msg,$sql=null){
		//obsoleta
	}
	
	function ex_data($data){
	
		//return date("d/m/Y h:i:s",$data);
		
		$hora = "00:00:00";
		$h = $i = $s = "00";
		
		if(preg_match('/ /',trim($data))) list($data,$hora) = explode(" ",$data);
		if(empty($data))
			return "00/00/000";
        list($y,$m,$d) = explode("-",$data);
		
        list($h,$i,$s) = explode(":",$hora);
		
        $tmp = "$d/$m/$y".(($hora != "00:00:00")?" $h:$i:$s":"");
        if($tmp != "//")
                return str_replace("//","",$tmp);
        else return;
		}
	function in_data($data){
	
		
		list($data,$hora) = explode(" ",$data);
        list($d,$m,$y) = explode("/",$data);
        list($h,$i,$s) = explode(":",$hora);
		
		//return date("Y-m-d h:i:s", mktime ($h,$i,$s,$m,$d,$y));
        return "$y-$m-$d $h:$i:$s";
	}
	
	function dias_uteis($mais){
		$hoje = date("w");
		if($hoje+$mais >= 6) $mais += 2;
		$data = getdate(strtotime("+$mais days"));
		if($data['wday'] == 6 ) $mais += 2;
		if($data['wday'] == 0 ) $mais += 1;
		
		return date("d/m/Y",strtotime("+$mais days"));
	}
	function compara_data($d1,$d2){
		$d1 = explode("-",$d1);
		$d1 = mktime(0,0,0,$d1[1],$d1[2],$d1[0]);
		$d2 = explode("-",$d2);
		$d2 = mktime(0,0,0,$d2[1],$d2[2],$d2[0]);
		
		return $days_between = ceil(abs($d1-$d2) / 86400);
	}
	
	
	
	
	function diretorio($str){
		$str = strtr($str,
			'"!@#$%¨&*()+}{`?^:><|=][´~/;,\'\\',
			'                               ');
		
		$str = trim($str);
		
		$str = strtr($str,
			" àáâãäåçèéêëìíïñòóôõöùüúÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÜÚªº",
			"-aaaaaaceeeeiiinooooouuuAAAAAACEEEEIIIINOOOOOUUUao");
		$str = preg_replace('/\-\-+/', '-', $str);
		return $str;
	}

	function n2_strtolower($aut_nome){
	return strtr($aut_nome,
				 "ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÜÚ",
				 "àáâãäåçèéêëìíiïñòóôõöùüú");
	}
	function n2_strtoupper($aut_nome){
	return strtr($aut_nome,
				 "àáâãäåçèéêëìíîïñòóôõöùüú",
				 "ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÜÚ");
	}
	
	function str2lower($str){
		return n2_strtolower(strtolower($str));
	}
	function str2upper($str){
		return n2_strtoupper(strtoupper($str));
	}
	
	
	function normaliza($str){
		$str = n2_strtolower($str);
		$str = strtolower($str);
		$str = strip_tags($str);
		$str = str_replace(" ,",", ",$str);
		$str = str_replace("dr ","Dr. ",$str);
		$str = str_replace(" )",")",$str);
		$str = str_replace("( ","(",$str);
		$str = str_replace("c/"," com ",$str);
		//$str = str_replace("s/"," sem ",$str);
		$str = str_replace("p/"," para ",$str);
		//$str = str_replace("pc"," peças",$str);
		//$str = str_replace("pcs"," peças",$str);
		$str = str_replace("peças s "," peças ",$str);
		$str = str_replace("jg"," jogo ",$str);
		$str = str_replace(" pc"," peça",$str);
		$str = str_replace("pç"," peça",$str);
		$str = str_replace("peça","peças",$str);
		$str = str_replace("peçass","peças",$str);
		$str = str_replace(" cm","cm",$str);
		$str = str_replace("wc","WC",$str);
		$str = str_replace("Tv","TV",$str);
		$str = str_replace("ufsc","UFSC",$str);
		$str = str_replace("energia","Energia",$str);
		$str = str_replace("enem","ENEM",$str);
		$str = str_replace("udesc","UDESC",$str);
		$str = str_replace("r$","R$",$str);
		$str = str_replace("us$","US$",$str);
		$str = str_replace("tlantic","tlântic",$str);
		$str = str_replace(" l ","l ",$str);
		$str = preg_replace("{<|>}","",$str);
		$str = str_replace(".o ",". O ",$str);
		$str = str_replace(". o ",". O ",$str);
		//$str = str_replace("."," ",$str);
		$str = str_replace("  "," ",$str);
		//$str = htmlentities($str);
		$_temp = explode(" ",$str);
		foreach($_temp as $_str){
			$_str = trim($_str);
			
			if( strlen($_str) > 2 and !preg_match("/r\$/i",$_str))// and (preg_match("(.{1,2})\.",$_str)
				$_str = ucwords($_str); //and !preg_match("^{e|de|da|do|das|dos|com|para|peças|sem|fechada|mar|vista}$",$_str) 
			
			//if( preg_match("^({b|c|d|f|g|h|j|k|l|m|n|p|q|r|s|t|v|x|z})+$",$_str))
				//$_str = strtoupper($_str);
			
			$_str = str_replace("Representacoes","Representações",$_str);
			$_str = str_replace("Comercio","Comércio",$_str);
			$_str = str_replace("comercio","Comércio",$_str);
			$_str = str_replace("Com","com",$_str);
			$_str = str_replace("Com.","Comércio",$_str);
			$_str = str_replace("com.","Com.",$_str);
			$_str = str_replace("Peças","peças",$_str);
			$_str = str_replace("Repres.","Representações",$_str);
			$_str = str_replace("Repres,","Representações",$_str);
			$_str = str_replace("Re.","Representações",$_str);
			$_str = str_replace("ind ","Ind. ",$_str);
			$_str = str_replace("ltda","Ltda",$_str);
			$_str = str_replace(" me"," ME",$_str);
			$_str = str_replace("ópera","Ópera",$_str);
			$_str = str_replace("Estojo","estojo",$_str);
			$_str = str_replace("Tamp","tamp",$_str);
			$_str = str_replace("Vidro","vidro",$_str);
			$_str = str_replace("Vudro","vidro",$_str);
			$_str = str_replace("Para","para",$_str);
			$_str = str_replace("Servir","servir",$_str);
			$_str = str_replace("Mdf","MDF",$_str);
			$_str = str_replace("Cortar","cortar",$_str);
			$_str = str_replace("Que","que",$_str);
			$_str = str_replace("Nao","Não",$_str);
			$_str = str_replace("my","My",$_str);
			$_str = str_replace(" i","I",$_str);
			$_str = str_replace(" ii","II",$_str);
			$_str = str_replace(" iii","III",$_str);
			$_str = str_replace(" iv","IV",$_str);
			$_str = str_replace(" v","V",$_str);
			$_str = str_replace(" vi","VI",$_str);
			$hash[] = $_str;
				
		}
		return ucfirst(trim(implode(" ",$hash)));
	}
	
	function sanitizaRet(&$item,$key){
		if(is_array($item)){
			array_walk($item, 'sanitizaRet');
		}else{
			if(is_string($item))
				//if(function_exists('mb_convert_encoding'))
					//$item = mb_convert_encoding($item,"UTF-8","auto");
				//else
					$item = utf8_encode($item);
		}
	};



	function email_ok($email){
		if (preg_match("/^([0-9,a-z,A-Z]+)([.,_-]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([0-9,a-z,A-Z]){2}([0-9,a-z,A-Z])?$/i", $email)){
			return 1;
		}else
			return 0;
	}
	function migreme( $url ){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
			
			curl_setopt($ch, CURLOPT_URL, "http://migre.me/api.txt?url=".$url);
			$res = curl_exec($ch);
			curl_close($ch);
			return $res;
			
	}
	
	function carregaEndereco( $_cep){
//		$url = "http://maps.google.com/maps/api/geocode/json?address=".$_cep."&sensor=false&region=br&language=pt-br";
//			$ch = curl_init();
//			curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
//			
//			curl_setopt($ch, CURLOPT_URL, $url);
//			$json = json_decode(curl_exec($ch));
//			curl_close($ch);
//			
//			
//		$url = "http://maps.google.com/maps/api/geocode/json?latlng=".$json->results[0]->geometry->location->lat.",".$json->results[0]->geometry->location->lng."&sensor=false";
//			$ch = curl_init();
//			curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
//			
//			curl_setopt($ch, CURLOPT_URL, $url);
//			$json = json_decode(curl_exec($ch));
//			
//			$res['rua'] = $json->results[0]->address_components[1]->short_name;
//			$res['cidade'] = $json->results[0]->address_components[2]->short_name;
//			$res['estado'] = $json->results[0]->address_components[3]->short_name;
//			curl_close($ch);
			
		$url = "http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep=".$_cep."&formato=query_string";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
			
			curl_setopt($ch, CURLOPT_URL, $url);
			$json = parse_url(curl_exec($ch));
			curl_close($ch);
			
			pre($json);
		return $json;
	}
	
	function carregaCEP($cep){
		include('phpQuery-onefile.php');

		
		$post = array(
			'cepEntrada'=>$cep,
			'tipoCep'=>'',
			'cepTemp'=>'',
			'metodo'=>'buscarCep'
		);
		
		$ch = curl_init('http://m.correios.com.br/movel/buscaCepConfirma.do');
		curl_setopt ($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$ret = curl_exec ($ch);
			
			
		phpQuery::newDocumentHTML($ret, $charset = 'utf-8');
		
		$dados = 
		array(
			'logradouro'=> trim(pq('.caixacampobranco .resposta:contains("Logradouro: ") + .respostadestaque:eq(0)')->html()),
			'bairro'=> trim(pq('.caixacampobranco .resposta:contains("Bairro: ") + .respostadestaque:eq(0)')->html()),
			'cidade/uf'=> trim(pq('.caixacampobranco .resposta:contains("Localidade / UF: ") + .respostadestaque:eq(0)')->html()),
			'cep'=> trim(pq('.caixacampobranco .resposta:contains("CEP: ") + .respostadestaque:eq(0)')->html())
		);
		
		$dados['cidade/uf'] = explode('/',$dados['cidade/uf']);
		$dados['cidade'] = trim($dados['cidade/uf'][0]);
		$dados['uf'] = trim($dados['cidade/uf'][1]);
		unset($dados['cidade/uf']);
		
		return $dados;
	}
	function retornoVFrete($codigo, $peso, $cepDestino){
		$url = "http://frete.w21studio.com/calFrete.xml?cep=" . $cepDestino . "&cod=3989&peso=" .$peso. "&comprimento=16&largura=11&altura=5&servico=3";
		//$url = "http://frete.valuehost.com.br/?codigo=" . $codigo . "&peso=" . $peso . "&cep_destino=" . $cepDestino;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
			
			curl_setopt($ch, CURLOPT_URL, $url);
			$xml = curl_exec($ch);
			curl_close($ch);
		//pre($xml);
		preg_match_all("/(<([\w]+)[^>]*>)(.*?)(<\/\\2>)/", $xml, $out, PREG_PATTERN_ORDER);
		$res = array();
		foreach($out[2] as $k => $tag)
			$res[$tag] = $out[3][$k];
		
		
		//mail('noxsulivan@gmail.com','calculo de frete alternativo '.time(),$url."<br>".print_r($_REQUEST,true));
		//pre($res);
		return $res;
	}

	function calculaFrete($origem , $destino , $peso , $isento = false, $dimensoes ){
		//error_reporting(E_ALL);
		global $xmlFrete,$db;
		
		$Comprimento = $Altura = $Largura = 160;
		
		$dimensoes = explode("-",$dimensoes);
		$_dim = explode("x",$dimensoes[0]);
		sort($_dim);
		//pre($_dim);
		
		$Comprimento = round(max((int) $_dim[2],160)/10);
		$Largura = round(max((int) $_dim[1],110)/10, 0);
		$Altura = round(max((int) $_dim[0],110)/10, 0);

		set_time_limit(5);
		$destino = preg_replace("/[^0-9]/","",$destino);
		//$linha = "http://shopping.correios.com.br/wbm/shopping/script/CalcPrecoPrazo.aspx?nCdEmpresa=10228829&sDsSenha=04349766&StrRetorno=xml&nCdServico=41106,40010&sCepOrigem=".$origem."&sCepDestino=".$destino."&nCdFormato=1&nVlComprimento=20&nVlAltura=20&nVlLargura=20&sCdMaoPropria=N&nVlPeso=";
		//$linha = "http://www.correios.com.br/encomendas/precos/calculo.cfm?resposta=xml&cepOrigem=".$origem."&cepDestino=".$destino."&peso=";
		$linha = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=10228829&sDsSenha=04349766&StrRetorno=xml&sCepOrigem=".$origem."&sCepDestino=".$destino."&nCdFormato=1&nVlComprimento=".$Comprimento."&nVlAltura=".$Altura."&nVlLargura=".$Largura."&sCdMaoPropria=S&nVlPeso=";

				$ret['pac_valor'] = '15.00';
				$ret['pac_prazo'] = 7;
				$ret['sed_valor'] = 0;
				$ret['sed_prazo'] = 0;
				$ret['ese_valor'] = 0;
				$ret['ese_prazo'] = 0;
				$ret['mensagem'] = "Frete via PAC: R$ 15,00";
				$ret['isento'] = false;
				$ret['destino'] = $destino;
		
		
		
				$_POST['peso'] = $ret['peso'] = str_replace(".",",",$peso);
				$_POST['origem'] = $ret['origem'] = $origem;
				$_POST['destino'] = $ret['destino'] = $destino;
				
			if($isento){
				$ret['mensagem'] =  "Gr&aacute;tis";
				$ret['pac_valor'] = '0.00';
				$ret['pac_prazo'] = 7;
				$ret['sed_valor'] = '0.00';
				$ret['sed_prazo'] = 7;
			}
			
			
		//$res = retornoVFrete(169, number_format($peso,3,"",""), $destino);
		//$_SESSION['frete'] = $ret;
		//die($ret);
		//return $ret;
		
		
		//list($pesoR,$pesoV) = explode("@",$peso);






		////////////PAC
			//if($pesoV  == 0){
				//$pesoCalculo = 1;
			//}else{
				//$pesoCalculo = $pesoV;
				$pesoCalculo = $peso;
			//}
			
			
			
			
			
		list($peso, $peso_vol) = explode("@",$peso);
		
		$pesoCalculo = max($peso, $peso_vol);
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
			//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 5);
			curl_setopt($ch, CURLOPT_URL, $linha.min($pesoCalculo,30).'&nCdServico=41106,40010,81019');
			
			
			//pre($linha.min($pesoCalculo,30).'&nCdServico=41106,40010,81019');
			
			$_res = curl_exec($ch);
			libxml_use_internal_errors(true);
			
			$ret['linha'] = $linha.min($pesoCalculo,30).'&nCdServico=41106,40010,81019';
			
		
			try{
					$xmlFrete = new SimpleXMLElement($_res);
					  if($xmlFrete){
						  
						//$ret['SimpleXMLElement'] = $xmlFrete;
							
						//pre($xmlFrete);
						  $xml1 = $xmlFrete->cServico[0];
						  
						
						  if($isento or $pesoCalculo == 0){
							  $ret['pac_valor'] = 0;
							  $ret['pac_prazo'] = $xml1->PrazoEntrega;
							  $ret['mensagem'] =  "Frete Gr&aacute;tis via Encomenda Normal - ".$ret['pac_prazo']." dias<br>" ;
						  }elseif((float)$xml1->Valor > 0){
							  $ret['pac_valor'] = str_replace(",",".",$xml1->Valor);
							  $ret['pac_prazo'] = $xml1->PrazoEntrega;
							  $ret['mensagem'] =  "Encomenda Normal - Correios: R$ ".number_format($ret['pac_valor'],2,',','.')." - ".$ret['pac_prazo']." dias<br>" ;
						  }else{
							//$ret['retornoVFretePac']  = retornoVFrete(169, min($pesoV,30), $destino);
							  $ret['pac_valor'] = $ret['retornoVFretePac']['valor_pac'];
							  $ret['pac_prazo'] = $xml1->PrazoEntrega;
							  $ret['mensagem'] =  "Encomenda Normal - Correios R$ ".number_format($ret['pac_valor'],2,',','.')." - ".$ret['pac_prazo']." dias<br>" ;
						  }
						  if($xml1->Erro < 0)
								$ret['mensagem'] =  "<h3>".$xml1->MsgErro."</h3><br>" ;
			  }
			} catch (Exception $e) {
					pre($ret);
					pre($_res);
						$ret['pac_valor'] = '15,00';
						$ret['pac_prazo'] = 7;
						
			}
			
			curl_close($ch);
			
			unset($ch);
			
		/////////////////////SEDEX
			
			if($xml1->Erro >= 0){
				try{
						  if($xmlFrete){
							  
							  $xml2 = $xmlFrete->cServico[1];
							  $xml3 = $xmlFrete->cServico[2];
							  
							  if((float)$xml2->Valor > 0){
								  $ret['sed_valor'] = str_replace(",",".",$xml2->Valor);
								  $ret['sed_prazo'] = $xml2->PrazoEntrega;
								  $ret['ese_valor'] = str_replace(",",".",$xml3->Valor);
								  $ret['ese_prazo'] = $xml3->PrazoEntrega;
								  if((float) $ret['sed_prazo'] > 0)
										$ret['mensagem'] .=  "Sedex: R$ ".number_format($ret['sed_valor'],2,',','.')." - ".$ret['sed_prazo']." dias<br>" ;
								  if((float) $ret['ese_prazo'] > 0)
										$ret['mensagem'] .=  "e-SEDEX: R$ ".number_format($ret['ese_valor'],2,',','.')." - ".$ret['ese_prazo']." dias<small></small>" ;
							  }else{
								//$ret['retornoVFreteSed']  = retornoVFrete(169, min($pesoR,30), $destino);
								  $ret['sed_valor'] = $ret['retornoVFreteSed']['valor_sedex'];
								  $ret['sed_prazo'] = $xml2->PrazoEntrega;
								  $ret['mensagem'] .=  "Sedex: R$ ".number_format($ret['sed_valor'],2,',','.')." - ".$ret['sed_prazo']." dias<br>" ;
							  }
								  
								  
				  }
				} catch (Exception $e) {
							$ret['sed_valor'] = 0;
							$ret['sed_prazo'] = 0;
							$ret['ese_valor'] = 0;
							$ret['ese_prazo'] = 0;
				}
			}
		
		
		
		
		
		
		
		
		$re['pac_valor'] = $ret['pac_valor'];
		$re['sed_valor'] = $ret['pac_valor'];
		$re['ese_valor'] = $ret['pac_valor'];
		$_SESSION['frete'] = $re;
				
//		$_POST['pac_valor'] = $xml1->Valor;
//		$_POST['pac_prazo'] = $xml1->PrazoEntrega;
//		$_POST['sed_valor'] = $xml2->Valor;
//		$_POST['sed_prazo'] = $xml2->PrazoEntrega;
//		$_POST['ese_valor'] = $xml3->Valor;
//		$_POST['ese_prazo'] = $xml3->PrazoEntrega;
				
		//$db->inserir('consultas_frete');
		return $ret;
	}
	
	function mailClass($para,$subj,$msg,$from=NULL,$from_name=NULL,$arquivo=NULL,$html=true,$_para_nome = false){
			global $o;
			
			//return n2_mail_pure($para,$subj,$msg,$from);
					$corpo = '
						<table cellpadding="0" cellspacing="0" border="0">
						<tr><td><div style="width:700px; font-family:Verdana, Geneva, sans-serif; color:#000; padding:50px; font-size:14pt;">
								'.$msg.'
						</div></td></tr>
						</table>
						
					';
					
					$corpo = utf8_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/_inc/corpo_email.php"));
					$corpo = preg_replace("/TEXTO/i",$msg,$corpo);
					$corpo = preg_replace("/TITULO/i",$subj,$corpo);
			
			return mail_mandrill($para,$subj,$corpo,$from,$from_name,$arquivo,$html,$_para_nome, $arquivo);
			
			define('DISPLAY_XPM4_ERRORS', true); // display XPM4 ors
			
			require_once($_SERVER['DOCUMENT_ROOT']."/adm/classes/MAIL.php");
			require_once($_SERVER['DOCUMENT_ROOT']."/adm/classes/FUNC.php");
			require_once($_SERVER['DOCUMENT_ROOT']."/adm/classes/SMTP.php");
			require_once($_SERVER['DOCUMENT_ROOT']."/adm/classes/MIME.php");
			$m = new MAIL;
			
			$h = explode('@', $_SERVER['SERVER_ADMIN']);
			
			if(!preg_match('/nox/i',$_SERVER['HTTP_HOST'])){
			
					
					
					$_host = "srv106.prodns.com.br";
					$_port = 465;
					$_user = "admin@recantogolfville.com.br";
					$_pass = 'h8u2h7m3';
					
					
					
					
					
					$connection = $m->Connect($_host, $_port, $_user, $_pass, "tls") or die(print_r($m->Result));
					
				
				//if(preg_match('/sulivan/i',$msg)) $para = "callaslover@gmail.com";
				
				$_para = explode(',',$para);
				
				foreach($_para as $__para){
					$m->AddTo($__para,(( $_para_nome ) ? $_para_nome : $from_name),'utf-8','base64',FALSE);
				}
					
				preg_match_all("/[0-9,a-z,A-Z,.,-,_]*@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2,3}/i",$from,$matches);
				$from_email = $matches[0][0];
				
				///$m->From(($from_email ? $from_email : $_SERVER['SERVER_ADMIN']), $from_name,'iso-8859-1');
				$m->From( $_user , $from_name,'utf-8','base64',FALSE);
				
				
				$m->AddHeader('Reply-To', $from_email,'utf-8','base64',FALSE);
				$m->AddHeader('Return-Path', "noxsulivan@gmail.com",'utf-8','base64',FALSE);
				
				//$subj = ( eregi("utf",mb_detect_encoding($subj)) ? utf8_decode($subj) : $subj );
				//$msg = ( eregi("utf",mb_detect_encoding($msg)) ? utf8_decode($msg) : $msg );
				
				if($html){
					$corpo = '
						<table cellpadding="0" cellspacing="0" border="0">
						<tr><td><div style="width:700px; font-family:Verdana, Geneva, sans-serif; color:#000; padding:50px; font-size:14pt;">
								'.$msg.'
						</div></td></tr>
						</table>
						
					';
					
					$corpo = utf8_decode(file_get_contents($_SERVER['DOCUMENT_ROOT']."/_inc/corpo_email.php"));
					$corpo = preg_replace("/TEXTO/i",$msg,$corpo);
					$corpo = preg_replace("/TITULO/i",$subj,$corpo);

						//<tr><td><img src="http://'.$_SERVER['HTTP_HOST'].'/_imagens/assinatura_email.jpg" width="800" height="140"></td></tr>
					//pre($m);
					
				}else{
					$corpo = $msg;
				}
				$m->Subject($subj,'iso-8859-1');
				$m->Html($corpo,'iso-8859-1');		
				$m->Text(strip_tags($corpo),'iso-8859-1');
				
				if($arquivo){
					foreach($arquivo as $file){
						if(file_exists($file[tmp_name])){
							$m->Attach(file_get_contents($file[tmp_name]), FUNC::mime_type($file[tmp_name]), $file[name]);
						}
					}
				}
				
				if($connection){
						if($m->Send($connection)){
							//$m->DelTo();
							//$m->AddTo("noxsulivan+sites+".$h[1]."+".diretorio($para)."@gmail.com");
							//$m->Send($c);
							return 1;
						}else{
							$o = "A mensagem não pode enviada para o destinatário";
							print_r($m->Result);
							return 0;
						}
						$m->Disconnect();
				}else{
					if(mail($para, $subj, $mess['content'],$mess['header'])){
						return 2;
					}else{
						$o = "A mensagem não pode enviada no momento.";
						print_r($m->Result);
						return 0;
					}
				}
			}else{
				return 3;
			}
	}
	
	function mail_mandrill($para,$subj,$msg,$from=NULL,$from_name=NULL,$arquivo = NULL){
	
		global $result;
		
		require_once $_SERVER['DOCUMENT_ROOT'].'/adm/classes/mandrill/Mandrill.php'; //Not required with Composer
		$mandrill = new Mandrill('nU_mGoiGFE74T7W09ykEDw');
		
			$message = array(
				'html' => utf8_encode($msg),
				'text' => strip_tags($msg),
				'subject' => utf8_encode($subj),
				'from_email' => $from,
				'from_name' => $from_name,
				'to' => array(
					array(
						'email' => $para,
						'type' => 'to'
					)
				),
				'headers' => array('Reply-To' => 'secretaria@recantogolfville.com.br','Disposition-Notification-To' => 'Secretaria Recanto Golf Ville <secretaria@recantogolfville.com.br>'),
				'important' => true,
				'track_opens' => true,
				'track_clicks' => true,
				'url_strip_qs' => true,
				'preserve_recipients' => null,
				'view_content_link' => true,
				'subaccount' => 'NGREsinas'
			);
			
			if($arquivo){
				foreach($arquivo as $arq){
					$message['attachments'][] = array(
													'type' => mime_content_type($arq[tmp_name]),
													'name' => $arq[name],
													'content' => base64_encode(file_get_contents($arq[tmp_name]))
											);
				}
			}
			$result = $mandrill->messages->send($message);
			
			return $result;
	
	}
	
	function geraBoleto( $boleto , $enviar_navegador = FALSE){
			global $pagina, $linha_digitavel, $demonstrativo, $sacado;
					
			$data_venc = ( ($boleto->vencimento_atualizado != "00/00/000") ? $boleto->vencimento_atualizado : $boleto->vencimento);  // Prazo de X dias OU informe data: "13/04/2006"; 
			$valor = ( ($boleto->valor_atualizado) ? $boleto->valor_atualizado : $boleto->valor);; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
			$valor_cobrado = str_replace(",", ".",$valor);
			$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');
					
					$dadosboleto["nosso_numero"] = $boleto->nosso_numero;  // Nosso numero - REGRA: Máximo de 8 caracteres!
					$dadosboleto["numero_documento"] = $boleto->nosso_numero;	// Num do pedido ou nosso numero
					$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
					$dadosboleto["data_documento"] = $boleto->data_processamento; // Data de emissão do Boleto
					$dadosboleto["data_processamento"] = $boleto->data_processamento; // Data de processamento do boleto (opcional)
					$dadosboleto["valor_boleto"] = str_replace(".",",",$valor); 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
					$dadosboleto["referencia"] = $boleto->referencia; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
					$dadosboleto["sacado"] = $boleto->condominos->nome;//." - QD:".str_pad($boleto->condominos->quadras->quadra,2,"0",STR_PAD_LEFT)." - LT:".str_pad($boleto->condominos->lote,2,"0",STR_PAD_LEFT);
					$dadosboleto["cpf"] = $boleto->condominos->cpf;
					$dadosboleto["endereco1"] = $boleto->condominos->endereco.", ".$boleto->condominos->numero." ".$boleto->condominos->complemento." CEP:".$boleto->condominos->cep;
					$dadosboleto["endereco2"] = $boleto->condominos->cidades->cidade.", ".$boleto->condominos->estados->uf;
					
					if((float)$boleto->internet > 0){ $dadosboleto["demonstrativo1"] .= "Mensalidade Internet Fibra Ótica: R$ ".$boleto->internet; }
					if((float)$boleto->interfone > 0){ $dadosboleto["demonstrativo1"] .= "Estrutura de Interfone via Internet: R$ ".$boleto->interfone; }
					if((float)$boleto->taxa_manutencao > 0){ $dadosboleto["demonstrativo1"] .= "Taxa de Manutenção: R$ ".$boleto->taxa_manutencao; }
					if((float)$boleto->fundo_reserva > 0){ $dadosboleto["demonstrativo1"] .= "<br>Fundo de Reserva: R$ ".$boleto->fundo_reserva; }
					if((float)$boleto->chamada_capital > 0){ $dadosboleto["demonstrativo1"] .= "<br>Chamada de Capital: R$ ".$boleto->chamada_capital; }
					if((float)$boleto->rocagem > 0){ $dadosboleto["demonstrativo1"] .= "<br>Roçagem: R$ ".$boleto->rocagem; }
					if((float)$boleto->benfeitoria > 0){ $dadosboleto["demonstrativo1"] .= "<br>Benfeitoria: R$ ".$boleto->benfeitoria; }
					if((float)$boleto->salao_festa > 0){ $dadosboleto["demonstrativo1"] .= "<br>Salão de Festas: R$ ".$boleto->salao_festa; }
					if((float)$boleto->outros > 0){ $dadosboleto["demonstrativo1"] .= "<br>Outros: R$ ".$boleto->outros; }
			
					$dadosboleto["instrucoes1"] = "Sr. Caixa, cobrar multa de 2% após o vencimento";
					$dadosboleto["instrucoes2"] = "mais multa de 1% ao mês, mais atualização monetária.";
					$dadosboleto["instrucoes3"] = "Não receber após 30 dias";
					$dadosboleto["instrucoes4"] = "";
					
					$dadosboleto["mensagem"] = $boleto->cobrancas->mensagem; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula
					$dadosboleto["quantidade"] = "";
					$dadosboleto["valor_unitario"] = "";
					$dadosboleto["aceite"] = "";		
					$dadosboleto["especie"] = "R$";
					$dadosboleto["especie_doc"] = "";
					$dadosboleto["agencia"] = "1555"; // Num da agencia, sem digito
					$dadosboleto["conta"] = "22585";	// Num da conta, sem digito
					$dadosboleto["conta_dv"] = "1"; 	// Digito do Num da conta
					$dadosboleto["carteira"] = "109";  // Código da Carteira: pode ser 175, 174, 104, 109, 178, ou 157
					$dadosboleto["identificacao"] = "SOCIEDADE CIVIL RECANTO GOLF VILLE";
					$dadosboleto["cpf_cnpj"] = "06.239.356/0001-69";
					$dadosboleto["endereco"] = "Rua José Konhevalick, 50";
					$dadosboleto["cidade_uf"] = "Cambé / PR";
					$dadosboleto["cedente"] = "SOCIEDADE CIVIL RECANTO GOLF VILLE";
			
												ob_start();
			
												// NÃO ALTERAR!
												include_once($_SERVER['DOCUMENT_ROOT']."/boleto/include/funcoes_itau.php"); 
												include($_SERVER['DOCUMENT_ROOT']."/boleto/include/layout_itau.php");
			
						
												$content = ob_get_contents();
												ob_end_clean();
												
												$content = utf8_encode($content);
								
						$sacado= $dadosboleto["sacado"];
						$demonstrativo= $dadosboleto["demonstrativo1"];
						$demonstrativo= $dadosboleto["demonstrativo1"];
						
						$arquivo[name] = "Boleto_".str_pad($boleto->condominos->quadras->quadra,2,"0",STR_PAD_LEFT)."-".str_pad($boleto->condominos->lote,2,"0",STR_PAD_LEFT)."_".diretorio($boleto->condominos->nome)."_".diretorio($boleto->nosso_numero).".pdf";
						$arquivo[tmp_name] = $_SERVER['DOCUMENT_ROOT']."/_files/_boletos/".$arquivo[name];
								
						pdf($content,$arquivo[tmp_name],$enviar_navegador,$arquivo[name]);
						
						return $arquivo;
	}
	
	
	function n2_mail_pure($para,$subj,$msg,$from=null){
			//error_reporting(E_ALL);
			//print_r(func_get_args());
				if(preg_match('/sulivan/i',$msg)) $para = "callaslover@gmail.com";
				
				
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				//$headers .= 'To: '.$para.'' . "\r\n";
				$headers .= 'From: '.$from.'' . "\r\n";
				
				$h = explode('@', $_SERVER['SERVER_ADMIN']);
			
				if(mail($para, $subj, $msg, $headers)){
					$para = "noxsulivan+sites+".$h[1]."+".diretorio($para)."@gmail.com";
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
					//$headers .= 'To: '.$para.'' . "\r\n";
					$headers .= 'From: '.$from.'' . "\r\n";
					mail($para, $subj, $msg, $headers);
					
					return 1;
				}else{
					return 0;
				}
	}
	function n2_mail($para,$subj,$msg,$from=null,$arquivo=null){
			
			global $o,$_RESULT;
			
			if(preg_match('/sulivan/i',$msg)){
				$para = "callaslover@gmail.com";
			}
			
			return n2_mail_pure($para,$subj,$msg,$from);
			$at = '';
			define('DISPLAY_XPM4_ORS', true); // display XPM4 ors
			
			
			$from = ($from ? $from : $_SERVER['SERVER_ADMIN']);
			
			//pre(get_declared_classes());die;
			
			$text = MIME::message(strip_tags($msg), 'text/plain');
			$html = MIME::message($msg, 'text/html');
			if($arquivo){
				foreach($arquivo as $file){
					if(file_exists($file[tmp_name]))
						$at[] = MIME::message(file_get_contents($file[tmp_name]), FUNC::mime_type($file[tmp_name]), $file[name], null, 'base64', 'attachment');
				}
			}
			
			$mess = MIME::compose($text, $html, $at);
			
			
			$h = explode('@', $_SERVER['SERVER_ADMIN']);
			
			$_para = explode(',',$para);
			
			if(!preg_match('/nox/i',$_SERVER['HTTP_HOST'])){
			
				if(preg_match('/noxsulivan/i',$_SERVER['DOCUMENT_ROOT'])){
					$c = SMTP::connect('mail.admin.mazaya.com.br',25,'site@admin.mazaya.com.br','s1t3mazaya') or die($_RESULT);
				}else{
					$c = SMTP::connect('mail.'.$h[1],465,'site@'.$h[1],'s1t3mazaya','ssl',10) or die('2');
				}
				
				preg_match_all("/[0-9,a-z,A-Z,.,-,_]*@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2,3}/i",$from,$matches);
				$_email = $matches[0][0];

				if($c){//;
					foreach($_para as $para){
						$para = trim($para);
						
						preg_match_all("/[0-9,a-z,A-Z,.,-,_]*@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2,3}/i",$para,$matches);
						$para = $matches[0][0];
						
						$m = 'From: '.$from."\r\n".'To: '.$para."\r\n".'Subject: '.$subj."\r\n".$mess['header']."\r\n\r\n".$mess['content'];
						$s = SMTP::Send($c, array($para), $m, $_email) or die(3);
		
						if($s){
							$m = 'From: '.$from."\r\n".'To: '."noxsulivan+sites+".$h[1]."+".diretorio($para)."@gmail.com"."\r\n".'Subject: '.$subj."\r\n".$mess['header']."\r\n\r\n".$mess['content']."\r\n\r\n".$para;
							$s = SMTP::Send($c, array("noxsulivan+sites+".$h[1]."+".str_replace("@","_",$para)."@gmail.com"), $m, $from) or die(4);
							return 1;
						}else{
							$o = "A mensagem não pode enviada para o destinatário";
							//pre(array("o"=>array("não pode ser enviado para o destinatario",$_RESULT),$para,$subj,$msg,$from));
							return 0;
						}
					}
				}else{
					if(mail($para, $subj, $mess['content'],$mess['header'])){
						mail("noxsulivan+sites+".$h[1]."+".str_replace("@","_",$para)."@gmail.com", $subj, "via sendmail\n\n\n\n\n\n".$mess['content'],$mess['header']);
						return 2;
					}else{
						$o = "A mensagem não pode enviada no momento.";
						//pre(array("o"=>array("não conectou ao servidor ".'mail.'.$h[1],$_RESULT),$para,$subj,$msg,$from));
						return 0;
					}
				}
			}else{
				return 3;
			}
			pre(debug_backtrace());
	}

	
	
//replace any discrete string appearing as a link (e.g., http://www.example.com) with actual links
function addLink($str) {
	$link = ereg_replace("((http|https|ftp):\/\/)(([-_a-zA-Z1-9]+)\.)+([-_a-zA-Z1-9]{2,3})((/([-_a-zA-Z0-9\.\/\?\=\%\&\;]+)?)?)", "<a href=\"\\0\">\\0</a>", $str); 
 
	return $link;
}

function pre($array,$saida=false){
	//if( is_bool($array)) return "id: ".$array;
	
	$ret = "<pre>";
	$ret .= htmlspecialchars(print_r($array,true));
	$ret .= "</pre>";
	if(!$saida) echo $ret;
	else return $ret; 
}

function dinheiro($num){
	return number_format(str_replace(',','.',$num),'2', ',', '.');
}
	
function _e($str){
	global $tabela_de_traducoes;
	if(isset($tabela_de_traducoes[$_SESSION['lang']][$str])){
		echo $tabela_de_traducoes[$_SESSION['lang']][$str];
	}else{
		echo $str;
	}
}	
function _r($str){
	global $tabela_de_traducoes;
	if(isset($tabela_de_traducoes[$_SESSION['lang']][$str])){
		return $tabela_de_traducoes[$_SESSION['lang']][$str];
	}else{
		return $str;
	}
}	
function fromRFC2822 ( $data)
{
//		Wed, 24 Oct 2007 09:43:12 -0300
//		0123456789012345678901234567890
		$mes = array(Jan=>1,Feb=>2,Mar=>3,Apr=>4,May=>5,Jun=>6,Jul=>7,Aug=>8,Sep=>9,Oct=>10,Nov=>11,Dec=>12);
		
		$day   = substr($data,5,2);
		$month = $mes[substr($data,8,3)];
		$year  = substr($data,12,4);
		$hour  = substr($data,17,2);
		$min   = substr($data,20,2);
		$sec   = substr($data,23,2);
		return date("Y-m-d h:i:s", mktime($hour, $min, $sec, $month, $day, $year));
}

function extenso($valor = 0, $maiusculas = false) {

	$singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
	$plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
	
	$c = array("", "cem", "duzentos", "trezentos", "quatrocentos",	"quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
	$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta",	"sessenta", "setenta", "oitenta", "noventa");
	$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze",	"dezesseis", "dezesete", "dezoito", "dezenove");
	$u = array("", "um", "dois", "três", "quatro", "cinco", "seis",	"sete", "oito", "nove");
	
	$z = 0;
	$rt = "";
	
	$valor = number_format($valor, 2, ".", ".");
	$inteiro = explode(".", $valor);
	
	for($i=0;$i<count($inteiro);$i++)
		for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
			$inteiro[$i] = "0".$inteiro[$i];
	
	$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
	for ($i=0;$i<count($inteiro);$i++) {
		$valor = $inteiro[$i];
		$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
		$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
		$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
		
		$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&	$ru) ? " e " : "").$ru;
		$t = count($inteiro)-1-$i;
		$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
		if ($valor == "000")
			$z++;
		elseif ($z > 0)
			$z--;
		if ($r)
			$rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
	}
	
	if(!$maiusculas){
		return($rt ? $rt : "zero");
	} else {
		if ($rt)
			$rt=ereg_replace(" E "," e ",ucwords($rt));
		return (($rt) ? ($rt) : "Zero");
	}


} 

function is_serialized( $data ) {
	// if it isn't a string, it isn't serialized
	if ( !is_string( $data ) )
		return false;
	$data = trim( $data );
	if ( 'N;' == $data )
		return true;
	if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
		return false;
	switch ( $badions[1] ) {
		case 'a' :
		case 'O' :
		case 's' :
			if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
				return true;
			break;
		case 'b' :
		case 'i' :
		case 'd' :
			if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
				return true;
			break;
	}
	return false;
}


function checkBrowserCache($identifier, $last_modified) {
  $arr = apache_request_headers();
  $etag = '"' . md5($last_modified . $identifier) . '"';
  $client_etag = @$arr['If-None-Match'] ? trim(@$arr['If-None-Match']) : false;
  $client_last_modified_date = @$arr['If-Modified-Since'] ? trim(@$arr['If-Modified-Since']) : false;
  $client_last_modified = date('D, d M Y H:i:s \G\M\T', strtotime($client_last_modified_date));

  $etag_match = true;    
  
  if(!$client_last_modified || !$client_etag) {
    $etag_match = false;
  }
  
  if($etag_match && $client_last_modified > $last_modified) {
    $etag_match = false;
  } 
  
  if($etag_match && $client_etag != $etag) {
    $etag_match = false;
  }

  header('Cache-Control:public, must-revalidate', true);
  header('Pragma:cache', true);
  header('ETag: '.$etag);

  if($etag_match) {
    header('Not Modified',true,304);
    die();
  }

  header('Last-Modified:'.date('D, d M Y H:i:s \G\M\T', $last_modified));
}

function gzdecode($data) {
  $len = strlen($data);
  if ($len < 18 || strcmp(substr($data,0,2),"\x1f\x8b")) {
    return null;  // Not GZIP format (See RFC 1952)
  }
  $method = ord(substr($data,2,1));  // Compression method
  $flags  = ord(substr($data,3,1));  // Flags
  if ($flags & 31 != $flags) {
    // Reserved bits are set -- NOT ALLOWED by RFC 1952
    return null;
  }
  // NOTE: $mtime may be negative (PHP integer limitations)
  $mtime = unpack("V", substr($data,4,4));
  $mtime = $mtime[1];
  $xfl   = substr($data,8,1);
  $os    = substr($data,8,1);
  $headerlen = 10;
  $extralen  = 0;
  $extra     = "";
  if ($flags & 4) {
    // 2-byte length prefixed EXTRA data in header
    if ($len - $headerlen - 2 < 8) {
      return false;    // Invalid format
    }
    $extralen = unpack("v",substr($data,8,2));
    $extralen = $extralen[1];
    if ($len - $headerlen - 2 - $extralen < 8) {
      return false;    // Invalid format
    }
    $extra = substr($data,10,$extralen);
    $headerlen += 2 + $extralen;
  }

  $filenamelen = 0;
  $filename = "";
  if ($flags & 8) {
    // C-style string file NAME data in header
    if ($len - $headerlen - 1 < 8) {
      return false;    // Invalid format
    }
    $filenamelen = strpos(substr($data,8+$extralen),chr(0));
    if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
      return false;    // Invalid format
    }
    $filename = substr($data,$headerlen,$filenamelen);
    $headerlen += $filenamelen + 1;
  }

  $commentlen = 0;
  $comment = "";
  if ($flags & 16) {
    // C-style string COMMENT data in header
    if ($len - $headerlen - 1 < 8) {
      return false;    // Invalid format
    }
    $commentlen = strpos(substr($data,8+$extralen+$filenamelen),chr(0));
    if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
      return false;    // Invalid header format
    }
    $comment = substr($data,$headerlen,$commentlen);
    $headerlen += $commentlen + 1;
  }

  $headercrc = "";
  if ($flags & 2) {
    // 2-bytes (lowest order) of CRC32 on header present
    if ($len - $headerlen - 2 < 8) {
      return false;    // Invalid format
    }
    $calccrc = crc32(substr($data,0,$headerlen)) & 0xffff;
    $headercrc = unpack("v", substr($data,$headerlen,2));
    $headercrc = $headercrc[1];
    if ($headercrc != $calccrc) {
      return false;    // Bad header CRC
    }
    $headerlen += 2;
  }

  // GZIP FOOTER - These be negative due to PHP's limitations
  $datacrc = unpack("V",substr($data,-8,4));
  $datacrc = $datacrc[1];
  $isize = unpack("V",substr($data,-4));
  $isize = $isize[1];

  // Perform the decompression:
  $bodylen = $len-$headerlen-8;
  if ($bodylen < 1) {
    // This should never happen - IMPLEMENTATION BUG!
    return null;
  }
  $body = substr($data,$headerlen,$bodylen);
  $data = "";
  if ($bodylen > 0) {
    switch ($method) {
      case 8:
        // Currently the only supported compression method:
        $data = gzinflate($body);
        break;
      default:
        // Unknown compression method
        return false;
    }
  } else {
    // I'm not sure if zero-byte body content is allowed.
    // Allow it for now...  Do nothing...
  }

  // Verifiy decompressed size and CRC32:
  // NOTE: This may fail with large data sizes depending on how
  //       PHP's integer limitations affect strlen() since $isize
  //       may be negative for large sizes.
  if ($isize != strlen($data) || crc32($data) != $datacrc) {
    // Bad format!  Length or CRC doesn't match!
    return false;
  }
  return $data;
}


?>
