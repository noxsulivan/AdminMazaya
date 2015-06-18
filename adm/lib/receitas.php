<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	

	$formulario->fieldset('Dados da receita');
	
	
		
		$formulario->fieldset->simples('Título','titulo');
		$formulario->fieldset->simples('Subtítulo','subtitulo');	

		
		$formulario->fieldset->simples('Tempo (Misenplace)','tempo_misenplace');
		$formulario->fieldset->simples('Tempo (Preparo)','tempo_preparo');
		$formulario->fieldset->simples('Rendimento','rendimento');	
	
		$formulario->fieldset->separador();
	
	
	$formulario->fieldset('Ingredientes');
		$formulario->fieldset->filhos('ingredientes');
		
	$formulario->fieldset('Detalhes do receita');
		$formulario->fieldset->simples('Instruções','instrucoes');
		$formulario->fieldset->simples('Notas','notas');
	
	
	
break;
case "salvar":
	if($admin->id == ''){
		$_POST['idcadastros'] = $usuario->cadastros->id;
		$db->inserir('receitas');
		$inserted_id = $db->inserted_id;
		$db->filhos('receitas',$inserted_id);
	
	}else{
		$db->editar('receitas',$admin->id);
		$db->filhos('receitas',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array('Autor' => "cadastros->nome",'Título' => "titulo",'Misenplace' => "tempo_misenplace",'Preparo' => "tempo_preparo",'Rendimento' => 'rendimento');
	$admin->listagemLink('enviarEmail','<img src="imagens/icons/16x16/yellow_mail_send.png" align="absmiddle">Email','');
	$admin->listagemLink('imprimirPDF','<img src="imagens/icons/16x16/pdf_file.png" align="absmiddle">PDF','');
break;
}
?>
