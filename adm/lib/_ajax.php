<?
if( ereg('n2design.com.br',$_SERVER['HTTP_HOST'])){
	$root = "/home/noxsulivan/n2design.com.br";
}else{
	$root = $_SERVER['DOCUMENT_ROOT'];
}
if(file_exists("$root/_inc/funcoes.php")){
include("$root/_inc/funcoes.php");
include("$root/_inc/classes.php");
include("$root/_inc/aut_site.php");
include("$root/_inc/conecta.php");
include("$root/_inc/classes_formulario.php");
include("$root/_inc/classes_canal.php");
}else{
echo "<pre>";print_r($_SERVER);echo "</pre>";
}

session_start();
$pagina = new layout($tg);


header("Content-Type: text/html; charset=ISO-8859-1",true);
		
switch($acao){
	case(adiciona_pedido):
			$sql = "update canais set status='sim' where idcanais = '$id'";
	break;
	case(retorna_representante):
		
			$sql = "select * from  estados, representantes
			where
				estados.uf = '$uf' and
				representantes.idestados = estados.idestados
			order by endereco desc";
			$qr = mysql_query($sql) or die(mysql_error());
			if(mysql_num_rows($qr)){
			while($res = mysql_fetch_array($qr)){
				echo '
				<h3>'.$res[nome].'</h3><p>'.
				nl2br($res[endereco]).'<br>
				Fone: '.$res[telefone].'<br>
				E-mail: <a href="mailto:'.$res[email].'">'.$res[email].'</a></p>
				';
			}
			}else{
				$sql = "select * from estados where uf = '$uf'";
				$qr = mysql_query($sql) or die(mysql_error());
				$res = mysql_fetch_array($qr);
				echo "<h3>Infelizmente não contamos com nenhum representante para ".$res['estado']."</h3>";
			}
	break;
	case(produto):
	
						$sql = "select produtos.*
						from produtos
						where produtos.idprodutos = '".$id."'";
						$qr = db_query($sql);
						$res = mysql_fetch_array($qr);
						
						$_SESSION[origem] = $_SERVER['QUERY_STRING'];
						
							//<h2>'.$res[produto].'</h2>	
						$produto = '
							<h1>'.$res[codigo].'</h1>
							'.$res['descricao_longa'.$_SESSION['lang']];
						
						
						
						
						$sql = "select * from fotos where idprodutos = '".$id."' order by fotos.ordem";
						$qr = db_query($sql);
						while($imgs = mysql_fetch_array($qr)){
							echo '
							<a href="javascript:showImage(\''.$pagina->localhost."imagem.php?id=".$imgs[0].'&height=600\');" rel="lightbox['.$pagina->id.']" title="'.$res[produto].' &raquo; '.$imgs[legenda].'" alt="'.$imgs[legenda].'" class="produtoImg">
							<img src="'.$pagina->localhost."imagem.php?id=".$imgs[0].'&width=200" alt="'.$imgs[legenda].'"/>
							<span>'._r('Clique para ampliar').'</span>
							</a>';
						}
						
						echo $produto;
						
						
						
						$_SESSION[produto] = $produto;
	break;
	
	case(marcarComparacao):
		if(!isset($_SESSION['itensComparacao'])){
			$var = array();
			$_SESSION['itensComparacao'] = $var;
		}
		if($_SESSION['itensComparacao'][] = $_REQUEST['id'])
			echo "ok";
		else
			echo "erro";
	break;
	
}
?>