<?

include('../ini.php');

if($tg=="Limpar"){pre($_SESSION);session_unset();session_destroy();die();}

$pagina = new layout($_SERVER['QUERY_STRING']);

$canal = new objetoDb('canais',$pagina->tg);

if(!$_SESSION["cadastro"]){
	$cadastro = new cadastro();
}else{
	$cadastro = $_SESSION["cadastro"];
}

//pre($pagina);
		
		$boleto = new objetoDb('boletos',$pagina->acao);
		
		$dias_de_prazo_para_pagamento = 5;
		$taxa_boleto = "0,0";
		$data_venc = ex_data($boleto->data_vencimento);
		
		//$valor_boleto=$valor_cobrado;
		//3328400000 +
		$dadosboleto["nosso_numero"] =  str_pad($boleto->id, 17, "0", STR_PAD_LEFT);//$rowconta_corrente['conta_corrente_boleto_numero_documento;  // Nosso numero sem o DV - REGRA: M�ximo de 11 caracteres!
		$dadosboleto["numero_documento"] = str_pad($boleto->id, 10, "0", STR_PAD_LEFT);	// Num do pedido ou do documento = Nosso numero
		
		$dadosboleto["data_vencimento"] = ex_data($boleto->data_vencimento); // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
		
		$dadosboleto["data_documento"] = date("d/m/Y");//ex_data($boleto->data_documento); // Data de emiss�o do Boleto
		
		$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
	
		// DADOS DO SEU CLIENTE
		$dadosboleto["sacado"] = normaliza($boleto->nome)." CPF/CNPJ:".$boleto->cpf." ".$boleto->cnpj;
			$dadosboleto["endereco1"] = normaliza($boleto->endereco.' '.$boleto->numero.' '.$boleto->complemento);
			$dadosboleto["endereco2"] = normaliza($boleto->cep.' '.$boleto->bairro.' - '.$boleto->cidade.'/'.$boleto->estado).'<br/>';
	
		// INFORMACOES PARA O CLIENTE     


		$dadosboleto["obs"] = $boleto->observacao;
		
		$dadosboleto["demonstrativo1"] = "";//$boleto->referencia;
		$dadosboleto["instrucoes"] =   "";
		$dadosboleto["instrucoes1"] = "";//$rowconta_corrente['conta_corrente_boleto_instrucoes;
		$dadosboleto["instrucoes2"] = "";
		$dadosboleto["instrucoes3"] = "";
		$dadosboleto["instrucoes4"] = "";
		$dadosboleto["sacador"] = $cadastro->dados->empresa_nome." - ".$cadastro->nome;
		$dadosboleto["guia_processo"] = $boleto->processo;
		$dadosboleto["guia_status"] = $boleto->status;
		$dadosboleto["guia_validade"] = $boleto->data_vencimento;
		
		$dadosboleto["valor_boleto"] = number_format($boleto->valor,2,',','.');
		
		// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
		$dadosboleto["quantidade"] = "001";
		$dadosboleto["valor_unitario"] = $valor_boleto;
		
		$dadosboleto["aceite"] = "N";
			
		$dadosboleto["uso_banco"] = ""; 	
		$dadosboleto["especie"] = "R$";
		$dadosboleto["especie_doc"] = "DM";
	
	
		// ---------------------- DADOS FIXOS DE CONFIGURA��O DO SEU BOLETO --------------- //
	
	
		// DADOS DA SUA CONTA - Bradesco
$dadosboleto["agencia"] = $pagina->configs['boleto_agencia'];//"1412"; // Num da agencia, sem digito
$dadosboleto["conta"] = $pagina->configs['boleto_conta'];//"30905";	// Num da conta, sem digito
$dadosboleto["conta_dv"] = $pagina->configs['boleto_conta_dv'];//"4"; 	// Digito do Num da conta

// DADOS PERSONALIZADOS - ITA�
$dadosboleto["carteira"] = "157";  // C�digo da Carteira

// DADOS PERSONALIZADOS - BANCO DO BRASIL
$dadosboleto["convenio"] = "003356";  // Num do conv�nio - REGRA: 6 ou 7 ou 8 d�gitos
$dadosboleto["contrato"] = "003356"; // Num do seu contrato
$dadosboleto["carteira"] = "18";
$dadosboleto["variacao_carteira"] = "-019";  // Varia��o da Carteira, com tra�o (opcional)

// TIPO DO BOLETO
$dadosboleto["formatacao_convenio"] = "6"; // REGRA: 8 p/ Conv�nio c/ 8 d�gitos, 7 p/ Conv�nio c/ 7 d�gitos, ou 6 se Conv�nio c/ 6 d�gitos
$dadosboleto["formatacao_nosso_numero"] = "2"; // REGRA: Usado apenas p/ Conv�nio c/ 6 d�gitos: informe 1 se for NossoN�mero de at� 5 d�gitos ou 2 para op��o de at� 17 d�gitos

// SEUS DADOS
$dadosboleto["identificacao"] = diretorio($pagina->configs['titulo_site']).'_'.$dadosboleto["numero_documento"];
$dadosboleto["cpf_cnpj"] = $pagina->configs['boleto_cpf_cnpj'];
$dadosboleto["endereco"] = $pagina->configs['boleto_endereco'];
$dadosboleto["cidade_uf"] = $pagina->configs['boleto_cidade_uf'];
$dadosboleto["cedente"] = $pagina->configs['boleto_cedente'];

	
	
	
	
//pre($dadosboleto);
	
	
	include($_SERVER['DOCUMENT_ROOT']."/boleto/include/funcoes_bb.php"); 
	include($_SERVER['DOCUMENT_ROOT']."/boleto/include/layout_bb.php");
		
			
?>