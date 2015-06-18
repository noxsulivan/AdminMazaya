<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	
	$formulario->fieldset('Cobrança');
	
	$formulario->fieldset->simples('Grupo', 'idtipos_de_cobrancas');
	$formulario->fieldset->simples('Mês', 'referencia');
	
	
		$formulario->fieldset->simples('Data do processamento', 'data_processamento',date('d/m/Y H:i:s'));
		
		$formulario->fieldset->simples('Vencimento', 'vencimento');
	
	
	$formulario->fieldset('Valores - Preencha os valores a serem aplicados a todos os condôminos');
		$formulario->fieldset->simples('Mensalidade do Interfone', 'interfone');
		
	$formulario->fieldset('Previsão de entrada');
		$formulario->fieldset->simples('Valor estimado', 'valor');
		
	
		
		
	$formulario->fieldset('Valores individuais',false, true);
		$formulario->fieldset->boletos($idtipos_de_cobrancas = 3);
		
		
	$formulario->fieldset('Arquivos anexos');
		$formulario->fieldset->arquivo();
		$formulario->fieldset->simples('Mensagem', 'mensagem');
	
	
break;
case "salvar":
	if($admin->id == ''){
		$db->inserir('cobrancas');
		$db->salvar_arquivos('cobrancas',$db->inserted_id);
		$idcobrancas = $db->inserted_id;
	}else{
		$db->editar('cobrancas',$admin->id);
		$db->salvar_arquivos('cobrancas',$admin->id);
		$idcobrancas = $admin->id;
	}
	
	
			foreach($_POST['boleto'] as $_id => $_registro){
				
					$_registro['idcobrancas'] = $idcobrancas;
					$_registro['idcondominos'] = $_id;
					$_registro['vencimento'] = $_POST['vencimento'];
					$_registro['referencia'] = $_POST['referencia'];
					$_registro['data_processamento'] = $_POST['data_processamento'];
					$_registro['nosso_numero'] = str_pad($idcobrancas,4,"0",STR_PAD_LEFT).str_pad($_id,4,"0",STR_PAD_LEFT);
					
					$qr = $db->query("select * from boletos where idcobrancas = '".$idcobrancas."' and idcondominos = '".$_id."'");
					$res = $db->fetch();
					if($db->rows() > 0){
						$db->editar("boletos",$res['idboletos'],$_registro);
					}else{
						$db->inserir("boletos",$_registro);
					}
			}
break;
default:
	
		$admin->campos_listagem = array('Data de processamento' => "data_processamento",'Mês' => "referencia",'Valor Total' => "valor",'Data de vencimento' => "vencimento");

		$admin->listagemLink('enviarLote','<img src="imagens/buttons/mail_send.png" align="absmiddle"> Enviar Boletos','');

		if($usuario->tipos_de_usuarios->id == 4){
				$admin->campos_listagem = array('Vencimento' => "data_vencimento",'Referente a' => 'cobrancas->referencia');
				$admin->ordenavel = true;
		}
				$admin->ordenar = "data_processamento";
				$admin->extra = "DESC";
		
break;
}


?>