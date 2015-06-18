<?


class layout{
	var $tg;
	var $acao;
	var $id;
	var $html;
	var $ABSURL;	
	var $localhost;
	var $root;
	var $titulo;
	var $configs = array();
	var $trilha = array();
	var $admin;
	var $db;
	
	
	
	
	function layout($vars){
		global $db,$_localhost, $_ABSURL, $_serverRoot, $_siteRoot, $_admin;
		
		$this->db = $db;
		$vars = explode("/",$vars);

		$t = $vars[0];
		
		if($t=='')
			$this->tg = 'Home';
		else
			$this->tg = $t;
			
		$this->acao =	isset($vars[1]) ? $vars[1] : '';			
		$this->id =		isset($vars[2]) ? $vars[2] : '';		
		$this->extra =	isset($vars[3]) ? $vars[3] : '';
		
		if(!file_exists(".htaccess")){
			$this->links = "?";
		}
		
		

		$sql = "select * from configuracoes";
		$db->query($sql);
		if(!$db->rows) die(mysql_error()."Configurações inacessíveis");
		while($res = $db->fetch())
			$this->configs[$res['parametro']] = nl2br($res['valor']);
				
		$_dir = explode("/",$_SERVER['REQUEST_URI']);
		$dir = $_dir[1];
		
			$this->localhost = $_localhost;
			$this->ABSURL = $_ABSURL;
			$this->_serverRoot = $_serverRoot;
			$this->_siteRoot = $_siteRoot;
			$this->admin = $_admin;
		
	
			if(!isset($_SESSION["maz_lang"])){
				$this->lang = '1';
			}else{
				$this->lang = $_SESSION["maz_lang"];
			}
		
	}
	function erro($msg){
		global $close;
		//$close = "erro";
		if($msg){
			$this->html .= '<div id="erro"><img src="'.$this->localhost.$this->admin.'imagens/32x32/dialog-error.png" alt="" align="absmiddle"/>'.$msg.'</div>';
			return true;
		}else
			return false;
	}
	
	function mensagem($msg){
		global $close;
		$close = "mensagem";
		$this->html .= '
		<div id="mensagem">'.$msg.'</div>
		';
	}

	
	
	function caminho($track){
		global $pagina;
		
		if(isset($this->configs['breadPath']) and $this->configs['breadPath'] == 'sim'){
			$tr = '<div id="caminhodePao"><a href="'.$this->localhost.'">Home</a> ';
			$e = $this->localhost;
			foreach($track as $k => $v){
				$e .= $v;
				$tr .= ' &raquo; <a href="'.$e.'">'.$k.'</a>';
			}
			
			$tr .= '</div>';
			return $tr;
		}else{
			return null;
		}
	}
	
	function trilha ($obj){
		$this->trilha[] = $obj->id;
		if($obj->id != 1)
			$this->trilha($obj->canais);
	}
	
	function menuOld($parans=array()){
		//pre(func_get_args());
		//$posicao=0,$pai=0,$nivel=0,submenu,class
		extract($parans);
		
		if(!isset($posicao))$posicao = NULL;
		if(!isset($pai))$pai = 1;
		if(!isset($nivel))$nivel = 1;
		if(!isset($submenu))$submenu = 0;
		if(!isset($class))$class = 1;

		$sql = "select idcanais from canais where idcanais_2 = '$pai' and status != 'nao'".($posicao ? " and idposicoes_do_menu = '$posicao'" : "")." order by ordem asc";
		//pre($sql);
		
		$this->db->query($sql);	
		$resourceAtual = $this->db->resourceAtual;	
		if($this->db->rows){
			echo '
			<ul class="menuUl_nivel'.$nivel.'" id="submenu_'.$pai.'" >';
			while($res = $this->db->fetch()){
				$canal = new objetoDb('canais',$res['idcanais']);
				if($canal->tipos_de_canais->id == 5){
						if(preg_match("/pop/i",$atr['Janela'])){
								echo '<li><a href="'.$atr['Endereço'].'">'.$canal->canal.'</a>';
						}else{
								echo '<li><a href="'.$this->localhost.$this->links.$canal->url.'">'.$canal->canal.'</a>';
						}
				}else{
					echo '<li id="menu_'.$canal->id.'"><span class="menuLi_nivel'.$nivel.'"><a href="'.( $nivel != 0 ? $this->localhost.$this->links.$canal->url : "#" ).'" class="menuA_nivel'.$nivel.'">'.htmlentities($canal->canal).'</a></span>';
				}
				if(in_array($canal->id,$this->trilha) or $submenu == 1)
					$this->menu(array('posicao'=>$posicao,'pai'=>$canal->id,'submenu'=>$submenu,'class'=>$class,'nivel'=> $nivel + 1));
				
				$this->db->resource($resourceAtual);
				echo "</li>";
			}
			echo "
			</ul>";
		}
	}
	
