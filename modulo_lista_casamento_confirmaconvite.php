
					<? if($pagina->id){?>
						<?
							$sql = "update convites set status = 'sim' where chave = '".$pagina->id."' ";
							$db->query($sql);
							
							$sql = "select idclientes from convites where chave = '".$pagina->id."'";
							$db->query($sql);
							if($db->rows){
								$res = $db->fetch();
								$obj = new objetoDb('clientes',$res['idclientes']);
								$convidado = $_SESSION['convidado'] = $obj;
							}
						?>
					<? }?>