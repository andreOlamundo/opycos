<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
/*===============================================================
/*===============================================================
/*
/*
/*
================================================================
*RESOURCE CLIENTE AUTH (AUTENTICAÇÃO) MIDDLEWARE => 'WEB'
*
*/
Route::group(['middleware' => 'web'], function (){

/*Route::get('/', function(){
	return view('templates.dashboard');

});*/


Route::get('/', function(){
	return view('auth.login-adm');

});



Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});

Route::any('/clientes/whatsapp/{id}','ClienteController@indexWhats')->name('indexWhatsapp'); //createWhatsapp
//Route::any('/clientes/whatsapp/{id}','ClienteInterController@indexWhats')->name('indexInterWhatsapp'); //createWhatsapp
Route::post('cliente/Auth', 'ClienteController@updateAuth')->name('alter.cliente');
//Route::post('cliente/Auth', 'ClienteInterController@updateAuth')->name('alterInter.cliente');

Route::any('search-cliente','ClienteController@searchClienteWhats')->name('search-cliente');
//Route::post('searchInter-cliente','ClienteInterController@searchClienteWhats')->name('search-cliente');
});


/*===============================================================
/*===============================================================
/*
/*
/*
================================================================
*RESOURCE ADMINISTRADOR MIDDLEWARE => 'ADMINISTRATOR'
*
*/

