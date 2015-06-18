<?
class db{
	
	public $banco;
	public $erro;
	public $inserted_id;
	public $affected_rows;
	public $rows = 0;
	public $resources;
	public $resourceAtual;
	public $qr;
	public $sql;
	public $tabelas = array();
	public function db($banco,$host,$usr, $senha){
	
		$this->banco = $banco;
			
		$dbh = mysql_pconnect($host,$usr, $senha) or die(mysql_error());
		
		$this->selecionaDb($this->banco);
		$this->sql = array();
		
		
		$this->query("show table status");
		while($res = $this->fetch()){
				$this->tabelas[$res['Name']] = $res;
		}
		//pre($this);die;
		
	}
	public function selecionaDb($db){
		@mysql_select_db($db);// or die("O banco de dados '$db' selecionado não existe ou não está disponível".mysql_error());
	}
	public function query($sql){
		$sql = trim($sql);
		if($_id = array_search($sql,$this->sql) and preg_match('/^(select|show|describe)/i',$sql) ){//ignorando cache de Resources and (preg_match('^select',$sql) or preg_match('^describe',$sql) or preg_match('^show',$sql) )
			$this->resourceAtual = $_id;
			$this->rows = mysql_num_rows($this->qr[$this->resourceAtual]);
			
			//if(mysql_num_rows($this->qr[$this->resourceAtual]))
				//$this->erro = strip_tags("Erro 1: ".mysql_error().$sql);
				
				$this->moveFetch(0);
				
			return true;
		}else{
			if ($qr = mysql_query($sql)){
				if(preg_match('/^(insert|update)/i',$sql)){
					$this->affected_rows[$this->resources] = mysql_affected_rows();
					$this->inserted_id = mysql_insert_id();
				}else{
					$this->resources++;
					
					$this->sql[$this->resources] = $sql;
				
					$this->qr[$this->resources] = $qr;
					$this->resourceAtual = $this->resources;
					$this->rows = @mysql_num_rows($qr);
				}
				$this->lastSql = $sql;
				
				
				//$this->erro = strip_tags("Erro 2: ".mysql_error().$sql);
				return $qr;
			}else{
				$this->erro = strip_tags("Erro 3 : ".mysql_error().$sql);
				return false;
			}
		}
	}
	public function fetch($var=null,$field=null){
		if($var){
			if(is_string($var))
				$this->query($var);
			else
				$this->resource($var);
		}
		if($res = mysql_fetch_assoc($this->qr[$this->resourceAtual])){
			if($field)
			return $res[$field];
			else
			return $res;
		}else{
			//$this->erro = "!Erro no Fetch(): ".mysql_error().$this->sql[$this->resourceAtual]." #".$this->resourceAtual."\r\n-><br><br>".$this->erro."\r\n-><br><br>".mysql_info($this->qr[$this->resourceAtual]);//.pre($this,true);
			$this->moveFetch(0);
			return false;
		}
	}
	public function rows($sql){
		if($sql){
			$this->query($sql);
		}
		return $this->rows;
	}
	public function moveFetch($_id){
		if(mysql_num_rows($this->qr[$this->resourceAtual]) and mysql_data_seek($this->qr[$this->resourceAtual],$_id)){
			return 1;
		}else{
			//pre("A linha $_id não existe no fetch resultante de ");
			//pre($this->sql[$this->resourceAtual]);
			//pre(debug_backtrace());
			return false;
		}
	}
	public function resource($_id){
		$this->resourceAtual = $_id;
	}
	public function prevResource(){
		$this->resourceAtual--;
	}
	public function nextResource(){
		$this->resourceAtual++;
	}
	public function pushResource($_id){
		$this->resourceAtual = $_id;
	}
	public function popResource(){
		$this->resourceAtual--;
	}
	public function erro($str){
		$this->erro .= $str;
	}
	public function mostraSql($_id = null){
		return $this->sql[$this->resourceAtual];
	}
	public function tabelaExiste($tabela){
		
		if($tabela and array_key_exists($tabela, $this->tabelas)){
			return true;
		}
		return false;
	}
	public function campos_da_tabela($tabela){
		
			$_subResource = $this->resourceAtual;
			$this->query("describe $tabela");
			$tb = array();
			while($res = $this->fetch()){
				if($res["Field"] != 'PRI'){
					$tb[$res["Field"]]["Key"] = $res["Key"];
					$tb[$res["Field"]]["Type"] = $res["Type"];
					$tb[$res["Field"]]["Default"] = strlen($res["Default"]) > 0 ? $res["Default"] : "NULL";
					$tb[$res["Field"]]["Null"] = $res["Null"] == 'YES' ? 'YES' : 'NO';
				}
			}
			$this->resource($_subResource);
			return $tb;
	}
	public function indices_da_tabela($tabela){
		if($this->tabelas[$tabela]['Comment'] == 'VIEW'){
			$_tab = preg_replace("/(rel_)/i","",$tabela);
			$this->indices_da_tabela($_tab);
			$this->tabelas[$tabela]['Indexes'] = $this->tabelas[$_tab]['Indexes'];
		}else{
				$_resource = $this->resourceAtual;
				$this->query("show index from ".$tabela."");
				while($res = $this->fetch())
					if( $res['Column_name'] != 'id'.$res['Name'])
						$this->tabelas[$tabela]['Indexes'][preg_replace('/^id/i','',preg_replace('/_2$/i','',$res['Column_name']))] = $res['Column_name'];
				$this->resource($_resource);
		}
		
		return $this->tabelas[$tabela];

	}
	public function indiceExiste($campo,$tabela){
		$this->indices_da_tabela($tabela);
		if(is_array($this->tabelas[$tabela]) and @array_key_exists($campo,$this->tabelas[$tabela]['Indexes']))
			return true;
		else
			return false;
	}
	public function campoExiste($campo,$tabela){
		if($this->tabelaExiste($tabela)){
			if(array_key_exists($campo,$this->campos_da_tabela($tabela)))
			 return true;
		}
		return false;
	}
	public function primeiroCampo($tabela){
		
			$this->query("describe $tabela");
			while($res = $this->fetch()){
				if(!preg_match("/MUL|PRI/i",$res["Key"]))
					return $res["Field"];
			}
			return false;
	}
	public function inserir($tabela, $dados = NULL){
		if(!$dados) $dados = $_POST;
		
		if($return = $this->query($this->geraSql('insert', $tabela, $dados))){
			$this->registraAlteracao('insert',$tabela,$dados,$this->inserted_id);
			return true;
		}else{
			//$this->erro = 'N&atilde;o foi poss&iacute;vel inserir';
			die($this->erro."\n\n<br><br>".$return);
			return false;
		}
	}
	public function editar($tabela, $id = NULL, $dados = NULL){
		if(!$dados) $dados = $_POST;
		if($id) $operacao = 'update';
		else $operacao = 'insert';
		
		$sql = $this->geraSql($operacao, $tabela, $dados, $id);
			if($this->query($sql)){
				//$this->registraAlteracao($operacao,$tabela,$dados,$id);
				unset($_SESSION['objetos'][$id.'@'.$tabela]);
				return $sql;
			}else{
				//$this->erro = 'N&atilde;o foi poss&iacute;vel editar';
				die($sql.$this->erro);
				return false;
			}
	}
	public function deletar($tabela, $id, $dados = NULL){
		if(!$dados) $dados = $_POST;
		
		$sql = "delete from ".$tabela." where id".$tabela." = '".$id."'";
		if($this->query($sql)){
			unset($_SESSION['objetos'][$id.'@'.$tabela]);
			$this->registraAlteracao('delete',$tabela,$dados,$id);
			return true;
		}else{
			$this->erro = 'Não foi possível editar';
			return false;
		}
	}
	public function geraSql($operacao, $tabela, $dados = NULL, $id = NULL){
		
		if(!$files) $files = $_FILES;
		
		//if(!empty($dados['id'.$tabela]) and $operacao == 'update'){
		if($operacao == 'update'){
			//$this->fetch("select id$tabela from $tabela where id$tabela = '".$id."'",'id'.$tabela);
			//if($this->rows == 1){
				$sqlCommand = "update ";
				$sqlTabela = "$tabela set ";
				$sqlCondition = " where id".$tabela." = '".$id."'";
			//}
		}else{
			  $sqlCommand = "insert ";
			  $sqlTabela = "into $tabela set ";
		}
		
		while(list($k,$v) = each($files)){
			if(file_exists($v["tmp_name"])){
				$dados[$k] = file_get_contents($v["tmp_name"]);//'images/'.$nome_arquivo;
			}
		}	
		
		$campos = $this->campos_da_tabela($tabela);
		foreach($campos as $key => $value){
			if($key == 'url' and !is_null($dados[$this->primeiroCampo($tabela)])){
				if($this->campoExiste('id'.$tabela."_2",$tabela) and $dados['id'.$tabela."_2"]  and $tabela == 'categorias'){
					$obj = new objetoDb($tabela,$dados['id'.$tabela."_2"]);
					$url = diretorio($obj->url."-".$dados[$this->primeiroCampo($tabela)]);
				}elseif($tabela == 'noticias'){
					$url = diretorio(trim($dados['data']." ".$dados[$this->primeiroCampo($tabela)]));
				}else{
					if(isset($dados[$key])){
						$url = diretorio($dados[$key]);
					}else{
						$url = diretorio($dados[$this->primeiroCampo($tabela)]);
					}
				}
						$_tmp[] = $key." = '".$url."'";
			}elseif($value["Key"] != 'PRI' and isset($dados[$key])){
				switch($value["Type"]){
					case 'date':
						$_data = explode(" ",in_data($dados[$key]));
						$_tmp[] = $key." = ".( ($_temp = $_data['0']) != "--" ? "'".$_temp."'" : $value["Default"] );
					break;
					case 'datetime':
						$_tmp[] = $key." = ".( ($_temp = in_data($dados[$key])) != "--" ? "'".$_temp."'" : $value["Default"] );
					break;
					case 'longtext':
						$_tmp[] = $key." = '".mysql_real_escape_string($dados[$key])."'";
					break;
					case 'float':
					case 'float unsigned':
						$_tmp[] = $key." = ".($dados[$key] ? "'".str_replace(',','.',floatval($dados[$key]))."'" : $value["Default"]);
					break;
					case 'float(9,3)':
						$_tmp[] = $key." = ".($dados[$key] ? "'".(str_replace('.','',$dados[$key])/1000)."'" : $value["Default"]);
					break;
					case 'float(11,2)':
					case 'float(9,2)':
					case 'float(9,2) unsigned':
						$_tmp[] = $key." = ".($dados[$key] ? "'".str_replace(',','.',str_replace('.','',$dados[$key]))."'" : $value["Default"]);
					break;
					default:
						$_tmp[] = $key." = ".($dados[$key] ? "'".mysql_real_escape_string($dados[$key])."'" : ($value["Default"] === 'NULL' ? 'NULL' : "'".$value["Default"]."'" )) ;
				}
			}
		}
		
		if($this->campoExiste('serializado',$tabela)){
						$_tmp[] = "serializado = '".serialize($dados)."'";
		}
		
		if(is_array($_tmp))
			$sqlStatements = implode(', ',$_tmp);
		$sql =  $sqlCommand.$sqlTabela.$sqlStatements.$sqlCondition;
		return $sql;
	}
	public function inserirCampo_filhos($tabela,$id){
		if(isset($_POST['filhos']) and is_array($_POST['filhos'])){
			foreach($_POST['filhos'] as $_tabela => $registro){
				foreach($registro as $_registro){
					$_registro['id'.$tabela] = $id;
					$this->inserir($_tabela,$_registro);
				}
			}
		}
	}
	public function filhos($tabela,$id){
		if(isset($_POST['filhos']) and is_array($_POST['filhos'])){
			foreach($_POST['filhos'] as $_tabela => $registro){
				foreach($registro as $_id => $_registro){
					$_registro = array_merge($_POST,$_registro);
					$_registro['id'.$tabela] = $id;
					if(preg_match("/_novo_/i",$_id)){
						$this->inserir($_tabela,$_registro);
					}else{
						$this->editar($_tabela,$_id,$_registro);
					}
				}
			}
		}
	}
	public function salvar_fotos($tabela,$id){
		if($_POST["fotos"]){
			foreach($_POST["fotos"] as $foto){
				$_POST['id'.$tabela] = $id;
				$_POST['id'] = $id;
				$_POST['tabela'] = $tabela;
				$_POST['status'] = 'sim';
				$this->editar("fotos",$foto);
				$this->query("delete from fotos where status != 'sim'");
			}
		}
	}
	public function salvar_arquivos($tabela,$id){
		if($_POST["arquivos"]){
			foreach($_POST["arquivos"] as $arquivo){
				$this->query("update arquivos set id$tabela = $id, status = 2 where idarquivos = '$arquivo'");
			}
		}
	}
	public function inserir_fotos($tabela,$id){
		
		$files = $_FILES["arquivo"];
		$dados = $_POST;
		
		$sql = "select max(ordem) as o from fotos where id$tabela='".$id."'";
		$max = $this->fetch($sql);
		
		$ordem = intval($max["o"]);
		
		for($i=0;$i<count($files["name"]);$i++){
			if(!$files["error"][$i]){
				
				$_tmp = getimagesize($files["tmp_name"][$i]);
							 
				$sql = "insert into fotos set
				legenda = '".$dados["legenda"][$i]."',
				url = '".diretorio($files["name"][$i])."',
				ordem = '".($ordem++)."',
				arquivo = '".mysql_escape_string(file_get_contents($files["tmp_name"][$i]))."',
				filetype= '".$_tmp["mime"]."',
				size = '".filesize($files["tmp_name"][$i])."',
				dimensoes = '".$_tmp["3"]."',
				id$tabela='".$id."'";
				
				$this->query($sql);
			}
		}
	}
	public function inserir_foto($name,$tmp_name,$_sql = NULL){
			global $_siteRoot;
			$cache =  $_SERVER['DOCUMENT_ROOT']."/_imagem/".time()."_".diretorio($name);
			
			//if(function_exists("exif_read_data"))
				//$exif = @exif_read_data($tmp_name, 0, true);
			
			//file_put_contents('exif.txt',pre($_FILES,true));
			
			//die('X-Server-Memory-Usage: '.round(memory_get_usage()/1024)."kb");
			if(exif_imagetype($tmp_name) == 3){
				$img_db = imagecreatefrompng( $tmp_name );
			}else{
				$img_db = imagecreatefromstring( file_get_contents($tmp_name) );
			}
			
			$width_orig	=	imagesx( $img_db );
			$height_orig	=	imagesy( $img_db );
			$width 	= 1920;
			$height = 1920;
			$scale	=	min($width  / $width_orig,	$height /  $height_orig);
			
			if($scale < 1){
				$width	=	floor( $scale * $width_orig );
				$height =	floor( $scale * $height_orig );
			}else{
				$width	=	$width_orig ;
				$height =	$height_orig ;
			}
			
			$image_p = imagecreatetruecolor($width, $height);
			imagealphablending( $image_p, false );
			imagesavealpha( $image_p, true );
			imagecopyresampled($image_p, $img_db, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
			if(preg_match("/\.png$/i",$name)){
				imagepng( $image_p,$cache, 9 );
			}else{
				imagejpeg( $image_p,$cache, 85 );
			}
			
			
			if(!$file["error"]){
				
				$_tmp = getimagesize($cache);
				
				$sql = "insert into fotos set
				chave = '".$chave."',
				nome_arquivo = '".$name."',
				filetype= '".$_tmp["mime"]."',
				size = '".filesize($cache)."',
				width = '".$_tmp[0]."',
				height = '".$_tmp[1]."',
				path = '".$cache."'";
				
				if($_sql)
					$sql .= $_sql;
				
				//id".$tg." = '".$_REQUEST['id']."',
				$this->query($sql) or die(mysql_error());
				if($this->error)
					return $this->error;
				else{
					return $this->inserted_id;
				}
			}else{
				return $this->error;
			}
	}
	public function inserir_tags($tabela,$id){
		global $db;
		$tags = array_unique(explode(",",$_POST["tags"]));
		
		$db->query("delete from tags_relacionadas where id$tabela = '$id'");
		
		foreach($tags as $tag){
			if(!trim($tag)) break;
			$db->query("select idtags from tags where url = '".diretorio(trim($tag))."'");
			if($db->rows){
				$tmp = $db->fetch();
				$idtag = $tmp["idtags"];
			}else{
				$db->query("insert into tags set tag = '".trim($tag)."', url = '".diretorio(trim($tag))."'");
				$idtag = $db->inserted_id;				
			}
			$db->query("insert into tags_relacionadas set idtags = '$idtag', id$tabela = '$id'");
		}
		
	}
	public function inserir_tb_assoc($tabela,$campo,$tabela_pai,$id){
		
		$dados = $_POST["assoc"];
		for($i=0;$i<count($dados);$i++){
				if($dados[$i]){
					$sql = "insert into $tabela set id$tabela_pai = '$id', $campo = '".$dados[$i]."'";
					$this->query($sql);
				}
		}
	}
	public function inserir_atributos_de_canais($id){
		
		$dados = $_POST;
		//pre($_POST);
		for($i=0;$i<count($dados["atributo_atributo"]);$i++){
				 echo $sql = "insert into atributos_de_canais set
						idtipos_de_canais = '$id',
						idtipos_de_campos = '".$dados["atributo_formato"][$i]."',
						atributo = '".$dados["atributo_atributo"][$i]."',
						valor_default = '".$dados["atributo_valor_default"][$i]."'";
				$this->query($sql);
		}
	}
	public function inserir_atributos($tabela, $id){
		$dados = $_POST["atributos"];
		$this->query("delete from ".$tabela."_has_atributos_de_".$tabela." where id".$tabela." = '$id'");
		if(is_array($dados))
		foreach($dados as $k => $v){
			$sql = "insert into ".$tabela."_has_atributos_de_".$tabela." set
				id".$tabela." = '$id',
				idatributos_de_".$tabela." = '".$k."',
				transporte = '".utf8_decode($v)."'";
			$this->query($sql);
		}
	}
	public function inserir_itens_relacionados($tabela,$id){
		
		$dados = $_POST;
		
		$this->query("delete from itens_relacionados where id$tabela = '$id'");
		for($i=0;$i<count($dados["itens"]);$i++){
			$sql = "insert into itens_relacionados set
				id$tabela = '$id',
				id_relacionado = '".$dados["itens"][$i]."'";
			$this->query($sql);
		}
	}
	public function tabela_link($tabela_principal,$tabela_secundaria,$id,$valores){

		$sql = "delete from ".$tabela_principal."_has_".$tabela_secundaria." where id".$tabela_principal." = '".$id."'";
		$this->query($sql);
		if(is_array($valores))
			foreach($valores as $valor){
				$sql = "
				insert into ".$tabela_principal."_has_".$tabela_secundaria." set
					id".$tabela_principal." = '".$id."',";
				if(is_array($valor))
					$sql .= "
						id".$tabela_secundaria." = '".$valor[0]."',
						transporte = '".$valor[1]."'";
				else
					$sql .= "
						id".$tabela_secundaria." = '".$valor."'";
				
				$this->query($sql);
			}
	}
	public function registraAlteracao($operacao,$tabela,$dados,$idtabela){
		global $usuario;
			if(!$this->tabelaExiste('_logs')){
				$this->query("
					CREATE TABLE IF NOT EXISTS `_logs` (
				  `id_logs` int(10) unsigned NOT NULL auto_increment,
				  `idusuarios` int(10) unsigned NOT NULL,
				  `operacao` tinyint NOT NULL,
				  `tabela` varchar(100) NOT NULL,
				  `idtabela` int(10) unsigned NULL,
				  `dados` longtext NULL,
				  `data_alteracao` datetime NOT NULL,
				  PRIMARY KEY  (`id_logs`),
				  INDEX (`idusuarios`)
				) ENGINE=InnoDB");
			}
			switch($operacao){
				case 'insert': $_op = 1; break;
				case 'update': $_op = 2; break;
				case 'delete': $_op = 3; break;
			}
			$_tmp = $this->inserted_id;
			//pre($usuario);
			$_id = $usuario->id;
			$this->query("insert _logs set idusuarios = '".$_id."', operacao = '".$_op."', tabela = '".$tabela."', idtabela = '".$idtabela."', dados = '".mysql_escape_string(serialize($dados))."', data_alteracao = '".date("Y-m-d H:i:s")."'");
			$this->inserted_id = $_tmp;
	}
	public function reconstituEstrutura(){
		
				$this->query("
				CREATE TABLE IF NOT EXISTS `sys_tabelas` (
				  `id_tabelas` int(10) unsigned NOT NULL auto_increment,
				  `tabela` varchar(100) NOT NULL,
				  `tipo` varchar(100) NOT NULL,
				  `rows` int(10) unsigned NOT NULL,
				  `auto_increment` int(10) unsigned NOT NULL,
				  PRIMARY KEY  (`id_tabelas`),
				  UNIQUE KEY `tabela` (`tabela`)
				) ENGINE=InnoDB");
	}
}
?>