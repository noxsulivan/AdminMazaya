<?
if($cadastro->erro){?>
                          <h2>Ocorreu um erro </h2>
                          <h3>
                            <?=$cadastro->erro?>
                          </h3>
<? } ?>

<? if($cadastro->conectado()){ ?>
<? //if($cadastro->id){ ?>
						<? include("modulo_restrito_pedido.php");?>

<? }else{?>
                        <h2>:) Identificação</h2>
                        <?=$canal->texto?>
                        <p>Para realizar o pedido você deve estar cadastrado no site, caso seja sua primeira compra <a href="<?=$pagina->localhost?>Cliente/Cadastro">clique aqui</a>. Se já possui cadastro informe seu email e sua senha e clique em "Continuar".</p>
                        <div class="formulario">
                          <h3>Já é cliente nosso? Faça o login:</h3>
                          
                                <form action="<?=$pagina->localhost.$canal->url?>Login" method="post" id="formularioForm">
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
                                  <button type="submit" class="awesome orange">Entrar</button>
                                  </div>
                                  <div class="campo ">
                                            <label for="">&nbsp;</label>
                                            <a href="<?=$pagina->localhost.$canal->url?>Lembrar-senha">Esqueci minha senha</a>
                                  </div>
                                </form>
                        
                        </div>
                        <div class="formularioGrd">
                          <h3> Cadastre-se </h3>
                          <p>Se você ainda não está cadastrado em nosso site, preencha seus dados. É simples, rápido, gratuito e sem compromisso.</p>
                                <form action="<?=$pagina->localhost.$canal->url?>Login" method="post" id="formularioForm">
                                  <div class="campo clear">
                                            <a class="awesome orange" href="<?=$pagina->localhost.$canal->url?>Cadastro"> Preencher cadastro </a>
                                  </div>
                                </form>
                        </div>
                        <div class="clear"></div>
<? } ?>