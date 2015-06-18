<?

switch($pagina->acao){
case "Salvar":
	$ret = salvarDadosCadastro();
	echo $ret['mensagem'];
	?>
    
										<div class="campo" >
                                            <button type="submit" class="botao orange float-right">Continuar e finalizar o pedido.</button>
										</div>
	
	<?
break;
  case "Alterar-senha":?>
						<h2>Alterar senha</h2>
							<? switch($pagina->id){
								case "Salvar":
									if($_REQUEST['chave']){
										$id = $db->fetch("select idcadastros from cadastros where chave = '".$_REQUEST['chave']."'",'idcadastros');
										$cli = new objetoDb('cadastros',$id);
										if($_POST["nova1"] == $_POST["nova2"])
										$_POST['senha'] = md5($_POST["nova1"]);
										$_POST['chave'] = '';
										if($db->editar('cadastros',$cli->id)){?>
										<h3>Sua senha foi alterada com sucesso</h3>
                                        <a href="<?=$pagina->localhost.$canal->url?>" class="botao orange float-left">Clique aqui para acessar seu cadastro</a>
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
                                            <button type="submit" class="botao orange"><strong>Salvar nova senha</strong></button>
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
									<input name="acao" type="hidden" value="enviarSenhaCadastro" />
									<div class="formulario">
										<div class="campo">
											<label for="email">E-mail válido *</label>
											<input id="email" name="email" type="text" value="<?=$cliente->email;?>" class="required validate-email inputGrande" />
										</div>
                                        <div class="campo">
                                        	<label for="">&nbsp;</label>
                                            <button type="submit" class="botao orange"><strong>Enviar instruções</strong></button>
                                        </div>
									</div>
								</form></div>
<? break; ?>
<? case "Sair":?>
						<? $cadastro->sair($pagina->localhost);?>
						<? unset($_SESSION['convidado']);?>
<? break; ?>
<? case "Meus-dados":?>
<? default:?>
<h2>Informações de entrega</h2>
							<div id="formulario">
								<form action="<?=$pagina->localhost.$canal->url?>Salvar" method="post" id="formularioForm" onsubmit="return sendWindowForm('formulario' ,'formularioForm')">
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
                                                <input id="nome" name="nome" type="text" value="<?=$cadastro->nome?> <?=$cadastro->sobrenome?>" class="inputGrande" />
                                            </div>
<?php /*?>                                            <div class="campo">
                                                <label for="sobrenome">Sobrenome *</label>
                                                <input id="sobrenome" name="sobrenome" type="text" value="<?=$cadastro->sobrenome?>" class="inputGrande" />
                                            </div>
<?php */?>                                            <div class="clear"></div>
                                            <div class="campo">
                                              <label for="cpf"><strong>CPF</strong></label>
                                              <input id="cpf" name="cpf" type="text" value="<?=$cadastro->cpf?>" class="inputMedio cpf" alt="cpf" maxlength="14" />
                                            </div>
                                            <div class="campo">
                                              <label for="rg"><strong>RG</strong></label>
                                              <input id="rg" name="rg" type="text" value="<?=$cadastro->rg?>" class="inputMedio" maxlength="14" />
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
											  <label for="telefone"><strong>Telefone</strong></label>
											  <input id="telefone" name="telefone" type="text" value="<?=$cadastro->telefone;?>" class="required inputMedio fone" alt="phone" maxlength="14" />
											</div>
<?php /*?>											<div class="campo">
											  <label for="telefone_alternativo"><strong>Outro</strong></label>
											  <input id="telefone_alternativo" name="telefone_alternativo" type="text" value="<?=$cadastro->telefone_alternativo;?>" class="required inputMedio fone" alt="phone" maxlength="14" />
											</div>
<?php */?><?php /*?>										<div class="campo">
										  <label for="celular">Celular</label>
										  <input id="celular" name="celular" type="text" value="<?=$cadastro->celular;?>" class="inputMedio mascara" mask="telefone" maxlength="14" />
										</div>
<?php */?>										<div class="clear"></div>
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
											<div class="campoDiv">
											<input id="newsletter" name="newsletter" type="radio" checked="checked" value="sim" /> Sim, desejo receber!<br />
                                            <input id="newsletter" name="newsletter" type="radio" value="nao" /> Não, obrigado.
											</div>
										</div>
										<? }else{?>
                                        <div class="campo">
                                            <label for="nascimento">Aniversário</label>
                                            <input id="nascimento" name="nascimento" type="text" value="<?=ex_data($cadastro->nascimento)?>" class="inputMedio data" alt="date" /> <small>(Opcional)</small>
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
											<input id="cep" name="cep" type="text" value="<?=$cadastro->cep;?>" class="required inputMedio cep" alt="cep" />
											<button onclick="carregaCep($('#cep').val())" class="cepButton botao grey float-right" type="button">Preencher</button>
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
<?php /*?>										<div class="campo">
											<label for="referencia"><strong>Ponto de referência</strong></label>
											<input id="referencia" name="referencia" type="text" value="<?=$cadastro->referencia;?>" class="required inputGrande" />
										</div>
<?php */?>	<?php /*?>									<div class="campo">
											<label for="bairro">Bairro</label>
											<select name="bairro" id="bairro" class="inputMedio" />
												<option></option>
												<?
												$db->query("select * from bairros where idcidades = '".$cadastro->cidades->id."' order by bairro");
												while($res = $db->fetch()){
																			echo '<option value="'.$res['idbairros'].'"'.(($res['idbairros']==$cadastro->bairros->id)?' selected':'').'>'.$res['bairro'].'</option>';
												}
												?>
											
											</select>
										</div><?php */?>
										<div class="clear"> </div>
                                        
										<div class="campo" >
                                        <label for="">&nbsp;</label>
											<?php
                                              require_once('recaptchalib.php');
                                              $publickey = "6LdMHPMSAAAAAH4ul0-m6dQ_Fi3-UorLieEihAZr"; // you got this from the signup page
                                              echo recaptcha_get_html($publickey);
                                            ?>
										</div>
										<div class="clear"> </div>
										<? if(!$cadastro->conectado()){?>
										<div class="campo" >
				<label for="">&nbsp;</label>
                                            <button type="submit" class="awesome orange">Continuar e finalizar o pedido.</button>
										</div>
                                        <? }else{ ?>
										<div class="campo" >
                                            <button type="submit" class="awesome orange ">Atualizar cadastro</button>
										</div>
                                        <? }?>
									</div>
								</form>
							</div>
										<div class="clear"> </div>
<? break; ?>
<? } ?>
