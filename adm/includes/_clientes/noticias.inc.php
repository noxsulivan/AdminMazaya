<?

				//$barra['botoes']['pdf']['funcao'] = "imprimirPDF('".$this->tabela."','".$this->id."')";
				$header['href'] = '';
				$header['classe'] = 'f_link';
				$header['campo'] = 'pdf';
				$header['visor'] = '<img src="imagens/icons/16x16/comment.png" align="absmiddle">Publicar no Facebook';
				$header['funcao'] = 'publicar';
				$lista['headers'][] = $header;
				
				$include_cliente_comando = "\$item[\$j][\$i++] = \$this->tabela.\"','\".\$obj->id;";


?>