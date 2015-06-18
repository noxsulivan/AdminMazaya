<? if($cliente->erro){?>

<h2>Ocorreu um erro </h2>
<h3>
  <?=$cliente->erro?>
</h3>
<? } ?>
<? if($cliente and @$cliente->conectado()){ ?>
<h2>Lista de chá de panela da noiva
  <?=$cliente->nome_noiva?>
  <?=$cliente->sobrenome_noiva?>
  <br />
  Seja bem-vinda</h2>
<div class="content24">
  <h3><a href="<?=$pagina->localhost.$canal->url?>Meus-Dados">Confira seus dados</a></h3>
  <p>No menu <a href="<?=$pagina->localhost.$canal->url?>Meus-Dados">"Meus dados"</a> constam as suas informações de cadastro, confira se estão corretas pois precisaremos entrar em contato com vocês posteriormente para realizarmos a entrega de seus presentes de chá de panela adquiridos</p>
  <h3><a href="<?=$pagina->localhost.$canal->url?>Meus-Presentes">Visualize sua lista</a></h3>
  <p>Gerencie os itens que gostaria de ganhar de presente e acompanhe o andamento, sabendo quem comprou online, através da <a href="<?=$pagina->localhost.$canal->url?>Meus-Presentes">Lista de Presentes</a></p>
</div>
<div class="sidebar24">
  <h3>Adicione ítens em sua lista</h3>
  <p>Para montar sua lista de presentes, navegue agora pelo site e em cada página de produtos, no submenu ao lado da foto, constará o botão "Por em minha lista", onde onde o produto poderá ser adicionado facilmente, clique nele quantas vezes desejar até atingir o total de unidades desejadas de cada produto.</p>
  <h3><a href="<?=$pagina->localhost.$canal->url?>Convidados">Divulgue para seus convidados</a></h3>
  <p>Após estar tudo ok com sua lista, convide seus amigos para acessar a sua lista e comprar seus presentes com muito mais comodidade em nossa loja virtual. No menu <a href="<?=$pagina->localhost.$canal->url?>Convidados">"Indique aos convidados"</a>, informe os endereços de e-mail para onde quiser enviar esse lembrete. É rapidinho e estimulante.</p>
</div>
<? }else{?>
<h2>Chá de Panela</h2>
<?=$canal->texto?>
<div class="formulario">
  <h3>Acesso para Convidados</h3>
  <p>Se você chegou até nossa loja virtual através da indicação de uma lista de chá de panela, selecione a seguir o casal.</p>
  <div id="formConvidado">
    <form action="<?=$pagina->localhost.$canal->url.$pagina->acao?>" method="post" id="formConvidadoForm" onsubmit="return sendWindowForm('formConvidado' ,'formConvidadoForm')">
      <input name="acao" id="acao" type="hidden" value="registrarConvidadoCha" />
      <div class="campo">
        <label for="nome_noiva">Nome da noiva</label>
        <select id="nome_noiva" name="nome_noiva">
          <option></option>
          <?
                                                $db->query("select * from clientes  order by nome_noiva");
                                                while($res = $db->fetch()){
                                                    echo '<option value="'.$res['idclientes'].'">'.normaliza($res['nome_noiva'].' '.$res['sobrenome_noiva']).'</option>';
                                                }
                                                ?>
        </select>
      </div>
<?php /*?>      <div class="campo clear"><label> ou</label></div>
      <div class="campo clear">
        <label for="nome_noivo">Nome do noivo</label>
        <select id="nome_noivo" name="nome_noivo">
          <option></option>
          <?
                                                $db->query("select * from clientes order by nome_noivo");
                                                while($res = $db->fetch()){
                                                    echo '<option value="'.$res['idclientes'].'">'.normaliza($res['nome_noivo'].' '.$res['sobrenome_noivo']).'</option>';
                                                }
                                                ?>
        </select>
      </div>
<?php */?>      <div class="campo">
				<label for="">&nbsp;</label>
				<button type="submit" class="awesome orange"><strong>Entrar</strong></button>
      </div>
    </form>
  </div>
  <div class="clear espaco"></div>
  <? if($convidado->id){?>
  <h3>Você já está conectado a lista de:</h3>
  <h4><?=$convidado->nome_noiva.' '.$convidado->sobrenome_noiva.' &amp; '.$convidado->nome_noivo.' '.$convidado->sobrenome_noivo?></h4>
  <a href="<?=$pagina->localhost.$canal->url?>Presentes" class="awesome grey float-left">Visualize lista de presentes</a>
  <? }?>
</div>
<div class="formularioGrd">
  <h3>Já tem sua lista de chá de panela?<br />Efetue login:</h3>
  <p>É possível alterar a lista e conferir os resultados. Aproveite</p>
  <div id="formLogin">
    <form action="<?=$pagina->localhost.$canal->url.$pagina->acao?>" method="post" id="formLoginForm" onsubmit="return sendWindowForm('formLogin' ,'formLoginForm')">
      <input name="acao" type="hidden" value="loginCliente" />
      <div class="campo">
        <label for="_email">E-mail</label>
        <input name="_email" type="text" value="" class="required inputField inputMedio" />
      </div>
      <div class="campo">
        <label for="_senha">Senha</label>
        <input name="_senha" type="password" value="" class="required inputField inputMedio" />
      </div>
      <div class="campo">
				<label for="">&nbsp;</label>
				<button type="submit" class="awesome orange"><strong>Continuar</strong></button>
      </div>
      <div class="campo clear">
				<label for="">&nbsp;</label>
				<a href="<?=$pagina->localhost.$canal->url?>Lembrar-senha" class="awesome grey"><strong>Esqueci minha senha</strong></a>
      </div>
    </form>
  </div>
  <div class="clear"></div>
  <h3>Crie sua lista de presentes</h3>
  <h4>Você pode criar sua lista de presentes através do site</h4>
  <p>Faça seu cadastro e navegue pelo site escolhendo os presentes que gostaria de ganhar, depois, divulgue para seus convidados. É bastante simples em alguns minutos fica pronto.</p>
  <a class="awesome grey" href="<?=$pagina->localhost.$canal->url?>Cadastro"><img src="<?=$pagina->localhost?>_imagens/1271297555_geschenk_box_1.png" width="48" height="48" align="left" style="position:relative; top:-5px; margin-left:-10px;" /> Criar meu cadastro! </a> </div>
<? } ?>
