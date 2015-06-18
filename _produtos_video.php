<?

$id = ( ($pagina->id) ? $pagina->id:"Cafeteiras-Francesas" );
$video = new objetoDb("videos",$id);
						$busca = $video->busca;
						
						$_POST['termo'] = $busca;
						
						if(!preg_match("/bot/i",$_SERVER['HTTP_USER_AGENT']))
						$db->query("insert into buscas ( termo) values ('".trim(strtolower(normaliza($busca)))."') on duplicate key update contagem = contagem + 1;");
						//}
						$busca = preg_replace("/\+/i"," +",$busca);
						$termosBusca = explode(" ",normaliza($busca));

						$whe = array();
						foreach($termosBusca as $_t){
							if(strlen($_t) > 2){
								$where[] = "(soundex(produto) like soundex('".preg_replace("/\+/i","",$_t)."')
																								or produto like '%".preg_replace("/\+/i","",$_t)."%'
																								or codigo like '%".preg_replace("/\+/i","",$_t)."%'
																								or linha like '%".preg_replace("/\+/i","",$_t)."%')";
								$db->query("insert into buscas (termo) values ('".trim(strtolower(normaliza($_t)))."') on duplicate key update contagem = contagem + 1;");
							}
						}
						
						
						
						
						$sql = "
						SELECT produtos.idprodutos, produtos.codigo, produtos.descricao_curta
						FROM produtos left join linhas on produtos.idlinhas = linhas.idlinhas
						where 
							produtos.ativo = 'sim' and ".implode(" and ",$where)."
						group by produtos.idprodutos";
							
							echo "<!-- $sql -->";
							
							
?>
<div id="bannerVideo">
<h2>Video: <?=$video->video?></h2>
<div id="divFrame">
<iframe width="853" height="480" src="<?=$video->link?>" frameborder="0" allowfullscreen></iframe>
</div>
<h3><?=$video->descricao;?></h3>
</div>
<?


        $ogvideosite_name = "Video: ".$video->video;
        $ogvideourl = $video->link;
        $ogvideotitle = "Video: ".$video->video;
        $ogvideoimage = $pagina->img($video->fotos[0]['id']);

        $ogvideodescription = $video->descricao;

        $ogvideotype = "video";
        $ogvideo = "http://www.youtube.com/v/-hXRSXfLrK8?version=3&amp;autohide=1";
        $ogvideotype = "application/x-shockwave-flash";
        $ogvideowidth = "1280";
        $ogvideoheight = "720";
							
						$db->query($sql);
						$total = $db->rows;
						$tituloCateg = "Conheça nossos produtos";
?>
