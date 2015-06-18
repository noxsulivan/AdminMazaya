<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Dados do veículo');
	$formulario->fieldset->simples('Visitante', 'idvisitantes');
	$formulario->fieldset->separador();
	
	
	$formulario->fieldset->simples('Marca','marca');
	$formulario->fieldset->simples('Modelo','modelo');
	$formulario->fieldset->simples('Cor','cor');
	$formulario->fieldset->simples('Placa','placa');
	
	$formulario->fieldset->separador();
	
	$formulario->fieldset->simples('Data de cadastro','data_cadastro');
	$formulario->fieldset->simples('Autorizado','autorizado');

	$formulario->fieldset->separador();
	$formulario->fieldset->simples('Observações','obs');
	
			
	$formulario->fieldset('Imagens');
	$formulario->fieldset->fotos();
break;
case "salvar":
	if($admin->id == ''){
	
		$db->inserir('veiculos_visitantes');
		$_id = $db->inserted_id;
		$db->salvar_fotos('veiculos_visitantes',$_id);
	}else{
		$db->editar('veiculos_visitantes',$admin->id);
		$db->salvar_fotos('veiculos_visitantes',$admin->id);
	
	}
break;
default:
	$admin->campos_listagem = array('Placa' => "placa",'Marca' => "marca",'Modelo' => "modelo",'Visitantes' => "visitantes->nome",'Data de cadastro' => "data_cadastro");
break; 
}
?>