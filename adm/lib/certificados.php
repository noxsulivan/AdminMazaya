<?
switch($admin->acao){
case "editar":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	$formulario->fieldset('Dados do certificado');
	$formulario->fieldset->simples('Refer�ncia/Curso', 'referencia');
	$formulario->fieldset->simples('Data', 'data');
	$formulario->fieldset->simples('Carga hor�ria', 'carga_horaria');
			$formulario->fieldset->separador();
	$formulario->fieldset->simples('Descri��o', 'descricao');
			$formulario->fieldset->separador();
	$formulario->fieldset->simples('Representante Tecnoglobo', 'representante');
	$formulario->fieldset->simples('Palestrante convidado', 'palestrante');
			$formulario->fieldset->separador();
	$formulario->fieldset->simples('Apoio (Instituto, Representante/ por linha)', 'apoio');
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('certificados');
		$_id = $db->inserted_id;
	}else{
		$db->editar('certificados',$admin->id);
		$_id = $admin->id;
	}
break;
default:
	$admin->campos_listagem = array('Refer�ncia/Curso' => "referencia",'Data' => "data");
	
	$sql = "select idcertificados from certificados";
	$admin->listagem($sql);
	$admin->ordenar = "data";
	$admin->extra = "DESC";

break;
}
?>