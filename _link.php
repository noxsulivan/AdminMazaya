
  <div id="contentFull">
<?	
		//echo $canal->titulo();
		
		//echo 'Voc� est� sendo direcionado para uma p�gina existente em outro site. Se uma nova janela n�o abriu, verifique o seu programa anti-popup, ou <a href="'.$canal->atributos['Endere�o'].'" target="blank">clique aqui</a> para visitar o site.';
		
		
		
		if(ereg("pop",$canal->atributos['Janela'])){
			echo '
				<script>
					w = window.open(\''.$canal->atributos['Endere�o'].'\',\''.$canal->atributos['Janela'].'\');
					w.focus();
				</script>';
		}elseif(ereg("_self",$canal->atributos['Janela'])){
			echo '
				<script>
					location="'.$canal->atributos['Endere�o'].'";
				</script>';
		}else{
			echo '
				<iframe id="frameLink" frameborder="0" name="frameLink" height="600" width="100%" src="'.$canal->atributos['Endere�o'].'">
				</iframe>';
		}
				
							/*echo '
							<div class="comandos">
							<a href="'.$pagina->localhost.$canal->url.'" class="linkRecomendar">Voltar</a>
							<a href="#" class="linkMarcador marker" onclick="marcarComparacaoExterna('.$id.');">Solicitar mais informa��es</a>
							<a href="#" class="linkRecomendar" onclick="recomendarPacote('.$res['idpacotes'].');">Recomendar</a>
							</div>';*/
	
?>
	
  </div> 
  <script>
		$('frameLink').observe('load', function(event){
		urchinTracker($('frameLink').src);
		//alert($('frameLink').src)
		});

  </script>