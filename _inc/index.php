<?PHP
$timeIni = microtime(true);

include('ini.php');
$query = str_replace("/RETORNO/","Retorno",$_SERVER['QUERY_STRING']);
$_temp = $query;
$pagina = new layout($_SERVER['QUERY_STRING']);

	if(preg_match('/Retorno/',$_SERVER['REQUEST_URI'].$pagina->acao)){
		
			$_localhost = "https://".$_SERVER['HTTP_HOST']."/";			
			$_ABSURL = "https://".$_SERVER['HTTP_HOST']."/";		
			$_admin = "admin/";
		$outHtml = ob_get_clean();
		//file_put_contents('RETORNO_'.microtime().'.txt',$outHtml."<br>---------------<br>".print_r($_REQUEST,true).print_r($_SERVER,true));
	}


	if($pagina->tg == 'T'){
		if(preg_match("/churrasco/i",$_SERVER['HTTP_REFERER'])){
			$_Ref = "Churrasco";
		}else{
			$_Ref = "Tramontina";
		}
			
		header("Location: http://www.mesacor.com.br/Produtos/Ver/".$pagina->acao."/?utm_source=TramontinaGeral&utm_campaign=".$_Ref."&utm_term=".$pagina->acao);
	}
	if($pagina->tg == 'Oral'){
			
		header("Location: http://www.oralimplante.com.br/novo/cadastro-promocao/?utm_source=TDC&utm_campaign=".$_Ref."&utm_term=".$pagina->acao);
	}
	if($pagina->tg == 'TDC'){
			
		header("Location: $pagina->localhost/Produtos/Ver/".$pagina->acao."/?utm_source=TDC&utm_campaign=".$_Ref."&utm_term=".$pagina->acao);
	}
	if($pagina->tg == 'M'){
			
		header("Location: $pagina->localhost/Produtos/Ver/".$pagina->id."/?utm_source=Mailing&utm_campaign=".$pagina->acao);
	}
	if($pagina->tg == 'F'){
			
		header("Location: $pagina->localhost/Produtos/Ver/".$pagina->acao."/?utm_source=Facebook");
	}
	if($pagina->tg == 'Twitter' || $pagina->tg == 'Tt'){
			
		header("Location: $pagina->localhost/Produtos/Ver/".$pagina->acao."/?utm_source=Twitter&utm_campaign=PostAutomatico");
	}
	if($pagina->tg == 'Twitter' || $pagina->tg == 'Ti'){
			
		header("Location: $pagina->localhost/Produtos/Ver/".$pagina->acao."/?utm_source=Twitter&utm_campaign=Recomendacao");
	}
	if($pagina->tg == 'Twitter' || $pagina->tg == 'Tw'){
			
		header("Location: $pagina->localhost/Produtos/Categoria/".$pagina->acao."/?utm_source=Twitter&utm_campaign=PostAutomatico");
	}
	if($pagina->tg == 'O'){
			
		header("Location: $pagina->localhost/Produtos/Ver/".$pagina->acao."/?utm_source=Orkut&utm_campaign=Amd");
	}
//	if($pagina->acao == 'Churrasco' or $pagina->id == 'Churrasco'){
//			
//		header("Location: http://churrasco.mesacor.com.br/");
//	}
//	if($pagina->tg == 'Produtos' and $pagina->acao == 'Categoria' and preg_match('/Churrasco/i',$pagina->id)){
//			
//		header("Location: http://churrasco.mesacor.com.br/Categoria/".preg_replace("/Churrasco-/i","",$pagina->id)."");
//	}
//	if($pagina->tg == 'Produtos' and $pagina->acao == 'Linha' and preg_match('/Churrasco/i',$pagina->id)){
//			
//		header("Location: http://churrasco.mesacor.com.br/");
//	}
	
	
//	if($pagina->acao == 'Rock-n-Cook' or $pagina->id == 'Rock-n-Cook'){
//			
//		header("Location: http://Rock-n-Cook.mesacor.com.br/");
//	}
//	if($pagina->tg == 'Produtos' and $pagina->acao == 'Categoria' and preg_match('/Rock-n-Cook/i',$pagina->id)){
//			
//		header("Location: http://Rock-n-Cook.mesacor.com.br/Categoria/".preg_replace("/Rock-n-Cook-/i","",$pagina->id)."");
//	}
//	if($pagina->tg == 'Produtos' and $pagina->acao == 'Linha' and preg_match('/Rock-n-Cook/i',$pagina->id)){
//			
//		header("Location: http://Rock-n-Cook.mesacor.com.br/");
//	}
	
	if($pagina->tg == 'Cliente' and $_SERVER["HTTPS"] != 'on'){
		//header("Location: https://www.mesacor.com.br/".$_SERVER['QUERY_STRING']);
	}
	//$ch = new cache();


	$_SESSION['frete'] = '';
	unset($_SESSION['frete']);


