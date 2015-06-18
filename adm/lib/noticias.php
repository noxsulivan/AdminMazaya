<?
switch($admin->acao){
case "editar":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	$formulario->fieldset('Dados do artigo');
	$formulario->fieldset->simples('Título', 'titulo');
	$formulario->fieldset->simples('Subtítulo', 'subtitulo');
	$formulario->fieldset->separador();
	
	if($db->tabelaExiste('tags'))	$admin->campo_tags();
	
	//$formulario->fieldset->canal($tipos_de_canais=2,$admin->registro->idcanais);
	$formulario->fieldset->simples('Data','data');
	$formulario->fieldset->simples('Fonte', 'fonte');
	$formulario->fieldset->simples('Corpo','texto');
	
	$formulario->fieldset('Imagens');
	$formulario->fieldset->fotos();
	
	
	if($db->campoExiste('idnoticias','arquivos')){
		$formulario->fieldset("Anexo");
		$formulario->fieldset->arquivo();
	}
	
	//$admin->tit_formulario('Video');
	//$admin->campo_arquivo();
	
	//$admin->tit_formulario('Notícias relacionadas');
	//$admin->campo_itens_relacionados(noticias,titulo,$res["idnoticias"]);
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('noticias');
		$_id = $db->inserted_id;
		$db->salvar_fotos('noticias',$_id);
		$db->salvar_arquivos('noticias',$_id);
		if($db->tabelaExiste('tags'))	$db->inserir_tags('noticias',$_id);
		//$db->inserir_itens_relacionados('noticias',$db->inserted_id);
		
	}else{
		$db->editar('noticias',$admin->id);
		$_id = $admin->id;
		$db->salvar_fotos('noticias',$_id);
		$db->salvar_arquivos('noticias',$_id);
		if($db->tabelaExiste('tags'))	$db->inserir_tags('noticias',$_id);
		//$db->inserir_itens_relacionados('noticias',$admin->id);
	}
	
	if(!ereg('localhost',$_SERVER['HTTP_HOST'])){
		postarTwitter("Notícia: ".$_POST['titulo'].' http://energiabalneario.com.br/Noticias/'.$_id);
	}
break;
default:
	$admin->campos_listagem = array('Data' => "data",'Título' => "titulo",'Canal' => "canais->canal");
	
	$sql = "select idnoticias from noticias";
	$admin->listagem($sql);
	$admin->ordenar = "data";
	$admin->extra = "DESC";

break;
}
?>