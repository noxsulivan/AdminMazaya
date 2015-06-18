<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title></title>

<link href="<?=$admin->localhost.$admin->admin."admin_admin.css";?>" rel="stylesheet" type="text/css" media="all">
<link href="<?=$admin->localhost.$admin->admin."admin_".$site.".css";?>" rel="stylesheet" type="text/css" media="all">
<link href='http://fonts.googleapis.com/css?family=Oxygen' rel='stylesheet' type='text/css'>
</head>
<body>
<div id="relatorioImpressao">
<h2>Relatório de <?=ucfirst($admin->tg)?><? 
				$sub_tg = new objetoDb($admin->sub_tg,$admin->sub_id);
				
				if($admin->sub_tg){
						$primeiro = $db->primeiroCampo($sub_tg->tabela);
						echo " agrupados por ".ucfirst($admin->sub_tg)."(".$sub_tg->$primeiro.")";
				}?></h2>
<?
	
	if(file_exists("lib/".$admin->tg.".php")){
		include("lib/".$admin->tg.".php");
	}else{
		include("lib/_geral.php");
	}
		
	if(!isset($admin->campos_relatorio))
	
		if(!is_array($admin->campos_listagem)){
			reset($campos);
			foreach($campos as $k=>$v){
				if($v["Key"] == 'PRI'){
				}elseif($v["Key"] == 'MUL'){
					$_tab = preg_replace("/^(id)/i","",$k);
					$_pri = $db->primeiroCampo($_tab);
					$_temp[normaliza($_tab)] = $_tab.'->'.$_pri;
				}else{
					$_temp[normaliza($k)] = $k;
				}
			}
			$admin->campos_listagem = $_temp;
		}
		$admin->campos_relatorio = $admin->campos_listagem;
	
	
						//pre($admin->acao_busca);
			$output = array();
			//pre($output);
			parse_str($admin->acao_busca, $output);
			if(preg_match("/=/i",$admin->acao_busca)){/////////////filtros
				foreach($output as $campo=>$valor){
						if(preg_match("/_ini$/i",$campo)){
							$tmp['camposlistagem'][] = $admin->view.".".preg_replace("/_ini/i","",$campo)." >= '".$valor."'";
						}elseif(preg_match("/_fim$/i",$campo) and $valor != 'Data final'){
							$tmp['camposlistagem'][] = $admin->view.".".preg_replace("/_fim/i","",$campo)." <= '".$valor."'";
						}elseif($valor and $valor != 'Data inicial' and $valor != 'Data final'){
							$tmp['camposlistagem'][] = $admin->view.".".$campo." = '".$valor."'";
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
				foreach($admin->campos_listagem as $campo => $valor){
					list($c,$s) = explode("->",$valor);
					if( $s ){
						$tmp['campos'][] =  $c.".".$s ;
						$tmp['tabelas'][] =  $c ;
						$tmp['camposJoin'][] =  $admin->view.".id".$c." = ".$c.".id".$c;
					}else{
						$tmp['campos'][] = $admin->view.".".$c ;
					}
				}
			
				foreach($tmp['campos'] as $campo){
						$tmp['camposlistagem'][] = "($campo like '%".$admin->acao_busca."%' )";
				}
			}
			
			
				if($admin->sub_tg){
						$tmp['tabelas'][] =  $admin->view."_has_".$admin->sub_tg ;
						$tmp['camposJoin'][] =  $admin->view.".id".$admin->view." = ".$admin->view."_has_".$admin->sub_tg.".id".$admin->view;
						$where = " and ".$admin->view."_has_".$admin->sub_tg.".id".$admin->sub_tg." = ".$admin->sub_id;
				}
				
				
				if( is_array($tmp['camposlistagem']) )	$camposlistagem = implode(" and ", $tmp['camposlistagem']);
				$lista['propriedades']['busca'] = $admin->acao_busca;
				if( is_array($tmp['tabelas']) )	$tabelas = implode(", ", array_unique($tmp['tabelas']));
				if( is_array($tmp['campos']) )	$campos = implode(", ", $tmp['campos']);
				if( is_array($tmp['camposJoin']) )	$camposJoin = implode(" and ", array_unique($tmp['camposJoin']));
				
				if(is_array($usuario->condicoes) and count($usuario->condicoes)){
					foreach($usuario->condicoes as $_tabela => $condicao){
						$condicao = preg_replace("/__USUARIO_ID__/i",$usuario->id,$condicao);
						$condicao = preg_replace("/__CADASTRO_ID__/i",$usuario->cadastros->id,$condicao);
						$condicao = preg_replace("/__CLIENTE_ID__/i",$usuario->cadastros->clientes->id,$condicao);
						if($_tabela == $admin->tabela) $tmp['camposCondicionais'][] = $condicao;
					}
					
					if( is_array($tmp['camposCondicionais']) )	$camposCondicionais = implode(" and ", array_unique($tmp['camposCondicionais']));
				}
				$sql = "select ".$admin->view.".".$admin->idtabela;
				$sql .= " from ".$admin->view.( $tabelas ? " left join (".$tabelas.") on (".$camposJoin.")" : "" );
				$sql .= " where 1".$where;
				
				if($camposlistagem)
					$sql .= " and (".$camposlistagem.")";
				
				if($camposCondicionais)
					$sql .= " and (".$camposCondicionais.")";
				
		//}
		
		reset($admin->campos_listagem);
		if(!preg_match('/ group by /i',$sql)) $sql .= " group by ".$admin->view.".".$admin->idtabela."";
		
		if($admin->acao_ordem){
			list($ordem,$sentido) = explode("=",$admin->acao_ordem);
			$sql .= " order by ".$admin->view.".".$ordem." ".$sentido."";
		}elseif($admin->ordenar){
			if($admin->extra == 'DESC'){ $sentido = " DESC";}
			else{$sentido = " ASC";}
			$sql .= " order by ".$admin->view.".".$admin->ordenar.$sentido."";
		}

		$sql = preg_replace('/\$usuario->cadastros->id/',$usuario->cadastros->id,$sql);
		$sql = preg_replace('/\$usuario->cadastros->representantes->id/',$usuario->cadastros->representantes->id,$sql);
		
		
		$db->query($sql);
		
		if(!$db->query($sql)){
			$ret .= "Não foi possível executar a consulta ao banco de dados<br>$sql<br>";
		}
		
		
		
		
		  
		$ret .= '<table border="1" cellspacing="0" cellpadding="3" width="100%">
		';
		$ret .= "<tr>
		";
		foreach($admin->campos_relatorio as $campo => $valor){
			$ret .= '<td><strong>'.$campo.'</strong></td>';
		}
		$ret .= "</tr>
		";
		reset($admin->campos_relatorio);
		
		  
		  if($db->rows){
		  
				while($res = $db->fetch()){
					$obj = new objetoDb($admin->tg,$res[$admin->idtabela]);
					
					
					$ret .= "<tr>
					";
					while(list(,$campo) = each($admin->campos_relatorio)){
						list($campo,$subcampo) = explode("->",$campo);
						
						if($subcampo){
							//$tmp = 	'<a href="'.$admin->localhost.$admin->admin.$obj->$campo->tabela.'/atualizarDinamico/'.$obj->id.'/'.$obj->$campo->id.'" onclick="return hs.htmlExpand(this, { objectType: \'ajax\', width: \'370\', height: \'500\'} )">'.
							//$tmp .= 	'<img src="'.$admin->ABSURL.'_admin/imagens/bt_abrir.png"> ';
							$tmp = 	$obj->$campo->$subcampo;
							$total[$campo][$obj->$campo->$subcampo] = 1;
							//$tmp .= 	'</a>';
							//$tmp2 = '2' ;
						}else{
							$tmp = $obj->$campo ;
							//$tmp2 = '<span class="InPlaceEditor" id="'.diretorio($obj->tabela."__".$campo."__".$obj->id).'">'.$obj->$campo.'</span>';
							//$tmp2 = '1' ;
						}
						
						
						
						
							switch($admin->campos[$campo]['Type']){
								case 'date': case 'datetime':
									$visor = ex_data($tmp);
								break;
								case 'float(11,2)':
									$visor = number_format($tmp,2,",",".");
									$total[$campo] = ($total[$campo] + $tmp)/2;
								break;
								case 'decimal(10,3)': case 'float(9,3)': case 'float(9,2)':
									$visor = number_format($tmp,2,",",".");
									$total[$campo] += $tmp;
								break;
								case 'varchar(200)':
									$visor = $tmp;
									$total[$campo] += $tmp;
								break;
								case 'tinyint(1)':
									$visor = ( $tmp ? "SIM" : "");
									$total[$campo] += $tmp;
								break;
								default:
									$visor = $tmp;
									$total[$campo][$tmp] = 1;
							}
						
						$ret .= '<td>'.$visor.'</td>';
						$visor = '';
						
						
					}
					reset($admin->campos_relatorio);
					
					$ret .= "</tr>
					";
				}
				if(count($total)){
					$ret .= "<tr>
					";
					while(list(,$campo) = each($admin->campos_relatorio)){
						list($campo,$subcampo) = explode("->",$campo);
							switch($admin->campos[$campo]['Type']){
								case 'date': case 'datetime': case 'varchar(255)':
									$ret .= '<td >&nbsp;</td>';
								break;
								case 'float(9,2)': case 'decimal(10,3)': case 'float(9,3)':
									$ret .= '<td ><h4>Total: '.number_format($total[$campo],2,",",".").'</h4></td>';
								break;
								case 'tinyint(1)':
									$ret .= '<td ><h4>Total: '.$total[$campo].'</h4></td>';
								break;
								default:
									$ret .= '<td ><h4>'.count($total[$campo]).'</h4></td>';
							}
					}
					$ret .= "</tr>
					";
					
				}
		  $ret .= "</table>";
			  
			  
		  }else{
		 	 $ret .= 'Nenhum registro cadastrado';
		  }
		  
			//header('Content-type: application/vnd.ms-excel');
			//header('Content-Disposition: attachment; filename="'.$admin->tg.'-'.$admin->acao.'-'.$admin->id.'-'.date("dmY_Hi").'.csv"');
		  echo $ret;
		  
		  
?>
</div>
</body>
</html>