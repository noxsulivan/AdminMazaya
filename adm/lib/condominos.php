<?
switch($admin->acao){
case "editar": case "abrir":

//die(pre($admin->id));
		
//die(pre($formulario->registro));

	
case "novo";
	
	
	
	  $formulario->fieldset('Identificação');
			$formulario->fieldset->simples('Nome completo', 'nome');
			$formulario->fieldset->simples('Quadra', 'idquadras');
			$formulario->fieldset->simples('Lote', 'lote');
			$formulario->fieldset->simples('Relação condominial', 'idtipos_de_condominos');
			$formulario->fieldset->simples('Situação do lote', 'idstatus_de_lotes');
			$formulario->fieldset->simples('Em obra', 'obra');
			$formulario->fieldset->simples('Data de nascimento', 'nascimento');
			$formulario->fieldset->simples('CPF', 'cpf');
			$formulario->fieldset->simples('RG', 'rg');
			$formulario->fieldset->simples('Razão Social', 'razao_social');
			$formulario->fieldset->simples('Nome Fantasia', 'nome_fantasia');
			$formulario->fieldset->simples('Pessoa de contato', 'contato');
			$formulario->fieldset->simples('CNPJ', 'cnpj');
			$formulario->fieldset->simples('Inscrição Estadual', 'inscricao');
			$formulario->fieldset->simples('Telefone', 'telefone');
			$formulario->fieldset->simples('Celular', 'celular');
			$formulario->fieldset->simples('Telefone 2', 'telefone_comercial');
			$formulario->fieldset->simples('Site', 'site');
			$formulario->fieldset->simples('E-mail', 'email');
		
	  
	  
	  $formulario->fieldset('Observações');
	  	$formulario->fieldset->simples('Observações', 'obs');
		
	  
	  
	  
	  $formulario->fieldset('Endereço para correspondência');
	  	$formulario->fieldset->localidade();
	  	$formulario->fieldset->simples('Logradouro', 'endereco');
	  	$formulario->fieldset->simples('Bairro', 'bairro');
	  	$formulario->fieldset->simples('Número', 'numero');
	  	$formulario->fieldset->simples('Complemento', 'complemento');
	  $formulario->fieldset('Foto');
	  	$formulario->fieldset->fotos();
		
	if($usuario->tipos_de_usuarios->id == 1){
	  $formulario->fieldset('Status no site');
	  	$formulario->fieldset->simples('Tipo', 'tipos_de_condominos');
	  	$formulario->fieldset->simples('Login', 'login');
	  	$formulario->fieldset->simples('Senha', 'senha');
	  	$formulario->fieldset->simples('Autorizado', 'autorizado');
	  	$formulario->fieldset->simples('Data de cadastro', 'data_cadastro');
		$formulario->fieldset('Recursos utilizados');
	  	$formulario->fieldset->simples('Internet', 'internet');	  
	}
		
		
	$formulario->fieldset('Membros da Família',false,true);
	$formulario->fieldset->filhos('dependentes');
	
	
	$formulario->fieldset('Veículos',false,true);
		$formulario->fieldset->filhos('veiculos');

	
	
	
break;
case "inserir";
break;
case "salvar":
	if($_POST['idcanais'] == 1) $_POST['idcanais_2'] = '0';
	
	if($admin->id == ''){
		$_POST["url"] = diretorio(trim($_REQUEST["nome"].' '.$_REQUEST["sobrenome"]));
		
		list($login) = explode("@",$_POST["email"]);
		$_POST["senha"] = md5($_REQUEST["senha"]);
		$_POST["login"] = $login;
		$db->inserir('condominos');
		$db->filhos('condominos',$inserted_id);
		$_id = $db->inserted_id;
		$db->salvar_fotos('condominos',$_id);
		$_POST['idcondominos'] = $_id;
		$_POST['idtipos_de_usuarios'] = $usuario->tipos_de_usuarios->id + 1;
		$db->inserir('usuarios');
		
		
	}else{
		$_POST["url"] = diretorio(trim($_REQUEST["nome"].' '.$_REQUEST["sobrenome"]));

		$admin->registro = new objetoDb($admin->tabela, $admin->id);
		if($admin->registro->senha != $_POST["senha"])
			$_POST["senha"] = md5($_REQUEST["senha"]);
		$db->editar('condominos',$admin->id);
		$db->filhos('condominos',$admin->id);
		$db->salvar_fotos('condominos',$admin->id);
		$_id = $db->fetch("select idusuarios from usuarios where idcondominos = '".$admin->id."'","idusuarios");
		
		
		//$db->editar('usuarios',$_id);
		if($usuario->condominos->id == $admin->id){
			$admin->acao = 'editar';
			$admin->funcao = "formulario";
		}
		
	}
break;
default:

		$admin->campos_listagem = array('Quadra' => "quadras->quadra",'Lote' => "lote",'Nome' => "nome",'Situação' => "status_de_lotes->status",'Telefone'=>'telefone','Ramal'=>'ramal','Email' => "email",'Internet' => "internet",'Interfone' => "interfone",'Senha enviada' => "enviado");//,'Representante' => "representantes->nome");
	
		//if(in_array( $usuario->tipos_de_usuarios->id, array(1))){
			//$admin->listagemLink('enviarSenhaCadastro','<img src="imagens/buttons/mail_send.png" align="absmiddle"> Senha','');
			$admin->listagemLink('enviarLote','<img src="imagens/buttons/mail_send.png" align="absmiddle"> Senha','');
		//}
		
						$admin->ordenavel = true;
				$admin->ordenar = "idquadras, lote";
				$admin->extra = "ASC";

break;
}
?>