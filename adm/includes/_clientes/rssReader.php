<?
	include("../ini.php");


	$db->query("select * from fontes_rss order by ordem");
	$resource = $db->resourceAtual;
	while($res = $db->fetch()){

		if($xml = new rssReader($res['rss'])){
			pre("Atualizando ". $res['fonte'].' '.$res['rss']);
			
			for ($i = 0; $i < sizeof($xml->itens); $i++){
				$_POST['titulo'] = eregi_replace("[0-9]{1,2}/[0-9]{1,2}/[0-9]{4} - ","",utf8_decode($xml->itens[$i]->title));
				$_POST['texto'] = utf8_decode($xml->itens[$i]->description);
				$_POST['link'] = $xml->itens[$i]->link;
				$_POST['guid'] = md5(diretorio($xml->itens[$i]->title));
				if($xml->itens[$i]->pubDate)
					$_POST['data'] = ex_data(fromRFC2822($xml->itens[$i]->pubDate)) ;
				else
					$_POST['data'] = ex_data(fromRFC2822($xml->itens[$i]->pubdate)) ;
				$_POST['fonte'] = $res['fonte'].' '.$res['link'];
				$db->query("select * from noticias where guid = '".$_POST['guid'] ."'");
				pre("Status para a noticia ".substr(utf8_decode($xml->itens[$i]->title),0,20) . ' - '.$_POST['guid'] ." - ".$db->rows);
				if(!$db->rows){
					$db->inserir("noticias");
					pre("inserido ".$xml->itens[$i]->title);
				}
			}
		}
		$db->resource($resource);
	}

?>