Route::group(['middleware' => 'admin'], function (){
Route::auth();
Route::group(['middleware' => 'auth:admin'], function(){
	/*
================================================================
*RESOURCE ADMINISTRADOR => 'ADMINISTRATOR'
*
*/
Route::get('/admin', 'PedidoController@compras');	
Route::get('/all-pedidos', 'PedidoController@admin')->name('all.pedidos');
Route::resource('admins', 'AdminController');

Route::get('/CadastroAdmin', 'AdminController@listCadastro')->name('listCadastro');

Route::any('/users','AdminController@users')->name('new.users');
Route::any('/users/list','AdminController@usersList')->name('list.users');
Route::any('admins-search','AdminController@searchAdmin')->name('Admin.search');
	/*
================================================================
*RESOURCE PEDIDOS => 'ADMINISTRATOR'
*
*/

//Route::get('/produto/{id}', 'HomeController@produto')->name('produto');
Route::get('/', 'PedidoController@index')->name('index');
Route::any('/carrinhoadm/adicionar', function() {
    return redirect()->route('index');
});

Route::get('/consignado', 'PedidoController@indexConsig')->name('index.consignado');
Route::any('/carrinhoadm/adicionar', function() {
    return redirect()->route('index.consignado');
});

Route::get('/pedidos/{id}/edit', 'PedidoController@edit')->name('pedidos/{id}/edit');
Route::any('/pedidos/edit/adicionar/{id}', function() {
    return redirect()->route('pedidos/{id}/edit');
});

Route::get('pedidos/{id}/consig/edit', 'PedidoController@editConsignado')->name('pedidos/{id}/consig/edit');
Route::get('pedidos/{id}/consig/relatorio', 'PedidoController@editConsignadoRelatorio')->name('pedidos/{id}/consig/relatorio');
Route::any('/pedidos/edit/consig/add/{id}', function() {
    return redirect()->route('pedidos/{id}/consig/edit');
});

Route::any('pdf/{id}', 'PedidoController@pedidoPdf')->name('pdf');
Route::any('pdf/consignado/{id}', 'PedidoController@pedidoConsigPdf')->name('pdf.consignado');

Route::any('pdf/comissao/{id}', 'PedidoController@comissaoPdf')->name('pdf.comissao');

Route::any('pdf/relatorio/{id}', 'PedidoController@relatorioPdf')->name('pdf.relatorio');

Route::any('/desconto/pedido/{id}', 'PedidoController@descontoPedido')->name('desconto.pedido');
Route::any('/desconto/pedido/consig/{id}', 'PedidoController@descontoPedidoConsig')->name('desconto.pedido.consig');

Route::any('/pedidos/edit/adicionar/{id}', 'PedidoController@adicionarEdit')->name('carrinho.adicionarEdit');

Route::any('/pedidos/consig/edit/add/{id}', 'PedidoController@adicionarEditConsig')->name('carrinho.adicionarConsigEdit');

Route::any('/pedidos/edit/remover/{id}', 'PedidoController@removerEdit')->name('carrinho.removerEdit');

Route::any('/pedidos/consig/edit/remover/{id}', 'PedidoController@removerEditConsig')->name('carrinho.removerConsigEdit');

//Route::any('/pedidos/consig/edit/remover/{id}', 'PedidoController@removerEditConsig')->name('carrinho.removerConsig');

Route::any('/info/pedido/{id}', 'PedidoController@info')->name('pedido.info');

Route::any('/info/pedido/consig/{id}', 'PedidoController@infoConsig')->name('pedido.infoConsig');

Route::any('/pedidos/edit/status/{id}', 'PedidoController@statusEdit')->name('alter.statusEdit');

Route::any('/pedidos/consig/edit/status/{id}', 'PedidoController@statusEditConsig')->name('alter.statusEditConsig');


Route::any('/carrinhoadm/adicionar', 'PedidoController@adicionar')->name('carrinho.adicionar');

Route::any('/carrinhoadm/Consig/adicionar', 'PedidoController@adicionarConsig')->name('carrinho.adicionarConsig');

Route::any('/carrinhoadm/remover', 'PedidoController@remover')->name('carrinho.remover');

Route::any('/carrinhoadm/Consig/remover', 'PedidoController@removerConsig')->name('carrinho.removerConsig');

Route::post('pedidoadm/concluir', 'PedidoController@concluir')->name('pedido.concluir');

Route::get('/pedidoadm/compras', 'PedidoController@compras')->name('pedido.compras');
Route::get('/pedidoadm/itens-vendidos', 'PedidoController@itensPedidos')->name('itens.pedidos');
Route::get('/pedidoadm/consignado/', 'PedidoController@consignado')->name('pedido.consignado');


/*
================================================================
COMISSÕES
*
*/

Route::get('/pedidoadm/comissoes', 'PedidoController@comissoes')->name('pedido.comissoes');

Route::get('/pedidoadm/allcomissoes', 'PedidoController@allcomissoes')->name('pedido.allcomissoes');

Route::any('/pagar/comissao/{id}', 'PedidoController@pagarComissao')->name('pagar/comissao/{id}');

Route::post('pagar.comissoes', 'PedidoController@pagarComissoes')->name('pagar.comissoes');

Route::post('/calcular/comissao/{id}', 'PedidoController@calcularComissao')->name('calcular.comissao');

Route::post('/concluir/comissao/{id}', 'PedidoController@concluirComissao')->name('concluir.comissao');

Route::any('comissoes-search','PedidoController@searchComissao')->name('Comissoes.search');


/*
================================================================
END
*
*/

Route::any('/carrinhoadm/cancelar', 'PedidoController@cancelar')->name('carrinho.cancelar');

Route::any('/carrinhoadm/finalizar/{id}', 'PedidoController@finalizar')->name('carrinho.finalizar');

Route::get('/pedido/allcompras', 'PedidoController@allcompras')->name('pedido.allcompras');

Route::get('/findVendId','PedidoController@findVendId'); 

Route::get('/findVendC','PedidoController@findVendC');

Route::get('/findVendName','PedidoController@findVendName');

Route::get('/infoFrete', 'PedidoController@infoFrete');

Route::get('/infoFretePrazoEntrega', 'PedidoController@infoFretePrazoEntrega');



Route::get('/pedidosAdmin','PedidoController@admin')->name('pedidosAdmin.admin');
Route::resource('pedidos', 'PedidoController');
Route::any('/pedidos/alter/{id}', 'PedidoController@updateFrete')->name('pedidos.updateFrete');

Route::any('/pedidos/detalhes/{id}', 'PedidoController@detalhes')->name('pedidos.detalhes');

Route::any('/pedidos/consig/detalhes/{id}', 'PedidoController@detalhesConsig')->name('pedidos.detalhesConsig');

Route::any('/pedidos/detalhesEdit/{id}', 'PedidoController@detalhesEdit')->name('pedidos.detalhesEdit');

Route::any('/pedidos/detalhesEdit/Consif/{id}', 'PedidoController@detalhesEditConsig')->name('pedidos.detalhesEditConsig');



//Route::get('addProduct', function() {
//	return redirect()->route('pedidos.index');
//});

//Route::post('addProduct','PedidoController@addProduct')->name('pedidos.addProduct');

//Route::get('/findProductCod','PedidoController@findProductCod');
//Route::get('/findProductName','PedidoController@findProductName');
//Route::any('list-product-search','ItensPedidoController@searchProdutoPedido')->name('ProdutoPedido.search');
Route::any('pedido-search','PedidoController@searchPedido')->name('Pedido.search');
Route::any('itens-search','PedidoController@searchItens')->name('Itens.search');
Route::any('pedido-consignado','PedidoController@searchConsignado')->name('PedidoConsig.search');

	/*
================================================================
*RESOURCE CLIENTES => 'ADMINISTRATOR'
*
*/
Route::get('/admin/clientes', 'AdminController@clientes');//No plural por se tratar de uma lista de clientes"
Route::resource('clientes','ClienteController');
Route::any('cliente/{id}','ClienteController@status')->name('cliente.status');

Route::any('cliente-search','ClienteController@searchCliente')->name('Cliente.search');

Route::any('clientes/{id}/edit','ClienteController@edit')->name('clientes/{id}/edit');

	/*
================================================================
*RESOURCE CLIENTES (LINK WHATSAPP) => 'ADMINISTRATOR'
*
*/
//Route::any('/link/whats', 'ClienteController@linkWhats')->name('cliente.linkWhats');
Route::any('/link/whatsapp', 'ClienteController@linkWhatsapp')->name('cliente.linkWhatsapp');
Route::post('preview/cadastro/whats', 'ClienteController@previewCadastroWhats')->name('cadastro.preview');
Route::post('/new/link', 'ClienteController@concluirLinkWhats')->name('concluir.LinkWhats');
Route::any('/whatsapplist', 'ClienteController@whatsapplist')->name('whatsapp.index');
Route::get('/findCodRegistro','ClienteController@findCodRegistro');
	
	/*
================================================================
*RESOURCE VENDEDORES  => 'ADMINISTRATOR'
*
*/

Route::resource('vendedores','VendedorController');
Route::any('vendedor-search','VendedorController@searchVendedor')->name('Vendedor.search');
Route::any('vendedor/{id}','VendedorController@status')->name('vendedor.status');
Route::any('vendedor/edit/{id}','VendedorController@edit')->name('vendedores/{id}/edit');

	/*
================================================================
*RESOURCE PRODUTOS  => 'ADMINISTRATOR'
*
*/
Route::resource('product','ProdutoController');	
Route::resource('categoria','GroupController');
Route::any('categoria-search','GroupController@searchCategoria')->name('Categoria.search');
Route::any('product-search','ProdutoController@searchProduto')->name('Produto.search');

	/*
================================================================
*RESOURCE REQUEST  => 'ADMINISTRATOR'
*
*/

Route::resource('requestopycos','RequestOpycosController');	
Route::any('/requestopycosCancelar/{id}','RequestOpycosController@cancelar')->name('requestopycos.cancelar');
Route::any('request-search','RequestOpycosController@searchRequest')->name('Request.search');
Route::get('/findRequisitionsName','RequestOpycosController@findRequisitionsName');


	/*

		/*
================================================================
*RESOURCE Codigo de Registro  => 'ADMINISTRATOR'
*
*/

//Route::get('/findCodReg','CodRegController@findRegistro');


	/*
================================================================
*TESTES  => 'ADMINISTRATOR'
*
*/
	//Route::resource('register', 'RegisterController');

	//Route::get('/home', 'HomeController@index');
});

/*
================================================================
*RESOURCE LOGIN  => 'ADMINISTRATOR'
*
*/

	Route::get('/admin/login', 'AdminController@login');
	Route::post('/admin/login', 'AdminController@postLogin');
	Route::get('/admin/logout', 'AdminController@logout');

});


