<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset("Dados do arquivo");
	
	if($admin->sub_tg != 'retorno'){
			$formulario->fieldset->simples('Titulo', 'arquivo',$res["arquivo"]);
		
			$formulario->fieldset->simples('Data de abertura', 'data_abertura',$res["data_abertura"]);
			$formulario->fieldset->simples('data de fechamento', 'data_fechamento',$res["data_fechamento"]);
		
		if($db->campoExiste('idcadastros','arquivos'))		$formulario->fieldset->simples('Cadastro', 'idcadastros');
		if($db->campoExiste('idorgaos','arquivos'))		$formulario->fieldset->simples('Órgão', 'idorgaos');
		if($db->campoExiste('idcategorias','arquivos'))		$formulario->fieldset->simples('Categoria', 'idcategorias');
		//	$formulario->fieldset->simples('Upload?', 'sentido',$res["sentido"]);
		
		//if($db->tabelaExiste('tags'))	$formulario->fieldset("Palavras-chaves");
		//if($db->tabelaExiste('tags'))		$formulario->fieldset->tags();
		
		$formulario->fieldset("Descrição");
			$formulario->fieldset->simples('Descrição/Observações', 'descricao',$res["descricao"]);
	}
		$formulario->fieldset->arquivo();
	
	
break;
case "salvar":
		$arquivo = new objetoDb('arquivos',$arquivos[0]);
		$_POST["data"] = ex_data(date("Y-m-d h:i:s"));	
		$_POST["sentido"] = 'sim';	
		$_POST["cobranca"] = 'sim';	
		//$_POST["status"] = 'sim';	
		$_POST["arquivo"] = $arquivo->nome_arquivo;	
		$db->editar('arquivos',$arquivos[0]);	
		
		unset($_POST);
	if($admin->sub_tg == 'retorno'){
		
		$_POST['idarquivos'] = $arquivos[0];
		$_POST['situacao'] = 'Pago';
		$_POST['idtipos_de_situacao'] = 2;
		
		$handle = fopen($arquivo->path,"r");
		while (!feof($handle)) {
		  $linha = fgets($handle);
		  if(strlen($linha) < 1) break;
		  
				list($ag,$cont,$cart,$convenio,$numero,$lixo,$lixo,$lixo,$data,$lixo,$valor_pago,$valo_recebido) = explode(';',$linha);
				$_id = intval(substr(str_replace("1724305","",$numero),0,10));//00000129166
				$_POST['data_processamento'] = substr($l[8],0,2).'/'.substr($l[8],2,2).'/'.substr($l[8],4,4);
				$db->editar('boletos',$_id);
		}
		fclose($handle);
	}
break;
default:

	
		if($admin->sub_tg){
				$admin->campos_listagem = array('Download'=>'arquivo');
				$sql = "select idarquivos from arquivos where cobranca = 'sim'";

		}else{
				$admin->campos_listagem = array('Download'=>'arquivo','Acesso restrito'=>'acesso_cadastro','Órgão'=>'orgaos->orgao','Categoria'=>'categorias->categoria','Data de abertura'=>'data_abertura','Data de fechamento'=>'data_fechamento');
				$sql = "select idarquivos from arquivos";
		}
	
break;
}
?>