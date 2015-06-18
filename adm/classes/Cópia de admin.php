<?


class admin{
	var $tg;
	var $sub_tg;
	var $sub_id;
	var $acao;
	var $acao_pag;
	var $acao_busca;
	var $acao_ordem;
	var $id;
	var $extra;
	var $funcao;
	var $tabela;
	var $idtabela;
	var $ABSURL;	
	var $localhost;
	var $admin;
	var $titulo;
	//var $menu;
function admin($var){
		global $db,$_localhost, $_ABSURL, $_serverRoot, $_siteRoot, $_admin,$usuario;
		//$_tmp = explode("/",$_SERVER['QUERY_STRING']);
		$_tmp = explode("/",$var);
			
		//pre($_SERVER['QUERY_STRING']);
		
		//array_shift($_tmp);		
		//pre($_tmp);
		
		if(preg_match('/admin/i',$_tmp[1]) ){
			array_shift($_tmp);
		}
		$this->vars = $_tmp;
		
		//pre($this->vars);die();
		$t = $this->vars["0"];
		
		if($t=='')
			$this->tg = 'Home';
		else
			$this->tg = $t;
		
		$tg = explode("@",$this->tg);	
		
		$this->funcao = 'listagem';
		
		
		if(preg_match("/view/",$tg[0])){
			$_tabela = explode("_view_",$tg[0]);
			$this->tabela = $_tabela[0];
			$this->idtabela = "id".$_tabela[0];
			$this->view = $tg[0];	
		}else{
			$this->tabela = $tg[0];
			$this->idtabela = "id".$this->tabela;
			$this->view = $this->tabela;	
		}
		
		$this->tg = $this->tabela;	
		
		$this->sub_tg = isset($tg[1]) ? $tg[1] : '';
		$this->sub_id = isset($tg[2]) ? $tg[2] : '';
		$this->acao= $this->vars["1"];	
		
		$acao = explode("@",$this->acao);	
		$this->acao = $acao[0];		
		$this->acao_pag = isset($acao[1]) ? $acao[1] : 0;
		$this->acao_busca = isset($acao[2]) ? $acao[2] : '';
		$this->acao_ordem = isset($acao[3]) ? $acao[3] : '';
		
		$this->id= isset($this->vars["2"]) ? $this->vars["2"] : '';			
		$this->extra = isset($this->vars["3"]) ? $this->vars["3"] : '';		
		
		if(!file_exists(".htaccess")){
			$this->links = "?";
		}
		
		
		$this->localhost = $_localhost;
		$this->ABSURL = $_ABSURL;
		$this->_serverRoot = $_serverRoot;
		$this->_siteRoot = $_siteRoot;
		$this->admin = $_admin;
						
		$db->query("select * from configuracoes");
		while($res = $db->fetch())
			$this->configs[$res["parametro"]] = nl2br($res["valor"]);
		
		$this->menu = new objetoDb('menus',$this->tg.($this->sub_tg ? '@'.$this->sub_tg : ''));
		if($this->menu->id){
			$this->titulo = ' &raquo; '.$this->menu->menu;
		}
		if($this->tabela and $db->tabelaExiste($this->tabela)){
			$this->campos = $db->campos_da_tabela($this->tabela);
			if($db->campoExiste("ordem",$this->tabela)){
					$this->ordenavel = true;
					$this->ordenar = "ordem";
			}else{
				$this->ordenar = $db->primeiroCampo($this->tabela);
			}
		}
		$this->listagemLinks = array();
		$this->listagemOperacoes = array();
		$this->addOpcao('onsubmit','return enviaFormulario(\''.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/listar@'.$this->acao_pag.'@'.$this->acao_busca.'@'.$this->acao_ordem.'\')');
		
		//pre($this);
	}
function menu($idPai=0,$nivel=1){
		global $db,$usuario;
		
		$ret = '<ul'.($idPai==0 ? '' : ' style=" display: none;" id="grupoMenu_'.$idPai.'" class="grupoMenu"').'>';
		if($usuario->menus){
			foreach($usuario->tipos_de_usuarios->menus as $menu){
				//die($menu->propriedades['@menus']."|");
				if($menu->propriedades['@menus'] == $idPai){
					if($nivel < 2)
						$sub = $this->menu($menu->id,$nivel+1);
					$ret .= '<li ><a href="#'.str_replace("/","",$menu->url).'" class="itemMenu nivel'.$nivel.'" onclick="'.($menu->url ? $menu->tipos_de_menus->funcao.'(\''.str_replace("/","",$menu->url).'\');' : '').(($sub and $nivel < 2) ? ' toggleMenu(\''.$menu->id.'\');' : '').'">';
					if($menu->icone and file_exists('imagens/icons/24x24/'.$menu->icone)){
						$ret .= '<img src="'.$this->localhost.$this->admin.'imagens/icons/24x24/'.$menu->icone.'" border="0" style="width:24px; height:24px" class="icone"  align="absmiddle"/> ';
					}else{
						$ret .= '<img src="'.$this->localhost.$this->admin.'imagens/icons/24x24/open_folder.png" border="0" style="width:24px; height:24px" class="icone"  align="absmiddle"/> ';
					}
					$ret .= $menu->menu;
					$ret .= '</a>'.$sub.'</li>';
				}
			}
		}else{
			$ret .= '<li><a href="'.$this->localhost.$this->admin.'#">Você não tem acesso a nenhuma seção válida do painel administrativo. Consulte o administrador do sistema.</a></li>';
			$ret .= '<li><a href="'.$this->localhost.$this->admin.'Sair">Sair.</a></li>';
		}
		$ret .= "</ul>";
		return $ret;
	}
function resetHtml(){
		$tmp = $this->html;
		$this->html = '';
		return $tmp;
	}
function addOpcao($parametro, $valor){
		$this->opcoes[$parametro] = $valor;
	}
function tit(){
		global $db;
	
		if($this->menu->icone and file_exists('imagens/icons/24x24/'.$this->menu->icone)){
			$titulo['icone'] = $this->menu->icone;
		}else{
			$titulo['icone'] = 'open_folder.png';
		}
		
		switch($this->acao){
			case editar: $titulo['acao'] = 'Alteração'; break;
			case novo: $titulo['acao'] = 'Inclusão'; break;
			default: $titulo['acao'] = 'Listagem'; break;
		}
		$titulo['caption'] = $this->titulo;
		
		return $titulo;
	}

function barra(){
		global $usuario;
		
		
		if(is_array($this->botoes_adicionais)){
			foreach($this->botoes_adicionais as $botao){
				$barra['botoes'][] = $this->botao($botao['caption'],$this->localhost.$this->admin.'#'.$botao['tg'],$botao['funcao']."('".$botao['tg']."');",$botao['imagem']);
			}
		}
		
		
			$barra['botoes']['atualizar']['caption'] = 'Recarregar';
			$barra['botoes']['atualizar']['alt'] = 'Recarregar listagem';
			$barra['botoes']['atualizar']['href'] = '#'.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'';
			$barra['botoes']['atualizar']['funcao'] = "listagem('".$this->tg."')";
			$barra['botoes']['atualizar']['imagem'] = 'refresh.png';
			
			
		if((@in_array($this->tabela,$usuario->tabelas) and !@preg_match('/^view_/i',$this->tabela))){
			$barra['botoes']['novo']['caption'] = 'Novo';
			$barra['botoes']['novo']['alt'] = 'Inserir novo registro';
			$barra['botoes']['novo']['href'] = '#'.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/novo/';
			$barra['botoes']['novo']['funcao'] = "formulario('".$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id."/novo')";
			$barra['botoes']['novo']['imagem'] = 'add.png';
		
			$barra['botoes']['deletar']['caption'] = 'Excluir';
			$barra['botoes']['deletar']['alt'] = 'Excluir selecionados';
			$barra['botoes']['deletar']['href'] = '#'.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id;
			$barra['botoes']['deletar']['funcao'] = "removerGrupo('".$this->tg."')";
			$barra['botoes']['deletar']['imagem'] = 'delete.png';
		
		}
			$barra['botoes']['imprimir']['caption'] = 'Imprimir';
			$barra['botoes']['imprimir']['alt'] = 'Versão para impressão';
			$barra['botoes']['imprimir']['href'] = '#'.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id;
			$barra['botoes']['imprimir']['funcao'] = "imprimirRelatorio('".$this->view.'@'.$this->sub_tg.'@'.$this->sub_id."','".'@'.$this->acao_pag.'@'.$this->acao_busca.'@'.$this->acao_ordem."')";
			$barra['botoes']['imprimir']['imagem'] = 'printer.png';
			
			
		//if(!$usuario->clientes->id and $usuario->id == 1)
		//if($usuario->id == 1)
			//$barra['botoes'][] = $this->botao('Relatório Excel',$this->localhost.$this->admin.'#'.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/exportar',"exportar('".$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id."/exportar/".base64_encode(serialize($_POST))."')",'Save.png');
		
		//if(!$usuario->clientes->id and $usuario->id == 1)
		//if($usuario->id == 1)
			//$barra['botoes'][] = $this->botao('Versão para Impressão',$this->localhost.$this->admin.'#'.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/exportar',"imprimirRelatorio('".$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id."')",'Print.png');
		
		return $barra;
	}
function listagem($sql){
		global $db,$usuario;
		
		//if($this->acao_busca){
			
			//pre($this->acao_busca);
			$output = array();
			//pre($output);
			parse_str($this->acao_busca, $output);
			if(preg_match("/=/i",$this->acao_busca)){/////////////filtros
				foreach($output as $campo=>$valor){
						if(preg_match("/_ini$/i",$campo)){
							$tmp['camposlistagem'][] = $this->view.".".preg_replace("/_ini/i","",$campo)." >= '".$valor."'";
						}elseif(preg_match("/_fim$/i",$campo) and $valor != 'Data final'){
							$tmp['camposlistagem'][] = $this->view.".".preg_replace("/_fim/i","",$campo)." <= '".$valor."'";
						}elseif($valor and $valor != 'Data inicial' and $valor != 'Data final'){
							$tmp['camposlistagem'][] = $this->view.".".$campo." = '".$valor."'";
						}
				}
//				switch($_REQUEST['formato']){
//					case 'date':
//						$tmp['camposlistagem'][] = $_REQUEST['campo']." > '".in_data($_REQUEST['ini'])."'";
//						$tmp['camposlistagem'][] = $_REQUEST['campo']." < '".in_data($_REQUEST['fim'])."'";
//					break;
//				}
				if( is_array($tmp['camposlistagem']) )
					$camposlistagem = implode(" and ", $tmp['camposlistagem']);
			}elseif(count($output) == 1){///////////busca
				$this->view = $this->tabela;
				foreach($this->campos_listagem as $campo => $valor){
					list($c,$s) = explode("->",$valor);
					if( $s ){
						$tmp['campos'][] =  $c.".".$s ;
						if($c != $this->tabela) $tmp['tabelas'][] =  $c ;
						$tmp['camposJoin'][] =  $this->view.".id".$c." = ".$c.".id".$c;
					}else{
						$tmp['campos'][] = $this->view.".".$c ;
					}
				}
			
				foreach($tmp['campos'] as $campo){
						$tmp['camposlistagem'][] = "($campo like '%".$this->acao_busca."%' )";
				}
			}
			
		
				if($this->sub_tg){
						$tmp['tabelas'][] =  $this->view."_has_".$this->sub_tg ;
						$tmp['camposJoin'][] =  $this->view.".id".$this->view." = ".$this->view."_has_".$this->sub_tg.".id".$this->view;
						$where = " and ".$this->view."_has_".$this->sub_tg.".id".$this->sub_tg." = ".$this->sub_id;
				}
				
				if( is_array($tmp['camposlistagem']) )	$camposlistagem = implode(" or ", $tmp['camposlistagem']);
				$lista['propriedades']['busca'] = $this->acao_busca;
				if( is_array($tmp['tabelas']) )	$tabelas = implode(", ", array_unique($tmp['tabelas']));
				if( is_array($tmp['campos']) )	$campos = implode(", ", $tmp['campos']);
				if( is_array($tmp['camposJoin']) )	$camposJoin = implode(" and ", array_unique($tmp['camposJoin']));
				
				if(is_array($usuario->condicoes) and count($usuario->condicoes)){
					foreach($usuario->condicoes as $_tabela => $condicao){
						$condicao = preg_replace("/__USUARIO_ID__/i",$usuario->id,$condicao);
						$condicao = preg_replace("/__CADASTRO_ID__/i",$usuario->cadastros->id,$condicao);
						$condicao = preg_replace("/__CLIENTE_ID__/i",$usuario->cadastros->clientes->id,$condicao);
						if($_tabela == $this->tabela) $tmp['camposCondicionais'][] = $condicao;
					}
					
					if( is_array($tmp['camposCondicionais']) )	$camposCondicionais = implode(" and ", array_unique($tmp['camposCondicionais']));
				}
				$sql = "select ".$this->view.".".$this->idtabela;
				$sql .= " from ".$this->view.( $tabelas ? " left join (".$tabelas.") on (".$camposJoin.")" : "" );
				$sql .= " where 1".$where;
				
				if($camposlistagem)
					$sql .= " and (".$camposlistagem.")";
				
				if($camposCondicionais)
					$sql .= " and (".$camposCondicionais.")";
					
				//if($this->sub_tg)
					//$sql .= " and (".$this->sub_tg .' = '.$this->sub_id.')';
				
		//}
		
		reset($this->campos_listagem);
		if(!preg_match('/ group by /i',$sql)) $sql .= " group by ".$this->view.".".$this->idtabela."";
		
		if($this->acao_ordem){
			list($ordem,$sentido) = explode("=",$this->acao_ordem);
			$sql .= " order by ".$this->view.".".$ordem." ".$sentido."";
		}elseif($this->ordenar){
			if($this->extra == 'DESC'){ $sentido = " DESC";}
			else{$sentido = " ASC";}
			$sql .= " order by ".$this->view.".".$this->ordenar.$sentido."";
		}
		
		if($this->acao_pag){
			$sql .= " limit ".($this->acao_pag * $this->configs["limite_listagem"]).",".$this->configs["limite_listagem"]."";
		}
		$sql = preg_replace('/\$usuario->cadastros->id/',$usuario->cadastros->id,$sql);
		$sql = preg_replace('/\$usuario->cadastros->representantes->id/',$usuario->cadastros->representantes->id,$sql);
		$lista['propriedades']['sql'] = $sql;
		$lista['propriedades']['acao_ordem'] = $this->acao_ordem;
		$lista['propriedades']['ordenar'] = $this->ordenar;
		$lista['propriedades']['tabela'] = $this->tabela;
		$lista['propriedades']['rows'] = $db->tabelas[$this->tabela]['Rows'];
		$lista['propriedades']['total'] = 0;
		
		
		
		$lista['output'] = $output;
		$lista['tmp'] = $tmp;

		
		$db->query($sql);
		$rows = $db->rows;
			if($this->ordenavel) $lista['propriedades']['ordenacao'] = true;
			else $lista['propriedades']['ordenacao'] = false;
		  
			$lista['propriedades']['visualizacao'] = true;
			if(preg_match('/^view_/i',$this->tabela)){
					$lista['propriedades']['edicao'] = false;
					$lista['propriedades']['visualizacao'] = false;
			}else{
				if( in_array($this->tabela,$usuario->tabelas) and !preg_match('/^view_/i',$this->tabela))
					$lista['propriedades']['edicao'] = true;
				else
					$lista['propriedades']['edicao'] = false;
			}
			
			
			$header['href'] = '';
			$header['classe'] = 'f_int-11';
			$header['campo'] = 'link';
			$header['visor'] = 'id';
			$header['funcao'] = '';
			$lista['headers'][] = $header;
			
			foreach($this->campos_listagem as $campo => $valor){
				list($c,$s) = explode("->",$valor);
				$tmp = $s ? "id".$c : $c ;
				
				
				//$header['href'] = ''.$this->localhost.$this->admin.'#'.$this->tg.($this->sub_tg ? "@".$this->sub_tg : "").'/listarPor/'.$tmp.($this->ordenar == $tmp? '/'.$sentido : '').'';
				//$header['click'] = 'listagem(\''.$this->tg.($this->sub_tg ? "@".$this->sub_tg : "").'\',\''.$tmp.($this->ordenar == $tmp? '/'.$sentido : '').'\');';
				
				
				$header['visor'] = $campo;
				
				
				if($usuario->tipos_de_usuarios->id > 2){
					$header['classe'] = 'f_'.diretorio($this->campos[$valor]['Type']);
					$header['campo'] = $valor;
				}elseif(preg_match('/>/i',$valor)){
					$tmp1 = preg_replace("/(.*)->(.*)/i","\\1",$valor);
					$tmp2 = preg_replace("/(.*)->(.*)/i","\\2",$valor);
					$campos_da_tabela = $db->campos_da_tabela($tmp1);
					$header['classe'] = 'f_'.diretorio($campos_da_tabela[$tmp2]['Type']).' edit_select';
					//$header['tabela'] = $tmp1;
					$header['campo'] = 'id'. preg_replace("/(.*)->(.*)/i","\\1",$valor);
					
					$resourceSelect = $db->resourceAtual;
					$primeiro = $db->primeiroCampo($tmp1);
					$camposCondicionais = array();
					if(is_array($usuario->condicoes) and count($usuario->condicoes)){
						foreach($usuario->condicoes as $_tabela => $condicao){
							$condicao = preg_replace("/__USUARIO_ID__/i",$usuario->id,"a.".$condicao);
							$condicao = preg_replace("/__CADASTRO_ID__/i",$usuario->cadastros->id,$condicao);
							$condicao = preg_replace("/__CLIENTE_ID__/i",$usuario->cadastros->clientes->id,$condicao);
							if($_tabela == $tmp1) $camposCondicionais[] = $condicao;
						}
						if( is_array($camposCondicionais) )	$camposCondicionais = implode(" and ", array_unique($camposCondicionais));
					}
					
					if(preg_match("/\->/i",$tmp1)){
						list($_campo,$_subcampo,$_extrasubcampo) = explode("->",$valor);
						$header['campo'] = $_extrasubcampo;
						$sql = "select ".$_subcampo.".* from ".$_subcampo." where 1 ".($camposCondicionais ? " ".$camposCondicionais.")" : "")."";
					}else{
						$sql = "select a.id".$tmp1.",a.".$primeiro." from ".$tmp1." as a,".$this->tabela." as b where a.id".$tmp1." = b.id".$tmp1.($tmp1 == $this->tabela ? "_2": "").($camposCondicionais ? " and (".$camposCondicionais.")" : "")." group by  a.id".$tmp1." order by a.".$primeiro."";
					}
					
					
					
					
					//die($sql);
					$header['sql'] = $sql;
					
					
						$header['selected'] = $output[$header['campo']];
					
/*							if(is_array($usuario->condicoes) and count($usuario->condicoes)){
						unset($_condicoes);
						reset($usuario->condicoes);
						foreach($usuario->condicoes as $_tabela => $condicao){
							if($_tabela == $tmp1){
								$_condicoes[] = $condicao;
							}
						}							
						if(is_array($_condicoes) and count($_condicoes) > 0) $sql .= " where ".implode(" and ", $_condicoes)."";
						$sql = preg_replace('/\$usuario->cadastros->id/',$usuario->cadastros->id,$sql);
					}
*/							$db->query($sql);
					if($db->rows < 200){
						while($res = $db->fetch()){
							if($tmp1 == 'clientes'){
								$obj = new objetoDb($tmp1,$res["id".$tmp1]);//pre($obj);
								$header['opcoes'][$res["id".$tmp1]] = trim($obj->fantasia.' - '.preg_replace("/\-/i"," ",$obj->nome));
							}else{
								$header['opcoes'][$res["id".$tmp1]] = $res[$primeiro];
							}
						}
					}
					$db->resource($resourceSelect);
				}elseif(preg_match('/enum/i',$this->campos[$valor]['Type'])){
					$header['classe'] = 'f_'.diretorio(preg_replace("/\(.*\)/i","",$this->campos[$valor]['Type'])).' edit_enum';
					$header['campo'] =$valor;
					$opcoes = explode("','",str_replace("')",'',str_replace("enum('",'',$this->campos[$valor]["Type"])));
					foreach($opcoes as $op)
							$header['opcoes'][utf8_encode($op)] = $op;
				}elseif(preg_match('/7/i',$this->campos[$valor]['Type'])){
					$header['classe'] = 'f_'.diretorio($this->campos[$valor]['Type']).' edit_month';
					$header['campo'] =$valor;
				}else{
					$header['classe'] = 'f_'.diretorio($this->campos[$valor]['Type']).' edit';
					$header['campo'] = $valor;
				}

				
				
				switch(true){
					case 'varchar(18)' == $this->campos[$valor]["Type"]:
						$header['v'] = ""; $header['m'] = "cpf";
					break;
					case 'varchar(200)' == $this->campos[$valor]["Type"]:
						$header['v'] = "email"; $header['m'] = "";
					break;
					case 'varchar(300)' == $this->campos[$valor]["Type"]:
						$header['v'] = "url"; $header['m'] = "";
					break;
					case 'varchar(7)' == $this->campos[$valor]["Type"]:
						$header['v'] = "mes"; $header['m'] = "mes";
					break;
					case 'varchar(9)' == $this->campos[$valor]["Type"]:
						$header['v'] = "cep"; $header['m'] = "cep";
					break;
					case 'varchar(10)' == $this->campos[$valor]["Type"]:
					case 'varchar(14)' == $this->campos[$valor]["Type"]:
						$header['v'] = "phone"; $header['m'] = "phone";
					break;
					case 'date' == $this->campos[$valor]["Type"]:
					case 'datetime' == $this->campos[$valor]["Type"]:
						$header['v'] = "date"; $header['m'] = "date";
					break;
					case 'int(10) unsigned' == $this->campos[$valor]["Type"]:
						$header['m'] = "integer";
					break;
					case 'int(10)' == $this->campos[$valor]["Type"]:
					case 'int(11)' == $this->campos[$valor]["Type"]:
					case 'tinyint(4)' == $this->campos[$valor]["Type"]:
					case 'float(9,3)' == $this->campos[$valor]["Type"]:
						$header['m'] = "integer";
					break;
					case 'smallint(6)' == $this->campos[$valor]["Type"]:
						$header['m'] = "month";
					break;
					case preg_match("/double/i",$this->campos[$valor]["Type"]):
					case preg_match("/float/i",$this->campos[$valor]["Type"]):
					case preg_match("/bigint/i",$this->campos[$valor]["Type"]):
					case preg_match("/decimal/i",$this->campos[$valor]["Type"]):
						$header['v'] = "decimal"; $header['m'] = "decimal";
						$header['classe'] = 'f_'.diretorio(preg_replace("/\(.*\)/i","",$this->campos[$valor]['Type'])).' edit';
					break;
					case preg_match("/^enum/i",$this->campos[$valor]["Type"]):
					break;
					default: 
						$header['v'] = ""; $header['m'] = "";break;
				}
						
				$lista['headers'][] = $header;
				unset($header);
			}
			if(count($this->listagemLinks)){
				foreach($this->listagemLinks as $link){
					$header['href'] = $link['url'];
					$header['classe'] = 'f_link';
					$header['campo'] = 'link';
					$header['visor'] = $link['caption'];
					$header['funcao'] = $link['function'];
					$lista['headers'][] = $header;
				}
			}
			if(count($this->listagemOperacoes)){
				foreach($this->listagemOperacoes as $operacao){
					$header['href'] = $link['url'];
					$header['classe'] = 'f_';
					$header['campo'] = 'link';
					$header['visor'] = $operacao['caption'];
					$lista['headers'][] = $header;
				}
			}
			if($db->campoExiste("id".$this->tabela,"arquivos")){
				$header['href'] = $this->localhost.'_Down/';
				$header['classe'] = 'f_link';
				$header['campo'] = 'arquivo';
				$header['visor'] = 'Anexo(s)';
				$header['funcao'] = 'popup';
				$lista['headers'][] = $header;
			}
//			if($db->campoExiste("id".$this->tabela,"fotos")){
//				$header['href'] = $this->localhost.'img/';
//				$header['classe'] = 'f_link';
//				$header['campo'] = 'imagem';
//				$header['visor'] = 'Imagem(s)';
//				$header['funcao'] = 'popup';
//				$lista['headers'][] = $header;
//			}
			
			
			if(file_exists("includes/_clientes/".$this->tabela.".php")){
				
				include("includes/_clientes/".$this->tabela.".inc.php");
				//$barra['botoes']['pdf']['funcao'] = "imprimirPDF('".$this->tabela."','".$this->id."')";
				
				//$header['href'] = '';
				//$header['classe'] = 'f_link';
				//$header['campo'] = 'pdf';
				//$header['visor'] = '<img src="imagens/icons/16x16/pdf_file.png" align="absmiddle">.PDF';
				//$header['funcao'] = 'imprimirPDF';
				//$lista['headers'][] = $header;
			}
		
			reset($this->campos_listagem);
			
			
			 $lista['itens'] = array();
			if($rows){
		  
				$resource = $db->resourceAtual;
				$limite_listagem = $this->configs["limite_listagem"] or 100;
				
				$j = 0;
				
				while($res = $db->fetch() and $j++ < $limite_listagem){
					++$lista['propriedades']['total'];
					$obj = new objetoDb($this->tabela,$res[$this->idtabela]);
					if(!$obj->existe()) continue;
					
					
					$item[$j][0] = $obj->id;
					
					$i = 1;
					while(list($caption,$campo) = each($this->campos_listagem)){
						list($campo,$subcampo,$extrasubcampo) = explode("->",$campo);
						
						if($subcampo){
							if($extrasubcampo){
								$tmp = $obj->$campo->$subcampo->$extrasubcampo;
							}else{
								$tmp = $obj->$campo->$subcampo;
							}
						}else{
							$tmp = $obj->$campo ;
						}
						
						if(is_object($obj->$campo)){
							if($this->tabela == $campo){
								$visor = $obj->pai($this->tabela);
							}else{
								$visor = $obj->$campo->$subcampo;
								if($extrasubcampo){
									$visor = $obj->$campo->$subcampo->$extrasubcampo;
								}else{
									$visor = $obj->$campo->$subcampo;
								}
							}
							if($this->tabela == 'cidades'){
								$lista['headers'][$i]['opcoes'][$obj->$campo->id] = $visor;
								asort($lista['headers'][$i]['opcoes']);
							}
						}else{
						
							switch($this->campos[$campo]['Type']){
								case 'date': case 'datetime':
									$visor = ex_data($tmp);
								break;
								case 'double(19,2)':
									$visor = "R$ ".number_format($tmp,2,",",".");
								break;
								case 'float(11,2)': case 'float(9,2)':
									$visor = number_format($tmp,2,",",".");
								break;
								default:
									$visor = $tmp;
							}
						}
						
						if($this->termoBusca){
							$visor = preg_replace('/('.$this->termoBusca.')/i', '<span class="destaqueBusca">${1}</div>', $visor);
						}
						$item[$j][$i++] = ($visor ? $visor : '');
						unset($visor);
					}
					$db->resource($resource);
					
					if(count($this->listagemLinks)){
						foreach($this->listagemLinks as $link){
							$item[$j][$i++] = $obj->id;
						}
					}
					
					if(count($this->listagemOperacoes)){
						foreach($this->listagemOperacoes as $operacao){
							$item[$j][$i++] = eval($operacao['eval']);
						}
					}
					
					reset($this->campos_listagem);
					
					if($db->campoExiste("id".$this->tabela,"arquivos")){
						if(count($obj->arquivos))
							$item[$j][$i++] = $obj->arquivos[0]->id.'/'.$obj->arquivos[0]->arquivo;
					}
//					if($db->campoExiste("id".$this->tabela,"fotos")){
//						if(count($obj->fotos))
//							$item[$j][$i++] = $obj->id;
//					}
					
					if(file_exists("includes/_clientes/".$this->tabela.".php")){
							//$item[$j][$i++] = $this->tabela."','".$obj->id;//////// pedidos
							
							eval($include_cliente_comando);
							
							//$item[$j][$i++] = $obj->id."-".md5($obj->email);
					}
					if(file_exists("includes/_clientes/mais_".$this->tabela.".php")){
							
							include("includes/_clientes/mais_".$this->tabela.".php");
							
							
					}
										
					$lista['mais'] = $mais;
					$lista['itens'] = $item;
				}
			  //$this->html .= $retlistagemPaginacao;
			  
			  
		  }else{
		 	 //$this->html .= '<li><h3>Nenhum registro cadastrado</h3></li>';
			 $lista['itens'] = array();
		  }
		  
		  
		  
		  
		  
		  
//	  $this->html .='
//		  </ul>
//		  ';
//		  if($this->ordenavel and $usuario->tipos_de_usuarios->id <= 2){
//		  $this->html .='
//		    <script type="text/javascript" charset="utf-8">
//			Sortable.create(
//				"lista",
//				{
//					only: "item",
//					onUpdate: function() {
//						ordenar("'.$this->tg.'",Sortable.serialize("lista","valores"));
//					},
//					constraint: "vertical",
//					handle: "hh"
//				}
//			);
//			< /script>';
//			}
//		  $this->html .= '
//		  </form>';
		  return $lista;
	}
function listagemPaginacao(){
		global $db, $usuario;
		
			$limite_listagem = $this->configs["limite_listagem"] ? $this->configs["limite_listagem"] : 50;
			
			$ret =  '<div class="navegacao"> Listando: <strong>'.( $limite_listagem < $db->rows ? $limite_listagem  : $db->rows) ."</strong> Total: <strong>".$db->rows."</strong> ";
			if($this->acao_pag > 3){
				$ret .=  '<a href="'.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/listar@0@'.$this->acao_busca.'@'.$this->acao_ordem.'" class="awesome yellow" onclick="return listagem(\''.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/listar@0@'.$this->acao_busca.'@'.$this->acao_ordem.'\')">&laquo; Início</a>';
			}
			for($p = ($this->acao_pag > 3 ? $this->acao_pag - 3 : 0) ; $p < ($db->rows/$limite_listagem) and $p < ($db->rows/$limite_listagem < 10  ? 10 : $this->acao_pag + 4); $p++){
				$ret .=  '<a href="'.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/listar@'.$p.'@'.$this->acao_busca.'@'.$this->acao_ordem.'" class="awesome '.( $this->acao_pag == $p ? " grey": " yellow").'" onclick="return listagem(\''.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/listar@'.$p.'@'.$this->acao_busca.'@'.$this->acao_ordem.'\')">'.($p+1).'</a>';
			}
			
			if($this->acao_pag < intval($db->rows/$limite_listagem)-4){
				$ret .=  '<a href="'.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/listar@'.intval($db->rows/$limite_listagem).'@'.$this->acao_busca.'@'.$this->acao_ordem.'" class="awesome yellow" onclick="return listagem(\''.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/listar@'.intval($db->rows/$limite_listagem).'@'.$this->acao_busca.'@'.$this->acao_ordem.'\')">... '.intval($db->rows/$limite_listagem).' &raquo;</a>';
			}
			$ret .= '<select onchange="return listagem(\''.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/listar@\' + this.value + \'@'.$this->acao_busca.'@'.$this->acao_ordem.'\')">';
			for($i = 0 ; $i < intval($db->rows/$limite_listagem) ; $i++){
				$ret .=  '<option value="'.$i.'">'.($i+1).'</option>';
			}
			$ret .= "</select>";
			
			$ret .=  '</div>';
			
			return $ret;
		//}
	}
function listagemLink($function,$caption,$url){
		array_push($this->listagemLinks, array('function' => $function , 'caption' => $caption , 'url' => $url ));
	}
function listagemOperacoes($caption,$eval){
		array_push($this->listagemOperacoes, array('caption' => $caption , 'eval' => $eval ));
	}
	
}
			
?>