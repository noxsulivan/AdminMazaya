<style type="text/css">
<!--
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 10px;
}
body {
	margin-left: 2cm;
	margin-top: 2cm;
	margin-right: 2cm;
	margin-bottom: 2cm;
}
-->
</style><?

list($contrato_id,$cliente_id) = explode('@',$admin->extra);
$contrato = new objetoDb('contratos',$contrato_id);
$cliente = new objetoDb('clientes',$cliente_id);


$corpo = $contrato->corpo;
$campos = $db->campos_da_tabela('clientes');

foreach($campos as $campo => $lixo){
	$corpo = str_replace("<strong>".$campo."</strong>",$cliente->$campo,$corpo);
	$corpo = str_replace("<b>".$campo."</b>",$cliente->$campo,$corpo);
}
echo($corpo);
?>