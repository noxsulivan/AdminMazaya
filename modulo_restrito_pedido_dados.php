<?php /*?><ol id="passos" class="clear"><li>1. Carrinho</li>
<li class="current">2.Entrega</li>
<li>3. Frete</li>
<li>4. Forma de pagamento</li>
<li>5. Confirmação</li>
</ol><?php */?>

<form action="<?=$pagina->localhost.$canal->url.$pagina->acao?>" method="post" id="carrinhoForm" >
<?
foreach($_POST as $k=>$v){
	if(is_string($v))
	echo '<input type="hidden" name="'.$k.'" value="'.htmlentities($v).'" />';
}
?>
										<h2>Confirme seus dados</h2>
                                          <h3>Caso deseje que o pedido seja entregue em outro endereço, altere os campos necessários, informando também o nome da pessoa que irá receber a encomenda.</h3>
                                          <div class="formulario">
                                            <h3>Informaçoes pessoais</h3>
                                            <div class="campo">
                                              <label for="nome">Nome</label>
                                              <input id="nome" name="nome" type="text" value="<?=$_POST['nome'] ? $_POST['nome'] : $cadastro->nome?>" class="required inputGrande" />
                                            </div>
                                            <div class="campo">
                                              <label for="sobrenome">Sobrenome</label>
                                              <input id="sobrenome" name="sobrenome" type="text" value="<?=$_POST['sobrenome'] ? $_POST['sobrenome'] : $cadastro->sobrenome?>" class="required inputGrande" />
                                            </div>
                                            <div class="clear"></div>
                                            <div class="campo">
                                              <label for="fone">DDD+Telefone</label>
                                              <input id="telefone" name="telefone"  type="text" value="<?=$_POST['telefone'] ? $_POST['telefone'] : $cadastro->telefone;?>" class="required inputMedio mascara" mask="telefone" maxlength="14" />
                                            </div>
                                            <div class="clear"></div>
                                            <div class="campo">
                                              <label for="email">E-mail</label>
                                              <input id="email" name="email" type="text" value="<?=$_POST['email'] ? $_POST['email'] : $cadastro->email;?>" class="required validate-email inputGrande" />
                                            </div>
                                          </div>
                                          <div class="formularioGrd">
                                            <h3>Endereço de entrega</h3>
                                            <small>Apenas para ítens que não constam como presente de Casamento</small>
                                            <div class="campo">
                                              <label for="cep">CEP</label>
                                              <input id="cep" name="cep" type="text" value="<?=$_POST['cep'] ? $_POST['cep'] : $cadastro->cep;?>" class="inputMedio" />
                                            </div>
                                           <div class="campo">
                                              <label>&nbsp;</label>
                                              <button onclick="carregaCep($('#cep').val())" class="cepButton" type="button">Preencher automaticamente</button>
                                            </div>
                                            <div id="carregandoCEP"> </div>
                                            <div class="clear"></div>
                                            <div class="campo">
                                              <label for="endereco">Endereço</label>
                                              <input id="endereco" name="endereco" type="text" value="<?=$_POST['endereco'] ? $_POST['endereco'] : $cadastro->endereco;?>" class="inputMedio" />
                                            </div>
                                            <div class="campo">
                                              <label for="numero">Número</label>
                                              <input id="numero" name="numero" type="text" value="<?=$_POST['numero'] ? $_POST['numero'] : $cadastro->numero;?>" class="inputPequeno" />
                                            </div>
                                            <div class="campo">
                                              <label for="complemento">Complemento</label>
                                              <input id="complemento" name="complemento" type="text" value="<?=$_POST['complemento'] ? $_POST['complemento'] : $cadastro->complemento;?>" class="inputPequeno" />
                                            </div>
                                            <div class="campo">
                                              <label for="estado">Estado *</label>
                                              <select id="estado" name="estado" class="inputPequeno" onchange="carregaCidades(this.value)" >
                                                <option></option>
                                                <?
                                                                                        $db->query("select * from estados order by estado");
                                                                                        while($res = $db->fetch()){
                                                                                                                    echo '<option value="'.$res['idestados'].'"'.(($res['idestados']==($_POST['estado'] ? $_POST['estado'] : $cadastro->estados->id))?' selected':'').'>'.$res['nome'].'</option>';
                                                                                        }
                                                                                        ?>
                                              </select>
                                            </div>
                                            <div class="campo">
                                              <label for="cidade">Cidade *</label>
                                              <select name="cidade" id="cidade"  onchange="carregaBairros(this.value)"  class="inputMedio" >
                                              
                                              <option></option>
                                              <?
                                                                                        $db->query("select * from cidades where idestados = '".($_POST['estado'] ? $_POST['estado'] : $cadastro->estados->id)."' order by cidade");
                                                                                        while($res = $db->fetch()){
                                                                                                                    echo '<option value="'.$res['idcidades'].'"'.(($res['idcidades']==($_POST['cidade'] ? $_POST['cidade'] : $cadastro->cidades->id))?' selected':'').'>'.$res['cidade'].'</option>';
                                                                                        }
                                                                                        ?>
                                              </select>
                                            </div>
                                            <div class="campo">
                                              <label for="bairro">Bairro</label>
                                              <select name="bairro" id="bairro" class="inputMedio" >
                                              
                                              <option></option>
                                              <?
											  $db->query("select * from bairros where idcidades = '".($_POST['cidade'] ? $_POST['cidade'] : $cadastro->cidades->id)."' order by bairro");
											  while($res = $db->fetch()){
													echo '<option value="'.$res['idbairros'].'"'.(($res['idbairros']===($_POST['bairro'] ? $_POST['bairro'] : $cadastro->bairros->id))?' selected':'').'>'.$res['bairro'].'</option>';
											  }
											  ?>
                                              </select>
                                            </div>
                                          </div>
                                          <div class="clear espaco"></div>
                                          <a href="javascript:void(0);" onclick="segue('carrinho');" class="awesome orange float-left"> Voltar </a>
                                          <a href="javascript:void(0);" onclick="segue('frete');" class="awesome red float-right"> Próximo Passo &raquo; Frete &raquo;</a>

</form>