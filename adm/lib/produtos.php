<?


switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	$formulario->fieldset('Dados do produto');
		$formulario->fieldset->simples('Código', 'codigo');
		$formulario->fieldset->simples('Produto', 'produto');
		$formulario->fieldset->simples('Categoria','idcategorias');
		$formulario->fieldset->simples('Linha','idlinhas');
		$formulario->fieldset->simples('Apenas Televenda', 'apenas_televenda');
		$formulario->fieldset->simples('Destaque na Home', 'destaque');
		$formulario->fieldset->simples('Origem', 'idfornecedores');
		$formulario->fieldset->simples('Material', 'idmateriais');
		$formulario->fieldset->simples('Processo', 'idprocessos');
		$formulario->fieldset->simples('Grade', 'grade');
		$formulario->fieldset->simples('Ativo', 'ativo');
		$formulario->fieldset->simples('Densidade', 'densidade');
		$formulario->fieldset->simples('Fluidez', 'fluidez');
		$formulario->fieldset->simples('Estoque Disponível', 'estoque');
		$formulario->fieldset->simples('Estoque Futuro', 'futuro');
		$formulario->fieldset->simples('Previsão de entrada', 'previsao');
		$formulario->fieldset->simples('Antibloqueio', 'antibloqueio');
		$formulario->fieldset->simples('Deslizante', 'deslizante');
		$formulario->fieldset->simples('Outros aditivos', 'aditivos');
		
		$formulario->fieldset->simples('Fabricante','idfabricantes');
		$formulario->fieldset->simples('Peso (em KG)', 'peso');
		$formulario->fieldset->simples('Peso Bruto (em KG)', 'peso_bruto');
		$formulario->fieldset->simples('Voltagem','voltagens');
		
		$formulario->fieldset->simples('Dimensões', 'dimensoes');
		$formulario->fieldset->simples('Capacidade', 'capacidade');
		$formulario->fieldset->simples('Largura', 'largura');
		$formulario->fieldset->simples('Altura', 'altura');
		$formulario->fieldset->simples('Comprimento', 'comprimento');
	
	
	
	
	if($db->campoExiste('preco_venda','produtos')){
		$formulario->fieldset('Dados de venda');
		$formulario->fieldset->simples('Preço de custo', 'preco_custo');
		$formulario->fieldset->simples('Preço de venda', 'preco_venda');
		$formulario->fieldset->simples('Preço promocional', 'preco_promocional');
		$formulario->fieldset->simples('Validade da promoção', 'validade_promocao');
		$formulario->fieldset->simples('Promoção', 'promocao');
		$formulario->fieldset->simples('Frete grátis', 'frete_gratis');
		$formulario->fieldset->simples('Qtd em estoque', 'quantidade');
		$formulario->fieldset->simples('Prazo de entrega (em dias acrescidos)', 'prazo_entrega');
		$formulario->fieldset->simples('Desconto À VISTA em (%)', 'desconto');
		
		$formulario->fieldset('Brinde');
		$formulario->fieldset->autocompleteInput("Brinde",'brindes','brindes');
	}
		
	
	
	if($db->campoExiste('video','produtos')){
		$formulario->fieldset('Video');
		$formulario->fieldset->simples('Video', 'video');
	}

	if($db->campoExiste('idprodutos','fotos')){
		$formulario->fieldset('Imagens');
			$formulario->fieldset->fotos();
	}
	
		
	if($db->tabelaExiste('variacoes')){
		$formulario->fieldset('Variações de Cor');
			$formulario->fieldset->filhos('variacoes');
	}
		
		
//		if($db->tabelaExiste('combos')){
//			$formulario->fieldset('Combo',true);
//				$formulario->fieldset->filhos('combos');
//		}


