<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Dados do eventos_convidados');
				$formulario->fieldset->simples('Condômino', 'idcadastros');
				
				
	$formulario->fieldset->separador();
	
	
	//$formulario->fieldset->canal($tipos_de_canais=2,$admin->registro->idcanais);
	$formulario->fieldset->simples('Nome completo','nome');
	$formulario->fieldset->simples('Acompanhantes','acompanhantes');
	$formulario->fieldset->simples('RG','rg');
	$formulario->fieldset->simples('CPF','cpf');
	
	$formulario->fieldset->separador();
	
	$formulario->fieldset->simples('Telefone','telefone');
	$formulario->fieldset->simples('Celular','celular');

	$formulario->fieldset->separador();
	$formulario->fieldset->simples('Placa do carro','placa');
	  $formulario->fieldset->simples('Autorizado', 'autorizado');
	  $formulario->fieldset->simples('Esteve presente', 'confirmado');
	
			
break;
case "salvar":
	if($admin->id == ''){
	
		$db->inserir('eventos_convidados');
		$_id = $db->inserted_id;
		$db->salvar_fotos('eventos_convidados',$_id);
	}else{
		$db->editar('eventos_convidados',$admin->id);
		$db->salvar_fotos('eventos_convidados',$admin->id);
	
	}
break;
default:
	$admin->campos_listagem = array('Nome' => "nome",'Evento' => "eventos->evento",'Condômino' => "cadastros->nome",'RG' => "rg",	'CPF' => "rg", 'Telefone' => "rg", 'Celular' => "celular", 'Placa' => "rg", 'Autorizado' => "autorizado", 'Confirmado' => "confirmado");
break; 
}
?>