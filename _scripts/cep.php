<?
include('../ini.php');
//header("HTTP/1.0 304 Not Modified");
$pagina = new layout('Home');
   // initialize ob_gzhandler function to send and compress data
   // send the requisite header information and character set
   //header ("content-type: text/javascript; charset: UTF-8");
   // check cached credentials and reprocess accordingly
   //header ("Cache-Control: no-store, no-cache, must-revalidate");
   //header ("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
   //header ("Pragma: no-cache");
	
if(0){?><script><? }?>


	function carregaCep( _cep ){
		$('#carregandoCEP').html("Consultando CEP " + _cep).fadeIn();
		$.ajax({
			url: '<?=$pagina->localhost?>_Request' + '/carregaCEP/'+_cep, 
			complete (resposta){
					var res = $.parseJSON( resposta.responseText );
					if(res){
						if(res.logradouro){
							$('#endereco').val(res.logradouro);
						}
						$('#carregandoCEP').html('Selecionando estado');
						if(res.estado){
							//$('#idestados').selectedIndex = res.estado;
							$('#estado').val(res.estado);
						}
						$('#carregandoCEP').html('Selecionando cidade');
						if(res.cidade){
							$('#carregandoCEP').html('Buscando lista de cidades');
							$('#cidade').load('<?=$pagina->localhost?>_Request' + '/carregaCidades/'+res.estado+"/"+res.cidade,
								function() {
									if(res.bairro){
										$('#carregandoCEP').html('Buscando lista de bairros');
										$('#bairro').load('<?=$pagina->localhost?>_Request' + '/carregaBairros/'+res.cidade+"/"+res.bairro);
									}else{
										$('#bairro').html("<option value=''>Outro:</option>").disable();
										$('#bairro').append('<input type="text" id="bairro" name="bairro" class="input inputTextoPequeno" value="">');
									}
								});
						}
					}else{
						alert('O CEP digitado não é válido')
					}
					$('#carregandoCEP').html('Carregando...').fadeOut();
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
	
<? if(0){?></script><? }?>