$canal = new objetoDb('canais',$pagina->tg);

if(preg_match("/(?P<subdominio>\w+)\.mesacor.com.br/i",$_SERVER['HTTP_HOST'],$matches)){
	
	//pre($matches);
}

if(isset($matches['subdominio']) and !preg_match("/(www|mesacor})/i",$matches['subdominio'])){
	$subdominio = $matches['subdominio'];
	
	if($subdominio != 'tramontina'){
		$hotsite = new objetoDb('hotsites',$subdominio);
				$canal = new objetoDb('canais',"Especial");
				$canal->url = '';
				
				$pagina->id = $pagina->acao;
				$pagina->acao = $pagina->tg;
				$pagina->tg = 'Produtos';
	}else{
	}
}
		$hotsite = false;

$HOME = preg_match('/Home/i',$canal->url) ? true : false ;


	if($pagina->id == "Limpar"){unset($_SESSION['idpedidos']);}

//pre($_COOKIE);

		if(isset($_COOKIE['Mesacor_cadastroID'])){
			$cadastro = new cadastro($_COOKIE['Mesacor_cadastroID']);
		}else{
			$cadastro = new cadastro();
		}
		
		if(!isset($_SESSION['cliente'])){
			$cliente = new cliente();
		}else{
			$cliente = $_SESSION['cliente'];
		}
		if(isset($_SESSION['convidado'])){
			$convidado = $_SESSION['convidado'];
		}
		
		
		if($pagina->acao == "Recuperar"){
			list($id,$md5) = explode("-",$pagina->id);
			
			$pedido = new objetoDb("pedidos",intval($id));
						
			if($md5 == md5($pedido->cadastros->email)){
					setcookie("pedidoID",intval($id),time()+(60*60*24*30),'/');
					$pedido = new objetoDb("pedidos",intval($pagina->id));
			
							$_POST['idestagios'] = 7;
			
							$_POST['data_transacao'] = date("d/m/Y H:I:s");
							$db->editar('pedidos',$pedido->id);
			}else{
					echo "Pedido inválido";
					unset($pedido);
			}
			
		}else{
			//MEDIDA PROVISORIA
			if(isset($_SESSION['idpedidos'])){
					setcookie("pedidoID",$_COOKIE['pedidoID'],time()+(60*60*24*30),'/');
					unset($_SESSION['idpedidos']);
			}
				
				
			if(isset($_COOKIE['pedidoID'])){
				
				$pedido = new objetoDb('pedidos',$_COOKIE['pedidoID']);
				
			}
			//se pedido finalizado ou excluido do bd
			
			if(!$pedido->id){
				setcookie("pedidoID",$_COOKIE['idpedidos'],time()-(60*60*24*30),'/');
				unset($_COOKIE['pedidoID']);
				unset($pedido);
			}

			if($pedido->estagios->id > 1 and $pedido->estagios->id < 7){
				setcookie("pedidoID",$_COOKIE['idpedidos'],time()-(60*60*24*30),'/');
				unset($_COOKIE['pedidoID']);
				unset($pedido);
			}
		}
		
		
		
		
		if($_REQUEST['cupom']){
			$cupom = recuperarCupom($_REQUEST['cupom']);
			//pre($pedido);
			if($cupom->status == TRUE){
			  $pedido = new objetoDb("pedidos",$pedido->id);
			}
		}
		
	
	if(!isset($_SESSION['itensVisitados'])){
		$_SESSION['itensVisitados'] = array();
	}
	
	
	setcookie("primeiraVisitaMesacor",true,time()+(60*60*24*30),'/');




if($canal->url == 'Facebook/' or $canal->url == 'Cadastro-Promocao/'){
	$FACEBOOK = true;
	}
