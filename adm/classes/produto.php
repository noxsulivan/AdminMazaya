<?


class produto extends objetoDb {
	
	
    public function __construct($id)
    {
        parent::objetoDb('produtos',$id);

    }
	
	function item(){
			global $db,$pagina,$busca,$termosBusca,$canal,$k,$hotsite,$cliente;
			
					
					
					if((float)$this->preco_promocional){
						$preco = $this->preco_promocional;
					}else{
						$preco = $this->preco_venda;
					}
					
					
				$ret = '<div class="produtosDestaques pad20"
							id="produtoDestaque_'.$this->id.'"
							data-produto="'.$this->produto.'"
							data-codigo="'.$this->codigo.'"
							data-preco="'.$preco.'"
							data-linha="'.$this->linhas->linha.'">';
	  
	  
	  
	  
				$desconto_vista = 0;
				
				if(count($this->categorias)){
					foreach($this->categorias as $c){
						if($c->id == 33){
							$_DC = true;
						}
						if($c->id == 48){
							$lancamento = true;
						}
						if($c->desconto_vista){
							$categoriaDesconto = $c->categoria;  $catPanelasUrl = $c->url; $catDesconto = $c->desconto_vista;
						}
						
						$desconto_vista = max($desconto_vista,$c->desconto_vista);
					}
				}
				
					$ret .= '<div class="produtosDestaquesFoto">';
						if($this->fotos) {
							$ret .= '<a href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'" title="'.normaliza($this->produto).'">';
							$foto = $this->fotos[0];
							
							if(!$foto['height']){
								preg_match_all('/width="(.+?)" height="(.+?)"/',$foto[dim],$matches); $foto['width']	=	$matches[1][0]; $foto['height']	=	$matches[2][0];
							}
							
							
							if($foto['width'] > $foto['height']){
								$h = intval(($foto['height'] * 228) / $foto['width']);
								$t = intval(124 - ($h/2));
								$w = 228;
							}else{
								$h = 228; $t = 0;
								$h = intval(($foto['width'] * 228) / $foto['height']);
							};
							$ret .= '<img src="'.$pagina->img($this->fotos[0]['id'].'/228/228').'" width="'.$w.'" height="'.$h.'" alt="'.$this->fotos[0]['legenda'].'" style="margin-top:'.$t.'px" /></a>';
						}
					$ret .= '</div>';
			
					if($this->vendas > 2){
								$ret .= '<div class="produtosDestaquesBoxTop" title="Top de Vendas">Top de Vendas</div>';
					}
					
					//$ret .= '<div class="produtosDestaquesBoxSocial"><div class="fb-like" data-href="http://www.mesacor.com.br/Ver/"'.$this->id."/".$this->url.'" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div></div>';
								
								
					if($lancamento){
								$ret .= '<a href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'" title="'.normaliza($this->produto).'" class="produtosDestaquesBoxLancamento">Lançamento</a>';
					}
//					if($_DC){
//								$ret .= '<a href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'" title="'.normaliza($this->produto).'" class="produtosDestaquesBoxTDC">TDC</a>';
//					}
					if($this->brindes->id > 0){
								$ret .= '<a href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'" title="'.normaliza($this->produto).'" class="produtosDestaquesBoxBrinde">Ganhe um Brinde!</a>';
					}
					
					if((float)$this->preco_promocional > 0){
							//if($_DC){
								//$ret .= '<div class="produtosDestaquesBoxFreteGrBR"><a href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'" title="'.normaliza($this->produto).'">Frete Grátis</a></div>';
							//}else
							if($this->frete_gratis == 'sim'){
								$ret .= '<div class="produtosDestaquesBoxFreteGrBR"><a href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'" title="'.normaliza($this->produto).'">Frete Grátis</a></div>';
							}elseif((float)$this->preco_promocional > $pagina->configs['limite_frete_gratis']){// and !$this->has("categorias",array(31,35,70))
								$ret .= '<div class="produtosDestaquesBoxFreteGr"><a href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'" title="'.normaliza($this->produto).'">Frete Grátis</a></div>';
							}
					}elseif((float)$this->preco_venda > 0){
							//if($_DC){
								//$ret .= '<div class="produtosDestaquesBoxFreteGrBR"><a href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'" title="'.normaliza($this->produto).'">Frete Grátis</a></div>';
							//}else
							if($this->frete_gratis == 'sim'){
								$ret .= '<div class="produtosDestaquesBoxFreteGrBR"><a href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'" title="'.normaliza($this->produto).'">Frete Grátis</a></div>';
							}elseif((float)$this->preco_venda > $pagina->configs['limite_frete_gratis']){// and !$this->has("categorias",array(31,35,70))
								$ret .= '<div class="produtosDestaquesBoxFreteGr"><a href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'" title="'.normaliza($this->produto).'">Frete Grátis</a></div>';
							}
					}
					
					$ret .= '<a href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'" title="'.normaliza($this->produto).'">';
					
					
					if(isset($busca)){
						$busca = preg_replace("/\+/i"," ",$busca);
						$termosBusca = explode(" ",normaliza($busca));
						foreach($termosBusca as $_t){
							$_cod = preg_replace('/('.$_t.')/i', '<span class="destaqueBusca">${1}</span>', $this->codigo);
						}
						$ret .= $_cod;
					}//else{
						//if(!preg_match('/mailing/i',$_SERVER['QUERY_STRING']))
							//$ret .= '<span class="codigo">'.$this->codigo.'</span>';
					//}
					
                    
					$ret .= '<strong class="produto">'.normaliza(preg_replace("/".$this->linhas->linha."/i","", preg_replace("/ - /i"," ", preg_replace("/\"/i"," ", $this->produto)))).'</strong>';
					
					if($this->brindes->id > 0){
						$ret .= '<strong class="brinde"><img src="'.$pagina->localhost.'_imagens/24/promotion.png" width="24" height="24" /> Brinde: '.$this->brindes->produto.'</strong>';
					}
					
					$ret .= '</a>';
					
					if($this->linhas->id > 1){
						$ret .= '<a href="'.$pagina->localhost.$canal->url.'Linha/'.$this->linhas->url.'" class="produtosDestaquesLinha"> &raquo; '.$this->linhas->linha.'</a>';
	
					}
					
					
					for( $i = 2 ; $i <=12; $i++) {
						if($preco/$i > 100 and $i <= 5){
							$pre = $preco / $i;
							$_x = $i;
						}
					}
					
					
					//if($cliente->id || $convidado->id){
						if($this->apenas_televenda == 'sim'){
							$ret .= '<h3><img src="'.$pagina->localhost.'_imagens/telephone.png" width="24" height="24" />Televendas</h3>';
							$ret .= '<div class="botaoComprar"><a class="awesome orange">ENTRE EM CONTATO</a></div>';
						}elseif((float)$this->preco_promocional > 0){
							$ret .= '<a href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'" title="'.normaliza($this->produto).'">';
							$_pre = explode(",",number_format($this->preco_promocional,2,",","."));
							$ret .= '<div class="preco"><span class="overline">de R$ '.number_format($this->preco_venda,2,",",".").'</span> por R$'.$_pre[0].','.$_pre[1].'</div></a>';
							$ret .= '<a href="'.$pagina->localhost.$canal->url.'Ver/'.$this->id."/".$this->url.'" class="destaque">
									<img src="'.$pagina->localhost.'_imagens/1334723428_bonus.png" width="24" height="24" alt="Promoção" />'.number_format(100 - ($this->preco_promocional *100 /$this->preco_venda) ,0,",",".").'% de desconto</a>';
	
							if($cliente->id){
								$ret .= '<div class="botaoComprar">
										<a class="awesome orange" href="javascript:adicionarListaPresentes('.$this->id.',1);"> Adicionar Casamento</a>
										<a class="awesome orange" href="javascript:adicionarListaChas('.$this->id.',1);" /> Adicionar Chá de Panela</a>
										<a class="awesome orange" href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'">MAIS DETALHES</a></DIV>';
							}else{
								$ret .= '<div class="botaoComprar"><a class="awesome orange">APROVEITE!!!</a></DIV>';
							}
						}elseif((float)$this->preco_venda > 0){
							$ret .= ' <div class="preco">R$'.number_format($this->preco_venda,2,",",".").'</div>';
							
							if((float)$this->desconto < 1){
							}elseif((float)$this->desconto > 0){
								$ret .= '<span>&raquo; ou à vista no boleto:</span><br />';
								$ret .= '<div class="preco">R$ '.number_format($this->preco_venda - ($this->preco_venda * $this->desconto/100) ,2,",",".")."</div>";
							}elseif($desconto_vista){
								$ret .= '<span>&raquo; ou à vista no boleto:</span><br />';
								$ret .= '<div class="preco">R$ '.number_format($this->preco_venda - ($this->preco_venda * $pagina->configs['desconto_boleto']/100) ,2,",",".")."</div>";
							}
	
							if($cliente->id){
								$ret .= '<div class="botaoComprar">
										<a class="awesome orange" href="javascript:adicionarListaPresentes('.$this->id.',1);"><img src="'.$pagina->localhost.'_imagens/botao2013_cupom.png" width="25" height="25" alt="Compre este produto" />Lista Casamento</a>
										<a class="awesome orange" href="javascript:adicionarListaChas('.$this->id.',1);" /><img src="'.$pagina->localhost.'_imagens/botao2013_cupom.png" width="25" height="25" alt="Compre este produto" />Chá de Panela</a>
										<a class="awesome orange" href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'"><img src="'.$pagina->localhost.'_imagens/botao2013_zoom.png" width="25" height="25" alt="Compre este produto" />Mais Detalhes</a></DIV>';
							}else{
								$ret .= '<div class="botaoComprar"><a class="awesome orange" href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'"><img src="'.$pagina->localhost.'_imagens/botao2013_comprar.png" width="25" height="25" alt="Compre este produto" />Comprar</a></DIV>';
							}
	
						}else{
							$ret .= '<h3>Indisponível Online</h3>';

							if($cliente->id){
								$ret .= '<div class="botaoComprar">
										<a class="awesome orange" href="javascript:adicionarListaPresentes('.$this->id.',1);"><img src="'.$pagina->localhost.'_imagens/botao2013_cupom.png" width="25" height="25" alt="Compre este produto" />Lista Casamento</a>
										<a class="awesome orange" href="javascript:adicionarListaChas('.$this->id.',1);" /><img src="'.$pagina->localhost.'_imagens/botao2013_cupom.png" width="25" height="25" alt="Compre este produto" />Chá de Panela</a>
										<a class="awesome orange" href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'"><img src="'.$pagina->localhost.'_imagens/botao2013_zoom.png" width="25" height="25" alt="Compre este produto" />Mais Detalhes</a></DIV>';
							}						}
						$pre = $_x = 0;
//					}else{
//						$ret .= '<div class="botaoComprar"><a class="awesome orange" href="'.$pagina->localhost.$canal->url."Ver/".$this->id."/".$this->url.'"><img src="'.$pagina->localhost.'_imagens/botao2013_zoom.png" width="25" height="25" alt="Compre este produto" />Mais Detalhes</a></DIV>';
//					}
					
					
					 
					 $ret .= '</div>';
					 
					 return $ret;
	
	}
}
?>