	function menu($parans=array()){
		global $canal;
		//pre(func_get_args());
		//$posicao=0,$pai=0,$nivel=0,submenu,class
		extract($parans);
		
		if(!isset($posicao))$posicao = NULL;
		if(!isset($pai))$pai = 1;
		if(!isset($nivel))$nivel = 1;
		if(!isset($classnivel1))$classnivel1 = "nav";
		if(!isset($submenu))$submenu = 0;
		if(!isset($class))$class = 1;
		if(!isset($ajax))$ajax = 0;
		if(!isset($proprio))$proprio = null;
		if(!isset($status))$status = null;
		if(!isset($restricao))$restricao = null;
		if(!isset($urlprefix))$urlprefix = null;
		if(!isset($lang))$lang = null;
		if(!isset($strip))$strip = null;

		$sql = "select idcanais from canais where ".
			(!is_null($proprio) ? "(idcanais = $proprio or idcanais_2 = '$pai')": "idcanais_2 = '$pai'").
			($restricao ? " and idtipos_de_restricoes = $restricao ":
				(!$status ? " and status = 'sim'" : "")).
			($lang ? " and ididiomas = '$lang' ": '').
			($posicao ? " and idposicoes_do_menu = '$posicao'" : "").
			" order by ordem asc";
		//pre($sql);
		
		$this->db->query($sql);	
		$resourceAtual = $this->db->resourceAtual;	
		if($this->db->rows){
			if(!$strip) echo ' <ul'.($nivel == 1 ? ' id="'.$classnivel1.'"' : '').'>';
			if($home){
				$c = new objetoDb('canais',1);
				if(!$strip) echo '<li'.( $canal->id == $c->id ? ' class="current"' : '').'>';
						echo '<a class="menuA'.($proprio == $c->id ? ' canalPai' : '').'" href="'.$this->localhost.$c->url.'" '.(($ajax) ? 'onclick="return menu(\''.$c->url.'\')"': '').'>'.htmlentities($c->canal).'</a>';
				if(!$strip) echo "</li>";
			}
			while($res = $this->db->fetch()){
				$c = new objetoDb('canais',$res['idcanais']);
				if(!$strip) echo '<li'.( $canal->id == $c->id ? ' class="current"' : '').'>';
					if($c->tipos_de_canais->id == 5){
							if(preg_match("/pop/i",$atr['Janela'])){
									echo '<a href="'.$atr['Endereço'].'">'.$c->canal.'</a>';
							}else{
									echo '<a href="'.$this->localhost.$urlprefix.$c->url.'">'.$c->canal.'</a>';// target="_blank"
							}
					}else{
						echo '<a class="menuA'.($proprio == $c->id ? ' canalPai' : '').'" href="'.$this->localhost.$urlprefix.$c->url.'" '.(($ajax) ? 'onclick="return menu(\''.$c->url.'\')"': '').'>'.htmlentities($c->canal).'</a>';
					}
					if(in_array($c->id,$this->trilha) or $submenu == 1 and is_null($proprio))
						$this->menu(array('posicao'=>$posicao,'pai'=>$c->id,'submenu'=>$submenu,'class'=>$class,'nivel'=> $nivel + 1,'status'=>$status));
					
					$this->db->resource($resourceAtual);
				if(!$strip) echo "</li>";
				else echo " . ";
			}
			if($extra){
				$c = new objetoDb('canais',$extra);
				if(!$strip) echo '<li'.( $canal->id == $c->id ? ' class="current"' : '').'>';
						echo '<a class="menuA'.($proprio == $c->id ? ' canalPai' : '').'" href="'.$this->localhost.$c->url.'" '.(($ajax) ? 'onclick="return menu(\''.$c->url.'\')"': '').'>'.htmlentities($c->canal).'</a>';
				if(!$strip) echo "</li>";
			}
			echo "
			</ul>";
		}
	}
	
	function img($img){
		if(file_exists($this->_siteRoot.'_cache/'.diretorio($img).'.jpg')){
			return $this->localhost.'_cache/'.diretorio($img).'.jpg';
		}else{
			return $this->localhost.'img/'.$img;
		}
	}
}
?>
