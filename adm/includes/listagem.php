<?

	if(file_exists("lib/".$admin->tg.".php")){
		include("lib/".$admin->tg.".php");
		$ret['titulo'] = $admin->tit();
		$ret['lista'] = $admin->listagem($sql);
		$ret['barra'] = $admin->barra();
	}else{
		if($db->tabelaExiste($admin->tg)){
			include("lib/_geral.php");
			$ret['titulo'] = $admin->tit();
			$ret['lista'] = $admin->listagem($sql);
			$ret['barra'] = $admin->barra();
		}else{
			$ret['titulo']['icone'] = 'text-x-generic.png';
			$ret['titulo']['caption'] = 'O acesso a este mуdulo nгo estб liberado, entre em contato com o suporte Mazaya';
			$ret['titulo']['acao'] = 'Opзгo invбlida. ';
			$ret['barra']['botoes']['deletar']['caption'] = 'Voltar para Home';
			$ret['barra']['botoes']['deletar']['href'] = '#';
			$ret['barra']['botoes']['deletar']['funcao'] = "linkdireto('')";
			$ret['barra']['botoes']['deletar']['imagem'] = 'Info.png';
			$ret['lista']['propriedades']['total'] = 0;
			$ret['lista']['header'] = array();
		}
	}
		array_walk($ret, 'sanitizaRet');
		if($admin->id == "pre"){
			$admin->html = pre($ret,true);
		}else{
			$admin->html = json_encode($ret);
		}
	echo utf8_encode($admin->html);
	$admin->resetHtml();
?>