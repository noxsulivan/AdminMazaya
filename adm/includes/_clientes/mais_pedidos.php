<?
$ret = '<table width="100%">';
pre($obj->itens);
//    foreach($obj->itens as $item_pedido){
//    $ret .= '<tr>';
//      $ret .= '<td>'.preg_replace("/CONVENCIONAL/i","CONV.",str2upper($item_pedido->produtos->materiais->material)).'</td>';
//      $ret .= '<td>'.strtoupper($item_pedido->produtos->grade).'</td>';
//      $ret .= '<td>R$'.strtoupper(number_format($item_pedido->preco_ex,2,",",".")).'</td>';
//      $ret .= '<td>R$'.strtoupper(number_format($item_pedido->unitario,2,",",".")).'</td>';
//      $ret .= '<td>'.strtoupper($item_pedido->tons).'</td>';
//      $ret .= '<td>R$'.strtoupper(number_format($item_pedido->subtotal,2,",",".")).'</td>';
//      $ret .= '<td>'.($item_pedido->data_entrega == '00/00/0000' || $item_pedido->data_entrega == '00/00/000' ? "A DEFINIR" : str2upper($item_pedido->data_entrega)).'</td>';
//      $ret .= '<td>'.strtoupper($item_pedido->prazo_condicoes).'</td>';
//      $ret .= '<td>'.strtoupper($item_pedido->produtos->fornecedores->nome).'</td>';
//    $ret .= '</tr>';
//    }
$ret .= '</table>';

$mais[$j] = $ret;
?>