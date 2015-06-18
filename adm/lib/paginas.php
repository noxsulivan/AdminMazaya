<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	$formulario->fieldset('Dados do artigo');
	$formulario->fieldset->simples('Título', 'titulo');
	$formulario->fieldset->simples('Subtítulo', 'subtitulo');
	$formulario->fieldset->separador();
	
		
	if(!preg_match('/inicial/i',$admin->registro->titulo)){
		$formulario->fieldset('Dados da página');
			$formulario->fieldset->simples('Título', 'titulo');
	}
	
	
	if(!$usuario->clientes->id){
		$formulario->fieldset('Atributos');
			$formulario->fieldset->simples('Cliente', 'idclientes');
			$formulario->fieldset->simples('Visível ao cliente', 'visivel_cliente');
			//$formulario->fieldset->tipo("paginas");
	}
	
	
	if($admin->registro->tipos_de_paginas->id != 6){
		$formulario->fieldset('Texto');
			$formulario->fieldset->simples('Corpo da página','texto');
	
		if((!$usuario->clientes->planos->id || $usuario->clientes->planos->id == 2) && $admin->registro->tipos_de_paginas->id != 4){
			$formulario->fieldset('Video');
				$formulario->fieldset->simples('Video do Youtube','video');
		}
	}
	
	if((!$usuario->clientes->id || $usuario->clientes->planos->id == 2) and preg_match('/inicial/i',$admin->registro->titulo)){
		$formulario->fieldset('Galeria');
			$formulario->fieldset->simples('Galeria de imagens em movimento', 'galeria_animada');
	}
	
	if($admin->registro->tipos_de_paginas->id != 4){
		$formulario->fieldset('Imagens');
			$formulario->fieldset->fotos();
	}
	
	if($admin->registro->tipos_de_paginas->id == 4){
		$formulario->fieldset('Endereço');
			$formulario->fieldset->simples('<b>Ex: Rua do Ouvidor, 150, Centro, Rio de Janeiro/RJ</b><br>Endereço para exibição do mapa automaticamente.<br><strong>Importante! Confirme no <a href="http://maps.google.com.br" target="_blank">http://maps.google.com.br</a> se o resultado está correto</strong>', 'endereco');
	}
	
	
	
	
break;
case "salvar":
	$cliente = new objetoDb('clientes',$usuario->clientes->id ? $usuario->clientes->id : $_POST['idclientes']);
	$url_cliente =	diretorio($cliente->nome);
	$url_titulo =	($_POST['titulo'] and !preg_match('inicial',$_POST['titulo'])) ? "_".diretorio(normaliza($_POST['titulo'])) : '';
	
	$_POST['url'] = $url_cliente.$url_titulo;

	if($admin->id == ''){
	
		$db->inserir('paginas');
		$_id = $db->inserted_id;
		$db->inserir_atributos('paginas',$_id);
		unset($_POST['idclientes']);
		$db->salvar_fotos('paginas',$_id);
	
	}else{
		$db->editar('paginas',$admin->id);
		$db->inserir_atributos('paginas',$admin->id);
		unset($_POST['idclientes']);
		$db->salvar_fotos('paginas',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array('Titulo' => "titulo","Tipo" => 'tipos_de_paginas->tipo',"Cliente" => 'clientes->nome');
	
	$sql = "select idpaginas from paginas";
	
break;
}
?>