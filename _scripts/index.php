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
			
		header("Location: http://tramontina.mesacor.com.br/Produtos/Ver/".$pagina->acao."/?utm_source=TramontinaGeral&utm_campaign=".$_Ref."&utm_term=".$pagina->acao);
	}
	if($pagina->tg == 'Oral'){
			
		header("Location: http://www.oralimplante.com.br/novo/cadastro-promocao/?utm_source=TDC&utm_campaign=".$_Ref."&utm_term=".$pagina->acao);
	}
	if($pagina->tg == 'TDC'){
			
		header("Location: http://www.mesacor.com.br/Produtos/Ver/".$pagina->acao."/?utm_source=TDC&utm_campaign=".$_Ref."&utm_term=".$pagina->acao);
	}
	if($pagina->tg == 'M'){
			
		header("Location: http://www.mesacor.com.br/Produtos/Ver/".$pagina->id."/?utm_source=Mailing&utm_campaign=".$pagina->acao);
	}
	if($pagina->tg == 'F'){
			
		header("Location: http://www.mesacor.com.br/Produtos/Ver/".$pagina->acao."/?utm_source=Facebook");
	}
	if($pagina->tg == 'Twitter' || $pagina->tg == 'Tt'){
			
		header("Location: http://www.mesacor.com.br/Produtos/Ver/".$pagina->acao."/?utm_source=Twitter&utm_campaign=PostAutomatico");
	}
	if($pagina->tg == 'Twitter' || $pagina->tg == 'Ti'){
			
		header("Location: http://www.mesacor.com.br/Produtos/Ver/".$pagina->acao."/?utm_source=Twitter&utm_campaign=Recomendacao");
	}
	if($pagina->tg == 'Twitter' || $pagina->tg == 'Tw'){
			
		header("Location: http://www.mesacor.com.br/Produtos/Categoria/".$pagina->acao."/?utm_source=Twitter&utm_campaign=PostAutomatico");
	}
	if($pagina->tg == 'O'){
			
		header("Location: http://www.mesacor.com.br/Produtos/Ver/".$pagina->acao."/?utm_source=Orkut&utm_campaign=Amd");
	}
	if($pagina->acao == 'Churrasco' or $pagina->id == 'Churrasco'){
			
		header("Location: http://churrasco.mesacor.com.br/");
	}
	if($pagina->tg == 'Produtos' and $pagina->acao == 'Categoria' and preg_match('/Churrasco/i',$pagina->id)){
			
		header("Location: http://churrasco.mesacor.com.br/Categoria/".preg_replace("/Churrasco-/i","",$pagina->id)."");
	}
	if($pagina->tg == 'Produtos' and $pagina->acao == 'Linha' and preg_match('/Churrasco/i',$pagina->id)){
			
		header("Location: http://churrasco.mesacor.com.br/");
	}
	
	
	if($pagina->acao == 'Rock-n-Cook' or $pagina->id == 'Rock-n-Cook'){
			
		header("Location: http://Rock-n-Cook.mesacor.com.br/");
	}
	if($pagina->tg == 'Produtos' and $pagina->acao == 'Categoria' and preg_match('/Rock-n-Cook/i',$pagina->id)){
			
		header("Location: http://Rock-n-Cook.mesacor.com.br/Categoria/".preg_replace("/Rock-n-Cook-/i","",$pagina->id)."");
	}
	if($pagina->tg == 'Produtos' and $pagina->acao == 'Linha' and preg_match('/Rock-n-Cook/i',$pagina->id)){
			
		header("Location: http://Rock-n-Cook.mesacor.com.br/");
	}
	
	if($pagina->tg == 'Cliente' and !$_SERVER["HTTPS"]){
			
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
		$hotsite = false;
	}
}

$HOME = preg_match('/Home/i',$canal->url) ? true : false ;


	if($pagina->id == "Limpar"){unset($_SESSION['idpedidos']);}

//pre($_COOKIE);

//if(!preg_match("/googlebot/i",$_SERVER['HTTP_USER_AGENT'])){
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
					echo "Pedido inv·lido";
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
				
				if($pedido->estagios->id > 1 and $pedido->estagios->id < 7){
					setcookie("pedidoID",$_COOKIE['idpedidos'],time()-(60*60*24*30),'/');
				}
			}
		}
		
		
		
		if(isset($_COOKIE['cupomID'])){
			$cupom = new objetoDb('cupons',$_COOKIE['cupomID']);
		}

		//if($_GET['cupom']){
		//	$cupom = recuperarCupom($_GET['cupomID']);
		//}
		

	
	if(!isset($_SESSION['itensVisitados'])){
		$_SESSION['itensVisitados'] = array();
	}
