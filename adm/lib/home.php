<?
$admin->html .="
<div id=capa>
<h1>Seja bem-vindo</h1>
  <p>";

$sql = "select * from entradas where idusuarios = '$logado["idusuarios"]' order by identradas desc limit 1,2";
$db->query($sql);
$res = $db->fetch();
$admin->html .="Data/hora $res["data"]<br />IP: $res["ip"]";
$admin->html .="
  </p>

  <h1>Not&iacute;cias N2Design</h1>";



class N2News {
    var $title;  // aa name
    var $link;    // three letter symbol
    var $description;  // one letter code
    var $data;  // hydrophobic, charged or neutral
    
    function N2News ($aa) {
        foreach ($aa as $k=>$v)
            $this->$k = $aa[$k];
    }
}

function readDatabase($filename) {
    // l&ecirc; o banco de dados XML de amino&aacute;cidos
	if(!$file = @file($filename)) return array(array("title"=>"Impossível estabelecer conexão com servidor de notícias"));
    $data = implode("", $file);
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, $data, $values, $tags);
    xml_parser_free($parser);

    // loop through the structures
    foreach ($tags as $key=>$val) {
        if ($key == "item") {
            $molranges = $val;
            // each contiguous pair of array entries are the 
            // lower and upper range for each molecule definition
            for ($i=0; $i < count($molranges); $i+=2) {
                    $offset = $molranges[$i] + 1;
                $len = $molranges[$i + 1"] - $offset;
                $tdb[] = parseMol(array_slice($values, $offset, $len));
            }
        } else {
            continue;
        }
    }
    return $tdb;
}

function parseMol($mvalues) {
    for ($i=0; $i < count($mvalues); $i++)
        $mol[$mvalues[$i]["tag"]] = $mvalues[$i]["value"];
    return new N2News($mol);
}

$db = readDatabase("http://www.n2design.com.br/rss.php");
//print_r($db);
//$admin->html .= print_r($db,true);
foreach ($db as $k=>$v) {
	$admin->html .= "
	<h3>".utf8_decode($v->title)."</h3>
		".utf8_decode($v->description);
}

		//$xml = simplexml_load_string($xmlstr);
		//echo "<pre>"; print_r($xml->channel);echo "</pre>";
		//echo count($xml->channel->item);
		
		//foreach ($xml->channel->item as $item) {
			//echo "<h4><a href=".$item->link.">".$item->title."</a></h4>".$item->description."";
		//}
	
	$admin->html .= "</div>";
?>
