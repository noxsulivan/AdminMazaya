<?php

if (!defined('TOKEN')) define ('TOKEN', '');

/**
 * RetornoPagSeguro
 *
 * Classe de manipulação para o retorno do post do pagseguro
 *
 * @package PagSeguro
 */

  function PAGS_preparaDados($post, $confirmacao=true) {
    if ('array' !== gettype($post)) $post=array();
    if ($confirmacao) {
      $post['Comando'] = 'validar';
      $post['Token'] = TOKEN;
    }
    $retorno=array();
    foreach ($post as $key=>$value){
      if('string'!==gettype($value)) $post[$key]='';
      $value=urlencode(stripslashes($value));
      $retorno[]="{$key}={$value}";
    }
    return implode('&', $retorno);
  }


  function PAGS_tipoEnvio() {
    //Prefira utilizar a função CURL do PHP
    //Leia mais sobre CURL em: http://us3.php.net/curl
    global $_retPagSeguroErrNo, $_retPagSeguroErrStr;
    if (function_exists('curl_exec')){
      return array('curl', 'https://pagseguro.uol.com.br/Security/NPI/Default.aspx');
	}elseif ((PHP_VERSION >= 4.3) && ($fp = @fsockopen('ssl://pagseguro.uol.com.br', 443, $_retPagSeguroErrNo, $_retPagSeguroErrStr, 30))){
      return array('fsocket', '/Security/NPI/Default.aspx', $fp);
	}elseif ($fp = @fsockopen('pagseguro.uol.com.br', 80, $_retPagSeguroErrNo, $_retPagSeguroErrStr, 30)){
      return array('fsocket', '/Security/NPI/Default.aspx', $fp);
	}
    return array ('', '');
  }


  function PAGS_not_null($value) {
    if (is_array($value)) {
      if (sizeof($value) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
        return true;
      } else {
        return false;
      }
    }
  }


  function PAGS_verifica($post, $tipoEnvio=false) {
	  
    global $_retPagSeguroErrNo, $_retPagSeguroErrStr;
	
	echo '|post :'.print_r($post,true).'|';
	
    if ('array' !== gettype($tipoEnvio))
      $tipoEnvio = PAGS_tipoEnvio();
    $spost=PAGS_preparaDados($post);
    if (!in_array($tipoEnvio[0], array('curl', 'fsocket')))
      return __LINE__;
    $confirma = false;
	echo '|tipoEnvio :'.print_r($tipoEnvio,true).'|';
    if ($tipoEnvio[0] === 'curl') {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $tipoEnvio[1]);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $spost);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_TIMEOUT, 30);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      $resp = curl_exec($ch);
	  echo '|resp :'.print_r($resp,true).'|';
      if (!PAGS_not_null($resp)) {
        curl_setopt($ch, CURLOPT_URL, $tipoEnvio[1]);
        $resp = curl_exec($ch);
		echo '|resp :'.print_r($resp,true).'|';
      }
      curl_close($ch);
      $confirma = (strcmp ($resp, 'VERIFICADO') == 0);
	  echo '|confirma :'.print_r($confirma,true).'|';
    } elseif ($tipoEnvio[0] === 'fsocket') {
      if (!$tipoEnvio[2]) {
        die ("{$_retPagSeguroErrStr} ($_retPagSeguroErrNo)");
      } else {
        $cabecalho = "POST {$tipoEnvio[1]} HTTP/1.0\r\n";
        $cabecalho .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $cabecalho .= "Content-Length: " . strlen($spost) . "\r\n\r\n";
        $resp = '';
        fwrite ($tipoEnvio[2], "{$cabecalho}{$spost}");
        while (!feof($tipoEnvio[2])) {
          $resp = fgets ($tipoEnvio[2], 1024);
          if (strcmp ($resp, 'VERIFICADO') == 0) {
            $confirma = (strcmp ($resp, 'VERIFICADO') == 0);
            $confirma=true;
            break;
          }
        }
        fclose ($tipoEnvio[2]);
      }
    }
	echo '|'.$confirma.'|';
    if ($confirma && function_exists('retorno_automatico')) {
      $itens = array (
                'VendedorEmail', 'TransacaoID', 'Referencia', 'TipoFrete',
                'ValorFrete', 'Anotacao', 'DataTransacao', 'TipoPagamento',
                'StatusTransacao', 'CliNome', 'CliEmail', 'CliEndereco',
                'CliNumero', 'CliComplemento', 'CliBairro', 'CliCidade',
                'CliEstado', 'CliCEP', 'CliTelefone', 'NumItens',
              );
      foreach ($itens as $item) {
        if (!isset($post[$item])) $post[$item] = '';
        if ($item=='ValorFrete') $post[$item] = str_replace(',', '.', $post[$item]);
      }
      $produtos = array ();
      for ($i=1;isset($post["ProdID_{$i}"]);$i++) {
        $produtos[] = array (
          'ProdID'          => $post["ProdID_{$i}"],
          'ProdDescricao'   => $post["ProdDescricao_{$i}"],
          'ProdValor'       => (double) (str_replace(',', '.', $post["ProdValor_{$i}"])),
          'ProdQuantidade'  => $post["ProdQuantidade_{$i}"],
          'ProdFrete'       => (double) (str_replace(',', '.', $post["ProdFrete_{$i}"])),
          'ProdExtras'      => (double) (str_replace(',', '.', $post["ProdExtras_{$i}"])),
        );
      }
      retorno_automatico (
        $post['VendedorEmail'], $post['TransacaoID'], $post['Referencia'], $post['TipoFrete'],
        $post['ValorFrete'], $post['Anotacao'], $post['DataTransacao'], $post['TipoPagamento'],
        $post['StatusTransacao'], $post['CliNome'], $post['CliEmail'], $post['CliEndereco'],
        $post['CliNumero'], $post['CliComplemento'], $post['CliBairro'], $post['CliCidade'],
        $post['CliEstado'], $post['CliCEP'], $post['CliTelefone'], $produtos, $post['NumItens']
      );
    }else{
    	return __LINE__;
	}
    return __LINE__;
  }
  
if ($_POST) {
  //PAGS_verifica($_POST);
  //die();
}


?>
