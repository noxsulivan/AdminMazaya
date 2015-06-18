<?


include($_SERVER['DOCUMENT_ROOT'].'/phpqrcode/qrlib.php');
$evento = new objetoDb("eventos",$admin->id);



foreach($evento->eventos_has_convidados as $convidado){


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
		* { font-family:"Gill Sans", "Gill Sans MT", "Myriad Pro", "DejaVu Sans Condensed", Helvetica, Arial, sans-serif;}
		h1 { font-size:18pt; }
		li { font-size:8pt; line-height:.8em;}
		small { font-size:8pt; display:block; line-height:1em;}
		<?
		//echo file_get_contents($_serverRoot.$admin->admin."pdf.css");
		//echo preg_replace("/imagens\//i",$_serverRoot.$admin->admin."imagens/",file_get_contents($_serverRoot.$admin->admin."admin_".$site.".css"));
		//echo file_get_contents($_serverRoot.$admin->admin."admin_".$site.".css");
		?>
		-->
		</style>
		</head>
		<body>
        <div style="text-align:center;">
            <div id=topo>
              <h1>Convites para: <?=str2upper($evento->evento)?></h1>
            </div>
         </div>	
        <div style="display:block;">
              <ul>
              <li>Gerado em: <?=str2upper(date("d/m/Y H:i:s"))?> - Por: <?=$evento->condominos->nome?></li>
              <li>Estes convites são pessoais e intransferiveis e devem ser apresentados no momento de sua entrada no Condomínio Recanto GolfVille.</li>
              <li>É necessária a apresentação de documento pessoal com foto.</li>
              <li>Em cada um está impresso um código de barras único que facilitará seu acesso e contribui com a segurança dos nosso moradores.</li>
              <li>Caso você esteja levando um acompanhante sem convite, este deverá ser anunciado ao Morador que está organizando o evento.</li>
              <li>Se desejar, corte na linha tracejada.</li>
              </ul>
         </div>	
        <table cellpadding="10" cellspacing="10" border="0" width="100%">
			<?
			$_conv['id'] = $convidado->id;
			
			$cartao  = serialize($_conv);
			
			for($i = 1; $i <= $convidado->acompanhantes + 1 ; $i++){
				echo ($i%2 ? "<tr>": "");
				echo '<td align="center" style="border:1px #000 dashed; text-align:center;border-collapse:collapse; vertical-align:top; page-break-inside:avoid;">';
				echo '<small>'.strtoupper($convidado->nome).($i ? "<br>Convite #".$i: "").'</small>';
				
				$_conv['pass'] = md5($cartao.time());
				$carta_acom = serialize($_conv);
				  
				//pre($carta_acom);
				//pre(unserialize($carta_acom));
				
				$imagem = $_SERVER['DOCUMENT_ROOT'].'/convites/convite_'.md5($carta_acom).".jpg";
				QRcode::png( $carta_acom, $imagem, QR_ECLEVEL_L , 6 );
				echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/convites/convite_'.md5($carta_acom).'.jpg" />';
				echo '</td>';
				echo ($i%2 ? "":"</tr>");
			}
			?>
         </table>	
			</body>
			</html>
			<?
			$buffer = utf8_encode(ob_get_clean());
			
			
			$nome_arquivo = "Convite ".diretorio($evento->condominos->nome)." - ".diretorio($evento->evento->evento);
			$arquivo[tmp_name] = $_SERVER['DOCUMENT_ROOT']."/_files/"."Convite_".diretorio($evento->id)."_".diretorio($evento->condominos->nome)."_".diretorio($evento->evento->evento).".pdf";
			$arquivo[name] = "Convite ".diretorio($evento->condominos->nome)." - ".diretorio($evento->evento->evento).".pdf";
				
							
					
					
			pdf($buffer,$arquivo[tmp_name]);
			
		
						
			$emails = split(',',$_POST['para']);
			
				$corpo = '
				<p>Olá '.$convidado->nome.'</p>
				<p>'.$evento->mensagem.'</p>
				<p>Em anexo seguem os convites especiais a serem apresentados na portaria do Condomínio Recanto GolfVille.</p>
				<p>Evento: <strong>'.$evento->evento.'</strong><br>
				Data: <strong>'.$evento->data.'</strong><br>
				Local: <strong>Condomínio Recanto GolfVille - Rua José Konhevalick, 50 - Recanto Santa Andrea - Cambé/PR</strong>
				</p>
				<p>Imprima o arquivo e leve consigo para o evento.</p>
				<p>Para sua entrada no condomínio, é necessário que você informe seus dados pessoais na portaria, por isso,
				agilize o processo preenchendo seus documentos e dados de contato em nossa página de
				acesso restrito, <a href="http:/'.$_SERVER['HTTP_HOST'].'//CadastroConvidados/'.md5($carta).'">clicando aqui</a>.</p>
				';
				
			//echo($buffer);
			//echo($corpo);
			//die();
			
				mailClass($convidado->email,'Convite: '.$evento->evento,$corpo,$evento->condominos->email,$evento->condominos->nome,array($arquivo));
				//mailClass("contato@ngresinas.com.br",utf8_decode($nome_arquivo),$corpo,"sistema@ngresinas.com.br","NGResinas",array($arquivo));

				
}


	$ret['status'] =  "ok";
	$ret['mensagem'] =  "<h3>A mensagem foi enviada com sucesso.</h3>";
	$ret['arquivo'] =  $arquivo;
	echo json_encode($ret);
	
?>