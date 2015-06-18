
			  <?=$pagina->caminho(array($canal->canal => $canal->url))?>
				  <div class="blocoTexto">
					<? if($pagina->id){?>
						<?
							$sql = "update clientes set confirmado = 'sim' where chave = '".$pagina->id."' ";
							$db->query($sql);
							if($db->affected_rows){
								$cadastro = new cadastro();
								echo '<h2>Cadastro confirmado com sucesso</h2><p><a href="'.$pagina->localhost.$canal->url.'" class="awesome orange">Clique aqui para fazer login na área restrita</a></p>';
							}else{
								echo "<h2>Erro</h2>A chave de confirmação fornecida é inválida.<p>Chave de confirmação fornecida é ".$pagina->id."</p>";
							}
						?>
					<? }else{?>
						<h2>Erro</h2>
						A chave de confirmação não foi fornecida.
					<? }?>
					</div>