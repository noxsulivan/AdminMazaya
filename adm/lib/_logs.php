<?
switch($admin->acao){
case "editar":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	$admin->ini_formulario();
	
	$admin->tit_formulario('Dados do cadastro');
	$admin->campo_simples('operacao', 'operacao');
	$admin->campo_simples('tabela', 'idtabelas');
	$admin->campo_simples('usuario', 'idusuarios');
	$admin->campo_simples('dados', 'dados');
	
	$admin->end_formulario();
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('_logs');
	}else{
		$db->editar('_logs',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array('data_alteracao' => "data_alteracao",'operacao' => "operacao",'tabela'=>'tabela','id'=>'idtabela','usuario'=>'usuarios->nome');
	
	$sql = "select * from _logs";
	
	$admin->listagem($sql);
break;
}
?>
