<?
switch($admin->acao){
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	
	
	

	$formulario->fieldset('Dados do Pedido');
		$formulario->fieldset->simples('Descrição','descricao');
		$formulario->fieldset->simples('Dados seriais','serialized');
	

	
	
break;
case "salvar":
	if($admin->id == ''){
		$_POST['idcadastros'] = $usuario->cadastros->id;
		$db->inserir('pedidos');
		$inserted_id = $db->inserted_id;
		$db->filhos('pedidos',$inserted_id);
	
	}else{
		$db->editar('pedidos',$admin->id);
		$db->filhos('pedidos',$admin->id);
	}
break;
default:
	$admin->campos_listagem = array('Pedido' => "pedidos->referencia",'Entrega' => "itens->data_entrega",'Pagamentos' => "data",'Cliente' => "clientes->fantasia",
									'Grade' => "produtos->grade",'Tons' => "itens->tons",'Parcela' => "valor");
	$admin->ordenar = "data";
	$admin->extra = "DESC";
break;
}
/*


CREATE VIEW

`rel_itens` AS select `itens`.`iditens` AS `idrel_itens`,
`itens`.`idpedidos` AS `idpedidos`,
`itens`.`idprodutos` AS `idprodutos`,
`clientes`.`idclientes` AS `idclientes`,
`itens`.`tons` AS `tons`,
`itens`.`preco_ex` AS `preco_ex`,
`itens`.`unitario` AS `unitario`,
`pedidos`.`faturamento` AS `faturamento`,
`itens`.`data_entrega` AS `data_entrega`,
`itens`.`prazo_condicoes` AS `prazo_condicoes`,
`itens`.`subtotal` AS `subtotal`,
replace(replace(replace(format(((`itens`.`subtotal` * `cadastros`.`comissao`) / 100),2),'.','|'),',','.'),'|',',') AS `valor_comissao`
from itens,pedidos,clientes,cadastros
where
((`itens`.`idpedidos` = `pedidos`.`idpedidos`) and
  (`pedidos`.`idclientes` = `clientes`.`idclientes`) and
  (`pedidos`.`idcadastros` = `cadastros`.`idcadastros`));




*/
?>
