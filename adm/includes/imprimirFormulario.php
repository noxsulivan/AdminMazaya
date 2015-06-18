<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Admin &raquo;
<?=$admin->titulo;?>
<?=$admin->configs["titulo_site"];?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="<?=$admin->localhost;?>admin/admin.css" rel="stylesheet" type="text/css">
</head>
<body class="site">


<?
	//$admin->tg = $admin->acao;
	
	$admin->acao = $admin->id;
	$admin->id = $admin->extra;
	include("lib/".$admin->tg.".php");

	//pre($admin);die();
	
	
	
			
			foreach($admin->campos_listagem as $campo => $valor){
				list($c,$s) = explode("->",$valor);
				if( $s ){
					$tmp[campos][] =  $c.".".$s." as ".$c.$s;
					$tmp[tabelas][] =  $c ;
					$tmp[camposJoin][] =  $admin->tg.".id".$c." = ".$c.".id".$c;
				}else{
					if('id'.$admin->tg != $c)
						$tmp[campos][] = $admin->tg.".".$c;
				}
			}
			foreach($tmp[campos] as $campo){
					$tmp[camposlistagem][] = "($campo like '%".$admin->id."%' )";
			}
			
			if( is_array($tmp[tabelas]) )	$tabelas = implode(", ", $tmp[tabelas]);
			if( is_array($tmp[campos]) )	$campos = implode(", ", $tmp[campos]);
			if( is_array($tmp[camposJoin]) )	$camposJoin = implode(" and ", $tmp[camposJoin]);
			
			if( is_array($tmp[camposlistagem]) )	$camposlistagem = implode(" or
				", $tmp[camposlistagem]);
			
			$sql = "
			select ".$admin->tg.".id".$admin->tg.", ".$campos."
			from ".$admin->tg." ".( $tabelas ? ", ".$tabelas." where ".$camposJoin."" : "" );
			
			if($admin->id){
				$sql = "
				and
					".$camposlistagem;
			}
				
				
		reset($admin->campos_listagem);
	
		if($admin->ordenavel ){
			$sql .= " order by ".$admin->tg.".".$admin->ordenar."";
		}else{
			list($campo,$valor) = each($admin->campos_listagem);
			list($c,$s) = explode("->",$valor);
			$tmp = $s ? "id".$c : $c ;
			$sql .= " group by ".$tmp." order by  ".$tmp." asc";
		}
		
		//pre($sql);die();
		
		if(!$db->query($sql)){
			$ret .= "Não foi possível executar a consulta ao banco de dados<br>$sql<br>";
		}
		
		
		
		
		  
		//tiitulo da listagem	
		foreach($admin->campos_listagem as $campo => $valor){
				$visor[] = '"'.$campo.'"';
		}
		$ret .= implode(";",$visor);
		unset($visor);
		reset($admin->campos_listagem);
		
		
		
					$ret .= "\r\n";
		  
		  if($db->rows){
		  
		  
				while($res = $db->fetch()){
					//$obj = new objetoDb($admin->tg,$res[$admin->idtabela]);
					
					//pre($res);
					
					while(list(,$campo) = each($admin->campos_listagem)){
						list($campo,$subcampo) = explode("->",$campo);
						
						if($subcampo)	$tmp = $res[$campo.$subcampo];//$obj->$campo->$subcampo;
						else 			$tmp = $res[$campo];//$obj->$campo ;
						
						//if($obj->primeiroCampo == $campo){
							//$visor[] = '"'.$obj->pai().'"';
						//}else{
						
							switch($admin->campos[$campo]['Type']){
								case 'date': case 'datetime':
									$visor[] = ex_data($tmp);
								break;
								case 'float': case 'float(11,2)': case 'float(9,2)':
									$visor[] = number_format($tmp,2,",",".");
								break;
								default:
									$visor[] = '"'.$tmp.'"';
							}
						//}
						
						
						
					}
						$ret .= implode(";",$visor);
						unset($visor);
					reset($admin->campos_listagem);
					
					$ret .= "\r\n";
				}
			  
			  
		  }else{
		 	 $ret .= 'Nenhum registro cadastrado';
		  }
		  
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$admin->tg.'-'.$admin->acao.'-'.$admin->id.'-'.date("dmY_Hi").'.csv"');
		  echo $ret;
?>
</body>
</html>