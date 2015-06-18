<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	
	$formulario->fieldset('Dados Principais');
		$formulario->fieldset->simples('T�tulo', 'titulo');
		$formulario->fieldset->simples('C�d Refer�ncia', 'referencia');
		$formulario->fieldset->simples('Tipo','idtipos_de_imoveis');
	if($db->tabelaExiste('imoveis_has_categorias'))
			$formulario->fieldset->checkBox('Categorias','categorias');
	else
			$formulario->fieldset->simples('Categoria', 'idcategorias');
		$formulario->fieldset->simples('Exclusivo','exclusivo');
	
	if($db->tabelaExiste('proprietarios')) 	$formulario->fieldset->separador();
	if($db->tabelaExiste('proprietarios')) 	$formulario->fieldset->simples('Propriet�rio', 'idproprietarios');
	if($db->tabelaExiste('edificios')) 	$formulario->fieldset->separador();
	if($db->tabelaExiste('edificios')) 	$formulario->fieldset->simples('Edif�cio', 'idedificios');	
	
		$formulario->fieldset->separador();
		$formulario->fieldset->simples('Total Dorm.', 'quartos');
		$formulario->fieldset->simples('Sendo Demi ', 'demis');
		$formulario->fieldset->simples('Sendo Su�tes', 'suites');
		$formulario->fieldset->simples('Banheiros', 'banheiros');
		$formulario->fieldset->simples('Lavabos', 'lavabos');
		$formulario->fieldset->simples('Garagens', 'garagem');
		$formulario->fieldset->simples('Formato das Garagens', 'garagem_formato');
		$formulario->fieldset->simples('�rea Privativa/Constru�da', 'area_comun');
		$formulario->fieldset->simples('�rea Comun/Terreno', 'area_privativa');
		$formulario->fieldset->simples('Metragem total', 'metragem');
		$formulario->fieldset->separador();
	if($db->tabelaExiste('estagios_de_mobilia')) 	$formulario->fieldset->simples('Mobilia', 'idestagios_de_mobilia');
	//	$formulario->fieldset->simples('Vista para o mar', 'vista_mar');
		$formulario->fieldset->simples('Mobiliado', 'mobiliado');
	
	$formulario->fieldset('Localiza��o');
		$formulario->fieldset->CEP();
		$formulario->fieldset->simples('Endere�o', 'endereco');
		$formulario->fieldset->simples('N�mero','numero');
		$formulario->fieldset->simples('Complemento/Apto', 'complemento');
		$formulario->fieldset->simples('Imedia��es', 'imediacoes');
		$formulario->fieldset->cidadeEstado();
	
	$formulario->fieldset('Descri��o textual');
		$formulario->fieldset->simples('Descri��o', 'caracteristicas');
	
		$formulario->fieldset->separador();
		$formulario->fieldset->simples('Observa��es', 'observacoes');

		
	if($db->tabelaExiste('valores'))$formulario->fieldset('Faixas de valores para conjunto de unidades em edif�cios');
	if($db->tabelaExiste('valores'))	$formulario->fieldset->faixasValores();
	
	
	/*$formulario->fieldset('Temporada');
	
	if($db->tabelaExiste('imoveis_has_datas'))
			$formulario->fieldset->datas('Datas');
	
		$formulario->fieldset->simples('Comiss�o de temporada', 'comissao_temporada');
		$formulario->fieldset->simples('Per�odo m�nimo de reserva', 'periodo_minimo');
		$formulario->fieldset->simples('N�mero m�ximo de inquilinos', 'maximo_inquilinos');
		$formulario->fieldset->simples('Di�ria (baixa temporada)', 'diaria_baixa_temporada');
		$formulario->fieldset->simples('Di�ria em Dezembro', 'diaria_dezembro');
		$formulario->fieldset->simples('Di�ria em Janeiro', 'diaria_janeiro');
		$formulario->fieldset->simples('Di�ria em Fevereiro', 'diaria_fevereiro');
		$formulario->fieldset->simples('Di�ria m�dia temporada', 'diaria_media_temporada');*/
	
	
	$formulario->fieldset('Valores de Venda/Aluguel Anual');
	
		$formulario->fieldset->simples('Moeda', 'moeda');
		$formulario->fieldset->simples('Valor do im�vel', 'valor_imovel');
		$formulario->fieldset->simples('Valor de aluguel', 'valor_aluguel');
		$formulario->fieldset->simples('Valor IPTU', 'valoriptu');
		$formulario->fieldset->simples('Valor do condom�nio', 'valorcondominio');
		$formulario->fieldset->simples('Exibir valores no site?', 'valor_exibir');
		$formulario->fieldset->simples('Condi��es de pagamento', 'condicoespgto');
		$formulario->fieldset->simples('Financiamento', 'financiamento');
	
	
	
	$formulario->fieldset('Caracter�sticas');
		$formulario->fieldset->grupoCheckbox(caracteristicas,opcoes);
	
	$formulario->fieldset('Imagens');
		$formulario->fieldset->simples('Video no Youtube (URL completa)', 'youtube');
		$formulario->fieldset->fotos();
	
	
	$formulario->fieldset('Visualiza��o no site');
		$formulario->fieldset->simples('Ativo', 'ativo');
		$formulario->fieldset->simples('Destaque', 'destaque');
		$formulario->fieldset->simples('Lan�amento', 'lancamento');
	//	$formulario->fieldset->simples('Aparecer na anima��o da capa', 'capa');
	
	
	
	
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
		$twit->Status(htmlentities('Novo im�vel adicionado no site. Confira! '.$_POST['referencia'].' '.$_POST['titulo'].' '.migreme($admin->localhost.'Imoveis/Ver/'.$inserted_id)));
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
		$twit->Status(htmlentities('Im�vel atualizado "'.$_POST['referencia'].' '.$_POST['titulo'].'". '.migreme($admin->localhost.'Imoveis/Ver/'.$admin->id)));
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
		'Refer�ncia' => "referencia",'Status' => "ativo",'Negocia��o' => "tipos_de_operacoes->tipo",'Categoria' => "tipos_de_imoveis->tipo",'T�tulo' => "titulo",
		'Valor do im�vel' => "valor_imovel",'Quartos' => "quartos",'Garagens' => "garagem");
	//$admin->listagem_addCheckbox();
	$sql = "select * from imoveis";

	
break;
}
?>
