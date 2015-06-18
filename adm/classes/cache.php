<?php
class cache
{

		var $cache_dir;
		var $cache_time;
		
		var $cachinge;
		var $file;
		
		
    function cache()
    {
		if (isset($_SERVER['HTTP_ACCEPT_ENCODING']))
			$encodings = explode(',', strtolower(preg_replace("/\s+/", "", $_SERVER['HTTP_ACCEPT_ENCODING'])));
	
		if ((in_array('gzip', $encodings) || in_array('x-gzip', $encodings) || isset($_SERVER['---------------'])) && function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')) {
			$enc = in_array('x-gzip', $encodings) ? "x-gzip" : "gzip";
			$supportsGzip = true;
		}
		
		$this->cache_dir = $_SERVER['DOCUMENT_ROOT'].'/_cache_php/';//This is the directory where the cache files will be stored;
		$this->cache_time = 1000;//How much time will keep the cache files in seconds.
		
		$this->caching = false;
		$this->file = '';
        //Constructor of the class
        $this->file = $this->cache_dir . diretorio( $_SERVER['REQUEST_URI'] );
		
		header("X-Cached-File: " . $this->file);
		
        if ( 0 )// file_exists ( $this->file ) && ( fileatime ( $this->file ) + $this->cache_time ) > time() && count($_POST) == 0 )
        {
            //Grab the cache:
            $data = file_get_contents( $this->file);
			
			
				if ($supportsGzip) {
						header("Content-Encoding: " . $enc);
						$cacheData = gzencode($data, 9, FORCE_GZIP);
						header('Content-Length: '.strlen($cacheData));
					echo $cacheData;
				} else {
						header('Content-Length: '.strlen($data));
					echo $data;
				}
            fclose($handle);
            exit();
        }
        else
        {
            //create cache :
            $this->caching = true;
            ob_start();
        }
    }
    
    function close()
    {
        //You should have this at the end of each page
        if ( $this->caching )
        {
            //You were caching the contents so display them, and write the cache file
            $data = ob_get_clean();
				if ($supportsGzip) {
						header("Content-Encoding: " . $enc);
						$cacheData = gzencode($data, 9, FORCE_GZIP);
						header('Content-Length: '.strlen($cacheData));
					echo $cacheData;
				} else {
						header('Content-Length: '.strlen($data));
					echo $data;
				}
            $fp = fopen( $this->file , 'w' );
            fwrite ( $fp , $data );
            fclose ( $fp );
        }
    }
}
?>