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
    	echo ('jquery.ajaxmanager,jquery-te-1.4.0.min,swfupload,jquery.jeditable,jquery.meio.mask,jquery.tmpl.min,scriptcam,jquery-ui-timepicker-addon,masonry.pkgd.min')?>"></script>

    
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
<?
if ($usuario->conectado()) {?>
<body class="site">
                        <div id=topo><h1><? echo $admin->configs["titulo_site"];?></h1>
                        <div id="usuario">Usuário: <? echo $usuario->nome.($usuario->tipos_de_usuarios->tipo ? ' ('.$usuario->tipos_de_usuarios->tipo.')' : "" );?> - <a onclick="trocarSenha(); " href="#">Alterar Senha</a> / <a onclick="linkDireto('Sair/'); " href="#Sair">Sair</a></div>
                            <div id="menu">
                                <?=$admin->menu()?>
                            </div>
                        </div>
						<div id="carregando" style="display:none;">Carregando...</div>
						<div id="conteudo">
						<?									include("lib/capa.php");
									echo $admin->html;
									$admin->html = '';
						?>
						</div>
						<div id="frame" style="display:none;"></div>
<? }else{?>
<body class="entrada">
						<div id="caixaLogin">
                        <div id="titulo"><? $logo = new objetoDb("configuracoes",'logo');?>
                        <img src="<?=$admin->localhost;?>img/<?=$logo->fotos[0]['id'];?>/250" width="250"   alt=""/>
                        <h1>Acesso Restrito</h1>
                        </div>
						  <?
								if($act == 'trocar_senha'){?>
									<form method="post" action="" onsubmit="return logar()">
									<input type="hidden" name="acaoUsuario" value="alterarSenha">
									Digite seu apelido
									<input name="apelido" type="text" class="form">
									Digite seu e-mail
									<input name="email" type="text" value="" class="form">
									Digite sua senha nova
									<input name="senha" type="password" value="" class="form">
									Confirme a sua senha
									<input name="senha_conf" type="password" value="" class="form">
									Uma nova senha será enviada para o e-mail configurado. Se vc deseja que ela também seja enviada para um outro e-mail, digite aqui o endereço alternativo.
									<input name="email_alt" type="text" value="" class="form">
									<input name="submit" type="submit" style="width: 50%" value="ok" class="form">
									</form>
								<? }else{?>
									<form name="formLogin" id="formLogin" method="post" onsubmit="return logar()">
									<label for="apelido">Login/Email</label>
									<input name="apelido" id="apelido" type="text">
									<label for="senha">Senha</label>
									<input name="senha" id="senha" type="password" value="">
									<input type="submit" id="botaoLogin" value="Entrar" />
									</form>
								<? }?>
							<div id="mensagemLogin"><?=$msg?>Digite seus dados de acesso</div>
						</div>
<? }?>

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
