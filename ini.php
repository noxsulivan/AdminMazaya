<?
	date_default_timezone_set('America/Sao_Paulo');
	//session_save_path($_SERVER['DOCUMENT_ROOT'].'/_tmp');
	//error_reporting(E_ALL);
			
	$http = $_SERVER['HTTPS'] == 'on' ? "https" : "http";
			$_localhost = $http."://".$_SERVER['HTTP_HOST']."/";	
			//$_localhost = "http://www.mesacor.com.br/";			
			$_ABSURL = $http."://".$_SERVER['HTTP_HOST']."/";	
			
			$_serverRoot = $_SERVER['DOCUMENT_ROOT']."/";	
			$_siteRoot = $_SERVER['DOCUMENT_ROOT']."/";			
			$_admin = "admin/";
			
	$host = "localhost";
			
$usr = "mazay620_mazaya";		$senha = "mazaya";			$banco = "mazay620_mesacor";

	include("$_serverRoot/_inc/funcoes.php");
	include("inc.funcoes.php");
	
	
	function __autoload($class_name) {
    	global $_serverRoot;
		if(file_exists($_serverRoot.'adm/classes/' .$class_name.  '.php')){
			require_once $_serverRoot.'adm/classes/' .$class_name.  '.php';
		}else{
			pre(debug_backtrace());
			die("Verifique os arquivos instalados. A Classe $class_name não pode ser definida.");
		}
	}
	
	
	session_start();

	$db = new db($banco,$host,$usr, $senha);
	

?>