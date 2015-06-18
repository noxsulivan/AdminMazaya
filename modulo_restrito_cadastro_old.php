<? switch($pagina->acao){
  case "Alterar-senha":?>
						<h2>Alterar senha</h2>
						<div class="blocoTexto">
							<? switch($pagina->id){
																					case "Salvar":
																					
																						if($cadastro->trocarSenha($_POST["senhaAtual"],$_POST["nova1"],$_POST["nova2"])){ ?>
							<h3>Sua senha foi alterada com sucesso</h3>
							<? }else{ ?>
							<h3>
								<?=$cadastro->erro;?>
							</h3>
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
								<script type="text/javascript">
								new Validation('formularioForm');
								</script>
							</div>
							<? }?>
						</div>
<? break; ?>
<? case "Sair":?>
						<? $cadastro->sair($pagina->localhost);?>
						<? unset($_SESSION['convidado']);?>
<? break; ?>
<? case "Meus-dados":?>
<? default:?>
							<div id="formulario">
								<form action="<?=$pagina->localhost.$canal->url?>Cadastro" method="post" id="formularioForm" onsubmit="return sendWindowForm('formulario' ,'formularioForm')">
									<input name="acao" type="hidden" value="salvarDadosCadastro" />
									<div class="content24">
										<h2>Lista de noivas</h2>
										<p>Se você chegou até nossa loja virtual através da indicação de uma lista de noiva, selecione a seguir o casal.</p>
										<? if(!$convidado->id && !$cadastro->conectado()){?>
	                                        <div class="campo">
                                            <label for="nome_noiva">Nome da noiva</label>
                                            <select id="nome_noiva" name="nome_noiva">
                                                <option></option>
                                                <?
                                                $db->query("select * from clientes order by nome_noiva");
                                                while($res = $db->fetch()){
                                                    echo '<option value="'.$res['idclientes'].'">'.normaliza($res['nome_noiva'].' '.$res['sobrenome_noiva']).'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        ou
                                        <div class="campo">
                                            <label for="nome_noivo">Nome do noivo</label>
                                            <select id="nome_noivo" name="nome_noivo">
                                                <option></option>
                                                <?
                                                $db->query("select * from clientes order by nome_noivo");
                                                while($res = $db->fetch()){
                                                    echo '<option value="'.$res['idclientes'].'">'.normaliza($res['nome_noivo'].' '.$res['sobrenome_noivo']).'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
										<? }?>		
									</div>
									<div class="sidebar24">
										<h2>Seus dados</h2>
										<small>
										<? _e("Os campos marcados com * são obrigatórios.")?>
										</small>
										<div class="campo">
											<label for="nome">Nome *</label>
											<input id="nome" name="nome" type="text" value="<?=$cadastro->nome?>" class="required inputGrande" />
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
										  <label for="fone">DDD+Telefone *</label>
										  <input id="telefone" name="telefone" type="text" value="<?=$cadastro->telefone;?>" class="required inputMedio mascara" mask="telefone" maxlength="14" />
										</div>
										<div class="campo">
										  <label for="celular">DDD+Celular</label>
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
											<label for="email">Confirme a senha</label>
											<input id="confirmacao" name="confirmacao" type="password" class="required inputMedio" onchange="confirmaSenha()" />
										</div>
										<? }?>
										<div class="clear espaco"></div>
										<h2>Endereço</h2>
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
											<button type="submit" class="submitButton"><strong>Enviar cadastro</strong></button>
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
