<?
class cadastroSite extends objetoDb {
	var $id; // atalho para $cadastros->dados["idcadastro"]
	var $dados;
	var $erro;
	var $mensagem;
	var $chave;
	var $sessao;
	
	function cadastroSite (){
		global $db;
		if(!isset($_SESSION["cadastro"]) and $this->logar()){
				$_SESSION["cadastro"] = $this;
		}
	}
	
	function logar(){
		global $db;
		if(isset($_POST["_email"]) and isset($_POST["_senha"])){
			//$sql = "select idcadastros from cadastros where login = '".$_POST["_email"]."' and senha = '".($_POST["_senha"])."'";
			$sql = "select idcadastros from cadastros where email = '".$_POST["_email"]."' and senha = '".md5($_POST["_senha"])."'";
			if($db->query($sql)){
				if($db->rows == 0 ){
					$this->erro .= "Seu apelido ou senha estão errados.";
					$this->sessao .= $this->erro;
					return false;
				}
				else{
					$res = $db->fetch();
					$this->id = $res['idcadastros'];
					$this->atualizarSession();
					return true;
				}
			}
			else{
				$this->erro .= "Não foi possível acessar o banco de dados. Tente novamente.<br />";
				$this->sessao .= $this->erro;
				return false;
			}
		}else{
				$this->sessao .= "Não está tentando logar";
				return false;
		}
	}
	
	function conectado(){
		global $db;
		
		if(isset($_POST["chave"])){
			$db->query("select 1 from usuarios where md5(senha) = '". $_POST["chave"]."'");
			if($db->rows)
				return true;
		}
		if($this->id != ''){
			return true;
		}else{
			return false;
		}
	}
	function atualizarSession(){
		global $db;
			//$sql = "select * from cadastros where idcadastros = '".$this->id."'";
			//if($db->query($sql)){
			
					//$this->dados = new objetoDb('cadastros',$this->id);
					//$this->id = $this->dados->idcadastros;
					
					$this->objetoDb('cadastros',$this->id);
					
					$this->chave = md5($this->dados->senha);
					
					$_SESSION["cadastro"] = $this;
					
					//$_tmp = str_word_count($this->dados->nome, 1);
					//$this->nome = $this->dados->nome;
					$this->mensagem = "<h3>Bem-vindo(a), <strong>".$this->dados->nome."</strong> à área restrita do site "."</h3>";
					if($db->tabelaExiste('pedidos') and $db->campoExiste('idcadastros','pedidos')) $db->query("update pedidos set idcadastros = '".$this->id."' where  sessionid = '".session_id()."'");
					//return true;
			//}else{
				//return false;
			//}
	}
	function sair($destino){
		unset($_SESSION["cadastro"]);
		unset($this->id);
		unset($this->dados);
		if($destino) echo "<script>window.location='".$destino."'</script>";
		die;
	}
	
	function trocarSenha($senhaAtual,$nova1,$nova2){
		global $db;
		
		if(md5($senhaAtual) == $this->dados["senha"]){
			if($nova1 == $nova2){
				$db->query("update cadastros set senha = '".md5($nova1)."' where idcadastros = '".$this->id."'");
				return true;
			}else{
				$this->erro = "Você precisa digitar a senha nova duas vezes";
			}
		}else{
			$this->erro = "A senha atual está incorreta";
		}
		return false;
	}
}
?>