<?
include('ini.php');
echo "1SIGEP DESTINATARIO NACIONAL";
$db->query("select idcadastros from cadastros where exportado_sigep = 'nao' order by idcadastros desc limit 10");
while($res = $db->fetch()){
	$cadastro = new objetoDb('cadastros',$res['idcadastros']);
	echo "
";
	echo "2              ";
	echo str_pad(substr($cadastro->nome.' '.$cadastro->sobrenome,0,60),60,' ',STR_PAD_LEFT);
	echo str_pad(substr($cadastro->email,0,60),60,' ',STR_PAD_LEFT);
	echo str_pad(substr("                                          ",0,60),60,' ',STR_PAD_LEFT);
	echo str_pad(substr("                                           ",0,60),60,' ',STR_PAD_LEFT);
	echo str_pad(substr($cadastro->cep,0,8),8,' ',STR_PAD_LEFT);
	echo str_pad(substr($cadastro->endereco,0,60),60,' ',STR_PAD_LEFT);
	echo str_pad(substr($cadastro->numero,0,6),6,' ',STR_PAD_LEFT);
	echo str_pad(substr($cadastro->complemento,0,30),30,' ',STR_PAD_LEFT);
	echo str_pad(substr($cadastro->bairros->bairro,0,60),60,' ',STR_PAD_LEFT);
	echo str_pad(substr("PASSO FUNDO                                       ",0,60),60,' ',STR_PAD_LEFT);
	echo str_pad(substr("telefone          ",0,18),18,' ',STR_PAD_LEFT);
	echo str_pad(substr("celu      ",0,10),10,' ',STR_PAD_LEFT);
	echo str_pad(substr("faz       ",0,10),10,' ',STR_PAD_LEFT);
	//$db->query('update cadastros set exportado_sigep = "sim" where idcadastros = "'.$res['idcadastros'].'"');
}
echo "
9000003
";
?>