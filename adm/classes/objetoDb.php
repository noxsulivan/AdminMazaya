<?
class objetoDb{
	public $id;
	public $tabela;
	//public $primeiroCampo;
	//public $ultimo;
	//public $atributos;
	//public $transporte;
	//public $relacoes;
	//public $fotos;
	//public $arquivos;
	//public $campos;

	public $propriedades;

	public function objetoDb($tabela,$id = null){
		global $db;
		
		$_resourceAtual = $db->resourceAtual;
		
		$this->tabela = $tabela;
		
		$_campos = $db->campos_da_tabela($this->tabela);
		
		if(!$id){
			return;
			$this->sql = "select id$tabela from $tabela order by ".( in_array('ordem',$_campos) ? 'ordem asc ' : ( in_array('data',$_campos) ? 'data desc' : "id$tabela desc") )." limit 1";
			$db->query($this->sql);
			$res = $db->fetch();
			$id = $this->ultimo = $res["id$tabela"];
			if(!$id) return;
		}
		$db->resource($_resourceAtual);
		
		$sql = "select * from $tabela ";
		if(is_numeric($id)){						$sql .= "where id$tabela = '".$id."'";
		}elseif(preg_match("/^where/i",$id)){		$sql .= $id;
		}else{										$sql .= "where url = '".$id."'";
		}
		$sql .= " order by ".( in_array('ordem',$_campos) ? 'ordem asc ' : ( in_array('data',$_campos) ? 'data desc' : "id$tabela desc") );
		
		if($db->query($sql)){
			if($db->rows){
					$res = $db->fetch();
					$this->id = $res["id".$this->tabela];
					
					foreach($_campos as $campo => $propriedados_do_campo){
						if(($propriedados_do_campo["Key"] == 'MUL' or preg_match("/^id/i",$campo)) and $this->tabela != 'fotos' ) {
							if(preg_match("/_foreign_/i",$campo)){
								$_tmp = explode("_foreign_",preg_replace("/^id/","",$campo));
								$this->$campo = $res[$campo];
								$_a['tabela'] = $_tmp[0];
								$_a['id'] = $res[$campo];
								$this->propriedades['@'.$_tmp[1]] = $_a;
							}else{
								$tabelaPai = str_replace("_2","",preg_replace("/^id/i","",$campo));
								if($db->tabelaExiste($tabelaPai)){
									$_tabelaPai = '@'.$tabelaPai;
									$this->propriedades[$_tabelaPai] = intval($res[$campo]);
									$this->propriedades[$campo] = intval($res[$campo]);
								}
							}
						}else{
								switch($propriedados_do_campo["Type"]){
									case 'date':
									case 'datetime':
										$this->propriedades[$campo] = ex_data($res[$campo]);
									break;
									case 'double(19,2)':
									case 'float':
									case 'float(11,2)':
									case 'float(9,2)':
									case 'float(9,2) unsigned':
									case 'float unsigned':
									case 'smallint(6)':
										$this->propriedades[$campo] = $res[$campo];
									break;
									default:
										$this->propriedades[$campo] = $res[$campo].($campo == 'url' ? '/' : '');
									break;
								}
						}
					}
					$_SESSION['objetos'][$this->id.'@'.$this->tabela] = $this; // DESABILITADO
			}
		}
		$db->resource($_resourceAtual);
	}
	
	public function existe(){
		return $this->id;
	}
	public function temPai(){
		$objeto = $this->tabela;
		if(is_object($this->$objeto))
			return $objeto;
		else
			return false;
	}
	
	public function pai($tabela){
		global $db;
		
		$obj = $this->$tabela;
		
		$primeiroCampo = $db->primeiroCampo($this->$tabela);
		
		if($obj->$tabela and is_object($obj->$tabela)){
			return $obj->pai($tabela)."/".$obj->$primeiroCampo;
		}else{
			return $obj->$primeiroCampo;
		}
	}

	
	public function caminho($tabela){
		global $db,$pagina;
		
		$obj = $this->$tabela;
		
		$campo = $obj->primeiroCampo;
		
		if($obj->$tabela){
			return $obj->pai($tabela).' &raquo; <a href="'.$pagina->localhost.$obj->url.'">'.$obj->$campo.'</a>';
		}else{
			return '<a href="'.$pagina->localhost.$obj->url.'">'.$obj->$campo.'</a>';
		}
	}
	
	public function has($tabela,$id){
		global $db,$pagina;
		
		foreach($this->$tabela as $_tab){
			$tabelas[] = $_tab->id;
		}
		
		$count = 0;
		if(is_array($id)){
			foreach($id as $_id){
				if($this->has($tabela,$_id)){
					$count++;
				}
			}
			return $count;
		}else{
			if(in_array($id,$tabelas)){
				return true;
			}else{
				return false;
			}
		}
	}
		
