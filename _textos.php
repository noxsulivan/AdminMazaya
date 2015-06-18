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
    <?=$canal->texto?>
</div>