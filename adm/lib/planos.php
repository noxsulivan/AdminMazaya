<?
switch($admin->acao){
case "editar":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	$admin->ini_formulario();
		
	$admin->tit_formulario('Construtora');
	$admin->campo_simples('Nome comercial', 'plano');
	$admin->campo_simples('N�mero', 'numero');
	$admin->campo_simples('Abrang�ncia geogr�fica', 'abrangencia');
	$admin->campo_simples('Segmenta��o assistencial', 'segmentacao');
		
	$admin->end_formulario();
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('planos');
		$db->filhos('planos',$db->inserted_id);
	}else{
		$db->editar('planos',$admin->id);
		$db->filhos('planos',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array('Nome' => "plano");
	//$admin->listagem_addCheckbox();
	$sql = "select * from planos";
	

	$admin->listagem($sql);
break;
}

?>