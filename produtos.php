<?

if($db->tabelaExiste('linhas'))
	$_idlinha = $admin->sub_tg;
if($db->tabelaExiste('categorias'))
	$cat = new objetoDb('categorias',$admin->sub_tg);
	

switch($admin->acao){
case "editar":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	$admin->ini_formulario();
	
	$categoria = isset($res["idcategorias"]) ? $res["idcategorias"] : $cat;
	
	$admin->tit_formulario('Dados do produto');
	$admin->campo_simples('Código', 'codigo');
	$admin->campo_simples('Produto', 'produto');
	$admin->campo_simples('Ativo', 'ativo');
	if($db->campoExiste('destaque','produtos'))
			$admin->campo_simples('Destaque','destaque');
	if($db->tabelaExiste('destaques'))
			$admin->campo_simples('Destaque', 'iddestaques');
	
	//$admin->separador();
	
	if($db->campoExiste('idmedidas','produtos'))
			$admin->campo_simples('Medido em','idmedidas');

	$admin->campo_simples('Lançamento', 'lancamento');
	$admin->campo_simples('Data de lançamento', 'data_lancamento');

	if($db->tabelaExiste('fabricantes'))
			$admin->campo_simples('Fabricante','idfabricantes');
			
	if($db->tabelaExiste('linhas'))
			$admin->campo_simples('Linha','idlinhas',$_idlinha);
			
			
	if($db->tabelaExiste('produtos_has_categorias'))
		$admin->campo_checkBox('Categorias','categorias');
	elseif($db->campoExiste('idcategorias','produtos'))
		$admin->campo_simples('Categoria','idcategorias',$cat->id);
			
	
	//$admin->separador();
	
	$admin->campo_simples('Peso (em KG)', 'peso');
	if($db->campoExiste('peso_bruto','produtos'))	$admin->campo_simples('Peso Bruto (em KG)', 'peso_bruto');
	if($db->tabelaExiste('produtos_has_voltagens'))
		$admin->campo_CheckboxSimples('Voltagem','voltagens');
		
	if($db->campoExiste('dimensoes','produtos'))	$admin->campo_simples('Dimensões', 'dimensoes');
	
	$admin->separador();
	if($db->campoExiste('largura','produtos'))	$admin->campo_simples('Largura', 'largura');
	if($db->campoExiste('altura','produtos'))	$admin->campo_simples('Altura', 'altura');
	if($db->campoExiste('comprimento','produtos'))	$admin->campo_simples('Comprimento', 'comprimento');
	
	$admin->separador();
	if($db->campoExiste('largura_emb','produtos'))	$admin->campo_simples('Larg Emb', 'largura_emb');
	if($db->campoExiste('altura_emb','produtos'))	$admin->campo_simples('Alt Emb', 'altura_emb');
	if($db->campoExiste('comprimento_emb','produtos'))	$admin->campo_simples('Comp Emb', 'comprimento_emb');
	
	//if($db->tabelaExiste('dimensoes'))				$admin->campo_dinamico('produtos', 'dimensoes');

	
	
	if($db->campoExiste('descricao_curta','produtos'))	$admin->tit_formulario('Descrição');
	if($db->campoExiste('descricao_curta','produtos'))	$admin->campo_simples('Texto curto', 'descricao_curta');
	
	
	
	if($db->campoExiste('preco_venda','produtos'))	$admin->tit_formulario('Dados de venda');
	
	//campo_grupoCheckbox($perguntas,$respostas,$pergunta,$resposta,$has,$admin->idrespondido,$tipo=null,$visibilidade=null);
	
	if($db->campoExiste('preco_custo','produtos'))	$admin->campo_simples('Preço de custo', 'preco_custo');
	if($db->campoExiste('preco_venda','produtos'))	$admin->campo_simples('Preço de venda', 'preco_venda');
	
	if($db->campoExiste('preco_promocional','produtos'))	$admin->campo_simples('Preço promocional', 'preco_promocional');
	if($db->campoExiste('validade_promocao','produtos'))	$admin->campo_simples('Validade da promoção', 'validade_promocao');
	if($db->campoExiste('promocao','produtos'))	$admin->campo_simples('Promoção', 'promocao');
	if($db->campoExiste('frete_gratis','produtos'))	$admin->campo_simples('Frete grátis', 'frete_gratis');
	if($db->campoExiste('prazo_entrega','produtos'))	$admin->campo_simples('Prazo de entrega (em dias acrescidos)', 'prazo_entrega');
	//$admin->separador();
	if($db->campoExiste('quantidade','produtos'))	$admin->campo_simples('Qtd em estoque', 'quantidade');
	
	if($db->campoExiste('desconto','produtos') and $admin->configs['minimo_carrinho_desconto'])
		$admin->campo_simples('Desconto padrão acima R$'.$admin->configs['minimo_carrinho_desconto'].' em (%)', 'desconto');
	
	//if($db->tabelaExiste('produtos_has_tamanhos')){
//		$admin->tit_formulario('Tamanhos');
//		$admin->campo_checkBox('Tamanhos disponíveis','tamanhos');
//	}
	
	
	
	$admin->tit_formulario('Imagens');
	$admin->campo_fotos();
	//$admin->campo_simples('Arquivo', 'imagem',$res["quantidade"]);
	
	if($db->campoExiste('descricao_longa','produtos'))	$admin->tit_formulario('Descrição');
	if($db->campoExiste('descricao_longa','produtos'))	$admin->campo_simples('Descrição', 'descricao_longa');
	
	if($db->campoExiste('ficha_tecnica','produtos'))	$admin->tit_formulario('Ficha Técnica');
	if($db->campoExiste('descricao_longa','produtos'))	$admin->campo_simples('Descrição', 'ficha_tecnica');
	
	//if($db->campoExiste('frete','produtos'))	$admin->campo_simples('Frete via Correios?', 'frete',$res["frete"]);

	if($db->tabelaExiste('produtos_has_opcoes')){
			$admin->tit_formulario('Características');
			$admin->campo_grupoCheckbox(caracteristicas,opcoes);
	}
	
		
	if($db->tabelaExiste('variacoes')){
		$admin->tit_formulario('Estoque');
		$admin->campo_filhos('variacoes');
	}
	
	
	if($db->tabelaExiste('combos')){
		$admin->tit_formulario('Combo');
		$admin->campo_filhos('combos');
	}
	
	if($db->tabelaExiste('medidas')){
		$admin->tit_formulario('Tabela de Medidas');
		$admin->campo_filhos('medidas');
	}
	
	$admin->end_formulario();
break;
case "salvar":
	//unset($_POST['idcategorias']);
	if($admin->id == ''){
		$db->inserir('produtos');
		$inserted_id = $db->inserted_id;
		$db->salvar_fotos('produtos',$inserted_id);
		//if($db->tabelaExiste('dimensoes'))	$db->inserirCampo_dinamico('dimensoes','produtos',$db->inserted_id);
		
		$db->filhos('produtos',$inserted_id);
		
		if($db->tabelaExiste('produtos_has_categorias')){
			$db->tabela_link('produtos','categorias',$inserted_id,$_POST["tabela_link"]['categorias']);
		}
		if($db->tabelaExiste('produtos_has_voltagens')){
			$db->tabela_link('produtos','voltagens',$inserted_id,$_POST["tabela_link"]['voltagens']);
		}
		if($db->tabelaExiste('produtos_has_tamanhos')){
			$db->tabela_link('produtos','tamanhos',$inserted_id,$_POST["tabela_link"]['tamanhos']);
		}
		if($db->tabelaExiste('produtos_has_opcoes')){
			$db->tabela_link('produtos','opcoes',$inserted_id,$_POST["tabela_link"]['opcoes']);
		}
	}else{
		$db->editar('produtos',$admin->id);
		$db->salvar_fotos('produtos',$admin->id);
		//if($db->tabelaExiste('dimensoes'))	$db->editarCampo_dinamico('dimensoes','dimensoes',$admin->id);
		$db->filhos('produtos',$admin->id);
		
		if($db->tabelaExiste('produtos_has_categorias')){
			$db->tabela_link('produtos','categorias',$admin->id,$_POST["tabela_link"]['categorias']);
		}
		if($db->tabelaExiste('produtos_has_voltagens')){
			$db->tabela_link('produtos','voltagens',$admin->id,$_POST["tabela_link"]['voltagens']);
		}
		if($db->tabelaExiste('produtos_has_tamanhos')){
			$db->tabela_link('produtos','tamanhos',$admin->id,$_POST["tabela_link"]['tamanhos']);
		}
		if($db->tabelaExiste('produtos_has_opcoes')){
			$db->tabela_link('produtos','opcoes',$admin->id,$_POST["tabela_link"]['opcoes']);
		}
	
	}
break;
default:



	if($db->campoExiste('peso','produtos')){
		$admin->campos_listagem['Codigo'] = "codigo";
		$admin->campos_listagem['Produto'] = "produto";
		$admin->campos_listagem['Valor'] = "preco_venda";
		if($db->campoExiste("preco_promocional","produtos")) $admin->campos_listagem['Promocional'] = "preco_promocional";
		if($db->campoExiste("preco_combo","produtos")) $admin->campos_listagem['Combo'] = "preco_combo";
		if($db->campoExiste("frete_gratis","produtos")) $admin->campos_listagem['Frete'] = "frete_gratis";
		$admin->campos_listagem['Peso'] = "peso";
		$admin->campos_listagem['Volume'] = "peso_volumetrico";
		$admin->campos_listagem['Dimensões'] = "dimensoes";
		$admin->campos_listagem['Ativo'] = "ativo";
		if($db->campoExiste("destaque","produtos")) $admin->campos_listagem['Destaque'] = "destaque";
		if($db->campoExiste("quantidade","produtos")) $admin->campos_listagem['Estoque'] = "quantidade";
	}elseif($db->tabelaExiste('linhas'))
		$admin->campos_listagem = array('Produto' => "produto", 'Codigo' => 'codigo','Valor'=>'preco_venda','Linha'=>'linhas->linha');
	elseif($db->tabelaExiste('categorias'))
		$admin->campos_listagem = array('Produto' => "produto", 'Codigo' => 'codigo','Categoria'=>'categorias->categoria');
	else
		$admin->campos_listagem = array('Produto' => "produto");

	
	
	
	if($db->tabelaExiste('fabricantes'))	$admin->campos_listagem['Fabricante'] = 'fabricantes->fabricante';
	if($_tmp == 'exportar')	$admin->campos_listagem['Categoria'] = 'categorias->categoria';
	
	if(is_numeric($admin->sub_tg)){
			$sql = "select idprodutos from produtos where idlinhas = '".$admin->sub_tg."'";
	}else{
		switch($admin->sub_tg){
			case "noivas":
				$sql .= 'select produtos.idprodutos from noivas_has_produtos where idnoivas = "'.$admin->acao.'"';
				$admin->sub_tgOpcoes = $sql_tg;
				$admin->ordenavel = false;
			break;
			default:
				$sql = "select idprodutos from produtos";
			break;
		}
	}
	//$admin->ordenar = "produtos.produto";
	
	$admin->listagem($sql);
break;
}
?>