<?
	include_once('../ini.php');
	$pagina = new layout('Home');
	
	

	session_start();
	@error_reporting(E_ERROR | E_WARNING | E_PARSE);
	
	
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


	// Check if it supports gzip
	if (isset($_SERVER['HTTP_ACCEPT_ENCODING']))
		$encodings = explode(',', strtolower(preg_replace("/\s+/", "", $_SERVER['HTTP_ACCEPT_ENCODING'])));

	if ((in_array('gzip', $encodings) || in_array('x-gzip', $encodings) || isset($_SERVER['---------------'])) && function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')) {
		$enc = in_array('x-gzip', $encodings) ? "x-gzip" : "gzip";
		$supportsGzip = true;
	}
ob_start();

if(0){?><script><? }?>


			var option = {
				rules: { 
						senha: { 
							required: false, 
							minlength: 5 
						}, 
						confirmacao: { 
							required: false, 
							minlength: 5, 
							equalTo: "#senha" 
						}, 
						email: { 
							required: true, 
							email: true
						},
						nome: {
						  required: "#pessoaFisica:checked"
						},
						cpf: {
						  required: "#pessoaFisica:checked"
						},
						rg: {
						  required: "#pessoaFisica:checked"
						},
						razao_social: {
						  required: "#pessoaJuridica:checked"
						},
						cnpj: {
						  required: "#pessoaJuridica:checked"
						}
					}, 
				messages: {
					nome: "Este campo &eacute; obrigat&oacute;rio.",
					cpf: "Este campo &eacute; obrigat&oacute;rio.",
					rg: "Este campo &eacute; solicitado pela transportadora.",
					referencia: "Este campo &eacute; solicitado pela transportadora.",
					telefone: "Este campo &eacute; obrigat&oacute;rio.",
					telefone_alternativo: "Este campo &eacute; obrigat&oacute;rio.",
					endereco: "Este campo &eacute; obrigat&oacute;rio.",
					numero: "Este campo &eacute; obrigat&oacute;rio.",
					remote: "Please fix this field.",
					email: "Este e-mail n&atilde;o &eacute; v&aacute;lido.",
					url: "Please enter a valid URL.",
					date: "Please enter a valid date.",
					equalTo: "Digite igual ao campo Senha.",
					cep: {
						required: "Por favor, informe o seu CEP",
						minlength: "Preencha todos os 8 dígitos do seu CEP"
					}
				}
			}
			
			
function openWindowForm( obj ){
				$.ajax({
					  url: obj.href,
					  dataType: 'html',
					  cache: false,
					  type: 'POST',
					  complete: function(resposta) {
										$("body").append('<div id="dialogForm">'+resposta.responseText+'</div>');
										$("#dialogForm").dialog({
													modal: true,
													zIndex: 10000,
													height: 450,
													width: 400,
													buttons: {
														Cancel: function() {
														$( this ).dialog( "close" );
														},
														'Enviar': function() {
														$( "#formEnviarForm" ).submit();
														}
													}
													
													
													
												});
					  }});
				return false;
}

