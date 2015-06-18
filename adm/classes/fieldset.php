<?
class fieldset{
	var $index;
	var $propriedades;
	var $campo;
	
	function fieldset($parent,$tit,$hidden = 0, $class = false){
		global $db;
			$reflector = new ReflectionClass('formulario');
			$properties = $reflector->getProperties();
			foreach($properties as $property){
				$this->{$property->getName()} = $parent->{$property->getName()};
			}
					
			if($this->tabela and $db->tabelaExiste($this->tabela)){
				$this->campos = $db->campos_da_tabela($this->tabela);
			}
			$this->index = $parent->propriedades["total"];
			$this->propriedades['index'] = $this->index;
			$this->propriedades['nome'] = $tit;
			$this->propriedades['hidden'] = $hidden;
			$this->propriedades['id'] = $parent->idtabela;
			$this->propriedades['class'] = ($class ? "full" : "middle");
	
		}
	function explicacao($mensagem){
		$this->explicacao_formulario($mensagem);
	}
	function explicacao_formulario($mensagem){
		$time = time();
						$this->campo[$time]['label'] = "Explicação";
						$this->campo[$time]['tag'] = "div";
						$this->campo[$time]['type'] = "expl";
						$this->campo[$time]['value'] = $mensagem;
	}
	function simples($visor,$campo,$dado=null,$explicacao=null,$campoNome=null){
			global $db,$usuario,$admin;
			if($this->campos[$campo]){
				  
			  
			  $campoNome = ($campoNome ? $campoNome : $campo );
			  
	  
			  if($this->campos[$campo]["Key"] == 'PRI' or preg_match("/hidden/i","",$this->campos[$campo]["Comment"])){
					$this->html .= ' <input type="hidden" id="'.$campoNome.'" name="'.$campoNome.'" value="'.$dado.'">';
			  }elseif($this->campos[$campo]["Key"] == 'MUL' and preg_match("/^id/",$campo)){
				  //pre($campo);
					if(preg_match("/_foreign_/",$campo)){
						$_tmp = explode("_foreign_",preg_replace("/^id/","",$campo));
						$tabela = $_tmp[0];
						
						//  ATENCAO  $_tmp2 = $_tmp[1];
						$_tmp2 = $_tmp[0];
						//$prim = $this->registro->$_tmp2->primeiroCampo;
						$primeiro = $db->primeiroCampo($tabela);
						$campos_da_tabela = $db->campos_da_tabela($tabela);
						
							
							$this->html .= '<input type="hidden" id="'.$campoNome.'" name="'.$campoNome.'"';
							$this->html .= 'value="'.$this->registro->$_tmp2->id.'">';
							
							$this->html .= '<input type="text" id="'.diretorio($campoNome).'_auto" name="'.diretorio($campoNome).'_auto"';
							$this->html .= 'class="f_'.diretorio($campos_da_tabela[$primeiro]['Type']).' autocompleteInput'.(($this->campos[$campo]["Null"] == 'NO')?" required":"").'"';
							$this->html .= 'value="'.$this->registro->$_tmp2->$primeiro.'" alt="campo='.$prim.'&tabela='.$tabela.'" target="'.$campoNome.'">';
							
							
							$this->html .= '<div id="'.diretorio($campoNome).'_auto_divAutocomplete" class="autocomplete"></div>';
							$this->html .= '<div id="'.diretorio($campoNome).'_auto_divAutocompleteCarregando" class="autocompleteCarregando" style=" display:none;"></div>';
					}else{
						$tabela = preg_replace("/_2/i","",preg_replace("/^id/i","",$campo));
						
						//$dado = ( $dado ? $dado : $this->registro->{$tabela}->id );
						$dado = ( $dado ? $dado : $this->registro->{$tabela}->id );
						
						$primeiro = $db->primeiroCampo($tabela);
						
						if($db->campoExiste('ordem',$tabela)){
							$orderby = 'ordem';
						}elseif($tabela == 'produtos'){
							$orderby = 'grade';
						}else{
							$orderby = $primeiro;
						}
						
						$campos_da_tabela = $db->campos_da_tabela($tabela);
								
						$this->campo[$campoNome]['label'] = $visor.(($this->campos[$campo]["Null"] == 'NO')?" *":"");
						if($this->campos[$campo]["Null"] == 'NO') $this->campo[$campoNome]['obrigatorio'] = true;
						$this->campo[$campoNome]['tag'] = "select";
						$this->campo[$campoNome]['expand'] = true;
						$this->campo[$campoNome]['value'] = $dado;
						$this->campo[$campoNome]['c'] = 'f_'.diretorio($campos_da_tabela[$primeiro]['Type']).(($this->campos[$campo]["Null"] == 'NO')?" required":"");
						
						if(@!in_array($this->tabela,$usuario->tabelas)) $this->campo[$campoNome]['disabled'] = true;
						if($this->campos[$campo]["Null"] == 'NO') $this->campos[$campoNome]['obrigatorio'] = true;
						
						
						$sql = "select * from $tabela";
						
						if($usuario->tipos_de_usuarios->condicao){
							$condicao = $usuario->tipos_de_usuarios->condicao;
							
							if($db->campoExiste("idusuarios", $tabela) and preg_match("/__USUARIO_ID__/i",$condicao)) $camposCondicionais = preg_replace("/__USUARIO_ID__/i",$usuario->id,$condicao);
							if($db->campoExiste("idcadastros", $tabela) and preg_match("/__CADASTRO_ID__/i",$condicao)) $camposCondicionais = preg_replace("/__CADASTRO_ID__/i",$usuario->cadastros->id,$condicao);
							if($db->campoExiste("idcondominos", $tabela) and preg_match("/__CONDOMINO_ID__/i",$condicao)) $camposCondicionais = preg_replace("/__CONDOMINO_ID__/i",$usuario->condominos->id,$condicao);
							if($db->campoExiste("idclientes", $tabela) and preg_match("/__CLIENTE_ID__/i",$condicao)) $camposCondicionais = preg_replace("/__CLIENTE_ID__/i",$usuario->cadastros->clientes->id,$condicao);
							
							if($camposCondicionais) $sql .= " where ".$camposCondicionais."";
						}
						
						
						$sql .= " order by $orderby";
						$db->query($sql);
				  //pre($sql);die();
	
						$campo = str_replace("_2","",$campo);
						$resource =$db->resourceAtual;
						if($tabela == 'clientes'){
				  //return;
							while($res = $db->fetch()){
								$obj = new objetoDb($tabela,$res["id".$tabela]);//pre($obj);
								if(!$db->campoExiste('idstatus_clientes','clientes') or $obj->status_clientes->id == 2){
									if($tabela != $this->tabela or $res[$campo]!=$this->id)
									//$this->html .= '<option'.( $res[$campo]==$dado ?' selected':'').' value="'.$res[$campo].'">'.( is_object($obj->$tabela) ? $obj->pai($tabela)." &raquo; ".$obj->$primeiro : $obj->$primeiro ).'</option>';
									$_tmp = (trim(str2upper($obj->fantasia).' - '.normaliza(preg_replace("/\-/i"," ",$obj->nome))));
									//asort($_tmp);
									$this->campo[$campoNome]['opcoes'][++$_cli] = array($res[$campo],$_tmp);
									$db->resource($resource);
								}
							}
						}elseif($tabela == 'produtos'){
				  //pre($sql);
				  //return;
							while($res = $db->fetch()){
								$obj = new objetoDb($tabela,$res["id".$tabela]);//pre($obj);
								if($db->campoExiste('ativo','produtos') and $obj->ativo == 'sim'){
									if($tabela != $this->tabela or $res[$campo]!=$this->id)
									$_tmp = (trim($obj->codigo.' '.$obj->$primeiro.' - '.$obj->fornecedores->nome));
									$this->campo[$campoNome]['opcoes'][++$_pro] = array($res[$campo],$_tmp);
									$db->resource($resource);
								}
							}
						}elseif($tabela == 'condominos'){
							while($res = $db->fetch()){
								$obj = new objetoDb($tabela,$res["id".$tabela]);//pre($obj);
								if($tabela != $this->tabela or $res[$campo]!=$this->id)
								$this->campo[$campoNome]['opcoes'][++$_else]= array($res[$campo],str_pad($obj->quadras->quadra,2,"0",STR_PAD_LEFT)."-".str_pad($obj->lote,2,"0",STR_PAD_LEFT)." ".substr($obj->$primeiro,0,20));
								$db->resource($resource);
							}
						}else{
				  //pre($sql);
				  //return;
							while($res = $db->fetch()){
								$obj = new objetoDb($tabela,$res["id".$tabela]);//pre($obj);
								if($tabela != $this->tabela or $res[$campo]!=$this->id)
								//$this->html .= '<option'.( $res[$campo]==$dado ?' selected':'').' value="'.$res[$campo].'">'.( is_object($obj->$tabela) ? $obj->pai($tabela)." &raquo; ".$obj->$primeiro : $obj->$primeiro ).'</option>';
								$this->campo[$campoNome]['opcoes'][++$_else]= array($res[$campo],$obj->$primeiro);
								$db->resource($resource);
							}
						}
						
						
					}
			  }else{
			  
				  //pre($campo);return;
				  
				  if(isset($this->registro)){// && is_null($dado)){
					  $dado = $this->registro->{$campo};
				  }
				  if(!$dado and $this->campos[$campo]["Default"] != "NULL"){
					  $dado = $this->campos[$campo]["Default"];
				  }
				  
				  $this->campo[$campoNome]['label'] = $visor.(($this->campos[$campo]["Null"] == 'NO')?" *":"");
				if($this->campos[$campo]["Null"] == 'NO') $this->campo[$campoNome]['obrigatorio'] = true;
				  $this->campo[$campoNome]['tag'] = "input";
				  $this->campo[$campoNome]['type'] = "text";
				  $this->campo[$campoNome]['c'] = 'f_'.diretorio($this->campos[$campo]['Type']).(($this->campos[$campo]["Null"] == 'NO')?" required":"");
				  $this->campo[$campoNome]['value'] = $dado;
				  
				  if(@!in_array($this->tabela,$usuario->tabelas)) $this->campo[$campoNome]['disabled'] = true;
				  if($this->campos[$campo]["Null"] == 'NO') $this->campos[$campoNome]['obrigatorio'] = true;
							  
				  switch(true){
					  case 'varchar(255)' == $this->campos[$campo]["Type"]://texto simples de uma linha
					  break;
					  case 'varchar(100)' == $this->campos[$campo]["Type"]://texto simples de uma linha
					  break;
					  case 'varchar(50)' == $this->campos[$campo]["Type"]://texto simples de uma linha
					  break;
					  case 'varchar(15)' == $this->campos[$campo]["Type"]://cpf
							  $this->campo[$campoNome]['c'] .= ' edit';
							  $this->campo[$campoNome]['alt'] = 'cpf';
					  break;
					  case 'varchar(18)' == $this->campos[$campo]["Type"]://
							  $this->campo[$campoNome]['c'] .= ' edit';
							  $this->campo[$campoNome]['alt'] = 'cnpj';
					  break;
					  case 'varchar(200)' == $this->campos[$campo]["Type"]:
							  $this->campo[$campoNome]['c'] .= ' validate-email';
					  break;
					  case 'varchar(300)' == $this->campos[$campo]["Type"]://url, endereço de site
							  $this->campo[$campoNome]['c'] .= ' validate-url';
							  $this->campo[$campoNome]['value'] = ($dado ? $dado : 'http://www.');
					  break;
					  case 'varchar(7)' == $this->campos[$campo]["Type"]:
							  $this->campo[$campoNome]['c'] .= ' edit_month';
							  $this->campo[$campoNome]['alt'] = 'mes';
							  $this->campo[$campoNome]['maxlength'] = 7;
					  break;
					  case 'varchar(8)' == $this->campos[$campo]["Type"]://Placa
							  $this->campo[$campoNome]['c'] .= ' edit';
							  $this->campo[$campoNome]['alt'] = 'placa';
							  $this->campo[$campoNome]['maxlength'] = 8;
					  break;
					  case 'varchar(9)' == $this->campos[$campo]["Type"]:
							  $this->campo[$campoNome]['c'] .= ' edit';
							  $this->campo[$campoNome]['alt'] = 'cep';
							  $this->campo[$campoNome]['maxlength'] = 9;
					  break;
					  case 'varchar(10)' == $this->campos[$campo]["Type"]://teletone
					  case 'varchar(14)' == $this->campos[$campo]["Type"]://teletone
							  $this->campo[$campoNome]['c'] .= ' edit';
							  $this->campo[$campoNome]['alt'] = 'phone';
							  $this->campo[$campoNome]['maxlength'] = 14;
					  break;
					  
					  case 'tinytext' == $this->campos[$campo]["Type"]:
							  $this->campo[$campoNome]['tag'] = "textarea";
					  break;
					  
					  case 'text' == $this->campos[$campo]["Type"]:
							  $this->campo[$campoNome]['tag'] = "textarea";
					  break;
					  
					  case 'mediumtext' == $this->campos[$campo]["Type"]:
							  $this->campo[$campoNome]['c'] .= ' richText';
							  $this->campo[$campoNome]['tag'] = "textarea";
					  break;
					  case 'longtext' == $this->campos[$campo]["Type"]:
							  $this->campo[$campoNome]['c'] .= ' richText';
							  $this->campo[$campoNome]['tag'] = "textarea";
					  break;
					  
					  case preg_match("/time/i",$this->campos[$campo]["Type"]):
							  $this->campo[$campoNome]['c'] .= ' edit';
							  $this->campo[$campoNome]['alt'] = 'datetime';
							  
							  list($_data,$_hora) = explode(" ",$dado);
							  $this->campo[$campoNome]['_data'] = $_data;
							  $this->campo[$campoNome]['_hora'] = $_hora;
					  break;
					  
					  case preg_match("/^date/i",$this->campos[$campo]["Type"]):
							  $this->campo[$campoNome]['c'] .= ' edit';
							  $this->campo[$campoNome]['alt'] = 'date';
							  $this->campo[$campoNome]['maxlength'] = 10;
					  break;
					  
					  case 'int(10) unsigned' == $this->campos[$campo]["Type"]:
							  $this->campo[$campoNome]['c'] .= ' edit';
							  $this->campo[$campoNome]['alt'] = 'integer';
							  $this->campo[$campoNome]['maxlength'] = 14;
					  break;
					  case 'int(10)' == $this->campos[$campo]["Type"]:
					  case 'int(11)' == $this->campos[$campo]["Type"]:
					  case 'tinyint(4)' == $this->campos[$campo]["Type"]:
					  case 'float(9,3)' == $this->campos[$campo]["Type"]:
							  $this->campo[$campoNome]['c'] .= ' edit';
							  $this->campo[$campoNome]['alt'] = 'integer';
							  $this->campo[$campoNome]['maxlength'] = 14;
					  break;
					  case 'smallint(6)' == $this->campos[$campo]["Type"]:
							  $this->campo[$campoNome]['c'] .= ' edit';
							  $this->campo[$campoNome]['alt'] = 'month';
							  $this->campo[$campoNome]['maxlength'] = 7;
					  break;
					  case 'double' == $this->campos[$campo]["Type"]:
					  case 'float' == $this->campos[$campo]["Type"]:
					  case 'float unsigned' == $this->campos[$campo]["Type"]:
							  $this->campo[$campoNome]['maxlength'] = 14;
					  break;
					  case 'float(9,2)' == $this->campos[$campo]["Type"]:
					  case 'float(9,2) unsigned' == $this->campos[$campo]["Type"]:
					  case 'float(11,2)' == $this->campos[$campo]["Type"]:
							  $this->campo[$campoNome]['c'] .= ' edit';
							  $this->campo[$campoNome]['alt'] = 'decimal';
					  break;
					  
					  case 'varchar(32)' == $this->campos[$campo]["Type"]:
							  $this->campo[$campoNome]['tag'] .= 'input';
							  $this->campo[$campoNome]['type'] = 'password';
					  break;
					  
					  case preg_match("/^enum/i",$this->campos[$campo]["Type"]):
							  $opcoes = explode("','",str_replace("')",'',str_replace("enum('",'',$this->campos[$campo]["Type"])));
							  $this->campo[$campoNome]['tag'] = "radio";
							  
							  foreach($opcoes as $op){
								  $this->campo[$campoNome]['opcoes'][$op] = $op;
							  }
					  break;
					  case 'tinyint(1)' == $this->campos[$campo]["Type"]:
							  $this->campo[$campoNome]['tag'] = "radio";
							  $this->campo[$campoNome]['opcoes'][0] = "Não";
							  $this->campo[$campoNome]['opcoes'][1] = "Sim";
					  break;
					  default:
							  $this->campo[$campoNome]['tag'] .= 'input';
							  $this->campo[$campoNome]['type'] = 'hidden';
							  break;
				  }
				  
				  if($explicacao){
					  $this->html .= "<h4>$explicacao</h4>";
				  }
			  }
			  $this->html .= '</div>';
			}
		}
	function separador(){
						$this->campo[substr(md5(microtime()),0,3)]['tag'] = "br";
		}
	
