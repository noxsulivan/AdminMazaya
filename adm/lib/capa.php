<?

return;

$admin->html .="
</ul></div>
";
$admin->html .="
<div id=capa>
<h2>Seja bem-vindo ao Sistema Online do Recanto Golf Ville</h2>".'<BR>
	<div class="button"><a href="#" onclick="formulario(\'condominos@@/novo\')"><img src="imagens/icons/48x48/package.png"><br>Meus dados</a></div>

	<div class="button"><a href="#" onclick="formulario(\'rastreios@@/novo\')"><img src="imagens/icons/48x48/airplane.png"><br>Cadastrar Rastreio</a></div>
	
	<div class="button"><a href="#" onclick="formulario(\'orcamentos@@/novo\')"><img src="imagens/icons/48x48/business_male_female_users_comments.png"><br>Cadastrar Orçamento</a></div>
	
'.
		
"</div>
";



		if($usuario->tipos_de_usuarios->id != 4 ){
?>
<div id=capa>
	<h1>listar veículos por:</h1>
    <div id="buscaCapaDiv">
      <form id="subFiltro1" onsubmit="listar('veiculos','placa'); return false;">
      Placa
        <input type="text" name="placa" value="" id="placa">
        <a href="javascript:void()" onclick="listar('veiculos','subFiltro1')" role="button"><img src="http://recantogolfville.com.br/adm/imagens/buttons/search.png" border="0" align="absmiddle" width="21" height="21"></a>
      </form>
    </div>
	<h1>listar Visitantes por:</h1>
    <div id="buscaCapaDiv">
      <form id="subFiltro2" onsubmit="listar('visitantes','nome'); return false;">
      Nome
        <input type="text" name="nome" value="" id="nome">
        <a href="javascript:void()" onclick="listar('visitantes','subFiltro2')" role="button"><img src="http://recantogolfville.com.br/adm/imagens/buttons/search.png" border="0" align="absmiddle" width="21" height="21"></a>
      </form>
    </div>
    
    <h1>listar funcionário por:</h1>
    <div id="buscaCapaDiv">
      <form id="subFiltro3">
      Nome
        <input type="text" name="nome" value="" id="nome">
        <a href="javascript:void()" onclick="listar('funcionarios','subFiltro3')" role="button"><img src="http://recantogolfville.com.br/adm/imagens/buttons/search.png" border="0" align="absmiddle" width="21" height="21"></a>
      CPF
        <input type="text" name="cpf" value="" id="cpf">
        <a href="javascript:void()" onclick="listar('funcionarios','subFiltro3')" role="button"><img src="http://recantogolfville.com.br/adm/imagens/buttons/search.png" border="0" align="absmiddle" width="21" height="21"></a>
    
      Código
        <input type="text" name="codigo" value="" id="codigo">
        <a href="javascript:void()" onclick="listar('funcionarios','subFiltro3')" role="button"><img src="http://recantogolfville.com.br/adm/imagens/buttons/search.png" border="0" align="absmiddle" width="21" height="21"></a>
      </form>
    </div>
</div>
<?
	}
