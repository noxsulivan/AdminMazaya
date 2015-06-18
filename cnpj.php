<?
$ch = curl_init('http://www.infoplex.com.br/api/v1/perfil/15118890000108.json');
$response = json_decode(curl_exec($ch));
print_r ($response);

?>