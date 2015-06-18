<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	
	$formulario->fieldset('Dados Principais');
		$formulario->fieldset->simples('Título', 'titulo');
		$formulario->fieldset->simples('Cód Referência', 'referencia');
		$formulario->fieldset->simples('Tipo','idtipos_de_imoveis');
	if($db->tabelaExiste('imoveis_has_categorias'))
			$formulario->fieldset->checkBox('Categorias','categorias');
	else
			$formulario->fieldset->simples('Categoria', 'idcategorias');
		$formulario->fieldset->simples('Exclusivo','exclusivo');
	
	if($db->tabelaExiste('proprietarios')) 	$formulario->fieldset->separador();
	if($db->tabelaExiste('proprietarios')) 	$formulario->fieldset->simples('Proprietário', 'idproprietarios');
	if($db->tabelaExiste('edificios')) 	$formulario->fieldset->separador();
	if($db->tabelaExiste('edificios')) 	$formulario->fieldset->simples('Edifício', 'idedificios');	
	
		$formulario->fieldset->separador();
		$formulario->fieldset->simples('Total Dorm.', 'quartos');
		$formulario->fieldset->simples('Sendo Demi ', 'demis');
		$formulario->fieldset->simples('Sendo Suítes', 'suites');
		$formulario->fieldset->simples('Banheiros', 'banheiros');
		$formulario->fieldset->simples('Lavabos', 'lavabos');
		$formulario->fieldset->simples('Garagens', 'garagem');
		$formulario->fieldset->simples('Formato das Garagens', 'garagem_formato');
		$formulario->fieldset->simples('Área Privativa/Construída', 'area_comun');
		$formulario->fieldset->simples('Área Comun/Terreno', 'area_privativa');
		$formulario->fieldset->simples('Metragem total', 'metragem');
		$formulario->fieldset->separador();
	if($db->tabelaExiste('estagios_de_mobilia')) 	$formulario->fieldset->simples('Mobilia', 'idestagios_de_mobilia');
	//	$formulario->fieldset->simples('Vista para o mar', 'vista_mar');
		$formulario->fieldset->simples('Mobiliado', 'mobiliado');
	
	$formulario->fieldset('Localização');
		$formulario->fieldset->CEP();
		$formulario->fieldset->simples('Endereço', 'endereco');
		$formulario->fieldset->simples('Número','numero');
		$formulario->fieldset->simples('Complemento/Apto', 'complemento');
		$formulario->fieldset->simples('Imediações', 'imediacoes');
		$formulario->fieldset->cidadeEstado();
	
	$formulario->fieldset('Descrição textual');
		$formulario->fieldset->simples('Descrição', 'caracteristicas');
	
		$formulario->fieldset->separador();
		$formulario->fieldset->simples('Observações', 'observacoes');

		
	if($db->tabelaExiste('valores'))$formulario->fieldset('Faixas de valores para conjunto de unidades em edifícios');
	if($db->tabelaExiste('valores'))	$formulario->fieldset->faixasValores();
	
	
	/*$formulario->fieldset('Temporada');
	
	if($db->tabelaExiste('imoveis_has_datas'))
			$formulario->fieldset->datas('Datas');
	
		$formulario->fieldset->simples('Comissão de temporada', 'comissao_temporada');
		$formulario->fieldset->simples('Período mínimo de reserva', 'periodo_minimo');
		$formulario->fieldset->simples('Número máximo de inquilinos', 'maximo_inquilinos');
		$formulario->fieldset->simples('Diária (baixa temporada)', 'diaria_baixa_temporada');
		$formulario->fieldset->simples('Diária em Dezembro', 'diaria_dezembro');
		$formulario->fieldset->simples('Diária em Janeiro', 'diaria_janeiro');
		$formulario->fieldset->simples('Diária em Fevereiro', 'diaria_fevereiro');
		$formulario->fieldset->simples('Diária média temporada', 'diaria_media_temporada');*/
	
	
	$formulario->fieldset('Valores de Venda/Aluguel Anual');
	
		$formulario->fieldset->simples('Moeda', 'moeda');
		$formulario->fieldset->simples('Valor do imóvel', 'valor_imovel');
		$formulario->fieldset->simples('Valor de aluguel', 'valor_aluguel');
		$formulario->fieldset->simples('Valor IPTU', 'valoriptu');
		$formulario->fieldset->simples('Valor do condomínio', 'valorcondominio');
		$formulario->fieldset->simples('Exibir valores no site?', 'valor_exibir');
		$formulario->fieldset->simples('Condições de pagamento', 'condicoespgto');
		$formulario->fieldset->simples('Financiamento', 'financiamento');
	
	
	
	$formulario->fieldset('Características');
		$formulario->fieldset->grupoCheckbox(caracteristicas,opcoes);
	
	$formulario->fieldset('Imagens');
		$formulario->fieldset->simples('Video no Youtube (URL completa)', 'youtube');
		$formulario->fieldset->fotos();
	
	
	$formulario->fieldset('Visualização no site');
		$formulario->fieldset->simples('Ativo', 'ativo');
		$formulario->fieldset->simples('Destaque', 'destaque');
		$formulario->fieldset->simples('Lançamento', 'lancamento');
	//	$formulario->fieldset->simples('Aparecer na animação da capa', 'capa');
	
	
	
	
