
<?
include("../ini.php");




$admin = new admin($_SERVER["QUERY_STRING"]);

$db->query("truncate datas_pagamentos");
$db->query("select * from itens");
$r = $db->resourceAtual;
while($res = $db->fetch()){

	$obj = new objetoDb("itens",$res['iditens']);
	//pre($obj);
	
	$datas = explode("/",$obj->prazo_condicoes);
	
	$datas_pagamento = array();

	$i = 0;
	
	if($res['data_fatura'] == "0000-00-00" || $res['data_fatura'] == "NULL"){
		$datas_pagamento[0] = "INDEFINIDO";
	}elseif($res['prazo_condicoes'] == "ANTECIPADO"){
		$datas_pagamento[0] = "ANTECIPADO";
	}else{
		foreach($datas as $data){
			if(date("w",strtotime($res['data_fatura']."+".$data." days")) == 0) $data++;
			
			$datas_pagamento[$i++] = date("d/m",strtotime($res['data_fatura']."+".$data." days"));//." - ".$semana[date("w",strtotime($res['data_fatura']."+".$data." days"))];
			
			pre($sql = "insert into datas_pagamentos values('','".$res['idpedidos']."',
																	   '".$obj->pedidos->idcadastros."','".$obj->pedidos->idclientes."','".$obj->idprodutos."',
																	   '".$res['iditens']."','".date("Y-m-d",strtotime($res['data_fatura']."+".$data." days"))."','".($obj->pedidos->total/count($datas))."')");
			$db->query($sql);
			pre($db->erro);
		}
	}
	
	//pre($datas_pagamento);
	//pre(implode(" - ",$datas_pagamento));
	
	
	$db->query("update itens set datas_pagamentos = '".implode(" - ",$datas_pagamento)."' where iditens = '".$res['iditens']."'");
	$db->resource($r);
	
}

//daniel manplastic, joinville
//pp (sopro e injeção)
//47 3028 3564



?>