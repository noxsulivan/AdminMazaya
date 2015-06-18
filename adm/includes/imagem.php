<?php
extract($_REQUEST);
$res = $db->fetch("select * from fotos where idfotos = '".$admin->id."'"); 


		//header("Cache-Control: max-age=21600");
		//header("Last-Modified: Mon, 27 Aug 2007 18:21:00 GMT");
		//header("Expires: Wed, 27 Aug 2008 18:21:00 GMT");
		
		header('Content-type: image/jpeg');
		//header('Content-Disposition: attachment; filename="'.$res["url"].'"');

		/*
		$cache = "_cache/".diretorio($res["url"]).".".$res["size"].".".base64_encode($_SERVER["QUERY_STRING"]);
		if(!is_dir("_cache"))
			mkdir("_cache", 0700);
		
		if(file_exists($cache)){
			echo file_get_contents($cache);
			die;
		}
		*/
		
if ($res)
{
		
		
		
		
		$img_db = imagecreatefromstring( $res["arquivo"] );
		
		
		$width_orig	=	imagesx( $img_db );
		$height_orig	=	imagesy( $img_db );
		
		$width 	= isset($_REQUEST["width"] ) ? trim($_REQUEST["width"]) : $width_orig;
		$height = isset($_REQUEST["height"]) ? trim( $_REQUEST["height"] ) : $height_orig;
			
		if(!$force){
			
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
		}else{
			
			$m_prop = min($width_orig/$width , $height_orig/$height);
			
			$sx = ($width_orig/2) - ($width/2)*$m_prop;
			$sy = ($height_orig/2) - ($height/2)*$m_prop;
			
			$width_orig = $width * $m_prop;
			$height_orig = $height * $m_prop;
		}
		
		// Resample
		$image_p = imagecreatetruecolor($width, $height);
		
		imagecopyresampled($image_p, $img_db, 0, 0, $sx, $sy, $width, $height, $width_orig, $height_orig);
		
		
		
		ob_start();
		
		imagejpeg( $image_p, '', 75 );
		
		$img = ob_get_contents();
		
		ob_end_clean();
		
		
		echo $img;
		file_put_contents($cache,$img);
		
		imagedestroy( $image_p );
}

?>