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
*/


//:::: Autenticação
/*Route::get('/user/login', function () {
    return redirect('/login');
});*/


/*Route::get('/login', ['uses' => 'Controller@fazerLogin']);

Route::post('/login', ['as' => 'user.login','uses' => 'DashboardController@auth']);

Route::get('/dashboard', ['as' => 'user.dashboard','uses' => 'DashboardController@index']);

Route::resource('user','UsersController');

Route::get('user', ['as' => 'user.index','uses' => 'UsersController@index']);

Route::get('user', ['as' => 'user.index','uses' => 'UsersController@index']);

Route::resource('product','ProdutoController');
Route::get('controller', 'AdministratorController@index');


Route::get('/cadastro', ['uses' => 'Controller@cadastrar']);

Route::get('/', ['uses' => 'Controller@homepage']);

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::resource('product','ProdutoController');

*/

Route::group(['middleware' => 'web'], function (){
//Auth::routes();

/*Route::get('/home', 'HomeController@index');

Route::get('/show-user', 'HomeController@show');*/
Route::get('/', function(){
	return view('templates.dashboard');

});


});

Route::group(['middleware' => 'admin'], function (){

	Route::auth();
	Route::group(['middleware' => 'auth:admin'], function(){
	//Adminnistradores
	Route::get('/admin', 'AdminController@index');	
	Route::get('/admin/create', 'AdminController@createAdmin');

	//Pedidos
	Route::get('/admin/pedido/create', 'AdminController@createPedido');

	//clientes
	Route::get('/admin/clientes', 'AdminController@clientes');//No plural por se tratar de uma lista de clientes"
	Route::get('/admin/cliente/create', 'AdminController@createCliente');		
	

	//Vendedores
	Route::get('/admin/vendedores', 'AdminController@vendedores');//No plural por se tratar de uma lista de clientes"
	Route::get('/admin/vendedor/create', 'AdminController@createVendedor');

	//Produtos
	Route::resource('product','ProdutoController');

	Route::get('/home', 'HomeController@index');
	Route::resource('register', 'RegisterController');
	Route::resource('cliente','ClienteController');


});
	

	Route::get('/admin/login', 'AdminController@login');
	Route::post('/admin/login', 'AdminController@postLogin');
	Route::get('/admin/logout', 'AdminController@logout');

});


Route::group(['middleware' => 'vendedor'], function (){
Route::auth();

	Route::group(['middleware' => 'auth:vendedor'], function(){
	Route::get('/vendedor', 'VendedorController@index');
});

	Route::get('/vendedor/login', 'VendedorController@login');
	Route::post('/vendedor/login', 'VendedorController@postLogin');
	Route::get('/vendedor/logout', 'VendedorController@logout');

});

Route::group(['middleware' => 'cliente'], function (){
Route::auth();
	Route::group(['middleware' => 'auth:cliente'], function(){
	Route::get('/cliente', 'ClienteController@index');
});

	Route::get('/cliente/login', 'ClienteController@login');
	Route::post('/cliente/login', 'ClienteController@postLogin');
	Route::get('/cliente/logout', 'ClienteController@logout');

});


