<?

if($admin->acao == "salvar"){

	$sql = "select tabela from tabelas where idtabelas= '$admin->idtabelas'";
	$db->query($sql);
	$res = $db->fetch();
	$arq = $res["tabela"];
	
	if($_POST["base_atual"]==sim){
		$sql = "truncate table $arq";
		$db->query($sql);
	}
	$sql = "
	LOAD DATA LOCAL INFILE '".str_replace('\\','\\\\',$_FILES["arquivo"]["tmp_name"])."'
	INTO TABLE ".$arq."
	FIELDS
		TERMINATED BY ';' ENCLOSED BY '\"' ESCAPED BY '\\' 
	LINES
		TERMINATED BY '\\n'"; 
		
		
	$sql = ' LOAD DATA LOCAL INFILE \''.str_replace('\\','\\\\',$_FILES["arquivo"]["tmp_name"]).'\''
       . ' INTO TABLE '.$arq.''
       . ' FIELDS'
       . ' TERMINATED BY \';\' ENCLOSED BY \'"\' ESCAPED BY \'\\\\\' '
       . ' LINES'
       . ' TERMINATED BY \'\\n\''; 
	//echo "<pre>$sql</pre>";

	//echo "<pre>";	print_r($_POST);	print_r($_FILES);	echo "</pre>";
	$db->query($sql);
	
	$admin->mensagem("O Backup foi restaurado com sucesso.");
}


	
		$formulario->fieldset->tabela_select('Banco de Dados','idtabelas','idtabelas','tabela','select idtabelas, tabela from tabelas order by tabela',$admin->idtabelas);
		$formulario->fieldset->simples('Arquivo de backup','arquivo','arquivo');
	$admin->explicacao_formulario('Se voc� excluiu inadvertidamente um registro e acredita que ele est� num arquivo de Backup <strong>MARQUE "N�O"</strong> a op��o a seguir. Dessa forma os dados atuais da base de dados n�o ser�o afetados, apenas ser�o acrescidos das informa��es reestabelecidas.<br />Caso deseje restaurar a base completamente, ou seja, <strong>apagando</strong> todas as possivelmente contidas atualmente na base, <strong>MARQUE "SIM"</strong> a op��o a seguir.');
		$formulario->fieldset->simples('Apagar base atual','base_atual','nao');
	
?>