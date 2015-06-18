<?

		if(file_exists($pagina->root.$canal->atributos_de_canais[1]->transporte)){
			include($pagina->root.$canal->atributos_de_canais[1]->transporte);
		}else{
			echo '
			<div id="content">
			<h2>O script '.$pagina->root.$canal->atributos_de_canais[14]->transporte.' não foi propriamente instalado.</h2>
			</div>';
		}
	
?>