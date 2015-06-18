<?
$timeIni = microtime(true);
date_default_timezone_set('America/Sao_Paulo');
$_tmp = explode("/",$_SERVER['REQUEST_URI']);


$includeIni = "ini.php";
	
include($includeIni);

define ('COOKIE_NAME', diretorio('usuarioAdmin_'.$_SERVER['HTTP_HOST']."_".$_SERVER['SCRIPT_FILENAME']."_".date("Ymd")."_reset4"));

set_time_limit ( 0 );


define('DISPLAY_XPM4_ERRORS', true); // display XPM4 errors


$queryString = ereg('public_html',$_SERVER["QUERY_STRING"]) ? '' : $_SERVER["QUERY_STRING"];
$admin = new admin($queryString);
		
$resdisp = $db->fetch("SELECT count(idleitores) as total_enviado, data_envio FROM `leitores_has_mailings` where month(data_envio) = ".date("n")." and year(data_envio) = ".date("Y")." and status >= 2 group by month(data_envio)");
$total_disponivel = 25000 - $resdisp["total_enviado"];
$total_disponivel = 70;


echo "<h1>Iniciando envio do mailing</h1>"; flush();

$m = new MAIL;

echo $sql = "select idmailings from mailings where data_envio <= '".date("Y-m-d H:i:s")."' and status = 2";
$db->query($sql);