?>
<?
/*$admin->html .="
<div id=capa>
<h2>Grade Disponíveis/Clientes direcionaids</h2>
<ul>";

$db->query("select * from produtos where estoque = 1");

$r = $db->resourceAtual;
while($res = $db->fetch()){

	$_clis = array();
	$produto = new objetoDb("produtos",$res['idprodutos']);
	$db->query("select * from materiais_consumidos where idmateriais = '".$produto->materiais->id."' and fluidez_min <= '".$produto->fluidez."' and fluidez_max >= '".$produto->fluidez."' ");
	$admin->html .="<li><strong>".$produto->grade." (".$produto->fluidez.") - </strong>";
	while($res = $db->fetch()){
		$cliente = new objetoDb("clientes",$res['idclientes']);
		$_clis[] = $cliente->fantasia;
	}
	$admin->html .= implode(" - ",$_clis)."</li>";
	$db->resource($r);
	

}
	


$admin->html .="
</ul></div>
";
$admin->html .="
<div id=capa>
<h2>Seja bem-vindo ao Sistema Administrativo<br>Cliente: ".$admin->configs["titulo_site"]."</h2>".'<BR>
	<div class="button"><a href="#" onclick="formulario(\'produtos@@/novo\')"><img src="imagens/icons/48x48/package.png"><br>Novo Produto</a></div>

	<div class="button"><a href="#" onclick="formulario(\'rastreios@@/novo\')"><img src="imagens/icons/48x48/airplane.png"><br>Cadastrar Rastreio</a></div>
	
	<div class="button"><a href="#" onclick="formulario(\'orcamentos@@/novo\')"><img src="imagens/icons/48x48/business_male_female_users_comments.png"><br>Cadastrar Orçamento</a></div>
	
'.
		
"</div>
";*/
/*		
		<iframe src="https://www.facebook.com/plugins/registration?
             client_id=169974619697942&
             redirect_uri=http://www.mesacor.com.br/_Request/Face&
             fields=name,birthday,gender,location,email"
        scrolling="auto"
        frameborder="no"
        style="border:none"
        allowTransparency="true"
        width="100%"
        height="330">

define('FACEBOOK_APP_ID', '169974619697942');
define('FACEBOOK_SECRET', '27f4d8bf5dd9b2ed980ac8c31b9decb3');

function parse_signed_request($signed_request, $secret) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

  // decode the data
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);

  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
    error_log('Unknown algorithm. Expected HMAC-SHA256');
    return null;
  }

  // check sig
  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
  if ($sig !== $expected_sig) {
    error_log('Bad Signed JSON signature!');
    return null;
  }

  return $data;
}

function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
}


$req = 'LJKxEdZzdHyGU8gtqBXH8-A1J78RuUQxzdrkQdhkOWw.eyJhbGdvcml0aG0iOiJITUFDLVNIQTI1NiIsImV4cGlyZXMiOjEzMzU5ODE2MDAsImlzc3VlZF9hdCI6MTMzNTk3NzIzMSwib2F1dGhfdG9rZW4iOiJBQUFDYWwwelQ4eFlCQUgwT3E2SElObkJMcDl1bmNYSWpoYzhtQnJNaDVlQ3NRSnBHOG9CSzRjOGZCMlpCUzF2TTFubW9WTFdDaGc0WkJhVmszTktJNjgxbkhzNXhod1hDWkMwOXdYdkRBZWJXajF5MHp5QSIsInJlZ2lzdHJhdGlvbiI6eyJuYW1lIjoiU3VsbGl2YW4gUm9kcmlndWVzIiwiYmlydGhkYXkiOiIwM1wvMjNcLzE5ODAiLCJnZW5kZXIiOiJtYWxlIiwibG9jYXRpb24iOnsibmFtZSI6IkxvbmRyaW5hIiwiaWQiOjEwODE3MTEwMjUzNzk5OX0sImVtYWlsIjoibm94c3VsaXZhblx1MDA0MGdtYWlsLmNvbSJ9LCJyZWdpc3RyYXRpb25fbWV0YWRhdGEiOnsiZmllbGRzIjoibmFtZSxiaXJ0aGRheSxnZW5kZXIsbG9jYXRpb24sZW1haWwifSwidXNlciI6eyJjb3VudHJ5IjoiYnIiLCJsb2NhbGUiOiJwdF9CUiJ9LCJ1c2VyX2lkIjoiMTYxNzA4NDM4OCJ9';
if ($req) {
  echo '<p>signed_request contents:</p>';
  $response = parse_signed_request($req, 
                                   FACEBOOK_SECRET);
  echo '<pre>';
  print_r($response);
  echo '</pre>';
} else {
  echo '$_REQUEST is empty';
}*/
return;

//'
//<h3>Precisa de ajuda?</h3>
//      <div id="fb-root"></div>
//		<script>(function(d, s, id) {
//          var js, fjs = d.getElementsByTagName(s)[0];
//          if (d.getElementById(id)) return;
//          js = d.createElement(s); js.id = id;
//          js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=244455668952934";
//          fjs.parentNode.insertBefore(js, fjs);
//        }(document, \'script\', \'facebook-jssdk\'));< /script>
//        
//        <div class="fb-comments" data-href="http://www.facebook.com/MazayaWeb" data-num-posts="10" data-width="800"></div>'



$admin->html .= "
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
                $len = $molranges[$i + 1] - $offset;
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
			//echo "<h1><a href=".$item->link.">".$item->title."</a></h1>".$item->description."";
		//}
	
?>