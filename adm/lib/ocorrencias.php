<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Dados da ocorr�ncia');
	if($usuario->tipos_de_usuarios->id != 4){
				$formulario->fieldset->simples('Cond�mino', 'idcondominos');
	}
	$formulario->fieldset->simples('T�tulo', 'ocorrencia');
	//$formulario->fieldset->canal($tipos_de_canais=2,$admin->registro->idcanais);
	$formulario->fieldset->simples('Data e hora','data',date("d/m/Y H:i"));
	$formulario->fieldset->simples('Descri��o','descricao');
	
	$formulario->fieldset('Respostas');
	$formulario->fieldset->filhos('ocorrencias_respostas');
			
	$formulario->fieldset('Fotos - Anexe se houver imagens dispon�veis');
	$formulario->fieldset->fotos();
	
	//$admin->tit_formulario('Video');
	//$admin->campo_arquivo();
	
	//$admin->tit_formulario('Not�cias relacionadas');
	//$admin->campo_itens_relacionados(noticias,titulo,$res["idnoticias"]);
	
	
break;
case "salvar":
	if($admin->id == ''){
	
		$_POST['idcondominos'] = $usuario->condominos->id;
		$db->inserir('ocorrencias');
		$_id = $db->inserted_id;
		$db->salvar_fotos('ocorrencias',$_id);
		$db->filhos('ocorrencias',$inserted_id);
	}else{
		$db->editar('ocorrencias',$admin->id);
		$db->salvar_fotos('ocorrencias',$admin->id);
		$db->filhos('ocorrencias',$admin->id);
	
	}
break;
default:
	$admin->campos_listagem = array('Data' => "data",'Ocorr�ncia' => "ocorrencia",'Cond�mino' => "condominos->nome");
break; 
}
?>