function sendWindowForm( divForm, _form ){
	
	_form = $('#'+_form);
	
	if($(_form).validate(option).form()){
		
		
			divResp = 'emailResponse'+divForm;
		
			if($('#'+divResp).length < 1){
				$(_form).append('<div id="'+divResp+'" class="emailResponse"><div class="emailResponseEnviando">Enviando...</div></div>');
			}
			$.ajax({
					  url: '<?=$pagina->localhost?>_Request/',
					  data: _form.serialize(),
					  dataType: 'json',
					  cache: false,
					  type: 'POST',
					  complete: function(resposta) {
						  try
							{
								$("#dialogForm").dialog;
								res = jQuery.parseJSON( resposta.responseText );
								$(divResp).html('');
								if(res.status == 'ok'){
									
									if(res.mensagem){
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
										$('#'+divResp).html('<div class="emailResponseEnviada">'+res.mensagem+'</div>');
										$.fancybox.close( )
									}else{
										$("body").append('<div id="dialog">'+res.erro+'</div>');
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
										$('#'+divResp).html('<div class="emailResponseErro">Tente novamente</div>');
										try{
											Recaptcha.reload();
										}catch(e){
										};
									}
									if(res.script)
										eval(res.script);
								}else{
										$("body").append('<div id="dialog">'+res.mensagem+'</div>');
										$("#dialog").dialog({
													modal: true,
													buttons: {
														Ok: function() {
															$(this).dialog('destroy');
															$("#dialog").remove();
														}
													}
												});
										$('#'+divResp).html('<div class="emailResponseErro">Tente novamente</div>');
										try{
											Recaptcha.reload();
										}catch(e){
										};
								}
							}
							catch(e)
							{
							  $("body").append('<div id="dialog">'+resposta.responseText+'</div>');
							  $("#dialog").dialog({
										  modal: true,
										  buttons: {
											  Ok: function() {
												  $(this).dialog('destroy');
												  $("#dialog").remove();
											  }
										  }
									  });
										$('#'+divResp).html('<div class="emailResponseErro">Tente novamente</div>');
							}
					  }
			});
	}
		
	return false;
}
$(document).ready(function() {
							
							
							$( ".tabs" ).tabs();
							
							$('#bannerPrincipal').cycle({
									fx: 'fade',
									timeout: 5000
							});
							$('.botao').button();
							
							$(".fancybox").fancybox();
							
//							$('#star').raty({
//											path:"<?=$pagina->localhost?>_shared/_images",
//											score:8,
//											number:10,
//											click: function(score, evt) {
//												$.ajax({
//														  url: '<?=$pagina->localhost?>_Request/vote/'+score+'@'+2,
//														  complete: function(resposta) {
//															$("body").append('<div id="dialog">Obrigado pelo seu voto.</div>');
//															$("#dialog").dialog({
//																		modal: true,
//																		zIndex: 10000,
//																		buttons: {
//																			Ok: function() {
//																				$(this).dialog('destroy');
//																				$("#dialog").remove();
//																			}
//																		}
//																	});
//														  }
//													   });
//												return score;
//											  }
//											});
						   
			
			
			//$('input[type=text]').setMask();
			
		   $(".data").mask("99/99/9999");
		   //$(".fone").mask("(99) 9999-9999");
		   $(".cep").mask("99999-999");
		   $(".cpf").mask("999.999.999-99");
		   $(".cnpj").mask("99.999.999/9999-99");
		   $(".cupom").mask("*****-*****-*****");

			
			$(".cat").mouseover(function(){
										 $(this).find('ul').slideDown(300);
										 _gaq.push(['_trackEvent', 'SubMenu', 'Expandir', $(this).name]);
										 $(this).find('ul').delay(10000).slideUp(300);
										 });
			
			$("#categorias ul").menu();
			
			
			
			$(".hipercategoria").mouseenter(
										function(){
										 $(this).find('ul').show();
										 })
								.mouseleave(
										function(){
										 $(this).find('ul').hide();
										 }
										);

			
			$("#cadastreseForm").validate(option);
			$("#formularioFormCadastro").validate(option);
			$("#formularioCadastro").validate(option);
			
			$(".listagemProdutosMini").carouFredSel({
													scroll      : 1,
													auto    : {
														pauseOnHover    : "immediate"
													}});

									$("#foo2_prev").hover(function() {
										$(".listagemProdutosMini").trigger("configuration", ["direction", "right"]);
									}).click(function() {
										$(".listagemProdutosMini").trigger("play");
										return false;
									});
									
									$("#foo2_next").hover(function() {
										$(".listagemProdutosMini").trigger("configuration", ["direction", "left"]);
									}).click(function() {
										$(".listagemProdutosMini").trigger("play");
										return false;
									});

			
			$('#listadeProdutos').isotope({
				  getSortData : {
					  produto : function ( $elem ) {
						return $elem.attr('data-produto');
					  },
					  codigo : function ( $elem ) {
						return $elem.attr('data-codigo');
					  },
					  preco : function ( $elem ) {
						return parseInt($elem.attr('data-preco'));
					  },
					  linha : function ( $elem ) {
						return $elem.attr('data-linha');
					  }
				  }});
		  
							  
			$('#sort-by a').click(function(){
			  var sortName = $(this).attr('data-option-value');
			  var asc = $(this).attr('data-option-asc')
			  $('#listadeProdutos').isotope({ sortBy : sortName, sortAscending : asc });
			
			  return false;
			});
			

			
			 $(window).scroll(function(){
			
			  mostraBusca();
			 });
			mostraBusca();
			
//			$('#listadeProdutos').masonry({
//				itemSelector: '.produtosDestaques',
//				columnWidth: 184,
//				isFitWidth: true,
//				animate: true,
//				animationOptions: {
//					duration: 300,
//					easing: 'linear',
//					queue: false
//				  }
//			  });
			
			$("#boxNewsletter input, #boxCadastroNewsletter input").focusin(function() {
										if($(this).val() == "" || $(this).val() == $(this).attr("alt"))
											$(this).val("");
									})
									.focusout(function(){
										if($(this).val() == "" || $(this).val() == $(this).attr("alt"))
											$(this).val($(this).attr("alt"))
									});

			
				$(".tipo_frete").change(function(){
					$(".pedidototal").hide();
					$(".parc").hide();
					$(".boleto").hide();
					$("#"+"ped"+$(this).val()).show();
					$("#"+"bol"+$(this).val()).show();
					$("#"+"parc"+$(this).val()).show();
				});
			
						   $(".produtosDestaques").hover(
							  function () {
								$(this).addClass("shdw").find(".botaoComprar").show();
								$(this).find(".produtosDestaquesFoto").css({'opacity':1});
								$(this).find(".produtosDestaquesBoxSocial").show();
							  }, 
							  function () {
								$(this).removeClass("shdw").find(".botaoComprar").hide();
								$(this).find(".produtosDestaquesFoto").css({'opacity':0.8});
								$(this).find(".produtosDestaquesBoxSocial").hide();
							  }
							);
						   
						   $("#banner-wrapper > div").hover(
							  function () {
								$(this).css({'box-shadow': '0 0px 20px rgba(0,0,0,0.9)', 'zIndex':1000});
							  }, 
							  function () {
								$(this).css({'box-shadow': '0 0 0 rgba(0,0,0,0.5)', 'zIndex':0});
							  }
							);
						   
						   $( "#campobusca1" ).autocomplete({
								source: '<?=$pagina->localhost?>_Request/Busca/',
								minLength: 3,
								delay: 100,
								select: function( event, ui ) {
									window.location = ui.item.value;
								}
							}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
									var inner_html = '<a><div class="list_item_container"><div class="image"><img src="' + item.img + '"></div><h3 class="produto">' + item.label + '<br>' + item.codigo + '</h3></div></a>';
									return $( "<li></li>" )
										.data( "item.autocomplete", item )
										.append(inner_html)
										.appendTo( ul );
							};		
							
							
			//$("a[href='"+window.location+"']").parent().parent().show()
			//$("a[href="+window.location+"]").addClass('current');
			//$("a[href="+window.location+"]").next().next().toggle();
	}
)
function enviaBusca( _form ){
	window.location = $(_form).attr('action')+'/'+$(_form).find('input').val();
	return false;
}

