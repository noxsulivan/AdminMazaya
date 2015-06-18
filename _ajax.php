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
			Este e-mail foi enviado através do site '.$pagina->localhost.'</strong><br>
			Página: <strong>"'.$canal->canal.'"</strong><br>
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
										  <h3>Não foi possível enviar a mensagem neste momento. Por favor não desanime e tente novamente.</h3>';

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
				<p>Seu e-mail foi cadastrado em nosso sistema, mas para efetivar a sua assinatura, é preciso que você confirme sua intenção acessando o link:<p>
				<p><a href="'.$pagina->localhost.'Newsletter/Confirma/'.$_POST['chave'].'">'.$pagina->localhost.'Newsletter/Confirma/'.$_POST['chave'].'</a></p>
				<p>Se você não consegue clicar no link, copie e cole na barra de endereço:</p>
				<p>'.$pagina->localhost.'Newsletter/Confirma/'.$_POST['chave'].'</p>
				<p>A Equipe Capri Turismo, agradece a sua participação</p>
				';
				if(@n2_mail($_REQUEST['email'],"Cadastro na newsletter ".$pagina->localhost,$corpo,$canal->atributos["Email destino"],$_FILES))
					$ret .=  "Você receberá um e-mail de confirmação. Siga as instruções nele para confirmar seu cadastro. Obrigado";
				else
					$ret .=  "Este não é um e-mail válido";
									
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