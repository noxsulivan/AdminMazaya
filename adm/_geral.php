<? 
//$timeIni = microtime(true);
$_tmp = explode("/",$_SERVER['REQUEST_URI']);

	$include = '';
	$pattern = "/\/([a-z]+)\/(.)*/i";
	$includeIni = $_SERVER['DOCUMENT_ROOT'].'/ini.php';
	
//die(__LINE__."aqui".$includeIni);
include($includeIni);

//set_time_limit ( 0 );

	
//$queryString = ereg('public_html',$_SERVER["QUERY_STRING"]) ? '' : $_SERVER["QUERY_STRING"];
//$admin = new admin($queryString);
$admin = new admin('');

$usuario = new usuario();
	
//ob_start();
if(0){?><script><? }?>
											var usuarioChave = '<?=$_SERVER['QUERY_STRING']?>';
											
											var dadosAjax;
											
											var ajaxManager = $.manageAjax.create('cacheQueue', {
												queue: 'clear',
												abortOld: true
											});

											var uploadFoto;
											var settingsFoto = {
																				upload_url : "Nucleo/uploadFotos",
																				flash_url : "<?=$admin->ABSURL?>_shared/_swf/swfupload.swf",
																				 
																				file_post_name : "arquivo",
																				 
																				file_types : "*.jpg;*.png;*.gif",
																				file_types_description : "Imagens JPG,GIF ou PNG",
																				file_upload_limit : 0,
																				post_params : {
																					"chave" : usuarioChave
																				},
																				file_size_limit : 2000,
																				debug : false,
																				
																				button_image_url : "imagens/buttons/upload.png",	// Relative to the SWF file
																				button_placeholder_id : "buttonPlaceholderFoto",
																				button_width: 220,
																				button_height: 25,
																				button_text : ' <span class="button"> Selecione 1 ou mais imagens </span>',
																				button_text_style : '.button { font-family: Tahoma; font-size: 12px; color: #ffffff; }',
																				button_text_top_padding: 0,
																				button_text_left_padding: 26,
																				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
																				button_cursor: SWFUpload.CURSOR.HAND,
											
																				file_queue_error_handler : fileQueueError,
																				file_queued_handler : fileQueued,
																				file_dialog_start_handler: fileDialogStart,
																				file_dialog_complete_handler : fileDialogComplete,
																				
																				upload_start_handler : uploadStart,
																				upload_progress_handler : uploadProgress,
																				upload_error_handler : uploadError,
																				upload_success_handler : uploadSuccessFotos,
																				upload_complete_handler : uploadComplete
																				
												};
											var uploadArquivo;
											var settingsArquivo = {
																				 upload_url : "Nucleo/uploadArquivo",
																				 flash_url : "<?=$admin->ABSURL?>_shared/_swf/swfupload.swf",
																				 
																				 file_post_name : "arquivo",
																				 
																				file_types : "*.*",
																				file_types_description : "Qualquer tipo",
																				file_upload_limit : "0",
																				post_params : {
																					"chave" : usuarioChave
																				},
																				
																				button_image_url : "imagens/buttons/upload.png",	// Relative to the SWF file
																				button_placeholder_id : "buttonPlaceholderArquivo",
																				button_width: 220,
																				button_height: 25,
																				button_text : ' <span class="button"> Selecione 1 ou mais arquivos</span>',
																				button_text_style : '.button { font-family: Tahoma; font-size: 12px; color: #ffffff; }',
																				button_text_top_padding: 0,
																				button_text_left_padding: 26,
																				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
																				button_cursor: SWFUpload.CURSOR.HAND,
											
																				file_queue_error_handler : fileQueueError,
																				file_queued_handler : fileQueued,
																				file_dialog_start_handler: fileDialogStart,
																				file_dialog_complete_handler : fileDialogComplete,
																				
																				upload_start_handler : uploadStart,
																				upload_progress_handler : uploadProgress,
																				upload_error_handler : uploadError,
																				upload_success_handler : uploadSuccessArquivo,
																				upload_complete_handler : uploadComplete
																				
												};
												
												
												$.mask.masks = $.extend($.mask.masks,{
													datetime:{ mask: '39/19/9999 29:69' },
													placa:{ mask: '999-9999' }
												});

$(document).ready(function() {
											//pageTracker._setVar('UserMazaya:<?=$_SESSION["usuario"]->email?>')
										
											$.editable.addInputType('masked', {
												element : function(settings, original) {
													if(settings.mask != false){
														var input = $('<input type="text" alt="'+settings.mask+'" />').setMask();
														$(this).append(input);
														return(input);
													}
												}
											});
	
	toggleAdmin('conteudo');
						
		if(document.location.hash != "#"){
			processaHash();
		}else{
			listagem('produtos');
		}
		resizeFrames();			
});
	
$(window).resize(function() {
		resizeFrames();
});
$(window).bind('hashchange', function() {
	//processaHash();
});

var hash;
function processaHash(){
			var query = document.location.hash.replace("#","");
			
			_query = query.split("/");
			_acao = _query[1] ? _query[1].split("@") : new Array;	
			acao = _acao[0];		
			//if(query.replace("/","") != hash){
				if(acao == "editar" || acao == "novo"){
					formulario(query);
				}else{			
					listagem(query);
				}
			//}
			//hash = query.replace("/","");
}
function resizeFrames(){
		$('#conteudo').css({'height':($(window).height()-200)+"px"});
		$('#frame').css({'height':($(window).height()-200)+"px"});
		var _sizeConteudo;
		_sizeConteudo = $('#conteudo').height()-79 - $('#chart_div').height();
		
		
		$('#contentListagem').css({'height':_sizeConteudo+"px"});
		$('#contentFormulario').css({'height':_sizeConteudo+"px"});
		$('#formRemover').css({'height':_sizeConteudo+"px"});
		$('#lista').css({'height':_sizeConteudo+"px"});
		$('#listaScroll').css({'height':(_sizeConteudo-40)+"px"});
};

function toggleAdmin( _div  ){
	if( _div == 'frame'){
		$('#conteudo').hide();
		$('#frame').show();
	}else{
		$('#frame').hide();
		$('#conteudo').show();
	}
};

function toggleMenu(_menu){
		$(".nivel1").removeClass("menuAtivo");
		$(".grupoMenu").hide();
		$("#grupoMenu_"+_menu).fadeIn();
		$("#grupoMenu_"+_menu).prev().addClass("menuAtivo");
};

function buscar( tg, _campo ){
	return listagem( tg+'/listar@0@'+$("#"+ _campo).val());
};

function filtrar( tg, _form ){
	var _tmp = $('#'+_form).serialize();
	return listagem( tg+'/listar@0@'+decodeURIComponent(_tmp).replace(/(\d{1,2})\/(\d{1,2})\/(\d{4})/gi,"$3-$2-$1"));
};
function ordenar( tg ){
	return listagem( tg+'/listar@0@@'+$('#campo_ordem').val());
};

function listar( tg , form  ){
	var _tmp = $('#'+form).serialize();
	return listagem( tg+'/listar@0@'+decodeURIComponent(_tmp).replace(/(\d{1,2})\/(\d{1,2})\/(\d{4})/gi,"$3-$2-$1"));
	//return listagem( tg+'/listar@0@'+_tmp.replace(reg,"$1-$2-$3"));
};
function atualizar(   ){
	processaHash()
};

