<div class="clear"></div>

											<div class="formulario">
											  <h2>Já é cliente nosso?</h2>
											  <a href="<?PHP echo $pagina->localhost?>Cliente/Login" class="botao red">Faça o login</a>
											<div class="clear"></div>

											  
											  		<div id="formLogin" style="display:none">
													<form action="<?=$pagina->localhost.$canal->url?>Login" method="post" id="formularioFormLogin">
													  <input name="acao" type="hidden" value="enviarDados" />
													  
													  
													  
													  
													  <div class="campo">
														<label for="_email">E-mail</label>
														<input name="_email" type="text" value="" class="required inputField inputGrande" />
													  </div>
													  <div class="campo">
														<label for="_senha">Senha</label>
														<input name="_senha" type="password" value="" class="required inputField inputGrande" />
													  </div>
													  <div class="campo">
														<label>&nbsp;</label>
													  <button type="submit" class="botao blue">Entrar</button>
													  </div>
													  <div class="campo clear">
																<label for="">&nbsp;</label>
																<a href="<?=$pagina->localhost.$canal->url?>Lembrar-senha" class="botao grey">Esqueci minha senha</a>
													  </div>
													</form>
													</div>
											
											</div>
											<div class="formularioGrd">
											  <h2> Cadastre-se </h2>
											  <a href="<?PHP echo $pagina->localhost?>Cliente/Cadastro" class="botao red">Preencher cadastro</a>
											<div class="clear"></div>
											  <p>Se você ainda não está cadastrado em nosso site, preencha seus dados. É simples, rápido, gratuito e sem compromisso.</p>
											  		<div id="formCadastro" style="display:none">
													
													
															<form action="<?=$pagina->localhost.$canal->url?>Salvar" method="post" id="formularioFormCadastro" onsubmit="return sendWindowForm('formulario' ,'formularioFormCadastro')">
																<input name="acao" type="hidden" value="salvarDadosCadastro" />
																<input name="referer" type="hidden" value="<?=$_SERVER['HTTP_REFERER']?>" />
																<div class="formulario">	
																	<h3>Seus dados</h3>
																	
																	<div class="campo">
																		<label for="pessoa">Pessoa</label>
																		<input id="pessoaFisica" name="pessoa" type="radio" checked="checked" value="<?=$cadastro->nome?>" onclick="$('#divpessoaJuridica').hide();$('#divpessoaFisica').show()" /> Física
																		<input id="pessoaJuridica" name="pessoa" type="radio" value="<?=$cadastro->nome?>" onclick="$('#divpessoaFisica').hide();$('#divpessoaJuridica').show()" /> Jurídica
																	</div>
																	<div class="clear"> </div>
																	<div id="divpessoaFisica">
																		<div class="campo">
																			<label for="nome"><strong>Nome Completo</strong></label>
																			<input id="nome" name="nome" type="text" value="<?=$cadastro->nome?> <?=$cadastro->sobrenome?>" class="inputGrande required" />
																		</div>
							                                            <div class="clear"></div>
																		<div class="campo">
																		  <label for="cpf"><strong>CPF</strong></label>
																		  <input id="cpf" name="cpf" type="text" value="<?=$cadastro->cpf?>" class="inputMedio cpf" alt="cpf" maxlength="14" />
																		</div>
																		<div class="campo">
																		  <label for="rg"><strong>RG</strong></label>
																		  <input id="rg" name="rg" type="text" value="<?=$cadastro->rg?>" class="inputMedio required" maxlength="14" />
																		</div>
																	</div>
																	<div id="divpessoaJuridica" style="display:none">
																		<div class="campo">
																			<label for="nome_fantasia"><strong>Razão Social</strong></label>
																			<input id="nome_fantasia" name="nome_fantasia" type="text" value="<?=$cadastro->nome_fantasia?>" class="inputGrande" />
																		</div>
																		<div class="campo">
																			<label for="responsavel"><strong>Responsável</strong></label>
																			<input id="responsavel" name="responsavel" type="text" value="<?=$cadastro->responsavel?>" class="inputGrande" />
																		</div>
																		<div class="clear"></div>
																		<div class="campo">
																		  <label for="cnpj"><strong>CNPJ</strong></label>
																		  <input id="cnpj" name="cnpj" type="text" value="<?=$cadastro->cpf?>" class="inputMedio cnpj" alt="cnpj" maxlength="18" />
																		</div>
																		<div class="campo">
																		  <label for="inscricao"><strong>Inscrição Estadual</strong></label>
																		  <input id="inscricao" name="inscricao" type="text" value="<?=$cadastro->inscricao?>" class="inputMedio" />
																		</div>
																	</div>
																	
																	<div class="campo">
																	  <label for="telefone"><strong>Fone principal</strong></label>
																	  <input id="telefone" name="telefone" type="text" value="<?=$cadastro->telefone;?>" class="required inputMedio" alt="phone" maxlength="14" />
																	</div>
																	<div class="campo">
																	  <label for="telefone_alternativo"><strong>Fone alternativo</strong></label>
																	  <input id="telefone_alternativo" name="telefone_alternativo" type="text" value="<?=$cadastro->telefone_alternativo;?>" class="required inputMedio" alt="phone" maxlength="14" />
																	</div>
																	
																<div class="clear"></div>
																	<div class="campo">
																		<label for="email"><strong>E-mail</strong></label>
																		<input id="email" name="email" type="text" value="<?=$cadastro->email;?>" class="required email inputGrande" />
																	</div>
																	<? if(!$cadastro->conectado()){?>
																	<div class="campo">
																		<label for="email">Senha</label>
																		<input id="senha" name="senha" type="password" class="inputMedio" />
																	</div>
																	<div class="campo">
																		<label for="email">Confirme a senha</label>
																		<input id="confirmacao" name="confirmacao" type="password" class="inputMedio" />
																	</div>
																	
																	
																	<div class="campo">
																		<label for="newsletter">Newsletter</label>
																		<input id="newsletter" name="newsletter" type="radio" checked="checked" value="sim" /> Sim, desejo receber!<br />
																		<input id="newsletter" name="newsletter" type="radio" value="nao" /> Não, obrigado.
																	</div>
																	<? }else{?>
																	<div class="campo">
																		<label for="nascimento">Aniversário</label>
																		<input id="nascimento" name="nascimento" type="text" value="<?=ex_data($cadastro->nascimento)?>" class="inputMedio" alt="date" /> <small>(Opcional)</small>
																	</div>
																	<div class="campo">
																		<label for="newsletter">Newsletter</label>
																		<input id="newsletter" name="newsletter" type="radio" <?=($cadastro->newsletter == 'sim' ? 'checked="checked"': "")?> value="sim" /> Sim, desejo receber!<br />
																		<input id="newsletter" name="newsletter" type="radio" <?=($cadastro->newsletter == 'nao' ? 'checked="checked"': "")?> value="nao" /> Não, obrigado.
																	</div>
																	<? }?>
																</div>
																<div class="formularioGrd">
																	<h3>Endereço de entrega</h3>
																	<div class="campo">
																		<label for="cep"><strong>CEP</strong></label>
																		<input id="cep" name="cep" type="text" value="<?=$cadastro->cep;?>" class="required inputMedio" alt="cep" />
																		<button onclick="carregaCep($('#cep').val())" class="cepButton awesome grey float-right" type="button">Preencher</button>
																	</div>
																	<div id="carregandoCEP"> </div>
																	<div class="clear"></div>
																	<div class="campo">
																		<label for="endereco"><strong>Endereço</strong></label>
																		<input id="endereco" name="endereco" type="text" value="<?=$cadastro->endereco;?>" class="required inputGrande" />
																	</div>
																	<div class="campo">
																		<label for="numero"><strong>Número</strong></label>
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
																			$db->query("select * from cidades where idestados = '".$cadastro->estados->id."' order by cidade");
																			while($res = $db->fetch()){
																										echo '<option value="'.$res['idcidades'].'"'.(($res['idcidades']==$cadastro->cidades->id)?' selected':'').'>'.$res['cidade'].'</option>';
																			}
																			?>
																		
																		</select>
																	</div>
																	<div class="campo">
																		<label for="referencia"><strong>Ponto de referência</strong></label>
																		<input id="referencia" name="referencia" type="text" value="<?=$cadastro->referencia;?>" class="required inputGrande" />
																	</div>
																	<div class="clear"> </div>
																	<? if(!$cadastro->conectado()){?>
																	<div class="campo" >
																		<button type="submit" class="awesome blue float-right">Continuar e finalizar o pedido.</button>
																	</div>
																	<? }else{ ?>
																	<div class="campo" >
																		<button type="submit" class="awesome blue float-right">Atualizar cadastro</button>
																	</div>
																	<? }?>
																</div>
															</form>
													</div>
											</div>
											<div class="clear"></div>