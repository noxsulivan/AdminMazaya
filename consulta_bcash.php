<?





			
	date_default_timezone_set('America/Sao_Paulo');
	//error_reporting(E_ALL);

$email = "pedro@mesacor.com.br";
  
$token = "46B4716026244020A6D522C01BF41918";   
  
$urlPost = "https://www.pagamentodigital.com.br/transacao/consulta/";  
$transacaoId = 19040217;  
$pedidoId = 173016;  
$tipoRetorno = 1;  
$codificacao = 1;  
  
ob_start();  
$ch = curl_init();  
curl_setopt($ch, CURLOPT_URL, $urlPost); curl_setopt($ch, CURLOPT_POST, 1);  
curl_setopt($ch,CURLOPT_POSTFIELDS,array("id_transacao"=>$transacaoId,  
"id_pedido"=>$pedidoId,"tipo_retorno"=>$tipoReto rno,"codificacao"=>$codificacao));  
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Basic  
".base64_encode($email. ":".$token)));  
curl_exec($ch);  
  
/* XML ou Json de retorno */   
$resposta = ob_get_contents();  
  
ob_end_clean();  
  
/* Capturando o http code para tratamento dos erros na requisição*/   
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
curl_close($ch);  
  
if($httpCode != "200"){  
//Tratamento das mensagens de erro  
}else{  
//Tratamento dos dados da transação consultada.  
}

	

?>