$cache = new boxcache($_siteRoot."_cache_obj/");
//$cache->enableDebug();
$estiloDC = false;
if($pagina->acao == 'robots.txt' || $pagina->tg == 'robots.txt' || $pagina->id == 'robots.txt') die("User-Agent: *
Allow: /");
ob_start();
		if($canal->tipos_de_canais->arquivo){
			include($canal->tipos_de_canais->arquivo);
		}else{
			$hotsite = new objetoDb('hotsites',$pagina->tg);
			if($hotsite->id){
				$canal = new objetoDb('canais',"Especial");
				$canal->url = $hotsite->url;
				include('_produtos_especial.php');
			}else{
				include('_inexistente.php');
			}
		}
		echo $pagina->html;
		
$outHtml = ob_get_clean();
//$estiloDC = false;


?>
<?PHP 								
									//if(!$cache->get("header")){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?PHP if(preg_match("/produto/i",$canal->url)){?>
<title><?PHP echo trim($titulo." - mesacor.com.br");?></title>
<? }else{ ?>
<title><?PHP echo trim($pagina->configs['titulo_site'].' &raquo; '.$titulo);?></title>
<? }?>
<meta name="resource-type" content="document" />
<meta name="classification" content="Internet" />
<meta name="description" content="<?PHP echo (($descricao_curta) ? strip_tags($descricao_curta): $pagina->configs[descricao])?>" />
<meta name="keywords" content="<?PHP echo $pagina->configs[palavras_chave]?>" />
<meta name="robots" content="ALL" />
<meta name="distribution" content="Global" />
<meta name="rating" content="General" />
<meta name="author" content="Mazaya.com.br" />
<meta name="language" content="pt-br" />
<meta name="doc-class" content="Completed" />
<meta name="doc-rights" content="Public" />
<meta name="google-site-verification" content="G-znf3ulXaddG0YqaePr2uVWH0TtZKIV_EPYGohJEKc" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link href="<?PHP echo $pagina->localhost;?>favicon.ico" rel="shortcut icon" />
<link href="<?PHP echo $pagina->ABSURL;?>_shared/_css/blitzer/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?PHP echo $pagina->ABSURL;?>_shared/_css/master.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?PHP echo $pagina->ABSURL;?>_shared/_css/jquery.fancybox.css" rel="stylesheet" type="text/css" media="all" />
<link href='https://fonts.googleapis.com/css?family=Open+Sans|Open+Sans+Condensed:300' rel='stylesheet' type='text/css' />


    <meta property="og:url" content="<?PHP echo $pagina->localhost;?>Produtos/Ver/<?=$pro->id?>/<?=$pro->url?>">
    <meta property="og:title" content="<?=$pro->produto." ".$pro->linhas->linha." ".$pro->codigo?> - Mesacor Tramontina">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?PHP echo $pagina->img($pro->fotos[0]['id'].'/800/600')?>">
    <meta property="og:description" content="<?=$pro->descricao_curta?>">
      
      
      
      
      
      
      
<link href="<?PHP echo $pagina->localhost;?>css/<?PHP
	echo (preg_match("/(cl3iente|retorno)/i",$canal->url) ? "mp2013_1l":"mp2013_1l")
	?>.css" rel="stylesheet" type="text/css" media="all" />
    
    
<script type="text/javascript" src="https://www.google.com/jsapi?key=ABQIAAAAbJ1kffsMyFnMaZ4PDuDS0BSC371tnk2SZdEOQM3zbULZ6eh5BRTxb1Bg-nAjYh2NAUyEpqMp0D6VTw"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js" type="text/javascript"></script>
<script src="<?PHP echo $pagina->localhost;?>proto.js/?sc=jquery.ui.selectmenu,jquery.validate,jquery.cycle.lite,jquery.maskedinput.min,jquery.isotope,jquery.carouFredSel-6.2.1,jquery.mousewheel-3.0.6.pack,jquery.fancybox.pack" type="text/javascript"></script>
<script src="<?PHP echo $pagina->localhost.filemtime("_scripts/_geral.php")?>_geral.js" type="text/javascript"></script>
<script src="<?PHP echo $pagina->localhost;?>_scripts/carrinho.js?t=<?=time()?>" type="text/javascript"></script>
<?PHP if(!preg_match('/localhost/i',$_SERVER['HTTP_HOST'])){?>
<script type="text/javascript">
    
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-194473-17']);
      _gaq.push(['_trackPageview','<?PHP echo $canal->url.$pagina->acao.($busca ? '/?'.http_build_query(array('busca'=>$busca)) : ($pagina->id ? '/'.$pagina->id : ''))?>']);
	  
      _gaq.push(['_setAccount', 'UA-32988701-1']);
      _gaq.push(['_trackPageview','<?PHP echo $canal->url.$pagina->acao.($busca ? '/?'.http_build_query(array('busca'=>$busca)) : ($pagina->id ? '/'.$pagina->id : ''))?>']);
	  
	  <?PHP if(isset($analytics)){ echo $analytics; }?>
	  
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js'
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
	  
    
    </script>
<?PHP }?>


