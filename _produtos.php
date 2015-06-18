<?
parse_str($pagina->extra);
ob_start();

	$titulo = "Produtos";
	//$DN = 1;
	if($pagina->tg == 'T' or $pagina->tg == 'M' or $pagina->tg == 'Twitter'){
		$pagina->id = $pagina->acao;
		$pagina->acao = "Ver";
		$canal = new objetoDb('canais','Produtos');
	}
	switch($pagina->acao){
		
		case "EnviarPedido":
			include('_produtos_pedido.php');
		break;
			
		case 'Ver' : 
			include("_produtos_descricao.php");
		break;
		case "Top":
			include('_produtos_top.php');
			include('_produtos_listagem.php');
		break;
		case "Video":
			include('_produtos_video.php');
			include('_produtos_listagem.php');
		break;
		case "Categoria":
			include('_produtos_categoria.php');
			include('_produtos_listagem.php');
		break;
		case "Linha":
			include('_produtos_linha.php');
			include('_produtos_listagem.php');
		break;
		case "Fabricante":
			include('_produtos_fabricante.php');
			include('_produtos_listagem.php');
		break;
		case "Buscar":case "Busca":
			include('_produtos_busca.php');
			include('_produtos_listagem.php');
		break;
		default:
			include('_produtos_destaques.php');
			include('_produtos_listagem.php');
		break;
	}
$bufferProdutos = ob_get_clean();

if(($estiloDC || $DN || $FACEBOOK) && 0){ ?>
    <div id="corpoL">
    <?=$bufferProdutos?>
    </div>
<? }else{ ?>
    <?php /*?><div id="corpo"><?php */?>
    <?=$bufferProdutos?>
    <?php /*?></div>

	<div id="lateral">
                  <iframe src="https://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2FMesacor&amp;width=200&amp;height=290&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=true&amp;appId=244455668952934" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:290px;" allowtransparency="true"></iframe>

            
			<?
			$db->query("select * from galerias where ativo = 'sim' and idtipos_de_banners = 2 order by rand()");
			$bannerLD = 0;
			if($db->rows){ ?>
					<? while($res = $db->fetch()){
						$obj = new objetoDb('galerias',$res['idgalerias']); ?>
                        <div id="bannerLateral<?=++$bannerLD?>" class="bannerLateral">
						<? foreach($obj->fotos as $foto){  ?>
							<a href="<?=$obj->link?>?utm_source=Banner&utm_campaign=<?=substr(diretorio($obj->referencia),0,30)?>&utm_medium=bannerLateral<?=$bannerLD?>">
							<img src="<?=$pagina->localhost?>img/<?=$foto['id']?>/200" title="<?=htmlentities($obj->referencia)?>" alt="<?=$obj->referencia?>" />
							</a>
						<? }?>
						</div>
					<? }?>
			<? }?>
	</div><?php */?>
<? }?>