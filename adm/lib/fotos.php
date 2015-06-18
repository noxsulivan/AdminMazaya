<?
switch($admin->acao){
case "editar":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	$admin->ini_formulario();
		
	$admin->tit_formulario('Foto');
	
	foreach($db->tabelas['fotos']['Indexes'] as $k=>$v)
	$admin->campo_simples($k, $v);
	
	$admin->separador();
	
	
	$admin->campo_simples('Legenda', 'legenda');
	$admin->campo_simples('Nome arquivo', 'nome_arquivo');
	$admin->campo_simples('Legenda', 'filetype');
	$admin->campo_simples('Tamanho', 'size');
	$admin->campo_simples('Largura', 'width');
	$admin->campo_simples('Altura', 'height');
	
	$admin->campo_fotos();
	
	$admin->end_formulario();
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('fotos');
		
	}else{
		$db->editar('fotos',$admin->id);
	}
break;
default:
	
	$admin->campos_listagem = array('Legenda'=>'legenda','Nome arquivo'=>'nome_arquivo','Tipo'=>'filetype','Tamanho'=>'size','Largura'=>'width','Altura'=>'height');
	$sql = "select idfotos from fotos";
	$admin->listagem($sql,fotos);

break;
}
?>