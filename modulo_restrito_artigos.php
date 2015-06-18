  <? if($pagina->id){ ?>
  <?
									$artigo = new objetoDb('artigos',$pagina->id);
									echo $pagina->caminho(array($canal->canal => $canal->url,"Artigos"=>$pagina->acao."/"));
									?>
  <div class="blocoTexto">
    <h2>
      <?=$artigo->titulo?></h2>
      <h4>
      <?=$artigo->subtitulo?>
      </h4> 
    <h3>Autor:</h3>
    <?=$artigo->profissionais->nome.$artigo->autores?>
	<? if($artigo->curriculos){?>
    <p><?=$artigo->curriculos?></p>
	<? }?>
    <h3>Resumo</h3>
    <?=$artigo->resumo?>
    <h3>Unitermos</h3>
    <?=$artigo->unitermos?>
    <?=$artigo->corpo?>
    <h3>Data de publicação</h3>
    <?=ex_data($artigo->data_publicacao)?>
  </div>
  <div class="blocoFuncoes">
    <ul class="listaFuncoes">
      <li><a class="funcaoForum" href="<?=$pagina->localhost.$canal->url?>Discutir-artigo/<?=$artigo->id?>"> Discutir no Fórum </a></li>
      <li id="marker_<?=$artigo->id?>" class="<?=(in_array($cadastro->id,$artigo->relacoes['cadastros']) ? 'marcado' : 'desmarcado')?>"><a class="funcaoFavorito" href="javascript:adicionarFavorito(<?=$artigo->id?>)">Marcar como favorito </a></li>
      <!-- <li><a class="funcaoSalvar" href="javascript:salvarArtigo(<?=$artigo->id?>)"> Salvar cópia PDF </a></li> -->
      <li><a class="funcaoImprimir" href="javascript:imprimir()"> Imprimir </a></li>
    </ul>
  </div>
  <br clear="all" />
  <div id="galeria"><?
	for($i=0; $i < count($artigo->fotos)	; $i++){ ?><a href="<?=$pagina->localhost?>imagem.php?id=<?=$artigo->fotos[$i]['id']	?>&width=500&height=600" title="<?=$artigo->fotos[$i]['legenda']?>" alt="<?=$artigo->fotos[$i]['legenda']?>" onclick="return hs.expand(this)" class="highslide <?=($j++%3==2) ? 'ultima' : 'img'?>" ><img class="imgThumb" src="<?=$pagina->localhost?>imagem.php?id=<?=$artigo->fotos[$i]['id']	?>&width=221&height=100&force=1" alt="<?=$artigo->fotos[$i]['legenda']?>" border="0"/></a><? }
	?>
	</div>
  <br clear="all" />
  <? }?>
  <?
								$sql = "select * from artigos where artigos.idartigos != '".$artigo->id."' order by data_publicacao desc limit 10";
								$db->query($sql);
								if($db->rows){ ?>
  <div class="blocoAdicionais">
    <h3 class="blocoTitulo titInfo">Leia também:</h3>
    <ul class="listaLinks">
      <? while($res = $db->fetch()){ ?>
      <li><a href="<?=$pagina->localhost.$canal->url.'Artigos/'.$res['url']?>">
        <?=$res['titulo']?>
        </a></li>
      <? }?>
    </ul>
  </div>
  <? }?>