<?
//$db->query("select * from galerias where ativo = 'sim' and idtipos_de_banners = 1");
//if($db->rows){
//	$_rows = $db->rows;
//    echo '<div id="bannerTop">';
//		while($res = $db->fetch()){
//			$obj = new objetoDb('galerias',$res['idgalerias']);
//			foreach($obj->fotos as $foto){
//    			echo '<div id="bannerTop_'.(++$bannerTop).'" style="display: none;">';
//				echo '<a href="'.$obj->link.'">';
//				echo '<img src="'.$pagina->localhost.'img/'.$foto['id'].'/590/180/1" title="'.htmlentities($obj->link).'" alt="'.$obj->link.'" />';
//				echo '</a>'
//				echo '</div>';
//			}
//		}
//	echo '</div>';
//	
//	echo '<script>document.observe("dom:loaded",function(){ var slideshow = new Slideshow(8,"bannerTop"); slideshow.start(); });< /script>';
//}

$canal = new objetoDb('canais',4);

						$sql = "
						select
							idprodutos
						from
							produtos
						where preco_venda > 0
						order by destaque desc, rand() asc
						limit 10";
						$db->query($sql);
						
						$total = $db->rows;
include("_produtos_listagem.php");
?>
</div>
<?php /*?>
<div class="clear"></div>
<h2 class="tituloSecundario awesome yellow">Mais Procurados</h2>
<?
						$sql = "SELECT
							sum( 1 ) ,
							produtos . *
						FROM produtos, itens, pedidos
						WHERE
						preco_venda > 0 and
						produtos.idprodutos = itens.idprodutos
						AND itens.idpedidos = pedidos.idpedidos
						AND pedidos.idestagios =4
						GROUP BY produtos.idprodutos
						ORDER BY sum( 1 ) DESC
						LIMIT 0 , 20";
						
						
						$db->query($sql);
						$total = $db->rows;
						?>
                        
                        <div class="listagemProdutosMiniBox">
                          <div class="listagemProdutosMini clear">
							<? while($res = $db->fetch() and ++$j <= 15){
								$produto = new produto($res['idprodutos']);?>
							<div class="produtosMini" id="produtoDestaque_<?=$produto->id?>">
							  <div class="produtosMiniFoto">
								<? if($produto->fotos) { ?>
								<a href="<?=$pagina->localhost.$canal->url."Ver/".$produto->id."/".$produto->url.'/?utm_source=MaisVendidos';?>" alt="<?=$imgs[legenda]?>">
								<?
									$foto = $produto->fotos[0];
									if($foto['width'] > $foto['height']){
										$h = intval(($foto['height'] * 100) / $foto['width']);
										$t = intval(50 - ($h/2));
									}else{
										$h = 100;
										$t = 0;
									}?>
								<img src="<?=$pagina->localhost."img/".$produto->fotos[0]['id']?>/100/100" alt="<?=$produto->fotos[0]['legenda']?>" style="margin-top:<?=$t?>px" /></a>
								<? } ?>
							  </div>
							  <div class="produtosMiniTexto icone"> <a href="<?=$pagina->localhost."Produtos/Ver/".$produto->id."/".$produto->url.'/?utm_source=MaisVendidos';?>" alt="<?=$imgs[legenda]?>">
								<?
							  $_pro = explode("-",$produto->produto);
							  echo $_pro[0];
							  ?>
								</a> </div>
							</div>
							<? }?>
						  </div>
							<div class="clear"></div>
							<a class="prev" id="foo2_prev" href="#"><span>anteriores</span></a>
							<a class="next" id="foo2_next" href="#"><span>próximos</span></a>
						  </div>
<?php */?>

<div class="clear espaco"></div>


