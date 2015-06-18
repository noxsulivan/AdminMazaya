<?
include('../ini.php');

$db->indices_da_tabela("fotos");
$indices = $db->tabelas['fotos']['Indexes'];


$dir = $_SERVER['DOCUMENT_ROOT']."/_imagem";

//if(is_dir($dir)){
//	  foreach(glob($dir . '/*') as $file) { 
//		if(is_file($file))
//			unlink($file); 
//		
//		pre($file);
//	  }
//}else{
//	mkdir($dir);
//}


//die();


//if(!in_array("tabela",$indices)){
//	$db->query("
//	ALTER TABLE  `fotos`
//		ADD  `tabela` VARCHAR( 100 ) NOT NULL AFTER  `idfotos` ,
//		ADD  `id` INT UNSIGNED NOT NULL AFTER  `tabela` ,
//		ADD  `path` VARCHAR( 255 ) NOT NULL AFTER  `id`,
//	ADD INDEX (  `tabela` ,  `id` )");
//
//	$db->query("ALTER TABLE `fotos` DROP `exif`");
//}

	$db->query('select * from fotos limit 10000');
	
			  
	pre($db->rows);flush();

	while($res = $db->fetch()){
			
			$cache =  $dir."/".time()."_".diretorio(str_replace("-",".",$res['nome_arquivo']));
			
			$fp = fopen($cache, 'w');		
			fwrite($fp, $res['arquivo']);
			fclose($fp);
			
			//pre(exif_imagetype ($cache ));
			
			foreach($indices as $indice){
				if($res[$indice] > 0 and $indice!='idfotos' and $indice!='tabela' and $indice!='id'){
					$tabela = ereg_replace("/id/i","",$indice);
					$id = $res[$indice];
					pre($sql = "update fotos set path = '".$cache."' where idfotos = '".$res['idfotos']. "'");
					$db->query($sql);
					flush();
				}
			}
			
				
	}
?>