	public function __call($propriedade,$valor){
		global $db,$res;
		

			$sql = "update ".$this->tabela." set ".$propriedade." = '".implode($valor)."' where id".$this->tabela." = '".$this->id."'";
			if($db->query($sql)){
				$this->$propriedade = $valor;
				$res['atualiza'.$propriedade] = $valor;
				return true;
			}else{
				$res['falou'.$propriedade] = $valor;
				return false;
			}
	}
	public function __get($campo){
		global $db;
		if(isset($this->$campo)){
			echo $this->$campo;
			return $this->$campo;
		}
		if(!isset($this->propriedades[$campo])){
			switch($campo){
				case 'fotos':
						$this->fotos = array();
						if($db->campoExiste('id'.$this->tabela,'fotos')){
							if($this->tabela != 'fotos'){
								$_subResource = $db->resourceAtual;
								$db->query("select * from fotos where id".$this->tabela." = '".$this->id."' order by ordem asc");
								while($res = $db->fetch()){
									if($res['height'] < 1){
										preg_match_all('/width="(.+?)" height="(.+?)"/',$res['dimensoes'],$dim);
										$res['width']	=	$dim[1][0];
										$res['height']	=	$dim[2][0];
									}
									array_push($this->fotos, array('id' => $res['idfotos'] , 'legenda' => $res['legenda'] , 'url' => $res['nome_arquivo'], 'dim' => $res['dimensoes'], 'width' => $res['width'], 'height' => $res['height']) );
								}
								$db->resource($_subResource);
							}
							return $this->fotos;
						}
				break;
				case 'arquivos':
						$this->arquivos = array();
						if($db->campoExiste('id'.$this->tabela,'arquivos')){
							if($this->tabela != 'arquivos'){
								$_subResource = $db->resourceAtual;
								$db->query("select idarquivos from arquivos where id".$this->tabela." = '".$this->id."'");
								while($res = $db->fetch()){
									$this->arquivos[] = new objetoDb('arquivos',$res['idarquivos']);
								}
								$db->resource($_subResource);
							}
							return $this->arquivos;
						}
				break;
				case 'comentarios':
						$this->comentarios = array();
						if($db->campoExiste('id'.$this->tabela,'comentarios')){
							if($this->tabela != 'comentarios'){
								$_subResource = $db->resourceAtual;
								$db->query("select idcomentarios from comentarios where id".$this->tabela." = '".$this->id."' order by data desc");
								while($res = $db->fetch()){
									$this->comentarios[] = new objetoDb('comentarios',$res['idcomentarios']);
								}
								$db->resource($_subResource);
							}
							return $this->comentarios;
						}
				break;
				case '_hascategorias':
						$campo = array();
								$db->query("select id".$this->tabela." from ".$this->tabela." where id".$this->tabela."_2 = '".$this->id."'");
								while($res = $db->fetch()){
									$campo[] = new objetoDb($this->tabela,$res['id'.$this->tabela]);
								}
								$db->resource($_subResource);
							$this->_hascategorias = $campo;
							return $this->_hascategorias;
				break;
				default:
					//$this->$campo = NULL;
					//pre($db);
					//pre($this->tabela.' - '.$campo);
					if($db->tabelaExiste($campo)){
						if($db->indiceExiste($campo,$this->tabela) and isset($this->propriedades["@".$campo])){//////// se é um objeto pai e seu indice está apontado
							//pre('aqui 1'.$this->tabela.$campo);
							$this->$campo = new objetoDb($campo,$this->propriedades["@".$campo]);
							
						}elseif($db->indiceExiste($this->tabela,$campo)){//////// se é um conjunto de objetos filhos
							//pre('aqui 2'.$this->tabela.$campo);
							$db->query('select id'.$campo.' from '.$campo.' where id'.$this->tabela.' = "'.$this->id.'"');
							//pre($db);
							while($res = $db->fetch()){
								$_campo[] = new objetoDb($campo,$res['id'.$campo]);
							}
							$this->$campo = $_campo;
						}elseif($db->tabelaExiste($this->tabela.'_has_'.$campo)){//se é um conjunto de associações de objetos
							//pre('aqui 3'.$this->tabela.$campo);
							$db->query('select * from '.$this->tabela.'_has_'.$campo.' where id'.$this->tabela.' = "'.$this->id.'"');
							//pre($db);
							while($res = $db->fetch()){
								$_ordem = '';
								$obj = new objetoDb($campo,$res['id'.$campo]);
								if($obj->ordem){
									$_campo[$obj->ordem] = $obj;
									$_ordem = $obj->ordem;
								}else{
									$_campo[++$j] = $obj;
								}
								
								//para criar campos de transporte da tabelea _has_
								$campos = $db->campos_da_tabela($this->tabela.'_has_'.$campo);
								//pre($campos);
								foreach($campos as $cK => $cV){
									if($cV['Key'] == '')
										$_campo[$_ordem.$j]->$cK = $res[$cK];
								}
							}
							if(is_array($_campo)) ksort($_campo);
							//pre($_campo);
							$this->$campo = $_campo;
						}
					}else{
						//pre("A propriedade $campo do objeto ".$this->tabela." não está definida e não pôde ser recuperada no banco de dados.");
						//mail("noxsulivan@gmail.com","propriedade inacessivel",debug_backtrace());
						return NULL;
						//die();
					}
				break;
			}
			return $this->$campo;
		}
		return $this->propriedades[$campo];
								
	}
	
	public function __isset($campo){
		return isset($this->$campo);
	}
	
	public function __toString(){
		return $this->tabela;
	}
	
	private function _ordenar(&$_tmp){
		foreach($_tmp as $item)
			$_aux[$item->ordem] = $item;
		ksort($_aux);
		$_tmp = $aux;
	}
	
}



?>