function listagem( query ){
	hash = query.replace("/","");
	_query = query.split("/");
						_tg = _query[0].split("@");	
						tg = _tg[0];		
						sub_tg = _tg[1] ? _tg[1] : '' ;
						sub_id = _tg[2] ? _tg[2] : '' ;
						
						_acao = _query[1] ? _query[1].split("@") : new Array;	
						acao = _acao[0];		
						acao_pag = _acao[1] ? _acao[1] : 0;
						acao_busca = _acao[2] ? _acao[2] : '';
						acao_ordem = _acao[3] ? _acao[3] : '';
						
						id = _query[2] ? _query[2] : '';	
	
	acao = acao ? acao : 'listar';
	
	ajaxManager.abort();
	
	
	var html = $();
	if( tg != ''){
		$("#carregando").text('Requisitando listagem: '+tg).show();
		$('#frame').hide().html('');
		$('#conteudo').html('');
		document.title = "Admin » "+tg;
		window.location = '#'+tg+'@'+sub_tg+'@'+sub_id+'/'+acao+'@'+acao_pag+'@'+acao_busca+'@'+acao_ordem+'/'+id;
		
		
		ajaxManager.add({
			url: tg+'@'+sub_tg+'@'+sub_id+'/'+acao+'@'+acao_pag+'@'+acao_busca+'@'+acao_ordem+'/'+id,
			type: 'POST',
			complete: function(resposta){
				try{
					dadosAjax = $.parseJSON(resposta.responseText);
					$("#carregando").text('Preparando resultados');
					if(dadosAjax){
						//conteudo = $('#conteudo');
						var titulo = $('<div class="titulo">');
							//titulo.html('<h1><img src="imagens/32x32/'+dadosAjax.titulo.icone+'" border="0" style="width:42px; height:42px" class="icone" align="absmiddle" width="21" height="21"/>'+dadosAjax.titulo.acao+' '+dadosAjax.titulo.caption+'</h1>');
							titulo.html('<h1>'+dadosAjax.titulo.acao+' '+dadosAjax.titulo.caption+'</h1>');
							
							$("#carregando").text('Exibindo comandos');
							var barra = $('<div id="barra" />');
							try{
								var _botoesDiv =  $('<div id="botoesDiv">');

								$.each(dadosAjax.barra.botoes,function(key, value){
										_botoesDiv.append('<a href="'+value.href+'" onclick="'+value.funcao+'" class="awesome yellow" title="'+value.title+'"><img src="imagens/buttons/'+value.imagem+'" border="0" align="absmiddle" width="21" height="21" alt="'+value.alt+'" title="'+value.alt+'"></a>');
										
								});
								barra.append(_botoesDiv);
							}catch(e){
								exibirMensagem("C0 " + e)
							};
							
							
							if(dadosAjax.lista.propriedades.total > 0){
								try{
									$("#carregando").text('Preparando filtros de campos predefinidos');
									var _filtroDiv =  $('<div id="filtroDiv">');
									//var _selectFiltro = $('<select>');
									var _filtroForm = $('<form id="filtroForm" />');
									$.each(dadosAjax.lista.headers,function(key, value){
										if(typeof(value.opcoes) == 'object'){
											//_selectFiltro.append('<option value="'+value.tabelas+'">'+value.visor+'</option>');
											var _subSelect = $('<select name="'+value.campo+'">')
											_subSelect.append('<option value="">'+value.visor+'</option>');
											$.each(value.opcoes,function(oK, oV){
												_subSelect.append('<option value="'+oK+'"'+(oK == value.selected ? " selected" : "")+'>'+oV+'</option>');
											});
											_filtroForm.append(_subSelect);
										}
									});
									//_filtroDiv.append(_selectFiltro);
									if(_filtroForm.children().size()){
										_filtroDiv.append(_filtroForm);
										_filtroDiv.append('<a class="awesome yellow" href="javascript:void()" onclick="listar(\''+tg+'\',\'filtroForm\')"><img src="imagens/buttons/filter.png" border="0" align="absmiddle" width="21" height="21"></a>');
										barra.append(_filtroDiv);
									}
		
								}catch(e){
									exibirMensagem("Não foi possível criar o filtro de campos predefinidos " + e)
								};
									
								try{
									$("#carregando").text('Preparando filtros de data');
									var _periodoDiv =  $('<div id="periodoDiv">');
									var _periodoForm = $('<form id="periodoForm" />');
									$.each(dadosAjax.lista.headers,function(key, value){
										if(value.v == 'date' || value.v == 'datetime'){
											_periodoForm.append('<input type="text" class="_periodoInputIni" name="'+value.campo+'_ini" value="Data inicial" id="'+value.campo+'_ini">');
											_periodoForm.append('<input type="text" class="_periodoInputFim" name="'+value.campo+'_fim" value="Data final" id="'+value.campo+'_fim">');
										}
									});
									//_filtroDiv.append(_selectFiltro);
									if(_periodoForm.children().size()){
										_periodoDiv.append(_periodoForm);
										_periodoDiv.append('<a class="awesome yellow" href="javascript:void()" onclick="listar(\''+tg+'\',\'periodoForm\')"><img src="imagens/buttons/filter.png" border="0" align="absmiddle" width="21" height="21"></a>');
										barra.append(_periodoDiv);
									}
		
								}catch(e){
									exibirMensagem("Não foi possível criar o filtro de data " + e)
								};
								
								try{
									$("#carregando").text('Preparando filtros de ordenação');
									var _ordemDiv =  $('<div id="ordemDiv">');
									var _ordemForm = $('<form id="ordemForm" />');
									var _ordemSelect = $('<select name="campo_ordem" id="campo_ordem">')
									_ordemSelect.append('<option value="">Ordenar</option>');
									$.each(dadosAjax.lista.headers,function(key, value){
										  _ordemSelect.append('<option value="'+value.campo+'=ASC'+'">'+value.visor+' &raquo; CRESC</option>');
										  _ordemSelect.append('<option value="'+value.campo+'=DESC'+'">'+value.visor+' &laquo; DESC</option>');
										  _ordemForm.append(_ordemSelect);
									});
									_ordemDiv.append(_ordemForm);
									_ordemDiv.append('<a class="awesome yellow" href="javascript:void()" onclick="ordenar(\''+tg+'\')"><img src="imagens/buttons/filter.png" border="0" align="absmiddle" width="21" height="21"></a>');
									barra.append(_ordemDiv);
		
								}catch(e){
									exibirMensagem("Não foi possível criar o filtro de ordenação " + e)
								};
							
							
								if(dadosAjax.lista.propriedades.edicao){
									$("#carregando").text('Preparando campo de busca');
									var _buscaDiv =  $('<div id="buscaDiv"/>');
									var _buscaForm = $('<form id="buscaForm" onSubmit="buscar(\''+tg+'\',\'campo_busca\'); return false;" />');
									_buscaForm.append('<input type="text" name="campo_busca" value="'+(dadosAjax.lista.propriedades.busca ? dadosAjax.lista.propriedades.busca : '')+'" id="campo_busca">');
									_buscaForm.append('<a class="awesome yellow" href="javascript:void()" onclick="buscar(\''+tg+'\',\'campo_busca\')"><img src="imagens/buttons/search.png" border="0" align="absmiddle" width="21" height="21"></a>');
									_buscaDiv.append(_buscaForm);
									barra.append(_buscaDiv);
								}
							
								var _innerBarraDir = $('<div id="innerBarraDir">');
								$("#carregando").text('Preparando paginação');
								_innerBarraDir.append('<a class="awesome grey">Atual: '+(parseInt(acao_pag) + 1)+' | Exibindo '+ dadosAjax.lista.propriedades.total+' de '+dadosAjax.lista.propriedades.rows+'</a>');
								if(acao_pag > 0) _innerBarraDir.append('<a href="#'+tg+'@'+sub_tg+'@'+sub_id+'/listar@'+(parseInt(acao_pag) - 1)+'@'+acao_busca+'@'+acao_ordem+'" class="awesome yellow" onclick="listagem(\''+tg+'@'+sub_tg+'@'+sub_id+'/listar@'+(parseInt(acao_pag) - 1)+'@'+acao_busca+'@'+acao_ordem+'/\')"><img src="imagens/buttons/previous.png" border="0" align="absmiddle" width="21" height="21"></a>');
								_totalPagina = parseInt(dadosAjax.lista.propriedades.rows) / parseInt(dadosAjax.lista.propriedades.total);
								if(parseInt(acao_pag) < _totalPagina) _innerBarraDir.append('<a href="#'+tg+'@'+sub_tg+'@'+sub_id+'/listar@'+(parseInt(acao_pag) + 1)+'@'+acao_busca+'@'+acao_ordem+'" class="awesome yellow" onclick="listagem(\''+tg+'@'+sub_tg+'@'+sub_id+'/listar@'+(parseInt(acao_pag) + 1)+'@'+acao_busca+'@'+acao_ordem+'/\')"><img src="imagens/buttons/next.png" border="0" align="absmiddle" width="21" height="21"></a>');
								
								var _paginacao = $('<select name="pagina">');
								var _c;
								for(_c = 0; _c < parseInt(dadosAjax.lista.propriedades.rows) / parseInt(dadosAjax.lista.propriedades.total); _c++){
										  _paginacao.append('<option value="'+_c+''+'">'+_c+'</option>');
									}
								
								_innerBarraDir.append(_paginacao);
								
								barra.append(_innerBarraDir);
							}
							
							//barra.append('<a href="javascript:grafico()" class="awesome yellow"><img src="imagens/buttons/chart.png" border="0" align="absmiddle" width="21" height="21"></a>');
	
						titulo.append(barra);
						$('#conteudo').append(titulo);
						
						
						var chart = $('<div id="chart_div" />');
						$('#conteudo').append(chart);
						
						
						var content = $('<div id="contentListagem">');
						
						$('#conteudo').append(content);
						
						$('#conteudo').show();
						
						
						$("#carregando").text('Exibindo resultados');
							var form = $('<form  name="formRemover" id="formRemover" method=post />');
							content.append(form);
							var lista = $('<ul id="lista" />');
							form.append(lista);
							var listaTitulo = $('<li class="liTitulo grey"/>');
							
							if(dadosAjax.lista.propriedades.ordenacao)
								listaTitulo.append('<div class="itemBoxOrder hh"></div>');
						  
							if(dadosAjax.lista.propriedades.edicao){
								if(dadosAjax.lista.propriedades.visualizacao){
									listaTitulo.append('<div class="itemBoxCheck ch"><input name="" type="checkbox" value="" class="ch" onclick="marcaRegistros()"></div>');
									listaTitulo.append('<div class="itemBoxCommand co"><span class="microTexto"><a href="#" onclick="marcaRegistros()">&nbsp;</a></span></div>');
								}
							}else{
								if(dadosAjax.lista.propriedades.visualizacao){
									listaTitulo.append('<div class="itemBoxCommand co"></div>');
								}
							}
							
							
							try{
								$.each(dadosAjax.lista.headers,function(key, value){
									if(key > 0)
										listaTitulo.append('<div class="'+value.classe+' branco" headerindex="'+key+'">'+value.visor+'</div>');
								});
							}catch(e){
								exibirMensagem("C2 " + e)
							};
							
							lista.append(listaTitulo);
							
							
							var _scroll = $('<div id="listaScroll" style="display:none" />');
							lista.append(_scroll);
							
							
					resizeFrames();
							
							if(dadosAjax.lista.propriedades.total > 0){
								$.each(dadosAjax.lista.itens,function(key, value){
									var _item = $('<li id="i_'+value[0]+'" class="liItem"/>');
									
									
									if(dadosAjax.lista.propriedades.ordenacao){
										_item.append('<div class="hh"><img src="imagens/mover-updown.png" /></div>');
									}
									
									if(dadosAjax.lista.propriedades.edicao){
										_item.append('<div class="ch"><input name="registro[]" type="checkbox" class="ch" value="'+value[0]+'"></div>');
										var _co = $('<div class="co">');
										_co.append('<a href="#'+tg+'@'+sub_tg+'@'+sub_id+'/editar@'+acao_pag+'@'+acao_busca+'@'+acao_ordem+'/'+value[0]+'" class="awesome yellow" onclick="formulario(\''+tg+'@'+sub_tg+'@'+sub_id+'/editar@'+acao_pag+'@'+acao_busca+'@'+acao_ordem+'/'+value[0]+'\')"><img src="imagens/buttons/edit.png" border="0" align="absmiddle" width="17" height="17" alt="Abrir/Editar"></a>');
										//_co.append('<a class="awesome yellow" href="javascript: remover(\''+tg+'\',\''+key+'\')"><img src="imagens/Delete.png" border="0" align="absmiddle" width="21" height="21" title="Excluir este registro"></a>');
										_item.append(_co)
									}else{
										if(dadosAjax.lista.propriedades.visualizacao){
											_item.append('<div class="ch"></div>');
											_item.append('<div class="co"><a href="#'+tg+'@'+sub_tg+'@'+sub_id+'/abrir@'+acao_pag+'@'+acao_busca+'@'+acao_ordem+'/'+value[0]+'" class="awesome yellow" onclick="formulario(\''+tg+'@'+sub_tg+'@'+sub_id+'/abrir@'+acao_pag+'@'+acao_busca+'@'+acao_ordem+'/'+value[0]+'\')"><img src="imagens/buttons/view.png" border="0" align="absmiddle" width="17" height="17" alt="Visualizar"></a></div>');
										}
									}
									
									try{
										$.each(value,function(key2, value2){
											if(key2 > 0){
												if(dadosAjax.lista.headers[key2].classe == "f_link")
													_item.append('<div id="'+dadosAjax.lista.headers[key2].campo+'__'+value[0]+'" headerindex="'+key2+'" class="'+dadosAjax.lista.headers[key2].classe+'"><a href="javascript:void(0)" onclick="'+dadosAjax.lista.headers[key2].funcao+'(\''+dadosAjax.lista.headers[key2].href+value2+'\')" class="awesome grey"> '+dadosAjax.lista.headers[key2].visor+'</a></div>');//<img src="imagens/icons/16x16/pdf_file.png" border="0" align="absmiddle" width="21" height="21">
												else
													if(dadosAjax.lista.headers[key2].classe == "f_enum"){
														var _item_opcoes = $('<div id="'+dadosAjax.lista.headers[key2].campo+'__'+value[0]+'" headerindex="'+key2+'" class="'+dadosAjax.lista.headers[key2].classe+' listaRadio">');
														$.each(dadosAjax.lista.headers[key2].opcoes, function(key3,value3){
															
															_item_opcoes.append('<label for="'+dadosAjax.lista.headers[key2].campo+'__'+value[0]+'__'+value3+'">'+value3+'</label>');
															_item_opcoes.append('<input'+(key3 == value2 ? " checked" : "")+' type="radio" name="'+dadosAjax.lista.headers[key2].campo+'__'+value[0]+'" value="'+key3+'" id="'+dadosAjax.lista.headers[key2].campo+'__'+value[0]+'__'+value3+'" class="l_enum">');
												
														});
														
														_item.append(_item_opcoes);

													}else if(dadosAjax.lista.headers[key2].classe == "f_tinyint"){
														var _item_opcoes = $('<div id="'+dadosAjax.lista.headers[key2].campo+'__'+value[0]+'" headerindex="'+key2+'" class="'+dadosAjax.lista.headers[key2].classe+'">');
															_item_opcoes.append('<input'+(1 == value2 ? " checked" : "")+' type="checkbox" name="'+dadosAjax.lista.headers[key2].campo+'__'+value[0]+'" id="'+dadosAjax.lista.headers[key2].campo+'__'+value[0]+'" class="listaCheckBox">');
															_item_opcoes.append('<label for="'+dadosAjax.lista.headers[key2].campo+'__'+value[0]+'"></label>');

														
														_item.append(_item_opcoes);

													}else{
														_item.append('<div id="'+dadosAjax.lista.headers[key2].campo+'__'+value[0]+'" headerindex="'+key2+'" class="'+dadosAjax.lista.headers[key2].classe+(dadosAjax.lista.headers[key2].campo == dadosAjax.lista.propriedades.ordenar ?' bold':'')+'"'+( dadosAjax.lista.headers[key2].m ? ' alt="'+dadosAjax.lista.headers[key2].m+'"' : '')+( dadosAjax.lista.headers[key2].tabela ? ' tabela="'+dadosAjax.lista.headers[key2].tabela+'"' : '') +'>'+value2+'</div>');
													}
											}
										});
									}catch(e){
										exibirMensagem("C3 " + e)
									};
									_scroll.append(_item);
								});
							}else{
									_scroll.append('<h3>Nenhum registro</h3>');
									$("#carregando").html('Nenhum registro disponível');
							}
							_scroll.slideDown("slow");
							
						//lista.append(_scroll);
						//form.append(lista);
						//content.append(form);
						//$('#conteudo').append(content);
						
						//$('#conteudo').show();
						
						
						//$("._periodoInput" ).datepicker({ dateFormat: 'dd/mm/yy', dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'], monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"] });

						var dates = $( "._periodoInputIni, ._periodoInputFim" ).datepicker({
							dateFormat: 'dd/mm/yy',
							dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
							monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],
							changeMonth: true,
							changeYear:true
						});
						
						var month = $( ".month" ).datepicker({
							dateFormat: 'mm/yy',
							dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
							monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],
							changeMonth: true,
							changeYear:true
						});
						
						
						if(dadosAjax.lista.propriedades.edicao){
							ativarInPlaceEditor(dadosAjax.lista.headers,dadosAjax.lista.propriedades.tabela);
							
							if(dadosAjax.lista.propriedades.ordenacao){
								$('#listaScroll').sortable({
										handle: 'div.hh',
										axis: 'y',
										containment: 'parent',
										dropOnEmpty: false,
										placeholder: "ui-state-highlight",
										cursor: 'crosshair',
										items: 'li',
										update: function() {
											salvarOrdenar(tg,$(this).sortable("serialize"));
										}
									});
							}
						}
						
					}
					
					ativaControles();
					atualizaCoresListagem();
					exibeRelatorioListagem(dadosAjax);
					
					$("#carregando").html("").fadeOut();
				}catch(e){
					exibirMensagem("<h3>Erro: Os dados recebidos não correpondem a uma listagem. Tente novamente ou use F5 do seu teclado</h3><small>(" + e + ")</small>")
					window.location = "http://"+location.hostname;
				}
				
			}
		});
	}
	return false;
};

