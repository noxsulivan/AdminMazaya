<?

if($admin->acao == "novo"){
	
		
	$formulario->fieldset('Dados do tabela');
		$formulario->fieldset->simples('Tabela', 'tabela',$res["tabela"]);
	
}elseif($admin->acao == "salvar" and $admin->id == ''){
	$db->inserir('tabelas');
	
}elseif($admin->acao == "salvar" and $admin->id != ''){
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
	$db->editar('tabelas',$admin->id);
	db_tabela_link_inserir('tabelas_has_especialidades','idtabelas',$admin->id,'idespecialidades',$_POST["tabela_link"]);
	
}elseif($admin->acao == "editar" and $admin->id != ''){
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
	$res = $db->fetch("select * from tabelas where idtabelas = '$admin->id'");
	
	
	$formulario->fieldset('Dados do tabela');
		$formulario->fieldset->simples('Tabela', 'tabela',$res["tabela"]);
	
}elseif($admin->acao == deletar and isset($registro)){
	for($i=0;$i<count($registro);$i++){
		$db->query("delete from tabelas where idtabelas = '".$registro[$i]."'");
	}
	
}




if($admin->acao == 'listar'){
	$admin->campos_listagem = array('Tabela' => "tabela");
	
	$sql = "show tables from ".$db->banco;
	
}
?>