<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
		
	$formulario->fieldset('Dados da categoria');
	if($db->campoExiste('idcategorias_2','categorias'))			$formulario->fieldset->simples('Categoria Anterior','idcategorias_2');
	
	$formulario->fieldset->simples('Categoria', 'categoria');
	$formulario->fieldset->simples('Grupamento/Menu', 'idhipercategorias');
	$formulario->fieldset->simples('Desconto à vista', 'desconto_vista');
	
	if($db->tabelaExiste('linhas'))			$formulario->fieldset->simples('Linha', 'idlinhas');
	
	if($db->campoExiste('sigla','categorias'))			$formulario->fieldset->simples('Sigla (2 letras)','sigla');
	
	if($db->campoExiste('texto','categorias'))			$formulario->fieldset->simples('Texto', 'texto');
	if($db->campoExiste('texto','categorias'))			$formulario->fieldset->simples('Texto auxiliar', 'descricao');
	
	if($db->campoExiste('idcategorias','fotos')){
		$formulario->fieldset('Imagens');
			$formulario->fieldset->fotos();
	}
	if($usuario->tipos_de_usuarios->id < 2) {
		$formulario->fieldset('Associações');
		if($db->tabelaExiste('categorias_has_associacoes'))
				$formulario->fieldset->checkBox('Associações disponíveis','associacoes');
	}
	
	
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('categorias');
		$inserted_id = $db->inserted_id;
		$db->salvar_fotos('categorias',$inserted_id);
		if($db->tabelaExiste('categorias_has_associacoes')){
			$db->tabela_link('categorias','associacoes',$inserted_id,$_POST['associacoes']);
		}
		
	}else{
		$admin->registro = new objetoDb($admin->tabela, $admin->id);
		$db->editar('categorias',$admin->id);
		$db->salvar_fotos('categorias',$admin->id);
		if($db->tabelaExiste('categorias_has_associacoes')){
			$db->tabela_link('categorias','associacoes',$admin->id,$_POST['associacoes']);
		}
	}
break;
default:
	$admin->campos_listagem = array('Grupamento' => "hipercategorias->categoria",'Categoria principal' => "categorias->categoria",'Categoria' => "categoria",'Desconto' => "desconto_vista",'URL' => "url");
	
	$sql = "select idcategorias, categoria from categorias where idcategorias > 0";
			$admin->listagemLink('listagem','Produtos','produtos@categorias@');
	$admin->listagem($sql,categorias);

break;
}
?>