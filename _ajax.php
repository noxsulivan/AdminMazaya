<?
include('ini.php');
ob_start();

	if(isset($_COOKIE['Mesacor_cadastroID'])){
		$cadastro = new cadastro($_COOKIE['Mesacor_cadastroID']);
	}else{
		$cadastro = new cadastro();
	}
	
	pre($cadastro);
	
$pagina = new layout("Home");
$corpo = '';
			
switch($_REQUEST['acao']){
	case 'adicionarFavorito':
		if($_REQUEST['id']){
			if($db->query("insert into cadastros_has_artigos set idcadastros = '".$cadastro->id."', idartigos = '".$_REQUEST['id']."'")){
				$ret .= "ok";
			}else{
				$ret .= "erro";
			}
		}else{
			$ret .= "erro";
		}
	break;
	case 'enviaContato':
			$corpo = '';
				while(list($k,$v) = each($_POST)){
					switch($k){
						case 'acao';
							break;
						default:
							$corpo .= "<strong>".ucfirst($k).":</strong> ".utf8_decode(strip_tags($v))."<br>";
					}
				}
			
			
			$corpo .= '
			<p>
			Este e-mail foi enviado atrav�s do site '.$pagina->localhost.'</strong><br>
			P�gina: <strong>"'.$canal->canal.'"</strong><br>
			Data e hora: <strong>'.date("d/m/Y H:i:s").'</strong><br>
			IP: <strong>'.$_SERVER['REMOTE_ADDR'].'</strong>
			</p>';
			
			$_POST['data'] = date("d/m/Y H:i:s");
			$_POST['mensagem'] = $corpo;
			$db->inserir('formularios');
			
										
			if(n2_mail($pagina->configs["email_suporte"],"Mensagem do site",$corpo,$_REQUEST['email'],$_FILES)){
				$ret .= '
										  <h2>Mensagem enviada</h2>
										  <h3>A mensagem foi enviada com sucesso para nossa equipe. Obrigado</h3>';
			}else{
				$ret .= '
										  <h2>Houve um problema</h2>
										  <h3>N�o foi poss�vel enviar a mensagem neste momento. Por favor n�o desanime e tente novamente.</h3>';

			}
			//if($_POST['novidades']==''){
				break;
			//}
	case "cadastraNewsletter":
	
	
				$_POST['data'] = date("d/m/Y h:i:s");
				$_POST['nome'] = $_REQUEST['nome']." ".$_REQUEST['sobrenome'];
				$_POST['email'] = $_REQUEST['email'];
				$_POST['chave'] = md5($_REQUEST['email'].date("d/m/Y h:i:s"));
				$_POST['idclientes'] = 1;
				$db->inserir('leitores');
				
				$_POST['idleitores'] = $inserted_id;
				$_POST['idsegmentos'] = 4;
				$db->inserir('leitores_has_mailings');
				
				$corpo = '
				<h2>Cadastro na newsletter '.$pagina->localhost.'</h2>
				<p>Seu e-mail foi cadastrado em nosso sistema, mas para efetivar a sua assinatura, � preciso que voc� confirme sua inten��o acessando o link:<p>
				<p><a href="'.$pagina->localhost.'Newsletter/Confirma/'.$_POST['chave'].'">'.$pagina->localhost.'Newsletter/Confirma/'.$_POST['chave'].'</a></p>
				<p>Se voc� n�o consegue clicar no link, copie e cole na barra de endere�o:</p>
				<p>'.$pagina->localhost.'Newsletter/Confirma/'.$_POST['chave'].'</p>
				<p>A Equipe Capri Turismo, agradece a sua participa��o</p>
				';
				if(@n2_mail($_REQUEST['email'],"Cadastro na newsletter ".$pagina->localhost,$corpo,$canal->atributos["Email destino"],$_FILES))
					$ret .=  "Voc� receber� um e-mail de confirma��o. Siga as instru��es nele para confirmar seu cadastro. Obrigado";
				else
					$ret .=  "Este n�o � um e-mail v�lido";
									
	break;
	case "carregaPagina":
	
			//$pagina = new layout($_REQUEST['pagina']);			
			$canal = new objetoDb('canais',$_REQUEST['pagina']);
			
			if($canal->tipos_de_canais->arquivo)
				include($canal->tipos_de_canais->arquivo);
			else
				include("_inexistente.php");
			$ret = $pagina->html;
	break;
	
}
echo $ret;

$ret = ob_get_contents();
header('Content-Length: '.ob_get_length());
ob_end_clean();

echo utf8_encode($ret);
?>