<?

class usuario extends objetoDb {
	var $id; // atalho para $usuarios->dados["idusuario"]
	var $dados;
	var $erro;
	var $chave;
	var $tabelas = array();
	
	function usuario(){
		
		
		global $db,$admin;
		$this->cookieName = "adminMazaya";
		if(isset($_COOKIE[$this->cookieName])){
			if(isset($_SESSION["usuario"]) and $_SESSION["usuario"]->id > 0){
			header('X-Usuario-Stage: '. 3);
				
				$this->id = $_SESSION["usuario"]->id;
				$this->erro = $_SESSION["usuario"]->erro;
				$this->chave = $_SESSION["usuario"]->chave;
				$this->atualizarSession();
				return true;
			}else{
			header('X-Usuario-Stage: '. 4);
				list($_senha,$_id) = explode("@@@",$_COOKIE[$this->cookieName]);
				$sql = "select idusuarios from usuarios where senha = '".$_senha."' and idusuarios = '".$_id."'";
				if($db->query($sql)){
					if($db->rows == 0 ){
						$this->erro .= "A sessão não pode ser restaurada a partir dos dados armazenados";
						
						unset($_SESSION["usuario"]);
						unset($_COOKIE);
						setcookie($this->cookieName,$_senha."@@@".$_id,time()-(60*60*24),'/');
						header('Location: '.$admin->localhost.$admin->admin);
						return false;
					}else{
						$res = $db->fetch();
						$this->id = $res['idusuarios'];
						$this->atualizarSession();
						return true;
					}
				}
				else{
					$this->erro .= "Não foi possível acessar o banco de dados. Tente novamente.";
					return false;
				}
			}
		}elseif($admin->acao == 'Logar'){
			header('X-Usuario-Stage: '. 1);
			if($this->logar()){
				$_SESSION["usuario"] = $this;
			}else{
				die(utf8_encode('Impossível conectar - '.$this->erro));
			}
		}elseif($_REQUEST['chave']){
			header('X-Usuario-Stage: '. 2);
				$sql = "select idusuarios from usuarios where md5(senha) = '".$_REQUEST['chave']."'";
				if($db->query($sql)){
						$res = $db->fetch();
						$this->id = $res['idusuarios'];
						$this->atualizarSession();
						return true;
				}
		}else{
			$this->erro .= "Nenhuma ação foi designada ou as chaves de autenticação estão incorretas";
			return false;
		}
	}
	
	function logar(){
		global $db;
		if(isset($_POST["apelido"]) and isset($_REQUEST["senha"])){
			$db->query("select idusuarios from usuarios where login = '".$_POST["apelido"]."@@@'");
			if($db->rows){
					$this->erro .= "Acesso temporariamente bloqueado";
					return false;
			}
			$sql = "select idusuarios from usuarios where login = '".$_POST["apelido"]."' and senha = '".md5($_REQUEST["senha"])."'";
			if($db->query($sql)){
				if($db->rows == 1 ){
					$res = $db->fetch();
					$this->id = $res['idusuarios'];
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
			$this->erro .= "Preencha obrigatoriamente os dois campos.";
			return false;
		}
	}
	
	function conectado(){
		global $db;
		if($_POST["chave"]){
			$db->query("select 1 from usuarios where md5(senha) = '". $_POST["chave"]."'");
			if($db->rows)
				return true;
		}
		if($this->id > 0){
			return true;
		}else{
			$this->error = "Usuário não conectado";
			return false;
		}
	}

	function sair(){
		global $admin;
		unset($_SESSION["usuario"]);
		unset($_COOKIE);
		setcookie($this->cookieName,$this->chave."@@@".$this->id,time()-(60*60*24),'/');
		header('Location: '.$admin->localhost.$admin->admin);
		
		ob_end_flush();
	}
	
	function atualizarSession(){
		global $db;
					parent::objetoDb('usuarios',$this->id);
										
					$this->chave = md5($this->senha);
					
					if($this->clientes->id == 1)
						$this->clientes = NULL;
					
//					if($this->id == 1){
//						//pre($this->menus);
//						$sql = "select idmenus from menus order by ordem";
//						//pre($sql);
//						$db->query($sql);
//						while($res = $db->fetch()){
//							$_tmp[] = new objetoDb('menus',$res['idmenus']);
//						}
//						//pre($_tmp);
//						$this->menus = $_tmp;
//						//pre($this->menus);
//					}

					$_temp = array();
					if(is_array($this->tipos_de_usuarios->tabelas) and count($this->tipos_de_usuarios->tabelas)){
						foreach($this->tipos_de_usuarios->tabelas as $tabela){
							$_temp[++$j] = $tabela->nome;
						}
					}
					$this->tabelas = $_temp;
					$this->condicao = $this->tipos_de_usuarios->condicao;
					
					
					$_temp = array();
					if(is_array($this->tipos_de_usuarios->menus) and count($this->tipos_de_usuarios->menus)){
						foreach($this->tipos_de_usuarios->menus as $menus){
							$_temp[] = $menus->menu;
						}
					}
					$this->menus = $_temp;
					
					
					//$db->query("select configuracoes.parametro, usuarios_has_configuracoes.transporte from configuracoes, usuarios_has_configuracoes where usuarios_has_configuracoes.idusuarios = '".$this->id."' and usuarios_has_configuracoes.idconfiguracoes = configuracoes.idconfiguracoes");
					//while($res = $db->fetch())
						//$this->configs[$res["parametro"]] = nl2br($res["transporte"]);
						
					$_SESSION["usuario"] = $this;
					
					
					@$db->query("update usuarios set ultimo_login = '".date("Y-m-d H:i:s")."' where idusuarios = '".$this->id."'");

		
					
					if(!$db->fetch("select id_entradas from _entradas where idusuarios = '".$this->id."' and session = '".$_COOKIE["PHPSESSID"]."' and ip = '".$_SERVER["REMOTE_ADDR"]."'","id_entradas")){
						$db->query("insert into _entradas set idusuarios = '".$this->id."', session = '".$_COOKIE["PHPSESSID"]."', data = '".date("Y-m-d H:i:s")."', ip = '".$_SERVER["REMOTE_ADDR"]."'");
					}
					
					if(!setcookie($this->cookieName,$this->chave."@@@".$this->id,time()+(60*60*24*7),'/'))
						die('Não é possível usar Cookies neste navegador, verifique suas configurações');
					
					return true;
	}
	function trocarSenha($senha,$senha_nova, $denovo){
		global $db,$usuario;
		if($senha_nova == $denovo){
				if($usuario->senha == md5($senha)){
						$db->query("update usuarios set senha = md5('$senha_nova') where idusuarios = '".$usuario->id."'") or $db->erro;
						$ret['mensagem'] = utf8_encode('<img width="64" height="64" alt="" src="http://recantogolfville.com.br/1422650374_678134-sign-check-128.png" align="left">'."Sua senha de acesso foi alterada com sucesso.<br />Caso não tenha sido você que pediu a alteração, entre imediatamente em contato com a secretaria.");
							$ret['status'] = 'ok';
				}else{
					$ret['mensagem'] = utf8_encode("A senha atual não está correta");
					$ret['status'] = 'erro';
				}
		}else{
			$ret['mensagem'] = utf8_encode("As duas senhas não conferem. Você deve ter errado a digitação. Tente novamente");
			$ret['status'] = 'erro';
		}
		
		
		return json_encode($ret);
	}
}
?>