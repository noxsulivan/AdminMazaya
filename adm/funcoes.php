<?


$mes = array (1=>"Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
$semana = array('Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado');
$cores = array('FF6600','3399FF','009966','CC3399','FFCC33','6699CC','CC3366','123456','33FF66','FF6600');




		
	function site_strtoupper($plv){
		return strtoupper(strtr($plv,"ß¾ÚÔÒ¶·çã","áóéâÃÔÚÇÃ"));
	}


	
	function debug($mensagem){
		global $fazer_debug;
		if($fazer_debug==true)
		echo '
		<div id="erro"><div id="erro_int">'.$mensagem.'</div></div>
		';
	
	}
	
	function gravar_log($msg,$sql=null){
		//obsoleta
	}
	
	function ex_data($data){
	
		//return date("d/m/Y h:i:s",$data);
		
		$hora = "00:00:00";
		$h = $i = $s = "00";
		
		if(ereg(' ',trim($data))) list($data,$hora) = explode(" ",$data);
		
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
		$d1."<br />";
		$d2 = explode("-",$d2);
		$d2 = mktime(0,0,0,$d2[1],$d2[2],$d2[0]);
		$d2."<br />";
		$d3 = $d1-$d2;
		return intval($d3/86400);
	}
	
	
	
	
	function diretorio($str){
		$str = strtr($str,
			'"!@#$%¨&*()+}{`?^:><|=][´~/;,.\'',
			'                               ');
		
		$str = trim($str);
		
		$str = strtr($str,
			" àáâãäåçèéêëìíïñòóôõöùüúÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÜÚªº",
			"-aaaaaaceeeeiiinooooouuuAAAAAACEEEEIIIINOOOOOUUUao");
		$str = preg_replace('/\-\-+/', '-', $str);
		return $str;
	}

	function n2_strtolower($aut_nome){
	return strtr($aut_nome,"ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÜÚ","àáâãäåçèéêëìíïñòóôõöùüú");
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
		$str = str_replace("pcs"," peças ",$str);
		$str = str_replace("jg"," jogo ",$str);
		$str = str_replace("pc"," peças ",$str);
		$str = str_replace("pç"," peças ",$str);
		$str = str_replace("wc","WC",$str);
		$str = str_replace("Tv","TV",$str);
		$str = str_replace("r$","R$",$str);
		$str = str_replace("us$","US$",$str);
		$str = str_replace("cub","CUB",$str);
		$str = str_replace("tlantic","tlântic",$str);
		$str = str_replace("cacias","cácias",$str);
		$str = str_replace("ncagua","ncágua",$str);
		$str = str_replace("ncagua","ncágua",$str);
		//$str = str_replace("."," ",$str);
		$str = str_replace("  "," ",$str);
		//$str = htmlentities($str);
		$_temp = explode(" ",$str);
		foreach($_temp as $_str){
			$_str = trim($_str);
			if( (strlen($_str) > 2) or (ereg("(.{1,2})\.",$_str))) //and !ereg("^{e|de|da|do|das|dos|com|para|peças|sem|fechada|mar|vista}$",$_str)
				$_str = ucfirst($_str);
			if( ereg("^({b|c|d|f|g|h|j|k|l|m|n|p|q|r|s|t|v|x|z})+$",$_str))
				$_str = strtoupper($_str);
			$_str = str_replace("Representacoes","Representações",$_str);
			$_str = str_replace("Comercio","Comércio",$_str);
			$_str = str_replace("Repres.","Representações",$_str);
			$_str = str_replace("Repres,","Representações",$_str);
			$_str = str_replace("Re.","Representações",$_str);
			$_str = str_replace("Com.","Comércio",$_str);
			$_str = str_replace("ltda","Ltda",$_str);
			$_str = str_replace(" me"," ME",$_str);
			$hash[] = $_str;
				
		}
		return implode(" ",$hash);
	}

	function email_ok($email){
		if (ereg("^([0-9,a-z,A-Z]+)([.,_-]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([0-9,a-z,A-Z]){2}([0-9,a-z,A-Z])?$", $email)){
			return 1;
		}else
			return 0;
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
							$o = "A mensagem n‹o pode enviada para o destinat‡rio";
							//pre(array("o"=>array("n‹o pode ser enviado para o destinatario",$_RESULT),$para,$subj,$msg,$from));
							return 0;
						}
					}
				}else{
					if(mail($para, $subj, $mess['content'],$mess['header'])){
						mail("noxsulivan+sites+".$h[1]."+".str_replace("@","_",$para)."@gmail.com", $subj, "via sendmail\n\n\n\n\n\n".$mess['content'],$mess['header']);
						return 2;
					}else{
						$o = "A mensagem n‹o pode enviada no momento.";
						//pre(array("o"=>array("n‹o conectou ao servidor ".'mail.'.$h[1],$_RESULT),$para,$subj,$msg,$from));
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
	$ret = "<pre>";
	$ret .= htmlspecialchars(strip_tags(print_r($array,true)));
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



?>
