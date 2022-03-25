function carrinhointerRemoverProduto( idpedido, idproduto, item ) {
    $('#form-remover-produto input[name="pedido_id"]').val(idpedido);
    $('#form-remover-produto input[name="produto_id"]').val(idproduto);
    $('#form-remover-produto input[name="item"]').val(item);
    $('#form-remover-produto').submit();
}

function carrinhointerAdicionarProduto( idproduto, obspedido ) {
    $('#form-adicionar-produto input[name="id"]').val(idproduto);
    $('#form-adicionar-produto input[name="obs_pedido"]').val().replace(obspedido);

   // $('#form-adicionar-produto input[name="entrega"]').val().replace(frete);
   // $('#form-adicionar-produto input[name="balcao"]').val().replace(retirada);
    $('#form-adicionar-produto').submit();
}


function carrinhointerRemoverRequest( idpedido, idrequest, item ) {
    $('#form-remover-produto input[name="pedido_id"]').val(idpedido);
    $('#form-remover-produto input[name="request_cod"]').val(idrequest);
    $('#form-remover-produto input[name="item"]').val(item);
    $('#form-remover-produto').submit();
}

function carrinhointerAdicionarRequest( idrequest, obspedido, idcliente ) {
    $('#form-adicionar-produto input[name="request_cod"]').val(idrequest);
    $('#form-adicionar-produto input[name="obs_pedido"]').val().replace(obspedido);
    $('#form-adicionar-produto input[name="id_cliente"]').val().replace(idcliente);
   // $('#form-adicionar-produto input[name="entrega"]').val().replace(frete);
   // $('#form-adicionar-produto input[name="balcao"]').val().replace(retirada);
    $('#form-adicionar-produto').submit();
}




function carrinhointerCancelarPedido( idSpedido) {
    $('#form-cancelar-pedido input[name="id"]').val(idSpedido);   
    $('#form-cancelar-pedido').submit();
}

function carrinhointerFinalizarPedido( idSpedido ) {
    $('#form-adicionar-produto input[name="request_cod"]').val(idSpedido);
    $('#form-adicionar-produto input[name="obs_pedido"]').val().replace(obspedido);
    $('#form-adicionar-produto input[name="id_cliente"]').val().replace(idcliente);
   // $('#form-adicionar-produto input[name="entrega"]').val().replace(frete);
   // $('#form-adicionar-produto input[name="balcao"]').val().replace(retirada);
    $('#form-adicionar-produto').submit();
}
