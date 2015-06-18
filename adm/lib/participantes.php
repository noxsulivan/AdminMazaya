<?
switch($admin->acao){
case "editar":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	$formulario->fieldset('Dados do participante');
	$formulario->fieldset->simples('Certificado concedido', 'idcertificados');
	$formulario->fieldset->simples('Nome completo', 'nome_completo');
	$formulario->fieldset->simples('Email', 'email');
	$formulario->fieldset->simples('CPF', 'cpf');
	$formulario->fieldset->simples('instituiηγo', 'instituicao');
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('participantes');
		$_id = $db->inserted_id;
	}else{
		$db->editar('participantes',$admin->id);
		$_id = $admin->id;
	}
break;
default:
	$admin->campos_listagem = array('Nome' => "nome_completo",'Email' => "email",'CPF' => "cpf");
	
	$sql = "select idparticipantes from participantes";
	$admin->listagem($sql);
	$admin->ordenar = "data";
	$admin->extra = "DESC";

break;
}
?>