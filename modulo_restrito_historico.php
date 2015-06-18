<h2><?=$titulo?></h2>

<?
	$db->query('select * from pedidos where idcadastros = "'.$cadastro->id.'" and idestagios > 1');
	while($res = $db->fetch()){
				$pedido = new objetoDb('pedidos',$res['idpedidos']);
				//$dados = (object) unserialize($pedido->serialized);
				$dados = $cadastro;
					
					echo "<h2>Pedido #".str_pad($pedido->id,10,0, STR_PAD_LEFT)." - Total R$ ".number_format($pedido->valor,2,',','')." - ".$pedido->data."</h2>";
					echo "<strong>".$pedido->estagios->estagio."</strong><br>";
					echo $pedido->status_transacao;
					
					?>
                    
                          <h3>Dados para entrega:</h3>
                          <blockquote><?=$cadastro->endereco?>, <?=trim($cadastro->numero.' '.$cadastro->complemento.' '.$cadastro->bairro)?><br />
                          <?=$cadastro->cidades->cidade?>, <?=$cadastro->estados->estado?>, <?=$cadastro->cep?><br />
                          Frete: <strong>R$ <?=number_format($pedido->valor_frete,2,',','.')?></strong> - Via <strong><?=( $pedido->tipo_frete == "EN" ? "PAC - Encomenda normal - Correios" : "Sedex - Correios")?></strong><br />
                          Prazo de entrega: <strong><?=$pedido->prazo?> dias úteis</strong> a partir da data de confirmação do pagamento.<br />
                          Rastreamento: <?=$pedido->rastreamento ? $pedido->rastreamento : 'Indisponível no momento'?></blockquote>
                          <h3>Forma de pagamento escolhida</h3>
                          <blockquote><strong><?=$pedido->tipo_pagamento?></strong> Parcelamento: <strong><?=($pedido->parcelas > 1 ? $pedido->parcelas.' parcelas' : ' Á vista')?></strong>
                    <?
					if(eregi('itau',$pedido->tipo_pagamento)){
						$Itau = new Itaucripto;
						$dados = $Itau->geraDados
						(
								$codEmp = 'J0043497660001370000012814',
								$pedido->id,
								$valor = number_format($pedido->valor,2,',',''),
								$observacao = '',
								$chave = 'MESACORTRA243101',
								$nomeSacado = $cadastro->nome.' '.$cadastro->sobrenome,
								$codigoInscricao = 01,
								$numeroInscricao = str_replace('.','',str_replace('-','',$cadastro->cpf)),
								$enderecoSacado = $cadastro->endereco,
								$bairroSacado =  $cadastro->bairros->bairro,
								$cepSacado = $cadastro->cep,
								$cidadeSacado = $cadastro->cidades->cidade,
								$estadoSacado = $cadastro->estados->nome,
								$dataVencimento = date("dmY",time()+60*60*24*5),
								$urlRetorna = 'https://www.mesacor.com.br',
								$obsAd1 = '',$obsAd2 = '',$obsAd3 = ''
						);
						
						$Itau->decripto($dados, $chave);
						//pre($Itau);
						//$Itau->decripto($dados, $chave);
						$consulta = $Itau->geraConsulta('J0043497660001370000012814', $pedido->id, 1, 'MESACORTRA243101');
						
						//pre($Itau);
						//$data = http_build_query(array("DC" => $consulta)); ?>
						
						<form action="https://shopline.itau.com.br/shopline/shopline.asp" method="post" target="_blank">
						<input type="hidden" name="DC" value="<?=$dados?>" />
						<button type="submit" class="botao grey"><strong>Gerar a 1ª via</strong></button>
						</form> 
						<form action="https://shopline.itau.com.br/shopline/reemissao.asp" method="post" target="_blank">
						<input type="hidden" name="DC" value="<?=$dados?>" />
						<button type="submit" class="botao grey"><strong>2ª via do boleto</strong></button>
						</form>
						
						<?
					}
					?>
                    </blockquote>
                    <?
					echo '<div class="clear"></div>';
					echo '<h3>Ítens solicitados</h3>';
					echo '<blockquote>'.$pedido->descricao.'</blockquote>';
					echo '<div class="clear espaco"></div>';
	}
?>