</head>
<body>
<!--[if lt IE 9]><p class=chromeframe>Seu avegador está <em>ultrapassado!</em> <a href="http://browsehappy.com/">Instale um mais moderno</a> ou um realmente poderoso <a href="http://www.google.com/chromeframe/?redirect=true">instale o Google Chrome Frame</a> para ter uma experiência completa.</p><![endif]-->
<div id="containerSite" class="sizeWide">
	<div id="barraPrincipal">
		<div id="barraPrincipalInner" class="sizeWide">
			
            <div id="logo_home"><a href="<?PHP echo $pagina->ABSURL?>" title="Mesacor Presentes">&nbsp;</a>
            </div>
            <div id="busca">
				<div id="spanBusca">Busca</div>
				<form action="<?PHP echo $pagina->localhost.( $hotsite->url ? "" : "Produtos/")?>Busca" method="post">
					<input name="q" id="campobusca1" type="text" value="<?PHP echo $busca?>" />
					<button type="submit" class="submitBusca">OK</button>
				</form>
			</div>
			<div id="cliente">
				<?PHP if($cadastro->conectado()){?>
				<ul id="cadastro">
					<li><a href="<?PHP echo $pagina->localhost?>Cliente"><?PHP echo $cadastro->nome?> |</a></li>
					<li><a href="<?PHP echo $pagina->localhost?>Cliente/Cadastro">Meu Cadastro</a></li>
					<li><a href="<?PHP echo $pagina->localhost?>Cliente/Carrinho">Meu Carrinho <?PHP echo (count($_SESSION['itensCarrinho']) ? " <strong>(".array_sum($_SESSION['itensCarrinho']).")</strong>": '')?> </a></li>
					<li><a href="<?PHP echo $pagina->localhost?>Cliente/Pedidos">Minhas Compras</a></li>
					<li><a href="<?PHP echo $pagina->localhost?>Cliente/Sair">Sair</a></li>
				</ul>
				<?PHP }else{ ?>
				<ul id="cadastro">
					<li><a href="<?PHP echo $pagina->localhost?>Cliente/Cadastro">Cadastre-se</a></li>
					<li><a href="<?PHP echo $pagina->localhost?>Cliente/Carrinho">Carrinho de Compras <?PHP echo (count($_SESSION['itensCarrinho']) ? " <strong>(".array_sum($_SESSION['itensCarrinho']).")</strong>": '')?> </a></li>
					<li><a href="<?PHP echo $pagina->localhost?>Cliente">Login</a></li>
				</ul>
				<?PHP } ?>
				<ul id="atendimento">
					<li><a href="<?PHP echo $pagina->localhost?>Contato">&raquo; Ajuda</a></li>
					<li><a href="<?PHP echo $pagina->localhost?>Contato">&raquo; Atendimento (47) 3056-6479</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div id="topo">
		<h1><a href="<?PHP echo $pagina->ABSURL?>" title="Mesacor - Página Inicial">Mesacor - Página Inicial</a></h1>
	</div>
