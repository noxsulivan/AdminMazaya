<?

class cliente extends objetoDb {
	var $id; // atalho para $clientes->dados["idcliente"]
	var $dados;
	var $erro;
	var $chave;
	
	function cliente(){
		global $db,$admin;
		$this->cookieName = COOKIE_NAME ? COOKIE_NAME."noiva" : diretorio($_SERVER['HTTP_HOST'])."noiva";
		
		if(isset($_COOKIE[$this->cookieName])){
			if(isset($_SESSION["cliente"]) and $_SESSION["cliente"]->id > 0){
				
				$this->id = $_SESSION["cliente"]->id;
				$this->erro = $_SESSION["cliente"]->erro;
				$this->chave = $_SESSION["cliente"]->chave;
				$this->atualizarSession();
				return true;
			}else{
				list($_senha,$_id) = split("@@@",$_COOKIE[$this->cookieName]);
				$sql = "select idclientes from clientes where md5(senha) = '".$_senha."' and idclientes = '".$_id."'";
				if($db->query($sql)){
					if($db->rows == 0 ){
						$this->erro .= "A sessão não pode ser restaurada a partir dos dados armazenados";
						return false;
					}
					else{
						$res = $db->fetch();
						$this->id = $res['idclientes'];
						$this->atualizarSession();
						return true;
					}
				}
				else{
					$this->erro .= "Não foi possível acessar o banco de dados. Tente novamente.";
					return false;
				}
			}
		}elseif($this->logar()){
			$_SESSION["cliente"] = $this;
		}
	}
	
	function logar(){
		global $db;
		if(isset($_POST["_email"]) and isset($_REQUEST["_senha"])){
			$db->query("select idclientes from clientes where email = '".$_POST["_email"]."@@@'");
			if($db->rows){
					$this->erro .= "Acesso temporariamente bloqueado";
					return false;
			}
			$sql = "select idclientes from clientes where email = '".$_POST["_email"]."' and senha = '".md5($_REQUEST["_senha"])."'";
			if($db->query($sql)){
				if($db->rows == 1 ){
					$res = $db->fetch();
					$this->id = $res['idclientes'];
					$this->atualizarSession();
					return true;
				}
				else{
					$this->erro .= "Seu apelido ou senha estão errados.";
					return false;
				}
			}
			else{
				$this->erro .= "Não foi possível acessar o banco de dados. Tente novamente.";
				return false;
			}
		}else{
			return false;
		}
	}
	
	function conectado(){
		global $db;
		if($_POST["chave"]){
			$db->query("select 1 from clientes where md5(senha) = '". $_POST["chave"]."'");
			if($db->rows)
				return true;
		}
		if($this->id > 0){
			return true;
		}else{
			$this->error .= "Usuário não conectado";
			return false;
		}
	}

	function sair($destino){
		global $admin;
		unset($_SESSION["cliente"]);
		setcookie($this->cookieName,0,time()-(60*60*24),'/');
		header('Location: '.$destino);
		
		ob_end_flush();
	}
	function atualizarSession(){
		global $db;
					parent::objetoDb('clientes',$this->id);
										
					$this->chave = md5($this->senha);
					
					if($this->clientes->id == 1)
						$this->clientes = NULL;
						
					$_SESSION["cliente"] = $this;
					
					setcookie($this->cookieName,$this->chave."@@@".$this->id,time()+(60*60*24),'/');
					
					$this->mensagem = "<h3>Bem-vindo(a), <strong>".$this->dados->nome."</strong> à área restrita do site "."</h3>";
					if($db->tabelaExiste('pedidos') and $db->campoExiste('idclientes','pedidos')) $db->query("update pedidos set idclientes = '".$this->id."' where  sessionid = '".session_id()."'");

					return true;
	}
	function trocarSenha($apelido,$senha){
		global $db;
		$db->query("select nome, senha, email,tipo from clientes where apelido = '$apelido'");
		if($senha == $senha_conf){
			if($db->rows > 0){
				$dados = $db->fetch();
				if($email == $dados["email"]){
					if($dados["tipo"] == 'cliente'){
						$db->query("update clientes set senha = '$senha' where apelido = '$apelido'") or $db->erro;
						$corpo = "Sua senha de administrador do site foi alterada para $senha.<br />Caso não tenha sido você que pediu a alteração, entre imediatamente em contato com <a href=\"mailto:noxffffffig.com.br\">noxffffffig.com.br</a>";
						if(manda_email($dados["email"],"Senha nova senha no COMverSOS",$corpo)){
							manda_email('',"$apelido trocou de senha  $senha",$corpo);
							if($email_alt)
								manda_email($email_alt,"Senha nova senha",$corpo);
							$mensagem = "Sua nova senha foi salva e enviada para seu e.mail.<br />";
						}else{
							$mensagem = "Não foi possível enviar um email com a senha. Primeiro tente acessar a admin com a senha que você digitou, senão tente trocá-la novamente";
							$act = 'trocar_senha';
						}
					}else{
						$mensagem = "Você não é um usuário do sistema com privilégios para acessar a administração. Escreva um email para <a href=\"mailto:edicaoffffffcomversos.com.br\">edicaoffffffcomversos.com.br</a> e peça para que os clientes do site pensem no seu caso.";
						$act = 'trocar_senha';
					}
				}else{
					$mensagem = "O e-mail digitado não corresponde ao configurado pelo usuário $apelido em nosso sistema";
					$act = 'trocar_senha';
				}
			}else{
				$mensagem = "O apelido digitado está errado ou não existe em nosso sistema. Verifique a digitação";
				$act = 'trocar_senha';
			}
		}else{
			$mensagem = "As duas senhas não conferem. Você deve ter errado a digitação. Tente novamente";
			$act = 'trocar_senha';
		}
	}
}
?>