//}
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
<title><?PHP echo trim($pagina->configs['titulo_site'].' '.$titulo);?></title>
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
<link href='https://fonts.googleapis.com/css?family=Open+Sans|Open+Sans+Condensed:300' rel='stylesheet' type='text/css' />
<link href="<?PHP echo $pagina->localhost;?>css/<?PHP
	echo ($FACEBOOK ? "facebook":
		  ($hotsite ? strtolower(diretorio($hotsite->url)):
										   ( preg_match("/(cliente|retorno)/i",$canal->url) ? "cliente13c" :
														($estiloDC ? "site10anosDCb8":"site2013_10e")
											)
			)
		)?>.css" rel="stylesheet" type="text/css" media="all" />
		
		
<script type="text/javascript" src="https://www.google.com/jsapi?key=ABQIAAAAbJ1kffsMyFnMaZ4PDuDS0BSC371tnk2SZdEOQM3zbULZ6eh5BRTxb1Bg-nAjYh2NAUyEpqMp0D6VTw"></script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js" type="text/javascript"></script>



<script src="<?PHP echo $pagina->localhost;?>proto.js/?sc=jquery.raty,jquery.validate,jquery.cycle.lite,jquery.maskedinput.min" type="text/javascript"></script>
<?PHP if(1){?>
<script src="<?PHP echo $pagina->localhost.filemtime("_scripts/_geral.php")?>_geral.js" type="text/javascript"></script>
<script src="<?PHP echo $pagina->localhost;?>_scripts/carrinho.js?t=<?=time()?>" type="text/javascript"></script>
<?PHP }else{ ?>
<script src="<?PHP echo $pagina->localhost;?>_scripts/geral_dcfba1fe1901764550a9da44274f3749.js" type="text/javascript"></script>
<?PHP } ?>





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
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
	  
	  
	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'stats.g.doubleclick.net/dc.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
    
    </script>
<?PHP }?>
</head>
<?PHP 									
										//$cache->write('header');

									//}?>
