<h2>Envio de arquivo</h2>
<? switch($pagina->id){
	case "Salvar":
															
						$_POST["data"] = ex_data(date("Y-m-d h:i:s"));
						$_POST["data_abertura"] = ex_data(date("Y-m-d h:i:s"));
						$_POST["data_fechamento"] = ex_data(date("Y-m-d h:i:s"));
						$_POST["idcadastros"] = $cadastro->id;
						$_POST["sentido"] = 1;
						
						$db->inserir('arquivos');
						
						$corpo .= '
						<p>O cliente <strong>'.$cadastro->dados['nome'].'</strong> enviou o arquivo <strong>'.$_POST['nome_arquivo'].'</strong></p>
						<p>Para fazer o download do arquivo, acesse a área administrativa do site.</p>
						<p>Este e-mail foi enviado através do site '.$pagina->localhost.'</strong><br>
						Data e hora: <strong>'.date("d/m/Y h:i:s").'</strong><br>
						IP: <strong>'.$_SERVER['REMOTE_ADDR'].'</strong>
						</p>';
				
						n2_mail($pagina->configs["email_suporte"],"Novo arquivo enviado através da área-restrita",$corpo,$cadastro->dados['email'],$_FILES);															
						
						if($db->inserted_id){ ?>
						<h2>O arquivo
						  <?=$_POST['']?>
						  foi inserido com sucesso</h2>
						<? }else{ ?>
						<h2>
						  <?=$db->erro;?>
						</h2>
						<? }?>
<? break;?>
<? default:?>
						<div id="formulario">
						  <form action="<?=$pagina->localhost.$canal->url?>Enviar-arquivo/Salvar" method="post" id="formularioForm">
							<div id="formEsq">
							  <label for="senhaAtual">Título</label>
							  <input id="arquivo" name="arquivo" type="text" value="" class="inputField" />
							  <button id="btnBrowse" type="button" class="submitButton" id="submitButton" onclick="uploadArquivo.selectFile()"> <img src="<?=$pagina->localhost?>_admin/imagens/add.png" style="padding-right: 3px; vertical-align: bottom;">Selecionar arquivo </button><br />

							  <div id="divFileProgressContainer"></div>
							  <div id="thumbnails"></div>
							  <label for="mensagem">Descrição</label>
							  <textarea id="descricao" name="descricao" class="inputField"></textarea>
							  <button type="submit" type="submit" class="submitButton" id="submitButton">
							  Enviar
							  </button>
							</div>
						  </form>
						</div>
<? }?>