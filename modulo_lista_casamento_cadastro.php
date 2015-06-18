<? switch($pagina->acao){
  case "Alterar-senha":
  case "Alterar-Senha":?>
						<h2>Alterar senha</h2>
							<? switch($pagina->id){
								case "Salvar":
									if($_REQUEST['chave']){
										$id = $db->fetch("select idclientes from clientes where chave = '".$_REQUEST['chave']."'",'idclientes');
										$cli = new objetoDb('clientes',$id);
										if($_POST["nova1"] == $_POST["nova2"])
										$_POST['senha'] = md5($_POST["nova1"]);
										$_POST['chave'] = '';
										if($db->editar('clientes',$cli->id)){?>
										<h3>Sua senha foi alterada com sucesso</h3>
                                        <a href="<?=$pagina->localhost.$canal->url?>" class="awesome orange float-left">Clique aqui para acessar seu cadastro</a>
										<? }else{ ?>
										<h3>
											<?=$db->erro;?>
										</h3>
										<? }?>
									<? }
									if(isset($cliente) and $cliente->conectado()){
										if($cliente->trocarSenha($_POST["senhaAtual"],$_POST["nova1"],$_POST["nova2"])){ ?>
										<h3>Sua senha foi alterada com sucesso</h3>
										<? }else{ ?>
										<h3>
											<?=$cliente->erro;?>
										</h3>
										<? }?>
                                    <? }?>
							<? break;?>
							<? default:?>
							<div id="formulario">
								<form action="<?=$pagina->localhost.$canal->url?>Alterar-senha/Salvar" method="post" id="formularioForm">
									<input name="acao" type="hidden" value="enviar" />
									<fieldset>
										<label for="senhaAtual">Senha atual</label>
										<input id="senhaAtual" name="senhaAtual" type="password" value="" class="" />
										<label for="nova1">Nova senha</label>
										<input id="nova1" name="nova1" type="password" value="" class="" />
										<label for="nova2">Confirme</label>
										<input id="nova2" name="nova2" type="password" value="" class="" />
										<button type="submit" class="">Enviar</button>
									</fieldset>
								</form>
							</div>
							<? }?>
						</div>
<? break; ?>
<? case "Chave":?>


						<h2>Alterar senha</h2>
							<div id="formulario">
								<form action="<?=$pagina->localhost.$canal->url?>Alterar-senha/Salvar" method="post">
									<input name="acao" type="hidden" value="enviar" />
									<input name="chave" type="hidden" value="<?=$pagina->id?>" />
									<div class="formulario">
										<div class="campo">
											<label for="nova1">Nova senha</label>
											<input id="nova1" name="nova1" type="password" value="" class="required inputMedio" />
										</div>
										<div class="campo">
											<label for="nova2">Confirme</label>
											<input id="nova2" name="nova2" type="password" value="" class="required inputMedio" />
										</div>
                                        <div class="campo">
                                        	<label for="">&nbsp;</label>
                                            <button type="submit" class="awesome orange float-right"><strong>Salvar nova senha</strong></button>
                                        </div>
									</div>
								</form></div>
                                        
                                        
<? break; ?>
<? case "Lembrar-senha":?>
<h2>Esqueceu sua senha?</h2>
<div class="content24">
<h3>Para alterar sua senha, informe o seu e-mail</h3>

							<div id="formulario">
								<form action="<?=$pagina->localhost.$canal->url?>Lembrar-senha" method="post" id="formularioForm" onsubmit="return sendWindowForm('formulario' ,'formularioForm')">
									<input name="acao" type="hidden" value="enviarSenha" />
									<div class="formulario">
										<div class="campo">
											<label for="email">E-mail válido *</label>
											<input id="email" name="email" type="text" value="<?=$cliente->email;?>" class="required validate-email inputGrande" />
										</div>
                                        <div class="campo">
                                        	<label for="">&nbsp;</label>
                                            <button type="submit" class="awesome orange float-left"><strong>Enviar instruções</strong></button>
                                        </div>
									</div>
								</form></div>
<? break; ?>
<? case "Sair":?>
						<? $cliente->sair($pagina->localhost);?>
						<? unset($_SESSION['convidado']);?>
<? break; ?>
<? default:?>
<? if(!$cliente->conectado()){?>
<h2>Cadastre sua lista de presentes na Mesacor</h2>
<?php /*?><div class="content24">
<h3>Cadastre-se, monte sua lista e divulgue para seus convidados e concorra ao um lindo produto da Tramontina Design Collection</h3>
<p>Para cada novo cadastro realizado no nosso site, o casal de noivos ganha 5 cupons, convidando seus amigos a acessar a sua lista mais 1 cupom por e-mail informado. E se este e-mail vier a se cadastrar como cliente na loja e efetuar a compra de um produto da lista, o casal ganha mais 10 cupons.</p>
<p>O sorteio será realizado no fim do mês de Julho/2011 e o prêmio é o maravilhoso <a href="https://www.mesacor.com.br/Produtos/Ver/510">Conjunto de argolas para guardanapo 6 peças.</a></p>
<p>Aproveite e comece agora mesmo.</p>
<h4>Confira o regulamento da <a href="<?=$pagina->localhost?>Regulamento-da-Promocao-Mes-da-Noivas">Promoção "Mês das Noivas"</a></h4>
</div><div class="sidebar24">
<a href="https://www.mesacor.com.br/Produtos/Ver/510"><img id="itemGrd_467" src="https://www.mesacor.com.br/img/467/450/300" alt="" style="margin-top:0px" title=""></a>
</div>

<?php */?>
<? }?>
<div class="clear espaco"></div>
							<div id="formulario">
								<form action="<?=$pagina->localhost.$canal->url?>Cadastro" method="post" id="formularioForm" onsubmit="return sendWindowForm('formulario' ,'formularioForm')">
									<input name="acao" type="hidden" value="salvarDadosCliente" />
									<div class="formulario">
										<h2>Dados do casal</h2>
										<small>
										<? _e("Os campos marcados com * são obrigatórios.")?>
										</small>
										<div class="campo">
											<label for="nome_noiva">Nome da noiva*</label>
											<input id="nome_noiva" name="nome_noiva" type="text" value="<?=$cliente->nome_noiva?>" class="required inputGrande" />
										</div>
                                        <div class="campo">
											<label for="cpf_noiva">CPF da noiva*</label>
											<input id="cpf_noiva" name="cpf_noiva" type="text" value="<?=$cliente->cpf_noiva?>" class="required inputGrande" />
										</div>
                                        <div class="campo float-right">
											Necessário pois o pedido deve ser enviado acompanhado da nota fiscal preenchida com o CPF
										</div>
<?php /*?>										<div class="campo">
											<label for="sobrenome_noiva">Sobrenome da noiva*</label>
											<input id="sobrenome_noiva" name="sobrenome_noiva" type="text" value="<?=$cliente->sobrenome_noiva?>" class="required inputGrande" />
										</div>
<?php */?>										<div class="campo">
											<label for="nome_noivo">Nome do noivo*</label>
											<input id="nome_noivo" name="nome_noivo" type="text" value="<?=$cliente->nome_noivo?>" class="required inputGrande" />
										</div>
                                        <div class="campo">
											<label for="cpf_noivo">CPF do noivo*</label>
											<input id="cpf_noivo" name="cpf_noivo" type="text" value="<?=$cliente->cpf_noivo?>" class="required inputGrande" />
										</div>
                                        <div class="campo float-right">
											Necessário pois o pedido deve ser enviado acompanhado da nota fiscal preenchida com o CPF
										</div>
<?php /*?>										<div class="campo">
											<label for="sobrenome_noivo">Sobrenome do noivo*</label>
											<input id="sobrenome_noivo" name="sobrenome_noivo" type="text" value="<?=$cliente->sobrenome_noivo?>" class="required inputGrande" />
										</div>
<?php */?>										<div class="campo">
											<label for="data_casamento">Data do casamento *</label>
											<input id="data_casamento" name="data_casamento" type="text" value="<?=ex_data($cliente->data_casamento)?>" class="inputMedio" alt="date" />
										</div>
										<div class="clear"></div>
										
										<div class="campo">
											<label for="email">E-mail válido *</label>
											<input id="email" name="email" type="text" value="<?=$cliente->email;?>" class="required validate-email inputGrande" />
										</div>
										<? if(!$cliente->conectado()){?>
										<div class="campo">
											<label for="email">Senha</label>
											<input id="senha" name="senha" type="password" class="required inputPequeno" />
										</div>
										<div class="campo">
											<label for="email">Confirme a senha</label>
											<input id="confirmacao" name="confirmacao" type="password" class="required inputPequeno" onchange="confirmaSenha()" />
										</div>
										<? }?>
                                    	
									</div>
									<div class="formularioGrd">
										<h2>Informações de contato</h2>
                                        <small>&nbsp;</small>
										<div class="campo">
										  <label for="fone">DDD+Telefone *</label>
										  <input id="telefone" name="telefone" type="text" value="<?=$cliente->telefone;?>" class="required inputMedio mascara" mask="telefone" maxlength="14" />
										</div>
										<div class="campo">
										  <label for="celular">DDD+Celular</label>
										  <input id="celular" name="celular" type="text" value="<?=$cliente->celular;?>" class="inputMedio mascara" mask="telefone" maxlength="14" />
										</div>
										<div class="clear"></div>
										<div class="campo">
											<label for="cep">CEP</label>
											<input id="cep" name="cep" type="text" value="<?=$cadastro->cep;?>" class="required inputMedio" alt="cep" />
											<button onclick="carregaCep($('#cep').val())" class="cepButton awesome grey float-right" type="button">Preencher</button>
										</div>
										<div id="carregandoCEP"> </div>
										<div class="clear"></div>
										<div class="campo">
											<label for="endereco">Endereço</label>
											<input id="endereco" name="endereco" type="text" value="<?=$cadastro->endereco;?>" class="required inputGrande" />
										</div>
										<div class="campo">
											<label for="numero">Número</label>
											<input id="numero" name="numero" type="text" value="<?=$cadastro->numero;?>" class="required inputPequeno" />
										</div>
										<div class="campo">
											<label for="complemento">Complemento</label>
											<input id="complemento" name="complemento" type="text" value="<?=$cadastro->complemento;?>" class="inputPequeno" />
										</div>
										<div class="campo">
											<label for="bairro">Bairro</label>
											<input id="bairro" name="bairro" type="text" value="<?=$cadastro->bairro;?>" class="inputMedio" />
										</div>
										<div class="campo">
											<label for="estado">Estado</label>
											<select id="estado" name="estado" class="inputPequeno" onchange="carregaCidades(this.value)">
												<option></option>
												<?
												$db->query("select * from estados order by estado");
												while($res = $db->fetch()){
																			echo '<option value="'.$res['idestados'].'"'.(($cadastro->conectado() && $res['idestados']==$cadastro->estados->id)?' selected':'').'>'.$res['nome'].'</option>';
												}
												?>
											</select>
										</div>
										<div class="campo">
											<label for="cidade">Cidade</label>
											<select name="cidade" id="cidade"  onchange="carregaBairros(this.value)"  class="inputMedio" />
												<option></option>
												<?
												//$db->query("select * from cidades where idestados = '".$cadastro->estados->id."' order by cidade");
												////while($res = $db->fetch()){
												//							echo '<option value="'.$res['idcidades'].'"'.(($res['idcidades']==$cadastro->cidades->id)?' selected':'').'>'.$res['cidade'].'</option>';
												//}
												?>
											
											</select>
										</div>
									</div>
										<div class="clear"> </div>
                                            <button type="submit" class="awesome orange float-right"><strong>Salvar informações</strong></button>
								</form>
							</div>
<div class="clear espaco"></div>
<div class="content24">
  <h3><a href="<?=$pagina->localhost.$canal->url?>Meus-Dados">Confira seus dados</a></h3>
  <p>No menu <a href="<?=$pagina->localhost.$canal->url?>Meus-Dados">"Meus dados"</a> constam as suas informações de cadastro, confira se estão corretas pois precisaremos entrar em contato com vocês posteriormente para realizarmos a entrega de seus presentes de casamento adquiridos</p>
  <h3><a href="<?=$pagina->localhost.$canal->url?>Meus-Presentes">Visualize sua lista</a></h3>
  <p>Gerencie os itens que gostaria de ganhar de presente e acompanhe o andamento, sabendo quem comprou online, através da <a href="<?=$pagina->localhost.$canal->url?>Meus-Presentes">Lista de Presentes</a></p>
</div>
<div class="sidebar24">
  <h3>Adicione ítens em sua lista</h3>
  <p>Para montar sua lista de presentes, navegue agora pelo site e em cada página de produtos, no submenu ao lado da foto, constará o botão "Por em minha lista", onde onde o produto poderá ser adicionado facilmente, clique nele quantas vezes desejar até atingir o total de unidades desejadas de cada produto.</p>
  <h3><a href="<?=$pagina->localhost.$canal->url?>Convidados">Divulgue para seus convidados</a></h3>
  <p>Após estar tudo ok com sua lista, convide seus amigos para acessar a sua lista e comprar seus presentes com muito mais comodidade em nossa loja virtual. No menu <a href="<?=$pagina->localhost.$canal->url?>Convidados">"Indique aos convidados"</a>, informe os endereços de e-mail para onde quiser enviar esse lembrete. É rapidinho e estimulante.</p>
</div><? break; ?>
<? } ?>

<div class="clear espaco"></div>