<body>
<div id="containerSite">
  <div id="site">
    <div id="topo">
      <h1><a href="<?PHP echo $pagina->ABSURL?>" title="Mesacor - P·gina Inicial">Mesacor - P·gina Inicial</a></h1>
      <?PHP if(!$estiloDC and $canal->tipos_de_canais->id != 6 and $canal->tipos_de_canais->id != 1
			   and !preg_match('/Lista-de-Casamento/',$canal->url) and !preg_match('/Cha-de-Panela/',$canal->url) and !preg_match('/Lista-de-Casamento/i',$canal->url)
				and !preg_match('/Retorno/',$canal->url)
			   and !preg_match('/Cliente/',$canal->url)
			   and !$FACEBOOK){?>
      <div id="contato">
        <ul>
          <li><a href="<?PHP echo $pagina->localhost?>Contato">&raquo; Ajuda</strong></a></li>
          <li><a href="<?PHP echo $pagina->localhost?>Contato">&raquo; Atendimento <strong>(47) 3056-6479</strong></a></li>
        </ul>
      </div>
      <? }?>
	<? if(!$FACEBOOK){?>
      <div id="cliente">
        <div id="busca">
          <div id="spanBusca">Busca</div>
          <form action="<?PHP echo $pagina->localhost.( $hotsite->url ? "" : "Produtos/")?>Busca" method="post">
            <input name="q" id="campobusca1" type="text" value="<?PHP echo $busca?>" />
            <button type="submit" class="submitBusca">OK</button>
          </form>
        </div>
        <div id="submenu">
          <?PHP if($cadastro->conectado()){?>
          <ul>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente"><strong><?PHP echo $cadastro->nome?></strong> |</a></li>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente/Cadastro">Meu Cadastro</a></li>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente/Carrinho">Meu Carrinho <?PHP echo (count($_SESSION['itensCarrinho']) ? " <strong>(".array_sum($_SESSION['itensCarrinho']).")</strong>": '')?> </a></li>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente/Pedidos">Minhas Compras</a></li>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente/Sair">Sair</a></li>
          </ul>
          <?PHP }else{?>
          <ul>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente/Cadastro">Cadastre-se</a></li>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente/Carrinho">Carrinho de Compras <?PHP echo (count($_SESSION['itensCarrinho']) ? " <strong>(".array_sum($_SESSION['itensCarrinho']).")</strong>": '')?> </a></li>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente">Login</a></li>
          </ul>
          <?PHP }?>
        </div>
      </div>
      <? }?>
      <?PHP if(!preg_match("/(cliente|retorno)/i",$canal->url)){?>
        <?PHP if(!$estiloDC and !$FACEBOOK){ ?>
      <div id="banner-wrapper">
        <?PHP 
	  	$sql = "select * from galerias, tipos_de_banners where ativo = 'sim' and ".($hotsite ? "tipos_de_banners.url = '".diretorio($hotsite->url)."'" : "galerias.idtipos_de_banners = 1")." and tipos_de_banners.idtipos_de_banners = galerias.idtipos_de_banners order by ordem";
		
		
		$db->query($sql);
        if($db->rows){
			$_rows = $db->rows;
              ?>
        <div id="banner">
        <?PHP
                  $i = 1;
                  while($res = $db->fetch()){
                      $obj = new objetoDb('galerias',$res['idgalerias']);
                     foreach($obj->fotos as $foto){
						 echo '<a href="'.$obj->link.'"><img src="'.$pagina->img($foto['id'].($hotsite ? '/988/257/1':'/1920/250/1')).'" title="'.$obj->referencia.'" alt="'.$obj->referencia.'" /></a>';
						 
						 ?>
        <?PHP
                      }
                  }
              }
			  ?>
		</div>
		</div>

      </div>
        <?PHP }?>
      <?PHP if(!$estiloDC){?>
      <div id="faixa" class="nivoSlider">
        <?PHP if(!$estiloDC){
          $db->query("select * from galerias where ativo = 'sim'and idtipos_de_banners = 2 order by ordem");
          if($db->rows){
              $_rows = $db->rows;
              ?>
        <?PHP
                  $i = 1;
                  while($res = $db->fetch()){
                      $obj = new objetoDb('galerias',$res['idgalerias']);
                      foreach($obj->fotos as $foto){ ?>
        <a href="<?PHP echo $obj->link?>"  onclick="_gaq.push(['_trackEvent', 'Banner', 'Click', '<?PHP echo $obj->referencia?>']);"> <img src="<?PHP echo $pagina->img($foto['id'].'/988/30/1')?>" alt="<?PHP echo $obj->referencia?>" /> </a>
        <?PHP
                      }
                  }
              }
      }?>
      </div>
      <?PHP }?>
      <?PHP }?>
    </div>
    
	<? if(!$FACEBOOK){?>
		<? if(0 and $hotsite and $pagina->id ){?>
        <div id="menu">
          <ul id="nav">
            <li><a href="<?=$pagina->localhost.'Especial/'.$hotsite->url?>" class="menuA">&laquo; Voltar para a seleÁ„o especial</a></li>
          </ul>
        </div>
        <? }else{ ?>
        <div id="menu">
          <?PHP //$pagina->menu(array("home"=>1))?>
          <ul id="nav">
            <li><a class="menuA" href="<?=$pagina->localhost?>Home/" ><img src="<?=$pagina->localhost?>_imagens/home.png" alt="" width="20" height="20" /></a></li>
            <li><a class="menuA" href="<?=$pagina->localhost?>Produtos/Categoria/Design-Collection/" >Design Collection</a></li>
            <li><a class="menuA" href="http://churrasco.mesacor.com.br/" >Churrasco</a></li>
            <li><a class="menuA" href="<?=$pagina->localhost?>Produtos/" >Produtos</a></li>
            <li><a class="menuA" href="<?=$pagina->localhost?>Produtos/Categoria/Lancamentos/" >Lan&ccedil;amentos</a></li>
            <li><a class="menuA" href="<?=$pagina->localhost?>Ofertas/" >Ofertas</a></li>
            <li><a class="menuA" href="<?=$pagina->localhost?>Lista-de-Casamento/" >Lista de Casamento</a></li>
            <li><a class="menuA" href="<?=$pagina->localhost?>Cha-de-Panela/" >Ch· de Panela</a></li>
            <li><a href="<?=$pagina->localhost?>Produtos/Categoria/Copos-e-Tacas/" class="menuCoposTacas">Copos e TaÁas</a></li>
            <li><a href="<?=$pagina->localhost?>Produtos/Categoria/Cafeteiras-Italianas/" class="menuCafeteiras" >Cafeteiras Italianas</a></li>
          </ul>
    </div>
        <? }?>
    <? }?>
    <div id="container">
      <?PHP if($canal->tipos_de_canais->id != 6 and $canal->tipos_de_canais->id != 1
			    and !preg_match('/Cadastro-Promocao/',$canal->url) 
				and !preg_match('/Cha-de-Panela/',$canal->url) and !preg_match('/Retorno/',$canal->url) and !preg_match('/Cliente/',$canal->url)){?>
      <div id="categorias">
        <?PHP 								
									//if(!$cache->get("menu_cat_".$categoria->id)){//$pagina->id)){
?>
        <?PHP
			
			function categoriasPol( $pai , $prof , $catUrl = NULL ){
				global $db,$pagina,$lista,$categoria,$canal,$hotsite;
				$sql = "select idcategorias from categorias where idcategorias_2 = $pai order by categoria";
				$db->query($sql);
				if($db->rows){
					$ret = '';
					$ret .= '
					<ul>';
					
					if($hotsite->url){
						$ret .= '
						<li id="cat_'.$cat->id.'"><a href="'.$pagina->localhost.( $hotsite->url ? "" : $canal->url).'">Principal</a></li>';
					}
					while($res = $db->fetch()){
						$resour = $db->resourceAtual;
						$cat = new objetoDb('categorias',$res['idcategorias']);
						$ret .= '
						<li id="cat_'.$cat->id.'" class="cat" name="'.diretorio($catUrl).'">
						<a href="'.$pagina->localhost.( $hotsite->url ? "" : "Produtos/")."Categoria/".( $hotsite->url ? preg_replace("/".diretorio($hotsite->url)."-/i","",$cat->url): $cat->url).'">'.htmlentities($cat->categoria).'</a>';
						//<a href="'.$pagina->localhost.( $hotsite->url ? "" : $canal->url)."Categoria/".( $hotsite->url ? preg_replace("/".diretorio($hotsite->url)."-/i","",$cat->url): $cat->url).'">'.htmlentities($cat->categoria).'</a>';
						 $ret .= categoriasPol($cat->id,$prof+1,$cat->url);
						$db->resource($resour);
						$ret .= '</li>';
					}
					$ret .= "
					</ul>";
					return $ret;
				}
			}
			
			
			if($hotsite->url == "Churrasco/"){
				echo categoriasPol(30, 1);
			}elseif($hotsite->url == "Cook/"){
				echo categoriasPol(157, 1);
			}elseif($estiloDC){
				echo '<div class="clear espaco"><h3>Design Collection</h3></div>';
				echo categoriasPol(33, 1);
				echo '<div class="clear espaco"><h3>Linha Tramontina</h3></div>';
				echo categoriasPol(0, 1);
			}else{
				echo categoriasPol(0, 1);
			}?>
        <?PHP if(!$estiloDC and !$hotsite){?>
        <div id="cadastrese">
          <form action="<?PHP echo $pagina->localhost.( $hotsite->url ? "" : "Produtos/")?>Login" method="post" id="cadastreseForm" onsubmit="return sendWindowForm('cadastrese' ,'cadastreseForm')">
            <input name="acao" id="acao" type="hidden" value="enviarNewsletter" />
            <h3>Cadastre em nossa Newsletter</h3>
            <div class="campo">
              <input name="nome" type="text" value="Nome" class="required inputField inputMedio" />
            </div>
            <div class="campo">
              <input name="email" type="text" value="E-mail" class="required inputField inputMedio" />
            </div>
            <div class="campo">
              <button type="submit" class="submitButton">OK</button>
            </div>
          </form>
        </div>
        <div id="twitter"> <a href="http://twitter.com/mesacor">Ofertas exclusivas e novidades na hora, siga no Twitter @mesacor</a> </div>
        <?PHP }?>
      </div>
      <?PHP }?>
      <?PHP echo $outHtml; ?>
    </div>
      <div class="clear"></div>
  </div>
      <div class="clear"></div>
 <? if(!$estiloDC and !$FACEBOOK and 0){?>
    <div id="floatBox" style="display:none;">
        <div id="busca">
          <form action="<?PHP echo $pagina->localhost.( $hotsite->url ? "" : "Produtos/")?>Busca" method="post">
            <div id="spanBusca">Busca</div>
            <input name="q" id="campobusca2" type="text" value="<?PHP echo $busca?>" />
            <button type="submit" class="submitBusca">OK</button>
          </form>
        </div>
        <?php /*?><div id="tagLink">
        <a href="javascript:$('#floatLinhas').fadeToggle(); void(0);" onclick="_gaq.push(['_trackEvent', 'Tags', 'exibir', '']);" class="awesome grey">Tags - Termos mais buscados</a>
        </div><?php */?>
        <div id="submenu">
          <?PHP if($cadastro->conectado()){?>
          <ul>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente"><strong><?PHP echo $cadastro->nome?></strong> |</a></li>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente/Cadastro">Meu Cadastro</a></li>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente/Carrinho">Meu Carrinho <?PHP echo (count($_SESSION['itensCarrinho']) ? " <strong>(".array_sum($_SESSION['itensCarrinho']).")</strong>": '')?> </a></li>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente/Pedidos">Minhas Compras</a></li>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente/Sair">Sair</a></li>
          </ul>
          <?PHP }else{?>
          <ul>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente/Cadastro">Cadastre-se</a></li>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente/Carrinho">Carrinho de Compras <?PHP echo (count($_SESSION['itensCarrinho']) ? " <strong>(".array_sum($_SESSION['itensCarrinho']).")</strong>": '')?> </a></li>
            <li><a href="<?PHP echo $pagina->localhost?>Cliente">Login</a></li>
          </ul>
          <?PHP }?>
        </div>
        <div id="floatLinhas" style="display:none">
        <div id="floatLinhasContent">
          <?
				echo $tags;
				?>
                </div>
        </div>
      </div>
<? }?>
      <div class="clear"></div>

 <? if(!$FACEBOOK){?>
  <div id="rodape">
    <div id="rodapeAtendimento">
      <h2>Atendimento</h2>
      <ul>
        <li><a href="<?PHP echo $pagina->localhost?>Contato">&raquo; Fale conosco pelo fone <strong>(47) 3056-6479</strong></a></li>
        <li><a href="<?PHP echo $pagina->localhost?>Contato">&raquo; Visite nossas lojas:<br />
          <strong>Matriz</strong><br />
          Av. Brasil n∫ 2431 - Centro<br />
          Balne·rio Cambori˙ - SC<br />
          <strong>Filial</strong><br />
          Av. Central, n∫ 700 - Centro<br />
          Balne·rio Cambori˙ - SC</a><br />
		  <strong>MESACOR COMERCIO DE UTILIDADES DOMESTICAS LTDA-ME</strong><br />
		  <strong>CNPJ 04.348.766/0001-37</strong></li>
      </ul>
    </div>
    <div id="rodapeProdutos">
      <h2>Produtos e Servi&ccedil;os</h2>
      <ul>
        <li><a href="http://www.mesacor.com.br/Produtos/Categoria/Design-Collection/">Design   Collection</a></li>
        <li><a href="http://www.mesacor.com.br/Produtos/Categoria/Lancamentos/">Lan&ccedil;amentos</a></li>
        <li><a href="http://www.mesacor.com.br/Ofertas/">Ofertas</a></li>
        <li><a href="http://www.mesacor.com.br/Produtos/Categoria/Saldo/">Saldo</a></li>
        <li><a href="http://www.mesacor.com.br/Lista-de-Casamento/">Lista de Casamento</a></li>
        <li><a href="http://www.mesacor.com.br/Cha-de-Panela/">Lista de Ch· de Panela</a></li>
      </ul>
    </div>
    <div id="rodapeAjuda">
      <h2>Ajuda</h2>
      <ul>
        <li><a href="http://www.mesacor.com.br/Sobre/">Sobre a Mesacor</a></li>
        <li><a href="http://www.mesacor.com.br/Privacidade/">Privacidade</a></li>
        <li><a href="http://www.mesacor.com.br/Trocas-e-Devolucoes/">Trocas e   Devolu&ccedil;&otilde;es</a></li>
        <li><a href="http://www.mesacor.com.br/Seguranca/">Seguran&ccedil;a</a></li>
        <li><a href="http://www.mesacor.com.br/Formas-de-Pagamento/">Formas de   Pagamento</a></li>
        <li><a href="http://www.mesacor.com.br/Contato/">Contato</a></li>
      </ul>
    </div>
    <div id="rodapeSeguranca">
	<h2>Formas de Pagamento</h2>
	<img src="<?PHP echo $pagina->localhost?>_imagens/formas_pagamento2013.png" width="324" height="39" alt="Formas de Pagamento Boleto, Visa, Master, Amex, Diners e Elo" />
	<h2>Acompanhe pelas Redes Sociais</h2>
	<a href="http://www.eitter.com/mesacor"><img src="<?PHP echo $pagina->localhost?>_imagens/1329481140_social_twitter_box_blue.png" width="30" height="30" alt="Twitter Mesacor" /> Twitter <strong>@Mesacor</strong></a>
	<a href="http://www.facebook.com/mesacor"><img src="<?PHP echo $pagina->localhost?>_imagens/1329481145_social_facebook_box_blue.png" width="30" height="30" alt="Facebook Mesacor" /> Facebook <strong> /Mesacor</strong></a>
	<a href="http://blog.mesacor.com.br"><img src="<?PHP echo $pagina->localhost?>_imagens/1329481159_social_rss_box_orange.png" width="30" height="30" alt="Blog Mesacor" /> Blog <strong>blog.mesacor.com.br</strong></a>
	<h2>SeguranÁa e Credibilidade</h2>
	<a id="seloEbit" href="http://www.ebit.com.br/#mesa-cor" target="_blank" onclick="redir(this.href);">Avaliação de Lojas e-bit</a>
<script type="text/javascript" id="getSelo" src="https://558701205.r.anankecdn.com.br/ebitBR/static/getSelo.js?11266" >
</script> 
<?php /*?>      <h2>SeguranÁa e Credibilidade</h2>
      <?PHP if(0 and !preg_match('/localhost/i',$_SERVER['HTTP_HOST'])){?>
      <script type="text/javascript" src="https://seal.thawte.com/getthawteseal?host_name=www.mesacor.com.br&amp;size=S&amp;lang=br"></script>
      <?PHP }?>
      <div id="divSeg"> <a href="http://www.ebit.com.br/ebit/rate/asp/index_empresa.asp?Empresa=1112665" title="Clique aqui para ver a avalia&ccedil;&atilde;o desta loja." onclick="_gaq.push(['_trackEvent', 'LinkExterno', 'eBit', '<?PHP echo $obj->referencia?>']);">e-Bit</a> </div>
      <div id="divCert"> <a href="https://sealinfo.thawte.com/thawtesplash?form_file=fdf/thawtesplash.fdf&amp;dn=WWW.MESACOR.COM.BR&amp;lang=br" title="Clique aqui para conferir o certificado de seguranÁa" onclick="_gaq.push(['_trackEvent', 'LinkExterno', 'Certificado', '<?PHP echo $obj->referencia?>']);">Site Seguro</a> </div>
<?php */?>    </div>
    <?php /*?><div id="rodapeAviso">
      <h2>* Frete gr·tis para: </h2>
      <ul><?php */?>
        <?php /*?><li><a href="http://www.mesacor.com.br/Frete_Gratis/">compras acima de R$<?=$pagina->configs['limite_frete_gratis']?>,00 para os estados de: RJ, SP, PR e RS</a></li><?php */?>
        <?php /*?><li><a href="http://www.mesacor.com.br/Frete_Gratis/">todos os produtos da linha Tramontina Design Collection para TODO O BRASIL</a></li><?php */?>
        <?php /*?><li><a href="http://www.mesacor.com.br/Frete_Gratis/">para todos os pedidos com destino em SANTA CATARINA</a></li><?php */?>
        <?php /*?><li><a href="http://www.mesacor.com.br/Frete_Gratis/">EXCETO para Televendas</a></li>
        <li><a href="http://www.mesacor.com.br/Formas-de-Pagamento/">descontos de atÈ 10% ‡ vista somente nos pagamentos com boleto. No cart„o de crÈdito em 1 vez n„o È considerado "‡ vista"</a></li>
      </ul>
    </div><?php */?>
    <div id="rodapeInstitucional"> <span>&copy; 2013 - Mesacor - Todos os direitos Reservados - Tramontina È uma marca registrada da Tramontina SA</span> </div>
    
    <?PHP if(!preg_match("/(cliente|retorno)/i",$canal->url)){?>
<?php /*?>	<div id="rodapeSocial">
      <iframe src="https://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2FMesacor&amp;width=350&amp;height=290&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=true&amp;appId=244455668952934" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:350px; height:290px;" allowtransparency="true"></iframe>
    </div>
<?php */?>	<? }?>
<?php /*?>    <div id="rodapeAssinatura">
      <div id="flashMazaya" class="flashReplace" title="<?PHP echo $pagina->localhost?>_flash/mazaya.swf"><a href="http://www.mazaya.com.br" >mazaya</a></div>
    </div>
<?php */?>  </div><? }?>
</body>
</html>

<? //pre($_SESSION//); ?>