function ativarInPlaceEditor(_headers, _tabela){
				$("#carregando").text('Ativando edição inline');
				$('.edit').each(function(index){
												$(this).editable("Nucleo/atualizar/", {
												   event:"dblclick",
												   indicator: "Salvando...",
												   cssclass:'edit',
												   submitdata: {'tabela':_tabela},
												   //onblur: 'ignore',
												   placeholder: '<small>Editar</small>',
												   callback : function(value, settings) {
														 console.log(this);
														 console.log(value);
														 console.log(settings);
														 ativaControles();
														 atualizaCoresListagem();
													},
												   type: $(this).attr('alt') ? 'masked' : 'text',
												   mask: $(this).attr('alt') ? $(this).attr('alt') : null});
				});
				$('.edit_enum').each(function(index){
												var _opcoes = _headers[$(this).attr('headerindex')]['opcoes'];
												$(this).editable("Nucleo/atualizar/", {
												   indicator: "Salvando...",
												   event:"dblclick",
												   data: _opcoes,
												   type:'select',
												   cssclass:'edit_enum',
												   submitdata: {'tabela':_tabela},
												   placeholder: '<small>Editar</small>',
												   callback : function(value, settings) {
														 console.log(this);
														 console.log(value);
														 console.log(settings);
														 ativaControles();
														 atualizaCoresListagem();
													},
												   submit:'<img src="imagens/buttons/save.png" border="0" align="absmiddle" width="21" height="21">'});
				});
				$("input[type=radio]").change(function(){
						$.ajax({
							data:'id='+this.name+'&tabela='+_tabela+'&value='+$(this).val(),
							url:'Nucleo/atualizar/',
							dataType: 'json',
							type:'POST'
						});
					
				});
				$("input[type=checkbox]").change(function(){
						$.ajax({
							data:'id='+this.name+'&tabela='+_tabela+'&value='+( $(this).is(":checked") ? 1 : 0 ),
							url:'Nucleo/atualizar/',
							dataType: 'json',
							type:'POST'
						});
					
				});
				$('.edit_select').each(function(index){
												var _opcoes = _headers[$(this).attr('headerindex')]['opcoes'];
												$(this).editable("Nucleo/atualizar/", {
												   //loadurl:"Nucleo/carregarOptions/"+$(this).attr('tabela')+'/',
												   event:"dblclick",
												   indicator: "Salvando...",
												   data: _opcoes,
												   placeholder: '<small>Selecionar</small>',
												   type:'select',
												   submitdata: {'tabela':_tabela},
												   cssclass:'edit_select',
												   //onblur: 'ignore',
												   callback : function(value, settings) {
														 console.log(this);
														 console.log(value);
														 console.log(settings);
														 ativaControles();
														 atualizaCoresListagem();
													},
												   submit:'<img src="imagens/buttons/save.png" border="0" align="absmiddle" width="21" height="21"> Salvar'	});
				});
				$('.edit_month').each(function(index){
												$(this).editable("Nucleo/atualizar/", {
												   event:"dblclick",
												   indicator: "Salvando...",
												   cssclass:'edit',
												   submitdata: {'tabela':_tabela},
												   //onblur: 'ignore',
												   placeholder: '<small>Editar</small>',
												   callback:function (){
														$( "input.f_varchar-7" ).datepicker( {
																monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"],
																changeMonth: true,
																changeYear: true,
																dateFormat: 'yy-mm',
																showButtonPanel: true,
																onClose: function(dateText, inst) { 
																	var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
																	var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
																	$(this).datepicker('setDate', new Date(year, month, 1));
																}
															});
														$("input.f_varchar-7").focus(function () {
															$(".ui-datepicker-calendar").hide();
															});}
													});
				});
};

function ativaControles(){
					//$('select').searchable();
					$('.awesome,.edit_select button').button();
					$('.thumbDivOpcoes').button({icons: { primary: "ui-icon-triangle-1-s" }});
					$('.thumbOpcoesLink a').button();
					$('.listaRadio').buttonset();
					
					
					$("#webcam").scriptcam({
						onError:onError,
						cornerRadius:3,
						onWebcamReady:onWebcamReady,
						width: 640,height: 480, flip:1, zoom:0.5,
						//width: 320,height: 240, flip:1,
						cornerColor:'ffffff'


					});
					
					$(".imgLi").smoothZoom({});
					
					$( document ).tooltip({ show:{ delay: 300, duration: 100 }, hide: false, position: { my: "left center", at: "right top"} });
					


}
					
					
function atualizaCoresListagem(){
						$("#carregando").text('Personalizando listagens especiais');
						
						$(".liItem").css('background-color',"");
						$(".liItem:odd").css('background-color',"#f6f6f6");
						
						$('option:contains("Liberado")').css('font-weight',"bold").css('color',"#7B9636").css('background-color',"#CAD5AF");
						$('option:contains("Interfonar")').css('font-weight',"bold").css('color',"#AA6500").css('background-color',"#FFB126");
						$('option:contains("Proibido")').css('font-weight',"bold").css('color',"#860925").css('background-color',"#D60038");
					
						$('div.f_varchar-255:contains("Ativo")').css('font-weight',"bold").css('color',"#7B9636").parent().css('background-color',"#CAD5AF");
						$('div.f_varchar-100:contains("Ativo")').css('font-weight',"bold").css('color',"#7B9636").parent().css('background-color',"#CAD5AF");
						$('div.f_varchar-255:contains("Inativo")').css('font-weight',"bold").css('color',"#860925").parent().css('background-color',"#EA5980");
						$('div.f_varchar-100:contains("Inativo")').css('font-weight',"bold").css('color',"#860925").parent().css('background-color',"#EA5980");
						$('div.f_varchar-255:contains("Aberto")').css('font-weight',"bold").css('color',"#86888A").parent().css('background-color',"#CFCFD0");
						$('div.f_varchar-100:contains("Aberto")').css('font-weight',"bold").css('color',"#86888A").parent().css('background-color',"#CFCFD0");
						$('div.f_varchar-255:contains("Dispon")').css('font-weight',"bold").css('color',"#86888A").parent().css('background-color',"#CFCFD0");
						$('div.f_varchar-100:contains("Dispon")').css('font-weight',"bold").css('color',"#86888A").parent().css('background-color',"#CFCFD0");
						$('div.f_varchar-255:contains("Vencendo")').css('font-weight',"bold").css('color',"#AA6500").parent().css('background-color',"#FFB126");
						$('div.f_varchar-100:contains("Vencendo")').css('font-weight',"bold").css('color',"#AA6500").parent().css('background-color',"#FFB126");
						$('div.f_varchar-255:contains("Em negocia")').css('font-weight',"bold").css('color',"#AA6500").parent().css('background-color',"#FFB126");
						$('div.f_varchar-100:contains("Em negocia")').css('font-weight',"bold").css('color',"#AA6500").parent().css('background-color',"#FFB126");
						$('div.f_varchar-255:contains("Aguardando")').css('font-weight',"bold").css('color',"#AA6500").parent().css('background-color',"#FFB126");
						$('div.f_varchar-100:contains("Aguardando")').css('font-weight',"bold").css('color',"#AA6500").parent().css('background-color',"#FFB126");
						$('div.f_varchar-255:contains("Confirmado")').css('font-weight',"bold").css('color',"#7B9636").parent().css('background-color',"#CAD5AF");
						$('div.f_varchar-100:contains("Confirmado")').css('font-weight',"bold").css('color',"#7B9636").parent().css('background-color',"#CAD5AF");
						$('div.f_varchar-255:contains("Faturado")').css('font-weight',"bold").css('color',"#7B9636").parent().css('background-color',"#CAD5AF");
						$('div.f_varchar-255:contains("Quitado")').css('font-weight',"bold").css('color',"#7B9636").parent().css('background-color',"#CAD5AF");
						$('div.f_varchar-100:contains("Quitado")').css('font-weight',"bold").css('color',"#7B9636").parent().css('background-color',"#CAD5AF");
						$('div.f_varchar-255:contains("Finalizado")').css('font-weight',"bold").css('color',"#7B9636").parent().css('background-color',"#CAD5AF");
						$('div.f_varchar-100:contains("Finalizado")').css('font-weight',"bold").css('color',"#7B9636").parent().css('background-color',"#CAD5AF");
						$('div.f_varchar-255:contains("Enviado")').css('font-weight',"bold").css('color',"#055AA0").parent().css('background-color',"#9BBDD9");
						$('div.f_varchar-255:contains("Recebido")').css('font-weight',"bold").css('color',"#055AA0").parent().css('background-color',"#9BBDD9");
						$('div.f_varchar-255:contains("Cancelado")').css('font-weight',"bold").css('color',"#860925").parent().css('background-color',"#D60038");
						$('div.f_varchar-255:contains("Vencido")').css('font-weight',"bold").css('color',"#860925").parent().css('background-color',"#EA5980");
						$('div.f_varchar-255:contains("Recuperado")').css('font-weight',"bold").css('color',"#055AA0").parent().css('background-color',"#9BBDD9");
						$('div.f_varchar-255:contains("Atualizado")').css('font-weight',"bold").css('color',"#055AA0").parent().css('background-color',"#9BBDD9");
						
						
						$('div[class*="f_varchar-100"]:contains("Pendente")').css('font-weight',"bold").css('color',"#AA6500").parent().css('background-color',"#FFB126");
						$('div[class*="f_varchar-100"]:contains("Atualizado")').css('font-weight',"bold").css('color',"#055AA0").parent().css('background-color',"#9BBDD9");
						$('div[class*="f_varchar-100"]:contains("Retornar")').css('font-weight',"bold").css('color',"#860925").parent().css('background-color',"#EA5980");
						$('div[class*="f_varchar-100"]:contains("Contactado")').css('font-weight',"bold").css('color',"#7B9636").parent().css('background-color',"#CAD5AF");
						$('div[class*="f_varchar-100"]:contains("Ignorar")').css('font-weight',"bold").css('color',"#860925").parent().css('background-color',"#D60038");
						$('div[class*="f_varchar-100"]:contains("Em Andamento")').css('font-weight',"bold").css('color',"#63720D").parent().css('background-color',"#ABD134");
						
						
						$('div[class*="f_varchar-100"]:contains("Liberado")').css('font-weight',"bold").css('color',"#7B9636").parent().css('background-color',"#CAD5AF");
						$('div[class*="f_varchar-100"]:contains("Interfonar")').css('font-weight',"bold").css('color',"#AA6500").parent().css('background-color',"#FFB126");
						$('div[class*="f_varchar-100"]:contains("Proibido")').css('font-weight',"bold").css('color',"#860925").parent().css('background-color',"#D60038");
						
						
						
						
}


