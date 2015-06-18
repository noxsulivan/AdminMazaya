<?
			
	date_default_timezone_set('America/Sao_Paulo');
	//error_reporting(ALL);
	
			$_localhost = "http://".$_SERVER['HTTP_HOST']."/";			
			$_ABSURL = "http://".$_SERVER['HTTP_HOST']."/";			
			$_serverRoot = $_SERVER['DOCUMENT_ROOT']."/";	
			$_siteRoot = $_SERVER['DOCUMENT_ROOT']."/";			
			$_admin = "adm/";
			
			
			$site = "alex";
			
	$host = "localhost";				$usr = "root";		$senha = "root";			$banco = "energia_bc";
	$host = "localhost";				$usr = "root";		$senha = "root";			$banco = "artlustres";
	$host = "localhost";				$usr = "root";		$senha = "root";			$banco = "ngresinas";
	$host = "localhost";				$usr = "root";		$senha = "root";			$banco = "mesacor";

	include("$_serverRoot/_inc/funcoes.php");
	
	
	function __autoload($class_name) {
    	global $_serverRoot;
		if(file_exists($_serverRoot.'adm/classes/' .$class_name.  '.php')){
			require_once $_serverRoot.'adm/classes/' .$class_name.  '.php';
		}else{
			pre(debug_backtrace());
			die("Verifique os arquivos instalados. A Classe $class_name não pode ser definida.");
		}
	}
	
	set_time_limit(10);
	session_start();

	$db = new db($banco,$host,$usr, $senha);
	

?>