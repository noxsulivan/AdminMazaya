<?
			
	
			$_localhost = "http://www.mesacor.com.br/";			
			$_ABSURL = "http://www.mesacor.com.br/";			
			$_root = $_SERVER['DOCUMENT_ROOT']."/";	
			$_serverRoot = $_SERVER['DOCUMENT_ROOT']."/";	
			$_siteRoot = $_SERVER['DOCUMENT_ROOT']."/";			
			$_admin = "admin/";
			
	//$host = "localhost";				$usr = "";		$senha = "";			$banco = "mesacor";
	$host = "localhost";				$usr = "mesacor_mesacor";		$senha = "mesacor";			$banco = "mesacor_2009";

	include("$_root/_inc/funcoes.php");
	include("inc.funcoes.php");
	
	
	function __autoload($class_name) {
    	global $_root;
		if(file_exists($_root.'_admin/classes/' .$class_name.  '.php')){
			require_once $_root.'_admin/classes/' .$class_name.  '.php';
		}else{
			pre(debug_backtrace());
			die("Verifique os arquivos instalados. A Classe $class_name não pode ser definida.");
		}
	}
	
	
	session_start();

	$db = new db($banco,$host,$usr, $senha);
	

?>