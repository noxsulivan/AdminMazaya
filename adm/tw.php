<?
if(eregi('robots',$_SERVER['QUERY_STRING'])) die("User-Agent: *
Allow: /");
include('ini.php');

set_time_limit(0);

define('TWITTER_CONSUMER_KEY', 'fax4tnC7BVCJRzdFLktvA');
define('TWITTER_CONSUMER_SECRET', 'Q9HjQwcZurOi1w6Ach5rPxV2zJtmWpdnJ1VxZacRHUk');
  
$consumer_key = 'fax4tnC7BVCJRzdFLktvA';
$consumer_secret = 'Q9HjQwcZurOi1w6Ach5rPxV2zJtmWpdnJ1VxZacRHUk';


//getulioimoveis
$oauth_token= '113334449-dKed0lFBB2OooijTjJ0TlNWyMQMURrQ9LD7yg5MO';
$oauthSecret = 'lNLxgSBldWOvHi917TIy44ae7EQD7gYfJE51UIvUw';
$user_id = '113334449';

//soja
$oauth_token= '92794289-RiD2ti7J9tgHCr3jdTVbMLYpHy9ai70o9qO7pOm02';
$oauthSecret = 'tZxkyakqxbqiHeXRQUtcOMwfOHk28saYsxN4ls3OXdk';
$user_id = '92794289';


//seixas
$oauth_token= '130829083-icET7Y3ZTDBgg1a2BrnDvfWfxIfwKlgqIURfoub9';
$oauthSecret = 'RjGxfDDItBALOD39EuqfcBnrp1pvsqaFY9tOi1uFiY';
$user_id = '130829083';



//noxsulivan
$oauth_token= '16811320-g9iS5mk84ZcECXeBva6fNpj3BzRHQCbza7h5Ba0KU';
$oauthSecret = '2xxiZQNXpNPOM8GxcKXgyPQi7S3DCYyt4qOuZNzMIgk';
$user_id = '16811320';



//mesacor
$oauth_token= '124257282-KjB6AzoGB25rFxMVNldXuwpN7nvmfI6KInBslwho';
$oauthSecret = 'dLoqmUAZn3R8kgk8eRctZ5Ws1H7hFo4gQXsYYUvFXdU';
$user_id = '124257282';

  pre("Autenticando");flush();
  die;

$twitterObj = new EpiTwitter($consumer_key, $consumer_secret, $oauth_token, $oauthSecret);
//$twitterObj->useAsynchronous(true);
$userInfo = $twitterObj->get_accountVerify_credentials();
//pre(json_decode($userInfo->responseText));
  
  //$twitterObj->post_statusesUpdate(array('status' => (urldecode(str_replace('@@','#',$_REQUEST['status'])))));
  
  //$mentions = $twitterObj->get_statusesMentions(array('count' => 10));
  //pre(json_decode($mentions->responseText));
  //$db->query("truncate tw_followers");
  
	function coletaFollowers($pagina){
		global $twitterObj;
		$mentions = $twitterObj->get_statusesFollowers(array('cursor' => $pagina));
		$list = json_decode($mentions->responseText);
		sentena($list->users,'followers');
		if($list->next_cursor_str > 0)
			coletaFollowers($list->next_cursor_str);
		else
			pre("FIM");
	}
  
  
	function coletaFriends($pagina){
		global $twitterObj;
		$mentions = $twitterObj->get_statusesFriends(array('cursor' => $pagina));
		$list = json_decode($mentions->responseText);
		sentena($list->users,'friends');
		if($list->next_cursor_str > 0)
			coletaFriends($list->next_cursor_str);
		else
			pre("FIM");
	
	
	
	function coletaCandidates($pagina,$screen_name){
		global $twitterObj;
		$mentions = $twitterObj->get_statusesFriends(array('cursor' => $pagina,'screen_name' => $screen_name));
		$list = json_decode($mentions->responseText);
		pre($list);die();
		sentena($list->users,'candidates',$screen_name);
		if($list->next_cursor_str > 0)
			coletaCandidates($list->next_cursor_str);
		else
			pre("FIM");
	}
	function sentena($users,$tab,$from = NULL){
		global $db;
		foreach($users as $user){
		  
		$_POST['idtw_'.$tab] = $user->id;
		$_POST['name'] = utf8_decode($user->name);
		$_POST['screen_name'] = $user->screen_name;
		$_POST['from'] = $screen_name;
		$_POST['location'] = utf8_decode($user->location);
		$_POST['site'] = $user->url;
		$_POST['following'] = $user->following;
		$_POST['followers_count'] = $user->followers_count;
		$_POST['friends_count'] = $user->friends_count;
		$_POST['statuses_count'] = $user->statuses_count;
		$_POST['description'] = utf8_decode($user->description);
		$_POST['profile_image_url'] = $user->profile_image_url;
		$db->inserir("tw_".$tab);
		$_POST = array();
		pre($user->screen_name);
		flush();
		}
	}
  
  //coletaFollowers(-1);
  //coletaFriends(-1);
  pre("Iniciando candidates");flush();
  coletaCandidates(-1,"25720336");
  
?>