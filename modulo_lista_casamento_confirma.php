
			  <?=$pagina->caminho(array($canal->canal => $canal->url))?>
				  <div class="blocoTexto">
					<? if($pagina->id){?>
						<?
							$sql = "update clientes set confirmado = 'sim' where chave = '".$pagina->id."' ";
							$db->query($sql);
							if($db->affected_rows){
								$cadastro = new cadastro();
								echo '<h2>Cadastro confirmado com sucesso</h2><p><a href="'.$pagina->localhost.$canal->url.'" class="awesome orange">Clique aqui para fazer login na �rea restrita</a></p>';
							}else{
								echo "<h2>Erro</h2>A chave de confirma��o fornecida � inv�lida.<p>Chave de confirma��o fornecida � ".$pagina->id."</p>";
							}
						?>
					<? }else{?>
						<h2>Erro</h2>
						A chave de confirma��o n�o foi fornecida.
					<? }?>
					</div>