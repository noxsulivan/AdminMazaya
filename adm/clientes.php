<?
//$db->query("select * from clientes");
//while($res = $db->fetch()){
//	$db->query('update clientes set blog = "'.strtolower(diretorio($res['nome'])).'" where idclientes = "'.$res['idclientes'].'"');
//}
switch($admin->acao){
case "editar":
	$admin->registro = new objetoDb($admin->tabela, $admin->id);
case "novo";
	$admin->ini_formulario();
	
	 
	$admin->tit_formulario('Dados do cliente');
	$admin->campo_simples('Nome', 'nome');
	$admin->campo_simples('Data de nascimento', 'nascimento');
	$admin->separador();
	$admin->campo_simples('Pessoa', 'cpf');
	$admin->campo_simples('RG/Inscrição Estadual', 'rg');
	
	
	if($db->campoExiste('idclientes','usuarios')){
		$admin->tit_formulario('Blog');
		$admin->campo_simples('Plano de gerenciamento', 'idplanos');
		$admin->campo_simples('Tipo', 'idtipos_de_clientes');
		$admin->campo_simples('Modelo', 'idmodelos');
		$admin->separador();
		$admin->campo_simples('Taxa de Planejamento de Viagem', 'tpv');
		$admin->separador();
		$admin->campo_simples('Título do blog', 'titulo_blog');
		if($db->campoExiste('blog','clientes')) $admin->campo_simples('Código de acesso', 'codigo_acesso');
		$admin->campo_simples('Data de evento', 'evento');
		$admin->campo_simples('Data de vencimento dos boletos', 'data_vencimento');
	}
	
	$admin->tit_formulario('Status no site');
	//$admin->campo_simples('E-mail', 'email');
	$admin->campo_simples('Login', 'login');
	$admin->campo_simples('Senha', 'senha');
	$admin->campo_simples('Autorizado', 'autorizado');
	$admin->campo_simples('Data de cadastro', 'data_cadastro');
	if(!$db->campoExiste('idclientes','usuarios')){
		$admin->campo_simples('Data do casamento', 'evento');
		$admin->campo_simples('Código de acesso', 'codigo_acesso');
	}
	$admin->tit_formulario('Dados de contato');
	$admin->campo_simples('Telefone', 'telefone');
	$admin->campo_simples('Celular', 'celular');
	$admin->campo_simples('Telefone Comercial', 'telefone_comercial');
	
	$admin->separador();
	$admin->campo_CEP();
	$admin->campo_cidadeEstado();
	$admin->campo_simples('Endereço', 'endereco');
	$admin->campo_simples('Número', 'numero');
	$admin->campo_simples('Complemento', 'complemento');
	
	if($db->tabelaExiste('despesas')){
		$admin->tit_formulario('Despesas');
		$admin->campo_filhos('despesas');
	}
	
	if(!$db->campoExiste('idclientes','usuarios') and $db->tabelaExiste('presentes')){
		$admin->tit_formulario('Presentes selecionados');
		$admin->campo_filhos('presentes');
	}
	
	if($db->campoExiste('idclientes','arquivos')){
		$admin->tit_formulario("Música");
		$admin->campo_arquivo();
	}
	
	if($db->campoExiste('idclientes','fotos')){
		$admin->tit_formulario("Convite");
		$admin->campo_fotos();
	}
	//if($db->campoExiste('blog','clientes')) $admin->campo_simples('Blog', 'url');
	
	
	
	
	
	$admin->end_formulario();
break;
case "salvar":

	if($admin->sub_tg == 'convite'){
			$db->salvar_fotos('clientes',$usuario->clientes->id);
			$admin->funcao = "formulario";
	}elseif($admin->sub_tg == 'musica'){
			$db->query('delete from arquivos where idclientes = "'.$usuario->clientes->id.'"');
			$db->salvar_arquivos('clientes',$usuario->clientes->id);
			$admin->funcao = "formulario";
	}else{
		if($admin->id == ''){
			$db->inserir('clientes');
			
			if(!$_POST["login"])
				$_POST["login"] = diretorio($_POST['nome']);
			$_POST["senha"] = md5($_REQUEST["senha"]);
				
			$inserted_id = $db->inserted_id;
			$db->salvar_fotos('clientes',$inserted_id);
			$db->salvar_arquivos('clientes',$inserted_id);
			$db->inserirCampo_filhos('clientes',$inserted_id);
			
			$_POST['idclientes'] = $inserted_id;
			
			/*if($_REQUEST['blog']){
				
				$cliente = new objetoDb('clientes',$_id);
				$ch = curl_init();
				
					$tmp = explode("@",$cliente->email);
					if($tmp[0]){
						$postFields = array('username' => $tmp[0],'password' => $cliente->codigo_acesso,'blog' => array( 'domain' => $cliente->url, 'email' => $cliente->email, 'title' => $cliente->nome ) );
						$spost = http_build_query($postFields);					
						curl_setopt($ch, CURLOPT_URL, "http://blogs.mazaya.com.br/wp-admin/_Mazaya_creatUsers.php");
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $spost);
						echo utf8_decode(curl_exec($ch));
					}
				curl_close($ch);
			}*/
			
			if($db->campoExiste('idclientes','usuarios')){
				$db->inserir('usuarios');
				$inserted_id = $db->inserted_id;
				
				$cliente =new objetoDb('clientes',$_POST['idclientes']);
					if($cliente->planos->id ==2){
						$dadosMenus = array(1,4,5,100,104,106,107,108);
					}else{
						$dadosMenus = array(1,4,5,100,104,107);
					}
					
					$dadosConfigs = array(array(2,150));
					$db->tabela_link('usuarios','menus',$inserted_id,$dadosMenus);
					$db->tabela_link('usuarios','configuracoes',$inserted_id,$dadosConfigs);
					
					
					$_POST['titulo'] = 'Página Inicial';
					$_POST['url'] = diretorio(trim($cliente->nome));
					$_POST['texto'] = '';
					$_POST['idtipos_de_paginas'] = 1;
					$_POST['visivel_cliente'] = 'sim';
					$_POST['ordem'] = ++$o;
					$db->inserir('paginas');
					
					$_POST['titulo'] = 'Sobre';
					$_POST['url'] = diretorio($cliente->nome."_".$_POST['titulo']);
					$_POST['texto'] = '';
					$_POST['idtipos_de_paginas'] = 1;
					$_POST['visivel_cliente'] = 'sim';
					$_POST['ordem'] = ++$o;
					$db->inserir('paginas');
					
					$_POST['titulo'] = 'Álbum de fotos';
					$_POST['url'] = diretorio($cliente->nome."_".$_POST['titulo']);
					$_POST['texto'] = '';
					$_POST['idtipos_de_paginas'] = 6;
					$_POST['visivel_cliente'] = 'sim';
					$_POST['ordem'] = ++$o;
					$db->inserir('paginas');
					
					$_POST['titulo'] = 'Sobre a festa';
					$_POST['url'] = diretorio($cliente->nome."_".$_POST['titulo']);
					$_POST['texto'] = '<table border="0" cellpadding="3"><tr><td><strong>Evento: </strong></td><td>INFORME</td></tr><tr><td><strong>Data: </strong></td><td>INFORME</td></tr><tr><td><strong>Horário: </strong></td><td>INFORME</td></tr><tr><td><strong>Atrações: </strong></td><td>INFORME</td></tr><tr><td><strong>Local: </strong></td><td>INFORME</td></tr></table>';
					$_POST['idtipos_de_paginas'] = 4;
					$_POST['visivel_cliente'] = 'sim';
					$_POST['ordem'] = ++$o;
					$db->inserir('paginas');
					
					
					$_POST['titulo'] = 'O presente desejado';
					$_POST['url'] = diretorio($cliente->nome."_".$_POST['titulo']);
					$_POST['texto'] = '';
					$_POST['idtipos_de_paginas'] = 1;
					$_POST['visivel_cliente'] = 'nao';
					$_POST['ordem'] = ++$o;
					$db->inserir('paginas');
					
					$_POST['titulo'] = 'Comprar Presente';
					$_POST['url'] = diretorio($cliente->nome."_".$_POST['titulo']);
					$_POST['texto'] = '';
					$_POST['idtipos_de_paginas'] = 5;
					$_POST['visivel_cliente'] = 'nao';
					$_POST['ordem'] = ++$o;
					$db->inserir('paginas');
					
					$_POST['titulo'] = 'Mural de Recados';
					$_POST['url'] = diretorio($cliente->nome."_".$_POST['titulo']);
					$_POST['texto'] = '';
					$_POST['idtipos_de_paginas'] = 2;
					$_POST['visivel_cliente'] = 'nao';
					$_POST['ordem'] = ++$o;
					$db->inserir('paginas');
					
					
					if($cliente->planos->id ==2){
						$_POST['titulo'] = 'Confirme sua presença';
						$_POST['url'] = diretorio($cliente->nome."_".$_POST['titulo']);
					$_POST['texto'] = '';
						$_POST['idtipos_de_paginas'] = 3;
					$_POST['visivel_cliente'] = 'nao';
					$_POST['ordem'] = ++$o;
						$db->inserir('paginas');
						
						$_POST['titulo'] = 'Tire suas dúvidas';
						$_POST['url'] = diretorio($cliente->nome."_".$_POST['titulo']);
					$_POST['texto'] = '';
						$_POST['idtipos_de_paginas'] = 7;
					$_POST['visivel_cliente'] = 'nao';
					$_POST['ordem'] = ++$o;
						$db->inserir('paginas');
						
						$_POST['titulo'] = 'Fotos do evento';
						$_POST['url'] = diretorio($cliente->nome."_".$_POST['titulo']);
					$_POST['texto'] = '';
						$_POST['idtipos_de_paginas'] = 6;
					$_POST['visivel_cliente'] = 'sim';
					$_POST['ordem'] = ++$o;
						$db->inserir('paginas');
					}
			}
			
		}else{
			$db->editar('clientes',$admin->id);
			$db->salvar_fotos('clientes',$admin->id);
			$db->salvar_arquivos('clientes',$admin->id);
			$db->editarCampo_filhos('clientes',$admin->id);
			
			$cliente =new objetoDb('clientes',$_POST['idclientes']);
			if($db->campoExiste('idclientes','usuarios')){
				$_POST["senha"] = md5($_REQUEST["senha"]);
				$res = $db->fetch('select idusuarios from usuarios where idclientes = "'.$admin->id.'"');
				$db->editar('usuarios',$res['idusuarios']);
					if($cliente->planos->id ==2){
						$dadosMenus = array(1,4,5,100,104,106,107,108);
					}else{
						$dadosMenus = array(1,4,5,100,104,107);
					}
					$dadosConfigs = array(array(2,150));
					$db->tabela_link('usuarios','menus',$res['idusuarios'],$dadosMenus);
					$db->tabela_link('usuarios','configuracoes',$res['idusuarios'],$dadosConfigs);
					
					
					if($cliente->planos->id ==2){
						$_POST['titulo'] = 'Confirme sua presença';
						$db->query('select idpaginas from paginas where idclientes = "'.$cliente->id.'" and url = "'.diretorio($cliente->nome."_".$_POST['titulo']).'"');
						if(!$db->rows){
							$_POST['url'] = diretorio($cliente->nome."_".$_POST['titulo']);
							$_POST['texto'] = '';
							$_POST['idtipos_de_paginas'] = 3;
							$_POST['visivel_cliente'] = 'nao';
							$_POST['ordem'] = ++$o;
							$db->inserir('paginas');
						}
						
						$_POST['titulo'] = 'Tire suas dúvidas';
						$db->query('select idpaginas from paginas where idclientes = "'.$cliente->id.'" and url = "'.diretorio($cliente->nome."_".$_POST['titulo']).'"');
						if(!$db->rows){
							$_POST['url'] = diretorio($cliente->nome."_".$_POST['titulo']);
							$_POST['texto'] = '';
							$_POST['idtipos_de_paginas'] = 7;
							$_POST['visivel_cliente'] = 'nao';
							$_POST['ordem'] = ++$o;
							$db->inserir('paginas');
						}
						
						$_POST['titulo'] = 'Fotos do evento';
						$db->query('select idpaginas from paginas where idclientes = "'.$cliente->id.'" and url = "'.diretorio($cliente->nome."_".$_POST['titulo']).'"');
						if(!$db->rows){
							$_POST['url'] = diretorio($cliente->nome."_".$_POST['titulo']);
							$_POST['texto'] = '';
							$_POST['idtipos_de_paginas'] = 6;
							$_POST['visivel_cliente'] = 'sim';
							$_POST['ordem'] = ++$o;
							$db->inserir('paginas');
						}
					}else{
						$_POST['visivel_cliente'] = 'nao';
						$_POST['status'] = 'nao';
						
						$_POST['titulo'] = 'Confirme sua presença';
						$_idpaginas = $db->fetch('select idpaginas from paginas where idclientes = "'.$cliente->id.'" and url = "'.diretorio($cliente->nome."_".$_POST['titulo']).'"');
						if($db->rows){
							$db->editar('paginas',$_idpaginas);
						}
						
						$_POST['titulo'] = 'Tire suas dúvidas';
						$_idpaginas = $db->fetch('select idpaginas from paginas where idclientes = "'.$cliente->id.'" and url = "'.diretorio($cliente->nome."_".$_POST['titulo']).'"');
						if($db->rows){
							$db->editar('paginas',$_idpaginas);
						}
						
						$_POST['titulo'] = 'Fotos do evento';
						$_idpaginas = $db->fetch('select idpaginas from paginas where idclientes = "'.$cliente->id.'" and url = "'.diretorio($cliente->nome."_".$_POST['titulo']).'"');
						if($db->rows){
							$db->editar('paginas',$_idpaginas);
						}
					}
			}
		}
	}
break;
default:
	if($admin->sub_tg == 'convite'){
		
		$admin->registro = new objetoDb($admin->tabela, $usuario->clientes->id);
		$admin->ini_formulario();
		if($db->campoExiste('idclientes','fotos')){
			$admin->tit_formulario("Convite");
			$admin->campo_fotos();
		}
		$admin->end_formulario();
	}elseif($admin->sub_tg == 'musica'){
		
		$admin->registro = new objetoDb($admin->tabela, $usuario->clientes->id);
		$admin->ini_formulario();
		if($db->campoExiste('idclientes','arquivos')){
			$admin->tit_formulario("Música");
			$admin->campo_arquivo();
		}
		$admin->end_formulario();
	}else{
		if($db->campoExiste('idclientes','usuarios'))
			$admin->campos_listagem = array('Nome' => "nome");
		else
			$admin->campos_listagem = array('Nome' => "nome");
			
		$admin->campos_relatorio = array('Nome' => "nome",'Data de nascimento' => "nascimento",'CPF/CNPJ' => "cpf",'RG/IE' => "rg",'E-mail' => "email",'Login' => "login",'autorizado' => "Autorizado",'Data de cadastro' => "data_cadastro",
										'Telefone' => "telefone",'Celular' => "celular",'Telefone Comercial' => "telefone_comercial",'Endereço' => "url",'Endereço' => "endereco",'Número' => "numero",'Complemento' => "complemento",
										'Título do blog' => "titulo_blog", 'Código de acesso' => "codigo_acesso",'Data de do evento' => "evento",'Data de vencimento dos boletos' => "data_vencimento");
		
		
		if($db->campoExiste('idclientes','usuarios')){
			$admin->listagemLink('listagem','Boletos','boletos@');
			$admin->listagemLink('listagem','Presente','presentes@');
			$admin->listagemLink('listagem','Páginas','paginas@');
			$admin->listagemLink('listagem','Mural','livros@');
			$admin->listagemLink('popup','Rel. Pagamentos','Arquivo/include/relBoletosViario/');
			$admin->listagemLink('popup','Rel. Confirmações','Arquivo/include/relConfirmacoesViario/');
		}else{
			$admin->listagemLink('listagem','Opções de Presente','presentes@noivas@');
		}
		$sql = "select * from clientes";
		
	
		$admin->listagem($sql);
	}
break;
}
?>