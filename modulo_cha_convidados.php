
							<div id="formulario">
								<form action="<?=$pagina->localhost.$canal->url?>Cadastro" method="post" id="formularioForm" onsubmit="return sendWindowForm('formulario' ,'formularioForm')">
									<input name="acao" type="hidden" value="enviarConvites" />
									<div class="formulario">
										<h2>Indique seus convidados</h2>
                                        <? for($i=1;$i<=5;$i++){?>
										<div class="campo">
											<label for="par[<?=$i?>][nome]">Nome</label>
											<input id="par[<?=$i?>][nome]" name="par[<?=$i?>][nome]" type="text" value="" class="inputGrande" />
										</div>
										<div class="campo">
											<label for="par[<?=$i?>][email]">E-mail</label>
											<input id="par[<?=$i?>][email]" name="par[<?=$i?>][email]" type="text" value="" class="inputGrande" />
										</div>
										<div class="clear espaco"> </div>
                                        <? }?>
										<div class="campo" >
                                            <label>&nbsp;</label>
                                            <button type="submit" class="awesome orange float-right"><strong>Enviar convites por e-mail</strong></button>
										</div>
                                    	
									</div>
									<div class="formularioGrd">
										<h2>Fácil e eficiente</h2>
                                        <p>Envie para seus amigos um convite por e-mail para eles conhecerem sua lista de presentes na Mesacor.</p>
                                        <p>Ele será informado da facilidade e comodidade da compra do ítem online, e assim você também poderá acompanhar quais produtos foram adquiridos e por quem..</p
                                        ><? if(count($cliente->convites) > 0){?>
                                            <h3>Convites já enviados</h3>
                                            <ul id="convidados">
                                            <? foreach($cliente->convites as $convite){?>
                                            <li><?=$convite->nome?> &lt;<?=$convite->email?>&gt;</li>
                                            <? }?>
                                            </ul>
                                        <? }?>
									</div>
								</form>
							</div>