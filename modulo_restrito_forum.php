  <? switch($pagina->acao){
		case "Forum":?>
									  <? if($pagina->id){ ?>
									  <?
																		$cadastro->atualizarSession();
																		$topico = new objetoDb('topicos',$pagina->id);
																		echo $pagina->caminho(array($canal->canal => $canal->url,"Fórum"=>$pagina->acao."/"));
																		?>
									  <div class="blocoTexto">
										<h2>
										  <?=$topico->titulo?>
										  <span class="subtitulo">
										  <?=$topico->subtitulo?>
										  </span> </h2>
										<h3>Autor:</h3>
										<?=$topico->cadastros->nome?>
										<h3>Texto</h3>
										<?=$topico->corpo?>
										<h3>Data</h3>
										<?=ex_data($topico->data)?>
									  </div>
									  <div class="blocoFuncoes">
										<ul class="listaFuncoes">
										  <li><a class="funcaoForum" href="<?=$pagina->localhost.$canal->url?>Responder-topico/<?=$topico->id?>"> Responder </a></li>
										  <li><a class="funcaoSalvar" href="javascript:salvarTopico(<?=$topico->id?>)"> Salvar cópia PDF </a></li>
										  <li><a class="funcaoImprimir" href="javascript:imprimir()"> Imprimir </a></li>
										</ul>
									  </div>
									  <? }?>
									  <?
																	$sql = "select * from topicos where topicos.idtopicos != '".$topico->id."' and aberto = 1 order by data desc limit 10";
																	$db->query($sql);
																	if($db->rows){ ?>
									  <div class="blocoAdicionais">
										<h3 class="blocoTitulo titInfo">Em discussão:</h3>
										<ul class="listaLinks">
										  <? while($res = $db->fetch()){ ?>
										  <li><a href="<?=$pagina->localhost.$canal->url.'Forum/'.$res['url']?>">
											<?=$res['titulo']?>
											</a></li>
										  <? }?>
										</ul>
									  </div>
									  <? }?>
  <? break; ?>
  
  <? case "Responder-topico":?>
  <? case "Abrir-topico":?>
  <?=$pagina->caminho(array($canal->canal => $canal->url,"Responder Tópico"=>$pagina->acao))?>
								  <? switch($pagina->id){
									case "Salvar": ?>
																	  <div class="blocoTexto">
																		<?
																			$_POST["idcadastros"] = $cadastro->id;
																			$_POST["data"] = date("d/m/Y");
																			$_POST["url"] = diretorio($_POST["titulo"]);
																			
																			$db->inserir("topicos");
																			if($db->erro){
																				echo $db->erro;
																			}else{ ?>
																		<?
																				$topico = new objetoDb('artigos',$_POST["idartigos"]);
																				?>
																		<h2>Comentário Publicado</h2>
																		<p>Seu comentário ao tópico &quot;
																		  <?=$topico->titulo?>
																		  &quot; foi publicado no fórum com sucesso.</p>
																		<p><a href="<?=$pagina->localhost.$canal->url?>Forum/<?=$db->inserted_id?>">Clique aqui para visualizar a discussão</a></p>
																		<? }?>
																	  </div>
									  <? break;?>
									  <? default:?>
																		<?
																		$topico = new objetoDb('topicos',$pagina->id);
																		?>
																	  <div class="blocoTexto">
																			<h3>Resposta ao tópico "<?=$topico->titulo?>"</h3>
																		<div id="formulario">
																		  <form action="<?=$pagina->localhost.$canal->url?>Responder-topico/Salvar" method="post" id="formularioForm">
																			<input name="idtopicos_2" type="hidden" value="<?=$pagina->id?>" />
																			<fieldset>
																			<label for="titulo">Assunto</label>
																			<input id="titulo" name="titulo" type="text" class="inputField required" />
																			<label for="corpo">Mensagem</label>
																			<textarea id="corpo" name="corpo"  class="required inputField"></textarea>
																			<button type="submit" class="inputField">Enviar</button>
																			</fieldset>
																		  </form>
																		  <script type="text/javascript">
																											  new Validation('formularioForm');
																										  </script>
																		</div>
																	  </div>
									  <? }?>
									  
									  <div class="blocoSidebar">
										<ul class="listaFuncoes">
										  <li><a class="funcaoForum" href="<?=$pagina->localhost.$canal->url?>Abrir-topico"> Abrir um novo </a></li>
										  <li><a class="funcaoForum" href="<?=$pagina->localhost.$canal->url?>Forum/<?=$topico->url?>"> Voltar </a></li>
										</ul>
									  </div>
  <? break; ?>  
  <? case "Meus-topicos":?>
  <?=$pagina->caminho(array($canal->canal => $canal->url,"Meus tópicos"=>$pagina->acao))?>
									  <?
										$sql = "select * from topicos where topicos.idcadastros != '".$cadastro->id."' order by data desc";
										$db->query($sql);
										if($db->rows){ ?>
									  <div class="blocoAdicionais">
										<h3 class="blocoTitulo titInfo">Em discussão:</h3>
										<ul class="listaLinks">
										  <? while($res = $db->fetch()){ ?>
										  <li><a href="<?=$pagina->localhost.$canal->url.'Forum/'.$res['url']?>">
											<?=$res['titulo']?>
											</a></li>
										  <? }?>
										</ul>
									  </div>
									  <? }else{?>
									  <div class="blocoTexto">
									  <h2>Nenhum tópico ou resposta encontrados.</h2>
									  Você ainda não abriu ou respondeu a nenhum tópico no Fórum OralImplante. Se você tem alguma dúvida que gostaria de compartilhar com nossos colegas especialistas, aproveite a oportunidade e <a href="<?=$pagina->localhost.$canal->url?>Forum/Abrir-topico">abra um novo tópico </a>
									  </div>
									  <? }?>
									  
									  <div class="blocoSidebar">
										<ul class="listaFuncoes">
										  <li><a class="funcaoForum" href="<?=$pagina->localhost.$canal->url?>Abrir-topico"> Abrir um novo </a></li>
										  <li><a class="funcaoForum" href="<?=$pagina->localhost.$canal->url?>Forum/<?=$topico->url?>"> Voltar </a></li>
										</ul>
									  </div>
  <? break; ?>  
  
  <? case "Discutir-artigo":?>
  <?=$pagina->caminho(array($canal->canal => $canal->url,"Discutir Artigo"=>$pagina->acao))?>
								  <? switch($pagina->id){
									case "Salvar": ?>
																	  <div class="blocoTexto">
																		<?
																											$_POST["idcadastros"] = $cadastro->id;
																											$_POST["data"] = date("d/m/Y");
																											$_POST["url"] = diretorio($_POST["titulo"]);
																											
																											$db->inserir("topicos");
																											if($db->erro){
																												echo $db->erro;
																											}else{ ?>
																		<?
																												$topico = new objetoDb('artigos',$_POST["idartigos"]);
																												?>
																		<h2>Comentário Publicado</h2>
																		<p>Seu comentário ao artigo &quot;
																		  <?=$topico->titulo?>
																		  &quot; foi publicado no fórum com sucesso.</p>
																		<p><a href="<?=$pagina->localhost.$canal->url?>Forum/<?=$db->inserted_id?>">Clique aqui para visualizar a discussão</a></p>
																		<? }?>
																	  </div>
									  <? break;?>
									  <? default:?>
																	  <div class="blocoTexto">
																		<?
																		$artigo = new objetoDb('artigos',$pagina->id);
																		?>
																		<h3>Discutindo o artigo: &quot;
																		  <?=$artigo->titulo?>
																		  &quot;</h3>
																		<div id="formulario">
																		  <form action="<?=$pagina->localhost.$canal->url?>Discutir-artigo/Salvar" method="post" id="formularioForm">
																			<input name="idartigos" type="hidden" value="<?=$pagina->id?>" />
																			<fieldset>
																			<label for="titulo">Título</label>
																			<input id="titulo" name="titulo" type="text" class="inputField required" />
																			<label for="corpo">Mensagem</label>
																			<textarea id="corpo" name="corpo"  class="required inputField"></textarea>
																			<button type="submit" class="inputField">Enviar</button>
																			</fieldset>
																		  </form>
																		  <script type="text/javascript">
																											  new Validation('formularioForm');
																										  </script>
																		</div>
																	  </div>
									  <? }?>
									  <div class="blocoSidebar">
										<ul class="listaFuncoes">
										  <li><a class="funcaoForum" href="<?=$pagina->localhost.$canal->url?>Abrir-topico"> Abrir um novo </a></li>
										  <li><a class="funcaoForum" href="<?=$pagina->localhost.$canal->url?>Artigos/<?=$artigo->url?>"> Voltar </a></li>
										</ul>
									  </div>
  <? break; ?>  
  
  <? } ?>