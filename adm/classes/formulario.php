<?


class formulario{
	var $registro;
	var $tabela;
	
function formulario($admin){
		global $usuario;
	
		$reflector = new ReflectionClass('admin');
		$properties = $reflector->getProperties();
		foreach($properties as $property){
			$this->{$property->getName()} = $admin->{$property->getName()};
		}
		if($admin->id)
			$this->registro = new objetoDb($admin->tabela, $admin->id);
		
		$this->propriedades["total"] = 0;
		
		$this->addOpcao('onsubmit','return enviaFormulario(\''.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/listar@'.$this->acao_pag.'@'.$this->acao_busca.'@'.$this->acao_ordem.'/'.$this->extra.'\')');
		
		$this->propriedades["method"] = "post";
		$this->propriedades["action"] = $this->localhost.$this->admin.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/'.( $acao ? $acao : "salvar@").$this->acao_pag.'@'.$this->acao_busca.'@'.$this->acao_ordem.( $this->id ? '/'.$this->id : '' ).'/'.$this->extra;
		$this->propriedades["name"] = 'name=formGeral id=formGeral';
		$this->propriedades["onsubmit"] =  $this->opcoes['onsubmit'];
		$this->propriedades["target"] = $this->opcoes['formTarget'];
		$this->propriedades['id'] = $admin->idtabela;
		//pre($usuario->tabelas);die();
		$this->propriedades['edicao'] = @in_array($this->tabela,$usuario->tabelas);
		
		
	}

function addOpcao($parametro, $valor){
		$this->opcoes[$parametro] = $valor;
	}

function tit(){
		global $db, $usuario;
		
		$titulo['icone'] = $this->menu->icone ? $this->menu->icone : 'accessories-text-editor.png';
		
		switch($this->acao){
			case 'editar':case 'abrir': $titulo['acao'] = 'Edi&ccedil;&atilde;o'; break;
			case 'novo': $titulo['acao'] = 'Inclus&atilde;o'; break;
			case 'imprimir': $titulo['acao'] = 'Imprimir'; break;
		}
		$titulo['caption'] = $this->tg;
		
		return $titulo;
	}
function barra(){
		global $usuario;
		
		
			$barra['botoes']['fechar']['caption'] = 'Voltar';
			$barra['botoes']['fechar']['href'] = '#';
			$barra['botoes']['fechar']['funcao'] = "toggleAdmin()";
			$barra['botoes']['fechar']['title'] = "Clique para voltar para a listagem";
			$barra['botoes']['fechar']['imagem'] = 'back.png';
		if((@in_array($this->tabela,$usuario->tabelas) and !preg_match('/^view_/i',$this->tabela)) or $usuario->id == 1){

			$barra['botoes']['imprimir']['caption'] = 'Imprimir';
			$barra['botoes']['imprimir']['href'] = '#';
			$barra['botoes']['imprimir']['title'] = "Clique para abrir versão para impressão";
			$barra['botoes']['imprimir']['funcao'] = "toggleAdmin()";
			$barra['botoes']['imprimir']['imagem'] = 'print.png';
			
			if(file_exists("includes/_clientes/".$this->tabela.".php")){
				$barra['botoes']['pdf']['caption'] = '.PDF';
				$barra['botoes']['pdf']['href'] = '#';
				$barra['botoes']['pdf']['title'] = 'Imprimir/Fazer download de versão PDF dessa listagem';
				$barra['botoes']['pdf']['funcao'] = "imprimirPDF('".$this->tabela."','".$this->id."')";
				$barra['botoes']['pdf']['imagem'] = 'pdf.png';
			}
		
			$barra['botoes']['deletar']['caption'] = 'Excluir registro';
			$barra['botoes']['deletar']['href'] = '#';//.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/removerGrupo';
			$barra['botoes']['deletar']['title'] = 'Excluir todos os registro selecionados';
			$barra['botoes']['deletar']['funcao'] = "remover('".$this->tg."','".$this->id."')";
			$barra['botoes']['deletar']['imagem'] = 'delete.png';
		
			$barra['botoes']['salvar']['caption'] = 'Salvar';
			$barra['botoes']['salvar']['href'] = '#'.$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/listar';
			$barra['botoes']['salvar']['title'] = 'Salvar alterações';
			$barra['botoes']['salvar']['funcao'] = "enviaFormulario('".$this->tg.'@'.$this->sub_tg.'@'.$this->sub_id.'/listar@'.$this->acao_pag.'@'.$this->acao_busca.'@'.$this->acao_ordem."')";
			$barra['botoes']['salvar']['imagem'] = 'save.png';
		}
		
		return $barra;
	}
function explicacao($mensagem){
	$this->explicacao_formulario($mensagem);
}
function explicacao_formulario($mensagem){
		$this->html .= '<div class=form_explicacao>'.$mensagem.'</div>';
	}
function fieldset($tit = NULL, $hidden = false, $class = false){
		
		$this->propriedades["total"]++;
		
		$this->fieldset = new fieldset($this,$tit,$hidden,$class);
		$this->fieldsets[$this->fieldset->index]['propriedades'] = &$this->fieldset->propriedades;
		$this->fieldsets[$this->fieldset->index]['campos'] = &$this->fieldset->campo;
		//$this->fieldsetsProps[htmlentities($tit)] = &$this->fieldset->propriedades;
	}
function fieldsets(){
	return $this->fieldsets;
	}
}
?>