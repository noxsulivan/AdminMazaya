<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	$formulario->fieldset('Dados do menu');
		$formulario->fieldset->simples('Menu', 'menu');
		$formulario->fieldset->simples('Submenu de','idmenus_2');
		$formulario->fieldset->simples('Fun��o','idtipos_de_menus');

		$formulario->fieldset->simples('Target', 'url');
		
		$formulario->fieldset('Imagens');
			$formulario->fieldset->fotos();
	
	//$formulario->fieldset("Fun��es permitidas");
	//	$formulario->fieldset->check('Pessoas permitidas','usuarios','menus','nome',$res["idmenus"]);
	
	//$formulario->fieldset('�cone');
	//$admin->icone_menu();
	
	
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('menus');
		$inserted_id = $db->inserted_id;
		$db->salvar_fotos('menus',$inserted_id);
	}else{
		$db->editar('menus',$admin->id);
		$db->salvar_fotos('menus',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array('Menu' => "menu",'Submenu de' => "menus->menu",'Target' => "url",'Fun��o' => "tipos_de_menus->tipo");
	
break;
}

?>