<?php /*?><ul id="hipercategorias" class="sizeWide">
		<?PHP
			
				$sql = "select * from hipercategorias order by ordem";
				$db->query($sql);
				while($res = $db->fetch()){
					$hiper = new objetoDb("hipercategorias",$res['idhipercategorias']);
					//;
					echo '<li class="hipercategoria"><a href="'.$pagina->localhost."Produtos/Categoria/".$hiper->url.'">'.$hiper->categoria.'</a>';
					if(count($hiper->categorias)){
						echo "<ul>";
						foreach($hiper->categorias as $cat){
							echo '<li><a href="'.$pagina->localhost.( $hotsite->url ? "" : "Produtos/")."Categoria/".( $hotsite->url ? preg_replace("/".diretorio($hotsite->url)."-/i","",$cat->url): $cat->url).'">'.$cat->categoria."</a></li>";
						}
						echo "</ul>";
					}
					echo '</li>';
				}
			
			
			?>
	</ul>
<?php */?>
<ul id="hipercategorias" class="sizeWide">
		<li class="hipercategoria"><a href="<?=$pagina->localhost?>Produtos/Categoria/Linha-Bar/">Linha Bar</a>
			<ul>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Bar-Baldes-para-garrafas/">Baldes para garrafas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Bar-Coqueteleiras/">Coqueteleiras</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Bar-Jogo-Termico/">Jogo Térmico</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Kits-Caipirinha/">Kits Caipirinha</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Linha-bar/">Linha bar</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Acessorios-para-mesa-Petisqueiras/">Petisqueiras</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Bar-Saca-rolhas-Abridor/">Saca-rolhas/Abridor</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Bar-Tabuas-de-frios-petiscos/">Tábuas de frios/petiscos</a></li>
			</ul>
		</li>
		<li class="hipercategoria"><a href="<?=$pagina->localhost?>Produtos/Categoria/Cha-e-Cafe/">Chá e Café</a>
			<ul>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Cha-e-cafe-Acucareiros/">Açucareiros</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Cha-e-cafe-Bules-e-Garrafas/">Bules e Garrafas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Cafeteiras-Italianas/">Cafeteiras Italianas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Cha-e-cafe-Cestos-para-paes/">Cestos para pães</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Cha-e-cafe-Cremeiras/">Cremeiras</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Garrafa-Termica/">Garrafa Térmica</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Cha-e-cafe-Jarras/">Jarras</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Cha-e-cafe-Mantegueiras/">Mantegueiras</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Cha-e-cafe-Migalheiras/">Migalheiras</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Cha-e-cafe-Xicaras/">Xícaras</a></li>
			</ul>
		</li>
		<li class="hipercategoria"><a href="<?=$pagina->localhost?>Produtos/Categoria/Acessorios-para-mesa/">Servir</a>
			<ul>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Acessorios-para-mesa/">Acessórios para mesa</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Acessorios-para-mesa-Aparelhos-para-fondue/">Aparelhos para fondue</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Baixelas-Travessas/">Baixelas/Travessas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Bandejas/">Bandejas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Copos-e-Tacas/">Copos e Taças</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Fruteira/">Fruteira</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Acessorios-para-mesa-Galheteiros/">Galheteiros</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Acessorios-para-mesa-Molheiras/">Molheiras</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Acessorios-para-mesa-Porta-guardanapos/">Porta-guardanapos</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Rechaud/">Rechaud</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Acessorios-para-mesa-Saleiros/">Saleiros</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Sobremesa/">Sobremesa</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Acessorios-para-mesa-Sousplat/">Sousplat</a></li>
			</ul>
		</li>
		<li class="hipercategoria"><a href="<?=$pagina->localhost?>Produtos/Categoria/Faqueiros-e-Talheres/">Faqueiros e Talheres</a>
			<ul>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Faqueiros-e-Talheres-Faqueiros-Diarios/">Faqueiros Diários</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Faqueiros-e-Talheres-Faqueiros-Finos-com-Estojo/">Faqueiros Finos com Estojo</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Faqueiros-Talheres-avulsos/">Talheres avulsos</a></li>
			</ul>
		</li>
		<li class="hipercategoria"><a href="<?=$pagina->localhost?>Produtos/Categoria/Facas/">Facas de Corte</a>
			<ul>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Facas-Accurato/">Accurato</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Afiadores/">Afiadores</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Facas/">Facas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Facas-Facas-Esportivas/">Facas Esportivas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Facas-Linha-Century/">Linha Century</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Facas-Linha-Cronos/">Linha Cronos</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Facas-Linha-Onix/">Linha Onix</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Facas-Prochef/">Prochef</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Facas-Profissional-Master/">Profissional Master</a></li>
			</ul>
		</li>
		<li class="hipercategoria"><a href="<?=$pagina->localhost?>Produtos/Categoria/Churrasco/">Churrasco</a>
			<ul>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Churrasco-Acessorios/">Acessórios</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Churrasco-Carrinhos/">Carrinhos</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Churrasco-Conjuntos/">Conjuntos</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Churrasco-Espetos/">Espetos</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Churrasco-Facas/">Facas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Churrasco-Grelhas/">Grelhas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Churrasco-Tabuas/">Tábuas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Churrasco-Talheres/">Talheres</a></li>
			</ul>
		</li>
		<li class="hipercategoria"><a href="<?=$pagina->localhost?>Produtos/Categoria/Panelas-e-frigideiras/">Forno e Fogão</a>
			<ul>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Assadeiras-Formas/">Assadeiras/Formas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Chaleiras/">Chaleiras</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Panelas-e-frigideiras-Conjunto-de-panelas/">Conjunto de panelas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Panelas-e-frigideiras-Cozi-Vapore/">Cozi-Vapore</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Panelas-e-Frigideiras-Espagueteiras-e-Cozi-pasta/">Espagueteiras e Cozi-pasta</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Panelas-e-frigideiras-Fervedores/">Fervedores</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Panelas-e-Frigideiras-Frigideiras/">Frigideiras</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Linha-Trix/">Linha Trix</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Panela-Forno-LYON/">Panela Forno LYON</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Panelas-e-frigideiras-Panela-Wok-e-Paelleras/">Panela Wok e Paelleras</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Panelas-e-Frigideiras-Panelas-avulsas/">Panelas avulsas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Panelas-de-Pressao/">Panelas de Pressão</a></li>
			</ul>
		</li>
		<li class="hipercategoria"><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-de-Cozinha/">Utensílios de Cozinha</a>
			<ul>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Abridores/">Abridores</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Balancas/">Balanças</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Batedores/">Batedores</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Centrifugas/">Centrífugas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Colheres/">Colheres</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Conchas/">Conchas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Conjuntos/">Conjuntos</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Cortadores/">Cortadores</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Escorredores/">Escorredores</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Espatulas/">Espátulas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Espremedores/">Espremedores</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Espumadeiras/">Espumadeiras</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Garfo-Trinchante/">Garfo Trinchante</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Itens-de-reposicao/">Itens de reposição</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Limpeza/">Limpeza</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Luvas/">Luvas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Moedor/">Moedor</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Pegadores/">Pegadores</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Pincel/">Pincel</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Potes-e-Recipientes/">Potes e Recipientes</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Ralador/">Ralador</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Raladores-e-Fatiadores/">Raladores e Fatiadores</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Termometro/">Termômetro</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Utensilios-Bambu/">Utensílios Bambu</a></li>
			</ul>
		</li>
		<li class="hipercategoria"><a href="<?=$pagina->localhost?>Produtos/Categoria/Design-Collection/">Design Collection</a>
			<ul>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Design-Collection-Bar/">Bar</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Design-Collection-Cha-e-Cafe/">Chá e Café</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Design-Collection-Facas/">Facas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Design-Collection-Forno-e-Fogao/">Forno e Fogão</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Design-Collection-Cha-e-Cafe-Glam/">Glam</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Design-Collection-Facas-Linha-Accurato/">Linha Accurato</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Design-Collection-Mesa/">Mesa</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Design-Collection-Presto/">Presto</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Design-Collection-Servir/">Servir</a></li>
			</ul>
		</li>