	function canal($tipos_de_canais,$id,$visor=null){
			global $db;
			
			$sql = "select * from canais where idtipos_de_canais = '$tipos_de_canais'";
			$principal = $db->primeiroCampo($tabela);
			
			$existe = array();
			if(is_array($this->registro->$tabela)){
				foreach($this->registro->$tabela as $_tabela){
					$existe[] = $_tabela->id;
				}
			}		
			$this->campo[$tabela]['label'] = $visor.(($this->campos[$campo]["Null"] == 'NO')?" *":"");
			$this->campo[$tabela]['tag'] = "div";
			$this->campo[$tabela]['type'] = "chechbox";
			$_grupos = $this->_checkBox($tabela,$existe,0,0);
			$this->campo[$tabela]['total'] = count($_grupos);
			$this->campo[$tabela]['grupos'] = $_grupos;
		}
	function hasTransporte($tabela_secundaria,$visor,$valorDefault,$transporte){
			global $db;
					
			$db->query("select id$tabela_secundaria from $tabela_secundaria");
			while($res = $db->fetch()){
				$hasTransporte = new objetoDb($tabela_secundaria,$res["id$tabela_secundaria"]);
				//pre($hasTransporte);
				if($hasTransporte->$visor){
					$this->html .= '<div class="campo">';
					$this->html .= '
					<label class="nao_obrigatorio" for="transp['.$hasTransporte->id.']">'.$hasTransporte->$visor.'</label>';
					
					$_temp = $this->registro->$tabela_secundaria;
					$this->html .=  '
					<input type="hidden" id="transp['.$hasTransporte->id.']" name="transp['.$hasTransporte->id.'][]" class="inputField" value="'.$hasTransporte->id.'">
					<input type="text" id="transp['.$hasTransporte->id.']" name="transp['.$hasTransporte->id.'][]" class="inputField" value="'.( $_temp[$hasTransporte->id]->_transporte[$transporte] ? $_temp[$hasTransporte->id]->_transporte[$transporte] : $hasTransporte->$valorDefault ).'">';
					$this->html .= '</div>';
				}
			}
		}
	function checkBox($visor,$tabela){
			global $db;
			$principal = $db->primeiroCampo($tabela);
			
			$existe = array();
			if(is_array($this->registro->$tabela)){
				foreach($this->registro->$tabela as $_tabela){
					$existe[] = $_tabela->id;
				}
			}		
			$this->campo[$tabela]['label'] = $visor.(($this->campos[$campo]["Null"] == 'NO')?" *":"");
			$this->campo[$tabela]['tag'] = "div";
			$this->campo[$tabela]['type'] = "chechbox";
			$_grupos = $this->_checkBox($tabela,$existe,0,0);
			$this->campo[$tabela]['total'] = count($_grupos);
			$this->campo[$tabela]['grupos'] = $_grupos;
			
			
		}
		
