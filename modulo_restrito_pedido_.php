<? $titulo = "Carrinho de compras"; ?><? //$ped = new objetoDb('pedidos',$_SESSION['idpedidos']); pre($ped);?>
<?
	if($pagina->id){
		adicionarCarrinho($pagina->id, 1, $convidado->id);
	}
?>
<h2><?=$titulo?></h2>
<div class="boxPrincipal" id="pedido">
	<div id="formulario">
		<? if($_SESSION['itensCarrinho']){?>
		<form action="https://pagseguro.uol.com.br/security/webpagamentos/webpagto.aspx" method="post" id="formPagSeguro" />
		
		<input type="hidden" name="idclientes" value="<?=$cadastro->id?>" />
		<? if(ereg('mazaya',$_SERVER['HTTP_HOST'])){ ?>
		<input type="hidden" name="email_cobranca" value="alexcouto@gmail.com" />
		<input type="hidden" name="tipo_frete" value="" />
		<? }else{?>
		<input type="hidden" name="email_cobranca" value="<?=$pagina->configs['email_pagseguro']?>" />
		<input type="hidden" name="tipo_frete" value="EN" />
		<? } ?>
		<input type="hidden" name="tipo" value="CP" />
		<input type="hidden" name="moeda" value="BRL" />
		<div id="carrinhoLista">
			<?=carregaCarrinhoFull();?>
		</div>
		<input type="hidden" name="ref_transacao" value="pedido_<?=$_SESSION['idpedidos']?>" />
		<input type="hidden" name="cliente_nome" value="<?=$cadastro->nome?>" />
		<input type="hidden" name="cliente_cep" value="<?=str_replace("-",'',$cadastro->cep)?>" />
		<input type="hidden" name="cliente_end" value="<?=$cadastro->endereco?>" />
		<input type="hidden" name="cliente_num" value="<?=$cadastro->numero?>" />
		<input type="hidden" name="cliente_compl" value="<?=$cadastro->complemento?>" />
		<input type="hidden" name="cliente_bairro" value="<?=$cadastro->bairro?>" />
		<input type="hidden" name="cliente_cidade" value="<?=$cadastro->cidade?>" />
		<input type="hidden" name="cliente_uf" value="<?=strtoupper($cadastro->estado)?>" />
		<input type="hidden" name="cliente_pais" value="BRA" />
		<?
		$ddd = substr($cadastro->telefone,1,2);
		$telefone = substr($cadastro->telefone,5);
		?>
		<input type="hidden" name="cliente_ddd" value="<?=$ddd?>" />
		<input type="hidden" name="cliente_tel" value="<?=$telefone?>" />
		<input type="hidden" name="cliente_email" value="<?=$cadastro->email?>" />
		</form>
		<? }else{ ?>
		<h3>Seu carrinho está vazio.</h3>
			<ul class="listaFuncoes">
				<li><a href="<?=$pagina->localhost?>Produtos" class="funcaoAtualizar funcao"> Continuar comprando </a></li>
			</ul>
		<? }?>
	</div>
	<div class="clear espaco"></div>
	<? if($convidado->id){ ?>
    <div class="sidebar24">
        <div id="formularioDedicatoria">
		<h3>Mensagem aos Noivos</h3>
        Junto a cada presente entregue pela Mesacor aos noivos, é anexado um cartão especial escrito a mão com uma dedicatória. Aproveite e escolha as suas palavras<br />
		<form action="<?=$pagina->localhost.$canal->url?>Enviar" method="post" id="dedicatoriaForm" onsubmit="return sendWindowForm('formularioDedicatoria' ,'dedicatoriaForm')">
			<input name="acao" type="hidden" value="enviarDedicatoria" />
			<div class="campo">
				<label for="dedicatoria">Dedicatória</label>
				<textarea id="dedicatoria" name="dedicatoria" cols="" rows="" class="inputGrande"></textarea>
			</div>
	<div class="clear"></div>
		<div class="campo">
			<label for="padrinhos">Nome(s) na assinatura</label>
			<input id="padrinhos" name="padrinhos" type="text" value="" class=" inputGrande" />
		</div>
			<div class="campo">
				<button type="submit" class="awesome orange"><strong>Salvar Dedicatória</strong></button>
			</div>
		</form>
	</div>
	</div>
	<div class="clear espaco"></div>
	<? }?>
	
	<? if(!$cadastro->conectado()){ ?>
	<h3>Para efetuar o pagamento é necessário que você esteja conectado.</h3>
		<a href="<?=$pagina->localhost.$canal->url?>Login" class="bt_log"> Fazer<br />login</a>
		<a href="<?=$pagina->localhost.$canal->url?>Cadastro" class="bt_sign"> Preencher<br />Cadastro</a>
	<? }elseif(count($_SESSION['itensCarrinho'])){ ?>
		<a href="#" class="bt_pay" onclick="$('formPagSeguro').submit();"> Efetuar<br />Pagamento </a>
		<a href="<?=$pagina->localhost?>Produtos" class="bt_go"> Continuar<br />comprando </a>
	<? }?>
</div>
<div class="clear espaco"></div>

<h3>* Envio Promocional - Tramontina Design Collection</h3>
<ul>
	<li>Produtos da linha Tramontina Design Collection serão enviados com frete grátis para compras acima de R$500,00 para os seguintes estados: SP, PR, SC e RS.</li>
	<li>Promoção válida até 30/05/2010</li>
</ul>
 
<h3>Importante:</h3>
<ul>
	<li>Produtos selecionados como presentes para noivas, não acrescentarão valor ao frete pois serão entregues em separado, conforme combinado.</li>
</ul>
<h3>Instruções:</h3>
<ul>
	<li>Para retirar um item de seu Carrinho clique em Excluir.</li>
	<li>Para alterar a quantidade de um item, digite o número no campo e clique no botão "Alterar" para calcular o novo valor da compra.</li>
	<li>Para finalizar a compra é necessário estar cadastrado e fazer login.</li>
	<li>Ítens com valor sob consulta não constarão no pedido final.</li>
</ul>