<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	$formulario->fieldset('Dados do parametro');
		$formulario->fieldset->simples('Descritivo', 'visor',$res["visor"]);
		$formulario->fieldset->simples('Parâmetro', 'parametro',$res["parametro"]);
		$formulario->fieldset->simples('Valor', 'valor',$res["valor"]);
			$formulario->fieldset('Imagens');
				$formulario->fieldset->fotos();
	
	
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('configuracoes');
		$inserted_id = $db->inserted_id;
		$db->salvar_fotos('configuracoes',$inserted_id);
	}else{
		$admin->registro = new objetoDb($admin->tabela, $admin->id);
		$db->editar('configuracoes',$admin->id);
		$db->salvar_fotos('configuracoes',$admin->id);
	}
break;
default:
	$sql = "select * from configuracoes";
	$admin->campos_listagem = array('Caption' => "visor",'Parâmetro' => "parametro","Valor"=>"valor");
	
break;
}
?>