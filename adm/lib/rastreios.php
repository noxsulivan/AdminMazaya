<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	$formulario->fieldset('Dados da mensagem');
			$formulario->fieldset->simples('Pedido', 'pedido');
			$formulario->fieldset->simples('Identificador do objeto', 'codigo_rastreio');
			$formulario->fieldset->simples('Mensagem adicional', 'info_adicional');
		
	
	
break;
case "salvar":
	$pedido = new objetoDb("pedidos",$_POST['pedido']);
	
	if($admin->id == ''){
		$_POST['data'] = date("Y-m-d H:i");
		$_POST['status_mensagem'] = 'sim';
		$db->inserir('rastreios');
		$corpo = '<h2>Ol&aacute;, '.$pedido->cadastros->nome_completo.'</h2>
					<p>&nbsp;</p>
					<p>Obrigado por escolher a Mesacor para realizar sua compra. Seu pedido foi despachado e você pode seguir a situação de envio diretamente no site dos correios:<p>
					<p>Site: <a href="http://www.correios.com.br/sistemas/rastreamento/default.cfm">http://www.correios.com.br/sistemas/rastreamento/default.cfm</a><br>
					Identificador do Objeto: <strong>'.$_POST['codigo_rastreio'].'</strong></p>
					<p>'.$_POST['info_adicional'].'</p>';
					
					mailClass(trim($pedido->cadastros->email),"Código de rastreio" ,$corpo,"site@mesacor.com.br","Mesacor Tramontina");

	}else{
		$db->editar('rastreios',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array("Pedido" => 'pedido', "Identificador do objeto" => 'codigo_rastreio','Enviada?' => "status_mensagem");
	$sql = "select idrastreios from rastreios order by data desc";
	
break;
}
?>