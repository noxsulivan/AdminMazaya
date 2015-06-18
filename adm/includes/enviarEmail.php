<?
	
				$produto = new objetoDb('produtos',$_POST['id']);
				
				$emails = split(',',$_POST['para']);
				
				foreach($emails as $email){
					$corpo = '<h2>Ol&aacute;, '.$email.'</h2>
					<p>&nbsp;</p>
					<p><strong>'.$_POST['nome'].'</strong> esteve em nosso site e indicou para voc&ecirc; a seguinte p&aacute;gina:<p>
					<p><img src="'.$pagina->localhost.'img/'.$produto->fotos[0]['id'].'/200/200/" align="left" vspace="5" hspace="5">
					<strong>Ref:'.$produto->codigo.' - '.$produto->produto.'</strong> '.
					$info.'</p>'.
					($_POST['mensagem'] ? '<p>Disse tamb&eacute;m: <em><strong>'.utf8_decode($_POST['mensagem']).'</strong></em></p>' : '').
					'<p>Link do produto</p>
					<p><a href="'.$pagina->localhost.'Produtos/Ver/'.$produto->id.'?utm_content=IndicacaoProduto&utm_source=Interacao&utm_campaign=Interno&utm_medium=Site">'.$pagina->localhost.'Produtos/Ver/'.$produto->id.'/</a></p>
					<p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua participa&ccedil;&atilde;o</p>';
					
					mailClass(trim($email),$_POST['nome'].utf8_decode(" indica uma página para você"),$corpo,$_POST['email'],$_POST['nome']);
				}
	
				$ret['status'] =  "ok";
				$ret['mensagem'] =  "<h3>A mensagem foi enviada com sucesso.</h3>";
				
				
				$_POST['data'] = date("d/m/Y h:i:s");
				$_POST['remetente'] = $_REQUEST['nome']." ".$_REQUEST['sobrenome'];
				$_POST['email'] = $_REQUEST['email'];
				$_POST['fone'] = $_REQUEST['telefone'];
				$_POST['titulo'] = 'Indicação de página';
				$_POST['mensagem'] = $corpo;
				$db->inserir('formularios');
		  
?>