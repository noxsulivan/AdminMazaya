<?
extract($_POST);
switch($admin->acao){
	case "publicar":
		$ret = "O envio do mailing foi agendado com sucesso e será executado durante as próximas horas de acordo com a quantidade de destinatários.";
		
	break;
	case 'uploadFotos':
		if($_FILES){
			$tg = split('@',$_REQUEST['tabela']);
			$tg = $tg[0];
			if($usuario->clientes->id and 0){
				$db->query('select idfotos from fotos where id'.$tg.' = "'.$_REQUEST['id'].'"');
				if($db->rows >= 12){
					$ret = "-1";
					break;
				}
			}
			$files = $_FILES["arquivo"];
			
			$cache =  $_siteRoot."_cache/".diretorio($files["tmp_name"]);
			
			if(function_exists("exif_read_data"))
				$exif = @exif_read_data($files["tmp_name"], 0, true);
				
			$img_db = imagecreatefromstring( file_get_contents($files["tmp_name"]) );
			
			$width_orig	=	imagesx( $img_db );
			$height_orig	=	imagesy( $img_db );
			$width 	= 1200;
			$height = 1200;
			$scale	=	min($width  / $width_orig,	$height /  $height_orig);
			
			if($scale < 1){
				$width	=	floor( $scale * $width_orig );
				$height =	floor( $scale * $height_orig );
			}else{
				$width	=	$width_orig ;
				$height =	$height_orig ;
			}
			$image_p = imagecreatetruecolor($width, $height);
			imagecopyresampled($image_p, $img_db, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
			imagejpeg( $image_p,$cache, 80 );
			
				if(!$files["error"]){
					
					$_tmp = getimagesize($cache);
					
					$sql = "insert into fotos set
					chave = '".$chave."',
					nome_arquivo = '".$files["name"]."',
					arquivo = '".mysql_escape_string(file_get_contents($cache))."',
					filetype= '".$_tmp["mime"]."',
					size = '".filesize($cache)."',
					dimensoes = '".$_tmp["3"]."',
					width = '".$_tmp[0]."',
					height = '".$_tmp[1]."',
					exif = '".mysql_escape_string(serialize($exif))."'";
					
					//id".$tg." = '".$_REQUEST['id']."',
					$db->query($sql) or die(mysql_error());
					if($db->error)
						$ret .= $db->error;
					else{
						$ret .= $db->inserted_id;
					}
				}else{
					pre($files);
				}
		}else{
			$ret .= "Nenhum arquivo foi submetido";
		}
	break;
	case 'atualizarLegenda':
		$_POST['legenda'] = preg_replace("/[^a-zA-Z0-9; .,<>&]/", "", htmlentities(utf8_decode($_REQUEST['l'])));
		$db->editar('fotos',$_REQUEST['id']);
		$ret .= 'ok';
	break;
	case 'uploadArquivo':
		if($_FILES){
		
			$file = $_FILES["arquivo"];
			$tmp = explode(".", $file["name"]);
			$path = $admin->root.'_files/'.md5($file["name"]."-".time());
			$handle = fopen($path, "w");
			fwrite($handle, file_get_contents($file["tmp_name"]));
			//fwrite($handle, gzcompress(file_get_contents($file["tmp_name"])));
			fclose($handle);
			
			
			$_tmp = explode(".",utf8_decode($file["name"]));
			$_ext = ".".$_tmp[count($_tmp)-1];
			$_nome = diretorio(str_replace($_ext,'',utf8_decode($file["name"])));
			$nome = $_nome.$_ext;
			
	
			$ret .= "<li>".$nome.' - '.round($file["size"]/1024,2).'kb</li>';
			
			$sql = "insert into arquivos set
			chave = '".$chave."',
			arquivo = '".$nome."',
			nome_arquivo = '".$nome."',
			path = '".$path."',
			mimetype= '".$file["mimetype"]."',
			tamanho = '".$file["size"]."'";
			
			//$ret .= pre($file,true);
			
			$db->query($sql) or die(mysql_error());
			if($db->error)
				$ret .= $db->error;
			else{
				$ret .= '<input name="arquivos[]" type="hidden" value="'.$db->inserted_id.'" />';
			}
	
		}else{
			$ret .= "<li>Nenhum arquivo foi submetido ou o tamanho arquivo enviado extrapola o limit do servidor que é de ".ini_get('upload_max_filesize')."</li>";
		}
	break;
	case "ordenar":
		$lista = $_REQUEST["lista"] ? $_REQUEST["lista"] : $_REQUEST["thumbnails"];
		$tabela = $_REQUEST["tabela"];
		unset($_POST['ordem']);
		while(list($k,$v) = each($lista)){
			$_POST['ordem'] = ++$j;
			$db->editar($tabela,$v);
		}
		$ret .= "Salvo";
		$usuario->_carregaMenus();
	break;
	case "remover":
		if($db->deletar($tabela,$id)){
			$ret .= "ok";
		}else{
			$ret .= "erro";
		}
		
	break;
	case "removerGrupo":
		
		for($i = 0; $i < count ($registro) ; $i++){
			if($db->deletar($tabela,$registro[$i])){
				$ret .= "ok";
			}else{
				$ret .= "erro";
			}
		}
	break;
	case "atualizar":
		list($tabela,$campo,$id) = explode("__",$admin->id);
		
		$admin = new admin($tabela);
		
		$_POST[$campo] = $_POST['value'];
		
		if($db->editar($tabela,$id)){
			$obj = new objetoDb($tabela,$id);
			if(ereg('float',$admin->campos[$campo]['Type']))
				$ret .= number_format($obj->$campo,2,",",".");
			else
				$ret .= $obj->$campo;
		}else{
			$ret .= "Não foi possível salvar";
		}
	break;
	case "adicionarCampo_filho":
			$admin = new admin($_REQUEST['tabela'].'/adicionarCampo_filho/'. (int)$_REQUEST['id']);
			if($admin->id)
				$admin->registro = new objetoDb($admin->tabela, $admin->id);
			
			$campos = $admin->campos;
			$campoNome = rand();
			$ret .= '<div id="'.$admin->tabela.'_'.$admin->id.'">';
			foreach($campos as $k => $v){
				if(!eregi("pri",$v['Key']) and !ereg("serialized|ordem",$k) and $k != "id".$_REQUEST['tabela_pai']){
					$admin->campo_simples(ucfirst($k),$k,null,null,'filhos['.$_REQUEST['tabela'].']['.$campoNome.']['.$k.']');
					$ret .= $admin->resetHtml();
				}
			}
			$ret .= '<button  type="button" onclick="remover(\''.$admin->tabela.'\',\''.$admin->id.'\')"><img src="'.$admin->localhost.$admin->admin.'imagens/Delete.png">Excluir</button></div>';
			$ret .= $admin->separador();
	break;
	case "adicionarCampo_faixasValores":
			global $admin;
			$admin->_campo_faixasValores($_REQUEST['contador']);
			$ret .= $admin->html;
	break;
	case 'inserirCidade':
		$db->inserir('cidades_marcadas',$admin->id);
		echo "Inserido";
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
			$sql = "select * from cidades where cep = '".str_replace("-","",$_REQUEST['id'])."'";
			$db->query($sql);
			if($db->rows){
				$res = $db->fetch();
				$result['cidade'] = $res['idcidades'];
				$result['estado'] = $res['idestados'];
			}else{
				$sql = "select * from logradouros where cep = '".str_replace("-","",$_REQUEST['id'])."'";
				$db->query($sql);
				if($db->rows){
					$res = $db->fetch();
					$log = new objetoDb('logradouros',$res['idlogradouros']);
					$result['logradouro'] = $log->tipo." ".$log->logradouro;
					$result['estado'] = $log->estados->id;
					$result['cidade'] = $log->cidades->id;
					$result['bairro'] = $log->bairros->id;
				}else{
					$result = 0;
				}
			}
			$ret .= json_encode($result);
	break;
	case "carregaCidades":	
			$sql = "select * from cidades where idestados = '".$_REQUEST['id']."' order by cidade";
			$ret .= '<option>Selecione uma cidade</option>';
			$db->query($sql);
				while($res = $db->fetch())
					$ret .= '
						<option value="'.$res['idcidades'].'"'.(($res['idcidades']==$_REQUEST['selected'])?' selected':'').'>'.$res['cidade'].'</option>';
	break;
	case "carregaBairros":	
			$sql = "select * from bairros where idcidades = '".$_REQUEST['id']."' order by bairro";
			$ret .= '<option>Selecione um bairro</option>';
			$db->query($sql);
				while($res = $db->fetch())
					$ret .= '
						<option value="'.$res['idbairros'].'"'.(($res['idbairros']==$_REQUEST['selected'])?' selected':'').'>'.$res['bairro'].'</option>';
	break;
	case "atualizarDinamico":
			$sql = "select * from ".$admin->tg."";
			$primeiroCampo = $db->primeiroCampo($admin->tg);
			//die($sql);
			$db->query($sql);
				while($res = $db->fetch())
					$ret .= '<a href="">'.$res[$primeiroCampo].'</a><br>';
	break;
	case "autocomplete":
			$ret = '<ul>';
			if($db->campoExiste('codigo',$_REQUEST['tabela'])){
				$sql = "select id".$_REQUEST['tabela'].", codigo,  ".$_REQUEST['campo']." from ".$_REQUEST['tabela']." where codigo like '".$_REQUEST['valor']."%' or ".$_REQUEST['campo']." like '".$_REQUEST['valor']."%'";
			}else{
				$sql = "select id".$_REQUEST['tabela'].", ".$_REQUEST['campo']." from ".$_REQUEST['tabela']." where ".$_REQUEST['campo']." like '".$_REQUEST['valor']."%'";
			}
			$db->query($sql);
				while($res = $db->fetch())
					$ret .= '<li value="'.$res['id'.$_REQUEST['tabela']].'">'.$res['codigo'].' '.$res[$_REQUEST['campo']].'</li>';
			$ret .= '</ul>';
	break;
	default:
			$ret = 'Nada foi feito... você está tentando acessar algo que não é da tua conta.';
	break;
	
}

echo utf8_encode($ret);
?>