<? if($cliente->erro){?>

<h2>Ocorreu um erro </h2>
<h3>
  <?=$cliente->erro?>
</h3>
<? } ?>
<? if($cliente and @$cliente->conectado()){ ?>
<h2>Lista de ch� de panela da noiva
  <?=$cliente->nome_noiva?>
  <?=$cliente->sobrenome_noiva?>
  <br />
  Seja bem-vinda</h2>
<div class="content24">
  <h3><a href="<?=$pagina->localhost.$canal->url?>Meus-Dados">Confira seus dados</a></h3>
  <p>No menu <a href="<?=$pagina->localhost.$canal->url?>Meus-Dados">"Meus dados"</a> constam as suas informa��es de cadastro, confira se est�o corretas pois precisaremos entrar em contato com voc�s posteriormente para realizarmos a entrega de seus presentes de ch� de panela adquiridos</p>
  <h3><a href="<?=$pagina->localhost.$canal->url?>Meus-Presentes">Visualize sua lista</a></h3>
  <p>Gerencie os itens que gostaria de ganhar de presente e acompanhe o andamento, sabendo quem comprou online, atrav�s da <a href="<?=$pagina->localhost.$canal->url?>Meus-Presentes">Lista de Presentes</a></p>
</div>
<div class="sidebar24">
  <h3>Adicione �tens em sua lista</h3>
  <p>Para montar sua lista de presentes, navegue agora pelo site e em cada p�gina de produtos, no submenu ao lado da foto, constar� o bot�o "Por em minha lista", onde onde o produto poder� ser adicionado facilmente, clique nele quantas vezes desejar at� atingir o total de unidades desejadas de cada produto.</p>
  <h3><a href="<?=$pagina->localhost.$canal->url?>Convidados">Divulgue para seus convidados</a></h3>
  <p>Ap�s estar tudo ok com sua lista, convide seus amigos para acessar a sua lista e comprar seus presentes com muito mais comodidade em nossa loja virtual. No menu <a href="<?=$pagina->localhost.$canal->url?>Convidados">"Indique aos convidados"</a>, informe os endere�os de e-mail para onde quiser enviar esse lembrete. � rapidinho e estimulante.</p>
</div>
<? }else{?>
<h2>Ch� de Panela</h2>
<?=$canal->texto?>
<div class="formulario">
  <h3>Acesso para Convidados</h3>
  <p>Se voc� chegou at� nossa loja virtual atrav�s da indica��o de uma lista de ch� de panela, selecione a seguir o casal.</p>
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
  <h3>Voc� j� est� conectado a lista de:</h3>
  <h4><?=$convidado->nome_noiva.' '.$convidado->sobrenome_noiva.' &amp; '.$convidado->nome_noivo.' '.$convidado->sobrenome_noivo?></h4>
  <a href="<?=$pagina->localhost.$canal->url?>Presentes" class="awesome grey float-left">Visualize lista de presentes</a>
  <? }?>
</div>
<div class="formularioGrd">
  <h3>J� tem sua lista de ch� de panela?<br />Efetue login:</h3>
  <p>� poss�vel alterar a lista e conferir os resultados. Aproveite</p>
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
  <h4>Voc� pode criar sua lista de presentes atrav�s do site</h4>
  <p>Fa�a seu cadastro e navegue pelo site escolhendo os presentes que gostaria de ganhar, depois, divulgue para seus convidados. � bastante simples em alguns minutos fica pronto.</p>
  <a class="awesome grey" href="<?=$pagina->localhost.$canal->url?>Cadastro"><img src="<?=$pagina->localhost?>_imagens/1271297555_geschenk_box_1.png" width="48" height="48" align="left" style="position:relative; top:-5px; margin-left:-10px;" /> Criar meu cadastro! </a> </div>
<? } ?>
