<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Admin &raquo;
<?=$admin->titulo;?>
<?=$admin->configs["titulo_site"];?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<link rel="shortcut icon" href="<?=$admin->localhost.$admin->admin;?>logos.png" />
<meta http-equiv="X-UA-Compatible" content="chrome=1">


<link href="<?=$admin->localhost.$admin->admin."admin_admin.css";?>" rel="stylesheet" type="text/css" media="all">
<link href="<?=$admin->localhost.$admin->admin."admin_".$site.".css";?>" rel="stylesheet" type="text/css" media="all">
<link href="<?=$admin->localhost.$admin->admin;?>print.css" rel="stylesheet" type="text/css" media="print">
<link href="<?=$admin->ABSURL."_shared/_css/".preg_replace($pattern,"$1",$_SERVER['REQUEST_URI'])."/jquery-ui-1.8.9.custom.css";?>" rel="stylesheet" type="text/css" media="all">
<link rel="shortcut icon" href="<?=$admin->localhost.$admin->admin;?>logos.png" />
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<?php /*?><link href="<?=$admin->ABSURL;?>_shared/_css/highslide.css" rel="stylesheet" type="text/css"><?php */?>



<script language="javascript" type="text/javascript" src="<?=$admin->ABSURL;?>_shared/_js/proto_gzip.php?f=<?=filemtime("_geral.php")?>&sc=jquery-1.4.4.min,jquery-ui-1.8.9.custom.min,swfupload,jquery.ajaxmanager,jquery.jeditable,jquery.meio.mask,jquery.tmpl.min,jquery.masonry"></script>


<link href='http://fonts.googleapis.com/css?family=Amaranth&subset=latin&v2' rel='stylesheet' type='text/css'>

<script>
$(document).ready(function() {
	$("#conteudoPDF").html($("#formGeral", window.opener.document).html());
	
	$(".yellow, .grey").remove()
	$(".legend").each(function(){
							   $(this).next().show();
				});
	$("input").each(function(){
				if($(this).is(":visible"))
					  $(this).val($("#" + $(this).attr("id"), window.opener.document).val());
					  //if($("#" + $(this).attr("id"), window.opener.document).attr("checked"))
					  	//$(this).attr("checked",true);
				});
	$("select").each(function(){
					  $(this).val($("#" + $(this).attr("id"), window.opener.document).val());
				});
				
	$("textarea").each(function(){
					  $(this).val($("#" + $(this).attr("id"), window.opener.document).val());
				});
	
	$("#texto").val($("#conteudoPDF").html() + "done");

});

</script>
</head>
<body>

<h2 id="titulo"></h2>
<div id="relatorioPDF">
<div id="conteudoPDF">Text12</div>
</div>
<textarea id="texto"></textarea>
</body>
</html>