function mostraBusca(){
	var y = $(window).scrollTop();
			  if( y > 108 ){
			   // if we are show keyboardTips
			   $("#hipercategorias").addClass("barraHipercategoriasOn");
			  } else {
			   $('#hipercategorias').removeClass("barraHipercategoriasOn");
			  }
			  
			  if( y > 353 ){
			   // if we are show keyboardTips
			   $("#menu").addClass("menuOn");
			  } else {
			   $('#menu').removeClass("menuOn");
			  }
			  
}

	function carregaCep( _cep ){
		$('#carregandoCEP').html("Consultando CEP " + _cep).fadeIn();
		$.ajax({
			url: '<?=$pagina->localhost?>_Request' + '/carregaCEP/'+_cep, 
			complete: function(resposta){
					var res = $.parseJSON( resposta.responseText );
					if(res.status == 'ok'){
						if(res.logradouro){
							$('#endereco').val(res.logradouro);
						}
						$('#carregandoCEP').html('Selecionando estado');
						if(res.estado){
							//$('#idestados').selectedIndex = res.estado;
							$('#estado').val(res.estado);
						}
						if(res.bairro){
							$('#bairro').val(res.bairro);
						}
						
						$('#carregandoCEP').html('Selecionando cidade');
						if(res.cidade){
							$('#carregandoCEP').html('Buscando lista de cidades');
							$('#cidade').load('<?=$pagina->localhost?>_Request' + '/carregaCidades/'+res.estado+"/"+res.cidade,
								function() {
								});
						}
						$('#carregandoCEP').html('').fadeOut();
					}else{
						$('#carregandoCEP').html(res.mensagem);
					}
			}
		});
		
	}
	function carregaCidades( _estado , _preSeleciona ){
		$('#carregandoCEP').html("Carregando lista de cidades").show();
		$('#cidade').html("<option value=''>Carregando</option>");
		$('#cidade').load('<?=$pagina->localhost?>_Request' + '/carregaCidades/'+_estado+"/"+_preSeleciona,
			function() {
				$('#carregandoCEP').html('Carregando...').hide();
			});
		$('#bairro').html("<option value=''>Selecione uma cidade</option>");
		
	}
	
	function carregaBairros( _cidade , _preSeleciona){
		$('#carregandoCEP').html("Carregando lista de bairros").show();
		$('#bairro').html("<option value=''>Carregando</option>");
		$('#bairro').load('<?=$pagina->localhost?>_Request' + '/carregaBairros/'+_cidade+"/"+_preSeleciona,
			function() {
				$('#carregandoCEP').html('Carregando...').hide();
			});
		
	}
	
	function redirecionaNoiva( _noiva ){
		window.location='http://mesacor.com.br/Lista-de-Casamento/Presentes/' + _noiva;
		return false;
	}


<? if(0){?></script><? }


//pre($db);
$timeEnd = microtime(true);
header('X-Database-Objects-Createds: '.count($_SESSION['objetos']));
header('X-Server-Elapsed-Time: '.round($timeEnd-$timeIni,2));
header('X-Server-Memory-Usage: '.round(memory_get_usage()/1024)."kb");
//header('X-Server-Memory-Peak-Usage: '.round(memory_get_peak_usage()/1024));
$content = ob_get_clean();

	if ($supportsGzip) {
			header("Content-Encoding: " . $enc);
			$cacheData = gzencode($content, 9, FORCE_GZIP);
			header('Content-Length: '.strlen($cacheData));
		echo $cacheData;
	} else {
			header('Content-Length: '.strlen($content));
		echo $content;
	}
?>