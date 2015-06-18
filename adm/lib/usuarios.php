<?
switch($admin->acao){
case 'senha':
	$admin->id = $usuario->id;
	
	$formulario->fieldset("Confirmação",false,true);
		$formulario->fieldset->simples('Senha atual', 'senha');
	$formulario->fieldset("Nova",false,true);
		$formulario->fieldset->simples('Nova Senha', 'senha_nova');
		$formulario->fieldset->simples('Digite novamente', 'senha_nova1');
			break;
case "editar": case "abrir":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
	//pre($admin->registro);
case "novo";
	
	$formulario->fieldset("Dados do usuário");
		$formulario->fieldset->simples('Nome', 'nome');
		$formulario->fieldset->simples('Login', 'login');
		$formulario->fieldset->simples('Senha', 'senha');
		$formulario->fieldset->simples('Função', 'idtipos_de_usuarios');
		$formulario->fieldset->simples('Relacionado ao condômino', 'idcondominos');
		$formulario->fieldset->separador();
		$formulario->fieldset->simples('E-mail', 'email');
	
	
	if(0){
	}
	if($usuario->id == 1){
		//$formulario->fieldset->simples('Data de expiração', 'expira');
	
		//$formulario->fieldset("Configurações pessoais");
			//$formulario->fieldset->hasTransporte('configuracoes','visor','valor','transporte');
		
		//$formulario->fieldset("Funções permitidas");
		//$formulario->fieldset->checkBox("Permissões","menus");
	}
	
	
break;
case "salvar":
	if($admin->id == ''){
		$s = $_POST["senha"];
		$_POST["senha"] = md5($_REQUEST["senha"]);
		$db->inserir('usuarios');
		$inserted_id = $db->inserted_id;
		$db->tabela_link('usuarios','menus',$inserted_id,$_POST["menus"]);
		$db->tabela_link('usuarios','configuracoes',$inserted_id,$_POST["transp"]);
		
		$msg = '
				<h2>Boas vindas ao Sistema Administrativo N2Design</h2>
				<p>'.$_POST["nome"].'</p>
				<p>O respons&aacute;vel pelo site <strong>'.$admin->configs['titulo_site'].'</strong>  criou para voc&ecirc; um login de acesso ao sistema administrativo:</p>
				<p>Memorize ou guarde em local seguro o login e senha, pois atrav&eacute;s deles &eacute; poss&iacute;vel alterar o conte&uacute;do do site, e o uso indevido desta informa&ccedil;&atilde;o pode trazer conseq&uuml;&ecirc;ncias indesej&aacute;veis.</p>
				<p>Login: '.$_POST["login"].'<br />
				Senha: '.$s.'</p>
				<p>Endere&ccedil;o de acesso</p>
				<p>http://'.$admin->localhost.'admin/</p>
				<p>Em caso de d&uacute;vidas   entre em contato com: '.$usuario->dados["nome"].' <a href="mailto:'.$usuario->dados["email"].'">'.$usuario->dados["email"].'</a> </p>';
		
		//n2_mail($_POST["email"],"Boas vindas ao Sistema Administrativo ".$admin->configs['titulo_site'],$msg,"contato@mazaya.com.br",$_FILES);
		
	}elseif($admin->acao == "salvar" and $admin->id != ''){
		$admin->registro = new objetoDb($admin->tabela, $admin->id);
		if($admin->registro->senha != $_POST["senha"] && $_POST["senha_nova"] == $_POST["senha_nova1"])
			$_POST["senha"] = md5($_REQUEST["senha"]);
		
		$_POST["senha_nova"] = $_POST["senha_nova1"] = NULL;
			
		$db->editar('usuarios',$admin->id);
		//$db->tabela_link('usuarios','menus',$admin->id,$_POST["menus"]);
		//$db->tabela_link('usuarios','configuracoes',$admin->id,$_POST["transp"]);
		$msg = "";
	}
break;
case 'alterarAfiliado':
	extract($_POST);
	if($nova1 == $nova2){
		$sql = "select idusuarios from usuarios where idusuarios = '".$usuario->id."' and senha = '".md5($senhaAtual)."'";
		$db->query($sql);
		if($db->rows){		
			$db->query("update usuarios set senha = '".md5($nova1)."' where idusuarios = '".$usuario->id."'");
			
		}else{
			die(utf8_encode("A senha atual não confere com a digitada"));
		}
	}else{
		die(utf8_encode("É preciso digitar a nova senha duas vezes. Verifique a digitação"));
	}
	
break;
default:
	$admin->campos_listagem = array('Nome' => "nome",'E-mail' => "email",'Permissão' => "tipos_de_usuarios->tipo");
	$sql = "select * from usuarios";
	
break;
}
?>