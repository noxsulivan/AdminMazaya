<?

include('../ini.php');
//if($_ENV['REDIRECT_Permalinked']){
	list( $lixo , $id , $_width , $_height , $force , $extras) = explode("/",$_SERVER['QUERY_STRING']);
	$a = explode("&",$extras);
	foreach($a as $b){
		list($k,$v) = explode("=",$b);
		$$k = $v;
	}
//}else{
	//$id = $_REQUEST['id'];
	//$_width = $_REQUEST['height'];
	//$_height = $_REQUEST['width'];
//	$force = $_REQUEST['force'];
//}

$query = @eregi_replace('img|jpg','',$_SERVER['QUERY_STRING']);

if ($id){
	$cache =  "_cache/".diretorio($query).".jpg";
	
	header('Content-type: image/jpeg');
	//die($cache);

	//header('Content-Disposition: attachment; filename="'.diretorio($_SERVER['QUERY_STRING']).".jpg".'"');
	
	//$gmdate_e = date('D, d M Y H:i:s', mktime(0,0,0,12,12,2012) ) . ' GMT';
	//header("Expires: $gmdate_e");
	
	
	//header("Cache-Control: max-age=3600, must-revalidate");
	//header("Pragma: cache");
	//header("Cache-Control: store");
		
	if(file_exists('../'.$cache) and 0){
	
			$gmdate_c = date('D, d M Y H:i:s', filemtime('../'.$cache) ) . ' GMT';
			header("Last-Modified: $gmdate_c");
			header("Mazaya-Cache: True");
			header("Mazaya-Cache-Time: ".date("D, d M Y H:i:s",filemtime('../'.$cache)));
			
			//$mtime = filectime ($cache);
			//$gmdate_c = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
			
			//header("HTTP/1.0 304 Not Modified");
			
			//$cache = "_imagens/checkbox.gif";
			
			header('Content-Length: '.filesize('../'.$cache));
			echo file_get_contents('../'.$cache);
			//header('Location: '.$_localhost.$cache);exit;
	
	}elseif($force == 2){
				
			$foto = new objetoDb('fotos',$id);
			$img_db = @imagecreatefromstring( $foto->arquivo );
			
			$width_orig	=	imagesx( $img_db );
			$height_orig	=	imagesy( $img_db );
			
			$width 	= isset($_width ) ? trim($_width) : $width_orig;
			$height = isset($_height) ? trim( $_height ) : $height_orig;
			$image_p = imagecreatetruecolor($width, $height);
			$image_f = imagecreatetruecolor($width, $height);
			$background = imagecolorallocate($image_p, 255, 255, 255);
			
			imagefill($image_p, 0, 0, $background);
			imagefill($image_f, 0, 0, $background);
			
				$posicao	=	$width_orig  / $height_orig;
				
				if($posicao < 1){
					$scale	= $height/$height_orig;
						$sx = ($width/2)-($width_orig*$scale/2);
						$sy = 0;
				}else{
					$scale	= $width/$width_orig;
						$sx = 0;
						$sy = 0;//(($height/2) - ($height_orig*$scale/2));
				}
				imagecopyresampled($image_p, $img_db, 0, 0, 0,0,$width_orig*$scale, $height_orig*$scale, $width_orig, $height_orig);
			
			
			imagecopyresampled($image_f, $image_p, $sx, $sy, 0, 0, $width, $height, $width, $height);
			
			ob_start();
			imagejpeg( $image_f,'', 90 );
			$img = ob_get_contents();
			header('Content-Length: '.ob_get_length());
			ob_end_clean();
			
			echo $img;
			file_put_contents($cache,$img);
			
			imagedestroy( $image_p );
	}else{
	
			$foto = new objetoDb('fotos',$id);
			//echo($foto->nome_arquivo);die();
				
			$img_db = @imagecreatefromstring( $foto->arquivo );
			
			
			$width_orig	=	imagesx( $img_db );
			$height_orig	=	imagesy( $img_db );
			
			$width 	= ($_width > 0 ) ? trim($_width) : $width_orig;
			$height = ($_height > 0) ? trim( $_height ) : $height_orig;
				
			if($force){
				$m_prop = min($width_orig/$width , $height_orig/$height);
				
				$sx = ($width_orig/2) - ($width/2)*$m_prop;
				$sy = ($height_orig/2) - ($height/2)*$m_prop;
				
				$width_orig = $width * $m_prop;
				$height_orig = $height * $m_prop;
			}else{
				
				$scale	=	min($width  / $width_orig,	$height /  $height_orig);
				if($scale < 1){
					$width	=	floor( $scale * $width_orig );
					$height =	floor( $scale * $height_orig );
				}else{
					$width	=	$width_orig ;
					$height =	$height_orig ;
				}
				
				$sx = 0;
				$sy = 0;
			}
			
			switch($pos){
				case 'top':
					$sy = 0;
				break;
			}
			// Resample
			if($sp == true){
				 $height +=  $height;
				 $height_orig += $height_orig;
				 $img_sp = imagecreatefromstring( $foto->arquivo );
				//imagefilter($img_sp, IMG_FILTER_GAUSSIAN_BLUR);
				//imagefilter($img_sp, IMG_FILTER_SMOOTH,6);
				//imagefilter($img_sp, IMG_FILTER_SMOOTH, 250);
				//imagefilter($img_sp, IMG_FILTER_GRAYSCALE);
				imagefilter($img_sp, IMG_FILTER_BRIGHTNESS, 50);
				imagefilter($img_sp, IMG_FILTER_CONTRAST, 0);
				imagefilter($img_sp, IMG_FILTER_GRAYSCALE);
			}
			$image_p = imagecreatetruecolor($width, $height);
			
			imagealphablending( $image_p, false );
			imagesavealpha( $image_p, true );
			
			if($filtro == 'pb' && function_exists('imagefilter')){
				//imagefilter($img_db, IMG_FILTER_COLORIZE, 255,255,200);
				imagefilter($img_db, IMG_FILTER_GRAYSCALE, -20);
				imagefilter($img_db, IMG_FILTER_BRIGHTNESS, -50);
				imagefilter($img_db, IMG_FILTER_CONTRAST, -50);
			}
			
			if($filtro == 'soft' && function_exists('imagefilter')){
				//imagefilter($img_db, IMG_FILTER_COLORIZE, 255,255,200);
				imagefilter($img_db, IMG_FILTER_GRAYSCALE, -50);
				imagefilter($img_db, IMG_FILTER_BRIGHTNESS, 10);
			}
				
			
			if($sp == true){
				imagecopyresampled($image_p, $img_sp, 0, 0, $sx, $sy, $width, $height, $width_orig, $height_orig);
				imagecopyresampled($image_p, $img_db, 0, $height/2, $sx, $sy, $width, $height, $width_orig, $height_orig);
			}else{
				imagecopyresampled($image_p, $img_db, 0, 0, $sx, $sy, $width, $height, $width_orig, $height_orig);
			}
			if($_REQUEST['rotate']){
				$image_p = imagerotate($image_p, $_REQUEST['rotate'], 0);
			}
			
			if(max($width, $height) > 300 and file_exists("../_imagens/watermark.png")){
				$wm = imagecreatefrompng( "../_imagens/watermark.png" );
				//imagealphablending( $wm, false );
				//imagesavealpha( $wm, true );
				//imagecopyresampled($wm, $wm, 0, 0, 0, 0, 50, 50, 200, 200);
				$wmwidth = imagesx($wm);  
				$wmheight = imagesy($wm);
				
				//imagecopymerge ( $image_p, $wm, $width-$wmwidth-5, $height-$wmheight-5, 0, 0, $wmwidth, $wmheight, 50 );
				$foto->nome_arquivo = "tmp.png";
			}
			
			//$gmdate_c = date('D, d M Y H:i:s',filemtime($cache)) . ' GMT';
			header("Last-Modified: $gmdate_c");
			header("Mazaya-Cache: False");
			//header("Mazaya-Cache-Time: ".date("D, d M Y H:i:s",filemtime($cache)));
			
			
			ob_start();
			if(@eregi("\.png$",$foto->nome_arquivo)){
				imagepng( $image_p, '',  9);
			}else{
				//imagejpeg( $image_p,'', $q ? $q : 100 );
				imagejpeg( $image_p,'', 90 );
			}
			$img = ob_get_contents();
			header('Content-Length: '.ob_get_length());
			ob_end_clean();
			
			echo $img;
			file_put_contents('../'.$cache,$img);
			
			imagedestroy( $image_p );
	}
}else{
	echo "<h1>O endereço não corresponde a uma imagem válida</h1>";
}
?>