<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
		
	$formulario->fieldset('Dados da categoria');
	if($db->campoExiste('idcategorias_2','categorias'))			$formulario->fieldset->simples('Categoria Pai','idcategorias_2');
	
		$formulario->fieldset->simples('Categoria', 'categoria');
	
	if($db->tabelaExiste('linhas'))			$formulario->fieldset->simples('Linha', 'idlinhas');
	
	if($db->campoExiste('sigla','categorias'))			$formulario->fieldset->simples('Sigla (2 letras)','sigla');
	
	if($db->campoExiste('texto','categorias'))			$formulario->fieldset->simples('Texto', 'texto');
	
	if($db->campoExiste('idcategorias','fotos')){
		$formulario->fieldset('Imagens');
			$formulario->fieldset->fotos();
	}
	
	
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('categorias');
		$inserted_id = $db->inserted_id;
		$db->salvar_fotos('categorias',$inserted_id);
		
	}else{
		$admin->registro = new objetoDb($admin->tabela, $admin->id);
		$db->editar('categorias',$admin->id);
		$db->salvar_fotos('categorias',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array('Ítem' => "iditens",'Fatura' => "data_fatura",'Pedido' => "pedidos->referencia",'Cliente' => "clientes->fantasia",'Produto' => "produtos->grade",'Tons' => "tons",
										'Preço EX' => "preco_ex", 'Status' => "estagio");
			$admin->listagemOperacoes("Comissão",'return number_format(($obj->subtotal * $obj->pedidos->cadastros->comissao)/100,2,",",".");');
	$admin->ordenar = "data_fatura";
	$admin->extra = "DESC";
	
	$admin->listagem($sql);

break;
}
?>