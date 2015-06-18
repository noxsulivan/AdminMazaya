<?
$pedido = new objetoDb("pedidos",$admin->id);

if($admin->extra){
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title>Admin &raquo;
<?=str2upper($admin->titulo)?>
<?=str2upper($admin->configs["titulo_site"])?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<style type="text/css">
<!--
<?
echo file_get_contents($_serverRoot.$admin->admin."pdf.css");
//echo preg_replace("/imagens\//i",$_serverRoot.$admin->admin."imagens/",file_get_contents($_serverRoot.$admin->admin."admin_".$site.".css"));
echo file_get_contents($_serverRoot.$admin->admin."admin_".$site.".css");
?>
-->
</style>
</head>
<body>
<div id=topo>
  <h1>ALEXSANDRO TEIXEIRA COUTINHO - ME</h1>
  <h4><?=str2upper(date("d/m/Y H:i:s"))?></h4>
  <h4>CNPJ: 19.789.578/0001-05</h4>
</div>
<div id="titulo">
  <h1>Pedido
    <?=str2upper($pedido->referencia)?>
  </h1>
</div>
<table width="100%" cellpadding="3" cellspacing="3" style="padding:2.5% 5%; width:100%; margin:1.5% 2.5%;">
  <tbody>
    <tr>
      <td width="12%"><strong>Cliente</strong></td>
      <td colspan="7"><?=str2upper($pedido->clientes->nome)?></td>
      <td colspan="2"><strong>Representante</strong></td>
      <td colspan="2"><?=str2upper($pedido->cadastros->nome)?></td>
    </tr>
    <tr>
      <td><strong>CPNJ</strong></td>
      <td colspan="4"><?=str2upper($pedido->clientes->cnpj)?></td>
      <td width="10%" colspan="1"><strong>IE</strong></td>
      <td colspan="3"><?=str2upper($pedido->clientes->inscricao)?></td>
      <td colspan="2"><strong>Data</strong></td>
      <td width="13%"><?=str2upper($pedido->data)?></td>
    </tr>
    <tr>
      <td><strong>Endereço</strong></td>
      <td colspan="4"><?=str2upper($pedido->clientes->endereco)?>, <?=str2upper($pedido->clientes->numero)?></td>
      <td colspan="1"><strong>Bairro</strong></td>
      <td colspan="3"><?=str2upper($pedido->clientes->bairro )?></td>
      <td colspan="2"><strong>Complemento</strong></td>
      <td><?=str2upper($pedido->clientes->complemento)?></td>
    </tr>
    <tr>
      <td><strong>Cidade</strong></td>
      <td colspan="4"><?=str2upper($pedido->clientes->cidades->cidade)?></td>
      <td colspan="1"><strong>CEP</strong></td>
      <td colspan="3"><?=str2upper($pedido->clientes->cep)?></td>
      <td colspan="2"><strong>UF</strong></td>
      <td><?=str2upper($pedido->clientes->estados->nome)?></td>
    </tr>
    <tr>
      <td><strong>Contato</strong></td>
      <td colspan="4"><?=str2upper($pedido->clientes->responsavel)?></td>
      <td colspan="1"><strong>Fone</strong></td>
      <td colspan="6"><?=str2upper($pedido->clientes->telefone)?></td>
    </tr>
    <tr>
      <td colspan="12" class="tdDiv"></td>
    </tr>
    <tr>
      <td><strong>MATERIAL</strong></td>
      <td width="13%"><strong>PRODUTO</strong></td>
      <td width="8%"><strong>PREÇO EX</strong></td>
      <td width="9%"><strong>PREÇO UNIT</strong></td>
      <td width="8%"><strong>TONS</strong></td>
      <td colspan="2"><strong>TOTAL</strong></td>
      <td><strong>FATURA</strong></td>
      <td><strong>ENTREGA</strong></td>
      <td colspan="2"><strong>PRAZO/CONDIÇÃO</strong></td>
      <td><strong>PETROQU&Iacute;MICA</strong></td>
    </tr>
    <? foreach($pedido->itens as $item){ ?>
    <tr>
      <td><?=preg_replace("/CONVENCIONAL/i","CONV.",str2upper($item->produtos->materiais->material))?></td>
      <td><?=str2upper($item->produtos->grade)?></td>
      <td>R$
        <?=str2upper(number_format($item->preco_ex,2,",","."))?></td>
      <td>R$
        <?=str2upper(number_format($item->unitario,2,",","."))?></td>
      <td><?=str2upper($item->tons)?></td>
      <td colspan="2">R$
        <?=str2upper(number_format($item->subtotal,2,",","."))?></td>
      <td><?=($item->data_fatura == '00/00/0000' || $item->data_fatura == '00/00/000' ? "A DEFINIR" : str2upper($item->data_fatura)) ?></td>
      <td><?=($item->data_entrega == '00/00/0000' || $item->data_entrega == '00/00/000' ? "A DEFINIR" : str2upper($item->data_entrega)) ?></td>
      <td colspan="2"><?=str2upper($item->prazo_condicoes)?></td>
      <td><?=str2upper($item->produtos->fornecedores->nome)?></td>
    </tr>
    <? }?>
    <tr>
      <td colspan="2" class="tdClean"></td>
      <td colspan="2"><strong>TOTAL DO PEDIDO</strong></td>
      <td><?=str2upper($pedido->peso)?></td>
      <td colspan="2">R$
        <?=str2upper(number_format($pedido->valor,2,",","."))?></td>
      <td colspan="5" class="tdClean"></td>
    </tr>
    <tr>
      <td colspan="12" class="tdDiv"></td>
    </tr>
    <tr>
      <td><strong>Descarga:</strong></td>
      <td colspan="4"><?=$pedido->clientes->descarga?><?=($pedido->clientes->entrada_carreta == 'sim' ? "" : " (Não dispõe de entrada para carreta)")?></td>
      <td colspan="4"><strong>Horário para descarga:</strong></td>
      <td colspan="3"><?=$pedido->clientes->horario_carga?></td>
    </tr>
    <tr>
      <td><strong>Frete</strong></td>
      <td colspan="4"><?=$pedido->clientes->frete?></td>
      <td colspan="4"><strong>Triangula&ccedil;&atilde;o:</strong></td>
      <td colspan="3"><?=str2upper($pedido->nota_fiscal_triangular)?></td>
    </tr>
    <tr>
      <td><strong>Observações:</strong></td>
      <td colspan="4"><?=$pedido->descricao?></td>
      <td colspan="4"><strong>NF-e:</strong></td>
      <td colspan="3"><?=$pedido->clientes->email_nfe?></td>
    </tr>
    <tr>
      <td colspan="9" rowspan="3" class="tdClean"></td>
      <td colspan="2"><strong>Total do pedido</strong></td>
      <td>R$
        <?=str2upper(number_format($pedido->valor,2,",","."))?></td>
    </tr>
    <tr>
      <td colspan="2"><strong>(+) <?=$pedido->clientes->ipi?>% IPI</strong></td>
      <td>R$
        <?=str2upper(number_format($pedido->ipi,2,",","."))?></td>
    </tr>
    <tr>
      <td colspan="2" class="tdDiv"><strong>Total Geral</strong></td>
      <td class="tdDiv">R$
        <?=str2upper(number_format($pedido->total,2,",","."))?></td>
    </tr>
  </tbody>
</table>
</body>
</html>
<?
$buffer = ob_get_clean();
//die($buffer);
//echo($buffer);
	
pdf($buffer,"Pedido ".diretorio($pedido->referencia)." - ".diretorio($pedido->clientes->fantasia));

}else{
	header("location: ".$admin->localhost."adm/pedidos/imprimirPDF/".$pedido->id."/"."Pedido ".diretorio($pedido->referencia)." - ".diretorio($pedido->clientes->fantasia).".pdf");
}
?>