<?
$includeIni = $_SERVER['DOCUMENT_ROOT'].'/ini.php';
		
include($includeIni);
$db->query("truncate tabelas");
		$db->query("show table status");
		$resource = $db->resourceAtual;
		while($res = $db->fetch()){
				//pre($res);
				//if(!$db->fetch("select * from tabelas where tabela = '".$res['Name']."'")){
					$_POST['nome'] = $res['Name'];
					pre("Tabela ".$res['Name']." inserida");
					$db->inserir('tabelas');
				//}
				$db->resource($resource);
		}
		
		
		die;
?>

<?
$timeIni = microtime(true);
$_tmp = explode("/",$_SERVER['REQUEST_URI']);
	$include = '';
	$pattern = "/\/([a-z]+)\/(.)*/i";
	$includeIni = $_SERVER['DOCUMENT_ROOT'].'/adm/ini.php';
		
include($includeIni);

define ('COOKIE_NAME', diretorio($_SERVER['SCRIPT_FILENAME'])."2");

function limpaPost(&$v,$k){
	if(is_array($v))
		array_walk($v,"limpaPost");
	else
		$v = utf8_decode($v);
}
array_walk($_POST,"limpaPost");

$admin = new admin($_SERVER["QUERY_STRING"]);

$usuario = new usuario();
	if($usuario->conectado()){pre("oi");}
pre($usuario);die;
?>





<?
include("ini.php");
$rel = new objetoDb('variacoes',38);



	$pattern = "/\/([a-z]+)\/(.)*/i";
	pre($_SERVER['DOCUMENT_ROOT']);
	pre($_SERVER['REQUEST_URI']);
	$includeIni = $_SERVER['DOCUMENT_ROOT'].'/'.preg_replace($pattern,"$1",$_SERVER['REQUEST_URI'])."/ini.php";
	pre($includeIni);
	
	
pre($rel);

pre($rel->cadastros);

pre($db->indices_da_tabela('rel_itens'));
//pre($db->indices_da_tabela('itens'));
pre($db);
		
		
		die();
?>


<?php
/**
 * Controller para busca de CEP utilizando serviço dos correios
 * @author Julio Vedovatto <juliovedovatto@gmail.com>
 * @version 1.0
 *
 */
class Modulo_BuscaCepController extends Mage_Core_Controller_Front_Action {
	
	const CORREIOS_URL = 'http://www.buscacep.correios.com.br/servicos/dnec/consultaEnderecoAction.do';
	const CSS_QUERY_RESULT = '.ctrlcontent div table'; //seletor de css é bem limitado
	 
	private $_http;
	
    public function indexAction() {
    	$request = $this->getRequest();
    	$output = array('success' => true);
    	
    	try {
    		if (strlen(preg_replace('#\D#', '', $request->getParam('cep'))) !== 8)
    			throw new Exception('CEP Inválido');
    			
    		$this->_buildHttpRequest($request->getParam('cep'));
    		
	    	$output['dados'] = $this->_parseResultCEP();
    	} catch (Exception $err) {
    		$output['success'] = false;
    		$output['error'] = $err->getMessage();
    		$output['stack'] = $err->getTraceAsString();
    	}
    	
//   	Retornar resultado, codificado em JSON 	
    	$this->getResponse()
    		->clearHeaders()
			->setHeader('Content-Type', 'application/json')
    		->setBody(Mage::helper('core')->jsonEncode($output));
    }
    
//  PRIVATE FUNCTIONS ----------------------------------------------------------------------------------------------------------  
    
    /**
     * Construir requisicao HTTP.
     * @param string $cep
     */
    private function _buildHttpRequest($cep) {
    	$this->_http = new Zend_Http_Client();
    	$this->_http->setUri(self::CORREIOS_URL);
    	$this->_http->setHeaders('User-agent', 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT)');
    	
//   	NOTA: Lembrar que os Correios podem mudar os campos a qualquer momento, caso dê problema sempre verifique os campos na url http://www.buscacep.correios.com.br/ 	
    	
    	$this->_http->setParameterPost('relaxation', $cep);
    	$this->_http->setParameterPost('TipoCep', 'ALL');
    	$this->_http->setParameterPost('semelhante', 'N');
    	
//    	Hidden
    	$this->_http->setParameterPost('cfm', 1);
    	$this->_http->setParameterPost('Metodo', 'listaLogradouro');
    	$this->_http->setParameterPost('TipoConsulta', 'relaxation');
    	$this->_http->setParameterPost('StartRow', 1);
    	$this->_http->setParameterPost('EndRow', 10);
    }
    
    /**
     * Fazer a requisicao e retornar o html necessário.
     * @throws Exception
     * @return Zend_Dom_Query_Result
     */
    private function _makeRequest() {
    	$request = $this->_http->request('POST');
	    	
    	$dom = new Zend_Dom_Query($request->getBody());
    	
    	return $dom->query(self::CSS_QUERY_RESULT);
    }
    
    /**
     * Recuperar informações do logadouro.
     * @throws Exception
     * @return array
     */
    private function _parseResultCEP() {
    	$results = $this->_makeRequest();
	    	
    	if (count($results) === 0)
    		throw new Exception('Sem resultados');
    		
    	$dados = array(
    		'logadouro' => '',
    		'bairro' => '',
    		'localidade' => '',
    		'UF' => '',
    		'CEP' => ''
    	);
    	
    	$result = $results->current(); //pegar o primeiro resultado, que contém os dados
    	
    	$doc = new DOMDocument();
		$doc->appendChild($doc->importNode($result, true)); //jogar o DomElement num novo DomDocument
    	$dom = new Zend_Dom_Query($doc->saveHTML()); //setar novo html na classe Zend_Dom_Query, somente o output do elemento acima
    		
    	if (count($result_nodes = $dom->query('td')) !== 5)
			throw new Exception('Sem resultados');

		$dados['logadouro'] = $result_nodes->current()->nodeValue;
		
    	$result_nodes->next();
    	$dados['bairro'] = $result_nodes->current()->nodeValue;
    	
    	$result_nodes->next();
    	$dados['localidade'] = $result_nodes->current()->nodeValue;
    	
    	$result_nodes->next();
    	$dados['UF'] = $result_nodes->current()->nodeValue;
    	
    	$result_nodes->next();
    	$dados['CEP'] = $result_nodes->current()->nodeValue;
    	
    	return $dados;
    }
    
//  /PRIVATE FUNCTIONS ---------------------------------------------------------------------------------------------------------  
}

die;

?>