<?
include('../ini.php');

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www4.bcb.gov.br/feed/taxas.ashx");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
			$exec = curl_exec($ch);
			
			//pre($exec);die;
			
			$xml = simplexml_load_string($exec);
			
			
$tidy = tidy_parse_string($xml->channel->item[5]->description);

$html = $tidy->html();


			pre($html->child[1]->child[0]->child[0]->child[0]->value);
			pre($html->child[1]->child[0]->child[1]->child[0]->value);
			pre($html->child[1]->child[1]->child[0]->child[0]->value);
			pre($html->child[1]->child[1]->child[1]->child[0]->value);
			pre($html->child[1]->child[2]->child[0]->value);

			//pre($html);
	curl_close($ch);
	die;
?>