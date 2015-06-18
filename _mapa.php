<div id="menu-interno">
<?
		$pagina->menu(array('pai'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id),'nivel'=>2,'proprio'=>($canal->canais->id == 0 ? $canal->id : $canal->canais->id)))
?>
</div>
<div id="conteudo-interno">
<h3><?=$canal->canal?></h3>
    <? $pagina->menu(array(pai=>0,'submenu'=>1,'nivel'=>2,'status'=>1))?>
</div>