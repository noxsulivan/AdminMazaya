<?
include('../ini.php');


$pagina = new layout($_SERVER['QUERY_STRING']);


	$db->query('select * from pedidos where idestagios = 4 and ebit = "nao" order by idpedidos desc limit 60');
	
			  
	
	while($res = $db->fetch()){
		
			
			$pedido = new objetoDb('pedidos',$res['idpedidos']);
			
								
				  $corpo = '
				  <h2>Queremos conhecer sua opinião</h2>
				  
				  <a href="https://www.ebitempresa.com.br/bitrate/pesquisa1.asp?empresa=1476185"><img src="http://www.mesacor.com.br/_imagens/msg_ebit.jpg" width="700" height="610"></a>
				  
				  <p>A Equipe '.$pagina->configs['titulo_site'].', agradece a sua preferência</p>';
				  
				  echo $corpo;
				  
				$_POST['ebit'] = "sim";
				$db->editar('pedidos',$pedido->id);
				
				mailClass($pedido->cadastros->email,"Queremos conhecer sua opinião",$corpo,$pagina->configs['email_suporte'],"Mesacor Tramontina",NULL,false);
	}
?>