<h2 class="tituloSecundario awesome yellow">Lista de Casamento</h2>

	<? if($cliente and @$cliente->conectado()){ ?>
			<h1>Lista de casamento da noiva
			  <?=$cliente->nome_noiva?>
			  <?=$cliente->sobrenome_noiva?></h1>
			<div class="content24">
			  <a href="<?=$pagina->localhost?>Lista-de-Casamento/Meus-Dados" class="awesome orange"><h2><img src="<?=$pagina->localhost?>_imagens/botao2013_cadastrar.png" width="36" height="36" alt="Compre este produto" />Confira seus dados</h2></a>
			  <p>No menu <a href="<?=$pagina->localhost?>Lista-de-Casamento/Meus-Dados">"Meus dados"</a> constam as suas informações de cadastro, confira se estão corretas pois precisaremos entrar em contato com vocês posteriormente para realizarmos a entrega de seus presentes de casamento adquiridos</p>
			  <a href="<?=$pagina->localhost?>Lista-de-Casamento/Meus-Presentes" class="awesome orange"><h2><img src="<?=$pagina->localhost?>_imagens/botao2013_confirma.png" width="36" height="36" alt="Compre este produto" />Visualize sua Lista de Casamento</h2></a>
			  <p>Gerencie os itens que gostara de ganhar de presente e acompanhe o andamento, sabendo quem comprou online, através da <a href="<?=$pagina->localhost?>Lista-de-Casamento/Meus-Presentes">Lista de Presentes</a></p>
			  <a href="<?=$pagina->localhost?>Cha-de-Panela/Meus-Presentes" class="awesome orange"><h2><img src="<?=$pagina->localhost?>_imagens/botao2013_confirma.png" width="36" height="36" alt="Compre este produto" />Visualize sua Lista de Chá e Panela</h2></a>
			  <p>Gerencie os itens que gostaria de ganhar de presente e acompanhe o andamento, sabendo quem comprou online, através da <a href="<?=$pagina->localhost?>Cha-de-Panela/Meus-Presentes">Lista de Presentes</a></p>
			</div>
			<div class="content24">
			  <a href="<?=$pagina->localhost?>Produtos" class="awesome orange"><h2><img src="<?=$pagina->localhost?>_imagens/botao2013_comprar.png" width="36" height="36" alt="Compre este produto" />Adicione ítens em sua lista</h2></a>
			  <p>Para montar sua lista de presentes, navegue agora pelo site e em cada página de produtos, no submenu ao lado da foto, constará o botão "Por em minha lista", onde onde o produto poderá ser adicionado facilmente, clique nele quantas vezes desejar até atingir o total de unidades desejadas de cada produto.</p>
			  <a href="<?=$pagina->localhost?>Lista-de-Casamento/Convidados" class="awesome orange"><h2><img src="<?=$pagina->localhost?>_imagens/botao2013_proximo.png" width="36" height="36" alt="Compre este produto" />Divulgue para seus convidados</h2></a>
			  <p>Após estar tudo ok com sua lista, convide seus amigos para acessar a sua lista e comprar seus presentes com muito mais comodidade em nossa loja virtual. No menu <a href="<?=$pagina->localhost?>Lista-de-Casamento/Convidados">"Indique aos convidados"</a>, informe os endereços de e-mail para onde quiser enviar esse lembrete. É rapidinho e estimulante.</p>
			</div>
	<? }else{?>
					<div class="content24">
					<h2>Convidados</h2>
					  <h3>Selecione a noiva ou o noivo para visualizar a lista</h3>
					  <p>Se você chegou até nossa loja virtual através da indicação de<br />uma lista de casamento, selecione a seguir o casal.</p>
					  <div id="formConvidado">
						<form action="http://mesacorpresentes.com.br/Lista-de-Casamento" method="post"
						onsubmit="return redirecionaNoiva($('#nome_noiva_home').val())">
						  <input name="acao" id="acao" type="hidden" value="registrarConvidado" />
						  <div class="campo">
							<label for="nome_noiva_home">Nome dos noivos</label>
							<select id="nome_noiva_home" name="nome_noiva">
							  <?
											  $db->query("select * from clientes order by nome_noiva");
											  while($res = $db->fetch()){
												  $_noivos[$res['nome_completo_noiva']] = '<option value="'.$res['url'].'">'.$res['nome_completo_noiva'].' & '.$res['nome_completo_noivo'].'</option>';
											  }
											  $db->query("select * from clientes order by nome_noivo");
											  while($res = $db->fetch()){
												  $_noivos[$res['nome_completo_noivo']] = '<option value="'.$res['url'].'">'.$res['nome_completo_noivo'].' & '.$res['nome_completo_noiva'].'</option>';
											  }
											  
											  ksort($_noivos);
											  
											  foreach($_noivos as $n)
											  	echo $n;
											  ?>
							</select>
									<label for="">&nbsp;</label>
									<button type="submit" class="awesome orange">Entrar</button>
						  </div>
						</form>
					  </div>
					  <div class="clear espaco"></div>
					  <? if($convidado->id){?>
					  <h3>Você já está conectado</h3>
					  <a href="<?=$pagina->localhost."Lista-de-Casamento/Presentes/".$convidado->url?>" class="awesome orange">Visualize lista de presentes de <?=$convidado->nome_noiva.' &amp; '.$convidado->nome_noivo?></a>
					  <? }?>
					</div>
					<div class="content24">
						<a href="<?=$pagina->localhost?>Lista-de-Casamento/Cadastro"  class="awesome orange"><h1><img src="<?=$pagina->localhost?>_imagens/botao2013_cadastrar.png" width="36" height="36" alt="Compre este produto" />Crie sua lista de presentes</h1></a>
						Faça seu cadastro e navegue pelo site escolhendo os presentes que gostaria de ganhar, depois, divulgue para seus convidados. É bastante simples em alguns minutos fica pronto.
					  <h1>Já tem sua lista de casamento em nosso site?</h1>
					  <div id="formLogin">
						<form action="<?=$pagina->localhost?>Lista-de-Casamento/Meus-Presentes" method="post" id="formLoginForm">
						<?php /*?>onsubmit="return sendWindowForm('formLogin' ,'formLoginForm')"<?php */?>
						  <input name="acao" type="hidden" value="loginCliente" />
						  <div class="campo">
							<label for="_email">E-mail</label>
							<input name="_email" type="text" value="" class="required inputField inputMedio" />
							<label for="_senha">Senha</label>
							<input name="_senha" type="password" value="" class="required inputField inputMedio" />
							<button type="submit" class="awesome orange">Acessar painel</button>
							<br><a href="<?=$pagina->localhost?>Lista-de-Casamento/Lembrar-senha">Esqueci minha senha</a>
						  </div>
						</form>
					  <h1>Montou sua lista na loja?</h1>
						<p class="clear espaco">Se você montou sua lista de presentes diretamente na loja e não criou sua senha na ocasião, <a href="<?=$pagina->localhost?>Contato">entre em contato</a>!</p>
					  </div>
		
	<? } ?>
                        
                        
