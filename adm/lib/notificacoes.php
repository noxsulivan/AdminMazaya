<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Dados da ocorr�ncia');
	if($usuario->tipos_de_usuarios->id != 4){
				$formulario->fieldset->simples('Usuario', 'idusuarios');
	}
	$formulario->fieldset->simples('T�tulo', 'ocorrencia');
	//$formulario->fieldset->canal($tipos_de_canais=2,$admin->registro->idcanais);
	$formulario->fieldset->simples('Data e hora','data',date("d/m/Y H:i"));
	$formulario->fieldset->simples('Descri��o','descricao');
	
	$formulario->fieldset('Fotos - Anexe se houver imagens dispon�veis');
	$formulario->fieldset->fotos();
	
	//$admin->tit_formulario('Video');
	//$admin->campo_arquivo();
	
	//$admin->tit_formulario('Not�cias relacionadas');
	//$admin->campo_itens_relacionados(noticias,titulo,$res["idnoticias"]);
	
	
break;
case "salvar":
	if($admin->id == ''){
	
		$_POST['idusuarios'] = $usuario->id;
		$db->inserir('notificacoes');
		$_id = $db->inserted_id;
		$db->salvar_fotos('notificacoes',$_id);
		$db->filhos('notificacoes',$inserted_id);
	}else{
		$db->editar('notificacoes',$admin->id);
		$db->salvar_fotos('notificacoes',$admin->id);
		$db->filhos('notificacoes',$admin->id);
		$_id = $admin->id;
	}
	
	$descricao = '<strong>MENSAGEM: </strong>'.$_POST['descricao'].'
	<p><strong>ENVIADO POR:</strong> '.$usuario->nome.'</p>
	<p><img src="http://'.$_SERVER['HTTP_HOST'].'/confirma_notificacoes.php?'.md5($_id).'"></p>';
	
	mailClass("noxsulivan@gmail.com",'Ocorr�ncia GolfVille: '.$_POST['ocorrencia'],$descricao,"admin@recantogolfville.com.br",utf8_encode("Administra��o Recanto GolfVille"));
					
break;
default:
	$admin->campos_listagem = array('Data' => "data",'Ocorr�ncia' => "ocorrencia",'Usu�rio' => "usuarios->nome");
break; 
}
?>