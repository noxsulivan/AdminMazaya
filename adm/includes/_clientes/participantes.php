<?

$id = explode("-",$admin->id);

$participante = new objetoDb("participantes",$id[0]);
if($id[1] != md5($participante->email)){
	die("Sem permissão para visualizar documento");
}


if($admin->id){
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Admin &raquo;
<?=str2upper($admin->titulo)?>
<?=str2upper($admin->configs["titulo_site"])?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<style type="text/css">
<!--
<?
echo file_get_contents($_serverRoot.$admin->admin."pdf_certificado.css");
//echo preg_replace("/imagens\//i",$_serverRoot.$admin->admin."imagens/",file_get_contents($_serverRoot.$admin->admin."admin_".$site.".css"));
echo file_get_contents($_serverRoot.$admin->admin."admin_".$site.".css");
?>
-->
</style>
</head>
<body>
<table width="100%" cellpadding="3" cellspacing="3" style="padding:5% 10%; width:100%; margin:2.5% 2.5%;">
  <tbody>
    <tr>
      <td colspan="4" class="tdTit"><h1>CERTIFICADO</h1></td>
      </tr>
    <tr>
      <td colspan="4" class="tdDes"><p>A <?=$participante->certificados->descricao?> certifica que <?=$participante->nome_completo?>, participou do evento sobre <?=$participante->certificados->referencia?>, ministrado por<br />
      		<?=$participante->certificados->palestrante?>, no dia <?=$participante->certificados->data?>, com carga hor&aacute;ria total de <?=$participante->certificados->carga_horaria?> horas.</p></td>
      </tr>
    <tr>
    	<td colspan="2" class="tdAss"><?=nl2br($participante->certificados->representante)?></td>
    	<td colspan="2" class="tdAss"><?=nl2br($participante->certificados->palestrante)?></td>
    	</tr>
	<?
	$apoio = explode("\n",preg_replace("/, /i","<br>",$participante->certificados->apoio));
	?>
    <tr>
    	<td class="tdApo"><?=$apoio[0]?></td>
    	<td class="tdApo"><?=$apoio[1]?></td>
    	<td class="tdApo"><?=$apoio[2]?></td>
    	<td class="tdApo"><?=$apoio[3]?></td>
    	</tr>
    </tbody>
</table>
</body>
</html>
<?
$buffer = ob_get_clean();
//die($buffer);
//echo($buffer);
	
pdf($buffer,diretorio($participante->nome_completo)."_".diretorio($participante->certificados->referencia));

}else{
	header("location: ".$admin->localhost."adm/participantes/imprimirPDF/".$participante->id."-".md5($obj->email)."/"."".diretorio($participante->certificados->referencia).".pdf");
}
?>