<?php /*?>		<li class="hipercategoria"><a href="<?=$pagina->localhost?>Produtos/Categoria/Linha-Empresarial/">Linha Empresarial</a>
			<ul>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Linha-empresarial/">Linha empresarial</a></li>
			</ul>
		</li>
		<li class="hipercategoria"><a href="<?=$pagina->localhost?>Produtos/Categoria/Pias-e-Cubas/">Pias e Cubas</a>
			<ul>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Pias-e-Cubas-Cubas/">Cubas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Pias-e-Cubas-Pias/">Pias</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Pias-e-Cubas-Tanques/">Tanques</a></li>
			</ul>
		</li>
		<li class="hipercategoria"><a href="<?=$pagina->localhost?>Produtos/Categoria/Fogoes-Cooktop/">Móveis e Eletros</a>
			<ul>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Acessorios-aramados/">Acessórios aramados</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Aspiradores-de-Po/">Aspiradores de Pó</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Moveis-Plastico-Cadeiras/">Cadeiras</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Coifas-e-Depuradores/">Coifas e Depuradores</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Fogoes-Cooktop/">Fogões/Cooktop</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Fornos-Eletricos/">Fornos Elétricos</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Moveis-Plastico-Linha-Infantil/">Linha Infantil</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Lixeiras/">Lixeiras</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Moveis-Plastico-Mesas/">Mesas</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Moveis-de-Madeira/">Móveis de Madeira</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Moveis-Plastico/">Móveis Plástico</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Tabuas-de-Passar/">Tábuas de Passar</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Fogoes-Cooktop-Vitrogril/">Vitrogril</a></li>
			</ul>
		</li><?php */?>
		<li class="hipercategoria"><a href="<?=$pagina->localhost?>Produtos/Categoria/Edicao-Limitada/">Linhas Especiais</a>
			<ul>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Copa/">Copa do Mundo</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Design-Collection/">Design Collection</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/La-Pasticceria/">La Pasticceria</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Lancamentos/">Lançamentos</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Linha-infantil/">Linha infantil</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/My-Lovely-Kitchen/">My Lovely Kitchen</a></li>
				<li><a href="<?=$pagina->localhost?>Produtos/Categoria/Pizza/">Pizza</a></li>
			</ul>
		</li>
		<li class="hipercategoria"><a href="<?=$pagina->localhost?>Produtos/Categoria/Lixeiras/">Lixeiras</a></li>
		<li class="hipercategoria"><a href="<?=$pagina->localhost?>Produtos/Categoria/Lancamentos/">Lançamentos</a></li>
	</ul>


	<? if($pagina->tg != 'Cliente'){?>
    <div id="banner-wrapper">
		<div id="bannerPrincipal">
		<?PHP 
	  	$sql = "select * from galerias, tipos_de_banners where ativo = 'sim' and ".($hotsite ? "tipos_de_banners.url = '".diretorio($hotsite->url)."'" : "galerias.idtipos_de_banners = 1")." and tipos_de_banners.idtipos_de_banners = galerias.idtipos_de_banners order by ordem";
		
		
		$db->query($sql);
        if($db->rows){
			$_rows = $db->rows;
              ?>
			<?PHP
                  $i = 1;
                  while($res = $db->fetch()){
                      $obj = new objetoDb('galerias',$res['idgalerias']);
                     foreach($obj->fotos as $foto){
						 echo '<a href="'.$obj->link.'"><img src="'.$pagina->img($foto['id'].($hotsite ? '/988/257/1':'/720/300/1')).'" title="'.$obj->referencia.'" alt="'.$obj->referencia.'" /></a>';
						 
						 ?>
			<?PHP
                      }
                  }
              }
			  ?>
		</div>
    
        <div id="bannerFixoCasamento"><a href="#" onclick="$.fancybox.open('#boxCadastroNewsletter')"><img src="<?=$pagina->localhost?>_imagens/banner_2014_mesacor_newsletter.jpg" width="500" height="150"  alt=""/>
        </div>
        <div id="bannerFixoInstitucional"><a href="<?=$pagina->localhost?>Lista-de-Casamento"><img src="<?=$pagina->localhost?>_imagens/banner_2014_mesacor_lista_casamento.jpg" width="500" height="150"  alt=""/></a>
        </div>
        

	</div>
    <? }?>
    
	<div id="boxNewsletter">
				<div id="spanNewsletter">CADASTRE SEU E-MAIL E CONCORRA A UMA
