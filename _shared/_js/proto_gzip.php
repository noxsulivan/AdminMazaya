<?php

//die('d'.__LINE__);		
	$_serverRoot = $_SERVER['DOCUMENT_ROOT']."/";	

	function __autoload($class_name) {
    	global $_serverRoot;
		if(file_exists($_serverRoot.'adm/classes/' .$class_name.  '.php')){
			require_once $_serverRoot.'adm/classes/' .$class_name.  '.php';
		}else{
			var_dump(debug_backtrace());
			die("Verifique os arquivos instalados. A Classe $class_name não pode ser definida.");
		}
	}
	
	
	$timeIni = microtime(true);
	//session_start();
	//error_reporting(E_ERROR | E_WARNING | E_PARSE);
		
	// Headers
	header("Content-type: application/javascript");
	//header("Vary: Accept-Encoding");  // Handle proxies
	//if(isset($_SESSION['proto_gzip']) and $_SESSION['proto_gzip'] == $_SERVER['QUERY_STRING'])
		//header("HTTP/1.0 304 Not Modified");
	//$_SESSION['proto_gzip'] = $_SERVER['QUERY_STRING'];
	
	
	
	$expires = 60 * 60 * 24 * 30;
	$cache_time = filemtime($_SERVER['SCRIPT_FILENAME']);
	
	
	header("Last-Modified: " . gmdate("D, d M Y H:i:s",$cache_time) . " GMT");
	if(getenv("HTTP_IF_MODIFIED_SINCE") == gmdate("D, d M Y H:i:s",$cache_time). " GMT")
		header ("HTTP/1.0 304 Not Modified");
	
	header("Expires: " . gmdate("D, d M Y H:i:s",$cache_time+$expires) . " GMT");
	header("Cache-Control: max-age=$expires, must-revalidate", true);
	header('Pragma:cache', true);
	
	//die($_SERVER['SCRIPT_FILENAME'].'-'.gmdate("D, d M Y H:i:s",$cache_time));
	// Get input
	$core = getParam("core", "true") == "true";
	$suffix = getParam("suffix", "_src") == "_src" ? "_src" : "";
	$cachePath = realpath("."); // Cache path, this is where the .gz files will be stored
	$expiresOffset = 3600 * 24 * 10; // Cache for 10 days in browser cache
	$content = "";
	$encodings = array();
	$supportsGzip = false;
	$enc = "";
	$cacheKey = "";

	// Custom extra javascripts to pack
	if($_REQUEST['sc']){
		if(preg_match("/jquery/i",$_REQUEST['sc']))
			$sc = $_REQUEST['sc']	;
		else
			$sc = base64_decode($_REQUEST['sc']);
		$_t = explode(",",$sc);
		foreach($_t as $_s){
			if(file_exists($_s."_m.js"))
				$custom[] = $_s."_m.js";
			else
				$custom[] = $_s.".js";
		}
	}else{
		$custom = array(
			'prototype.js',
			'scriptaculous.js',
			'swfupload.js',
			'validation.js',
			'effects.js',
			'builder.js',
			'effects.js',
			'controls.js',
			'dragdrop.js',
			'slider.js',
			'datepicker.js',
			'lightbox.js'
		);
	}



	// Check if it supports gzip
	if (isset($_SERVER['HTTP_ACCEPT_ENCODING']))
		$encodings = explode(',', strtolower(preg_replace("/\s+/", "", $_SERVER['HTTP_ACCEPT_ENCODING'])));

	if ((in_array('gzip', $encodings) || in_array('x-gzip', $encodings) || isset($_SERVER['---------------'])) && function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')) {
		$enc = in_array('x-gzip', $encodings) ? "x-gzip" : "gzip";
		$supportsGzip = true;
	}
	// Add custom files
	foreach ($custom as $file){
		if(preg_match("/sifr/i",$file)){
			$content .= "\n\n\n".(getFileContents($file));//getFileContents($file);
		}else{
			//$content .= "\n\n\n".JSMin::minify(getFileContents($file));//getFileContents($file);
			$content .= "\n\n\n".getFileContents($file);//getFileContents($file);
		}
	}



header('X-Server-Memory-Usage: '.round(memory_get_usage()/1024)."kb");
header('X-Database-Objects-Createds: '.count($_SESSION['objetos']));

	// Generate GZIP'd content
	if ($supportsGzip and 0) {
			header("Content-Encoding: " . $enc);
			
			$timeEnd = microtime(true);
			header('X-Server-Elapsed-Time: '.round($timeEnd-$timeIni,2));
			
		// Stream to client
			//echo $cacheData;
			//$packer = new JavaScriptPacker($content, 'Normal', true, false);
			//$packed = $packer->pack();
			
			//header('Content-Length: '.strlen($packed));
			$cacheData = gzencode($content, 9, FORCE_GZIP);
			header('Content-Length: '.strlen($cacheData));
			echo $cacheData;
	} else {
		// Stream uncompressed content
			$timeEnd = microtime(true);
			header('X-Server-Elapsed-Time: '.round($timeEnd-$timeIni,2));
			
			//$packer = new JavaScriptPacker($content, 'Normal', true, false);
			//$packed = $packer->pack();
			
	//die(__LINE__."aqui".$enc);
			header('Content-Length: '.strlen($content));
			echo $content;
			//echo $content;
	}


	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	function getParam($name, $def = false) {
		if (!isset($_GET[$name]))
			return $def;

		return preg_replace("/[^0-9a-z\-_,]+/i", "", $_GET[$name]); // Remove anything but 0-9,a-z,-_
	}

	function getFileContents($path) {
		$path = realpath($path);

		if (!$path || !@is_file($path))
			return "";

		if (function_exists("file_get_contents"))
			return @file_get_contents($path);

		$content = "";
		$fp = @fopen($path, "r");
		if (!$fp)
			return "";

		while (!feof($fp))
			$content .= fgets($fp);

		fclose($fp);

		return $content;
	}
?>