/*===============================================================
/*===============================================================
/*
/*
/*
================================================================
*RESOURCE VENDEDOR MIDDLEWARE => 'VENDEDOR'
*
*/

Route::group(['middleware' => 'vendedor'], function (){
	Route::auth();

	Route::group(['middleware' => 'auth:vendedor'], function(){
	
	Route::get('/vendedor', 'PedidoInterController@compras');

	Route::get('/listCadastro', 'VendedorController@listCadastro')->name('listCadastroVend');	
	/*
	=============================================================
	*RESOURCE CLIENTE
	*
	*/
	Route::resource('clientesinter','ClienteInterController');	
	Route::any('clientesinter-search','ClienteInterController@searchCliente')->name('ClienteInter.search');
	Route::any('clientesinter/{id}/edit','ClienteInterController@edit')->name('clientesinter/{id}/edit');

	/*
	=============================================================
	* LINK WHATS
	*
	*/

Route::any('/linkInter/whatsapp/', 'ClienteInterController@linkWhatsapp')->name('cliente.linkInterWhatsapp');
Route::post('preview/cadastro', 'ClienteInterController@previewCadastroWhats')->name('cadastroInter.preview');
Route::post('/newInter/link', 'ClienteInterController@concluirLinkWhats')->name('concluirI.Link.Whats');
Route::any('/whatsIlist', 'ClienteInterController@whatsapplist')->name('whatsappInter.index');


	/*
	=============================================================
	*RESOURCE PRODUCT
	*
	*/
	Route::resource('productinter','ProdutoInterController');	
	Route::any('productinter-search','ProdutoInterController@searchProduto')->name('ProdutoInter.search');

	/*
	==============================================================
	*RESOURCE PEDIDO ORIGINAL
	*
	*/
/*Route::get('/index', 'PedidoInterController@index')->name('indexint');
Route::get('/carrinho/adicionar', function() {
    return redirect()->route('indexint');
});
Route::post('/carrinho/adicionar', 'PedidoInterController@adicionar')->name('carrinhointer.adicionar');
Route::delete('/carrinho/remover', 'PedidoInterController@remover')->name('carrinhointer.remover');
Route::post('pedido/concluir', 'PedidoInterController@concluir')->name('pedidointer.concluir');
Route::get('/pedido/compras', 'PedidoInterController@compras')->name('pedidointer.compras');
Route::post('/carrinho/cancelar', 'PedidoInterController@cancelar')->name('carrinhointer.cancelar');
	});
	Route::get('/admin/login', 'AdminController@login')->name('cliente.primeiroacesso');
	Route::get('/admin/login', 'AdminController@login');
	Route::post('/admin/login', 'AdminController@postLogin');
	Route::get('/admin/logout', 'AdminController@logout');
});*/

	/*
	==============================================================
	*RESOURCE PEDIDO APRIMORADO
	*
	*/



Route::get('/indexint', 'PedidoInterController@index')->name('indexint');
Route::any('/carrinhointer/adicionar', function() {
    return redirect()->route('indexint');
});

Route::get('/pedidosint/{id}/edit', 'PedidoInterController@edit')->name('pedidosint/{id}/edit');
Route::any('/pedidosint/edit/adicionar/{id}', function() {
    return redirect()->route('pedidosint/{id}/edit');
});

Route::any('pdfint/{id}', 'PedidoInterController@pedidoPdf')->name('pdfint');

Route::any('/pedidosint/edit/adicionar/{id}', 'PedidoInterController@adicionarEdit')->name('carrinhoint.adicionarEdit');

Route::any('/pedidosint/edit/remover/{id}', 'PedidoInterController@removerEdit')->name('carrinhoint.removerEdit');

Route::any('/info/pedidosint/{id}', 'PedidoInterController@info')->name('pedidosint.info');



Route::any('/carrinhointer/adicionar', 'PedidoInterController@adicionar')->name('carrinhointer.adicionar');

Route::any('/carrinhointer/remover', 'PedidoInterController@remover')->name('carrinhointer.remover');

Route::post('pedidoint/concluir', 'PedidoInterController@concluir')->name('pedidointer.concluir');

Route::get('/pedidoint/compras', 'PedidoInterController@compras')->name('pedidointer.compras');

Route::any('/carrinhointer/cancelar', 'PedidoInterController@cancelar')->name('carrinhointer.cancelar');

Route::any('/carrinhointer/finalizar', 'PedidoInterController@finalizar')->name('carrinhointer.finalizar');

//Route::get('/pedidoint/allcompras', 'PedidoInterController@allcompras')->name('pedido.allcompras');

//Route::get('/pedidosAdmin','PedidoInterController@admin')->name('pedidosAdmin.admin');
Route::resource('pedidosint', 'PedidoInterController');
Route::any('/pedidosint/alter/{id}', 'PedidoInterController@updateFrete')->name('pedidosint.updateFrete');

Route::any('/pedidosint/detalhes/{id}', 'PedidoInterController@detalhes')->name('pedidosint.detalhes');
Route::any('/pedidosint/detalhesEdit/{id}', 'PedidoInterController@detalhesEdit')->name('pedidosint.detalhesEdit');

Route::any('pedidoint-search','PedidoInterController@searchPedido')->name('Pedidoint.search');

Route::get('/findRequisitionsNameInt','RequestOpycosController@findRequisitionsNameInt');

Route::get('/infoFreteInt', 'PedidoInterController@infoFrete');

Route::get('/infoFretePrazoEntregaInt', 'PedidoInterController@infoFretePrazoEntrega');

Route::any('/pedidosint/edit/status/{id}', 'PedidoInterController@statusEdit')->name('alterint.statusEdit');


	/*
================================================================
*RESOURCE Comissoes  => 'VENDEDOR'
*
*/

Route::get('/pedidoint/comissoes', 'PedidoInterController@comissoes')->name('pedidoint.comissoes');

Route::get('/pedidoint/allcomissoes', 'PedidoInterController@allcomissoes')->name('pedidoint.allcomissoes');

Route::any('comissoesint-search','PedidoInterController@searchComissao')->name('Comissoesint.search');





	/*
================================================================
*RESOURCE REQUEST  => 'VENDEDOR'
*
*/


Route::resource('requestopycosint','RequestOpycosInterController');	
Route::any('/requestopycosintCancelar/{id}','RequestOpycosInterController@cancelar')->name('requestopycosint.cancelar');
Route::any('requestint-search','RequestOpycosInterController@searchRequest')->name('Requestint.search');

});

	

	});

/*===============================================================
/*===============================================================
/*
/*
/*
================================================================
*RESOURCE VENDEDOR MIDDLEWARE => 'CLIENTE'
*
*/


Route::group(['middleware' => 'cliente'], function (){
	Route::auth();
	Route::group(['middleware' => 'auth:cliente'], function(){	
	Route::get('/cliente', 'ClienteController@clienteindex');	
	Route::get('/cliente', 'ClienteController@clienteindex')->name('clienteindex');
	Route::get('/pedido/clientes', 'PedidoController@clientecompras')->name('pedido.clientecompras');
	Route::get('/contato/{vendedor_id}', 'ClienteController@vendedores')->name('contato.vendedores');


});


	
	//Route::any('/cliente/autentic', 'ClienteController@login')->name('cliente.primeiroacesso');

		
	Route::get('/admin/login', 'AdminController@login');

	//Route::post('/cliente/login', 'ClienteControllerAuth@postLogin');
	//Route::get('/cliente/logout', 'ClienteControllerAuth@logout');
	Route::post('/cliente/login', 'AdminController@postLogin');
	Route::get('/cliente/logout', 'AdminController@logout');

});


