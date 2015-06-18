<?

include('../ini.php');

list( $lixo , $idarquivos , $iddocumentos , $idcondominos) = explode("/",$_SERVER['QUERY_STRING']);
	
if ($idarquivos){

			$_idarquivo = $db->fetch("select idarquivos from arquivos where md5(idarquivos) = '".$idarquivos."'","idarquivos");
			$_iddocumento = $db->fetch("select iddocumentos from documentos where md5(iddocumentos) = '".$iddocumentos."'","iddocumentos");
			$_idcondomino = $db->fetch("select idcondominos from condominos where md5(idcondominos) = '".$idcondominos."'","idcondominos");
			
			$sql = "select iddocumentos_has_visualizacoes from documentos_has_visualizacoes where iddocumentos = '".$_iddocumento."' and  idcondominos = '".$_idcondomino."'";
			
			$res = $db->fetch($sql);
			if(!$res){
				$_POST['idcondominos'] =  $_idcondomino;
				$_POST['iddocumentos'] = $_iddocumento;
				
				$_POST['via'] = "EMAIL";
				
				$_POST['data'] = date("d/m/Y H:i:s");
				$db->inserir('documentos_has_visualizacoes');
			}
			
			if(preg_match('/google\.com\.br/i',$_SERVER['HTTP_REFERER'])){
					header("location: http://".$_SERVER['HTTP_HOST'].'/Produtos/'.$arquivo->produtos->materiais->url.$arquivo->produtos->url);
			}
			
			
			$arquivo = new objetoDb('arquivos',$_idarquivo);
			
			$sql = "update arquivos set downloads = downloads + 1 where idarquivos='".$_idarquivo."'";
			$db->query($sql);
			
				header('Content-type: '.$arquivo->mime_type.'');
				header('Content-Length: '.$arquivo->tamanho);
				//header('Content-Disposition: attachment; filename="'.$arquivo->nome_arquivo.'"');

				//echo gzuncompress(file_get_contents($pagina->root.$res[url]));
				
				echo file_get_contents($arquivo->path);
				
				//$handle = fopen($arquivo->path, "r");
				//while (!feof($handle)) {
				  //echo fread($handle, 8192);
				  //flush();
				//}
				//fclose($handle);
				//echo ($arquivo->path);
				//echo $_SERVER['DOCUMENT_ROOT'].'/'.$arquivo->path;
				
				
}else{
	echo "O arquivo solicitado não existe ou não está disponível no momento";
}
?>