            
            <div class="clear espaco"></div>
<?php /*?>            <h3>* Promo��o de Frete Gr�tis</h3>
                        <ul>
							<li><a href="http://www.mesacor.com.br/Frete_Gratis/">compras acima de R$<?=$pagina->configs['limite_frete_gratis']?>,00 para os estados de: RJ, SP, PR e RS</a></li>
							<li><a href="http://www.mesacor.com.br/Frete_Gratis/">todos os produtos da linha Tramontina Design Collection para todo o Brasil</a></li>
							<li><a href="http://www.mesacor.com.br/Frete_Gratis/">para todos os pedidos com destino em SANTA CATARINA</a></li>
                          
                        </ul>
<?php */?>
<?php /*?>            <h3>Desconto v�lido exceto para:</h3>
            	<? $db->query("select idcategorias, categoria from categorias where desconto_vista = 0") ?>
                        <ul>
                          <? while($res = $db->fetch()){?>
                          <li><?=$res['categoria']?></li>
                          <? }?>
                          <li>Alguns produtos das linha TEEC (eletro).</li>
                        </ul>
<?php */?>                
            <h3>Importante:</h3>
                        <ul>
                          <li>Produtos selecionados como presentes para noivas, n�o acrescentar�o valor ao frete pois ser�o entregues em separado, conforme combinado.</li>
                        </ul>
            <h3>Instru��es:</h3>
            <ul>
              <li>Para retirar um item de seu Carrinho clique em Excluir.</li>
              <li>Para alterar a quantidade de um item, digite o n�mero no campo e clique no bot�o "Alterar" para calcular o novo valor da compra.</li>
              <li>Para finalizar a compra � necess�rio estar cadastrado e fazer login.</li>
              <li>�tens com valor sob consulta n�o constar�o no pedido final.</li>
            </ul>
