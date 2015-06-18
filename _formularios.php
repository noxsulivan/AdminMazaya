<div id="sidebar14">
<?
	$titulo = ' - Revendedor Tramontina - '.$canal->canal;
	if($cadastro->conectado() and ereg("49|52",$canal->canais->id))
		if($cadastro->tipos_de_restricoes->id == 1){
			$pagina->menu(array('pai'=>49,'nivel'=>2,'submenu'=>1,'proprio'=>49,'status'=>1));
		}else{
			$pagina->menu(array('pai'=>52,'nivel'=>2,'submenu'=>1,'proprio'=>52,'status'=>1));
		}
	else
		$pagina->menu(array('pai'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id),'nivel'=>2,'proprio'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id)))?>
</div>
<div id="content34">
<h2><?=$canal->canal?></h2>

	<div id="formulario" class="formularioGrd">
		<form action="<?=$pagina->localhost.$canal->url?>Enviar" method="post" id="formularioForm" onsubmit="return sendWindowForm('formulario' ,'formularioForm')">
			<input name="acao" type="hidden" value="enviarDados" />
			<div class="campo">
				<label for="remetente">Nome</label>
				<input name="remetente" type="text" value="" class="required inputGrande" />
			</div>
			<div class="campo">
				<label for="telefone">DDD Telefone</label>
				<input id="telefone" name="telefone" type="text" value="<?=$bol->telefone?>" class="inputMedio mascara" mask="telefone" maxlength="14" />
			</div>
			<div class="campo">
				<label for="email">E-mail</label>
				<input name="email" type="text" value="" class="required validate-email inputGrande" />
			</div>
			<div class="campo">
				<label for="cidade">Cidade</label>
				<input id="cidade" name="cidade" type="text" myvalue="Cidade" value="<?=$cadastro->dados->cidade;?>" class=" inputMedio" />
			</div>
      <div class="campo">
        <label for="estado" class="inputPequeno">Estado</label>
        <select id="estado" name="estado" class="inputField inputPequeno">
          <option></option>
          <?
                                        $db->query("select * from estados order by estado");
                                        while($res = $db->fetch()){
                                                                    echo '<option value="'.$res['nome'].'">'.$res['nome'].'</option>';
                                        }
                                        ?>
        </select>
      </div>
			<div class="campo">
				<label for="">Mensagem</label>
				<textarea id="mensagem" name="mensagem" cols="" rows="" class="required inputGrande"></textarea>
			</div>
			<div class="campo">
				<label for="">&nbsp;</label>
				<button type="submit" class="awesome orange">Enviar</button>
			</div>
		</form>
	</div>
    <?=$canal->texto?>
</div>