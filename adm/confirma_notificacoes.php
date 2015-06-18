<?php

$_tmp = explode("/",$_SERVER['REQUEST_URI']);

	include("ini.php");

$sql = "update notificacoes set data_leitura = '".date("Y-m-d H:i:s")."', origem = '".$_SERVER['HTTP_USER_AGENT']."', ip = '".$_SERVER['REMOTE_ADDR']."' where md5(idnotificacoes) = '".$_SERVER['QUERY_STRING']."'";

if(mysql_query($sql) or die(mysql_error()))
	$ok = "ok";
else
	$ok = 'e';


header('Content-type: image/jpeg');
		
  $im = imagecreate(1, 1);
  imagejpeg($im);



?>