//		if($db->tabelaExiste('contratipos')){
//			$formulario->fieldset('Contratipos',true);
//				$formulario->fieldset->filhos('contratipos');
//		}
		
		//if($db->tabelaExiste('aplicacoes')){
			//$formulario->fieldset('Aplicações',true);
				//$formulario->fieldset->filhos('produtos_has_aplicacoes');
		//}
		
	if($db->campoExiste('idprodutos','arquivos')){
		$formulario->fieldset("Arquivo");
			$formulario->fieldset->arquivo();
	}

	
	$formulario->fieldset('Textos em português');
		$formulario->fieldset->simples('Características', 'descricao_longa');
		$formulario->fieldset->simples('Especificação', 'descricao_curta');
		$formulario->fieldset->simples('Ficha Técnica', 'ficha_tecnica');
	
			
	if($usuario->tipos_de_usuarios->id < 2 and $db->tabelaExiste('produtos_has_hotsites')) {
		$formulario->fieldset('Hotsites');
		$formulario->fieldset->checkBox('Hotsites','hotsites');
	}
	if($usuario->tipos_de_usuarios->id < 2 and $db->tabelaExiste('produtos_has_associacoes')) {
		$formulario->fieldset('Associações');
		$formulario->fieldset->checkBox('Associações','associacoes');
	}
	if($usuario->tipos_de_usuarios->id < 2 and $db->tabelaExiste('produtos_has_categorias')) {
		$formulario->fieldset('Categorias');
		$formulario->fieldset->checkBox('Categorias','categorias');
	}
	
	
	
break;
case "salvar":

	//file_put_contents(diretorio($_POST['codigo']."-".time()).".txt",print_r($_POST,true));
	//$_POST['url'] = diretorio(trim($_POST['codigo'].' '.$_POST['produto']));
	//unset($_POST['idcategorias']);
	if($admin->id == ''){
		$db->inserir('produtos');
		$inserted_id = $db->inserted_id;
		$db->salvar_fotos('produtos',$inserted_id);
		$db->salvar_arquivos('produtos',$inserted_id);
		//if($db->tabelaExiste('dimensoes'))	$db->inserirCampo_dinamico('dimensoes','produtos',$db->inserted_id);
		
		$db->filhos('produtos',$inserted_id);
		
		if($db->tabelaExiste('produtos_has_categorias')){
			$db->tabela_link('produtos','categorias',$inserted_id,$_POST['categorias']);
		}
		if($db->tabelaExiste('produtos_has_associacoes')){
			$db->tabela_link('produtos','associacoes',$inserted_id,$_POST['associacoes']);
		}
		if($db->tabelaExiste('produtos_has_hotsites')){
			$db->tabela_link('produtos','hotsites',$inserted_id,$_POST['hotsites']);
		}
		if($db->tabelaExiste('produtos_has_metodos')){
			$db->tabela_link('produtos','metodos',$inserted_id,$_POST["tabela_link"]['metodos']);
		}
		if($db->tabelaExiste('produtos_has_voltagens')){
			$db->tabela_link('produtos','voltagens',$inserted_id,$_POST["tabela_link"]['voltagens']);
		}
		if($db->tabelaExiste('produtos_has_tamanhos')){
			$db->tabela_link('produtos','tamanhos',$inserted_id,$_POST["tabela_link"]['tamanhos']);
		}
		if($db->tabelaExiste('produtos_has_opcoes')){
			//$db->tabela_link('produtos','opcoes',$inserted_id,$_POST["tabela_link"]['opcoes']);
		}
	}else{
		$db->editar('produtos',$admin->id);
		$db->salvar_fotos('produtos',$admin->id);
		$db->salvar_arquivos('produtos',$admin->id);
		//if($db->tabelaExiste('dimensoes'))	$db->editarCampo_dinamico('dimensoes','dimensoes',$admin->id);
		$db->filhos('produtos',$admin->id);
		
		if($db->tabelaExiste('produtos_has_categorias')){
			$db->tabela_link('produtos','categorias',$admin->id,$_POST['categorias']);
		}
		if($db->tabelaExiste('produtos_has_associacoes')){
			$db->tabela_link('produtos','associacoes',$admin->id,$_POST['associacoes']);
		}
		if($db->tabelaExiste('produtos_has_hotsites')){
			$db->tabela_link('produtos','hotsites',$admin->id,$_POST['hotsites']);
		}
		if($db->tabelaExiste('produtos_has_metodos')){
			$db->tabela_link('produtos','metodos',$admin->id,$_POST["tabela_link"]['metodos']);
		}
		if($db->tabelaExiste('produtos_has_voltagens')){
			$db->tabela_link('produtos','voltagens',$admin->id,$_POST["tabela_link"]['voltagens']);
		}
		if($db->tabelaExiste('produtos_has_tamanhos')){
			$db->tabela_link('produtos','tamanhos',$admin->id,$_POST["tabela_link"]['tamanhos']);
		}
		if($db->tabelaExiste('produtos_has_opcoes')){
			//$db->tabela_link('produtos','opcoes',$admin->id,$_POST["tabela_link"]['opcoes']);
		}
	
	}
