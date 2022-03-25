function carrinhoRemoverProduto( idpedido, idproduto, item ) {
    $('#form-remover-produto input[name="pedido_id"]').val(idpedido);
    $('#form-remover-produto input[name="produto_id"]').val(idproduto);
    $('#form-remover-produto input[name="item"]').val(item);
    $('#form-remover-produto').submit();
}

function carrinhoAdicionarProduto(idproduto, idcliente, vendedor_id) {
    $('#form-adicionar-produto input[name="id"]').val(idproduto);
    $('#form-adicionar-produto input[name="id_cliente"]').val().replace(idcliente);
      $('#form-adicionar-produto input[name="vendedor_id"]').val().replace(vendedor_id);
   // $('#form-adicionar-produto input[name="obs_pedido"]').val().replace(obspedido);
   // $('#form-adicionar-produto input[name="entrega"]').val().replace(frete);
   // $('#form-adicionar-produto input[name="balcao"]').val().replace(retirada);
    $('#form-adicionar-produto').submit();
}


function carrinhoRemoverRequest( idpedido, idrequest, item ) {
    $('#form-remover-produto input[name="pedido_id"]').val(idpedido);
    $('#form-remover-produto input[name="request_cod"]').val(idrequest);
    $('#form-remover-produto input[name="item"]').val(item);
    $('#form-remover-produto').submit();
}

function carrinhoAdicionarRequest( idrequest, idcliente, vendedor_id ) {
    $('#form-adicionar-produto input[name="request_cod"]').val(idrequest);
    $('#form-adicionar-produto input[name="id_cliente"]').val().replace(idcliente);
    $('#form-adicionar-produto input[name="vendedor_id"]').val().replace(vendedor_id);
   // $('#form-adicionar-produto input[name="entrega"]').val().replace(frete);
   // $('#form-adicionar-produto input[name="balcao"]').val().replace(retirada);
    $('#form-adicionar-produto').submit();
}




function carrinhoCancelarPedido( idSpedido) {
    $('#form-cancelar-pedido input[name="id"]').val(idSpedido);   
    $('#form-cancelar-pedido').submit();
}

function carrinhoFinalizarPedido( idSpedido ) {
    $('#form-adicionar-produto input[name="request_cod"]').val(idSpedido);
    $('#form-adicionar-produto input[name="obs_pedido"]').val().replace(obspedido);
    $('#form-adicionar-produto input[name="id_cliente"]').val().replace(idcliente);
   // $('#form-adicionar-produto input[name="entrega"]').val().replace(frete);
   // $('#form-adicionar-produto input[name="balcao"]').val().replace(retirada);
    $('#form-adicionar-produto').submit();
}


function carrinhoItemConsignado( idSpedido ) {
$('#form-pagar-item input[name="id"]').val(idSpedido);   
    $('#form-pagar-item').submit();
 
}