<strong>FRIGIDEIRA MY LOVELY KITCHEN</strong></div>
				  <form action="<?PHP echo $pagina->localhost.( $hotsite->url ? "" : "Produtos/")?>Login" method="post" id="cadastreseForm" onsubmit="return sendWindowForm('cadastrese' ,'cadastreseForm')">
					<input name="acao" id="acao" type="hidden" value="enviarNewsletter" />
					  <input name="nome" type="text" alt="Nome" value="Nome" class="required inputField inputMedio" />
					  <input name="email" type="text" alt="E-mail" value="E-mail" class="required inputField inputMedio" />
					<button type="submit" class="submitBusca">OK</button>
				  </form>
	</div>
<?php /*?>	<div id="boxNoivos">
                  <? if($convidado->id){ ?>
                        <a href="<?=$pagina->localhost."Lista-de-Casamento/Presentes/".$convidado->url?>" class="awesome orange"><h2>Ver a lista de <?=$convidado->nome_noiva.' &amp; '.$convidado->nome_noivo?></h2></a>
                	<? }else{ ?>
                            <h3>Você é um convidado? Selecione o casal</h3>
                          <form action="http://mesacor.com.br/Lista-de-Casamento/" method="post"
                                onsubmit="return redirecionaNoiva($('#nome_noiva').val())">
                                  <input name="acao" id="acao" type="hidden" value="registrarConvidado" />
                                <select id="nome_noiva" name="nome_noiva">
                                  <option></option>
                                  <?
                                                                        $db->query("select * from clientes order by nome_noiva");
                                                                        while($res = $db->fetch()){
                                                                            echo '<option value="'.$res['url'].'">'.$res['nome_noiva'].' & '.$res['nome_noivo'].'</option>';
                                                                        }
                                                                        ?>
                                </select>
                            <button type="submit" class="submitBusca">OK</button>
                          </form>
				  <? }?>
                  <? if($cliente->id){ ?>
                    <h3><?=normaliza($cliente->nome_noiva.' &amp; '.$cliente->nome_noivo)?></h3>

                        <div id="menu">
                            <ul>
                            <li><a class="menuA" href="<?=$pagina->localhost?>Lista-de-Casamento/Meus-Dados" >Meus dados</a></li>
                            <li><a class="menuA" href="<?=$pagina->localhost?>Lista-de-Casamento/Meus-Presentes" >Meus presentes</a></li>
                            <li><a class="menuA" href="<?=$pagina->localhost?>Lista-de-Casamento/Convidados" >Indique aos convidados</a></li>
                            <li><a class="menuA" href="<?=$pagina->localhost?>Lista-de-Casamento/Sair" >Sair</a></li></ul>
                        </div>                  <? }else{?>
                    <h3>Vai se casar?</h3>
                    <a href="<?=$pagina->localhost?>Lista-de-Casamento/Cadastro"  class="awesome orange"><img src="<?=$pagina->localhost?>_imagens/botao2013_cadastrar.png" width="36" height="36" alt="Compre este produto" />Crie sua lista de presentes</a>
                  <? }?>
	</div><?php */?>
	
	<div id="container"> <?PHP echo $outHtml; ?>
    <div class="clear"></div>
    </div>
    
    <div class="clear"></div>
    <div id="bannerfull"> <a href="#" onclick="$.fancybox.open('#boxCadastroNewsletter')"><img src="<?=$pagina->localhost?>_imagens/banner_2014_mesacor_newsletter_full.jpg" width="1160" height="167"  alt=""/></div>

    <div class="clear"></div>
