<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Dados do avisos');
	$formulario->fieldset->simples('Título', 'titulo');
	
	
	//$formulario->fieldset->canal($tipos_de_canais=2,$admin->registro->idcanais);
	$formulario->fieldset->simples('Data','data');
	$formulario->fieldset->simples('Descrição','descricao');
	
	$formulario->fieldset('Imagens');
	$formulario->fieldset->fotos();
	
	if($usuario->tipos_de_usuarios->id != 4){
		$formulario->fieldset('Registro de vizualização',false,true);
		$formulario->fieldset->filhos('avisos_has_visualizacoes');
	}else{
		if(!$db->fetch("select idavisos_has_visualizacoes from avisos_has_visualizacoes where idcondominos = '".$usuario->condominos->id."' and idavisos = '".$admin->id."'")){
			$_POST['idcondominos'] = $usuario->condominos->id;
			$_POST['data'] = date("d/m/Y H:i:s");
			$_POST['idavisos'] = $admin->id;
			$db->inserir('avisos_has_visualizacoes');
		}
	}
	
	
	
	
break;
case "salvar":
	if($admin->id == ''){
	
		$_POST['idcondominos'] = $usuario->condominos->id;
		$_POST['data'] = date("d/m/Y H:i:s");
		$db->inserir('avisos');
		$id = $inserted_id = $db->inserted_id;
		$db->salvar_fotos('avisos',$inserted_id);
		//$db->filhos('avisos',$inserted_id);
		if(!$db->rows("select * from avisos_has_visualizacoes where idavisos = '".$id."'")){
			
			$sql = "select * from condominos group by email  order by idquadras, lote";
			$db->query($sql);
			$resource = $db->resourceAtual;
			while($res = $db->fetch()){
					$condomino = new objetoDb("condominos",$res['idcondominos']);		
					$_POST['idcondominos'] = $condomino->id;
					$_POST['idavisos'] = $id;
					$db->inserir('avisos_has_visualizacoes');
					$db->resource($resource);
			}
		}
	}else{
		$db->editar('avisos',$admin->id);
		$db->salvar_fotos('avisos',$admin->id);
		$id = $admin->id;
	
	}
	
	
	
			
break;
default:
	$admin->campos_listagem = array('Data' => "data",'Título' => "titulo",'Enviado em' => "data_envio");
	$admin->listagemLink('enviarLote','<img src="imagens/buttons/mail_send.png" align="absmiddle"> Disparar','');
	
				$admin->ordenar = "data";
				$admin->extra = "DESC";
break; 
}
?>