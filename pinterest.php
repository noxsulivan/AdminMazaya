<?

	include("ini.php");
	
	header('Content-Type: application/xml; charset=utf-8');
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 5);
			curl_setopt($ch, CURLOPT_URL, "http://www.pinterest.com/mesacor/feed.rss");
			
			
			$_res = curl_exec($ch);
			//libxml_use_internal_errors(true);
			
			
		
			try{
				$_res = str_replace('/192x/', '/736x/', $_res);
				
				//$xml = new SimpleXMLElement($_res);
				echo($_res);
			} catch (Exception $e) {
					pre($xml);
						
			}
		
			curl_close($ch);
?>
			