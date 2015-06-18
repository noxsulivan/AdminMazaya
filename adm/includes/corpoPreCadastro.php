<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Admin &raquo;
<?=$admin->titulo;?>
<?=$admin->configs["titulo_site"];?>
</title>
<meta property="fb:admins" content="1617084388"/>
<meta property="fb:app_id" content="169974619697942"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="viewport" content="width=1024, initial-scale=1" />

<?php /*?><link href="<?=$admin->localhost.$admin->admin."admin_ipad.css";?>" rel="stylesheet" media="only screen and (max-device-width: 1024px)" type="text/css" /><?php */?>

<link href="<?=$admin->localhost.$admin->admin;?>print.css" rel="stylesheet" type="text/css" media="print">
<link href="<?=$admin->ABSURL."_shared/_css/mazaya/jquery-ui.css";?>" rel="stylesheet" type="text/css" media="all">
<link href="<?=$admin->ABSURL."_shared/_css/mazaya/jquery-ui.theme.css";?>" rel="stylesheet" type="text/css" media="all">
<link href="<?=$admin->ABSURL."_shared/_css/jquery-te-1.4.0_mazaya.css";?>" rel="stylesheet" type="text/css" media="all">
<link href="<?=$admin->ABSURL."_shared/_css/smoothzoom.css";?>" rel="stylesheet" type="text/css" media="all" />

<link href="<?=$admin->localhost.$admin->admin."admin_2014c.css";?>" rel="stylesheet" type="text/css" media="all">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>


<link rel="shortcut icon" href="<?=$admin->localhost.'icon_'.$site.'.png';?>" />
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<?php /*?><link href="<?=$admin->ABSURL;?>_shared/_css/highslide.css" rel="stylesheet" type="text/css"><?php */?>

<?php /*?><script language="javascript" type="text/javascript" src="<?=$admin->ABSURL;?>_shared/_js/proto_gzip.php?f=<?=filemtime("_geral.php")?>&sc=<?
	echo base64_encode('jquery-1.4.4.min,jquery-ui-1.8.16.custom.min,jquery.ajaxmanager,swfupload,jquery.jeditable,jquery.meio.mask,jquery.tmpl.min,jquery.masonry,jquery.ui.selectmenu')?>"></script>
<?php */?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.0/jquery-ui.min.js" type="text/javascript"></script>
<script language="JavaScript" src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>

<script language="javascript" type="text/javascript" src="<?=$admin->ABSURL;?>_shared/_js/proto_gzip.php?f=<?=filemtime("_geral.php")?>&sc=<?
    	echo ('jquery.ajaxmanager,jquery-te-1.4.0.min,swfupload,jquery.jeditable,jquery.meio.mask,jquery.tmpl.min,scriptcam,jquery-ui-timepicker-addon,masonry.pkgd.min,smoothzoom.min')?>"></script>

    
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
    </script>


<?php /*?><script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
</script>
<?php */?>
<script language="javascript" type="text/javascript" src="<?=$admin->localhost.$admin->admin.filemtime("_geral.php");?>_geral.js/?<?=$usuario->chave?>"></script>

</head>
<body class="entrada">
						<div id="caixaPreCadastro">
                        <? $logo = new objetoDb("configuracoes",'logo');?>
                        <div><img src="<?=$admin->localhost;?>img/<?=$logo->fotos[0]['id'];?>/250" width="250"   alt=""/></div>
                        <h1>Pré-cadastro de Condôminos</h1>
									<form name="formPreCadastro" id="formPreCadastro" method="post" onsubmit="return enviarPreCadastro()">
                                    <div class="campoPreCadastroGrande">
									<label for="Nome">Nome do Proprietário</label>
									<input name="Nome" id="Nome" type="text">
                                    </div>
                                    <div class="campoPreCadastroPequeno">
									<label for="Quadra">Quadra</label>
									<input name="Quadra" id="quadra" type="text" value="" alt="integer">
                                    </div>
                                    <div class="campoPreCadastroPequeno">
									<label for="Lote">Lote</label>
									<input name="Lote" id="lote" type="text" value="" alt="integer">
                                    </div>
                                    <div class="campoPreCadastroPequeno">
									<label for="Telefone">Telefone</label>
									<input name="Telefone" id="telefone" type="text" value="" alt="phone">
                                    </div>
                                    <div class="campoPreCadastroPequeno">
									<label for="Celular">Celular</label>
									<input name="Celular" id="telular" type="text" value="" alt="phone">
                                    </div>
                                    <div class="campoPreCadastroGrande">
									<label for="E-mail">E-mail</label>
									<input name="E-mail" id="E-mail" type="text" value="">
                                    </div>
									<input type="submit" id="botaoLogin" value="Enviar" />
									</form>
							<div id="mensagemPreCadastro"><?=$msg?>Digite seus dados de acesso</div>
						</div>
                        <script>
                        $('input').setMask()
                        </script>
<? if(!preg_match('/nox/i',$_SERVER['HTTP_HOST'])){?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-194473-31");
pageTracker._trackPageview();
</script>
<? }?>
</body>
</html>
