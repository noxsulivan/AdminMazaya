  <h2>
    <?=$canal->canal?>
  </h2>
    <?=$pagina->caminho(array($canal->canal => $canal->url))?>
    <?=$canal->texto?>
    <div id="formulario">
      <form action="<?=$pagina->localhost?>" method="post" id="formularioForm" onsubmit="return sendWindowForm('formulario' ,this)">
        <input name="acao" type="hidden" value="enviarOrcamento" />
        <div id="formPeq">
          <ul>
            <li>
              <label for="nome">Nome/Razão Social *</label>
              <input id="nome" name="nome" type="text" class="required inputField" />
            </li>
            <li>
              <label for="cnpj">CNPJ *</label>
              <input id="cnpj" name="cnpj" type="text" class="required inputField" />
            </li>
            <li>
              <label for="responsavel">Responsável</label>
              <input id="responsavel" name="responsavel" type="text" class="required inputField" />
            </li>
            <li>
              <label for="ddd">DDD *</label>
              <input id="ddd" name="ddd" type="text" class="required inputField inputPequeno" />
            </li>
            <li>
              <label for="fone">Telefone Comercial *</label>
              <input id="fone" name="fone" type="text" class="required inputField inputGrande" />
            </li>
            <li>
              <label for="email">E-mail *</label>
              <input id="email" name="email" type="text" class="required inputField validate-email" />
            </li>
            <li>
              <label for="endereco">Endereço *</label>
              <input id="endereco" name="endereco" type="text" class="required inputField" />
            </li>
            <li>
              <label for="cidade">Cidade</label>
              <input id="cidade" name="cidade" type="text" class="required inputField inputGrande" />
            </li>
            <li>
              <label for="estado">Estado</label>
              <input id="estado" name="estado" type="text" class="required inputField inputPequeno" />
            </li>
            <li>
              <label for="cep">CEP</label>
              <input id="cep" name="cep" type="text" class="required inputField inputMedio" />
            </li>
          </ul>
        </div>
        <div id="formGrd">
          <ul>
            <? if(is_array($_SESSION['itensCarrinho'])){
						foreach($_SESSION['itensCarrinho'] as $_id => $qtd){
							$produto = new objetoDb('produtos',$_id);
							?>
							<li>
							  <label for="quantidade">Item</label>
							  <input id="produto[<?=$produto->id?>][codigo]" name="produto[<?=$produto->id?>][codigo]" type="text" class="required inputField inputGrande" value="<?=$produto->codigo?><?=$produto->produto?> - <?=$produto->codigo?>" />
							</li>
							<li>
							  <label for="quantidade">Quantidade</label>
							  <input id="produto[<?=$produto->id?>][quantidade]" name="produto[<?=$produto->id?>][quantidade]" type="text" class="required inputField inputPequeno" value="<?=$qtd?>" />
							</li>

						<? }?>
            <? }else{?>
				<li>
				  <label for="folhas_por_bloco">Produtos desejados</label>
				  <textarea id="produtos_desejados" name="produtos_desejados" cols="" rows="" myValue="produtos_desejados *" class="required inputField"></textarea>
				</li>
				<li>
				  <label for="quantidade">Quantidade</label>
				  <input id="quantidade" name="quantidade" type="text" class="required inputField inputPequeno" />
				</li>
            <? }?>
          </ul>
        </div>
        <div id="formPeq">
          <ul>
            <li>
              <label for="mensagem">Mensagem *</label>
              <textarea id="mensagem" name="mensagem" cols="" rows="" myValue="Mensagem *" class="required inputField"></textarea>
            </li>
            <li> <a href="javascript:void(0);" class="radio" inputid="novidades" value="sim">Desejo receber a newsletter</a>
              <input name="novidades" id="novidades" type="hidden"/>
            </li>
            <li>
              <button type="submit" class="submitButton" id="submitButton">Enviar</button>
            </li>
          </ul>
        </div>
        <small style="display:none;" id="aviso">
        <? _e("Os campos marcados são obrigatórios.")?>
        </small>
      </form>
    </div>
				<br clear="all" />