<?
extract($_POST);
switch($admin->acao){
	case "publicar":
		$ret = "O envio do mailing foi agendado com sucesso e será executado durante as próximas horas de acordo com a quantidade de destinatários.";
	break;
	case 'giraImagem':
		$foto = new objetoDb('fotos',$id);
		$imagem = @imagecreatefromstring( $foto->arquivo );
		$imagem = imagerotate($imagem,$_REQUEST['angulo'],0);
		
			ob_start();
			imagejpeg( $imagem,'', 100 );
			$img = ob_get_contents();
			ob_end_clean();
			
		$db->query("update fotos set arquivo = '".mysql_escape_string($img)."' where idfotos = '".$foto->id."'");
		$ret .= 'ok';
	break;
	case "colherDados":
	$obj = new objetoDb(str_replace("id","",$admin->id),$admin->extra);
	$ret['tabela'] = $obj->tabela;
	$ret['propriedades'] = $obj->propriedades;
	break;
	case 'atualizarLegenda':
		$_POST['legenda'] = preg_replace("/[^a-zA-Z0-9; .,<>&]/", "", htmlentities(utf8_decode($_REQUEST['l'])));
		$db->editar('fotos',$_REQUEST['id']);
		$ret .= 'ok';
	break;
	case 'uploadFotos':
		if($_FILES){
			$file = $_FILES["arquivo"];
			$tg = explode('@',$_REQUEST['tabela']);
			$tg = $tg[0];
								if($usuario->clientes->id and 0){
									$db->query('select idfotos from fotos where id'.$tg.' = "'.$_REQUEST['id'].'"');
									if($db->rows >= 12){
										$ret = "-1";
										break;
									}
								}
			$db->inserir_foto($file['name'],$file['tmp_name']);
			$ret['id'] = $db->inserted_id;
			$ret['nome_arquivo'] = $file['name'];
			$ret['tamanho'] = round($file["size"]/1024,1).'kb';
			$ret['legenda'] = $db->erro;
		}else{
			$ret['status'] = "erro";
			$ret['msg'] = "Nenhum arquivo foi submetido ou o tamanho arquivo enviado extrapola o limit do servidor que é de ".ini_get('upload_max_filesize')."";
		}
	break;
	case 'uploadArquivo':
		if($_FILES){
		
			$file = $_FILES["arquivo"];
			$tmp = explode(".", $file["name"]);
			$path = $admin->_serverRoot.'_files/'.date("Ymd").'_'.md5($file["name"]."-".time());
			$handle = fopen($path, "w");
			fwrite($handle, file_get_contents($file["tmp_name"]));
			//fwrite($handle, gzcompress(file_get_contents($file["tmp_name"])));
			fclose($handle);
			
			
			$_tmp = explode(".",utf8_decode($file["name"]));
			$_ext = ".".$_tmp[count($_tmp)-1];
			$_nome = diretorio(str_replace($_ext,'',utf8_decode($file["name"])));
			$nome = $_nome.$_ext;
			
			$sql = "insert into arquivos set
			chave = '".$chave."',
			arquivo = '".$nome."',
			nome_arquivo = '".$nome."',
			path = '".$path."',
			mimetype= '".$file["mimetype"]."',
			tamanho = '".$file["size"]."'";
			
			$db->query($sql) or die(mysql_error());
			$ret['id'] = $db->inserted_id;
			$ret['nome_arquivo'] = $nome;
			$ret['tamanho'] = round($file["size"]/1024,1).'kb';
			$ret['downloads'] = 0;
						
	
		}else{
			$ret['status'] = "erro";
			$ret['msg'] = "Nenhum arquivo foi submetido ou o tamanho arquivo enviado extrapola o limit do servidor que é de ".ini_get('upload_max_filesize')."";
		}
	break;
	case 'uploadCamera':
		if($_REQUEST['imagem']){
		
			
			$file_name = "camera_".time().".jpg";
			$path = $admin->_serverRoot.'_files/'.$file_name;
			
			file_put_contents( $path, base64_decode($_REQUEST['imagem']));
			imagecreatefrompng( $path );
			$img_db = imagecreatefromstring( file_get_contents($path) );
			
			die();
			
			
			$db->inserir_foto($file_name,$path);
			$ret['id'] = $db->inserted_id;
			$ret['nome_arquivo'] = $file_name;
			$ret['tamanho'] = round($file["size"]/1024,1).'kb';
			$ret['legenda'] = $db->erro;
						
	
		}else{
			$ret['status'] = "erro";
			$ret['msg'] = "Nenhum arquivo foi submetido ou o tamanho arquivo enviado extrapola o limit do servidor que é de ".ini_get('upload_max_filesize')."";
		}
	break;
	case "ordenar":
		$lista = $_REQUEST["i"] ;
		$tabela = $_REQUEST["tabela"];
		unset($_POST['ordem']);
		while(list($k,$v) = each($lista)){
			$_POST['ordem'] = ++$j;
			$db->editar($tabela,$v);
		}
		$ret['status'] = "ok";
	break;
	case "remover":
		if($db->deletar($_REQUEST["tabela"],$_REQUEST["id"])){
			$ret['status'] = "ok";
		}else{
			$ret['status'] = "erro";
		}
		
	break;
	case "removerGrupo":
		for($i = 0; $i < count ($registro) ; $i++){
			if($db->deletar($admin->id,$registro[$i])){
				$ret['status'] = "ok";
			}else{
				$ret['status'] = "erro";
			}
		}
	break;
	case "atualizar":
		list($campo,$id) = explode("__",$_POST['id']);
		$tabela = $_REQUEST['tabela'];
		$admin = new admin($tabela);
		if($_POST['value'] == 'Clique para editar'){
			$ret['status'] = "ok";
			$ret['msg'] = "<small>Clique para editar</small>";
		}else{
			//$_POST[$campo] = normaliza($_POST['value']) ;
			$_POST[$campo] = $_POST['value'];
			
			if($sql = $db->editar($tabela,$id)){
				$obj = new objetoDb($tabela,$id);
				$campos = $db->campos_da_tabela($tabela);
				if($campos[$campo]['Key']=='MUL'){
					$_campo = str_replace('id','',$campo);
					$primeiro = $db->primeiroCampo($_campo);
					//$ret['status'] = "ok";
					$ret = $obj->$_campo->$primeiro;
					
				}else{
					$ret = $obj->$campo;
				}
				
				if($campo == "rastreamento"){
							$pedido = new objetoDb("pedidos",$id);
								$corpo = '<h2>Ol&aacute;, '.$pedido->cadastros->nome_completo.'</h2>
								<p>&nbsp;</p>
								<p>Obrigado por escolher a Mesacor para realizar sua compra. Seu pedido foi despachado e você pode seguir a situação de envio diretamente no site dos correios:<p>
								<p>Site: <a href="http://www.correios.com.br/sistemas/rastreamento/default.cfm">http://www.correios.com.br/sistemas/rastreamento/default.cfm</a><br>
								Identificador do Objeto: <strong>'.$_POST['value'].'</strong></p>';
								
								mailClass(trim($pedido->cadastros->email),"Código de rastreio" ,$corpo,"site@mesacor.com.br","Mesacor Tramontina");
				}

			}else{
				$ret['status'] = "erro";
				$ret['msg'] = "Não foi possível salvar";
			}
		}
	break;
	case "adicionarCampo_faixasValores":
			global $admin;
			$admin->_campo_faixasValores($_REQUEST['contador']);
			$ret .= $admin->html;
	break;
	case 'inserirCidade':
		$db->inserir('cidades_marcadas',$admin->id);
		$ret['status'] = "ok";
	break;
	case "atualizarAtributos":
		if($_POST['tipo']){
			$sql = "
			SELECT
				ac.idatributos_de_".$_POST['tabela'].", atributo, transporte, tag, input_type
			FROM
				atributos_de_".$_POST['tabela']." AS ac
				LEFT OUTER JOIN
					".$_POST['tabela']."_has_atributos_de_".$_POST['tabela']." AS chac
					ON chac.idatributos_de_".$_POST['tabela']." = ac.idatributos_de_".$_POST['tabela']."
						AND chac.id".$_POST['tabela']." = '".$_POST['id']."'
				LEFT OUTER JOIN tipos_de_campos AS tc
					ON ac.idtipos_de_campos = tc.idtipos_de_campos
			WHERE idtipos_de_".$_POST['tabela']." = '".$_POST['tipo']."'";
				
			//pre($sql);
			$qr = $db->query($sql);
			while($res = $db->fetch()){
				
				switch($res['tag']){
					case 'textarea':
						$ret .= '
						<label for="'.$campo.'">'.$res["atributo"].'</label>
						<textarea id="atributos['.$res["idatributos_de_".$_POST['tabela'].""].']" name="atributos['.$res["idatributos_de_".$_POST['tabela'].""].']" class="input inputTexto textarea mceNoEditor">'.$res["transporte"].'</textarea>';
					break;
					case 'input':
					default:
						$ret .= '
						<label for="'.$campo.'">'.$res["atributo"].'</label>
						<input type="'.$res["input_type"].'" id="atributos['.$res["idatributos_de_".$_POST['tabela'].""].']" name="atributos['.$res["idatributos_de_".$_POST['tabela'].""].']" class="inputField" value="'.$res["transporte"].'">';
					break;
				}
					
			}
		}
	break;
	case "carregaCEP":	
			$sql = "select * from cidades where cep = '".preg_replace("/[^0-9]/","",$_REQUEST['id'])."'";
			$db->query($sql);
			if($db->rows){
				$res = $db->fetch();
				$ret['status'] = "ok";
				$ret['cidade'] = $res['idcidades'];
				$ret['estado'] = $res['idestados'];
			}else{
				$sql = "select * from logradouros where cep = '".preg_replace("/[^0-9]/","",$_REQUEST['id'])."'";
				$db->query($sql);
				if($db->rows){
					$res = $db->fetch();
					$log = new objetoDb('logradouros',$res['idlogradouros']);
					$ret['status'] = "ok";
					$ret['logradouro'] = $log->tipo." ".$log->logradouro;
					$ret['estado'] = $log->estados->id;
					$ret['cidade'] = $log->cidades->id;
					$ret['bairro'] = $log->bairros->id;
				}else{
					$ret['status'] = "erro ".$sql;
				}
			}
	break;
	case "carregaCidades":	
			$sql = "select * from cidades where idestados = '".$_REQUEST['id']."' order by cidade";
			if($db->query($sql)){
				$ret['status'] = "ok";
				while($res = $db->fetch()){
					$ret['cidades'][$res['idcidades']] = $res['cidade'];
				}
			}else{
				$ret['status'] = "erro";
			}
	break;
	case "carregaBairros":	
			$sql = "select * from bairros where idcidades = '".$_REQUEST['id']."' order by bairro";
			if($db->query($sql)){
				$ret['status'] = "ok";
				while($res = $db->fetch()){
					$ret['bairros'][$res['idbairros']] = $res['bairro'];
				}
					$ret['bairros'][0] = "- Outro";
			}else{
				$ret['status'] = "erro";
			}
	break;
	case "carregarOptions":
	case "carregaOptions":
			$primeiro = $db->primeiroCampo($admin->id);
			$sql = "select id".$admin->id.",".$primeiro." from ".$admin->id."";
			$db->query($sql);
				while($res = $db->fetch())
					//$r[] = '['.$res['id'.$admin->id].',\''.$res[$primeiro].'\']';
					$ret[$res['id'.$admin->id]] = htmlentities($res[$primeiro]);
				//$ret .= '['.implode(',',$r).']';
					//$_json[$res['id'.$admin->id]] = htmlentities($res[$primeiro]);
	break;
	case "atualizarDinamico":
			$sql = "select * from ".$admin->tg."";
			$primeiroCampo = $db->primeiroCampo($admin->tg);
			//die($sql);
			$db->query($sql);
				while($res = $db->fetch())
					$ret .= '<a href="">'.$res[$primeiroCampo].'</a><br>';
	break;
	case "autoComplete":
			$primeiroCampo = $db->primeiroCampo($admin->id);
			if($db->campoExiste('codigo',$admin->id)){
				$sql = "select id".$admin->id.", codigo,  ".$primeiroCampo." from ".$admin->id." where codigo like '".$_REQUEST['term']."%' or ".$primeiroCampo." like '%".$_REQUEST['term']."%'";
			}else{
				$sql = "select id".$admin->id.", ".$primeiroCampo." from ".$admin->id." where ".$primeiroCampo." like '".$_REQUEST['term']."%'";
			}
			$db->query($sql);
			while($res = $db->fetch())
				$_temp[] = array("id"=> $res["id".$admin->id], "value" => trim($res['codigo'].' '.utf8_encode(normaliza($res[$primeiroCampo]))));
				
			$ret = json_encode($_temp);
	break;
	default:
			$ret = 'Nada foi feito... você está tentando acessar algo que não é da tua conta.';
	break;
	
}

	if(is_array($ret)){
		array_walk($ret, 'sanitizaRet');
			
		if($admin->extra == "pre"){
			$buffer = pre($ret,true);
		}else{
			$buffer = json_encode($ret);
		}
	}else{
		$buffer = utf8_encode($ret);
	}
	echo $buffer;
?>