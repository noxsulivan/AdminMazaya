<?
include('../ini.php');
//header("HTTP/1.0 304 Not Modified");
$pagina = new layout('Home');



   // initialize ob_gzhandler function to send and compress data

   // send the requisite header information and character set
   header ("content-type: text/javascript; charset: UTF-8");
	//header('Content-Disposition: attachment; filename="'.diretorio($_SERVER['QUERY_STRING']).".jpg".'"');
	$_time = filemtime('carrinho.php');
	
	$gmdate_e = gmdate('D, d M Y H:i:s', $_time + 60*60*24*365 ) . ' GMT';
	header("Expires: $gmdate_e");
	
	$gmdate_c = gmdate('D, d M Y H:i:s', $_time ) . ' GMT';
	header("Last-Modified: $gmdate_c");
	
	header("Cache-Control: max-age=3600, must-revalidate");
	header("Pragma: cache");
	header("Cache-Control: store");

   // check cached credentials and reprocess accordingly
   //header ("Cache-Control: no-store, no-cache, must-revalidate");
   //header ("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
   //header ("Pragma: no-cache");
	
	
	

if(0){?><script><? }?>

	 function adicionar( _id ,_qtd, _noiva ){
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/adicionarCarrinho/'+_id+'/'+_qtd+','+_noiva,
			complete: function(resposta) {
				if(resposta.responseText.isJSON()){
					var res = $.parseJSON( resposta.responseText );
					if(res.status == 'ok'){
						pageTracker._trackEvent('Carrinho','Adicionar',_id,_qtd);
						carrega();
					}else{
						alert(res.mensagem)
						pageTracker._trackEvent('Carrinho','Tentativa de Adição',_id,_qtd);
					}
				}else{
					alert(resposta.responseText)
					pageTracker._trackEvent('Carrinho','Tentativa de Adição',_id,_qtd);
				}
			}
		});
	}
	function adicionarListaPresentes( _id ,_qtd ){
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/adicionarListaPresentes/'+_id+'/1',
			complete: function(resposta) {
					var res = $.parseJSON( resposta.responseText );
					if(res.status == 'ok'){
						carregaLista();
						//pageTracker._trackEvent('Lista','Adicionar à lista de Casamento',_id,_qtd);
					}else{
						alert(res.mensagem)
						//pageTracker._trackEvent('Lista','Tentativa de Adição à lista de Casamento',_id,_qtd);
					}
			}
		});
	}
	function adicionarListaChas( _id ,_qtd ){
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/adicionarListaChas/'+_id+'/1',
			complete: function(resposta) {
					var res = $.parseJSON( resposta.responseText );
					if(res.status == 'ok'){
						carregaListaChas();
						//pageTracker._trackEvent('Lista','Adicionar à lista de Casamento',_id,_qtd);
					}else{
						alert(res.mensagem)
						//pageTracker._trackEvent('Lista','Tentativa de Adição à lista de Casamento',_id,_qtd);
					}
			}
		});
	}
	function acrescentarLista( _id ,_qtd ){
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/acrescentarLista/'+_id+'/'+_qtd,
			complete: function(resposta) {
					var res = $.parseJSON( resposta.responseText );
					if(res.status == 'ok'){
						$('#produtosNoivasQtd_'+_id).html(res.mensagem);
					}else{
						alert(res.mensagem)
					}
			}
		});
	}
	function acrescentarListaCha( _id ,_qtd ){
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/acrescentarListaCha/'+_id+'/'+_qtd,
			complete: function(resposta) {
					var res = $.parseJSON( resposta.responseText );
					if(res.status == 'ok'){
						$('#produtosNoivasQtd_'+_id).html(res.mensagem);
					}else{
						alert(res.mensagem)
					}
			}
		});
	}
	function atualizarQuantidadeCarrinho( _id ,_qtd ){
		window.location = "<?=$pagina->localhost?>Cliente/Carrinho/"+_id+"|"+_qtd;
	}
	function atualizarQuantidade( _id ,_qtd ){
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/atualizarQuantidade/'+_id+'/'+_qtd,
			complete: function(resposta) {
					var res = $.parseJSON( resposta.responseText );
					if(res.status == 'ok'){
						$('#carrinhoAddForm').invoke('fade')
						carregaCarrinhoFull();
						atualizaFrete ( $('cep').value,$('pesoTotal').value );
					}else{
						alert(res.mensagem)
					}
			}
		});
	}
	function consultaFrete( _cep ,_peso ){
		$('#resultFrete').html('Aguarde, consultando...');
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/consultaFrete/'+_cep+'/'+_peso,
			complete: function(resposta) {
					var res = $.parseJSON( resposta.responseText );
					if(res.status == 'ok'){
							$('#resultFrete').html(res.mensagem);
							$('#totalGeral').html((parseFloat(res.pac_valor) + parseFloat($('#subTotal').val())).toFixed(2).replace('.',','));
					}else{
						alert(res.mensagem)
					}
			}
		});
	}
	function atualizaFrete(){
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/atualizaFrete/',
			complete: function(resposta) {
					var res = $.parseJSON( resposta.responseText );
					if(res.status == 'ok'){
							$('formatoFrete').html(res.mensagem);
							this.atualizaParcelamento ( );
					}else{
						alert(res.mensagem)
					}
			}
		});
	}
	function recuperarCupom( _cupom ){
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/recuperarCupom/'+_cupom,
			complete: function(resposta) {
					var res = $.parseJSON( resposta.responseText );
					if(res.status == 'ok'){
							$('resultCupom').html(res.mensagem);
							segue("cupom");
							pageTracker._trackEvent('Carrinho','Consulta de cupom válido',_cupom);
					}else{
										$("body").append('<div id="dialog">'+res.mensagem+'</div>');
										$("#dialog").dialog({
													modal: true,
													zIndex: 10000,
													buttons: {
														Ok: function() {
															$(this).dialog('destroy');
															$("#dialog").remove();
														}
													}
												});
						pageTracker._trackEvent('Carrinho','Consulta de cupom inválido',res.mensagem,1);
					}
			}
		});
	}
	function atualizaParcelamento( ){
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/atualizaParcelamento/',
			complete: function(resposta) {
					var res = $.parseJSON( resposta.responseText );
					if(res.status == 'ok'){
							$('parcelamento').html(res.mensagem);
					}
			}
		});
	}
	function mudaFormatoFrete( _formato ){
		if( _formato.getAttribute('format') == 'EN'){
			$('tipo_frete').val("PAC");
			$('parc_pac').slideDown();
			$('parc_sedex').hide();
		}
		if( _formato.getAttribute('format') == 'SEDEX'){
			$('tipo_frete').val("SEDEX");
			$('parc_sedex').slideDown();
			$('parc_pac').hide();
		}
		$('total').value = parseInt(_formato.value) + parseInt($F('pretotal'));
		
	}
	function marcarPresente(_id){
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/marcarPresente/'+_id
		});
	}
	function marcaVariacao(_id, _extra){
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/marcaVariacao/'+_id+'/'+_extra
		});
	}
	function removerItem( _id ) {
		
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/removerItemCarrinho/'+_id,
			complete: function() {
				//$('itemCarrinho_' + _id).blindUp();
				$("#produtosNoivas_"+_id).remove();
				window.location = "<?=$pagina->localhost?>Cliente/Carrinho/";
			}
		});
	}
	function removerItemLista( _id ) {
		
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/removerItemLista/'+_id,
			complete: function() {
				$("#produtosNoivas_"+_id).fadeOut();
			}
		});
	}
	function removerItemListaCha( _id ) {
		
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/removerItemListaCha/'+_id,
			complete: function() {
				$("#produtosNoivas_"+_id).remove();
			}
		});
	}
	function toggle( ){
				$('carrinho').toggle();
	}
	function carrega() {
		$('#carrinhoLista').load('<?=$pagina->localhost?>_Request/carregaCarrinhoMini',
			function() {
				$('#carrinho').slideDown();
			}
		);
	}
	function carregaLista(){
		$('#listaLista').load('<?=$pagina->localhost?>_Request/carregaListaMini',
			function() {
				$('#lista').slideDown();
			});
	}
	function carregaListaChas(){
		$('#listaListaChas').load('<?=$pagina->localhost?>_Request/carregaListaMiniChas',
			function() {
				$('#listaChas').slideDown();
			});
	}
	function carregaCarrinhoFull() {
		//$('#carrinhoLista').html('<div id="carregando">Carregando...</div>');
		$('#carrinhoLista').load('<?=$pagina->localhost?>_Request/carregaCarrinhoFull',
			function() {
				atualizaFrete();
			});
	}
	function passo( _div ){
		$(".carrinhoDiv").each(function(_ele){
			if(_ele.id != _div)
				_ele.blindUp();
			else
				_ele.blindDown();
		});
	}
	function segue( _passo ){
		//$('#passo_dados').html('<div id="carregando">Carregando...</div>');
		
										$("body").append('<div id="dialog" title="Aguarde">Carregando próxima etapa...</div>');
										$("#dialog").dialog({
													modal: true,
													height: 80
												});
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/segueCarrinho/'+_passo, 
			data: $('#carrinhoForm').serialize(),
			type: 'POST',
			complete: function(resposta) {
					$("#passo_dados").html( resposta.responseText);
					$("#dialog").dialog('destroy');
					$("#dialog").remove();
					$('.botao').button();
			}
		});
	}
	function processaCarrinho( _form ){
		$.ajax({
			url: '<?=$pagina->localhost?>_Request/processaCarrinho/',
			data: $(_form).serialize(),
			complete: function(resposta) {
					var res = $.parseJSON( resposta.responseText );
					if(res.status == 'ok'){
						<? if(!preg_match('/localhost/',$_SERVER['HTTP_HOST'])){?>
						pageTracker._trackPageview('Carrinho/Submeter');
						pageTracker._trackEvent('Carrinho','Fechar Compra');
						pageTracker._addTrans(res.trans[0],res.trans[1],res.trans[2],res.trans[3],res.trans[4],res.trans[5],res.trans[6],res.trans[7]);
						_dados = res.itens;
						_dados.each(function(_i){
											 pageTracker._addItem(_i[0],_i[1],_i[2],_i[3],_i[4],_i[5]);
											 });
						pageTracker._trackTrans();
						<? }?>
					}
			}
		});
	}
	
	
<? if(0){?></script><? }?>