<?
//if($convidado->id || $cadastro->conectado()){ 
if($convidado->id){ 
?>
	<h3>Lista de presentes</h3>
	<h2><?=$convidado->nome_noiva.' '.$convidado->sobrenome_noiva?><br />
    Dia do chá de panelas: <?=$convidado->data_cha?></h2>
	<?
	$sql = "select * from chas, produtos where chas.idclientes = '".$convidado->id."' and chas.idprodutos = produtos.idprodutos order by produtos. codigo";
	echo "<!-- $sql -->";
	$db->query($sql);
	?>
	
						<div class="listagemProdutos">
						<? if($db->rows){
							while($res = $db->fetch()){
								$presente = new objetoDb('chas',$res['idchas']);?>
								
								<div class="produtosNoivas" id="produtosNoivas_<?=$presente->produtos->id?>">
									<div class="produtosNoivasFoto">
									<? if($presente->produtos->fotos) { ?>
									<a href="<?=$pagina->localhost."Produtos/Ver/".$presente->produtos->id?>" title="<?=normaliza($presente->produtos->produto)?>"><img src="<?=$pagina->localhost."img/".$presente->produtos->fotos[0]['id']?>/140/140" alt="<?=$presente->produtos->fotos[0]['legenda']?>" /></a>
									<? }?>
									</div>
									<div class="produtosNoivasTitulo"><a href="<?=$pagina->localhost."Produtos/Ver/".$presente->produtos->id?>" title="<?=normaliza($presente->produtos->produto)?>">Cod.: <?=$presente->produtos->codigo?><strong><?=$presente->produtos->produto?></strong></a> <?=($presente->produtos->fabricantes ? $presente->produtos->fabricantes->fabricante : "")?>
										<div class="produtosNoivasPreco">
											<? if((float)$presente->produtos->preco_promocional > 0){
												echo '<span class="overline">de R$ '.number_format($presente->produtos->preco_venda,2,",",".").'</span>';
												echo '<div class="preco">por R$ '.number_format($presente->produtos->preco_promocional,2,",",".")."</div>";
											}else{
												if((float)$presente->produtos->preco_venda > 0)
													echo '<div class="preco">por R$ '.number_format($presente->produtos->preco_venda,2,",",".")."</div>";
												else
													echo '<div class="preco">Indisponível para compra online</div>';
											}?>
										</div>
									</div>
									<div class="produtosNoivasNumero">
                                    <ul><li>Pediu <span id="produtosNoivasQtd_<?=$presente->id?>"><?=$presente->quantidade?></span></li>
                                    <li>Ganhou <span id="produtosNoivasGnd_<?=$presente->id?>"><?=$presente->ganhados?></span></li></ul>
									</div>
									<div class="produtosNoivasFuncoes">

										<? if($presente->ganhados > 0){?>
                                        	<img src="<?=$pagina->localhost?>_imagens/ja_ganhou.png" width="185" height="82" alt="Este presente já foi enviado para os noivos" />
                                        <? }else{ ?>
											<? if((float)$presente->produtos->preco_venda > 0){?>
                                            <a href="<?=$pagina->localhost?>Cliente/Carrinho/<?=$presente->produtos->id?>-<?=$convidado->id?>">
                                              <img src="<?=$pagina->localhost?>_imagens/btn_comprar_sec.png" width="200" height="120" align="left" />
                                            </a>
											<? }?>
                                        <? }?>									</div>
								</div>
								<? if(++$listagemBreak%3==0){?><div class="clear"></div><? }?>
							<? }?>
						<? }else{?>
							<h3>A lista de presentes para o chá de panelas não está disponível no momento.</h3>
						<? }?>
                        
                        
			<div class="clear"></div>
						
						</div>
<? }else{?>
	<h2><?=$canal->canal?></h2>
	<div class="content24">
		<?=$canal->texto?>
	</div>
	<div class="sidebar24">
		<h3>Crie sua lista de presentes</h3>
			<h4>Você pode criar sua lista de presentes através do site</h4>
            <p>Faça seu cadastro e navegue pelo site escolhendo os presentes que gostaria de ganhar, depois, divulgue para seus convidados. É bastante simples em alguns minutos fica pronto.</p>
            <p>É possível alterar a lista e conferir os resultados. Aproveite</p>
            <a class="awesome orange" href="<?=$pagina->localhost?>Noiva/Cadastro"><img src="<?=$pagina->localhost?>_imagens/1271297555_geschenk_box_1.png" width="48" height="48" align="left" style="position:relative; top:-5px; margin-left:-10px;" /> Criar meu cadastro! </a>

			<div class="clear"></div>
		<h3>Acesso para Convidados</h3>
			<h4>Selecione os noivos e informe a senha</h4>
			<div id="formulario">
				<form action="<?=$pagina->localhost.$canal->url?>Login" method="post" id="formularioForm" onsubmit="return sendWindowForm('formulario' ,'formularioForm')">
					<input name="acao" id="acao" type="hidden" value="registrarConvidado" />
					<div class="campo">
						<label for="nome">Nome</label>
						<select id="nome" name="nome" class="required ">
							<option></option>
							<?
							$db->query("select * from clientes order by nome");
							while($res = $db->fetch()){
								echo '<option value="'.$res['idclientes'].'">'.normaliza($res['nome']).'</option>';
							}
							?>
						</select>
					</div>
					<div class="campo">
						<label for="codigo_acesso">Senha</label>
						<input name="codigo_acesso" type="password" value="" class="required inputField inputMedio" />
					</div>
					<div class="campo">
						<label>&nbsp;</label>
						<button type="submit" class="submitButton">Entrar</button>
					</div>
				</form>
			</div>
			<div class="clear"></div>
	</div>
<? }?>