	private function _checkBox($tabela,$existe,$pai,$nivel){
			global $db;
			
			if($nivel == 3) return;
			
			$_prim = $db->primeiroCampo($tabela);
			$_ord = $db->campoExiste("ordem",$tabela) ? "ordem" : $_prim;
			
			$sql = "select * from $tabela ".( $db->campoExiste("id${tabela}_2",$tabela) ? "where id${tabela}_2 = '".$pai."'": '')." order by $_ord";
			$db->query($sql);
			$resource = $db->resourceAtual;
			if($db->rows){
				$resource = $db->resourceAtual;
				$i = 0;
				while($res = $db->fetch()){
					$obj = new objetoDb($tabela,$res["id".$tabela]);
					$ret[$i]['id'] = $obj->id;
					$ret[$i]['nivel'] = $nivel;
					$ret[$i]['label'] = $obj->$_prim;
					$ret[$i]['checked'] = in_array($obj->id,$existe);
					if($db->campoExiste("id${tabela}_2",$tabela)) $_grupos = $this->_checkBox($tabela,$existe,$res["id".$tabela],$nivel+1);
					$ret[$i]['total'] = count($_grupos);
					$ret[$i]['grupos'] = $_grupos;
					$db->resource($resource);
					$i++;
				}
			}
			return $ret;
		 }
	function grupoCheckbox($tabela_principal,	$tabela_secundaria, $where = null){
			global $db;
			  
			$this->html .= '<div class="campo">';
			$principal = $db->primeiroCampo($tabela_principal);
			$secundaria = $db->primeiroCampo($tabela_secundaria);
			
			$sql = "select * from ".$this->tg."_has_".$tabela_secundaria." where id".$this->tg." = '".$this->id."'";
	
			$existe = array();
			$db->query($sql);
			while($res = $db->fetch()){
				$existe[] = $res["id".$tabela_secundaria];
			}
			
			
			$sql = "select * from $tabela_principal".( $where ? " where $where" : "");
			
			$tb = $db->campos_da_tabela($tabela_principal);
			if($this->ordenar) $sql .= " order by ordem";
			
			
			$db->query($sql);
			$_resource_principal = $db->resourceAtual;
			
			
			while($res = $db->fetch()){
					$db->query( "select * from $tabela_secundaria where id$tabela_principal = '".$res["id".$tabela_principal]."' order by $secundaria");
					$j=0;
					while($res2 = $db->fetch()){
						$this->html .= '<input type=checkbox name="tabela_link['.$tabela_principal.'][]" value="'.$res2["id".$tabela_secundaria].'"'.(in_array($res2["id".$tabela_secundaria],$existe) ?" checked":"").'>'.$res2[$secundaria];
					}
				$db->resource($_resource_principal);
			}
			$this->html .= '</div>';
		}
		
