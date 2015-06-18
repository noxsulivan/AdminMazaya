<?
$cliente = new objetoDb("clientes_potenciais",$admin->id);



$nome_arquivo = "Apresentação NGResinas";
$arquivo[tmp_name] = $_SERVER['DOCUMENT_ROOT']."/Apresentacao_NGResinas.pdf";
$arquivo[name] = "Apresentação NGResinas.pdf";
	
				
				
//pdf($buffer,$arquivo[tmp_name],TRUE);

				
				$emails = split(',',$_POST['para']);
				
					$corpo = '
					<p style="font-size:12pt">'.( (date("H") < 12) ? "Bom dia ": "Boa tarde " ).$cliente->responsavel.'</p>
					<p style="font-size:12pt">Conforme contato, segue anexo breve histórico de nossa empresa para vosso conhecimento. Acessando nosso site www.ngresinas.com.br você encontrará maiores informações.</p>
					<p style="font-size:12pt">Para que possamos lhe prestar um melhor atendimento, encaminhe os grades e materiais usados por vossa empresa, assim lhe encaminharemos ofertas e disponibilidade de estoque somente do que possa lhe interessar.</p>
					<p style="font-size:12pt">&nbsp;</p>
					<p style="font-size:12pt">Obrigado pela atenção.</p>
					<p style="font-size:12pt">Cordialmente</p>';
										
					//<p>Acesse o formulário <a href="http://www.ngresinas.com.br/PreCadastroEmpresa/'.base64_encode($cliente->email).'">clicando aqui</a> e complete o pré-cadastro da sua empresa, os grades e quantidades de seu interesse para que possamos verificar a disponibilidade em estoque. Assim poderemos agilizar o processo para o atendimento eficiente e lhe encaminhar proposta comercial para análise. Ou se preferir, encaminhe sua resposta por e-mail</p>

					mailClass($cliente->email,$nome_arquivo,$corpo,"contato@ngresinas.com.br","NGResinas",array($arquivo),true,$PARA);
					//mailClass("contato@ngresinas.com.br",utf8_decode($nome_arquivo),$corpo,"contato@ngresinas.com.br","NGResinas",array($arquivo));
	
				$ret['status'] =  "ok";
				$ret['mensagem'] =  "<h3>A mensagem foi enviada com sucesso.</h3>";
				$ret['arquivo'] =  $arquivo;
				
				
	echo json_encode($ret);
?>