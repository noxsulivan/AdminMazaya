<?php

$_tmp = explode("/",$_SERVER['REQUEST_URI']);

	include("ini.php");

$sql = "update avisos_has_visualizacoes set data_leitura = '".date("Y-m-d H:i:s")."', origem = '".$_SERVER['HTTP_USER_AGENT']."', ip = '".$_SERVER['REMOTE_ADDR']."' where idcondominos = '".$_REQUEST['l']."'  and idavisos = '".$_REQUEST['m']."'";

if(mysql_query($sql) or die(mysql_error()))
	$ok = "ok";
else
	$ok = 'e';


header('Content-type: image/jpeg');
		
  $im = imagecreate(120, 15);
  $bg = imagecolorallocate($im, 255, 255, 255);
  $textcolor = imagecolorallocate($im, 0, 0, 0);
  imagestring($im, 3, 0, 0, $_REQUEST['l']."/".$_REQUEST['m']."", $textcolor);
  imagejpeg($im);



?>