	function autocompleteInput($visor,$tabela,$tabela_secundaria){
		
				global $db,$usuario;
				
				
				$dado = $this->registro->{$tabela};
				$primeiro = $db->primeiroCampo($tabela);
				
				$this->campo[$tabela]['label'] = $visor;
				$this->campo[$tabela]['tag'] = "div";
				$this->campo[$tabela]['type'] = "autoCompleteInput";
				$this->campo[$tabela]['tabela'] = $tabela;
				$this->campo[$tabela]['tabela_secundaria'] = $tabela_secundaria;
				$this->campo[$tabela]['value'] = $dado->{$primeiro};
				$this->campo[$tabela]['id'] = $dado->id;
								
	//			$sql = "select * from ".$tabela." where id".$this->tabela." = '".$this->registro->id."'";
	//			$db->query($sql);
	//			$this->campo[$tabela]['total'] = $db->rows;
	//			  if($db->rows){
	//				  while($res = $db->fetch()){
	//					  $obj = new objetoDb($tabela,$res['id'.$tabela]);
	//					  $this->campo[$tabela]['values'][] = array("id"=>$obj->id,"valor"=>$obj->produtos->codigo.' - '.$obj->produtos->produto,"quantidade"=>$obj->quantidade);
	//				  }
	//			  }
			}
	function autoCompleteTags($visor,$tabela,$tabela_secundaria){
		
				global $db,$usuario;
				
				
				$dado = $this->registro->{$tabela};
				$primeiro = $db->primeiroCampo($tabela_secundaria);
				
				$this->campo[$tabela]['label'] = $visor;
				$this->campo[$tabela]['tag'] = "div";
				$this->campo[$tabela]['type'] = "autoCompleteTags";
				$this->campo[$tabela]['tabela'] = $tabela;
				$this->campo[$tabela]['tabela_secundaria'] = $tabela_secundaria;
				$this->campo[$tabela]['value'] = $dado->{$primeiro};
				$this->campo[$tabela]['id'] = $dado->id;
				$this->campo[$tabela]['primeiro'] = $primeiro;
								
				$sql = "select * from ".$tabela." left join ".$tabela_secundaria." on  ".$tabela.".id".$tabela_secundaria." = ".$tabela_secundaria.".id".$tabela_secundaria." where id".$this->tabela." = '".$this->registro->id."' order by ".$tabela_secundaria.".codigo";
				
				$this->campo[$tabela]['sql'] = $sql;
				
				$db->query($sql);
				$this->campo[$tabela]['total'] = $db->rows;
				  if($db->rows){
					  while($res = $db->fetch()){
						  $obj = new objetoDb($tabela,$res['id'.$tabela]);
						  $this->campo[$tabela]['values'][++$t] = array(
								"id"=>$obj->produtos->id,
								"valor"=>substr($obj->produtos->codigo.' - '.$obj->produtos->produto,0,80),
								"quantidade"=>$obj->quantidade,
								"ganhados"=>$obj->ganhados,
								'idprincipal'=>$res['id'.$tabela]);
					  }
				  }
			}
	function arquivo(){
					global $db;
					
					
				  $this->campo['arquivos']['tag'] = "div";
				  $this->campo['arquivos']['type'] = "files";
					
				  $db->query("select idarquivos from arquivos where id".$this->tabela." = '".$this->registro->id."'");
					
				  $this->campo['arquivos']['total'] = $db->rows;
					if($db->rows){
						while($res = $db->fetch()){
							$arquivo = new objetoDb("arquivos",$res['idarquivos']);
							$this->campo['arquivos']['values'][$arquivo->id]['nome_arquivo'] = $arquivo->nome_arquivo;
							$this->campo['arquivos']['values'][$arquivo->id]['tamanho'] = round($arquivo->tamanho/1024,2).'kb';
							$this->campo['arquivos']['values'][$arquivo->id]['downloads'] = $arquivo->downloads;
						}
					}
				}
	function fotos(){
					global $db,$usuario;
					
				  $this->campo["fotos"]['tag'] = "div";
				  $this->campo["fotos"]['type'] = "fotos";
				  
				  $db->query("select idfotos from fotos where id".$this->tabela." = '".$this->registro->id."' order by ordem");
				  
				  //$this->campo["fotos"]['sql'] = $db->lastSql;
				  
				  $this->campo['fotos']['total'] = $db->rows;
				  if($db->rows){
					  while($res = $db->fetch()){
							$foto = new objetoDb("fotos",$res['idfotos']);
							$this->campo["fotos"]['values'][$foto->id]["legenda"] = $foto->legenda;
							$this->campo['fotos']['values'][$foto->id]['nome_arquivo'] = $foto->nome_arquivo;
							$this->campo['fotos']['values'][$foto->id]['tamanho'] = round($foto->tamanho/1024,2).'kb';
							$this->campo['fotos']['values'][$foto->id]['downloads'] = $foto->downloads;
					  }
				  }
			  }
	function camera(){
					global $db,$usuario;
					
				  $this->campo["camera"]['tag'] = "div";
				  $this->campo["camera"]['type'] = "camera";
				  
				  $db->query("select idfotos from fotos where id".$this->tabela." = '".$this->registro->id."' order by ordem");
				  
				  //$this->campo["fotos"]['sql'] = $db->lastSql;
				  
				  $this->campo['camera']['total'] = $db->rows;
				  if($db->rows){
					  while($res = $db->fetch()){
							$foto = new objetoDb("fotos",$res['idfotos']);
							$this->campo["camera"]['values'][$foto->id]["legenda"] = $foto->legenda;
							$this->campo['camera']['values'][$foto->id]['nome_arquivo'] = $foto->nome_arquivo;
							$this->campo['camera']['values'][$foto->id]['tamanho'] = round($foto->tamanho/1024,2).'kb';
							$this->campo['camera']['values'][$foto->id]['downloads'] = $foto->downloads;
					  }
				  }
			  }
	function filhos($tabela){
				global $db;//,$usuario;
				
				$this->campo[$tabela]['tag'] = "div";
				$this->campo[$tabela]['type'] = "filhos";
				
								
				$usuario = new usuario();
				if(in_array($tabela,$usuario->tabelas)){
					$this->campo[$tabela]['edicao'] = true;
				}else{
					$this->campo[$tabela]['edicao'] = false;
				}
					
				
				$admin = new admin($tabela);
				
				$primeiro = $db->primeiroCampo($tabela);
						
				$campos = $db->campos_da_tabela($tabela);
					$_form = new formulario($admin);
					$_form->fieldset($tabela);
					foreach($_form->fieldset->campos as $k => $v){
						if($k != $this->propriedades['id']){
							if(
								($tabela == "itens") and ( preg_match("/cadastros/",$k) or preg_match("/clientes/",$k))
								or
								($tabela == "veiculos" and (preg_match("/condominos/",$k) or preg_match("/visitantes/",$k) or preg_match("/funcionarios/",$k)))
								or
								($tabela == "visitantes_has_visitas" or $tabela == "funcionarios_has_entradas" or $tabela == "eventos_has_convidados") and ( preg_match("/condominos/",$k))
								){
								//do nothing
							}elseif(preg_match("/^id/i",$k) and (preg_match("/produtos/",$k) and preg_match("/mesacor/",$_SERVER['HTTP_HOST']))){// and preg_match("/mesacor/",$_SERVER['HTTP_HOST'])){//){ and preg_match("/produtos/",$k)
								$_form->fieldset->autocompleteInput(ucfirst(preg_replace("/^id(.)?/i","$1",$k)),preg_replace("/^id(.)?/i","$1",$k),preg_replace("/^id(.)?/i","$1",$k));
							}else{
								$_form->fieldset->simples(ucfirst(preg_replace("/^id(.)?/i","$1",$k)),$k);
							}
						}
					}
					$this->campo[$tabela]['subset'] = $_form->fieldsets[$_form->fieldset->index];
					$this->campo[$tabela]['subset']['propriedades']['index'] = $this->index;
					
				$sql = "select * from ".$tabela." where id".$this->tabela." = '".$this->registro->id."' order by '".$primeiro."'";
				$db->query($sql);
				$this->campo[$tabela]['total'] = $db->rows;
				$this->campo[$tabela]['sql'] = $sql;
				$resource = $db->resourceAtual;
				while($res = $db->fetch()){
					$obj = new objetoDb($tabela,$res["id".$tabela]);
					$this->campo[$tabela]['values'][$res['id'.$tabela]] = $obj->propriedades;
					$db->resource($resource);
				}
					
			}
	function tipo($tabela,$dado = NULL){
			global $db,$usuario;
			
			$tipo = "tipos_de_".$tabela;
			
			if(isset($this->registro) && is_null($dado)){
				$dado = $this->registro->$tipo->id;
			}
			  
			$this->html .= '<div class="campo">';
			$this->html .= '
			<label>Tipo de funcionamento</label>
			';
			//$this->html .= $dado.$sql;
			$db->query("select idtipos_de_".$tabela.", tipo from tipos_de_".$tabela." order by tipo");
				$this->html .= ' <select name="idtipos_de_'.$tabela.'" class="inputField"';
				if(!in_array($tabela,$usuario->tabelas))
				$this->html .= ' disabled="disabled"';
				$this->html .= ' onchange="atributos(\''.$tabela.'\',this.options[selectedIndex].value,\''.$this->id.'\')">
						<option class="inputField"></option>';
				while($res = $db->fetch())
					$this->html .= '
						<option class="inputField" value="'.$res["idtipos_de_".$tabela].'"'.(($res["idtipos_de_".$tabela]==$dado)?' selected':'').'>'.$res["tipo"].'</option>';
				$this->html .= '
						</select>
			';
			$this->html .= '</div>';
		
		
			$this->html .= '
			<div id=atributos></div>
			<script>atributos(\''.$tabela.'\',\''.$dado.'\',\''.$this->id.'\')</script>';
		}
	function atributos_de_canais(){
			global $db;
			$this->html .= '
			<div class=linha>';
			if($this->id){
				$sql = "select * from atributos_de_canais where idtipos_de_canais = '$this->id'";
				$db->query($sql);
				while($res = $db->fetch())
					$this->html .= '
					<div id="atributos_de_canais'.$res["idatributos_de_canais"].'">
					<a href="'.$this->localhost.$this->admin.'_atributos_de_canais/editar/'.$res["idatributos_de_canais"].'">'.$res["atributo"].'</a>
					["<a href="javascript:ajax_remover_atributos_de_canais('.$res["idatributos_de_canais"].')"><img src="'.$this->localhost.'imagens/22x22/user-trash.png" border=0 />Remover</a>"]
					</div>';
			}
			$this->html .= '
			<div id=atributos_de_canais></div>
			<script>ajax_insere_atributos_de_canais()</script>
			<a href="javascript:ajax_insere_atributos_de_canais()"><img src="'.$this->localhost.'imagens/22x22/document-new.png" border=0 />Incluir outro arquivo</a>
			</div>';
		}
	function localidade(){
			global $db,$usuario;
				$this->campo["cep"]['tag'] = "div";
				$this->campo["cep"]['type'] = "cep";
				$this->campo["cep"]['label'] = $visor.(($this->campos[$campo]["Null"] == 'NO')?" *":"");
				$this->campo["cep"]['c'] = 'f_'.diretorio($this->campos["cep"]['Type']).' edit';
				$this->campo["cep"]['value'] = $this->registro->cep;
				
			$estado = $this->registro->estados;
			$cidade = $this->registro->cidades;
			
				
				$this->campo["idestados"]['label'] = "Estado";
				$this->campo["idestados"]['tag'] = "select";
				$this->campo["idestados"]['value'] = $estado->id;
				$this->campo["idestados"]['c'] = 'f_'.diretorio($this->campos["cep"]['Type']);
				$db->query("select * from estados order by nome");
	
				while($res = $db->fetch()){
					$obj = new objetoDb("estados",$res["idestados"]);
					$this->campo["idestados"]['opcoes'][++$est]= array($obj->id,$obj->estado);
	
				}
								
				$this->campo["idcidades"]['label'] = "Cidade";
				$this->campo["idcidades"]['tag'] = "select";
				$this->campo["idcidades"]['value'] = $cidade->id;
				$this->campo["idcidades"]['c'] = 'f_'.diretorio($this->campos["cep"]['Type']);
				if(count($estado->cidades)){
					foreach($estado->cidades as $_cid){
						$this->campo["idcidades"]['opcoes'][++$cid]= array($_cid->id,$_cid->cidade);
					}
				}else{
					$this->campo["idcidades"]['opcoes'][0] = "Selecione o estado";
				}
				
		}
	function boletos($idtipos_de_cobrancas = 1){
				global $db,$usuario;
				
				$this->campo["boletos"]['tag'] = "div";
				$this->campo["boletos"]['type'] = "boletos";
				
				
				$this->campo["boletos"]['cobranca'] = $this->registro->idcobrancas;
				
				if($idtipos_de_cobrancas == 2){
					$where = "where internet = 1 ";
				}
				if($idtipos_de_cobrancas == 3){
					$where = "where interfone = 1 and internet = '0'";
				}
				
				
				$sql = "select * from condominos ".$where."order by idquadras, lote";
				$db->query($sql);
				$this->campo["boletos"]['total'] = $db->rows;
				$resource = $db->resourceAtual;
				$j=0;
				while($res = $db->fetch()){
					$obj = new objetoDb("condominos",$res["idcondominos"]);
					$this->campo["boletos"]['condominos'][$j]["id"] = $res['idcondominos'];
					$this->campo["boletos"]['condominos'][$j]["nome"] = str_pad($obj->quadras->quadra,2,"0",STR_PAD_LEFT)."-".str_pad($obj->lote,2,"0",STR_PAD_LEFT)." ".substr($obj->nome,0,30);
					
					$boleto = $db->fetch("select * from boletos where idcobrancas = '".$this->registro->idcobrancas."' and idcondominos = '".$obj->id."'");
					
					//$this->campo["boletos"]['condominos'][$j]["rows"] = $db->rows();
					
					//$this->campo["boletos"]['condominos'][$j]["boleto"] = $boleto;
					
					if($db->rows){						
						if($idtipos_de_cobrancas == 2){
							$this->campo["boletos"]['condominos'][$j]["in"] = $boleto['internet'];
						}elseif($idtipos_de_cobrancas == 3){
							$this->campo["boletos"]['condominos'][$j]["if"] = $boleto['interfone'];
						}else{
							$this->campo["boletos"]['condominos'][$j]["tm"] = $boleto['taxa_manutencao'];
							$this->campo["boletos"]['condominos'][$j]["fr"] = $boleto['fundo_reserva'];
							$this->campo["boletos"]['condominos'][$j]["rc"] = $boleto['rocagem'];
							$this->campo["boletos"]['condominos'][$j]["cc"] = $boleto['chamada_capital'];
							$this->campo["boletos"]['condominos'][$j]["bf"] = $boleto['benfeitoria'];
							$this->campo["boletos"]['condominos'][$j]["sl"] = $boleto['salao_festa'];
							$this->campo["boletos"]['condominos'][$j]["ou"] = $boleto['outros'];
						}
							$this->campo["boletos"]['condominos'][$j]["vl"] = $boleto['valor'];
					}
					
					$j++;
					$db->resource($resource);
				}
	}
	
	
	
	
	function grafico($grafico){
			global $db,$usuario,$admin;
						//if(file_exists($_SERVER['DOCUMENT_ROOT']."adm/includes/_clientes/grafico_".$grafico.".php")){
							
								$this->campo[$grafico]['tag'] = "div";
								$this->campo[$grafico]['type'] = "grafico";
								include("includes/_clientes/grafico_".$grafico.".php");
						//}
				
		}
}
?>