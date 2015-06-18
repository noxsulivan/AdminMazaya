<?

//if($admin->extra){
	
$noticia = new objetoDb("noticias",$admin->id);

//1617084388


//AAADEySbMrkwBAGwcoSbAZA9Emh7KdQx1ZC0ZBcgd4g1SfMcelyF9EBGbusa5smZAIaegx96sasl68SRyxecD0S83r05FQgrjTzBnZAcDdpmJDVSIzrygv


include_once('../ini.php');
include_once('php-sdk/facebook.php');


$app_url = "http://www.energiabalneario.com.br/facebookTTTT/"; // no slash at the end, e.g. 'https://social-cafe.herokuapp.com'
$app_id = "216368218418764";
$app_secret = "ce7d33efca5f80991295fda9a09be41e";
$app_namespace = "energiabc"; // no colon at the end, e.g. 'social-cafe'

$facebook = new Facebook( array(
                           'appId' => $app_id,
                           'secret' => $app_secret
                         ));

//$login_url = $facebook->getLoginUrl( array( 'scope' => 'publish_stream,manage_pages') );

$facebook->setAccessToken("AAADEySbMrkwBAGwcoSbAZA9Emh7KdQx1ZC0ZBcgd4g1SfMcelyF9EBGbusa5smZAIaegx96sasl68SRyxecD0S83r05FQgrjTzBnZAcDdpmJDVSIzrygv");
								
//echo '<a href="' . $login_url . '" class="awesome blue float-left">Entrar no facebook</a>';
	
//$facebook->getUser();
  
				$attachment = array(
					'message' => $noticia->texto,
					'name' => $noticia->subtitulo,
					//'caption' => "UFSC 2013",
					'link' => $noticia->url,
					//'description' => 'Noticia',
					//'picture' => 'http://mysite.com/pic.gif',
					//'actions' => array(
						//array(
							//'name' => 'Get Search',
							//'link' => 'http://www.google.com'
						//)
					//)
				);
				
				
				$result = $facebook->api('/541042012582096/feed/', 'post', $attachment);
				
				echo "publicado";
				
				
				
				//$result = $facebook->api('/1617084388/accounts/', 'get', array( ));
				
				//pre($result);
				
				//pre($facebook);





//}else{
		header("location: ".$admin->localhost."adm/noticias/publicar/".$noticia->id);

//}
?>