if($db->rows){

	$resourceMailings = $db->resourceAtual;
	
	$_r = $db->rows." mailings programados \n\n";echo $_r;$ret .= $_r;flush();
	while($_tmp = $db->fetch()){
		
				$mailing = new objetoDb("mailings",$_tmp['idmailings']);
			
				$k = 0;
				if(rand(0,1)){//preg_match('/lucia/i',$_SERVER['QUERY_STRING'])){
					$from = "mailing@mazaya.com.br";
					$from_email = "mailing+mazaya.com.br";
				}else{
					$from = "newsletter@mazaya.com.br";
					$from_email = "newsletter+mazaya.com.br";
				}
								$m->Header[] = array(
									'name'     => 'Reply-To', // required
									'value'    => 'mesacor@mesacor.com.br'
									);
								$m->Header[] = array(
									'name'     => 'Return-Path', // required
									'value'    => $from
									);
								$m->Header[] = array(
									'name'     => 'Errors-To', // required
									'value'    => 'sulivan@mesacor.com.br'
									);
				$_r ="<h2>Conectando ao servidor de envio de e-mail (".$from.")</h2>\n\n";echo $_r;$ret .= $_r;flush();
				
				
				if($c = $m->Connect("cpweb0009.servidorwebfacil.com", 465, $from_email, 's1t3mazaya','tls') or die(pre($m,true))){//$c = $m->Connect()){
				
						$usr_countError = $usr_countErrorInvalid = $usr_count = 0;
						$corpo = $mailing->corpo;
						
						$_r ="<h2>Conectado</h2>\n\n".pre($m,true);echo $_r;$ret .= $_r;flush();
						$_r ="<h2>Preparando para enviar mensagem '<em>".$mailing->titulo."</em>' através de ".$from."</h2>\n\n";echo $_r;$ret .= $_r;flush();
					
						$vg='';
						foreach($mailing->segmentos as $segmento){ $_inSegmento[] = $segmento->id; }
						$inSegmento = implode(',',$_inSegmento);
					
						
						$sql = "
						SELECT *
						FROM leitores, leitores_has_segmentos
						where
							leitores.idleitores not in (select idleitores from leitores_has_mailings
														where
															idmailings = ".$mailing->id."
														)  and
							leitores_has_segmentos.idleitores = leitores.idleitores and
							leitores.temp < 1 and
							leitores_has_segmentos.idsegmentos in (".$inSegmento.")
						limit $total_disponivel
						";
						$_r = $sql;echo $_r;$ret .= $_r;flush();
						
						$db->query($sql);
						$resource = $db->resourceAtual;
						if($db->rows){
							
							$_r = "<h3>".$db->rows." leitores no segmento atual</h3>\n\n";echo $_r;$ret .= $_r;flush();
							
							while($leitores = $db->fetch()){
								$img = '<p>Caso n&atilde;o queira mais receber nosso newsletter, acesse: <a href="http://'.$_SERVER['HTTP_HOST'].'/Newsletter/Remover/'.$leitores["idleitores"].'">http://'.$_SERVER['HTTP_HOST'].'/Newsletter/Remover/'.$leitores["idleitores"].'</a></p>';
								$img .= '<p><img src="http://'.$_SERVER['HTTP_HOST'].'/admin/confirma.php?m='.$mailing->id.'&l='.$leitores["idleitores"].'"></p>';
								
								$para = trim($leitores["email"]);
								
								$m->AddTo($para);
									
								$from_email = $from;
								
								$from_name = trim("Mesacor Tramontina");
								$m->From($from_email, $from_name);
								
								$m->Subject($mailing->titulo,'iso-8859-1');
								
								$m->Html($corpo.$aviso.$img,'iso-8859-1');		
								$m->Text(strip_tags($corpo.$aviso.$img),'iso-8859-1');
			
								$qr = '';
								$_POST['idleitores'] = $leitores["idleitores"];
								$_POST['idmailings'] = $mailing->id;
								$_POST['data_envio'] = date("d/m/Y H:i:s");
									
									
								if(FUNC::is_mail($para)){
									
									if($m->Send($c)){
											$status = 2;
											$usr_count++;
											$_r ="$usr_count Enviando mailing para ".$para." - via \$c<br>\n";echo $_r;$ret .= $_r;flush();
		
											if($usr_count % 1000 == 0){
													$m->DelTo();
													$m->Disconnect();
											}
									}else{
											$status = 1;
											$usr_countError++;
											$_r ="Houve um problema durante o envido do mailing '".$mailings->titulo."' para '".$para."'<br>\n";pre($_RESULT);echo $_r;$ret .= $_r;flush();
											
											$m->Disconnect();
									}
									$m->DelTo();
								}else{
											$_POST['status'] = 1;
											$usr_countErrorInvalid++;
											$_r ="O e-mail não existe '".$para."' ou não pode ser verificado neste momento. Uma nova tentativa pode ser feita posteriormente<br>\n";echo $_r;$ret .= $_r;flush();
		
								}
								$db->query('insert into leitores_has_mailings set idleitores = "'.$leitores["idleitores"].'", idmailings = "'.$mailing->id.'", data_envio = "'.date("d/m/Y H:i:s").'", status = "'.$status.'"');
								$db->resource($resource);
							}
								
							$_r ="\n\n<h3>Envio do mailing '".$mailing->titulo."' concluido. Distribuido para $usr_count usuarios</h3>\n\n";echo $_r;$ret .= $_r;flush();
							$_r ="\n\n<h3>Problema no envio de $usr_countError usuario(s)</h3>\n\n";echo $_r;$ret .= $_r;flush();
							$_r ="\n\n<h3>Emails inválidos $usr_countErrorInvalid usuario(s)</h3>\n\n";echo $_r;$ret .= $_r;flush();
							$_r ="<h2>Fim do envio</h2>\n\n";echo $_r;$ret .= $_r;flush();
							
						}else{
							$_POST['data_conclusao'] = date("d/m/Y H:i:s");
							$_POST['status'] = 3;
							$db->editar('mailings',$mailing->id);
							pre($db);
							$_r ="<h2>Não restam destinatários</h2>\n\n";echo $_r;$ret .= $_r;flush();
							n2_mail('noxsulivan@gmail.com','Mailing enviado no site '.$_SERVER['HTTP_HOST'],$ret,$from,$_FILES);
						}
					
					
				}else{
						$_r ="<h2>Não foi possível conectar ao servidor de e-mails no momento, tente novamente em alguns minutos</h2>";echo $_r;$ret .= $_r;flush();
						 die(print_r($m->Result));
				}
			$db->resource($resourceMailings);
			
	}
}else{
	$_r ="Nenhum mailing a ser publicado";echo $_r;$ret .= $_r;flush();
}				
?>