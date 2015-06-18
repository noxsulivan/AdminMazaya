<?

//error_reporting(E_ALL);


$timeIni = microtime(true);
$_tmp = explode("/",$_SERVER['REQUEST_URI']);

$includeIni = $_SERVER['DOCUMENT_ROOT'].'/ini.php';	
include($includeIni);

define ('COOKIE_NAME', diretorio($_SERVER['SCRIPT_FILENAME']));

function limpaPost(&$v,$k){
	if(is_array($v))
		array_walk($v,"limpaPost");
	else
		$v = utf8_decode($v);
}
array_walk($_POST,"limpaPost");

$admin = new admin($_SERVER["QUERY_STRING"]);

	if($admin->view == 'condominos_view_morador'){
		$id = $usuario->condominos->id;
		$admin = new admin("condominos_view_morador/editar/".$id);
	}
$usuario = new usuario();

		if($admin->tg == "condominos" and $admin->acao == "editar" and $admin->id == "")
			$admin->id = $usuario->condominos->id;
		if($admin->tg == "usuarios" and $admin->acao == "editar" and $admin->id == "")
			$admin->id = $usuario->id;
			
extract($_POST);

ob_start();
	switch($admin->tg){
		case "Sair":
			$usuario->sair();
			die();
		break;
		case "Nucleo":
			switch($admin->acao){
				case "enviarPreCadastro":
					include($include."includes/_clientes/enviarPreCadastro.email.php");
				break;
				case "TrocarSenha":
					echo $usuario->trocarSenha($_POST["senha"],$_POST["senha_nova"],$_POST["denovo"]);
					die();
				break;
				case "Logar":
					if($usuario->conectado())
						echo "ok";
					else{
						echo utf8_encode($usuario->erro);
					}
					die();
				break;
			}
		break;
	}
	
	if($usuario->conectado()){
		switch($admin->acao){
			case "novo":
			case "editar":
			case "abrir":
			case "salvar":
			case "inserir":
			case "inserirAfiliado":
			case "alterar":
			case "alterarAfiliado":
			case "prepararMailing":
			case "publicar":
			case "senha":
				include($include."includes/formulario.php");
			break;
			case "include":
				include($include."includes/arquivo.php");
			break;
			case "exportar":
				include($include."includes/exportar.php");
			break;
			case "imprimirRelatorio":
				include($include."includes/imprimirRelatorio.php");
			break;
			case "imprimirPDF":
				include($include."includes/_clientes/".$admin->tg.".pdf.php");
			break;
			case "enviarEmail":
				include($include."includes/_clientes/".$admin->tg.".email.php");
			break;
			case "enviarSenhaCadastro":
				include($include."includes/_clientes/".$admin->tg.".enviarSenhaCadastro.php");
			break;
			case "enviarAviso":
				include($include."includes/_clientes/".$admin->tg.".email.php");
			break;
			case "enviarApresentacao":
				include($include."includes/_clientes/".$admin->tg.".apresentacao.php");
			break;
			case "enviarDocumento":
				include($include."includes/_clientes/".$admin->tg.".documento.php");
			break;
			case "atualizarBoleto":
				include($include."includes/_clientes/".$admin->tg.".atualizar.php");
			break;
			case "imagem":
				include($include."includes/imagem.php");
			break;
			case "extras":
			case "uploadFotos":
			case "giraImagem":
			case "colherDados":
			case "atualizarLegenda":
			case "uploadArquivo":
			case "uploadCamera":
			case "ordenar":
			case "remover":
			case "removerGrupo":
			case "atualizarDinamico":
			case "atualizar":
			case "atualizarAtributos":
			case "adicionarCampo_filho":
			case "adicionarCampo_faixasValores":
			case "inserirCidade":
			case "carregaCEP":
			case "carregaCidades":
			case "carregaBairros":
			case "carregarOptions":
			case "carregaOptions":
			case "carregaLista":
			case "autocomplete":
			case "autoComplete":
			case "autoCompleteTags":
				include("includes/extras.php");
			break;
			case "buscar":
				$admin->termoBusca = $admin->id;
			case "listar":
			case "importar":
				include($include."includes/listagem.php");
			break;
			case "capa":
			default:
				include($include."includes/corpo.php");
			break;
			
		}
	}else{
		include($include."includes/corpo.php");
	}
//pre($db);
$timeEnd = microtime(true);
header('Content-Length: '.ob_get_length());
header('X-Database-Objects-Resources: '.count($db->resources));
header('X-Database-Objects-Createds: '.count($_SESSION['objetos']));
header('X-Server-Elapsed-Time: '.round($timeEnd-$timeIni,2));
header('X-Server-Memory-Usage: '.round(memory_get_usage()/1024)."kb");
header('X-Usuario: '.$_SESSION["usuario"]->id);
//header('X-Server-Memory-Peak-Usage: '.round(memory_get_peak_usage()/1024));

ob_end_flush();
?>