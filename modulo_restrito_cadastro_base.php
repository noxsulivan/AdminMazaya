<? switch($pagina->acao){
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
<h2>Cadastro de clientes</h2>
							<div id="formulario">
								<form action="<?=$pagina->localhost.$canal->url?>Cadastro" method="post" id="formularioForm" onsubmit="return sendWindowForm('formulario' ,'formularioForm')">
									<input name="acao" type="hidden" value="salvarDadosCadastro" />
									<div class="formulario">	
										<h3>Seus dados</h3>
										<small>
										<? _e("Os campos marcados com * são obrigatórios.")?>
										</small>
										<div class="campo">
											<label for="nome">Nome *</label>
											<input id="nome" name="nome" type="text" value="<?=$cadastro->nome?>" class="required inputGrande" />
										</div>
										<div class="campo">
											<label for="sobrenome">Sobrenome *</label>
											<input id="sobrenome" name="sobrenome" type="text" value="<?=$cadastro->sobrenome?>" class="required inputGrande" />
										</div>
										<div class="campo">
											<label for="nascimento">Nascimento *</label>
											<input id="nascimento" name="nascimento" type="text" value="<?=ex_data($cadastro->nascimento)?>" class="inputMedio" />
										</div>
										<div class="clear"></div>
										<div class="campo">
										  <label for="cpf">CPF/CNPJ *</label>
										  <input id="cpf" name="cpf" type="text" value="<?=$cadastro->cpf?>" class="required inputMedio mascara validate-cpf" mask="cpf" maxlength="14" />
										</div>
										
										<div class="campo">
										  <label for="fone">Telefone *</label>
										  <input id="telefone" name="telefone" type="text" value="<?=$cadastro->telefone;?>" class="required inputMedio mascara" mask="telefone" maxlength="14" />
										</div>
										<div class="campo">
										  <label for="celular">Celular</label>
										  <input id="celular" name="celular" type="text" value="<?=$cadastro->celular;?>" class="inputMedio mascara" mask="telefone" maxlength="14" />
										</div>
										<div class="clear"></div>
										<div class="campo">
											<label for="email">E-mail válido *</label>
											<input id="email" name="email" type="text" value="<?=$cadastro->email;?>" class="required validate-email inputGrande" />
										</div>
										<? if(!$cadastro->conectado()){?>
										<div class="campo">
											<label for="email">Senha</label>
											<input id="senha" name="senha" type="password" class="required inputMedio" />
										</div>
										<div class="campo">
											<label for="email">Confirme</label>
											<input id="confirmacao" name="confirmacao" type="password" class="required inputMedio" onchange="confirmaSenha()" />
										</div>
										<? }?>
									</div>
									<div class="formularioGrd">
										<h3>Endereço</h3>
										<div class="campo">
											<label for="cep">CEP</label>
											<input id="cep" name="cep" type="text" value="<?=$cadastro->cep;?>" class="inputMedio" />
										</div>
										<div class="campo">
											<label>Preencher automaticamente</label>
											<button onclick="consultaCEP.carregaCep($('cep').value)" class="cepButton" type="button">Buscar</button>
										</div>
										<div id="carregandoCEP"> </div>
										<div class="clear"></div>
										<div class="campo">
											<label for="endereco">Endereço</label>
											<input id="endereco" name="endereco" type="text" value="<?=$cadastro->endereco;?>" class="inputGrande" />
										</div>
										<div class="campo">
											<label for="numero">Número</label>
											<input id="numero" name="numero" type="text" value="<?=$cadastro->numero;?>" class="inputPequeno" />
										</div>
										<div class="campo">
											<label for="complemento">Complemento</label>
											<input id="complemento" name="complemento" type="text" value="<?=$cadastro->complemento;?>" class="inputPequeno" />
										</div>
										<div class="campo">
											<label for="estado">Estado *</label>
											<select id="idestados" name="idestados" class="inputPequeno" onchange="consultaCEP.carregaCidades(this.value)">
												<option></option>
												<?
												$db->query("select * from estados order by estado");
												while($res = $db->fetch()){
																			echo '<option value="'.$res['idestados'].'"'.(($res['idestados']==$cadastro->estados->id)?' selected':'').'>'.$res['nome'].'</option>';
												}
												?>
											</select>
										</div>
										<div class="campo">
											<label for="cidade">Cidade *</label>
											<select name="idcidades" id="idcidades"  onchange="consultaCEP.carregaBairros(this.value)"  class="inputMedio" />
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
											<label for="bairro">Bairro</label>
											<select name="idbairros" id="idbairros" class="inputMedio" />
												<option></option>
												<?
												$db->query("select * from bairros where idcidades = '".$cadastro->cidades->id."' order by bairro");
												while($res = $db->fetch()){
																			echo '<option value="'.$res['idbairros'].'"'.(($res['idbairros']==$cadastro->bairros->id)?' selected':'').'>'.$res['bairro'].'</option>';
												}
												?>
											
											</select>
										</div>
										<div class="clear"> </div>
										<div class="campo" >
                                            <button type="submit" class="botao orange float-right">Enviar cadastro</button>
										</div>
									</div>
								</form>
							</div>
							<script>
							<? //if($cadastro->estados->id && $cadastro->cidades->id) echo "consultaCEP.carregaCidades( ".$cadastro->estados->id." , ".$cadastro->cidades->id.")"; ?>
							<? //if($cadastro->cidades->id && $cadastro->bairros->id) echo "consultaCEP.carregaBairros( ".$cadastro->cidades->id." , ".$cadastro->bairros->id.")"; ?>
							</script>
<? break; ?>
<? } ?>