</div>
<div class="clear"></div>
<div id="rodape">
<div id="rodapeInner">
	<div id="rodapeAtendimento">
		<h2>Atendimento</h2>
		<ul>
			<li><a href="<?PHP echo $pagina->localhost?>Contato">&raquo; Fale conosco pelo fone <strong>(47) 3056-6479</strong></a></li>
			<li>Visite nossas lojas:<br />
				<strong>Matriz</strong><br />
				Av. Brasil nº 2431 - Centro<br />
				</li>
		</ul>
	</div>
	<div id="rodapeAjuda">
		<h2>Ajuda</h2>
		<ul>
			<li><a href="<?=$pagina->localhost?>Sobre/">Sobre a Mesacor</a></li>
			<li><a href="<?=$pagina->localhost?>Privacidade/">Privacidade</a></li>
			<li><a href="<?=$pagina->localhost?>Trocas-e-Devolucoes/">Trocas e   Devolu&ccedil;&otilde;es</a></li>
			<li><a href="<?=$pagina->localhost?>Seguranca/">Seguran&ccedil;a</a></li>
			<li><a href="<?=$pagina->localhost?>Formas-de-Pagamento/">Formas de   Pagamento</a></li>
			<li><a href="<?=$pagina->localhost?>Contato/">Contato</a></li>
		</ul>
	</div>
	<div id="rodapePagamento">
		<h2>Formas de Pagamento</h2>
		<img src="<?PHP echo $pagina->localhost?>_imagens/formas_pagamento2013.png" width="324" height="39" alt="Formas de Pagamento Boleto, Visa, Master, Amex, Diners e Elo" />
		<h2>Acompanhe pelas Redes Sociais</h2>
		<a href="http://www.eitter.com/mesacor"><img src="<?PHP echo $pagina->localhost?>_imagens/1329481140_social_twitter_box_blue.png" width="30" height="30" alt="Twitter Mesacor" /> Twitter <strong>@Mesacor</strong></a> <a href="http://www.facebook.com/mesacor"><img src="<?PHP echo $pagina->localhost?>_imagens/1329481145_social_facebook_box_blue.png" width="30" height="30" alt="Facebook Mesacor" /> Facebook <strong> /Mesacor</strong></a> <a href="http://blog.mesacor.com.br"><img src="<?PHP echo $pagina->localhost?>_imagens/1329481159_social_rss_box_orange.png" width="30" height="30" alt="Blog Mesacor" /> Blog <strong>blog.mesacor.com.br</strong></a>
	</div>
	<div id="rodapeSeguranca">
	<h2>Segurança e Credibilidade</h2>
		<a id="seloEbit" href="http://www.ebit.com.br/#MESA-COR" target="_blank" onclick="redir(this.href);">Avaliação de Lojas e-bit</a>
	</div>
	<div id="rodapeInstitucional"> <span><strong>MESACOR COMERCIO DE UTILIDADES DOMESTICAS LTDA-ME | CNPJ 04.349.766/0001-37</strong> - &copy; 2013 - Mesacor - Todos os direitos Reservados - Tramontina é uma marca registrada da Tramontina SA</span> </div>
	<div id="rodapeAssinatura">
		<div id="flashMazaya"><a href="http://www.mazaya.com.br" >mazaya</a></div>
	</div>
</div>
</div>
<div id="boxCadastroNewsletter">
				  <form action="<?PHP echo $pagina->localhost?>" method="post" id="cadastreseBoxForm" onsubmit="return sendWindowForm('cadastreseBox' ,'cadastreseBoxForm')">
					<input name="acao" id="acao" type="hidden" value="enviarNewsletter" />
					  <input name="nome" type="text" alt="Nome" value="Nome" class="required inputField inputMedio" />
					  <input name="email" type="text" alt="E-mail" value="E-mail" class="required inputField inputMedio" />
					<button type="submit" class="submitBusca">OK</button>
				  </form>
                  <a  href="<?PHP echo $pagina->localhost?>Regulamento-da-Cadastro-na-Newsletter/" target="_blank">* Leia o regulamento</a>
</div>
<? if(!$primeiraVisitaMesacor){?>
<script>
$.fancybox.open( "#boxCadastroNewsletter", {
								helpers	: {
									overlay : {
										showEarly : false,
										locked     : true
									}}}
				); 
</script>
<? }?>
</body>
</html>