break;
default:



	if($db->campoExiste('peso','produtos') ){
		$admin->campos_listagem['Produto'] = "produto";
		$admin->campos_listagem['Codigo'] = "codigo";
		$admin->campos_listagem['Desconto'] = "desconto";
		if($db->campoExiste("preco_venda","produtos")) $admin->campos_listagem['Valor'] = "preco_venda";
		if($db->campoExiste("preco_promocional","produtos")) $admin->campos_listagem['Promocional'] = "preco_promocional";
		if($db->campoExiste("frete_gratis","produtos")) $admin->campos_listagem['Frete'] = "frete_gratis";
		//$admin->campos_listagem['Peso'] = "peso";
		if($db->campoExiste("peso_volumetrico","produtos")) $admin->campos_listagem['Volume'] = "peso_volumetrico";
		if($db->campoExiste("idlinhas","produtos")) $admin->campos_listagem['Linha'] = "linhas->linha";
		if($db->campoExiste("idcategorias","produtos")) $admin->campos_listagem['Categoria'] = "categorias->categoria";
		//$admin->campos_listagem['Dimensões'] = "dimensoes";
		$admin->campos_listagem['Ativo'] = "ativo";
		$admin->campos_listagem['Televendas'] = "apenas_televenda";
		//if($db->campoExiste("destaque","produtos")) $admin->campos_listagem['Destaque'] = "destaque";
		//if($db->campoExiste("quantidade","produtos")) $admin->campos_listagem['Estoque'] = "quantidade";
	}elseif($db->tabelaExiste('linhas')){
		$admin->campos_listagem = array('Produto' => "produto", 'Codigo' => 'codigo','Valor'=>'preco_venda','Linha'=>'linhas->linha');
	}elseif($db->tabelaExiste('fornecedores')){
		$admin->campos_listagem['Material'] = "materiais->material";
		$admin->campos_listagem['Processo'] = "processos->processo";
		$admin->campos_listagem['Grade'] = "grade";
		$admin->campos_listagem['Petroquímica'] = "fornecedores->nome";
		//$admin->campos_listagem['Origem'] = "fornecedores->origem";
		if($usuario->tipos_de_usuarios->id <= 1)$admin->campos_listagem['Densidade'] = "densidade";
		if($usuario->tipos_de_usuarios->id <= 1)$admin->campos_listagem['Fluidez'] = "fluidez";
		$admin->campos_listagem['Disponível (kg)'] = "estoque";
		$admin->campos_listagem['Futuro (kg)'] = "futuro";
		$admin->campos_listagem['Previsão'] = "previsao";
		$admin->campos_listagem['Status'] = "ativo";
		//$admin->campos_listagem['Previsão'] = "previsao";
		//'Antibloqueio'=>'antibloqueio','Deslizante'=>'deslizante',
	}elseif($db->tabelaExiste('categorias')){
		$admin->campos_listagem = array('Produto' => "produto", 'Codigo' => 'codigo','Categoria'=>'categorias->categoria');
	}else{
		$admin->campos_listagem = array('Produto' => "produto");
	}
	
	if($db->tabelaExiste('fabricantes'))	$admin->campos_listagem['Fabricante'] = 'fabricantes->fabricante';
	if($_tmp == 'exportar')	$admin->campos_listagem['Categoria'] = 'categorias->categoria';
	
	
	$admin->campos_aditivos = array('Clientes interessados' => "clientes_interessados");
	
	//if(preg_match("/realplastic/",$_SERVER['DOCUMENT_ROOT'])){
		//$admin->ordenar = "idmateriais";
		//$admin->extra = "asc";
	//}
	//$admin->listagemLink('link','Ficha','presentes@');
	//$admin->ordenar = "produtos.produto";
	
	
break;
}
?>