<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Dados do eventos');
	if($usuario->tipos_de_usuarios->id != 4){
				$formulario->fieldset->simples('Condômino', 'idcondominos');
	}
	$formulario->fieldset->simples('Título', 'evento');
	//$formulario->fieldset->separador();
	
	
	//$formulario->fieldset->canal($tipos_de_canais=2,$admin->registro->idcanais);
	$formulario->fieldset->simples('Data','data');
	//$formulario->fieldset->simples('Local','local');
	$formulario->fieldset->simples('Local','idareas_comuns');
	//$formulario->fieldset->separador();
	$formulario->fieldset->simples('Observações','obs');
	$formulario->fieldset('Imagens');
	$formulario->fieldset->fotos();
	
	$formulario->fieldset('Convidados', false, true);
	$formulario->fieldset->filhos('eventos_has_convidados');
			
	
	//$admin->tit_formulario('Video');
	//$admin->campo_arquivo();
	
	//$admin->tit_formulario('Notícias relacionadas');
	//$admin->campo_itens_relacionados(noticias,titulo,$res["idnoticias"]);
	
	
break;
case "salvar":
	if($admin->id == ''){
	
		$_POST['idcondominos'] = $usuario->condominos->id;
		$db->inserir('eventos');
		$_id = $db->inserted_id;
		$db->salvar_fotos('eventos',$_id);
		$db->filhos('eventos',$_id);
	}else{
		$db->editar('eventos',$admin->id);
		$db->salvar_fotos('eventos',$admin->id);
		$db->filhos('eventos',$admin->id);
	
	}
break;
default:
	
	
	if($usuario->tipos_de_usuarios->id == 4){
		$admin->campos_listagem = array('Data' => "data",'Evento' => "evento",'Local' => "areas_comuns->nome");//'Condômino' => "cndominos->nome",
	}else{
		$admin->campos_listagem = array('Data' => "data",'Evento' => "evento",'Condômino' => "condominos->nome",'Local' => "areas_comuns->nome");//
	}

	if(in_array( $usuario->tipos_de_usuarios->id, array(1,4))){
		//$admin->listagemLink('enviarEmail','<img src="imagens/buttons/mail_send.png" align="absmiddle">Enviar convites','');
	}
break; 
}
?>