<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Presente');
	
	if($db->tabelaExiste('clientes'))	$formulario->fieldset->simples('Cliente','idclientes',$admin->sub_tg);
	
	if($db->campoExiste('idprodutos','presentes')){
			$formulario->fieldset->simples('Produto', 'idprodutos');
			$formulario->fieldset->simples('Quantidade', 'quantidade');
			$formulario->fieldset->simples('Ganhos', 'ganhados');
	}else{
			$formulario->fieldset->simples('Presente', 'presente');
			$formulario->fieldset->simples('Valor', 'valor');
	}
	
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('presentes');
	}else{
		$db->editar('presentes',$admin->id);
	}
break;
default:
	if($db->campoExiste('idprodutos','presentes'))
		$admin->campos_listagem = array('Produtos' => "produtos->produto",'Quantidade' => "quantidade",'Ganhos' => "ganhados");
	else
		$admin->campos_listagem = array('Cliente' => "clientes->nome",'Presente' => "presente",'Valor' => "valor");
	$_cli = new objetoDb("clientes",$admin->sub_id);
	$admin->html .= "<h3>Cliente: ".$_cli->nome_noiva."</h3>";
	$admin->titulo = " de presentes. Noivos: ".$_cli->nome_noiva." ".$_cli->sobrenome_noiva." & ".$_cli->nome_noivo." ".$_cli->sobrenome_noivo;
	
	switch($admin->sub_tg){
		case "noivas":
			$sql = "select * from presentes where idclientes = '".$admin->sub_id."'" ;
			$admin->ordenavel = false;
		break;
		default:
			$admin->botoes_adicionais = array(array('caption'=>'Voltar aos Clientes','tg'=>'clientes','funcao'=>'listagem','imagem'=>'Back.png'));
			$sql = "select * from presentes".( $usuario->clientes->id ? " where idclientes = '".$usuario->clientes->id."'" : ($admin->sub_tg ? " where idclientes = '".$admin->sub_tg."'" : "" )) ;
		break;
	}

	
break;
}
//pre($admin);
?>