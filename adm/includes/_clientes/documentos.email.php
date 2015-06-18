<?
$doc = new objetoDb("documentos",$admin->id);


//$arquivo[tmp_name] = $doc->arquivos[0]->path;
//$arquivo[name] = $doc->arquivos[0]->arquivo;
	
				
				
//pdf($buffer,$arquivo[tmp_name],TRUE);

$condominos = $doc->condominos;

//pre($condominos);die();

		foreach($condominos as $cond){
			
					reset($doc->arquivos);
				
					foreach($doc->arquivos as $arq){
						$_arquivos = '<li>'.$doc->documento.' ('.$doc->data.')<br>'.$doc->descricao.'<br><a href="'.$admin->ABSURL.'downl/'.md5($arq->id).'/'.md5($doc->id).'/'.md5($cond->id).'">'.$arq->arquivo.'</a></li>';
					}

					$corpo = '
					<p>'.( (date("H") < 12) ? "Bom dia ": "Boa tarde " ).$cond->nome.'</p>
					<p>Um novo arquivo foi disponibilizado pela Administração do Condomínio Golfville</p>
					<p>Para visualizá-lo clique no link ou copie e cole em seu navegador</p>
					<ul>'.$_arquivos.'</ul>
					<p>Obrigado pela atenção.</p>
					<p>Cordialmente</p>';
										

					if(mailClass($cond->email,"Arquivo do Condomínio GolfVille",$corpo,"no-reply@recantogolfville.com.br","Condomínio Golfville")){
						$ret['status'] =  "ok";
						$ret['mensagem'] =  "A mensagem foi enviada com sucesso.";
					}else{
						$ret['status'] =  "erro";
						$ret['mensagem'] =  "Ocorreu um erro durante a tentativa de envio.".$o ;
					}
					//die($corpo);
	
				//$ret['arquivo'] =  $doc->arquivos;
				//$ret['condominos'] =  $condominos;
				
		}
				
	echo json_encode($ret);
?>