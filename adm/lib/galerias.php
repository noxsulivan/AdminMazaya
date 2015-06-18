<?
	if(!$db->tabelaExiste('galerias')){
		$db->query("CREATE TABLE `galerias` (
		  `idgalerias` int(10) unsigned NOT NULL AUTO_INCREMENT,
		  `referencia` varchar(255) DEFAULT NULL,
		  `data_cadastro` date DEFAULT NULL,
		  `ativo` enum('nao','sim') DEFAULT 'nao',
		  PRIMARY KEY (`idgalerias`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;");
	}
	if(!$db->campoExiste('idgalerias','fotos')){
		$db->query("ALTER TABLE `fotos` ADD `idgalerias` INT UNSIGNED NULL DEFAULT NULL AFTER `idfotos` ,ADD INDEX ( `idgalerias` ) ");
		$db->query("ALTER TABLE `fotos` ADD FOREIGN KEY ( `idgalerias` ) REFERENCES `galerias` (`idgalerias`) ON DELETE CASCADE ;");

	}

switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset("Dados do нtem");
	
		$formulario->fieldset->simples('Referкnca', 'referencia');
	if($db->campoExiste('idtipos_de_banners','galerias'))		$formulario->fieldset->simples('Posiзгo', 'idtipos_de_banners',$admin->sub_tg);
		$formulario->fieldset->simples('Ativo', 'ativo');
	
	$formulario->fieldset("Imagem");
		//$admin->explicacao_formulario("A primeira imagem serб exibida na pбgina como miniatura. Se for uma galeria completa de imagens, as demais serгo exibidas quando abrir o zoom. Arraste para alterar a ordem");
			$formulario->fieldset->fotos();
	
	if($db->campoExiste('idgalerias','arquivos')){
		$formulario->fieldset("Video");
			//$admin->explicacao_formulario("Video (.mp4 ou .flv), Animaзгo em flash (.swf)");
			$formulario->fieldset->arquivo();
	}
	
	if($db->campoExiste('link','galerias')){
		$formulario->fieldset("Link para outro site");
				$formulario->fieldset->simples('Link', 'link');
	}
	
	if($db->campoExiste('script','galerias')){
		$formulario->fieldset("Javascript");
				$formulario->fieldset->simples('Script', 'script');
	}
	
	
break;
case "salvar":
	$_POST['idcanais'] = $admin->sub_tg;
	if($admin->id == ''){
		$_POST["data_cadastro"] = ex_data(date("Y-m-d h:i:s"));	
		$db->inserir('galerias');
		$inserted_id = $db->inserted_id;
		$db->salvar_fotos('galerias',$inserted_id);
		$db->salvar_arquivos('galerias',$inserted_id);
	}else{
		$_POST["data_cadastro"] = ex_data(date("Y-m-d h:i:s"));	
		$db->editar('galerias',$admin->id);	
		$db->salvar_fotos('galerias',$admin->id);
		$db->salvar_arquivos('galerias',$admin->id);
	}
break;
default:

		
		
	$admin->campos_listagem = array('Referкnca'=>'referencia','Posiзгo'=>'tipos_de_banners->tipo','Ativo'=>'ativo','Link'=>'link');
	
		if($admin->sub_tg){
			if($db->tabelaExiste('tipos_de_banners')){
				$sql = "select idgalerias from galerias where idtipos_de_banners = '".$admin->sub_tg."'";
			}else{
				$sql = "select idgalerias from galerias where idcanais = '".$admin->sub_tg."'";
			}
		}else{
			$sql = "select idgalerias from galerias";
		}
	
	
break;
}
?>