break;
case "salvar":
	
	if($admin->id == ''){
		$db->inserir('imoveis');
		$inserted_id = $db->inserted_id;
		
		$db->salvar_fotos('imoveis',$inserted_id);
		$db->tabela_link('imoveis','opcoes',$inserted_id,$_POST["per"]);
		$db->tabela_link('imoveis','datas',$inserted_id,$_POST["tabela_data"]);
		
		if($db->tabelaExiste('imoveis_has_categorias')){
			$db->tabela_link('imoveis','categorias',$inserted_id,$_POST["tabela_link"]);
		}
		if($db->tabelaExiste('valores')){
			foreach($_REQUEST['faixasValores'] as $faixa){
				$_POST['idimoveis'] = $inserted_id;
				$_POST['registro'] = serialize($faixa['registro']);
				$_POST['valor'] = $faixa['valor'];
				$db->inserir('valores');
			}
		}
		$twit = new twitter;
		$twit->Username = 'getulioimoveis';
		$twit->Password = '170402';
		$twit->Status(htmlentities('Novo imóvel adicionado no site. Confira! '.$_POST['referencia'].' '.$_POST['titulo'].' '.migreme($admin->localhost.'Imoveis/Ver/'.$inserted_id)));
	}else{
		$db->editar('imoveis',$admin->id);
		$db->salvar_fotos('imoveis',$admin->id);
		$db->tabela_link('imoveis','opcoes',$admin->id,$_POST["per"]);
		$db->tabela_link('imoveis','datas',$admin->id,$_POST["tabela_data"]);
		
		if($db->tabelaExiste('imoveis_has_categorias')){
			$db->tabela_link('imoveis','categorias',$admin->id,$_POST["tabela_link"]);
		}
		if($db->tabelaExiste('valores')){
			$db->query('delete from valores where idimoveis = "'.$admin->id.'"');
			foreach($_REQUEST['faixasValores'] as $faixa){
				$_POST['idimoveis'] = $admin->id;
				$_POST['registro'] = serialize($faixa['registro']);
				$_POST['valor'] = $faixa['valor'];
				$db->inserir('valores');
			}
		}
		$twit = new twitter;
		$twit->Username = 'getulioimoveis';
		$twit->Password = '170402';
		$twit->Status(htmlentities('Imóvel atualizado "'.$_POST['referencia'].' '.$_POST['titulo'].'". '.migreme($admin->localhost.'Imoveis/Ver/'.$admin->id)));
	}
	/*
	unset($_POST);
	$imo = new objetoDb('imoveis',$inserted_id.$admin->id);
	$_POST['referencia'] = $imo->categorias->sigla.$imo->tipos_de_imoveis->sigla.$inserted_id.$admin->id;
	$db->editar('imoveis',$inserted_id.$admin->id);
	*/
	
break;
default:
	$admin->campos_listagem = array(
		'Referência' => "referencia",'Status' => "ativo",'Negociação' => "tipos_de_operacoes->tipo",'Categoria' => "tipos_de_imoveis->tipo",'Título' => "titulo",
		'Valor do imóvel' => "valor_imovel",'Quartos' => "quartos",'Garagens' => "garagem");
	//$admin->listagem_addCheckbox();
	$sql = "select * from imoveis";

	
break;
}
?>