function exibeRelatorioListagem(_data){
	
	if(dadosAjax.lista.propriedades.grafico){
		
		$('#chart_div').css({'height':'200px'}).show();
		
        var data = google.visualization.arrayToDataTable(dadosAjax.lista.relatorio.itens);
		
		
   
   
        var options = {
          title: dadosAjax.lista.propriedades.tituloRelatorio
		 };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
		
		resizeFrames();
	}
}
var dadosAjax;
function formulario( query ){

	toggleAdmin( 'frame' );
			
	$("#carregando").html('Conectando ao servidor').fadeIn();
	$('#frame').html('');
	
		ajaxManager.add({
			url: ''+query,
			complete: function(resposta){
				try{
					dadosAjax = $.parseJSON(resposta.responseText);	
					$("#carregando").html('Carregando formulário');					
					var titulo = $('<div class="titulo">');
					//titulo.html('<h1><img src="imagens/icons/48x48/'+dadosAjax.titulo.icone+'" border="0" style="width:48px; height:48px" class="icone" align="absmiddle" width="21" height="21"/>'+dadosAjax.titulo.acao+' '+dadosAjax.titulo.caption+'</h1>');
					titulo.html('<h1>'+dadosAjax.titulo.acao+' '+dadosAjax.titulo.caption+'</h1>');
					
					var barra = $('<div id="barra" />');
					var _botoesDiv =  $('<div id="botoesDiv">');
					try{
						$.each(dadosAjax.barra.botoes,function(key, value){
								_botoesDiv.append('<a href="'+value.href+'" onclick="'+value.funcao+'" title="'+value.title+'" class="awesome yellow"><img src="imagens/buttons/'+value.imagem+'" border="0" align="absmiddle" width="21" height="21"> '+value.caption+'</a>');
						});
					}catch(e){
						exibirMensagem("C0 " + e)
					};
					barra.append(_botoesDiv);
					titulo.append(barra);
					$('#frame').append(titulo);
					
					
					
					var content = $('<div id="contentFormulario" />');
					
					var _form = $('<form method="'+dadosAjax.propriedades.method+'" action="'+dadosAjax.propriedades.action+'"  '+dadosAjax.propriedades.name+' onsubmit="'+dadosAjax.propriedades.onsubmit+'" target="'+dadosAjax.propriedades.target+'" />');
									
						var _scroll = $('<div id="formScroll" />');
						if(dadosAjax.propriedades.total > 0){
							var i = 1;
							$.each(dadosAjax.fieldsets,function(key, field){
								//var _fieldset = $('<fieldset style="float:'+ (i % 2 == 0 ? "right" : "left") +'"><div class="legend awesome'+(field.propriedades.hidden == 1 ? ' hidden': '')+'">'+field.propriedades.nome+'</div></fieldset>');
								var _fieldset = $('<fieldset class="'+field.propriedades.class+'"><div class="legend '+(field.propriedades.hidden == 1 ? ' hidden': '')+'">'+field.propriedades.nome+'</div></fieldset>');
								i += 1;
								
								///var _fieldsetDiv = $('<div>');
								
								try{
									$.each(field.campos,function(_id, _dados){
										_fieldset.append(campo(_id, _dados));
									});
								}catch(e){
									exibirMensagem("Mensagem: " + e.message + "<br />Linha: " + e.lineNumber + "")
								};
								//_fieldset.append(_fieldsetDiv);
								_scroll.append(_fieldset);
							});
						}else{
								_scroll.append('<h3>Informações indisponíveis</h3>');
						}
						
						
					_form.append(_scroll);
					content.append(_form);
					$('#frame').append(content);
					
					resizeFrames();
					$(".legend").click(function(){
								$(this).toggleClass('hidden')
								$(this).next().slideToggle();
								refazMasonry()
								});
					
					$(".hidden").nextAll().hide();
					$('#frame').show();
					$("#carregando").hide();
					
					
					
					if(dadosAjax.propriedades.edicao){

						$( "input.f_date" ).datepicker({ dateFormat: 'dd/mm/yy', dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'], monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"] });
						$( "input.f_datetime" ).datetimepicker({ currentText: 'Agora', closeText: 'OK', controlType: 'select', timeText: 'Horario', hourText: 'Hora', minuteText: 'Minuto', dateFormat: 'dd/mm/yy', dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'], monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"] });
						//$( "input.f_datetime, input.f_date" ).datepicker({ dateFormat: 'dd/mm/yy', dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'], monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"] });
						$( "input.f_smallint-6" ).datepicker({ dateFormat: 'mm/yy', monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"], changeMonth: true, changeYear: true });
					
						$( "input.f_varchar-7" ).datepicker( {
							monthNames: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"], changeMonth: true, changeYear: true,
							showButtonPanel: true,
							dateFormat: 'yy-mm',
							onClose: function(dateText, inst) { 
								var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
								var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
								$(this).datepicker('setDate', new Date(year, month, 1));
							}
						});
						$("input.f_varchar-7").focus(function () {
						$(".ui-datepicker-calendar").hide();
						});
					
					
						$('#buttonPlaceholderFoto').each(function(){
							uploadFotos = new SWFUpload(settingsFoto); 
							$("#carregando").html('Preparando envio de fotos');
			
						});
						
						$('#buttonPlaceholderArquivo').each(function(){
							uploadArquivo = new SWFUpload(settingsArquivo); 
							$("#carregando").html('Preparando envio de arquivos');
						});
			
						$('.autoCompleteInput').each(function(){
									var _ele = $(this);
									$( _ele ).autocomplete({
										source: 'Nucleo/autoComplete/'+_ele.attr('tabela')+'/',
										minLength: 0,
										select: function( event, ui ) {
											$("#id" + _ele.attr('tabela')).val(ui.item.id);
										}
									});
						});
	
						$('.grafico').each(function(){
													exibeRelatorioFormulario(dadosAjax.relatorio,$(this).attr('id'));
						});
						$('.autoCompleteTags').each(function(){
									var _ele = $(this);
									$( _ele ).autocomplete({
										source: 'Nucleo/autoComplete/'+_ele.attr('tabela_secundaria')+'/',
										minLength: 3,
										select: function( event, ui ) {
											
											var dados = { "id" : ui.item.id , "valor" : ui.item.value , "quantidade" : 1 , "ganhados" : 0};
											
											$("#id" + _ele.attr('id')).prepend(tag(_ele.attr('id'),_ele.attr('tabela'),_ele.attr('tabela_secundaria'),Math.random(),dados,"_novo_"));
											//$("#id" + _ele.attr('id')).append(tag(_key,_dados.tabela,_dados.tabela_secundaria,oKey,oValue,''));
											
											$('.editQuantidade').each(function(index){
																			$(this).editable("Nucleo/atualizar/", {
																			   indicator: "Salvando...",
																			   cssclass:'inherit',
																			   submitdata: {'tabela':"presentes"},
																			   //onblur: 'ignore',
																			   placeholder: '<small>Editar</small>',
																			   type: $(this).attr('alt') ? 'masked' : 'text',
																			   mask: $(this).attr('alt') ? $(this).attr('alt') : null,
																			   cssclass : '',
																			   style: ""
																			   });});
										}
									});
						});
						$('.editQuantidade').each(function(index){
														$(this).editable("Nucleo/atualizar/", {
														   indicator: "Salvando...",
														   cssclass:'inherit',
														   submitdata: {'tabela':"presentes"},
														   //onblur: 'ignore',
														   placeholder: '<small>Editar</small>',
														   type: $(this).attr('alt') ? 'masked' : 'text',
														   mask: $(this).attr('alt') ? $(this).attr('alt') : null,
														   cssclass : '',
														   style: ""
														   });});
						
						$('#idestados').change(function(){carregaCidades($(this).val())});
						
						$('#thumbnailsFotos').sortable({
															containment: 'parent',
															dropOnEmpty: false,
															cursor: 'crosshair',
															items: 'li',
															update: function() {
																salvarOrdenar("fotos",$(this).sortable("serialize",{key:'i[]'}));
															}
														});
								

					}
					
					__setCalculoPedido();
					__calculaValoresBoletos();
					
					$("#carregando").html('Ativando controles');	
					$( ".campoRadio" ).buttonset();
					
					$( "#checkbox_categorias" ).button();
					$( ".divPergunta0" ).buttonset();

					
					$('.edit').setMask();
					
					
					$(".richText").jqte();
					
					
					ativaControles();
					
					refazMasonry();
					
				}catch(e){
					exibirMensagem("Erro: O formulário não pode ser exibido. Tente novamente.<br />Mensagem: " + e.message + "<br />Linha: " + e.lineNumber + "" )
				}
				
			}
		});
	return false;
};

function refazMasonry(){
				  //$('#scroll').masonry({
						//itemSelector : 'fieldset'
				  //});
				  
				  var msnry = new Masonry( '#formScroll', {
					  "itemSelector" : 'fieldset'
					});
				  
				  //$('.divCheckboxes').masonry({
						//itemSelector : '.divPergunta0'
					//});
}

function campo( _key, _dados){
	
	var _campo;
	
									switch(_dados.tag){
										case "textarea":
											var _div = $('<div class="campoTextarea">');
											if(_dados.label){
												_label = $('<label '+( _dados.obrigatorio ?' class=obrigatorio" title="Este campo é obrigatório"':"")+'>'+_dados.label+'</label>');
												_div.append(_label)
											}
											var _campo = $("<textarea />");
											_campo.attr("id",_key);
											_campo.attr("name",_key);
											_campo.attr("class",_dados.c);
											_campo.attr("disabled",_dados.disabled);
											_campo.attr("alt",_dados.alt);
											_campo.attr("maxlength",_dados.maxlength);
											_campo.html(_dados.value);
											_div.append(_campo)
										break;
										case "select":
											var _div = $('<div class="campo">');
											if(_dados.label){
												var _label = $('<label '+( _dados.obrigatorio ?' class=obrigatorio" title="Este campo é obrigatório"':"")+'>'+_dados.label+'</label>');
												_div.append(_label)
											}
											var _campo = $("<select />");
											_campo.append('<option />');
											
											$.each(_dados.opcoes,function(oKey, oValue){
												var _op = $('<option value="'+oValue[0]+'" oKey="'+oKey+'">'+oValue[1]+'</option>');
												if(oValue[0] == _dados.value)
													_op.attr("selected",true);
												_campo.append(_op);
											});
											_campo.attr("id",_key);
											_campo.attr("name",_key);
											_campo.attr("class",_dados.c);
											_campo.attr("disabled",_dados.disabled);
											_campo.attr("alt",_dados.alt);
											_campo.attr("maxlength",_dados.maxlength);
											_campo.attr("value",_dados.value);
											if(_dados.expand){
												_campo.change(expandirDados);
												//alert(_campo.attr("id"))
												_campo.trigger('change')
											}
											_div.append(_campo)
										break;
										case "radio":
											var _div = $('<div class="campo">');
											if(_dados.label){
												var _label = $('<label '+( _dados.obrigatorio ?' class=obrigatorio" title="Este campo é obrigatório"':"")+'>'+_dados.label+'</label>');
												_div.append(_label)
											}
											var _divSet = $('<div class="campoRadio">');
											$.each(_dados.opcoes,function(oKey, oValue){
												var _campo = $("<input />");
												_campo.attr("id",_key+oValue);
												_campo.attr("type","radio");
												_campo.attr("name",_key);
												_campo.attr("disabled",_dados.disabled);
												_campo.attr("alt",_dados.alt);
												_campo.attr("value",oKey);
												if(oKey == _dados.value)
													_campo.attr("checked",true);
												_divSet.append(_campo)	;
												
												var _label = $('<label/>');
												_label.attr("for",_key+oValue);
												_label.append(oValue)
												_divSet.append(_label)	;
											});
											
											_div.append(_divSet);
											
											if(_dados.expand){
												_campo.change(expandirDados);
											}
										break;
										case "div":
											switch(_dados.type){
												case "files":
													var _div = $('<div class="divFilhos">');
													var _ul = $('<ul id="thumbnailsArquivos">');
													if(_dados.total > 0){
														$.each(_dados.values,function(oKey, oValue){
															_ul.append(arquivo(oKey, oValue));
														});
													}
													_div.append(_ul)
													
													var _divButton = $('<div class="divFilhosButton">');
													
													if(dadosAjax.propriedades.edicao){
														var _Button = $('<div class="awesome yellow addSubsetButton"><div id="buttonPlaceholderArquivo"></div></<div>');
														_divButton.append(_Button);
													}
													_div.append(_divButton)
												break;
												case "fotos":
													var _div = $('<div class="divFilhos">');
													var _ul = $('<ul id="thumbnailsFotos">');
													if(_dados.total > 0){
														$.each(_dados.values,function(oKey, oValue){
															_ul.append(foto(oKey, oValue));
														});
													}
													_div.append(_ul)
													var _divButton = $('<div class="divFilhosButton">');
													
													if(dadosAjax.propriedades.edicao){
														var _Button = $('<div class="awesome yellow addSubsetButton"><div id="buttonPlaceholderFoto"></div></<div>');
														_divButton.append(_Button);
													}
													_div.append(_divButton)
												break;
												case "camera":
													var _div = $('<div class="divFilhos">');
													
													var _divCamera = $('<div class="divCamera">')
													var _camera = $('<div id="webcam" style="width:320px;">');
													var _cameraNames = $('<select id="cameraNames" size="1" onChange="changeCamera()" style="width:245px;font-size:10px;height:25px;" />');
													_camera.append(_cameraNames);
													
													_divCamera.append(_camera);
													
													var _decoded = $('<textarea name="decoded" id="decoded" style="display:none">');
													_divCamera.append(_decoded);
													var _cameraPreview = $('<img id="cameraPreview" />');
													_divCamera.append(_cameraPreview);
													
													var _Button = $('<button class="awesome" id="btn1">Registrar foto</button>');
													_Button.click(function(){ enviaCamera(); return false;})
													_divCamera.append(_Button);
													
													var _ul = $('<ul id="thumbnailsFotos">');
													_ul.append(_divCamera);
													if(_dados.total > 0){
														$.each(_dados.values,function(oKey, oValue){
															_ul.append(camera(oKey, oValue));
														});
													}
													_div.append(_ul)
												break;
												case "chechbox":
													var _div = $('<div class="divCheckboxes">');
													var _MT = $('<a href="javascript:$(\'input[type=checkbox]\').attr(\'checked\', true);$(\'.divPergunta0\').buttonset(\'refresh\');void(0);" class="awesome">Marcar todos</a>');
													var _DT = $('<a href="javascript:$(\'input[type=checkbox]\').attr(\'checked\', false);$(\'.divPergunta0\').buttonset(\'refresh\');void(0);" class="awesome">Desmarcar todos</a><br><br>');
													
													_div.append(_MT);
													_div.append(_DT);
													
													if(_dados.total > 0){
														$.each(_dados.grupos,function(oKey, oValue){
															_div.append(grupo(oKey, oValue,_key));
														});
													}
												break;
												case "filhos":
													var _div = $('<div class="divFilhos">');
													
													var _divTit = $('<div class="thumbFilhosTit">');
													_divTit.append('<div class="campoListTop" style="width:20px;">&nbsp;</div>')
													$.each(_dados.subset.campos,function(k, v){
														_divTit.append('<div class="campoListTop '+v.c+'">'+v.label+'</div>');
													});
													
													_div.append(_divTit)
													
													var _ul = $('<ul class="thumbFilhos" id="filhos_'+_key+'">');
													if(_dados.total > 0){
														$.each(_dados.values,function(k, v){
															_ul.append(subset(_dados.subset,_key,k,v));
														});
													}else{
															_ul.append(subset(_dados.subset,_key));
													}
													_div.append(_ul);
													_div.append('<a href="javascript:addSubset(\''+_dados.subset.propriedades.index+'\',\''+_dados.subset.propriedades.nome+'\');" class="awesome yellow addSubsetButton clear"><img src="imagens/buttons/add.png" border="0" align="absmiddle" width="21" height="21"> Adicionar registro</a>');//'+_key+'

												break;
												case "boletos":
													var _div = $('<div class="divBoletos">');
													
													var _tabelaBoletos = $('<table class="_tabelaBoletos">');
													var _tr = $('<tr><th width="40%">Condômino</th><th>Internet</th><th>Interfone</th><th>Taxa de Manutenção</th><th>Fundo de Reserva</th><th>Roçagem</th><th>Chamada de Capital</th><th>Benfeitorias</th><th>Salão de Festas</th><th>Outros</th><th>Total Individual</th></tr>');
													
													_tabelaBoletos.append(_tr);

													if(_dados.total > 0){
														$.each(_dados.condominos,function(k, v){
															var _tr = $('<tr>');
															_tr.append('<td><h3>'+v.nome+'</h3></td>');
															_tr.append('<td><input id="_bolin'+v.id+'" ib="'+v.id+'" alt="decimal" class="f_float-9-2 _bolin edit" name="boleto['+v.id+'][internet]" value="'+v.in+'"></td>');
															_tr.append('<td><input id="_bolif'+v.id+'" ib="'+v.id+'" alt="decimal" class="f_float-9-2 _bolif edit" name="boleto['+v.id+'][interfone]" value="'+v.if+'"></td>');
															_tr.append('<td><input id="_boltm'+v.id+'" ib="'+v.id+'" alt="decimal" class="f_float-9-2 _boltm edit" name="boleto['+v.id+'][taxa_manutencao]" value="'+v.tm+'"></td>');
															_tr.append('<td><input id="_bolfr'+v.id+'" ib="'+v.id+'" alt="decimal" class="f_float-9-2 _bolfr edit" name="boleto['+v.id+'][fundo_reserva]" value="'+v.fr+'"></td>');
															_tr.append('<td><input id="_bolrc'+v.id+'" ib="'+v.id+'" alt="decimal" class="f_float-9-2 _bolrc edit" name="boleto['+v.id+'][rocagem]" value="'+v.rc+'"></td>');
															_tr.append('<td><input id="_bolcc'+v.id+'" ib="'+v.id+'" alt="decimal" class="f_float-9-2 _bolcc edit" name="boleto['+v.id+'][chamada_capital]" value="'+v.cc+'"></td>');
															_tr.append('<td><input id="_bolbf'+v.id+'" ib="'+v.id+'" alt="decimal" class="f_float-9-2 _bolbf edit" name="boleto['+v.id+'][benfeitoria]" value="'+v.bf+'"></td>');
															_tr.append('<td><input id="_bolsl'+v.id+'" ib="'+v.id+'" alt="decimal" class="f_float-9-2 _bolsl edit" name="boleto['+v.id+'][salao_festa]" value="'+v.sl+'"></td>');
															_tr.append('<td><input id="_bolou'+v.id+'" ib="'+v.id+'" alt="decimal" class="f_float-9-2 _bolou edit" name="boleto['+v.id+'][outros]" value="'+v.ou+'"></td>');
															_tr.append('<td><input id="_bolvl'+v.id+'" ib="'+v.id+'" alt="decimal" class="f_float-9-2 _bolvl edit" indiceBol="'+v.id+'" name="boleto['+v.id+'][valor]" value="'+v.vl+'"></td>');
															_tabelaBoletos.append(_tr);
														});
													}
													
													_div.append(_tabelaBoletos);
													

												break;
												case "autoCompleteInput":
													var _div = $('<div class="campo">');
													if(_dados.label){
														_label = $('<label '+( _dados.obrigatorio ?' class=obrigatorio" title="Este campo é obrigatório"':"")+'>'+_dados.label+'</label>');
														_div.append(_label);
													}
													var _campo = $('<input />');
													_campo.attr("id",_key);
													_campo.attr("name",_key);
													_campo.attr("class",'autoCompleteInput f_varchar-300');
													_campo.attr("tabela",_dados.tabela);
													_campo.attr("tabela_secundaria",_dados.tabela_secundaria);
													_campo.attr("value",_dados.value);
													_div.append(_campo);
													
													var _campo = $('<input />');
													_campo.attr("id","id" + _key);
													_campo.attr("name","id" + _key);
													_campo.attr("value",_dados.id);
													_campo.attr("type","hidden");
													_div.append(_campo)
													
													var _campo = $('<a />');
													_campo.html("Limpar");
													_campo.attr("class","awesome");
													_campo.click(function() {
																		  $("#id" + _key).val('');
																		  $("#"+ _key).val('');
																		  });
													_div.append(_campo);
													
												break;
												case "autoCompleteTags":
													var _div = $('<div class="campo">');
													if(_dados.label){
														_label = $('<label '+( _dados.obrigatorio ?' class=obrigatorio" title="Este campo é obrigatório"':"")+'>'+_dados.label+'</label>');
														_div.append(_label)
													}
													var _campo = $('<input />');
													_campo.attr("id",_key);
													_campo.attr("name",_key);
													_campo.attr("class",'autoCompleteTags');
													_campo.attr("tabela",_dados.tabela);
													_campo.attr("tabela_secundaria",_dados.tabela_secundaria);
													_campo.attr("value",_dados.value);
													_div.append(_campo)
													
													var _campo = $('<div />');
													_campo.attr("id","id" + _key);
													_campo.attr("name","id" + _key);
													_campo.attr("class",'tags');
													_campo.attr("value",_dados.id);
													
													
													if(_dados.total > 0){
														$.each(_dados.values,function(oKey, oValue){
															_campo.append(tag(_key,_dados.tabela,_dados.tabela_secundaria,oKey,oValue,''));
														});
													}
													
													_div.append(_campo)
												break;
												case "cep":
													var _div = $('<div class="campo">');
													_label = $('<label '+( _dados.obrigatorio ?' class=obrigatorio" title="Este campo é obrigatório"':"")+'>CEP</label>');
													_div.append(_label);
													
													var __div = $('<div class="cidadeEstado">');
													_campo = $("<input />");
													_campo.attr("id","cep");
													_campo.attr("name","cep");
													_campo.attr("class",_dados.c);
													_campo.attr("alt","cep");
													_campo.attr("maxlength",9);
													_campo.attr("value",_dados.value);
													__div.append(_campo);
													_div.append(__div)
													
													var __div = $('<div class="cidadeEstado">');
													__div.append('<a onclick="carregaCep($(\'#cep\').val())" type="button" class="awesome yellow"><img src="imagens/buttons/search.png" border="0" align="absmiddle" width="21" height="21"></a></div>');
													_div.append(__div)
												break;
												case "datetime":
													var _div = $('<div class="campo">');
													_label = $('<label '+( _dados.obrigatorio ?' class=obrigatorio" title="Este campo é obrigatório"':"")+'>'+_dados.label+'</label>');
													_div.append(_label);
													
													_campo = $("<input />");
													_campo.attr("id",_key+"_data");
													_campo.attr("name",_key+"_data");
													_campo.attr("class","f_date");
													_campo.attr("alt","date");
													_campo.attr("maxlength",10);
													_campo.attr("value",_dados._data);
													_div.append(_campo);
													
													_campo = $("<input />");
													_campo.attr("id",_key+"_hora");
													_campo.attr("name",_key+"_hora");
													_campo.attr("class","f_varchar-8");
													_campo.attr("alt","time");
													_campo.attr("maxlength",8);
													_campo.attr("value",_dados._hora);
													_div.append(_campo);
													
												break;
												case "grafico":
													var _div = $('<div class="grafico" id="'+ _key +'">');
													
													_div.append(__div)
												break;
												case "expl":
													var _div = $('<div class="explicacao">'+_dados.value+'</div>');
													_div.append(_campo)
												break;
												default:
													var _div = $('<div>');
													_campo = $("<input />");
													_campo.attr("id","vazio");
													_campo.attr("value","PROBLEMA NA DEFINIÇÃO JSON DO CAMPO");
													_campo = $(_dados.type);
													_div.append(_campo)
												break;
											}
										break;
										case "br":
											var _div = $('<br clear="all" />');
										break;
										default:
											var _div = $('<div class="campo">');
											if(_dados.label){
												_label = $('<label '+( _dados.obrigatorio ?' class=obrigatorio" title="Este campo é obrigatório"' :"")+'>'+_dados.label+'</label>');
												_div.append(_label)
											}
											_campo = $("<input />");
											_campo.attr("id",_key);
											_campo.attr("name",_key);
											_campo.attr("class",_dados.c);
											_campo.attr("disabled",_dados.disabled);
											_campo.attr("alt",_dados.alt);
											_campo.attr("maxlength",_dados.maxlength);
											_campo.attr("value",_dados.value);
											_campo.attr("type",_dados.type);
											_div.append(_campo)
										break;
									}
									
			return _div;
}
function addSubset(_fieldset_index,_campo){
	//$("#filhos_"+_campo).children().first().show();
	$("#filhos_"+_campo).append(subset(dadosAjax.fieldsets[_fieldset_index].campos[_campo].subset,_campo));
	$('.edit').setMask();
	$( ".campoRadio" ).buttonset();
	__setCalculoPedido();
			ativaControles();refazMasonry();
}
function __setCalculoPedido(){
	
				$('.preco_ex,.tons,.unitario').keyup(function(){
					if(dadosExpandidos['idclientes']){
						
						var _indice = $(this).attr('indicefilhos');
						var imposto = parseFloat(( 100-dadosExpandidos['idclientes'].icms) / 100);
						var ipi = parseFloat((dadosExpandidos['idclientes'].ipi) / 100);
						
						var _precoex = parseFloat($('#filhos_itens_'+_indice+'_preco_ex').val().replace(",","")) / 100;
						var _unitario = _precoex / imposto;
						var _tons = parseFloat($('#filhos_itens_'+_indice+'_tons').val().replace(".",""));
						
						$('#filhos_itens_'+_indice+'_unitario').val(roundNumber(_unitario,2).replace(".",","));
						
						var _subtotal;
						_subtotal1 = _tons * (roundNumber(_unitario,2));
						_subtotal2 = roundNumber(_subtotal1,2);
						$('#filhos_itens_'+_indice+'_subtotal').val(_subtotal2).keyup();
							
						var __subtotal = 0;
						$(".unitario").each(function(){
							var __indice = $(this).attr('indicefilhos');
							var __tons = parseFloat($('#filhos_itens_'+__indice+'_tons').val().replace(".",""));
							var __preco_ex = parseFloat($('#filhos_itens_'+__indice+'_preco_ex').val().replace(",","")) / 100;
							__subtotal += __tons * roundNumber(( __preco_ex / imposto ),2);
						});
						$('#valor').val(roundNumber(__subtotal,2).replace(".",","));
						
						_ipi = __subtotal * ipi;
						$('#ipi').val(roundNumber(_ipi,2).replace(".",","));
						var total = __subtotal + _ipi;
						$('#total').val(roundNumber(total,2).replace(".",","));

						var _peso = 0;
						$(".tons").each(function(){
							_peso += parseFloat($(this).val().replace(".",""));
							$('#peso').val(_peso);
						});
					}else{
						exibirMensagem('Primeiro selecione o cliente')
					}
				});
}

function __calculaValoresBoletos(){
				
				$('#internet').keyup(function(){
						$("._bolin").each(function(){
							$(this).val($('#internet').val())
						});
				});
				$('#interfone').keyup(function(){
						$("._bolif").each(function(){
							$(this).val($('#interfone').val())
						});
				});
				
				
				$('#taxa_manutencao').keyup(function(){
						$("._boltm").each(function(){
							$(this).val($('#taxa_manutencao').val())
						});
				});
				$('#fundo_reserva').keyup(function(){
						$("._bolfr").each(function(){
							$(this).val($('#fundo_reserva').val())
						});
				});
				$('#chamada_capital').keyup(function(){
						$("._bolcc").each(function(){
							$(this).val($('#chamada_capital').val())
						});
				});
				$('#benfeitoria').keyup(function(){
						$("._bolbf").each(function(){
							$(this).val($('#benfeitoria').val())
						});
				});
				
				
				$('#internet,#interfone,#taxa_manutencao,#fundo_reserva,#chamada_capital,#benfeitoria').keyup(function(){

						$("._bolvl").each(function(){
							__setaValorBoleto( this );
						});
				});
				
				$('._bolin,._bolif,._boltm,._bolfr,._bolrc,._bolcc,._bolbf,._bolsl,._bolou').keyup(function(){
					__setaValorBoleto( this );
				});
}

function __setaValorBoleto( obj ){
						var _indice = $(obj).attr('ib');
						var _valor_bolin = parseFloat($('#_bolin'+_indice).val().replace(",",""));
						var _valor_bolif = parseFloat($('#_bolif'+_indice).val().replace(",",""));
						var _valor_boltm = parseFloat($('#_boltm'+_indice).val().replace(",",""));
						var _valor_bolfr = parseFloat($('#_bolfr'+_indice).val().replace(",",""));
						var _valor_bolrc = parseFloat($('#_bolrc'+_indice).val().replace(",",""));
						var _valor_bolcc = parseFloat($('#_bolcc'+_indice).val().replace(",",""));
						var _valor_bolbf = parseFloat($('#_bolbf'+_indice).val().replace(",",""));
						var _valor_bolsl = parseFloat($('#_bolsl'+_indice).val().replace(",",""));
						var _valor_bolou = parseFloat($('#_bolou'+_indice).val().replace(",",""));
						
						var _v = parseFloat(_valor_bolin + _valor_bolif + _valor_boltm + _valor_bolfr + _valor_bolrc + _valor_bolcc + _valor_bolbf + _valor_bolsl + _valor_bolou ) / 100;
						$("#_bolvl"+_indice).val( roundNumber(_v,2).replace(".",","));
						
						var _valor_estimado = 0;			
						$("._bolvl").each(function(){
							_valor_estimado += parseFloat($(this).val().replace(",","")) / 100;
						});
						
						$("#valor").val( roundNumber(_valor_estimado,2).replace(".",","));

}

function __execCalculoPedido(){
}
function subset(_subset,_key,k,v){
	k = k ? k : '_novo_'+$("#filhos_"+_key).children().length;
									  var __campo = $('<li id="'+_key+'_'+k+'">');
									  
									  if(dadosAjax.propriedades.edicao){
									  	__campo.append('<div class="campoList"><a href="javascript:remover(\''+_key+'\',\''+k+'\');" class="awesome red"><img src="imagens/buttons/delete.png" border="0" align="absmiddle" width="21" height="21"></a></div>');
									  }
									  
									  $.each(_subset.campos,function(oKey, oValue){
										 // __divcampo.append('<label>'+oValue.label+'</label>');
										  switch(oValue.tag){
											  case "select":
												  var __divcampo = $('<div class="campoList">');
												  var ___campo = $("<select />");
												  ___campo.append('<option />');
												  ___campo.attr("id",'filhos_'+_key+'_'+k+'_'+oKey);
												  ___campo.attr("name",'filhos['+_key+']['+k+']['+oKey+']');
												  ___campo.attr("class",oValue.c+' '+oKey);
												  if(v){
													  	___campo.attr("value",v[oKey]);
													  $.each(oValue.opcoes,function(oK, oV){
														  _op = $('<option value="'+oV[0]+'"'+(oV[0] == v[oKey] ? " selected" : "")+'>'+oV[1]+'</option>');
														  ___campo.append(_op);
													  });
												  }else{
													  $.each(oValue.opcoes,function(oK, oV){
														  _op = $('<option value="'+oV[0]+'">'+oV[1]+'</option>');
														  ___campo.append(_op);
													  });
												  }
											  break;
											  case "radio":
												  var __divcampo = $('<div class="campoList">');
												  var ___campo = $('<div class="campoRadio" />');
												  $.each(oValue.opcoes,function(oK, oV){
													  if(v){
														_op = $('<input type="radio" id="filhos_'+_key+'_'+k+'_'+oKey+oK+'" name="filhos['+_key+']['+k+']['+oKey+']" value="'+oK+'"'+" checked"+'><label for="filhos_'+_key+'_'+k+'_'+oKey+oK+'">'+oV+'</label>');
													  }else{
														_op = $('<input type="radio" id="filhos_'+_key+'_'+k+'_'+oKey+oK+'" name="filhos['+_key+']['+k+']['+oKey+']" value="'+oK+'"'+'><label for="filhos_'+_key+'_'+k+'_'+oKey+oK+'">'+oV+'</label>');
													  }
														___campo.append(_op);
													});
											  break;
											  case "input":
												  var __divcampo = $('<div class="campoList">');
												  var ___campo = $("<input />");
												  ___campo.attr("id",'filhos_'+_key+'_'+k+'_'+oKey);
												  ___campo.attr("name",'filhos['+_key+']['+k+']['+oKey+']');
												  ___campo.attr("class",oValue.c+' '+oKey+' '+_key);
												  ___campo.attr("alt",oValue.alt);
												  ___campo.attr("maxlength",oValue.maxlength);
												  ___campo.attr("indiceFilhos",k);
												  if(v) ___campo.attr("value",v[oKey]);
												  
											  break;
											  case "textarea":
												  var __divcampo = $('<div class="campoTextareaFilhos">');
												  var ___campo = $("<textarea />");
												  ___campo.attr("id",'filhos_'+_key+'_'+k+'_'+oKey);
												  ___campo.attr("name",'filhos['+_key+']['+k+']['+oKey+']');
												  ___campo.attr("class",oValue.c+' '+oKey+' '+_key);
												  ___campo.attr("alt",oValue.alt);
												  ___campo.attr("maxlength",oValue.maxlength);
												  ___campo.attr("indiceFilhos",k);
												  if(v) ___campo.append(v[oKey]);
											  break;
											  case "div":
												  var __divcampo = $('<div class="campoList">');
												  var ___campo = $("<input />");
												  ___campo.attr("id",'filhos_'+_key+'_'+k+'_'+oKey);
												  ___campo.attr("name",'filhos['+_key+']['+k+']['+oKey+']');
												  ___campo.attr("class",oValue.c+' '+oKey+' '+_key);
												  ___campo.attr("alt",oValue.alt);
												  ___campo.attr("maxlength",oValue.maxlength);
												  ___campo.attr("indiceFilhos",k);
												  if(v) ___campo.attr("value",v[oKey]);
												  
											  break;
										  }
										  __divcampo.append(___campo);
										  __campo.append(__divcampo);
									  });
		
		return __campo;
}
var dadosExpandidos = [];

var j = 0;
function expandirDados(  ){
	
	obj = $(this);
	_id = obj.attr('id');
	$.getJSON('Nucleo/colherDados/'+_id+'/'+obj.val(),
		function(dataExpand){
			dadosExpandidos["id"+dataExpand.tabela] = dataExpand.propriedades;
		});
}
function grupo(_id, _dados, _tabela){
	var _chechbox = $('<div class="divPergunta'+_dados.nivel+'">');
	var _input = $('<input type="checkbox" id="checkbox_'+_tabela+''+_dados.id+'" name="'+_tabela+'[]" value="'+_dados.id+'"'+(_dados.checked ? ' checked="checked"':"")+'">');
	var _label = $('<label for="checkbox_'+_tabela+''+_dados.id+'">'+_dados.label+'</label>');
	_chechbox.append(_label);
	_chechbox.append(_input);
	
	if(_dados.total > 0){
		var _chechbox2 =	$('<div class="divPergunta1">');
		$.each(_dados.grupos,function(oKey, oValue){
			//_chechbox.append(grupo(oKey, oValue, _tabela));
			var _input = $('<input type="checkbox" id="checkbox_'+_tabela+''+oValue.id+'" name="'+_tabela+'[]" value="'+oValue.id+'"'+(oValue.checked ? ' checked="checked"':"")+'">');
			var _label = $('<label for="checkbox_'+_tabela+''+oValue.id+'">'+oValue.label+'</label>');
			_chechbox2.append(_label);
			_chechbox2.append(_input);
		});
		_chechbox.append(_chechbox2)
	}
	return _chechbox;
}

function thumbRotate( _id , _angle ){
	$('fotos_'+_id).down('img').src = '<?=$admin->localhost?>img/'+_id+'/100/100/1/filter=soft';
		ajaxManager.add({
			url: 'Nucleo/giraImagem/',
			data:'id='+_id+'&angulo='+_angle,
			complete: function( resposta ){
				$('#fotos_'+_id).down('img').src = '<?=$admin->localhost?>img/'+_id+'/100/100/1/filter=pb';
			}
		});
};
function atualizarLegenda( _id ){
		ajaxManager.add({
			url: 'Nucleo/atualizarLegenda/',
			data:'id='+_id+'&l='+$('#legendaInput_'+_id).val(),
			type: 'POST',
			dataType: 'json',
			complete: function( resposta ){
				res = jQuery.parseJSON( resposta.responseText );
				exibirMensagem("Legenda " + res.status + "" )
			}
		});
};


function exibeRelatorioFormulario(_data,_div){
        var data = google.visualization.arrayToDataTable(_data[_div]);
        var chart = new google.visualization.ColumnChart(document.getElementById(_div));
		var options = {
		};

        chart.draw(data, options);
}
function toggleOpcoes( _ele ){
	$('.thumbOpcoes').hide();
	$(_ele).next('.thumbOpcoes').fadeIn();
};


	
	
function foto(_id, _dados){
	var thumbFoto =	'<li id="fotos_${id}" class="thumbDivFotos">'
				+'<a href="<?=$admin->localhost?>img/${id}" class="imgLi" target="blank"><img class="thumbDivHandler imgThumb" src="<?=$admin->localhost?>img/${id}/100/100/1" width="100" height="100" title="${nome_arquivo}\n${tamanho}"/></a>'
				//+(dadosAjax.propriedades.edicao ? '<div class="thumbDivOpcoes" onclick="toggleOpcoes(this)">Opções</div>' : '')
				+'<div class="thumbDivOpcoes" onclick="toggleOpcoes(this)">Opções</div>'
				+'<div class="thumbOpcoes" style="display:none;">'
					+'<div class="thumbOpcoesLink"><a href="javascript:remover(\'fotos\',${id});fechaOpcoes();">Apagar esta imagem</a></div>'
					+'<div class="thumbOpcoesLink"><a href="javascript:thumbRotate(${id},90)">Girar a Esquerda</a></div>'
					+'<div class="thumbOpcoesLink"><a href="javascript:thumbRotate(${id},-90)">Girar a Direita</a></div>'
					+'<div class="thumbDivEditarLegenda">Legenda'
						+'<textarea class="thumbInputLegenda" name="legenda_${id}" type="text" id="legendaInput_${id}">${legenda}</textarea><br><a href="javascript:atualizarLegenda(${id})">Salvar</a>'
					+'</div>'
				+'</div>'
				+'<input name="fotos[]" type="hidden" value="${id}" />'
			+'</li>';
	var dados = [{id:_id, nome_arquivo : _dados.nome_arquivo, tamanho: _dados.tamanho, legenda : _dados.legenda}];
	$.template( "thFoto", thumbFoto );
	return $.tmpl( "thFoto", dados );
};


function enviaCamera(){
		$("#carregando").html("Salvando foto no servidor").fadeIn();
		
						$('#decoded').val($.scriptcam.getFrameAsBase64());
						//exibirMensagem('<img src="data:image/png;base64,' + _imagem + '">')
						$.ajax({
							url: 'Nucleo/uploadCamera/',
							type: 'POST',
							data:$('#formGeral').serialize(),//.replace(/\+/g, '-').replace(/\//g, '_'),
							processData:false,
							complete: function( resposta ){
								try{
									dados = $.parseJSON(resposta.responseText);
									$('#thumbnailsFotos').fadeIn().append(foto(dados.id,dados));
									$('#span'+dados.id).addClass('ok').html('[ Completo '+ dados.nome_arquivo +' ]');
									//exibirMensagem(resposta.responseText)
								}catch(e){
									exibirMensagem(e)
								}
								$("#carregando").hide();
							}
							
						});
}
function camera(_id, _dados){
	var thumbFoto =	'<li id="fotos_${id}" class="thumbDivFotos">'
				+'<img class="thumbDivHandler imgThumb" src="<?=$admin->localhost?>img/${id}/100/100/1" width="100" height="100" title="${nome_arquivo}\n${tamanho}"/>'
				//+(dadosAjax.propriedades.edicao ? '<div class="thumbDivOpcoes" onclick="toggleOpcoes(this)">Opções</div>' : '')
				+'<div class="thumbDivOpcoes" onclick="toggleOpcoes(this)">Opções</div>'
				+'<div class="thumbOpcoes" style="display:none;">'
					+'<div class="thumbOpcoesLink"><a href="javascript:remover(\'fotos\',${id});fechaOpcoes();">Apagar esta imagem</a></div>'
					+'<div class="thumbOpcoesLink"><a href="javascript:thumbRotate(${id},90)">Girar a Esquerda</a></div>'
					+'<div class="thumbOpcoesLink"><a href="javascript:thumbRotate(${id},-90)">Girar a Direita</a></div>'
					+'<div class="thumbDivEditarLegenda">Legenda'
						+'<textarea class="thumbInputLegenda" name="legenda_${id}" type="text" id="legendaInput_${id}">${legenda}</textarea><br><a href="javascript:atualizarLegenda(${id})">Salvar</a>'
					+'</div>'
				+'</div>'
				+'<input name="fotos[]" type="hidden" value="${id}" />'
			+'</li>';
	var dados = [{id:_id, nome_arquivo : _dados.nome_arquivo, tamanho: _dados.tamanho, legenda : _dados.legenda}];
	$.template( "thFoto", thumbFoto );
	return $.tmpl( "thFoto", dados );
};



	
	
function arquivo(_id, _dados){
	var thumbArquivo =	'<li id="arquivo_${id}" class="thumbDivArquivos">'
				+(dadosAjax.propriedades.edicao ? '<a href="javascript:remover(\'arquivos\',${id});" class="awesome grey"><img src="imagens/buttons/delete.png" border="0" align="absmiddle" width="21" height="21"> Deletar</a>' : '')
				//+'<a href="javascript:toggleOpcoes(this)" class="awesome grey"><img src="imagens/Info.png" border="0" align="absmiddle" width="21" height="21"> Opções</a>'
				+'<a href="<?=$admin->localhost?>download/${id}" class="awesome grey" target="_black"><img src="imagens/buttons/save.png" border="0" align="absmiddle" width="21" height="21"> Baixar</a>'
				+' <strong>${nome_arquivo}</strong> - Tamanho: ${tamanho} - Downloads: ${downloads} '
				+'<div class="thumbOpcoes" style="display:none;">'
					+'<div class="thumbDivEditarLegenda">Anotação'
						+'<textarea class="thumbInputLegenda" name="legenda_${id}" type="text" id="legendaInput_${id}">${legenda}</textarea><br><a href="javascript:atualizarLegenda(${id})">Salvar</a>'
					+'</div>'
				+'</div>'
				+'<input name="arquivos[]" type="hidden" value="${id}" />'
			+'</li>';
	var dados = [{id:_id, nome_arquivo : _dados.nome_arquivo, tamanho: _dados.tamanho, downloads : _dados.downloads}];
	$.template( "thArquivo", thumbArquivo );
	var tmp = $.tmpl( "thArquivo", dados );
	return tmp;
};


function tag(campo,tabela,tabela_secundaria,idtabela,dados,_novo){
	
													var _campo = $('<div />');
													_campo.attr("id","" + campo + "_" + dados.idprincipal);
													_campo.attr("class","tag");
													
													
														//ativarInPlaceEditor(dadosAjax.lista.headers,dadosAjax.lista.propriedades.tabela);
														
														_campo.append('Qtd: <strong><span id="quantidade__'+dados.idprincipal+'" class="editQuantidade">'+dados.quantidade+'</span></strong> - ');
														_campo.append('Ganhos: <strong><span id="ganhados__'+dados.idprincipal+'" class="editQuantidade">'+dados.ganhados+'</span></strong> - ');
														_campo.append(dados.valor);

													
														var _link = $('<a href="javascript:remover(\''+campo+'\','+ dados.idprincipal +');" />')
														var __campo = $('<img />');
														__campo.attr("src", "imagens/icons/16x16/delete.png");
														_link.append(__campo);
														_campo.append(_link);
														
														//var __campo = $('<img />');
														//__campo.attr("src", "imagens/icons/16x16/edit.png");
														//_campo.append(__campo);
													
														var __campo = $('<input />');
														__campo.attr("value", dados.id);
														__campo.attr("type","hidden");
														__campo.attr("name","filhos["+tabela+"][" + _novo + dados.id +"][" +"id" + tabela_secundaria + "]");
														_campo.append(__campo);
														
														//var __campo = $('<input />');
														//__campo.attr("value", id);
														//__campo.attr("type","hidden");
														//__campo.attr("name","filhos["+tabela+"][" + id +"][quantidade]");
														//_campo.append(__campo);
																												
													//$("#id" + campo).append(_campo);
													$("#" + tabela).val( "1" );
													
													return _campo;
}

function enviaFormulario( query ){

	$("#carregando").html("Validando informações").fadeIn();	
		$("#carregando").html("Enviando dados para o servidor");
		
		$.ajax({
				url: $('#formGeral').attr('action'),
				data: $('#formGeral').serialize(),
				dataType: 'json',
				type: 'POST',
				complete: function( resposta ){
					try{
						res = jQuery.parseJSON( resposta.responseText );
						if(res.status == 'ok'){
							if(res.funcao == 'formulario'){
								formulario(res.tg+"/"+res.acao+"/"+res.id)
							}else{
								listagem(res.tg+"/"+res.acao);
							}
						}else{
							exibirMensagem(resposta.responseText);
							$("#carregando").hide();
						}
					}catch(e){
						  exibirMensagem("Aconteceu um erro: "+ resposta.responseText + "\r\n<br>E a excessão diz que é:" + e);
						  $("#carregando").hide();
					}
				}
		});
};

function grafico(){
	
		alert(dadosAjax.lista.relatorio.itens);
        //var data = new google.visualization.arrayToDataTable(dadosAjax.lista.relatorio.itens);
			

        //var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        //chart.draw(data, { width: '100%', height: 240 , title: 'Relatorio'});
}

function remover( tabela , id){
	
	
	$("body").append('<div id="dialog">Tem certeza de que deseja remover este item?</div>');
	$("#dialog").dialog({
				modal: true,
				width:500,
				buttons: {
					Cancelar: function() {
						$(this).dialog('destroy');
						$("#dialog").remove();
					},
					Ok: function() {
						
						$(this).dialog('destroy');
						$("#dialog").remove();
						
						$('#'+tabela+'_'+id).html('Excluindo...');
						
						$('.preco_ex').keypress();
						
						$.ajax({
							data:'tabela='+tabela+'&id='+id,
							url:'Nucleo/remover/',
							dataType: 'json',
							type: 'POST',
							complete: function( resposta ){
								try{
									var data = $.parseJSON(resposta.responseText);
								}catch(e){
									exibirMensagem("C1 " + e)
								}
								if(dadosAjax.status == 'ok'){
									$('#'+tabela+'_'+id ).remove();
								}else{
									exibirMensagem(dadosAjax.mensagem);
								}
							}
						});
					}
				}
			});
};





function linkDireto( _link ){
	window.location = _link ;
};

function popup( _link ){
	window.open( _link );
};

function exportar ( tg ){
		new Insertion.Bottom(document.body, "<iframe name=iframeExp id=iframeExp style=\"display:none\"></iframe>");
		iframeExp.location = ''+tg;
};

function imprimirRelatorio ( tg , query ){
		var win = window.open(''+tg+"/imprimirRelatorio"+query, 'impressaoRelatorio');
		win.focus();
};
function imprimirPDF ( id ){
		//svar win = window.open(''+dadosAjax.lista.propriedades.tabela+"/imprimirPDF/"+id, 'imprimirPDF');
		var win = window.open(''+dadosAjax.lista.propriedades.tabela+"/imprimirPDF/"+id, 'imprimirPDF');
		win.focus();
};


var _ret;
function trocarSenha (  ){

	var _div = $('<div id="dialog" title="Alteração de Senha">Para alterar, digite a senha atual.</div>');
	
	$("body").append(_div);
	
							var _form = $('<form id="formSenha">');
								_label = $('<label title="Este campo é obrigatório">Senha Atual</label>');
								_form.append(_label);
								_campo = $("<input id='' name='senha' class='f_varchar-32 required' type='password'/>");
								_form.append(_campo);
								
								_label = $('<label title="Este campo é obrigatório">Nova senha</label>');
								_form.append(_label);
								_campo = $("<input id='' name='senha_nova' class='f_varchar-32 required' type='password'/>");
								_form.append(_campo);
								
								_label = $('<label  title="Este campo é obrigatório">Digite novamente</label>');
								_form.append(_label);
								_campo = $("<input id='' name='denovo' class='f_varchar-32 required' type='password'/>");
								_form.append(_campo);
							_div.append(_form);
							
							_ret = $('<div id="_ret" class="erro"/>');
							
							_div.append(_ret);
							
							
	$("#dialog").dialog({
				modal: true,
				width:300,
				buttons: {
					"Cancelar": function() {
						$(this).dialog('destroy');
						$("#dialog").remove();
					},
					"Salvar": function() {
						$("#carregando").html('Comunicando com o servidor., aguarde...').fadeIn();	
						$.ajax({
							url: 'Nucleo/TrocarSenha',
							dataType: 'json',
							type: 'POST',
							data: $('#formSenha').serialize(),
							complete: function( resposta ){
								try{
									var data = $.parseJSON(resposta.responseText);
									if(data.status == 'ok'){
											$("#dialog").html(data.mensagem);
											$("#dialog").dialog({
												modal: true,
												width:400,
												buttons: {
													"Fechar": function() {
														$(this).dialog('destroy');
														$("#dialog").remove();
													}
												}
											});
									}else{
										$("#_ret").html(data.mensagem);
									}
								}catch(e){
									exibirMensagem("C1 " + e)
								}
								$("#carregando").hide();
							}
							
						});
					}
				}
			});
		 
};
function enviarEmail ( id ){

	$("body").append('<div id="dialog" title="Envio de arquivo por email">Tem certeza de que desejar enviar o email?</div>');
	$("#dialog").dialog({
				modal: true,
				width:500,
				buttons: {
					Cancelar: function() {
						$(this).dialog('destroy');
						$("#dialog").remove();
					},
					"Sim, enviar agora!": function() {
						
						$(this).dialog('destroy');
						$("#dialog").remove();
						$("#carregando").html('Enviando mensagem, aguarde...').fadeIn();	
						
						$.ajax({
							url: ''+dadosAjax.lista.propriedades.tabela+"/enviarEmail/"+id,
							dataType: 'json',
							type: 'POST',
							complete: function( resposta ){
								try{
									var data = $.parseJSON(resposta.responseText);
									if(data.status == 'ok'){
										
											$("#dialog").html(data.mensagem);
											$("#dialog").dialog({
												modal: true,
												width:300,
												buttons: {
													Fechar: function() {
														$(this).dialog('destroy');
														$("#dialog").remove();
													}
												}
											});
									}else{
										exibirMensagem(data.erro);
									}
								}catch(e){
									exibirMensagem("C1 " + e)
								}
								$("#carregando").hide();
							}
							
						});
					}
				}
			});
		 
};

var resquestLote;
var progressbar;
var progressLabel;
							
function enviarLote ( id ){

	$("body").append('<div id="dialog" title="Envio de e-mails em lote">Tem certeza de que prosseguir com o envio?</div>');
	$("#dialog").dialog({
				modal: true,
				width:500,
				buttons: {
					Cancelar: function() {
						$(this).dialog('destroy');
						$("#dialog").remove();
						resquestLote.abort();
					},
					"Sim, prosseguir!": function() {
						$("#carregando").html('Enviando e-mails, aguarde...').fadeIn();
						
							$(this).dialog('destroy');
							$("#dialog").html('<div id="progressbar"><div class="progress-label">Preparando para enviar...</div></div><div style="text-align:center; width:100%" id="destinoBoleto"></div>');
							progressbar = $( "#progressbar" );
							progressLabel = $( ".progress-label" );
	  
	
										progressbar.progressbar({
										  value: false,
										  change: function() {
											progressLabel.text( progressbar.progressbar( "value" ) + "%" );
										  },
										  complete: function() {
											progressLabel.text( "Envio concluído com sucesso!" );
										  }
										});
	
							$("#dialog").dialog({
								modal: true,
								width:700,
								buttons: {
									Interromper: function() {
										$(this).dialog('destroy');
										$("#dialog").remove();
										resquestLote.abort();
											$("#dialog").html('Envio interrompido');
											$("#dialog").dialog({
												modal: true,
												width:300,
												buttons: {
													Fechar: function() {
														$(this).dialog('destroy');
														$("#dialog").remove();
													}
												}
											});
									}
								}
							});
						_enviarLote( id );
						
						
						
					}
				}
			});
		 
};

function _enviarLote( id ){
	
						resquestLote = $.ajax({
							url: ''+dadosAjax.lista.propriedades.tabela+"/enviarEmail/"+id,
							dataType: 'json',
							type: 'POST',
							complete: function( resposta ){
								try{
									var data = $.parseJSON(resposta.responseText);
									if(data.percent < 100){
										_enviarLote( id );
										
										progressbar.progressbar( "value", data.percent );
										
										
						 
										$("#destinoBoleto").html("<strong>"+data.now+"</strong>");

									}else{
										
											$("#dialog").html('Envio Concluído');
											$("#dialog").dialog({
												modal: true,
												width:300,
												buttons: {
													Ok: function() {
														$(this).dialog('destroy');
														$("#dialog").remove();
													}
												}
											});
									}
								}catch(e){
									exibirMensagem("C1 " + e)
								}
								$("#carregando").hide();
							}
							
						});
}
function enviarSenhaCadastro ( id ){

	$("body").append('<div id="dialog" title="Envio de arquivo por email">Tem certeza de que desejar enviar o email com a senha?</div>');
	$("#dialog").dialog({
				modal: true,
				width:500,
				buttons: {
					Cancelar: function() {
						$(this).dialog('destroy');
						$("#dialog").remove();
					},
					"Sim, enviar agora!": function() {
						
						$(this).dialog('destroy');
						$("#dialog").remove();
						$("#carregando").html('Enviando mensagem, aguarde...').fadeIn();	
						
						$.ajax({
							url: ''+dadosAjax.lista.propriedades.tabela+"/enviarSenhaCadastro/"+id,
							dataType: 'json',
							type: 'POST',
							complete: function( resposta ){
								try{
									var data = $.parseJSON(resposta.responseText);
									if(data.status == 'ok'){
										exibirMensagem(data.mensagem);
									}else{
										exibirMensagem(data.erro);
									}
								}catch(e){
									exibirMensagem("C1 " + e)
								}
								$("#carregando").hide();
							}
							
						});
					}
				}
			});
		 
};
function enviarAviso ( id ){

	$("body").append('<div id="dialog" title="Envio de aviso">Tem certeza de que desejar enviar o aviso a todos os condôminos?</div>');
	$("#dialog").dialog({
				modal: true,
				width:500,
				buttons: {
					Cancelar: function() {
						$(this).dialog('destroy');
						$("#dialog").remove();
					},
					"Sim, enviar agora!": function() {
						
						$(this).dialog('destroy');
						$("#dialog").remove();
						$("#carregando").html('Enviando mensagem, aguarde...').fadeIn();	
						
						$.ajax({
							url: ''+dadosAjax.lista.propriedades.tabela+"/enviarAviso/"+id,
							dataType: 'json',
							type: 'POST',
							complete: function( resposta ){
								try{
									var data = $.parseJSON(resposta.responseText);
									if(data.status == 'ok'){
										exibirMensagem(data.mensagem);
									}else{
										exibirMensagem(data.erro);
									}
								}catch(e){
									exibirMensagem("C1 " + e)
								}
								$("#carregando").hide();
							}
							
						});
					}
				}
			});
		 
};
function enviarPreCadastro ( id ){

	$("body").append('<div id="dialog" title="Envio de aviso">Os dados estão corretos?</div>');
	$("#dialog").dialog({
				modal: true,
				width:500,
				buttons: {
					Cancelar: function() {
						$(this).dialog('destroy');
						$("#dialog").remove();
					},
					"Sim, enviar agora!": function() {
						
						$(this).dialog('destroy');
						$("#dialog").remove();
						$("#mensagemPreCadastro").html('Enviando mensagem, aguarde...').fadeIn();	
						
						$.ajax({
							url: "http://recantogolfville.com.br/Pre-Cadastro/Nucleo/enviarPreCadastro/",
							dataType: 'json',
							type: 'POST',
							data: $('#formPreCadastro').serialize(),
							complete: function( resposta ){
								try{
									var data = $.parseJSON(resposta.responseText);
									if(data.status == 'ok'){
										exibirMensagem(data.mensagem);
									}else{
										exibirMensagem(data.erro);
									}
								}catch(e){
									exibirMensagem("C1 " + e)
								}
								$("#mensagemPreCadastro").hide();
							}
							
						});
					}
				}
			});
			return false;
		 
};
function enviarApresentacao ( id ){

	$("body").append('<div id="dialog" title="Envio de arquivo por email">Tem certeza de que desejar enviar o email?</div>');
	$("#dialog").dialog({
				modal: true,
				width:500,
				buttons: {
					Cancelar: function() {
						$(this).dialog('destroy');
						$("#dialog").remove();
					},
					"Sim, enviar agora!": function() {
						
						$("#carregando").html("Enviando mensagem de apresentação").fadeIn();
						$(this).dialog('destroy');
						$("#dialog").remove();
						
						$.ajax({
							url: ''+dadosAjax.lista.propriedades.tabela+"/enviarApresentacao/"+id,
							dataType: 'json',
							type: 'POST',
							complete: function( resposta ){
								try{
									var data = $.parseJSON(resposta.responseText);
									if(data.status == 'ok'){
										exibirMensagem(data.mensagem);
										
										$.post( 'Nucleo/atualizar/', { 'id': 'idstatus_clientes_potenciais__'+id, "tabela":  "clientes_potenciais", "value": "4",  })
										  .done(function( data ) {
											listagem( "clientes_potenciais" );
										  });
									}else{
										exibirMensagem(resposta.responseText);
									}
								}catch(e){
									exibirMensagem("C1 " + e)
								}
							}
							
						});
					}
				}
			});
		 
};
function enviarDocumento ( id ){

	$("body").append('<div id="dialog" title="Envio de arquivo por email">Tem certeza de que desejar enviar o email?</div>');
	$("#dialog").dialog({
				modal: true,
				width:500,
				buttons: {
					Cancelar: function() {
						$(this).dialog('destroy');
						$("#dialog").remove();
					},
					"Sim, enviar agora!": function() {
						
						$("#carregando").html("Enviando cópia do documento por email").fadeIn();
						$(this).dialog('destroy');
						$("#dialog").remove();
						
						$.ajax({
							url: ''+dadosAjax.lista.propriedades.tabela+"/enviarDocumento/"+id,
							dataType: 'json',
							type: 'POST',
							complete: function( resposta ){
								try{
									var data = $.parseJSON(resposta.responseText);
									if(data.status == 'ok'){
										exibirMensagem(data.mensagem);
										$.post( 'Nucleo/atualizar/', { 'id': 'iddata_envio__'+id, "tabela":  "documentos", "value": "NOW",  })
									}else{
										exibirMensagem(resposta.responseText);
									}
								}catch(e){
									exibirMensagem("C1 " + e)
								}
							}
							
						});
					}
				}
			});
		 
};
function atualizarBoleto ( id ){

	$("body").append('<div id="dialog" title="Atualização de boleto">A data de vencimento do boleto será atualizada para 2 dias úteis a partir de hoje e o valor recalculado com juros e multa.</div>');
	$("#dialog").dialog({
				modal: true,
				width:500,
				buttons: {
					Cancelar: function() {
						$(this).dialog('destroy');
						$("#dialog").remove();
					},
					"Sim, enviar agora!": function() {
						
						$("#carregando").html("Atualizando boleto").fadeIn();
						$(this).dialog('destroy');
						$("#dialog").remove();
						
						$.ajax({
							url: ''+dadosAjax.lista.propriedades.tabela+"/atualizarBoleto/"+id,
							dataType: 'json',
							type: 'POST',
							complete: function( resposta ){
								try{
									var data = $.parseJSON(resposta.responseText);
									if(data.status == 'ok'){
										exibirMensagem(data.mensagem);
										atualizar();
									}else{
										exibirMensagem(resposta.responseText);
									}
								}catch(e){
									exibirMensagem("C1 " + e)
								}
							}
							
						});
					}
				}
			});
		 
};

function removerGrupo( tabela ){
	
	
	$("body").append('<div id="dialog" title="Exclusão de ítens">Tem certeza de que deseja remover este(s) item(s)?</div>');
	$("#dialog").dialog({
				modal: true,
				width:500,
				buttons: {
					Cancelar: function() {
						$(this).dialog('destroy');
						$("#dialog").remove();
					},
					"Sim, remover.": function() {
						
						$(this).dialog('destroy');
						$("#dialog").remove();
						$.ajax({
							url : 'Nucleo/removerGrupo/'+tabela,
							data: $('#formRemover').serialize(),
							type:'POST',
							complete:function(){
								$(".ch:checked").parent().parent().slideUp('slow',function(){
											$(this).remove();
											atualizaCoresListagem();
										});
								}
						});
					}
				}
			});
};


function salvarOrdenar(tabela,lista){	

	$("#carregando").html("Salvando").fadeIn();	
	$.ajax({
		url: 'Nucleo/ordenar',
		dataType: 'json',
		type: 'POST',
		data:'tabela='+tabela+'&'+lista,
		complete : function(resposta){
			$("#carregando").hide();
		}
		
	});
};	

	
function carregaCep( cep ){
	$("#carregando").html("Consultando CEP " + cep).fadeIn();
	$.ajax( {
		url: 'Nucleo/carregaCEP/',
		data:'id='+cep,
		dataType: 'json',
		type: 'POST',
		complete : function(resposta){
				var dadosAjax = $.parseJSON(resposta.responseText);
				if(dadosAjax){
					if(dadosAjax.logradouro){
						$('#endereco').val(dadosAjax.logradouro);
					}
					if(dadosAjax.estado){
						$('#idestados').val(dadosAjax.estado);
					}
					if(dadosAjax.bairro){
						$('#bairro').val(dadosAjax.bairro);
					}
					
					var _cidade = dadosAjax.cidade;
					if(dadosAjax.cidade){
						$("#carregando").html('Buscando lista de cidades');

							$.ajax( {
								url: 'Nucleo/carregaCidades/',
								data:'id='+dadosAjax.estado,
								type: 'POST',
								complete : function(resposta){
										var dadosAjax = $.parseJSON(resposta.responseText);
										
										try{
											$.each(dadosAjax.cidades,function(key, value){
													$("#idcidades").append('<option value="'+key+'">'+value+'</option>');
											});
											$('#idcidades').val(_cidade);
										}catch(e){
											exibirMensagem("Imposível carregar a lista de cidades " + e)
										};
										$("#carregando").html('').hide();
								}
							});	
					}
					ativaControles();
				}else{
					exibirMensagem('O CEP digitado não é válido');
				}
		}
	});
	
};

function carregaCidades( _estado ){
	$("#carregando").html("Carregando lista de cidades").fadeIn();
	$("#idcidades").html('Carregando');
	$.ajax( {
		url: 'Nucleo/carregaCidades/',
		data:'id='+_estado,
		type: 'POST',
		complete : function(resposta){
				var dadosAjax = $.parseJSON(resposta.responseText);
				
				try{
					$.each(dadosAjax.cidades,function(key, value){
							$("#idcidades").append('<option value="'+key+'">'+value+'</option>');
					});
				}catch(e){
					exibirMensagem("Imposível carregar a lista de cidades " + e)
				};
				$("#carregando").html('').hide();
		}
	});
	ativaControles();
	
};

function carregaBairros( _cidade ){
	$("#carregando").html("Carregando lista de bairros").fadeIn();
	$("#idbairros").html('');
	$.ajax( {
		url: 'Nucleo/carregaBairros/',
		data:'id='+_cidade,
		type: 'POST',
		complete : function(resposta){
				var dadosAjax = $.parseJSON(resposta.responseText);
				
				try{
					$.each(dadosAjax.bairros,function(key, value){
							$("#idbairros").append('<option value="'+key+'">'+value+'</option>');
					});
				}catch(e){
					exibirMensagem("Imposível carregar a lista de bairros " + e)
				};
		}
	});
	$("#carregando").html('').hide();
	ativaControles();
	
};


var registroMarcados = false;
function marcaRegistros(){
	if(registroMarcados == false){
		$('.ch').prop("checked", true);
		registroMarcados = true;
	}else{
		$('.ch').prop("checked", false);
		registroMarcados = false;
	}
	
};

function fileQueueError(file, error_code, message) {
	try {
		if (error_code !== "") {
			if(error_code == '-100'){
				$('#spanUpload').append('<li id="span'+file.id+'" class="erro">[ '+ file.name +' ] O limite de envio de arquivos é '+settingsFoto.file_upload_limit+'</li>').fadeIn();
			}else if(error_code == '-110'){
				$('#spanUpload').append('<li id="span'+file.id+'" class="erro">[ '+ file.name +' ] O arquivo não pode ser maior do que '+settingsFoto.file_size_limit+'KB</li>').fadeIn();
			}else{
				$('#span'+file.id).html('<li id="span'+file.id+'" class="erro">[ '+ file.name +' ] ['+error_code+message+']'+'</li>').fadeIn();
			}
			return;
		}

	} catch (ex) {
		this.debug(ex);
	}

};

function uploadError(file, error_code, message) {
	try {
		switch (error_code) {
		case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
			$('#span'+file.id).html('[ '+ file.name +' ] Canelado').fadeIn();
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
			$('#span'+file.id).html('[ '+ file.name +' ] Parado').fadeIn();
			break;
		case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
			$('#span'+file.id).html('[ '+ file.name +' ] O arquivo não pode ter tamanha maior do que '+settingsFotos.file_size_limit+'KB').fadeIn();
			break;
		default:
			$('#span'+file.id).html('[ '+ file.name +' ] Erro: "'+message+'"').fadeIn();
			break;
		}
	} catch (ex) {
		this.debug(ex);
	};

};
function fileDialogStart(){
			$("#carregando").html('Enviando arquivo(s) para o servidor:<ul id="spanUpload"></ul>').fadeIn();
}
function fileDialogComplete(num_files_queued) {
	try {
		if (num_files_queued > 0) {
			this.startUpload();
		}
	} catch (ex) {
		this.debug(ex);
	}
};

function fileQueued(file){
	$('#spanUpload').append('<li id="span'+file.id+'">[ '+ file.name +' ]</li>').fadeIn();
}
function uploadStart(file) {
		//exibirMensagem(file);
};

function uploadProgress(file, bytesLoaded) {
	try {
		var percent = Math.ceil((bytesLoaded / file.size) * 100);
		$('#span'+file.id).html(''+percent+'% '+ file.name +'');
	} catch (ex) {
		this.debug(ex);
	}
};

function uploadSuccessFotos(file, server_data) {
	try {
		dados = $.parseJSON(server_data);
		$('#thumbnailsFotos').fadeIn().append(foto(dados.id,dados));
		$('#span'+file.id).addClass('ok').html('[ Completo '+ file.name +' ]');
	} catch (ex) {
		this.debug(ex);
	}
};

function uploadSuccessArquivo(file, server_data) {
	try {
		dados = $.parseJSON(server_data)
		$('#thumbnailsArquivos').fadeIn().append(arquivo(dados.id,dados));
		$('#span'+file.id).addClass('ok').html('[ Completo '+ file.name +' ]');
	} catch (ex) {
		this.debug(ex);
	}
};

function uploadComplete(file) {
	try {
		if (this.getStats().files_queued > 0) {
			this.startUpload();
		} else {
			$("#carregando").html('Envio finalizado').fadeOut();
		}
	} catch (ex) {
		this.debug(ex);
	}
};


function logar(){
	
	$('#mensagemLogin').html('Enviando dados de login...');
	
	$.ajax({
		url:"<?=$admin->localhost.$admin->admin;?>Nucleo/Logar", 
		data: $('#formLogin').serialize(),
		type:'POST',
		sucess: function( resposta ){
				$('#mensagemLogin').update('Conectando...');
		},
		complete: function( resposta ){
				if(resposta.responseText == 'ok'){
					$('#mensagemLogin').html('Redirecionando...');
					window.location = '<?=$admin->localhost.$admin->admin;?>';
				}else{
					$('#mensagemLogin').html(resposta.responseText);
					$('#botaoLogin').html('Tentar novamente');
				}
		}
	});
	return false;
};

function exibirMensagem( _msg){
	
										$("body").append('<div id="dialog">'+_msg+'</div>');
										$("#dialog").dialog({
													modal: true,
													width:700,
													buttons: {
														Ok: function() {
															$(this).dialog('destroy');
															$("#dialog").remove();
														}
													}
												});
}

function roundNumber(number,decimal_points) {
	if(!decimal_points) return Math.round(number);
	if(number == 0) {
		var decimals = "";
		for(var i=0;i<decimal_points;i++) decimals += "0";
		return "0."+decimals;
	}

	var exponent = Math.pow(10,decimal_points);
	var num = Math.round((number * exponent)).toString();
	return num.slice(0,-1*decimal_points) + "." + num.slice(-1*decimal_points)
}


var addressFormatting = function(text){
	var newText = text;
	//array of find replaces
	var findreps = [
		{find:/^([^\-]+) \- /g, rep: '<span class="ui-selectmenu-item-header">$1</span>'},
		{find:/([^\|><]+) \| /g, rep: '<span class="ui-selectmenu-item-content">$1</span>'},
		{find:/([^\|><\(\)]+) (\()/g, rep: '<span class="ui-selectmenu-item-content">$1</span>$2'},
		{find:/([^\|><\(\)]+)$/g, rep: '<span class="ui-selectmenu-item-content">$1</span>'},
		{find:/(\([^\|><]+\))$/g, rep: '<span class="ui-selectmenu-item-footer">$1</span>'}
	];
	
	for(var i in findreps){
		newText = newText.replace(findreps[i].find, findreps[i].rep);
	}
	return newText;
}	

			function onError(errorId,errorMsg) {
				exibirMensagem(errorMsg);
			}			
			function changeCamera() {
				$.scriptcam.changeCamera($('#cameraNames').val());
			}
			function onWebcamReady(cameraNames,camera,microphoneNames,microphone,volume) {
				$.each(cameraNames, function(index, text) {
					$('#cameraNames').append( $('<option></option>').val(index).html(text) )
				}); 
				$('#cameraNames').val(camera);
			}
			
			

<? if(0){?></script><? }

//pre($db);
//$timeEnd = microtime(true);
//header('X-Database-Objects-Createds: '.count($_SESSION['objetos']));
//header('X-Server-Elapsed-Time: '.round($timeEnd-$timeIni,2));
//header('X-Server-Memory-Usage: '.round(memory_get_usage()/1024)."kb");
//header('X-Server-Memory-Peak-Usage: '.round(memory_get_peak_usage()/1024));
//$content = ob_get_clean();


//$packer = new JavaScriptPacker($content, 'Normal', true, false);
//$packed = $packer->pack();

//echo $content;

?>