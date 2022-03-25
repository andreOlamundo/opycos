<?php

namespace App\Http\Controllers;

use App\Entities\Pedido;
use App\Entities\Produto;
use App\Entities\OpycosRequest;
use App\Entities\Comissao;
use App\Entities\Frete;
use App\Entities\Cliente;
use App\Entities\Vendedor;
use Illuminate\Support\Facades\Validator;
//use App\Entities\GroupProducts;
use App\Entities\ItensPedido;
//use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use PDF;
//use Exception;
use DB;

class PedidoController extends Controller
{

public function __construct()
{
$this->middleware('auth');
}

public function index() {

$registros = Produto::where([
'ativo' => 's'
])->get();

$list_requisitions= OpycosRequest::where('status','=','FI')->get();

$pedidos = Pedido::where([
'status' => 'GE',
'user_id' => Auth::id()
])->get();

$pedidossearch = Pedido::where([
'status' => 'GE',
'user_id' => Auth::id()
])->pluck('id');

$e_pedido = Pedido::where([
'status' => 'GE',
'user_id' => Auth::id()
])->first();

$pedidos_produto = Pedido::where([
'status' => 'GE',
'user_id' => Auth::id()
])->where( 'produto_id', '!=', NULL)->get();


$pedidos_request=Pedido::select('id','id_cliente', 'request_id','status')->where('request_id', '!=', NULL)->where(['status' => 'GE',  'user_id' => Auth::id()])->take(100)->get();


$dadosClientes= Cliente::where('status', '!=', 'A')->get();

$dadosVendedores= Vendedor::where('status', '=', 'active')->get();

$retiradaBalcPF = Frete::where([
'status' => 'AR',
'balcao' => 'Y',
'boolean' => 'Y',
'user_id' => Auth::id()
])->get();

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->get();

$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');

$produtossearchP  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->get();

$requestsearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'R')->get();

$desconto_request = $requestsearch->sum('request_desconto');

$soma_request  = $requestsearch->sum('prod_preco_padrao');

$conte = ($produtossearchP)->count();

$conteR = ($requestsearch)->count();



if(isset($e_pedido))
{
$produtossearchc  = ItensPedido::select('comissao', 'prod_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'P')->get();

$requestsearchc  = ItensPedido::select('comissao', 'request_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'R')->get();

$p_comissao = $produtossearchc->pluck('comissao');

$v_produto = $produtossearchc->pluck('prod_preco_padrao');
$d_produto = $produtossearchc->pluck('prod_desconto');
if(empty($requestsearchc))
{
	$d_request = 0;
	$v_request = 0;
	$r_comissao = 0;
}
else
{
$d_request = $requestsearchc->pluck('request_desconto');
$v_request = $requestsearchc->pluck('prod_preco_padrao');
$r_comissao = $requestsearchc->pluck('comissao');	
}


$itenspedidoP = ItensPedido::where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'P')->first();

$itenspedidoR = ItensPedido::where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'R')->first();

}

else
{
$p_comissao = 0;
$v_produto = 0;
$v_request = 0;
$d_produto = 0;
$d_request = 0;
$v_request = 0;
$r_comissao = 0;
$itenspedidoP = 0;
$itenspedidoR = 0;
}

$requisitions = ItensPedido::where([
'status' => 'GE', //Gerado
'tipo' => 'R'
])->get();

$pedidos_id = Pedido::where([
'status' => 'GE',
'user_id' => Auth::id()
])->where( 'produto_id', '!=', NULL)->pluck('id');


$freteB_PF = Frete::where([
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'B',
'boolean' => 'Y'
])->get();


$freteC_PF = Frete::where([
'user_id' => Auth::id(),
'status' => 'C',
'entrega' => 'C',
'boolean' => 'Y'
])->get();


$valorFrete = DB::table('fretes')->select('valor')->where([
// 'user_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'Y'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
//'user_id' => Auth::id(),
'status' => 'C',
'boolean' => 'Y'
])->get();


return view('admin.pedidoResource.index', compact('registros', 'pedidos', 'dadosClientes', 'retiradaBalcPF', 'freteB_PF', 'freteC_PF', 'valorFrete','valorFreteC', 'list_requisitions', 'requisitions', 'pedidos_request', 'pedidos_produto', 'dadosVendedores', 'desconto_produtos', 'conte', 'conteR', 'soma_produtos', 'p_comissao', 'v_produto', 'itenspedidoP', 'itenspedidoR','d_produto', 'v_request', 'desconto_request', 'soma_request', 'd_request', 'v_request', 'r_comissao'));


}



public function indexConsig() {


$registros = Produto::where([
'ativo' => 's'
])->get();


$list_requisitions= OpycosRequest::where('status','=','FI')->get();



$pedidos = Pedido::where([
'status' => 'GE',
'user_id' => Auth::id()
])->get();

/* $pedidos_request = Pedido::where([
'request_cod' => '>', 0,
'user_id' => Auth::id(),
'status' => 'GE'

])->get();*/

$pedidos_produto = Pedido::where([
'status' => 'GE',
'user_id' => Auth::id()
])->where( 'produto_id', '!=', NULL)->get();


$pedidos_request=Pedido::select('id','id_cliente', 'request_id','status')->where('request_id', '!=', NULL)->where(['status' => 'GE',  'user_id' => Auth::id()])->take(100)->get();


$dadosClientes= Cliente::where('status', '!=', 'A')->get();

$dadosVendedores= Vendedor::where('status', '=', 'active')->get();


$retiradaBalcPF = Frete::where([
'status' => 'AR',
'balcao' => 'Y',
'boolean' => 'Y',
'user_id' => Auth::id()
])->get();


/*$pedidosRequest = Pedido::where([
'request_cod' => 'AR',
'request_valor' => 'Y',
'req_desc' => 'Y',
'user_id' => Auth::id()
])->get();*/


$requisitions = ItensPedido::where([
'status' => 'GE', //Gerado
'tipo' => 'R',
//    'user_id' => Auth::id()
])->get();

$pedidos_id = Pedido::where([
'status' => 'GE',
'user_id' => Auth::id()
])->where( 'produto_id', '!=', NULL)->pluck('id');

/*  $produtos = ItensPedido::where([
'status' => 'GE', //Gerado
'tipo' => 'P',
'pedido_id' => $pedidos_id
//   'user_id' => Auth::id()
])->orderBy('id', 'desc')->get();*/





// dd($retirada);

$freteB_PF = Frete::where([
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'B',
'boolean' => 'Y'
])->get();




$freteC_PF = Frete::where([
'user_id' => Auth::id(),
'status' => 'C',
'entrega' => 'C',
'boolean' => 'Y'
])->get();



$valorFrete = DB::table('fretes')->select('valor')->where([
// 'user_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'Y'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
//'user_id' => Auth::id(),
'status' => 'C',
'boolean' => 'Y'
])->get();


// $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();


/*dd([
$pedidos,
$pedidos[0]->itens_pedido,
$pedidos[0]->itens_pedido[0]->product
]);*/

// $total = Pedido::all()->count();
return view('admin.pedidoResource.index-consignado', compact('registros', 'pedidos', 'dadosClientes', 'retiradaBalcPF', 'freteB_PF', 'freteC_PF', 'valorFrete','valorFreteC', 'list_requisitions', 'requisitions', 'pedidos_request', 'pedidos_produto', 'dadosVendedores'));
}





public function admin() {

$dadosClientes=DB::table('clientes')->get();

$dadosVendedores=DB::table('vendedores')->get();

$dadosPedidos=DB::table('pedidos')->get();

$ano = NULL;


$periodo = NULL;

$pedidossearch = Pedido::select('id')->where('consignado', '=', 'N')->
where('status', '=', 'FI')->pluck('id');

$compras = Pedido::where([
'status'  => 'GE',
'user_id' => Auth::id()
])->take(100)->get();

$totalPageSearch = ($compras)->count();

//$produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
//$produtos  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$produtos  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
		->orderBy('quantidade', 'desc')->get();



//$desconto_produtos = DB::table('itens_pedidos')->select('prod_desconto')->get();
$desconto_produtos  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$desconto_request = DB::table('itens_pedidos')->select('request_desconto')->get();
$desconto_request  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$total_desconto_prod = $desconto_produtos->sum('prod_desconto');

$total_desconto_req = $desconto_request->sum('request_desconto');

$desconto = $total_desconto_prod + $total_desconto_req;


$soma_produtos = $produtos->sum('prod_preco_padrao');

//$frete = DB::table('fretes')->select('valor')->get();
$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();

$frete_total = $frete->sum('valor');

$geral = $soma_produtos + $frete_total - $desconto;
//$geral = $soma_produtos - $desconto;

$total_preco = $geral;

// $pedidos = Pedido::where('id', '!=', NULL)->orderBy('id', 'desc')->paginate(7);
$pedidos = Pedido::where('status', '!=', 'GE')->where('consignado', '=', 'N')->get();
//$pedidos = Pedido::all();

//  $dadosClientes= Cliente::where('status', '!=', 'A')->get();

// dd($retirada);
$valorFrete = DB::table('fretes')->select('valor')->where([
//  'user_id' => Auth::id(),
'status' => 'EMB',
'entrega'   => 'B',
'boolean' => 'Y'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
//  'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'Y'
])->get();

/*$cancelados = Pedido::where([
'status'  => 'CA',
'user_id' => Auth::id()
])->orderBy('updated_at', 'desc')->get();*/

$totaladm = Pedido::where([
//  'status'  => 'CA',
'user_id' => Auth::id()
])->get();

//  $total = Pedido::all()->count();
$total = ($totaladm)->count();

return view('admin.pedidoResource.compras', compact('compras',
'dadosPedidos',
'valorFrete',
'valorFreteC',
'pedidos',
'total',
'produtos',
'dadosClientes',
'dadosVendedores',
'totalPageSearch',
'total_preco', 'ano', 'periodo', 'frete_total', 'soma_produtos', 'desconto'));

}






public function adicionar()
{

$this->middleware('VerifyCsrfToken');
$req = Request();

$validator = validator($req->all(),
[
'id_cliente' => 'required'
]);

if ( $validator->fails()){

$req->session()->flash('mensagem-falha', 'É preciso escolher um cliente.');
return redirect()->route('index')->withInput();
}



$idcliente = $req->input('id_cliente');
$obspedido = $req->input('obs_pedido');
$tip = $req->input('boolean');
$idvendedor = $req->input('vendedor_id');
$comissao = $req->input('comissao');




$idproduto = $req->input('id'); //Produto
$idrequest = $req->input('request_cod');  //Requisição



if (($idrequest == NULL) && ($idproduto == NULL))
{

$req->session()->flash('mensagem-falha', 'Escolha algum item.');
return redirect()->route('index')->withInput();

}

if (isset($idrequest) && isset($idproduto))
{

$req->session()->flash('mensagem-falha', 'Escolha um item por vez');
return redirect()->route('index')->withInput();

}


$desconto_percent_prod = $req->input('desconto_produto'); //Desconto Produto %
$desconto_percent_prod = str_replace( '%', '', $desconto_percent_prod);
if ($desconto_percent_prod > 100)
{
$req->session()->flash('mensagem-falha', 'Não é Permitido desconto acima de 100%!');
return redirect()->route('index')->withInput();
}



$desconto_percent_req = $req->input('desconto_request');  //Desconto Requisição %
$desconto_percent_req = str_replace( '%', '', $desconto_percent_req);
if ($desconto_percent_req > 100)
{
$req->session()->flash('mensagem-falha', 'Não é Permitido desconto acima de 100%!');
return redirect()->route('index')->withInput();
}



$quantidade_prod =  $req->input('quantidade_produto');//Qtd Produto %
$quantidade_req =  $req->input('quantidade_request');//Qtd Requisição %





if ($idrequest == NULL)
{

$produto = Produto::find($idproduto);//Query ID Produto


if(empty($produto->id)) {
$req->session()->flash('mensagem-falha', 'Produto não pode ser Localizado');
return redirect()->route('index');
}

$desconto_reais_prod = $req->input('desconto_produto_reais');  //Desconto Produto R$
$desconto_reais_prod = str_replace( ',', '.', $desconto_reais_prod );



 
/*if ($produto->prod_preco_padrao <= $desconto_reais_prod)
{
$req->session()->flash('mensagem-falha', 'Desconto não pode possuir valor de desconto maior ao igual o valor do produto');
return redirect()->route('index');
}*/



$preco = $produto->prod_preco_padrao;
if ($desconto_percent_prod > 0)
{
$desconto_produtos = ($preco * $desconto_percent_prod)/100;
$desconto_produtos = number_format($desconto_produtos, 2, '.', '');	
//dd($desconto_produtos);
}


elseif ($desconto_reais_prod > 0)
{
$desconto_produtos = $desconto_reais_prod;
//dd($desconto_produtos);
}
//dd($desconto_reais_prod);

elseif (($desconto_percent_prod == "") && ($desconto_reais_prod == ""))
{
//dd($desconto_reais_prod);	
$desconto_produtos = '0.00';

} else {

$desconto_produtos = '0.00';
	
}


if( $preco <= $desconto_produtos ) {
$req->session()->flash('mensagem-falha', 'Erro!. Valor do desconto igual ou superior ao valor do Produto!');
return redirect()->route('index')->withInput();
}


$idusuario = Auth::id();
$idpedido = Pedido::consultaId([
'user_id' => $idusuario,
'status'  => 'GE' // Reservado
]);

$pencent_desconto = ($desconto_produtos/$preco)*100;

if( empty($idpedido) ) {
$pedido_novo = Pedido::create([
'user_id' => $idusuario,
'vendedor_id' => $idvendedor,
'produto_id' => $idproduto,
'obs_pedido' => $obspedido,
'id_cliente' => $idcliente,
//'percentual_comissao' => $comissao,
'consignado' => 'N',
'status'  => 'GE' // Gerado
]);

$idpedido = $pedido_novo->id;

}

Pedido::where([
'id' => $idpedido,
'user_id' => $idusuario
])->update([
'obs_pedido' => $obspedido,
'produto_id' => $idproduto,
'vendedor_id'  => $idvendedor,

]);

if (isset($quantidade_prod))
{

if ($pencent_desconto > 15)
{

$comissao = $comissao / 2;

$contador = 0;

while($contador < $quantidade_prod)
{

ItensPedido::create([
'pedido_id'  => $idpedido,
'produto_id' => $idproduto,
'tipo' => 'P',
'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $produto->prod_preco_padrao,
'prod_preco_prof' => $produto->prod_preco_prof,
'prod_desconto' => $desconto_produtos,
'comissao' => $comissao,
'status'     => 'GE'

]);
$contador++;
}



$req->session()->flash('mensagem-sucesso', 'Produto adicionado!');

return redirect()->route('index');

}

else
{

$contador = 0;

while($contador < $quantidade_prod)
{

ItensPedido::create([
'pedido_id'  => $idpedido,
'produto_id' => $idproduto,
'tipo' => 'P',
'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $produto->prod_preco_padrao,
'prod_preco_prof' => $produto->prod_preco_prof,
'prod_desconto' => $desconto_produtos,
'comissao' => $comissao,
'status'     => 'GE'

]);
$contador++;
}

$req->session()->flash('mensagem-sucesso', 'Produto adicionado!');

return redirect()->route('index');
}

} else {

	if ($pencent_desconto > 15)
{

$comissao = $comissao / 2;

//dd($comissao);

ItensPedido::create([
'pedido_id'  => $idpedido,
'produto_id' => $idproduto,
'tipo' => 'P',
'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $produto->prod_preco_padrao,
'prod_preco_prof' => $produto->prod_preco_prof,
'prod_desconto' => $desconto_produtos,
'comissao' => $comissao,
'status'     => 'GE'

]);

$req->session()->flash('mensagem-sucesso', 'Produto adicionado!');

return redirect()->route('index');

}

else

{

ItensPedido::create([
'pedido_id'  => $idpedido,
'produto_id' => $idproduto,
'tipo' => 'P',
'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $produto->prod_preco_padrao,
'prod_preco_prof' => $produto->prod_preco_prof,
'prod_desconto' => $desconto_produtos,
'comissao' => $comissao,
'status'     => 'GE'

]);

$req->session()->flash('mensagem-sucesso', 'Produto adicionado!');

return redirect()->route('index');

}


}


}

if ($idproduto == NULL)
{

$desconto_reais_req = $req->input('desconto_request_reais');  //Desconto Produto R$
$desconto_reais_req = str_replace( ',', '.', $desconto_reais_req );

$request = OpycosRequest::find($idrequest);//Query ID Requisição

if( empty($request->id)) {
$req->session()->flash('mensagem-falha', 'Requisição não pode ser Localizada');
return redirect()->route('index');
}

$preco = $request->request_valor;

if ($desconto_percent_req > 0)
{
$desconto_request = ($preco * $desconto_percent_req)/100;
$desconto_request = number_format($desconto_request, 2, '.', '');
}

elseif ($desconto_reais_req > 0)
{
$desconto_request = $desconto_reais_req;
}

elseif (($desconto_percent_req == "") && ($desconto_reais_req == ""))
{
$desconto_request = '0.00';
} else {

$desconto_request = '0.00';
	
}


if( $preco <= $desconto_request ) {
$req->session()->flash('mensagem-falha', 'Valor do desconto igual ou superior ao valor da Requisição!');
return redirect()->route('index')->withInput();
}

$check_request = OpycosRequest::where([
'id' => $idrequest,
'id_cliente' => $idcliente
])->exists();

if( !$check_request) {

$req->session()->flash('mensagem-falha', 'Requisição não pertence ao cliente');
return redirect()->route('index')->withInput();

}

$idusuario = Auth::id();
$idpedido = Pedido::consultaId([
'user_id' => $idusuario,
'status'  => 'GE' // Reservado
]);

if( empty($idpedido) ) {
$pedido_novo = Pedido::create([
'user_id' => $idusuario,
'vendedor_id' => $idvendedor,
'obs_pedido' => $obspedido,
'id_cliente' => $idcliente,
'request_id' => $request->id,
'request_valor' => $request->request_valor,
'request_desc' => $request->req_desc,
//'percentual_comissao' => $comissao,
'consignado' => 'N',
'status'  => 'GE' // Gerado
]);

$idpedido = $pedido_novo->id;

}


Pedido::where([
'id' => $idpedido,
'user_id' => $idusuario
])->update([
'obs_pedido' => $obspedido,
'request_id' => $idrequest
]);

OpycosRequest::where([
'id' => $idrequest
])->update([
'status' => 'RE' // Reservado no pedido
]);

if (isset($quantidade_req))  {

$contador = 0;

while($contador < $quantidade_req)
{
ItensPedido::create([
'pedido_id'  => $idpedido,
'request_id' => $idrequest,
'tipo' => 'R',
'prod_preco_padrao' => $request->request_valor,
'request_desconto' => $desconto_request,
'status'     => 'GE',
'comissao' => $comissao
]);

$contador++;
}



$req->session()->flash('mensagem-sucesso', 'Requisição adicionada!');

return redirect()->route('index');

} else {

ItensPedido::create([
'pedido_id'  => $idpedido,
'request_id' => $idrequest,
'tipo' => 'R',
'prod_preco_padrao' => $request->request_valor,
'request_desconto' => $desconto_request,
'status'     => 'GE',
'comissao' => $comissao

]);

$req->session()->flash('mensagem-sucesso', 'Requisição adicionada!');

return redirect()->route('index');


}


}

}


public function adicionarConsig()
{

$this->middleware('VerifyCsrfToken');
$req = Request();

/*$validator = validator($req->all(),
[
'id_cliente' => 'required'
]);

if ( $validator->fails()){

$req->session()->flash('mensagem-falha', 'É preciso escolher um cliente.');
return redirect()->route('index')->withInput();
}*/

$obspedido = $req->input('obs_pedido');
$tip = $req->input('boolean');
$idvendedor = $req->input('vendedor_id');

$idproduto = $req->input('id'); //Produto
//$idrequest = $req->input('request_cod');  //Requisição
$comissao = $req->input('comissao');



/*if (($idrequest == NULL) && ($idproduto == NULL))
{

$req->session()->flash('mensagem-falha', 'Escolha algum item.');
return redirect()->route('index')->withInput();

}*/

/*if (isset($idrequest) && isset($idproduto))
{

$req->session()->flash('mensagem-falha', 'Escolha um item por vez');
return redirect()->route('index')->withInput();

}*/

$desconto_percent_prod = $req->input('desconto_produto'); //Desconto Produto %
$desconto_percent_prod = str_replace( '%', '', $desconto_percent_prod);
if ($desconto_percent_prod > 100)
{
$req->session()->flash('mensagem-falha', 'Não é Permitido desconto acima de 100%!');
return redirect()->route('index.consignado')->withInput();
}



/*$desconto_percent_req = $req->input('desconto_request');  //Desconto Requisição %
$desconto_percent_req = str_replace( '%', '', $desconto_percent_req);
if ($desconto_percent_req > 100)
{
$req->session()->flash('mensagem-falha', 'Não é Permitido desconto acima de 100%!');
return redirect()->route('index')->withInput();
}*/

$desconto_reais_prod = $req->input('desconto_produto_reais');  //Desconto Produto R$
$desconto_reais_prod = str_replace( ',', '.', $desconto_reais_prod );



$quantidade_prod =  $req->input('quantidade_produto');//Qtd Produto %
//$quantidade_req =  $req->input('quantidade_request');//Qtd Requisição %





/*if ($desconto_percent_prod == NULL)
{
$desconto_percent_prod = '0';
}*/

$produto = Produto::find($idproduto);//Query ID Produto

if( empty($produto->id)) {
$req->session()->flash('mensagem-falha', 'Produto não pode ser Localizado');
return redirect()->route('index.consignado');
}

$preco = $produto->prod_preco_padrao;

if ($desconto_percent_prod > 0)
{
$desconto_produtos = ($preco * $desconto_percent_prod)/100;
$desconto_produtos = number_format($desconto_produtos, 2, '.', '');	
//dd($desconto_produtos);
}


elseif ($desconto_reais_prod > 0)
{
$desconto_produtos = $desconto_reais_prod;
//dd($desconto_produtos);
}
//dd($desconto_reais_prod);

elseif (($desconto_percent_prod == "") && ($desconto_reais_prod == ""))
{
//dd($desconto_reais_prod);	
$desconto_produtos = '0.00';

} else
{
$desconto_produtos = '0.00';
}


if( $preco <= $desconto_produtos ) {
$req->session()->flash('mensagem-falha', 'Erro! Valor do desconto igual ou superior ao valor do Produto!');
return redirect()->route('index.consignado');
}



$idusuario = Auth::id();
$idpedido = Pedido::consultaId([
'user_id' => $idusuario,
'status'  => 'GE' // Reservado
]);

if( empty($idpedido) ) {
$pedido_novo = Pedido::create([
'user_id' => $idusuario,
'vendedor_id' => $idvendedor,
'produto_id' => $idproduto,
'percentual_comissao' => $comissao,
'obs_pedido' => $obspedido,
'consignado' => 'S',
//'id_cliente' => $idcliente,
'status'  => 'GE' // Gerado
]);

$idpedido = $pedido_novo->id;

}

Pedido::where([
'id' => $idpedido,
'user_id' => $idusuario
])->update([
'obs_pedido' => $obspedido,
'produto_id' => $idproduto,
'vendedor_id'  => $idvendedor

]);

if (isset($quantidade_prod))
{

$contador = 0;

while($contador < $quantidade_prod)
{

ItensPedido::create([
'pedido_id'  => $idpedido,
'produto_id' => $idproduto,
'tipo' => 'P',
'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $produto->prod_preco_padrao,
'prod_preco_prof' => $produto->prod_preco_prof,
'prod_desconto' => $desconto_produtos,
'status'     => 'GE'

]);
$contador++;
}



$req->session()->flash('mensagem-sucesso', 'Produto adicionado!');

return redirect()->route('index.consignado');

} else {

ItensPedido::create([
'pedido_id'  => $idpedido,
'produto_id' => $idproduto,
'tipo' => 'P',
'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $produto->prod_preco_padrao,
'prod_preco_prof' => $produto->prod_preco_prof,
'prod_desconto' => $desconto_produtos,
'status'     => 'GE'

]);

$req->session()->flash('mensagem-sucesso', 'Produto adicionado!');

return redirect()->route('index.consignado');


}







/*if ($idproduto == NULL)
{

if ($desconto_percent_req == NULL)
{
$desconto_percent_req = '0';
}

$request = OpycosRequest::find($idrequest);//Query ID Requisição

if( empty($request->id)) {
$req->session()->flash('mensagem-falha', 'Requisição não pode ser Localizada');
return redirect()->route('index');
}

$preco = $request->request_valor;
$desconto_request = ($preco * $desconto_percent_req)/100;
number_format($desconto_request, 2, ',', '.');

if( $request->request_valor <= $desconto_request ) {
$req->session()->flash('mensagem-falha', 'Valor do desconto igual ou superior ao valor da Requisição!');
return redirect()->route('index');
}

$check_request = OpycosRequest::where([
'id' => $idrequest,
'id_cliente' => $idcliente
])->exists();

if( !$check_request) {

$req->session()->flash('mensagem-falha', 'Requisição não pertence ao cliente');
return redirect()->route('index');

}

$idusuario = Auth::id();
$idpedido = Pedido::consultaId([
'user_id' => $idusuario,
'status'  => 'GE' // Reservado
]);

if( empty($idpedido) ) {
$pedido_novo = Pedido::create([
'user_id' => $idusuario,
'vendedor_id' => $idvendedor,
'obs_pedido' => $obspedido,
'id_cliente' => $idcliente,
'request_id' => $request->id,
'request_valor' => $request->request_valor,
'request_desc' => $request->req_desc,
'status'  => 'GE' // Gerado
]);

$idpedido = $pedido_novo->id;

}


Pedido::where([
'id' => $idpedido,
'user_id' => $idusuario
])->update([
'obs_pedido' => $obspedido,
'request_id' => $idrequest
]);

OpycosRequest::where([
'id' => $idrequest
])->update([
'status' => 'RE' // Reservado no pedido
]);

if (isset($quantidade_req))  {

$contador = 0;

while($contador < $quantidade_req)
{
ItensPedido::create([
'pedido_id'  => $idpedido,
'request_id' => $idrequest,
'tipo' => 'R',
'prod_preco_padrao' => $request->request_valor,
'request_desconto' => $desconto_request,
'status'     => 'GE'
]);

$contador++;
}



$req->session()->flash('mensagem-sucesso', 'Requisição adicionada!');

return redirect()->route('index');

} else {

ItensPedido::create([
'pedido_id'  => $idpedido,
'request_id' => $idrequest,
'tipo' => 'R',
'prod_preco_padrao' => $request->request_valor,
'request_desconto' => $desconto_request,
'status'     => 'GE'

]);

$req->session()->flash('mensagem-sucesso', 'Requisição adicionada!');

return redirect()->route('index');


}


}*/

}




public function adicionarEdit($id)
{

$this->middleware('VerifyCsrfToken');
$req = Request();



$validator = validator($req->all(),
[
'id_cliente' => 'required'
]);

if ( $validator->fails()){
$req->session()->flash('mensagem-falha', 'É preciso escolher um cliente.');
return redirect()->route('pedidos/{id}/edit', $id)->withInput();
}

$idcliente = $req->input('id_cliente');
//$idvendedor = $req->input('id_vendedor');
$obspedido = $req->input('obs_pedido');
$tip = $req->input('boolean');
$idproduto = $req->input('id'); //Produto
$idrequest = $req->input('request_cod');  //Requisição
$comissao = $req->input('comissao');



if (($idrequest == NULL) && ($idproduto == NULL))
{

$req->session()->flash('mensagem-falha', 'Escolha algum item.');
return redirect()->route('pedidos/{id}/edit', $id)->withInput();

}


if (isset($idrequest) && isset($idproduto))
{

$req->session()->flash('mensagem-falha', 'Escolha um item por vez');
return redirect()->route('pedidos/{id}/edit', $id)->withInput();

}

$desconto_percent_prod = $req->input('desconto_produto'); //Desconto Produto %
$desconto_percent_prod = str_replace( '%', '', $desconto_percent_prod);
if ($desconto_percent_prod > 100)
{
$req->session()->flash('mensagem-falha', 'Não é Permitido desconto acima de 100%!');
return redirect()->route('pedidos/{id}/edit', $id)->withInput();
}


$desconto_reais_prod = $req->input('desconto_produto_reais');  //Desconto Produto R$
$desconto_reais_prod = str_replace( ',', '.', $desconto_reais_prod );


$desconto_reais_req = $req->input('desconto_request_reais');  //Desconto Requisição R$
$desconto_reais_req = str_replace( ',', '.', $desconto_reais_req );


$desconto_percent_req = $req->input('desconto_request');  //Desconto Requisição %
$desconto_percent_req = str_replace( '%', '', $desconto_percent_req);
if ($desconto_percent_req > 100)
{
$req->session()->flash('mensagem-falha', 'Não é Permitido desconto acima de 100%!');
return redirect()->route('pedidos/{id}/edit', $id)->withInput();
}

$quantidade_prod =  $req->input('quantidade_produto');//Qtd Produto %
$quantidade_req =  $req->input('quantidade_request');//Qtd Requisição %



if ($idrequest == NULL)
{

/*if ($desconto_percent_prod == NULL)
{
$desconto_percent_prod = '0';
}*/

$produto = Produto::find($idproduto);//Query ID Produto

if( empty($produto->id)) {
$req->session()->flash('mensagem-falha', 'Produto não pode ser Localizado');
return redirect()->route('pedidos/{id}/edit', $id);
}

$preco = $produto->prod_preco_padrao;


if ($desconto_percent_prod > 0)
{
$desconto_produtos = ($preco * $desconto_percent_prod)/100;
$desconto_produtos = number_format($desconto_produtos, 2, '.', '');	
//dd($desconto_produtos);
}


elseif ($desconto_reais_prod > 0)
{
$desconto_produtos = $desconto_reais_prod;
//dd($desconto_produtos);
}
//dd($desconto_reais_prod);

elseif (($desconto_percent_prod == "") && ($desconto_reais_prod == ""))
{
//dd($desconto_reais_prod);	
$desconto_produtos = '0.00';

} else {

$desconto_produtos = '0.00';

}


if( $preco <= $desconto_produtos ) {
$req->session()->flash('mensagem-falha', 'Erro! Valor do desconto igual ou superior ao valor do Produto!');
return redirect()->route('pedidos/{id}/edit', $id);
}

$idusuario = Auth::id();
$idpedido = Pedido::findOrFail($id);

$pencent_desconto = ($desconto_produtos/$preco)*100;

/*  Pedido::where([
'id' => $idpedido,
'user_id' => $idusuario
])->update([
'obs_pedido' => $obspedido,
'produto_id' => $idproduto
]);*/

if (isset($quantidade_prod))
{

if ($pencent_desconto > 15)
{

$comissao = $comissao / 2;

$contador = 0;


while($contador < $quantidade_prod)
{

ItensPedido::create([
'pedido_id'  => $id,
'produto_id' => $idproduto,
'tipo' => 'P',
'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $produto->prod_preco_padrao,
'prod_preco_prof' => $produto->prod_preco_prof,
'prod_desconto' => $desconto_produtos,
'comissao' => $comissao,
'status'     => 'GE'

]);
$contador++;
}


$pedidossearch = Pedido::where([
'id' => $id
])->pluck('id');


$produtossearchP  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->get();



$conte = ($produtossearchP)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');


$produtossearchc  = ItensPedido::select('comissao', 'prod_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'P')->get();


$p_comissao = $produtossearchc->pluck('comissao');

$v_produto = $produtossearchc->pluck('prod_preco_padrao');
$d_produto = $produtossearchc->pluck('prod_desconto');

            $t_comissao = 0;
            $contador = 0;
            while($conte > $contador)
            {
            $v_comissao = 
            ($p_comissao[$contador]/100) * (($v_produto[$contador]) - ($d_produto[$contador]));
            $t_comissao += $v_comissao;
            $contador++;
            
            }     
        



            $requestsearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'R')->get();



$conteR = ($requestsearch)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');



$requestsearchc  = ItensPedido::select('comissao', 'request_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'R')->get();


$r_comissao = $requestsearchc->pluck('comissao');

$v_request = $requestsearchc->pluck('prod_preco_padrao');
$d_request = $requestsearchc->pluck('request_desconto');

            $t_comissaoR = 0;
            $conta = 0;
            while($conteR > $conta)
            {
            $vcomissao = 
            ($r_comissao[$conta]/100) * (($v_request[$conta]) - ($d_request[$conta]));
            $t_comissaoR += $vcomissao;
            $conta++;
            
            }     
         

$t_comissao = $t_comissaoR + $t_comissao;
$t_comissao = number_format($t_comissao, 2, '.', '');


Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $t_comissao,
//'percentual_comissao' => $percent
]);



$req->session()->flash('mensagem-sucesso', 'Produtos adicionados!. Comissão Recalculada!.');

return redirect()->route('pedidos/{id}/edit', $id);

}

else

{

$contador = 0;


while($contador < $quantidade_prod)
{

ItensPedido::create([
'pedido_id'  => $id,
'produto_id' => $idproduto,
'tipo' => 'P',
'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $produto->prod_preco_padrao,
'prod_preco_prof' => $produto->prod_preco_prof,
'prod_desconto' => $desconto_produtos,
'comissao' => $comissao,
'status'     => 'GE'

]);
$contador++;
}


$pedidossearch = Pedido::where([
'id' => $id
])->pluck('id');


$produtossearchP  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->get();


$conte = ($produtossearchP)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');


$produtossearchc  = ItensPedido::select('comissao', 'prod_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'P')->get();


$p_comissao = $produtossearchc->pluck('comissao');

$v_produto = $produtossearchc->pluck('prod_preco_padrao');
$d_produto = $produtossearchc->pluck('prod_desconto');

            $t_comissao = 0;
            $contador = 0;
            while($conte > $contador)
            {
            $v_comissao = 
            ($p_comissao[$contador]/100) * (($v_produto[$contador]) - ($d_produto[$contador]));
            $t_comissao += $v_comissao;
            $contador++;
            
            }     
        

            $requestsearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'R')->get();



$conteR = ($requestsearch)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');



$requestsearchc  = ItensPedido::select('comissao', 'request_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'R')->get();


$r_comissao = $requestsearchc->pluck('comissao');

$v_request = $requestsearchc->pluck('prod_preco_padrao');
$d_request = $requestsearchc->pluck('request_desconto');

            $t_comissaoR = 0;
            $conta = 0;
            while($conteR > $conta)
            {
            $vcomissao = 
            ($r_comissao[$conta]/100) * (($v_request[$conta]) - ($d_request[$conta]));
            $t_comissaoR += $vcomissao;
            $conta++;
            
            }     
         


$t_comissao = $t_comissaoR + $t_comissao;
$t_comissao = number_format($t_comissao, 2, '.', '');


Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $t_comissao,
//'percentual_comissao' => $percent
]);



$req->session()->flash('mensagem-sucesso', 'Produtos adicionados!. Comissão Recalculada!.');

return redirect()->route('pedidos/{id}/edit', $id);
}



} else {

	if ($pencent_desconto > 15)
{

$comissao = $comissao / 2;

ItensPedido::create([
'pedido_id'  => $id,
'produto_id' => $idproduto,
'tipo' => 'P',
'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $produto->prod_preco_padrao,
'prod_preco_prof' => $produto->prod_preco_prof,
'prod_desconto' => $desconto_produtos,
'comissao' => $comissao,
'status'     => 'GE'

]);

$pedidossearch = Pedido::where([
'id' => $id
])->pluck('id');


$produtossearchP  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->get();



$conte = ($produtossearchP)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');


$produtossearchc  = ItensPedido::select('comissao', 'prod_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'P')->get();


$p_comissao = $produtossearchc->pluck('comissao');

$v_produto = $produtossearchc->pluck('prod_preco_padrao');
$d_produto = $produtossearchc->pluck('prod_desconto');

            $t_comissao = 0;
            $contador = 0;
            while($conte > $contador)
            {
            $v_comissao = 
            ($p_comissao[$contador]/100) * (($v_produto[$contador]) - ($d_produto[$contador]));
            $t_comissao += $v_comissao;
            $contador++;
            
            }     


            $requestsearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'R')->get();



$conteR = ($requestsearch)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');



$requestsearchc  = ItensPedido::select('comissao', 'request_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'R')->get();


$r_comissao = $requestsearchc->pluck('comissao');

$v_request = $requestsearchc->pluck('prod_preco_padrao');
$d_request = $requestsearchc->pluck('request_desconto');

            $t_comissaoR = 0;
            $conta = 0;
            while($conteR > $conta)
            {
            $vcomissao = 
            ($r_comissao[$conta]/100) * (($v_request[$conta]) - ($d_request[$conta]));
            $t_comissaoR += $vcomissao;
            $conta++;
            
            }     
         


$t_comissao = $t_comissaoR + $t_comissao;
$t_comissao = number_format($t_comissao, 2, '.', '');


Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $t_comissao,
//'percentual_comissao' => $percent
]);

$req->session()->flash('mensagem-sucesso', 'Produto adicionado! Comissão Recalculada!.');

return redirect()->route('pedidos/{id}/edit', $id);

}

else {

	ItensPedido::create([
'pedido_id'  => $id,
'produto_id' => $idproduto,
'tipo' => 'P',
'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $produto->prod_preco_padrao,
'prod_preco_prof' => $produto->prod_preco_prof,
'prod_desconto' => $desconto_produtos,
'comissao' => $comissao,
'status'     => 'GE'

]);

$pedidossearch = Pedido::where([
'id' => $id
])->pluck('id');


$produtossearchP  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->get();


$conte = ($produtossearchP)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');


$produtossearchc  = ItensPedido::select('comissao', 'prod_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'P')->get();


$p_comissao = $produtossearchc->pluck('comissao');

$v_produto = $produtossearchc->pluck('prod_preco_padrao');
$d_produto = $produtossearchc->pluck('prod_desconto');

            $t_comissao = 0;
            $contador = 0;
            while($conte > $contador)
            {
            $v_comissao = 
            ($p_comissao[$contador]/100) * (($v_produto[$contador]) - ($d_produto[$contador]));
            $t_comissao += $v_comissao;
            $contador++;
            
            }

            $requestsearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'R')->get();


$conteR = ($requestsearch)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');



$requestsearchc  = ItensPedido::select('comissao', 'request_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'R')->get();


$r_comissao = $requestsearchc->pluck('comissao');

$v_request = $requestsearchc->pluck('prod_preco_padrao');
$d_request = $requestsearchc->pluck('request_desconto');

            $t_comissaoR = 0;
            $conta = 0;
            while($conteR > $conta)
            {
            $vcomissao = 
            ($r_comissao[$conta]/100) * (($v_request[$conta]) - ($d_request[$conta]));
            $t_comissaoR += $vcomissao;
            $conta++;
            
            }     
         


$t_comissao = $t_comissaoR + $t_comissao;
$t_comissao = number_format($t_comissao, 2, '.', '');


Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $t_comissao,
//'percentual_comissao' => $percent
]);

$req->session()->flash('mensagem-sucesso', 'Produto adicionado! Comissão Recalculada!.');

return redirect()->route('pedidos/{id}/edit', $id);
}


}




}

if ($idproduto == NULL)
{

/*if ($desconto_percent_req == NULL)
{
$desconto_percent_req = '0';
}*/

$request = OpycosRequest::find($idrequest);//Query ID Requisição

if( empty($request->id)) {
$req->session()->flash('mensagem-falha', 'Requisição não pode ser Localizada');
return redirect()->route('pedidos/{id}/edit', $id);
}

$preco = $request->request_valor;


if ($desconto_percent_req > 0)
{
$desconto_request = ($preco * $desconto_percent_req)/100;
$desconto_request = number_format($desconto_request, 2, '.', '');
}

elseif ($desconto_reais_req > 0)
{
$desconto_request = $desconto_reais_req;
}

elseif (($desconto_percent_req == "") && ($desconto_reais_req == ""))
{
$desconto_request = '0.00';
} else {

$desconto_request = '0.00';

}


if( $preco <= $desconto_request ) {
$req->session()->flash('mensagem-falha', 'Valor do desconto igual ou superior ao valor da Requisição!');
return redirect()->route('pedidos/{id}/edit', $id);
}



$check_request = OpycosRequest::where([
'id' => $idrequest,
'id_cliente' => $idcliente
//'status' => 'FI'
])->exists();

if( !$check_request) {

$req->session()->flash('mensagem-falha', 'Requisição não pertence ao cliente');
return redirect()->route('pedidos/{id}/edit', $id);

}

$idusuario = Auth::id();
$idpedido = Pedido::findOrFail($id);

/*   Pedido::where([
'id' => $idpedido,
'user_id' => $idusuario
])->update([
'obs_pedido' => $obspedido,
'request_id' => $idrequest
]);*/



OpycosRequest::where([
'id' => $idrequest,
// 'status' => 'FI'
])->update([
'status' => 'RE' // Reservado no pedido
]);

if (isset($quantidade_req))
{

$contador = 0;


while($contador < $quantidade_req)
{

ItensPedido::create([
'pedido_id'  => $id,
'request_id' => $idrequest,
'tipo' => 'R',
//'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $request->request_valor,
'request_desconto' => $desconto_request,
'comissao' => $comissao,
// 'prod_preco_prof' => $produto->prod_preco_prof,
'status'     => 'GE'

]);

$contador++;
}

$pedidossearch = Pedido::where([
'id' => $id
])->pluck('id');


$produtosearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->get();


$conteP = ($produtosearch)->count();

$produtosearchc  = ItensPedido::select('comissao', 'prod_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'P')->get();


$p_comissao = $produtosearchc->pluck('comissao');

$v_produto = $produtosearchc->pluck('prod_preco_padrao');
$d_produto = $produtosearchc->pluck('prod_desconto');

            $t_comissao = 0;
            $contador = 0;
            while($conteP > $contador)
            {
            $v_comissao = 
            ($p_comissao[$contador]/100) * (($v_produto[$contador]) - ($d_produto[$contador]));
            $t_comissao += $v_comissao;
            $contador++;
            
            } 

        
      

$requestsearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'R')->get();


$conte = ($requestsearch)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');



$requestsearchc  = ItensPedido::select('comissao', 'request_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'R')->get();


$r_comissao = $requestsearchc->pluck('comissao');

$v_request = $requestsearchc->pluck('prod_preco_padrao');
$d_request = $requestsearchc->pluck('request_desconto');

            $t_comissaoR = 0;
            $conta = 0;
            while($conte > $conta)
            {
            $vcomissao = 
            ($r_comissao[$conta]/100) * (($v_request[$conta]) - ($d_request[$conta]));
            $t_comissaoR += $vcomissao;
            $conta++;
            
            }     
        
      
        	
        


$t_comissaoR = $t_comissao + $t_comissaoR;
$t_comissaoR = number_format($t_comissaoR, 2, '.', '');


Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $t_comissaoR,
//'percentual_comissao' => $percent
]);



$req->session()->flash('mensagem-sucesso', 'Requisição adicionada! Comissão Recalculada!');

return redirect()->route('pedidos/{id}/edit', $id);

} else {

ItensPedido::create([
'pedido_id'  => $id,
'request_id' => $idrequest,
'tipo' => 'R',
//'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $request->request_valor,
'request_desconto' => $desconto_request,
'comissao' => $comissao,
// 'prod_preco_prof' => $produto->prod_preco_prof,
'status'     => 'GE'

]);

$pedidossearch = Pedido::where([
'id' => $id
])->pluck('id');


$produtosearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->get();


$conteP = ($produtosearch)->count();



$produtosearchc  = ItensPedido::select('comissao', 'prod_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'P')->get();


$p_comissao = $produtosearchc->pluck('comissao');

$v_produto = $produtosearchc->pluck('prod_preco_padrao');
$d_produto = $produtosearchc->pluck('prod_desconto');

            $t_comissao = 0;
            $contador = 0;
            while($conteP > $contador)
            {
            $v_comissao = 
            ($p_comissao[$contador]/100) * (($v_produto[$contador]) - ($d_produto[$contador]));
            $t_comissao += $v_comissao;
            $contador++;
            
            } 

        
      

$requestsearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'R')->get();


$conte = ($requestsearch)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');



$requestsearchc  = ItensPedido::select('comissao', 'request_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'R')->get();


$r_comissao = $requestsearchc->pluck('comissao');

$v_request = $requestsearchc->pluck('prod_preco_padrao');
$d_request = $requestsearchc->pluck('request_desconto');

            $t_comissaoR = 0;
            $conta = 0;
            while($conte > $conta)
            {
            $vcomissao = 
            ($r_comissao[$conta]/100) * (($v_request[$conta]) - ($d_request[$conta]));
            $t_comissaoR += $vcomissao;
            $conta++;
            
            }     



$t_comissaoR = $t_comissao + $t_comissaoR;
$t_comissaoR = number_format($t_comissaoR, 2, '.', '');



Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $t_comissaoR,
//'percentual_comissao' => $percent
]);

$req->session()->flash('mensagem-sucesso', 'Requisição adicionada! Comissão Recalculada! ');

return redirect()->route('pedidos/{id}/edit', $id);


}




}


}




public function adicionarEditConsig($id)
{

$this->middleware('VerifyCsrfToken');
$req = Request();



$obspedido = $req->input('obs_pedido');
$tip = $req->input('boolean');



$idproduto = $req->input('id'); //Produto


$desconto_percent_prod = $req->input('desconto_produto'); //Desconto Produto %
$desconto_percent_prod = str_replace( '%', '', $desconto_percent_prod);
if ($desconto_percent_prod > 100)
{
$req->session()->flash('mensagem-falha', 'Não é Permitido desconto acima de 100%!');
return redirect()->route('pedido/consignado/{id}/edit', $id)->withInput();
}





$quantidade_prod =  $req->input('quantidade_produto');//Qtd Produto %


$desconto_reais_prod = $req->input('desconto_produto_reais');  //Desconto Produto R$
$desconto_reais_prod = str_replace( ',', '.', $desconto_reais_prod );

$getcomissao = Pedido::find($id);
$percent = $getcomissao->percentual_comissao;//Percentual de comissão.



/*if ($desconto_percent_prod == NULL)
{
$desconto_percent_prod = '0';
}*/

$produto = Produto::find($idproduto);//Query ID Produto

if( empty($produto->id)) {
$req->session()->flash('mensagem-falha', 'Produto não pode ser Localizado');
return redirect()->route('pedido/consignado/{id}/edit', $id);
}

$preco = $produto->prod_preco_padrao;

if ($desconto_percent_prod > 0)
{
$desconto_produtos = ($preco * $desconto_percent_prod)/100;
$desconto_produtos = number_format($desconto_produtos, 2, '.', '');	
//dd($desconto_produtos);
}


elseif ($desconto_reais_prod > 0)
{
$desconto_produtos = $desconto_reais_prod;
//dd($desconto_produtos);
}
//dd($desconto_reais_prod);

elseif (($desconto_percent_prod == "") && ($desconto_reais_prod == ""))
{
//dd($desconto_reais_prod);	
$desconto_produtos = '0.00';

} else
{
$desconto_produtos = '0.00';
}


if( $produto->prod_preco_padrao <= $desconto_produtos ) {
$req->session()->flash('mensagem-falha', 'Erro! Valor do desconto igual ou superior ao valor do Produto!');
return redirect()->route('pedido/consignado/{id}/edit', $id);
}

$idusuario = Auth::id();
$idpedido = Pedido::findOrFail($id);




/*  Pedido::where([
'id' => $idpedido,
'user_id' => $idusuario
])->update([
'obs_pedido' => $obspedido,
'produto_id' => $idproduto
]);*/

if (isset($quantidade_prod))
{

$contador = 0;


while($contador < $quantidade_prod)
{

ItensPedido::create([
'pedido_id'  => $id,
'produto_id' => $idproduto,
'tipo' => 'P',
'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $produto->prod_preco_padrao,
'prod_preco_prof' => $produto->prod_preco_prof,
'prod_desconto' => $desconto_produtos,
'status'     => 'GE'

]);
$contador++;
}


//Verifica Estado atual do pedido
$pedidossearch = Pedido::select('id')->where([
'id' => $id
])->pluck('id');

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$soma_produtos  = $produtossearch->sum('prod_preco_padrao');

$desconto_produtos = $produtossearch->sum('prod_desconto');

$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

$geral = $soma_produtos - $desconto;

$total_preco = $geral;

$Calculopercent = $percent * $total_preco/100;
$Calculopercent = number_format($Calculopercent, 2, '.', '');


Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $Calculopercent,
//'percentual_comissao' => $percent
]);




$req->session()->flash('mensagem-sucesso', 'Produto adicionado!');

return redirect()->route('pedido/consignado/{id}/edit', $id);

} else {

ItensPedido::create([
'pedido_id'  => $id,
'produto_id' => $idproduto,
'tipo' => 'P',
'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $produto->prod_preco_padrao,
'prod_preco_prof' => $produto->prod_preco_prof,
'prod_desconto' => $desconto_produtos,
'status'     => 'GE'

]);


//Verifica Estado atual do pedido
$pedidossearch = Pedido::select('id')->where([
'id' => $id
])->pluck('id');

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$soma_produtos  = $produtossearch->sum('prod_preco_padrao');

$desconto_produtos = $produtossearch->sum('prod_desconto');

$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

$geral = $soma_produtos - $desconto;

$total_preco = $geral;

$Calculopercent = $percent * $total_preco/100;
$Calculopercent = number_format($Calculopercent, 2, '.', '');


Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $Calculopercent,
//'percentual_comissao' => $percent
]);


$req->session()->flash('mensagem-sucesso', 'Produto adicionado!');

return redirect()->route('pedido/consignado/{id}/edit', $id);


}







}




public function statusEdit($id)

{
$pedidos = Pedido::findOrFail($id);
$this->middleware('VerifyCsrfToken');
$req = Request();
$status = $req->input('status');

$check_pedido = Pedido::where([
'id'      => $id,
//'user_id' => $idusuario,
// 'status'  => 'GE' // Gerado
])->exists();
if( !$check_pedido ) {
$req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
return redirect()->route('pedido.compras');
}


if( $status == 'RE' ) {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status,
'cancelados' => 'N'

]);

$req->session()->flash('mensagem-sucesso', 'Pedido aberto para alterações.');
return redirect()->route('pedidos/{id}/edit', $id);


}


if( $status == 'AP' ) {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status,
'cancelados' => 'N'

]);

$req->session()->flash('mensagem-sucesso', 'Pedido Aguardando confirmação de pagamento.');
return redirect()->route('pedido.compras');


}

if( $status == 'EL' ) {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status,
'cancelados' => 'N'

]);

$req->session()->flash('mensagem-sucesso', 'Pedido Encaminhado ao Laboratório.');
return redirect()->route('pedido.compras');


}


if( $status == 'EC' ) {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status,
'cancelados' => 'N'

]);

$req->session()->flash('mensagem-sucesso', 'Pedido Enviado ao cliente.');
return redirect()->route('pedido.compras');


}




if( $status == 'FI') {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status,
'cancelados' => 'N'

]);

$req->session()->flash('mensagem-sucesso', 'Pedido pago. Finalizado com sucesso!');
return redirect()->route('pedido.compras');


}

if( $status == 'CA') {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status,
'cancelados' => 'S'

]);

$req->session()->flash('mensagem-sucesso', 'Pedido Cancelado pelo Cliente.');
return redirect()->route('pedido.compras');


}




}


public function statusEditConsig($id)

{
$pedidos = Pedido::findOrFail($id);
$this->middleware('VerifyCsrfToken');
$req = Request();
$status = $req->input('status');

$check_pedido = Pedido::where([
'id'      => $id,
//'user_id' => $idusuario,
// 'status'  => 'GE' // Gerado
])->exists();
if( !$check_pedido ) {
$req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
return redirect()->route('pedido.compras');
}


if( $status == 'RE' ) {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status

]);

$req->session()->flash('mensagem-sucesso', 'Pedido aberto para alterações.');
return redirect()->route('pedidos/{id}/consig/edit', $id);


}


if( $status == 'AP' ) {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status,
'cancelados' => 'N'

]);

$req->session()->flash('mensagem-sucesso', 'Pedido Aguardando confirmação de pagamento.');
return redirect()->route('pedido.consignado');


}

if( $status == 'EL' ) {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status,
'cancelados' => 'N'

]);

$req->session()->flash('mensagem-sucesso', 'Pedido Encaminhado ao Laboratório.');
return redirect()->route('pedido.consignado');


}


if( $status == 'EC' ) {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status,
'cancelados' => 'N'

]);

$req->session()->flash('mensagem-sucesso', 'Pedido Enviado ao cliente.');
return redirect()->route('pedido.consignado');


}




if( $status == 'FI') {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status,
'cancelados' => 'N'

]);

$req->session()->flash('mensagem-sucesso', 'Pedido Entregue ao cliente. Finalizado com sucesso!');
return redirect()->route('pedido.consignado');


}

if( $status == 'CA') {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status,
'cancelados' => 'S'

]);

$req->session()->flash('mensagem-sucesso', 'Pedido Cancelado pelo Cliente.');
return redirect()->route('pedido.consignado');


}




}





public function remover()
{

$this->middleware('VerifyCsrfToken');

$req = Request();
$idpedido           = $req->input('pedido_id');
$idrequest          = $req->input('request_cod');
$idproduto          = $req->input('produto_id');
$remove_apenas_item = (boolean)$req->input('item');
$idusuario          = Auth::id();

$idpedido = Pedido::consultaId([
'id'      => $idpedido,
//'user_id' => $idusuario,
'status'  => 'GE' // Reservada
]);

if( empty($idpedido) ) {
$req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
return redirect()->route('index');
}


if ($idrequest == NULL)
{


$where_produto = [
'pedido_id'  => $idpedido,
'produto_id' => $idproduto
];

$produto = ItensPedido::where($where_produto)->orderBy('id', 'desc')->first();
if( empty($produto->id) ) {
$req->session()->flash('mensagem-falha', 'Produto não encontrado no pedido!');
return redirect()->route('index');
}

if( $remove_apenas_item ) {
$where_produto['id'] = $produto->id;

}


ItensPedido::where($where_produto)->delete();




$check_pedido = ItensPedido::where([
'pedido_id' => $produto->pedido_id
])->exists();

if( !$check_pedido ) {
Pedido::where([
'id' => $produto->pedido_id
])->delete();

Pedido::where([
'id' => $produto->pedido_id,

])->update([
'produto_id' => NULL
]);

}

$req->session()->flash('mensagem-sucesso', 'Produto removido do pedido com sucesso!');

return redirect()->route('index');


}

if ($idproduto == NULL)
{

$where_request = [
'pedido_id'  => $idpedido,
'request_id' => $idrequest
];

$request = ItensPedido::where($where_request)->orderBy('id', 'desc')->first();
if( empty($request->id) ) {
$req->session()->flash('mensagem-falha', 'Requisição não localizada!');
return redirect()->route('index');
}


if( $remove_apenas_item ) {
$where_request['id'] = $request->id;


}

ItensPedido::where($where_request)->delete();


$check_pedido = ItensPedido::where([
'pedido_id' => $request->pedido_id
])->exists();

if( !$check_pedido ) {
Pedido::where([
'id' => $request->pedido_id
])->delete();



}

OpycosRequest::where([
'id' => $idrequest

])->update([
'status' => 'FI' // Finalizado
]);

$req->session()->flash('mensagem-sucesso', 'Requisição removida do pedido com sucesso!');

return redirect()->route('index');


}


}


public function removerConsig()
{

$this->middleware('VerifyCsrfToken');

$req = Request();
$idpedido           = $req->input('pedido_id');
$idrequest          = $req->input('request_cod');
$idproduto          = $req->input('produto_id');
$remove_apenas_item = (boolean)$req->input('item');
$idusuario          = Auth::id();

$idpedido = Pedido::consultaId([
'id'      => $idpedido,
//'user_id' => $idusuario,
'status'  => 'GE' // Gerado
]);

if( empty($idpedido) ) {
$req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
return redirect()->route('index.consignado');
}


if ($idrequest == NULL)
{


$where_produto = [
'pedido_id'  => $idpedido,
'produto_id' => $idproduto
];

$produto = ItensPedido::where($where_produto)->orderBy('id', 'desc')->first();
if( empty($produto->id) ) {
$req->session()->flash('mensagem-falha', 'Produto não encontrado no pedido!');
return redirect()->route('index.consignado');
}

if( $remove_apenas_item ) {
$where_produto['id'] = $produto->id;

}


ItensPedido::where($where_produto)->delete();




$check_pedido = ItensPedido::where([
'pedido_id' => $produto->pedido_id
])->exists();

if( !$check_pedido ) {
Pedido::where([
'id' => $produto->pedido_id
])->delete();

Pedido::where([
'id' => $produto->pedido_id,

])->update([
'produto_id' => NULL
]);

}

$req->session()->flash('mensagem-sucesso', 'Produto removido do pedido com sucesso!');

return redirect()->route('index.consignado');


}

if ($idproduto == NULL)
{

$where_request = [
'pedido_id'  => $idpedido,
'request_id' => $idrequest
];

$request = ItensPedido::where($where_request)->orderBy('id', 'desc')->first();
if( empty($request->id) ) {
$req->session()->flash('mensagem-falha', 'Requisição não localizada!');
return redirect()->route('index.consignado');
}


if( $remove_apenas_item ) {
$where_request['id'] = $request->id;


}

ItensPedido::where($where_request)->delete();


$check_pedido = ItensPedido::where([
'pedido_id' => $request->pedido_id
])->exists();

if( !$check_pedido ) {
Pedido::where([
'id' => $request->pedido_id
])->delete();



}

OpycosRequest::where([
'id' => $idrequest

])->update([
'status' => 'FI' // Finalizado
]);

$req->session()->flash('mensagem-sucesso', 'Requisição removida do pedido com sucesso!');

return redirect()->route('index.consignado');


}


}


public function removerEdit($id)
{

$this->middleware('VerifyCsrfToken');

$req = Request();
// $idpedido           = $req->input('pedido_id');
$idrequest          = $req->input('request_cod');
$idproduto          = $req->input('produto_id');
// dd($idproduto);
$remove_apenas_item = (boolean)$req->input('item');
$idusuario          = Auth::id();



//dd($t_comissao);

$idpedido = Pedido::findOrFail($id);

$getcomissao = Pedido::find($id);
$percent = $getcomissao->percentual_comissao;//Percentual de comissão.

/* if( empty($idpedido) ) {
$req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
return redirect()->route('pedidos/{id}/edit', $id);
}*/


if ($idrequest == NULL)
{


$where_produto = [
'pedido_id'  => $id,
'produto_id' => $idproduto
];

$produto = ItensPedido::where($where_produto)->orderBy('id', 'desc')->first();

if( empty($produto->id) ) {
$req->session()->flash('mensagem-falha', 'Produto não encontrado no pedido!');
return redirect()->route('pedidos/{id}/edit', $id);
}

if( $remove_apenas_item ) {
$where_produto['id'] = $produto->id;

}


ItensPedido::where($where_produto)->delete();



$check_pedido = ItensPedido::where([
'pedido_id' => $produto->pedido_id
])->exists();

if( !$check_pedido ) {
/*  Pedido::where([
'id' => $produto->pedido_id
])->delete();*/

Pedido::where([
'id' => $produto->pedido_id,

])->update([
'produto_id' => NULL
]);

}

$pedidossearch = Pedido::where([
'id' => $id
])->pluck('id');


$produtossearchP  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->get();

 $t_comissao = 0;

if(isset($produtossearchP))
{

$conte = ($produtossearchP)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');


$produtossearchc  = ItensPedido::select('comissao', 'prod_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'P')->get();


$p_comissao = $produtossearchc->pluck('comissao');

$v_produto = $produtossearchc->pluck('prod_preco_padrao');
$d_produto = $produtossearchc->pluck('prod_desconto');

            $t_comissao = 0;
            $contador = 0;
            while($conte > $contador)
            {
            $v_comissao = 
            ($p_comissao[$contador]/100) * (($v_produto[$contador]) - ($d_produto[$contador]));
            $t_comissao += $v_comissao;
            $contador++;
            
            }
}
            $requestsearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'R')->get();

 $t_comissaoR = 0;

if(isset($requestsearch))
{

$conteR = ($requestsearch)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');



$requestsearchc  = ItensPedido::select('comissao', 'request_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'R')->get();


$r_comissao = $requestsearchc->pluck('comissao');

$v_request = $requestsearchc->pluck('prod_preco_padrao');
$d_request = $requestsearchc->pluck('request_desconto');

            $t_comissaoR = 0;
            $conta = 0;
            while($conteR > $conta)
            {
            $vcomissao = 
            ($r_comissao[$conta]/100) * (($v_request[$conta]) - ($d_request[$conta]));
            $t_comissaoR += $vcomissao;
            $conta++;
            
            }     
        }     


$t_comissao = $t_comissaoR + $t_comissao;
$t_comissao = number_format($t_comissao, 2, '.', '');


Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $t_comissao,
//'percentual_comissao' => $percent
]);

$req->session()->flash('mensagem-sucesso', 'Produto removido, comissão recalculada!');

return redirect()->route('pedidos/{id}/edit', $id);



}

if ($idproduto == NULL)
{

$where_request = [
'pedido_id'  => $id,
'request_id' => $idrequest
];

$request = ItensPedido::where($where_request)->orderBy('id', 'desc')->first();
if( empty($request->id) ) {
$req->session()->flash('mensagem-falha', 'Requisição não localizada!');
return redirect()->route('pedidos/{id}/edit', $id);
}


if( $remove_apenas_item ) {
$where_request['id'] = $request->id;
}

ItensPedido::where($where_request)->delete();


/*   $check_pedido = ItensPedido::where([
'pedido_id' => $request->pedido_id
])->exists();

if( !$check_pedido ) {
Pedido::where([
'id' => $request->pedido_id
])->delete();



}*/

OpycosRequest::where([
'id' => $idrequest

])->update([
'status' => 'FI' // Finalizado
]);


//Verifica Estado atual do pedido
$pedidossearch = Pedido::select('id')->where([
'id' => $id
])->pluck('id');

$produtossearchP  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->get();

 $t_comissao = 0;

if(isset($produtossearchP))
{

$conte = ($produtossearchP)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');


$produtossearchc  = ItensPedido::select('comissao', 'prod_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'P')->get();


$p_comissao = $produtossearchc->pluck('comissao');

$v_produto = $produtossearchc->pluck('prod_preco_padrao');
$d_produto = $produtossearchc->pluck('prod_desconto');

            $t_comissao = 0;
            $contador = 0;
            while($conte > $contador)
            {
            $v_comissao = 
            ($p_comissao[$contador]/100) * (($v_produto[$contador]) - ($d_produto[$contador]));
            $t_comissao += $v_comissao;
            $contador++;
            
            }
}
            $requestsearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'R')->get();

 $t_comissaoR = 0;

if(isset($requestsearch))
{

$conteR = ($requestsearch)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');



$requestsearchc  = ItensPedido::select('comissao', 'request_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'R')->get();


$r_comissao = $requestsearchc->pluck('comissao');

$v_request = $requestsearchc->pluck('prod_preco_padrao');
$d_request = $requestsearchc->pluck('request_desconto');

            $t_comissaoR = 0;
            $conta = 0;
            while($conteR > $conta)
            {
            $vcomissao = 
            ($r_comissao[$conta]/100) * (($v_request[$conta]) - ($d_request[$conta]));
            $t_comissaoR += $vcomissao;
            $conta++;
            
            }     
        }     


$t_comissao = $t_comissaoR + $t_comissao;
$t_comissao = number_format($t_comissao, 2, '.', '');


Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $t_comissao,
//'percentual_comissao' => $percent
]);

$req->session()->flash('mensagem-sucesso', 'Requisição removida do pedido com sucesso! Comissão Recalculada.');

return redirect()->route('pedidos/{id}/edit', $id);


}


}

public function removerEditConsig($id)
{

$this->middleware('VerifyCsrfToken');

$req = Request();
// $idpedido           = $req->input('pedido_id');
$idrequest          = $req->input('request_cod');
$idproduto          = $req->input('produto_id');
// dd($idproduto);
$remove_apenas_item = (boolean)$req->input('item');
$idusuario          = Auth::id();



//dd($t_comissao);

$idpedido = Pedido::findOrFail($id);

$getcomissao = Pedido::find($id);
$percent = $getcomissao->percentual_comissao;//Percentual de comissão.

/* if( empty($idpedido) ) {
$req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
return redirect()->route('pedidos/{id}/edit', $id);
}*/


if ($idrequest == NULL)
{


$where_produto = [
'pedido_id'  => $id,
'produto_id' => $idproduto
];

$produto = ItensPedido::where($where_produto)->orderBy('id', 'desc')->first();

if( empty($produto->id) ) {
$req->session()->flash('mensagem-falha', 'Produto não encontrado no pedido!');
return redirect()->route('pedidos/{id}/consig/edit', $id)->withInput();
}

if( $remove_apenas_item ) {
$where_produto['id'] = $produto->id;

}


ItensPedido::where($where_produto)->delete();



$check_pedido = ItensPedido::where([
'pedido_id' => $produto->pedido_id
])->exists();

if( !$check_pedido ) {
/*  Pedido::where([
'id' => $produto->pedido_id
])->delete();*/

Pedido::where([
'id' => $produto->pedido_id,

])->update([
'produto_id' => NULL
]);

}


//Verifica Estado atual do pedido
$pedidossearch = Pedido::select('id')->where([
'id' => $id
])->pluck('id');

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$soma_produtos  = $produtossearch->sum('prod_preco_padrao');

$desconto_produtos = $produtossearch->sum('prod_desconto');

$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

$geral = $soma_produtos - $desconto;

$total_preco = $geral;

$Calculopercent = $percent * $total_preco/100;
$Calculopercent = number_format($Calculopercent, 2, '.', '');


Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $Calculopercent,
//'percentual_comissao' => $percent
]);





$req->session()->flash('mensagem-sucesso', 'Produto removido, comissão recalculada!');

return redirect()->route('pedidos/{id}/consig/edit', $id);



}

if ($idproduto == NULL)
{

$where_request = [
'pedido_id'  => $id,
'request_id' => $idrequest
];

$request = ItensPedido::where($where_request)->orderBy('id', 'desc')->first();
if( empty($request->id) ) {
$req->session()->flash('mensagem-falha', 'Requisição não localizada!');
return redirect()->route('pedidos/{id}/consig/edit', $id);
}


if( $remove_apenas_item ) {
$where_request['id'] = $request->id;
}

ItensPedido::where($where_request)->delete();


/*   $check_pedido = ItensPedido::where([
'pedido_id' => $request->pedido_id
])->exists();

if( !$check_pedido ) {
Pedido::where([
'id' => $request->pedido_id
])->delete();



}*/

OpycosRequest::where([
'id' => $idrequest

])->update([
'status' => 'FI' // Finalizado
]);


//Verifica Estado atual do pedido
$pedidossearch = Pedido::select('id')->where([
'id' => $id
])->pluck('id');

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$soma_produtos  = $produtossearch->sum('prod_preco_padrao');

$desconto_produtos = $produtossearch->sum('prod_desconto');

$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

$geral = $soma_produtos - $desconto;

$total_preco = $geral;

$Calculopercent = $percent * $total_preco/100;
$Calculopercent = number_format($Calculopercent, 2, '.', '');


Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $Calculopercent,
//'percentual_comissao' => $percent
]);



$req->session()->flash('mensagem-sucesso', 'Requisição removida do pedido com sucesso! Comissão Recalculada.');

return redirect()->route('pedidos/{id}/consig/edit', $id);


}


}






public function detalhes($id) {

$pedidos = Pedido::findOrFail($id);
$this->middleware('VerifyCsrfToken');
$req = Request();
$local = $req->input('local');
$cep = $req->input('cep');// CEP de destino lterado
$endereço = $req->input('endereço');
$numero = $req->input('numero');
$bairro = $req->input('bairro');
$complemento = $req->input('complemento');
$cidade = $req->input('cidade');
$estado = $req->input('estado');
$obspedido = $req->input('obs_pedido');
$idcliente = $req->input('id_cliente');
$idvendedor = $req->input('vendedor_id');
//$idusuario = $req->input('user_id');
$comissao = $req->input('comissao');
//$valor_comissao = $req->input('valor_comissao');
//$percent_comissao = $req->input('percentual_comissao');

//dd($comissao);

$prazoentrega = $req->input('prazo_entrega');
$prazoentrega = str_replace( ' ', '', str_replace('Dias', '', $prazoentrega));
// $prazoentrega = $prazoentrega + 2;
$cdservico = $req->input('cdservico');

// $idproduto = $req->input('id');
$retirada = $req->input('balcao');
$frete = $req->input('entrega');
// $status = $req->input('status');
$valor = $req->input('valor');
$valor = str_replace( ',', '.', $valor );
$pagamento = $req->input('pagamento');
//$cdservico = $req->input('cdservico');
$idusuario = Auth::id();
// $cepOrigem = "09090520"; //CEP de Origem!!!!!!!!!


//$getcomissao = Pedido::find($id);
//$percent = $getcomissao->percentual_comissao;


$pedidossearch = Pedido::select('id')->where([
'id' => $id
])->pluck('id');

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');

$desconto_produtos = $produtossearch->sum('prod_desconto');

$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;


//$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
//$frete_total = $frete->sum('valor');

$geral = $soma_produtos - $desconto;

$total_preco = $geral;

//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');




/* $produto = Produto::find($idproduto);
if( empty($produto->id) ) {
$req->session()->flash('mensagem-falha', 'Produto não encontrado em nossa loja!');
return redirect()->route('pedidos.index');
}*/





//  $check_cep = Cliente::select('cep')->where('id', '=', $idcliente)->get(); //CEP de destino!!!!!!!!!


$check_pedido = Pedido::where([
'id'      => $id,
'status'  => 'GE' // Gerado
])->exists();

if( !$check_pedido ) {
$req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
return redirect()->route('index');
}


$idpedido = Pedido::consultaId([
'id' => $id,
'status'  => 'GE' // GERADO
]);

if( isset($idpedido) ) {

if( empty($pagamento) ) {
//  dd($pagamento);
$req->session()->flash('message', 'Preencha a forma de Pagamento!');
return redirect()->route('index')->withInput();
}

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id,
'status'  => 'GE'
])->update([
'pagamento' => $pagamento,
'vendedor_id' => $idvendedor,
//'percentual_comissao' => $comissao,
'obs_pedido' => $obspedido

]);


$idcomissao = Comissao::consultaId([
'pedido_id'	=> $id,
'cliente_id' => $idcliente,
'vendedor_id' => $idvendedor,
'status'  => 'PE' // Pendente
]);

if( empty($idcomissao) ) {
$gera_comissao = Comissao::create([
'pedido_id' => $id,
'cliente_id' => $idcliente,
'vendedor_id' => $idvendedor,
'status' => 'PE',
'valor_comissao' => $comissao,
//'percentual_comissao' => $percent,
'user_id' => $idusuario
//'obs_comissao'
//'user_id' => $idusuario
]);

$idcomissao = $gera_comissao->id;

}


Comissao::where([
'id' => $idcomissao,
'vendedor_id' => $idvendedor
//'user_id' => $idusuario

])->update([
'status' => 'PE',	
'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $comissao,
//'percentual_comissao' => $percent
]);



} /*END ISSET PEDIDO*/





if ($retirada == NULL && $frete == NULL)
{
$req->session()->flash('message', 'Informe o tipo de frete!');
return redirect()->route('index')->withInput();

}




if ($frete == "B")
{

if ($valor == NULL) {
$req->session()->flash('message', 'Informar o custo do frete!');
return redirect()->route('index')->withInput();
}


if (isset($local))
{

Frete::create([
'pedido_id'  => $id,
// 'produto_id' => $idproduto,
'local' => $local,
'cep' =>        $cep,
'endereço' =>   $endereço,
'numero' =>  $numero,
'bairro' =>  $bairro,
'complemento' =>  $complemento,
'cidade' =>  $cidade,
'estado' =>  $estado,
'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'vendedor_id' => $idvendedor,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'B', //Boy "Moto Booy"
'valor' => $valor,
//  'prazo_entrega' => $prazoentrega,
'status' => 'EMB' //Entrega moto boy

]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);



$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.compras');

}

Frete::create([
'pedido_id'  => $id,
// 'produto_id' => $idproduto,
'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'vendedor_id' => $idvendedor,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'B', //Boy "Moto Booy"
'valor' => $valor,
//  'prazo_entrega' => $prazoentrega,
'status' => 'EMB' //Entrega moto boy

]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);




$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.compras');



}


if ($frete == "C")
{

if ($valor == NULL) {
$req->session()->flash('message', 'Informar o custo do frete!');
return redirect()->route('index')->withInput();


}


if (isset($local))

{

Frete::create([
'pedido_id'  => $id,
// 'produto_id' => $idproduto,
'local' => $local,
'cep' =>        $cep,
'endereço' =>   $endereço,
'numero' =>  $numero,
'bairro' =>  $bairro,
'complemento' =>  $complemento,
'cidade' =>  $cidade,
'estado' =>  $estado,
'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'vendedor_id' => $idvendedor,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'B', //Boy "Moto Booy"
'valor' => $valor,
'prazo_entrega' => $prazoentrega,
'serviço_correio' => $cdservico,
'status' => 'EMB' //Entrega moto boy

]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);

$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.compras');

}



Frete::create([
'pedido_id'  => $id,

// 'produto_id' => $idproduto,

'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'vendedor_id' => $idvendedor,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'C', //Correios
'valor' => $valor,
'prazo_entrega' => $prazoentrega,
'serviço_correio' => $cdservico,
'status' => 'EC' //Entrega Correios

]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);

$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.compras');


}






if ($frete == NULL && $retirada == 'Y' ) {

Frete::create([
'pedido_id'  => $id,
// 'produto_id' => $idproduto,
'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'vendedor_id' => $idvendedor,
'boolean' => 'Y',
'balcao' => 'Y',
'entrega' => NULL,
'status' => 'AR' //Aguardando Retirada

]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);


$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.compras');



}






}



public function detalhesConsig($id) {

$pedidos = Pedido::findOrFail($id);
$this->middleware('VerifyCsrfToken');
$req = Request();
$local = $req->input('local');
$cep = $req->input('cep');// CEP de destino lterado
$endereço = $req->input('endereço');
$numero = $req->input('numero');
$bairro = $req->input('bairro');
$complemento = $req->input('complemento');
$cidade = $req->input('cidade');
$estado = $req->input('estado');
$obspedido = $req->input('obs_pedido');
//$idcliente = $req->input('id_cliente');
$idvendedor = $req->input('vendedor_id');
$idusuario = $req->input('user_id');
$comissao = $req->input('comissao');

$prazoentrega = $req->input('prazo_entrega');
$prazoentrega = str_replace( ' ', '', str_replace('Dias', '', $prazoentrega));
// $prazoentrega = $prazoentrega + 2;
$cdservico = $req->input('cdservico');

// $idproduto = $req->input('id');
$retirada = $req->input('balcao');
$frete = $req->input('entrega');
// $status = $req->input('status');
$valor = $req->input('valor');
$valor = str_replace( ',', '.', $valor );
$pagamento = $req->input('pagamento');
//$cdservico = $req->input('cdservico');
$idusuario = Auth::id();

$getcomissao = Pedido::find($id);
$percent = $getcomissao->percentual_comissao;



$pedidossearch = Pedido::select('id')->where([
'id' => $id
])->pluck('id');

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');

$desconto_produtos = $produtossearch->sum('prod_desconto');

$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;


//$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
//$frete_total = $frete->sum('valor');

$geral = $soma_produtos - $desconto;

$total_preco = $geral;

$Calculopercent = $percent * $total_preco/100;
$Calculopercent = number_format($Calculopercent, 2, '.', '');

// $cepOrigem = "09090520"; //CEP de Origem!!!!!!!!!


/* $produto = Produto::find($idproduto);
if( empty($produto->id) ) {
$req->session()->flash('mensagem-falha', 'Produto não encontrado em nossa loja!');
return redirect()->route('pedidos.index');
}*/




//  $check_cep = Cliente::select('cep')->where('id', '=', $idcliente)->get(); //CEP de destino!!!!!!!!!


$check_pedido = Pedido::where([
'id'      => $id,
// 'user_id' => $idusuario,
'status'  => 'GE' // Gerado
])->exists();

if( !$check_pedido ) {
$req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
return redirect()->route('index.consignado');
}


$idpedido = Pedido::consultaId([
'id' => $id,
'status'  => 'GE' // GERADO
]);

if( isset($idpedido) ) {

if( empty($pagamento) ) {
//  dd($pagamento);
$req->session()->flash('message', 'Preencha a forma de Pagamento!');
return redirect()->route('index.consignado')->withInput();
}

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id,
'status'  => 'GE'
])->update([
'pagamento' => $pagamento,
'vendedor_id' => $idvendedor,
'obs_pedido' => $obspedido

]);

Comissao::create([

'pedido_id' => $id,
//'cliente_id' => $idcliente,
'vendedor_id' => $idvendedor,
'status' => 'PE',
'valor_comissao' => $Calculopercent,
'percentual_comissao' => $percent,
//'obs_comissao'
'user_id' => $idusuario
]);

} /*END ISSET PEDIDO*/





if ($retirada == NULL && $frete == NULL)
{
$req->session()->flash('message', 'Informe o tipo de frete!');
return redirect()->route('index.consignado')->withInput();

}




if ($frete == "B")
{

if ($valor == NULL) {
$req->session()->flash('message', 'Informar o custo do frete!');
return redirect()->route('index.consignado')->withInput();
}


if (isset($local))
{

Frete::create([
'pedido_id'  => $id,
// 'produto_id' => $idproduto,
'local' => $local,
'cep' =>        $cep,
'endereço' =>   $endereço,
'numero' =>  $numero,
'bairro' =>  $bairro,
'complemento' =>  $complemento,
'cidade' =>  $cidade,
'estado' =>  $estado,
//'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'vendedor_id' => $idvendedor,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'B', //Boy "Moto Booy"
'valor' => $valor,
//  'prazo_entrega' => $prazoentrega,
'status' => 'EMB' //Entrega moto boy

]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);



$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.consignado');

}

Frete::create([
'pedido_id'  => $id,
// 'produto_id' => $idproduto,
//'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'vendedor_id' => $idvendedor,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'B', //Boy "Moto Booy"
'valor' => $valor,
//  'prazo_entrega' => $prazoentrega,
'status' => 'EMB' //Entrega moto boy

]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);




$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.consignado');



}


if ($frete == "C")
{

if ($valor == NULL) {
$req->session()->flash('message', 'Informar o custo do frete!');
return redirect()->route('index.consignado')->withInput();


}


if (isset($local))

{

Frete::create([
'pedido_id'  => $id,
// 'produto_id' => $idproduto,
'local' => $local,
'cep' =>        $cep,
'endereço' =>   $endereço,
'numero' =>  $numero,
'bairro' =>  $bairro,
'complemento' =>  $complemento,
'cidade' =>  $cidade,
'estado' =>  $estado,
//'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'vendedor_id' => $idvendedor,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'B', //Boy "Moto Booy"
'valor' => $valor,
'prazo_entrega' => $prazoentrega,
'serviço_correio' => $cdservico,
'status' => 'EMB' //Entrega moto boy

]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);

$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.consignado');

}



Frete::create([
'pedido_id'  => $id,

// 'produto_id' => $idproduto,

//'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'vendedor_id' => $idvendedor,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'C', //Correios
'valor' => $valor,
'prazo_entrega' => $prazoentrega,
'serviço_correio' => $cdservico,
'status' => 'EC' //Entrega Correios

]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);

$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.consignado');


}






if ($frete == NULL && $retirada == 'Y' ) {

Frete::create([
'pedido_id'  => $id,
// 'produto_id' => $idproduto,
//'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'vendedor_id' => $idvendedor,
'boolean' => 'Y',
'balcao' => 'Y',
'entrega' => NULL,
'status' => 'AR' //Aguardando Retirada

]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);


$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.consignado');


}






}





public function detalhesEdit($id) {


$pedidos = Pedido::findOrFail($id);
$this->middleware('VerifyCsrfToken');
$req = Request();
$local = $req->input('local');
$cep = $req->input('cep');// CEP de destino lterado
$endereço = $req->input('endereço');
$numero = $req->input('numero');
$bairro = $req->input('bairro');
$complemento = $req->input('complemento');
$cidade = $req->input('cidade');
$estado = $req->input('estado');
$obspedido = $req->input('obs_pedido');
$idcliente = $req->input('id_cliente');
$idvendedor = $req->input('vendedor_id');
$idusuario = $req->input('user_id');

$prazoentrega = $req->input('prazo_entrega');
$prazoentrega = str_replace( ' ', '', str_replace('Dias', '', $prazoentrega));
// $prazoentrega = $prazoentrega + 2;
$cdservico = $req->input('cdservico');

// $idproduto = $req->input('id');
$retirada = $req->input('balcao');
$frete = $req->input('entrega');
// $status = $req->input('status');
$valor = $req->input('valor');
$valor = str_replace( ',', '.', $valor );
$pagamento = $req->input('pagamento');
$cdservico = $req->input('cdservico');
$idusuario = Auth::id();
/* $dataAtual = $req->input('dataAtual');
$dataRegistro = $req->input('dataRegistro');*/
// dd($valor);
// $cepOrigem = "09090520"; //CEP de Origem!!!!!!!!!


/* $produto = Produto::find($idproduto);
if( empty($produto->id) ) {
$req->session()->flash('mensagem-falha', 'Produto não encontrado em nossa loja!');
return redirect()->route('pedidos.index');
}*/




//  $check_cep = Cliente::select('cep')->where('id', '=', $idcliente)->get(); //CEP de destino!!!!!!!!!

/*   if ($dataAtual > $dataRegistro){
$req->session()->flash('mensagem-falha', 'Pedido não pode ser alterado!');
return redirect()->route('pedidos/{id}/edit', $id);
}*/


$check_pedido = Pedido::where([
'id'      => $id
//  'user_id' => $idusuario,
//'status'  => 'RE' // Reservado
])->exists();

if( !$check_pedido ) {
$req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
return redirect()->route('pedidos/{id}/edit', $id);
}


$idpedido = Pedido::consultaId([
'id' => $id,
'status'  => 'RE' // GERADO
]);

if( isset($idpedido) ) {

if( empty($pagamento) ) {
//  dd($pagamento);
$req->session()->flash('message', 'Preencha a forma de Pagamento!');
return redirect()->route('pedidos/{id}/edit', $id)->withInput();
}

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id,
'status'  => 'RE'
])->update([
'pagamento' => $pagamento,
//  'vendedor_id' => $idvendedor,
'obs_pedido' => $obspedido

]);

} /*END ISSET PEDIDO*/





if ($retirada == NULL && $frete == NULL)
{
$req->session()->flash('message', 'Informe o tipo de frete!');
return redirect()->route('pedidos/{id}/edit', $id)->withInput();

}




if ($frete == "B")
{

if ($valor == NULL) {
$req->session()->flash('message', 'Informar o custo do frete!');
return redirect()->route('pedidos/{id}/edit', $id)->withInput();
}


if (isset($local))
{

Frete::where([
'pedido_id'  => $id

])->update([
// 'produto_id' => $idproduto,
'local' => $local,
'cep' =>        $cep,
'endereço' =>   $endereço,
'numero' =>  $numero,
'bairro' =>  $bairro,
'complemento' =>  $complemento,
'cidade' =>  $cidade,
'estado' =>  $estado,
'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'B', //Boy "Moto Booy"
'valor' => $valor,
'prazo_entrega' => $prazoentrega,
'status' => 'EMB' //Entrega moto boy

]);

Pedido::where([
//  'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);



$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.compras');

}

Frete::where([
'pedido_id'  => $id

])->update([
// 'produto_id' => $idproduto,
'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'B', //Boy "Moto Booy"
'valor' => $valor,
'prazo_entrega' => $prazoentrega,
'status' => 'EMB' //Entrega moto boy


]);

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);




$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.compras');



}


if ($frete == "C")
{

if ($valor == NULL) {
$req->session()->flash('message', 'Informar o custo do frete!');
return redirect()->route('index')->withInput();


}


if (isset($local))

{

Frete::where([
'pedido_id'  => $id


])->update([
// 'produto_id' => $idproduto,
'local' => $local,
'cep' =>        $cep,
'endereço' =>   $endereço,
'numero' =>  $numero,
'bairro' =>  $bairro,
'complemento' =>  $complemento,
'cidade' =>  $cidade,
'estado' =>  $estado,
'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'C', //Correis
'valor' => $valor,
'prazo_entrega' => $prazoentrega,
'serviço_correio' => $cdservico,
'status' => 'EC' //Entrega Correios


]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);

$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.compras');

}



Frete::where([
'pedido_id'  => $id,


])->update([
// 'produto_id' => $idproduto,

'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'C', //Correios
'valor' => $valor,
'prazo_entrega' => $prazoentrega,
'serviço_correio' => $cdservico,
'status' => 'EC' //Entrega Correios

]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);

$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.compras');


}






if ($frete == NULL && $retirada == 'Y' ) {

Frete::where([
'pedido_id'  => $id

])->update([
// 'produto_id' => $idproduto,
'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'boolean' => 'Y',
'balcao' => 'Y',
'entrega' => NULL,
'valor' => NULL,
'status' => 'AR' //Aguardando Retirada



]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);


$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.compras');



}






}



public function detalhesEditConsig($id) {






$pedidos = Pedido::findOrFail($id);
$this->middleware('VerifyCsrfToken');
$req = Request();
$local = $req->input('local');
$cep = $req->input('cep');// CEP de destino lterado
$endereço = $req->input('endereço');
$numero = $req->input('numero');
$bairro = $req->input('bairro');
$complemento = $req->input('complemento');
$cidade = $req->input('cidade');
$estado = $req->input('estado');
$obspedido = $req->input('obs_pedido');
//$idcliente = $req->input('id_cliente');
$idvendedor = $req->input('vendedor_id');
$idusuario = $req->input('user_id');

$prazoentrega = $req->input('prazo_entrega');
$prazoentrega = str_replace( ' ', '', str_replace('Dias', '', $prazoentrega));
// $prazoentrega = $prazoentrega + 2;
$cdservico = $req->input('cdservico');

// $idproduto = $req->input('id');
$retirada = $req->input('balcao');
$frete = $req->input('entrega');
// $status = $req->input('status');
$valor = $req->input('valor');
$valor = str_replace( ',', '.', $valor );
$pagamento = $req->input('pagamento');
//$cdservico = $req->input('cdservico');
$idusuario = Auth::id();
/* $dataAtual = $req->input('dataAtual');
$dataRegistro = $req->input('dataRegistro');*/
// dd($valor);
// $cepOrigem = "09090520"; //CEP de Origem!!!!!!!!!


/* $produto = Produto::find($idproduto);
if( empty($produto->id) ) {
$req->session()->flash('mensagem-falha', 'Produto não encontrado em nossa loja!');
return redirect()->route('pedidos.index');
}*/




//  $check_cep = Cliente::select('cep')->where('id', '=', $idcliente)->get(); //CEP de destino!!!!!!!!!

/*   if ($dataAtual > $dataRegistro){
$req->session()->flash('mensagem-falha', 'Pedido não pode ser alterado!');
return redirect()->route('pedidos/{id}/edit', $id);
}*/


$check_pedido = Pedido::where([
'id'      => $id,
//  'user_id' => $idusuario,
'status'  => 'RE' // Reservado
])->exists();

if( !$check_pedido ) {
$req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
return redirect()->route('pedidos/{id}/consig/edit', $id);
}


$idpedido = Pedido::consultaId([
'id' => $id,
'status'  => 'RE' // GERADO
]);

if( isset($idpedido) ) {

if( empty($pagamento) ) {
//  dd($pagamento);
$req->session()->flash('message', 'Preencha a forma de Pagamento!');
return redirect()->route('pedidos/{id}/consig/edit', $id)->withInput();
}

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id,
'status'  => 'RE'
])->update([
'pagamento' => $pagamento,
//  'vendedor_id' => $idvendedor,
'obs_pedido' => $obspedido

]);

} /*END ISSET PEDIDO*/





if ($retirada == NULL && $frete == NULL)
{
$req->session()->flash('message', 'Informe o tipo de frete!');
return redirect()->route('pedidos/{id}/consig/edit', $id)->withInput();

}




if ($frete == "B")
{

if ($valor == NULL) {
$req->session()->flash('message', 'Informar o custo do frete!');
return redirect()->route('pedidos/{id}/consig/edit', $id)->withInput();
}


if (isset($local))
{

Frete::where([
'pedido_id'  => $id

])->update([
// 'produto_id' => $idproduto,
'local' => $local,
'cep' =>        $cep,
'endereço' =>   $endereço,
'numero' =>  $numero,
'bairro' =>  $bairro,
'complemento' =>  $complemento,
'cidade' =>  $cidade,
'estado' =>  $estado,
//'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'B', //Boy "Moto Booy"
'valor' => $valor,
'prazo_entrega' => $prazoentrega,
'status' => 'EMB' //Entrega moto boy

]);

Pedido::where([
//  'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);



$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.consignado');

}

Frete::where([
'pedido_id'  => $id

])->update([
// 'produto_id' => $idproduto,
//'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'B', //Boy "Moto Booy"
'valor' => $valor,
'prazo_entrega' => $prazoentrega,
'status' => 'EMB' //Entrega moto boy


]);

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);




$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.consignado');



}


if ($frete == "C")
{

if ($valor == NULL) {
$req->session()->flash('message', 'Informar o custo do frete!');
return redirect()->route('index.consignado')->withInput();


}


if (isset($local))

{

Frete::where([
'pedido_id'  => $id


])->update([
// 'produto_id' => $idproduto,
'local' => $local,
'cep' =>        $cep,
'endereço' =>   $endereço,
'numero' =>  $numero,
'bairro' =>  $bairro,
'complemento' =>  $complemento,
'cidade' =>  $cidade,
'estado' =>  $estado,
//'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'B', //Boy "Moto Booy"
'valor' => $valor,
'prazo_entrega' => $prazoentrega,
'serviço_correio' => $cdservico,
'status' => 'EMB' //Entrega moto boy


]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);

$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.consignado');

}



Frete::where([
'pedido_id'  => $id,


])->update([
// 'produto_id' => $idproduto,

//'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'C', //Correios
'valor' => $valor,
'prazo_entrega' => $prazoentrega,
'serviço_correio' => $cdservico,
'status' => 'EC' //Entrega Correios

]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);

$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.consignado');


}






if ($frete == NULL && $retirada == 'Y' ) {

Frete::where([
'pedido_id'  => $id

])->update([
// 'produto_id' => $idproduto,
//'id_cliente' =>  $idcliente,
'user_id' =>   $idusuario,
'boolean' => 'Y',
'balcao' => 'Y',
'entrega' => NULL,
'valor' => NULL,
'status' => 'AR' //Aguardando Retirada



]);

Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);


$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedido.consignado');



}






}



public function infoFrete(Request $request) {

//  $this->middleware('VerifyCsrfToken');

//$req = Request();

// $id  = $req->input('infoFrete');
// dd($id);

$idpedido =  $request->pedido_id_load;


$cep_alt =  $request->cep_alter;

$cdservico =  $request->cdservico_alt;

if (empty($cdservico)){

$cdservico_vazio = "Preencha o Serviço!";


return $cdservico_vazio;

}






//     $pedidos = Pedido::findOrFail($id);


//  $id = Pedido::select('id')->where('id', $request->pedido_id_load)->first();

$idcliente = Pedido::select('id_cliente')->where('id', '=', $idpedido)->pluck('id_cliente');

// $idcliente = Pedido::select('id_cliente')->where('id', '=', $id)->get();

// $cep_destino_alt = Frete::select('cep')->where('pedido_id', '=', $idpedido)->pluck('cep');

//   if (empty($cep_destino_alt)){


if (empty($cep_alt)){

$cepdestino = Cliente::select('cep')->where('id', '=', $idcliente)->pluck('cep');

$cepdestino = str_replace('-', '',  $cepdestino);

$cepdestino = preg_replace("/[^0-9]/", "", $cepdestino);




} else {


$cepdestino =  $cep_alt;

$cepdestino = str_replace('-', '',  $cepdestino);

$cepdestino = preg_replace("/[^0-9]/", "", $cepdestino);


}








//  $cepdestino = Frete::select('cep')->where('pedido_id', '=', $idpedido)->pluck('cep');




//  dd($cepdestino);

//  $cepdestino = '04159000';

$ceporigem = '09090520';
$peso = '0.3';
$comprimento = '20';
$altura = '20';
$largura = '20';
$diametro = '0';
$formato = 1;
$maopropria = 'N';
$valordeclarado = 0;
$avisorecebimento = 'N';
$tiporetorno = 'xml';
$indicacalculo = 3;
$cdempresa = '16148185';
$cdsenha = '05304588';
//$cdservico = '4162';
//$cdservico = '04014';


$url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=16148185&sDsSenha=05304588&sCepOrigem=".$ceporigem."&sCepDestino=".$cepdestino."&nVlPeso=".$peso."&nCdFormato=".$formato."&nVlComprimento=".$comprimento."&nVlAltura=".$altura."&nVlLargura=".$largura."&sCdMaoPropria=".$maopropria."&nVlValorDeclarado=".$valordeclarado."&sCdAvisoRecebimento=".$avisorecebimento."&nCdServico=".$cdservico."&nVlDiametro=".$diametro."&StrRetorno=".$tiporetorno."&nIndicaCalculo=".$indicacalculo;





//link do arquivo xml

//carrega o arquivo XML e retornando um Array
$xml = simplexml_load_file($url);

/*if($xml->cServico->Erro == '0') {
$sedex = array(
"codigo"    => $xml->cServico->Codigo,
"valor"     => $xml->cServico->Valor,
"prazo_entrega" => $xml->cServico->PrazoEntrega,
"aviso_recebto" => $xml->cServico->ValorAvisoRecebimento,
"erro"      => $xml->cServico->Erro,
"msg_erro"    => $xml->cServico->MsgErro,
);
return $sedex;*/




$data = $xml->cServico->Valor;

if ($data == '0,00'){

return  $xml->cServico->MsgErro;


} else {




return $xml->cServico->Valor;// $xml->cServico->PrazoEntrega;


}

// $data1 = $xml->cServico->MsgErro
//   return response()->json($data);

// return response()->json($url);

//return response()->file($pathToFile);


// return redirect()->away($url);








}



public function infoFretePrazoEntrega(Request $request) {

//  $this->middleware('VerifyCsrfToken');

//$req = Request();

// $id  = $req->input('infoFrete');
// dd($id);

$idpedido =  $request->pedido_id_load;


$cep_alt =  $request->cep_alter;

$cdservico =  $request->cdservico_alt;

if (empty($cdservico)){

$cdservico_vazio = "Preencha o Serviço!";


return $cdservico_vazio;

}






//     $pedidos = Pedido::findOrFail($id);


//  $id = Pedido::select('id')->where('id', $request->pedido_id_load)->first();

$idcliente = Pedido::select('id_cliente')->where('id', '=', $idpedido)->pluck('id_cliente');

// $idcliente = Pedido::select('id_cliente')->where('id', '=', $id)->get();

// $cep_destino_alt = Frete::select('cep')->where('pedido_id', '=', $idpedido)->pluck('cep');

//   if (empty($cep_destino_alt)){


if (empty($cep_alt)){

$cepdestino = Cliente::select('cep')->where('id', '=', $idcliente)->pluck('cep');

$cepdestino = str_replace('-', '',  $cepdestino);

$cepdestino = preg_replace("/[^0-9]/", "", $cepdestino);




} else {


$cepdestino =  $cep_alt;

$cepdestino = str_replace('-', '',  $cepdestino);

$cepdestino = preg_replace("/[^0-9]/", "", $cepdestino);


}








//  $cepdestino = Frete::select('cep')->where('pedido_id', '=', $idpedido)->pluck('cep');




//  dd($cepdestino);

//  $cepdestino = '04159000';

$ceporigem = '09090520';
$peso = '0.3';
$comprimento = '20';
$altura = '20';
$largura = '20';
$diametro = '0';
$formato = 1;
$maopropria = 'N';
$valordeclarado = 0;
$avisorecebimento = 'N';
$tiporetorno = 'xml';
$indicacalculo = 3;
$cdempresa = '16148185';
$cdsenha = '05304588';
//$cdservico = '4162';
//$cdservico = '04014';


$url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=16148185&sDsSenha=05304588&sCepOrigem=".$ceporigem."&sCepDestino=".$cepdestino."&nVlPeso=".$peso."&nCdFormato=".$formato."&nVlComprimento=".$comprimento."&nVlAltura=".$altura."&nVlLargura=".$largura."&sCdMaoPropria=".$maopropria."&nVlValorDeclarado=".$valordeclarado."&sCdAvisoRecebimento=".$avisorecebimento."&nCdServico=".$cdservico."&nVlDiametro=".$diametro."&StrRetorno=".$tiporetorno."&nIndicaCalculo=".$indicacalculo;





//link do arquivo xml

//carrega o arquivo XML e retornando um Array
$xml = simplexml_load_file($url);

/*if($xml->cServico->Erro == '0') {
$sedex = array(
"codigo"    => $xml->cServico->Codigo,
"valor"     => $xml->cServico->Valor,
"prazo_entrega" => $xml->cServico->PrazoEntrega,
"aviso_recebto" => $xml->cServico->ValorAvisoRecebimento,
"erro"      => $xml->cServico->Erro,
"msg_erro"    => $xml->cServico->MsgErro,
);
return $sedex;*/




$data = $xml->cServico->Valor;

if ($data == '0,00'){

return  $xml->cServico->MsgErro;


} else {

$prazo =  $xml->cServico->PrazoEntrega;

$prazo +=2;



return $prazo.' Dias';// $xml->cServico->PrazoEntrega;


}

// $data1 = $xml->cServico->MsgErro
//   return response()->json($data);

// return response()->json($url);

//return response()->file($pathToFile);


// return redirect()->away($url);








}


public function calcularComissao($id)
{
//$comissao = Pedido::findOrFail($id);
$this->middleware('VerifyCsrfToken');
$req = Request();
$idpedido  = $req->input('pedido_id');
$idusuario = Auth::id();
$idvendedor = $req->input('vendedor_id');
$idcliente = $req->input('cliente_id');
//$status = $req->input('status');
//$valor_comissao = $req->input('valor_comissao');
//$valor_comissao = str_replace( ',', '.', $valor_comissao );
$obs_comissao = $req->input('obs_comissao');
$valor_pedido = $req->input('valor_pedido');

$percent_comissao = $req->input('percent_comissao'); //comissao%  
if ($percent_comissao == NULL){
	   $req->session()->flash('mensagem-falha', 'Informe o valor percentual!');
         return redirect()->route('pagar/comissao/{id}', $id)->withInput();
}

$percent_comissao = str_replace( '%', '', $percent_comissao);

if ($percent_comissao > 100)
        {
          $req->session()->flash('mensagem-falha', 'Não é Permitido comissões acima de 100%!');
         return redirect()->route('pagar/comissao/{id}', $id)->withInput();
        }


$valor_comissao = $percent_comissao * $valor_pedido /100;

number_format($valor_comissao, 2, ',', '.');

$check_pedido = Pedido::where([
'id'      => $idpedido,
'vendedor_id' => $idvendedor,
'status'  => 'FI' // Finalizado
])->exists();

if( !$check_pedido ) {
$req->session()->flash('mensagem-falha', 'Pedido não pode ser localizado!');
return redirect()->route('pedido.comissoes');
}



/*if( $valor_comissao == NULL ) {
$req->session()->flash('mensagem-falha', 'Pagamento não foi localizado!');
return redirect()->route('/pagar/comissao/{id}', $id)->withInput();
}*/


/*$pago = Comissao::where([
'pedido_id' => $idpedido,
'vendedor_id' => $idvendedor,
'status' => 'PA'
])->first();

if(isset($pago) ) {
$req->session()->flash('mensagem-falha', 'Comissão não esta disponível para pagamento!');
return redirect()->route('pedido.comissoes')->withInput();
}*/
$idcomissao = Comissao::consultaId([
'pedido_id'	=> $idpedido,
'vendedor_id' => $idvendedor,
'status'  => 'PE' // Pendente
]);

if( empty($idcomissao) ) {
$gera_comissao = Comissao::create([
'user_id' => $idusuario,
'vendedor_id' => $idvendedor,
'pedido_id' => $idpedido,
'obs_comissao' => $obs_comissao,
'cliente_id' => $idcliente,
'percentual_comissao' => $percent_comissao,
'valor_comissao' => $valor_comissao,
'status'  => 'PE' // Pendente
]);

$idcomissao = $gera_comissao->id;

}


Comissao::where([
'id' => $idcomissao
//'user_id' => $idusuario
])->update([
'obs_comissao' => $obs_comissao,
'valor_comissao' => $valor_comissao,
'percentual_comissao' => $percent_comissao
]);


$pedidos = Pedido::find($id);
$pedidos->calculo_comissao = 'S';
$pedidos->timestamps = false;
$pedidos->save();
      

/*Pedido::where([
'id' => $idpedido,
'vendedor_id' => $idvendedor,
])->update([
'calculo_comissao'  => 'S'
]);*/


$req->session()->flash('mensagem-sucesso', 'Calculo Realizado com sucesso!');

return redirect()->route('pagar/comissao/{id}', $id);

}


public function concluirComissao($id)
{
//$comissao = Pedido::findOrFail($id);
$this->middleware('VerifyCsrfToken');
$req = Request();
$idpedido  = $req->input('pedido_id');
$idusuario = Auth::id();
$idvendedor = $req->input('vendedor_id');
$idcliente = $req->input('cliente_id');
//$status = $req->input('status');
//$valor_comissao = $req->input('valor_comissao');
//$valor_comissao = str_replace( ',', '.', $valor_comissao );
$obs_comissao = $req->input('obs_comissao');
$valor_pedido = $req->input('valor_pedido');


$check_pedido = Pedido::where([
'id'      => $idpedido,
'vendedor_id' => $idvendedor,
'status'  => 'FI' // Finalizado
])->exists();

if( !$check_pedido ) {
$req->session()->flash('mensagem-falha', 'Pedido não pode ser localizado!');
return redirect()->route('pedido.comissoes');
}

$idcomissao = Comissao::consultaId([
'pedido_id'	=> $idpedido,
'vendedor_id' => $idvendedor,
'status'  => 'PE' // Pendente
]);

if( empty($idcomissao) ) {
$gera_comissao = Comissao::create([
'user_id' => $idusuario,
'vendedor_id' => $idvendedor,
'pedido_id' => $idpedido,
'obs_comissao' => $obs_comissao,
'cliente_id' => $idcliente,
'valor_comissao' => $valor_comissao,
'status'  => 'PE' // Pendente
]);

$idcomissao = $gera_comissao->id;

}


Comissao::where([
'id' => $idcomissao
//'user_id' => $idusuario
])->update([
'obs_comissao' => $obs_comissao,
'status' => 'PA',
'idGeral' => $idpedido
]);

$pedidos = Pedido::find($id);
$pedidos->comissao = 'PA';
$pedidos->timestamps = false;
$pedidos->save();

/*Pedido::where([
'id' => $idpedido,
'vendedor_id' => $idvendedor,
])->update([
'comissao'  => 'PA'
]);*/


$req->session()->flash('mensagem-sucesso', 'Pagamento Registrado com sucesso!');

return redirect()->route('pedido.comissoes');

}

public function compras()
{

$dadosClientes=DB::table('clientes')->get();

$dadosVendedores=DB::table('vendedores')->get();

$dadosPedidos=DB::table('pedidos')->get();

$ano = date("Y");


$periodo = date("m");

$pedidossearch = Pedido::select('id')->where('consignado', '=', 'N')->
where('status', '=', 'FI')->pluck('id');

$compras = Pedido::where([
'status'  => 'GE',
'user_id' => Auth::id()
])->take(100)->get();

$totalPageSearch = ($compras)->count();

//$produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
//$produtos  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$produtos  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
		->orderBy('quantidade', 'desc')->get();



//$desconto_produtos = DB::table('itens_pedidos')->select('prod_desconto')->get();
$desconto_produtos  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$desconto_request = DB::table('itens_pedidos')->select('request_desconto')->get();
$desconto_request  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$total_desconto_prod = $desconto_produtos->sum('prod_desconto');

$total_desconto_req = $desconto_request->sum('request_desconto');

$desconto = $total_desconto_prod + $total_desconto_req;


$soma_produtos = $produtos->sum('prod_preco_padrao');

//$frete = DB::table('fretes')->select('valor')->get();
$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();

$frete_total = $frete->sum('valor');

$geral = $soma_produtos + $frete_total - $desconto;
//$geral = $soma_produtos - $desconto;

$total_preco = $geral;

// $pedidos = Pedido::where('id', '!=', NULL)->orderBy('id', 'desc')->paginate(7);
$pedidos = Pedido::where('status', '!=', 'GE')->where('consignado', '=', 'N')->whereYear('created_at', $ano)->whereMonth('created_at', $periodo)->get();
//$pedidos = Pedido::all();

//  $dadosClientes= Cliente::where('status', '!=', 'A')->get();

// dd($retirada);
$valorFrete = DB::table('fretes')->select('valor')->where([
//  'user_id' => Auth::id(),
'status' => 'EMB',
'entrega'   => 'B',
'boolean' => 'Y'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
//  'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'Y'
])->get();

/*$cancelados = Pedido::where([
'status'  => 'CA',
'user_id' => Auth::id()
])->orderBy('updated_at', 'desc')->get();*/

$totaladm = Pedido::where([
//  'status'  => 'CA',
'user_id' => Auth::id()
])->get();

//  $total = Pedido::all()->count();
$total = ($totaladm)->count();

return view('admin.pedidoResource.compras', compact('compras',
'dadosPedidos',
'valorFrete',
'valorFreteC',
'pedidos',
'total',
'produtos',
'dadosClientes',
'dadosVendedores',
'totalPageSearch',
'total_preco', 'ano', 'periodo', 'frete_total', 'soma_produtos', 'desconto'));

}

public function itensPedidos()
{

//$dadosClientes=DB::table('clientes')->get();

$dadosClientes = Pedido::where('consignado', '=', 'N')
->where('status', '=', 'FI')
->whereNotNull('produto_id')
->select(\DB::raw('id_cliente as id_cliente'))
->groupBy('id_cliente')
->orderBy('id_cliente', 'asc')
->get();

//dd($dadosClientes);

$dadosVendedores = Pedido::where('consignado', '=', 'N')
->where('status', '=', 'FI')
->whereNotNull('produto_id')
->select(\DB::raw('vendedor_id as vendedor_id'))
->groupBy('vendedor_id')
->orderBy('vendedor_id', 'asc')
->get();

//dd($dadosVendedores);

$dadosPedidos=DB::table('pedidos')->get();

$pedidossearch = Pedido::select('id')->where('consignado', '=', 'N')->
where('status', '=', 'FI')->pluck('id');

$compras = Pedido::where([
'status'  => 'GE',
'user_id' => Auth::id()
])->take(100)->get();

$totalPageSearch = ($compras)->count();

//$produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
//$produtos  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$produtos  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
		->orderBy('quantidade', 'desc')->get();



//$desconto_produtos = DB::table('itens_pedidos')->select('prod_desconto')->get();
$desconto_produtos  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$desconto_request = DB::table('itens_pedidos')->select('request_desconto')->get();
$desconto_request  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$total_desconto_prod = $desconto_produtos->sum('prod_desconto');

$total_desconto_req = $desconto_request->sum('request_desconto');

$desconto = $total_desconto_prod + $total_desconto_req;


$soma_produtos = $produtos->sum('prod_preco_padrao');

//$frete = DB::table('fretes')->select('valor')->get();
$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();

$frete_total = $frete->sum('valor');

$geral = $soma_produtos + $frete_total - $desconto;
//$geral = $soma_produtos - $desconto;

$total_preco = $geral;

// $pedidos = Pedido::where('id', '!=', NULL)->orderBy('id', 'desc')->paginate(7);
$pedidos = Pedido::where('consignado', '=', 'N')->where('status', '=', 'FI')->get();
//$pedidos = Pedido::all();

//  $dadosClientes= Cliente::where('status', '!=', 'A')->get();

// dd($retirada);
$valorFrete = DB::table('fretes')->select('valor')->where([
//  'user_id' => Auth::id(),
'status' => 'EMB',
'entrega'   => 'B',
'boolean' => 'Y'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
//  'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'Y'
])->get();

$consignado = 'N';
$tipo = 'P';

/*$cancelados = Pedido::where([
'status'  => 'CA',
'user_id' => Auth::id()
])->orderBy('updated_at', 'desc')->get();*/

$totaladm = Pedido::where([
//  'status'  => 'CA',
'user_id' => Auth::id()
])->get();

//  $total = Pedido::all()->count();
$total = ($totaladm)->count();

return view('admin.pedidoResource.itens-pedidos', compact('compras',
'dadosPedidos',
'valorFrete',
'consignado',
'tipo',
'valorFreteC',
'pedidos',
'total',
'produtos',
'dadosClientes',
'dadosVendedores',
'totalPageSearch',
'total_preco', 'frete_total', 'soma_produtos', 'desconto'));

}


public function consignado()
{

$dadosClientes=DB::table('clientes')->get();

$dadosVendedores=DB::table('vendedores')->get();

$dadosPedidos=DB::table('pedidos')->get();

$pedidossearch = Pedido::select('id')->where('consignado', '=', 'S')->pluck('id');



$compras = Pedido::where([
'status'  => 'GE',
'user_id' => Auth::id()
])->take(100)->get();

$totalPageSearch = ($compras)->count();

//$produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtos  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$desconto_produtos = DB::table('itens_pedidos')->select('prod_desconto')->get();
$desconto_produtos  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$desconto_request = DB::table('itens_pedidos')->select('request_desconto')->get();
$desconto_request  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$total_desconto_prod = $desconto_produtos->sum('prod_desconto');

$total_desconto_req = $desconto_request->sum('request_desconto');

$desconto = $total_desconto_prod + $total_desconto_req;


$soma_produtos = $produtos->sum('prod_preco_padrao');

//$frete = DB::table('fretes')->select('valor')->get();
$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();

$frete_total = $frete->sum('valor');

$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



/* $idpedido = Pedido::consultaId([
'user_id' => $idusuario,
'status'  => 'RE' // Reservado
]);*/



// $pedidos = Pedido::where('id', '!=', NULL)->orderBy('id', 'desc')->paginate(7);
$pedidos = Pedido::where('consignado', '=', 'S')->where('status', '!=', 'GE')->get();
//$pedidos = Pedido::all();

//  $dadosClientes= Cliente::where('status', '!=', 'A')->get();

// dd($retirada);
$valorFrete = DB::table('fretes')->select('valor')->where([
//  'user_id' => Auth::id(),
'status' => 'EMB',
'entrega'   => 'B',
'boolean' => 'Y'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
//  'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'Y'
])->get();

/*$cancelados = Pedido::where([
'status'  => 'CA',
'user_id' => Auth::id()
])->orderBy('updated_at', 'desc')->get();*/

$totaladm = Pedido::where([
//  'status'  => 'CA',
'user_id' => Auth::id()
])->get();

//  $total = Pedido::all()->count();
$total = ($totaladm)->count();

return view('admin.pedidoResource.consignado', compact('compras',
'dadosPedidos',
'valorFrete',
'valorFreteC',
'pedidos',
'total',
'dadosClientes',
'dadosVendedores',
'totalPageSearch',
'total_preco', 'frete_total', 'soma_produtos', 'desconto'));


}

public function comissoes()
{

$dadosClientes=DB::table('clientes')->get();

$dadosVendedores=DB::table('vendedores')->get();

$dadosPedidos=DB::table('pedidos')->get();

$ano = date("Y");

$periodo = date("m");

$compras = Pedido::where([
//  'status'  => 'RE'
'user_id' => Auth::id()
])->take(100)->get();

$totalPageSearch = ($compras)->count();

$pedidossearch = Pedido::select('id')->where('status', '!=', 'GE')->where(['consignado' => "N"])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');



$produtos  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();

//$desconto_produtos = DB::table('itens_pedidos')->select('prod_desconto')->get();

$desconto_produtos  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$desconto_request = DB::table('itens_pedidos')->select('request_desconto')->get();

$desconto_request  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$total_desconto_prod = $desconto_produtos->sum('prod_desconto');

$total_desconto_req = $desconto_request->sum('request_desconto');

$desconto = $total_desconto_prod + $total_desconto_req;


$total_produtos = $produtos->sum('prod_preco_padrao');

//$frete = DB::table('fretes')->select('valor')->get();

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();

$frete_total = $frete->sum('valor');

$geral_prod = $total_produtos;

$total_preco_prod = $geral_prod;

//$total = $total_produtos + $frete_total - $desconto;
$total = $total_produtos - $desconto;

$comissoes  = Comissao::whereIn('pedido_id', $pedidossearch)->get();


$total_comissoes = $comissoes->sum('valor_comissao');

$geral = $total_comissoes;

$total_preco = $geral;



/* $idpedido = Pedido::consultaId([
'user_id' => $idusuario,
'status'  => 'RE' // Reservado
]);*/




// $pedidos = Pedido::where('id', '!=', NULL)->orderBy('id', 'desc')->paginate(7);
$pedidos = Pedido::where('status', '!=', 'GE')->where(['consignado' => "N"])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->get();


//$pedidos = Pedido::all();

//  $dadosClientes= Cliente::where('status', '!=', 'A')->get();

// dd($retirada);
$valorFrete = DB::table('fretes')->select('valor')->where([
//  'user_id' => Auth::id(),
'status' => 'EMB',
'entrega'   => 'B',
'boolean' => 'Y'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
//  'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'Y'
])->get();

$cancelados = Pedido::where([
'status'  => 'CA',
'user_id' => Auth::id()
])->orderBy('updated_at', 'desc')->get();

$totaladm = Pedido::where([
//  'status'  => 'CA',
'user_id' => Auth::id()
])->get();

//  $total = Pedido::all()->count();
//$total = ($totaladm)->count();

return view('admin.pedidoResource.comissoes', compact('compras',
'dadosPedidos',
'valorFrete',
'valorFreteC',
'ano',
'periodo',
'pedidos',
'cancelados',
'dadosClientes',
'dadosVendedores',
'totalPageSearch',
'total_preco', 'total_preco_prod', 'desconto', 'total', 'frete_total'));

}


public function allcomissoes()
{

$dadosClientes=DB::table('clientes')->get();

$dadosVendedores=DB::table('vendedores')->get();

$dadosPedidos=DB::table('pedidos')->get();



$compras = Pedido::where([
//  'status'  => 'RE'
'user_id' => Auth::id()
])->take(100)->get();

$totalPageSearch = ($compras)->count();

$pedidossearch = Pedido::select('id')->where('status', '!=', 'GE')->where(['consignado' => "N"])->pluck('id');

$produtos  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();

//$desconto_produtos = DB::table('itens_pedidos')->select('prod_desconto')->get();

$desconto_produtos  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$desconto_request = DB::table('itens_pedidos')->select('request_desconto')->get();

$desconto_request  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$total_desconto_prod = $desconto_produtos->sum('prod_desconto');

$total_desconto_req = $desconto_request->sum('request_desconto');

$desconto = $total_desconto_prod + $total_desconto_req;


$total_produtos = $produtos->sum('prod_preco_padrao');

//$frete = DB::table('fretes')->select('valor')->get();

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();

$frete_total = $frete->sum('valor');

$geral_prod = $total_produtos;

$total_preco_prod = $geral_prod;

//$total = $total_produtos + $frete_total - $desconto;
$total = $total_produtos - $desconto;

$comissoes  = Comissao::whereIn('pedido_id', $pedidossearch)->get();


$total_comissoes = $comissoes->sum('valor_comissao');

$geral = $total_comissoes;

$total_preco = $geral;



/* $idpedido = Pedido::consultaId([
'user_id' => $idusuario,
'status'  => 'RE' // Reservado
]);*/




// $pedidos = Pedido::where('id', '!=', NULL)->orderBy('id', 'desc')->paginate(7);
$pedidos = Pedido::where('status', '!=', 'GE')->where(['consignado' => "N"])->get();
//$pedidos = Pedido::all();

//  $dadosClientes= Cliente::where('status', '!=', 'A')->get();

// dd($retirada);
$valorFrete = DB::table('fretes')->select('valor')->where([
//  'user_id' => Auth::id(),
'status' => 'EMB',
'entrega'   => 'B',
'boolean' => 'Y'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
//  'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'Y'
])->get();

$cancelados = Pedido::where([
'status'  => 'CA',
'user_id' => Auth::id()
])->orderBy('updated_at', 'desc')->get();

$totaladm = Pedido::where([
//  'status'  => 'CA',
'user_id' => Auth::id()
])->get();

//  $total = Pedido::all()->count();
//$total = ($totaladm)->count();

return view('admin.pedidoResource.comissoes', compact('compras',
'dadosPedidos',
'valorFrete',
'valorFreteC',
'pedidos',
'cancelados',
'dadosClientes',
'dadosVendedores',
'totalPageSearch',
'total_preco', 'total_preco_prod', 'desconto', 'total', 'frete_total'));

}


public function pagarComissao($id) {
$registros = Produto::where([
'ativo' => 's'
])->get();

$list_requisitions= OpycosRequest::where('status','=','FI')->get();


$pedidos = Pedido::findOrFail($id);

$retiradaBalcPF = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPF',
//   'entrega' => 'BPF',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();

// $dadosClientes= Frete::where('status', '!=', 'A')->get();
// dd($retiradaBalcPF);

$retiradaBalcPJ = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPJ',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();



// dd($retirada);

$freteB_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPF',
'boolean' => 'N'

])->get();

$freteB_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPJ',
'boolean' => 'N'
])->get();


$freteC_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPF',
'boolean' => 'N'
])->get();

$freteC_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPJ',
'boolean' => 'N'
])->get();

$valorFrete = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'N'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'N'
])->get();





$itenspedido = ItensPedido::where($id);


return view('admin.pedidoResource.pagar-comissao', compact('pedidos', 'registros', 'itenspedido', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions'));
}


public function pagarComissoes() {



$this->middleware('VerifyCsrfToken');

$req = Request();
$idSpedido = $req->input('id');
$idusuario      = Auth::id();
$idvendedor = $req->input('vendedor_id');
$idcliente = $req->input('id_cliente');
$idgeral = $req->input('idGeral');
//dd($idgeral);


if( empty($idSpedido) ) {
$req->session()->flash('mensagem-falha', 'Nenhum pedido selecionado para Pagamento!');
return redirect()->route('pedido.comissoes');
}


$check_pedidos = Comissao::where([
'status'    => 'PE' //Pendente
])->whereIn('pedido_id', $idSpedido)->exists();

if( !$check_pedidos ) {
$req->session()->flash('mensagem-falha', 'Os pedidos em questão não estão disponíveis para pagamento!');
return redirect()->route('pedido.comissoes');
}


$pedidos = Pedido::find($idSpedido);


foreach ($pedidos as $pedido) {
$pedido->comissao = 'PA';
$pedido->timestamps = false;
$pedido->save();
      
}


Comissao::where([
'status' => 'PE'
])->whereIn('pedido_id', $idSpedido)->update([
'idGeral' => $idgeral,
'status' => 'PA' //Cancelado
// 'prod_preco_padrao' => 0.00
]);
        
            



$req->session()->flash('mensagem-sucesso', 'Pagamento de comissão realizado com sucesso!');

return redirect()->route('pedido.comissoes');



}


/*public function pagarComissoes() {


$this->middleware('VerifyCsrfToken');

$req = Request();

$idSpedido = $req->input('id');
$idusuario      = Auth::id();
$idvendedor = $req->input('vendedor_id');
$idcliente = $req->input('id_cliente');
$valor_comissao = $req->input('valor_comissao');
$percent_comissao = $req->input('percentual_comissao');

if( empty($idSpedido) ) {
$req->session()->flash('mensagem-falha', 'Nenhum pedido selecionado para Pagamento!');
return redirect()->route('pedido.comissoes');
}





foreach( $idSpedido as $key => $id ) {

$cliente = $idcliente[ $key ];
$vendedor = $idvendedor[ $key ];
$comissao = $valor_comissao[ $key ];
$percComissao = $percent_comissao[ $key ];

Comissao::create([

'pedido_id' => $id,
'cliente_id' => $cliente,
'vendedor_id' => $vendedor,
'status' => 'PE',
'valor_comissao' => $comissao,
'percentual_comissao' => $percComissao,
//'obs_comissao'
'user_id' => $idusuario
]);
        
            
}



$req->session()->flash('mensagem-sucesso', 'Lote de pagamento de comissão realizado com sucesso!');

return redirect()->route('pedido.comissoes');









Comissao::create([

'pedido_id' => $idSpedido,
'cliente_id' => $cliente,
'vendedor_id' => $idvendedor,
'status' => 'PA',
//'valor_comissao'
//'obs_comissao'
'user_id' => $idusuario
]);       
            




$req->session()->flash('mensagem-sucesso', 'Lote de pagamento de comissão realizado com sucesso!');



return redirect()->route('pedido.comissoes');


}*/





public function comprasVendedor()
{


$dadosVendedores=DB::table('vendedores')->get();




$compras = Pedido::where([
//  'status'  => 'RE'
'vendedor_id' => Auth::id()
])->orderBy('id', 'desc')->paginate(7);

$totalPage = ($compras)->count();

$idvendedor = Auth::id();


$pedidoVendedor = Pedido::select('id')->where('vendedor_id', '=', $idvendedor)->pluck('id');

$produtos = ItensPedido::select('prod_preco_padrao')->whereIn('pedido_id', $pedidoVendedor)->get();

$frete  = Frete::whereIn('pedido_id', $pedidoVendedor)->get();

$frete_total = $frete->sum('valor');

$total_produtos = $produtos->sum('prod_preco_padrao');

$geral = $total_produtos + $frete_total;
$total_preco = $geral;






$pedidos = Pedido::where([
// 'status'  => 'RE',
'vendedor_id' => Auth::id()
])->orderBy('id', 'desc')->paginate(7);

$dadosClientes = Cliente::where('status', '!=', 'A')->get();


$valorFrete = DB::table('fretes')->select('valor')->where([
'vendedor_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'Y'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
'vendedor_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'Y'
])->get();


$cancelados = Pedido::where([
'status'  => 'CA',
'vendedor_id' => Auth::id()
])->orderBy('updated_at', 'desc')->get();

$total = Pedido::where('vendedor_id', '=', $idvendedor)->count();

return view('vendedor.pedidoResource.compras', compact('compras',
'idvendedor',
'pedidos',
'cancelados',
'total',
'dadosClientes',
'dadosVendedores',
'totalPage',
'total_preco'));

}



public function pedidoPdf($id)
{
$registros = Produto::where([
'ativo' => 's'
])->get();

$list_requisitions= OpycosRequest::where('status','=','FI')->get();


$pedidos = Pedido::findOrFail($id);

$retiradaBalcPF = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPF',
//   'entrega' => 'BPF',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();

// $dadosClientes= Frete::where('status', '!=', 'A')->get();
// dd($retiradaBalcPF);

$retiradaBalcPJ = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPJ',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();



// dd($retirada);

$freteB_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPF',
'boolean' => 'N'

])->get();

$freteB_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPJ',
'boolean' => 'N'
])->get();


$freteC_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPF',
'boolean' => 'N'
])->get();

$freteC_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPJ',
'boolean' => 'N'
])->get();

$valorFrete = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'N'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'N'
])->get();



$itenspedido = ItensPedido::where($id);


return PDF::loadView('admin.pedidoResource.pdf-compras', compact('pedidos', 'registros', 'itenspedido', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions'))->stream('PedidosOpycos.pdf');



}


public function pedidoConsigPdf($id)
{
$registros = Produto::where([
'ativo' => 's'
])->get();

$list_requisitions= OpycosRequest::where('status','=','FI')->get();


$pedidos = Pedido::findOrFail($id);

$retiradaBalcPF = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPF',
//   'entrega' => 'BPF',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();

// $dadosClientes= Frete::where('status', '!=', 'A')->get();
// dd($retiradaBalcPF);

$retiradaBalcPJ = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPJ',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();



// dd($retirada);

$freteB_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPF',
'boolean' => 'N'

])->get();

$freteB_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPJ',
'boolean' => 'N'
])->get();


$freteC_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPF',
'boolean' => 'N'
])->get();

$freteC_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPJ',
'boolean' => 'N'
])->get();

$valorFrete = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'N'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'N'
])->get();



$itenspedido = ItensPedido::where($id);


return PDF::loadView('admin.pedidoResource.pdf-consignado', compact('pedidos', 'registros', 'itenspedido', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions'))->stream('PedidosOpycos.pdf');



}

public function comissaoPdf($id)
{
$registros = Produto::where([
'ativo' => 's'
])->get();

$list_requisitions= OpycosRequest::where('status','=','FI')->get();


$pedidos = Pedido::findOrFail($id);

$retiradaBalcPF = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPF',
//   'entrega' => 'BPF',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();

// $dadosClientes= Frete::where('status', '!=', 'A')->get();
// dd($retiradaBalcPF);

$retiradaBalcPJ = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPJ',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();



// dd($retirada);

$freteB_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPF',
'boolean' => 'N'

])->get();

$freteB_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPJ',
'boolean' => 'N'
])->get();


$freteC_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPF',
'boolean' => 'N'
])->get();

$freteC_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPJ',
'boolean' => 'N'
])->get();

$valorFrete = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'N'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'N'
])->get();



$itenspedido = ItensPedido::where($id);


return PDF::loadView('admin.pedidoResource.pdf-comissoes', compact('pedidos', 'registros', 'itenspedido', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions'))->stream('PedidosOpycos.pdf');



}

public function relatorioPdf($id)
{
$registros = Produto::where([
'ativo' => 's'
])->get();

$list_requisitions= OpycosRequest::where('status','=','FI')->get();


$pedidos = Pedido::findOrFail($id);

$comissoes = Comissao::where(['idGeral' => $id])->get();

$comissao_total = $comissoes->sum('valor_comissao');

$pedido_id = Comissao::where(['idGeral' => $id])->pluck('pedido_id');

$pedidossearch  = ItensPedido::whereIn('pedido_id', $pedido_id)->get();

//dd($pedidossearch);

$soma_pedidos = $pedidossearch->sum('prod_preco_padrao');


//dd($soma_pedidos);


$retiradaBalcPF = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPF',
//   'entrega' => 'BPF',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();

// $dadosClientes= Frete::where('status', '!=', 'A')->get();
// dd($retiradaBalcPF);

$retiradaBalcPJ = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPJ',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();



// dd($retirada);

$freteB_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPF',
'boolean' => 'N'

])->get();

$freteB_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPJ',
'boolean' => 'N'
])->get();


$freteC_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPF',
'boolean' => 'N'
])->get();

$freteC_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPJ',
'boolean' => 'N'
])->get();

$valorFrete = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'N'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'N'
])->get();



$itenspedido = ItensPedido::where($id);


return PDF::loadView('admin.pedidoResource.pdf-relatorio', compact('pedidos', 'registros', 'itenspedido', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions', 'comissao_total', 'comissoes', 'soma_pedidos'))->stream('PedidosOpycos.pdf');



}


public function allcompras()
{

$dadosClientes=DB::table('clientes')->get();

$dadosVendedores=DB::table('vendedores')->get();

/*$totalPedido = Pedido::where([
'status'  => 'RE'
])->orderBy('id', 'desc')->paginate(5);*/

$produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$total_produtos = $produtos->sum('prod_preco_padrao');

$frete = DB::table('fretes')->select('valor')->get();

$frete_total = $frete->sum('valor');

$geral = $total_produtos + $frete_total;

$total_preco = $geral;



$compras = Pedido::orderBy('id', 'desc')->paginate(7);

$totalPageSearch = ($compras)->count();


$pedidos = Pedido::orderBy('id', 'desc')->paginate(7);

// $pedidos = Pedido::where('id', '!=', NULL)->orderBy('id', 'desc')->paginate(7);
// dd($pedidos);

$cancelados = Pedido::where([
'status'  => 'CA'
])->orderBy('updated_at', 'desc')->get();

$total = Pedido::all()->count();


$retiradaBalcPF = Frete::where([
'status' => 'AR',
'balcao' => 'Y',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();






// dd($retirada);

$freteB_PF = Frete::where([
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPF',
'boolean' => 'N'
])->get();




$freteC_PF = Frete::where([
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPF',

'boolean' => 'N'
])->get();



$valorFrete = DB::table('fretes')->select('valor')->where([
// 'user_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'Y'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
//  'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'Y'
])->get();








return view('admin.pedidoResource.compras', compact('compras',
'pedidos',
'cancelados',
'retiradaBalcPF',

'freteB_PF',

'freteC_PF',

'valorFrete',
'valorFreteC',
'total',
'dadosClientes',
'dadosVendedores',
'totalPageSearch',
'total_preco'));

}


public function clientecompras()
{

$compras = Pedido::where([
'status'  => 'RE'
])->orderBy('id', 'desc')->paginate(5);

$pedidos = Pedido::where([
'status'  => 'RE',
'id_cliente' => Auth::id()
])->orderBy('id', 'desc')->paginate(5);

$cancelados = Pedido::where([
'status'  => 'CA',
'id_cliente' => Auth::id()
])->orderBy('updated_at', 'desc')->get();

$total = Pedido::all()->count();

return view('cliente.pedidoResource.compras', compact('compras', 'pedidos','cancelados', 'total'));

}



public function cancelar()
{
$this->middleware('VerifyCsrfToken');

$req = Request();
//$idpedido       = $req->input('pedido_id');
//$idspedido_prod = $req->input('id');
$idSpedido = $req->input('id');
$idusuario      = Auth::id();

if( empty($idSpedido) ) {
$req->session()->flash('mensagem-falha', 'Nenhum pedido selecionado para cancelamento!');
return redirect()->route('pedido.compras');
}

/*$check_pedido = Pedido::where([
'id'      => $idpedido,
'user_id' => $idusuario,
'status'  => 'RE' // Finalizado
])->exists();*/

$check_pedidos = Pedido::where(
//'pedido_id' => $idpedido,
//'user_id' => $idusuario,
'status', '!=', 'FI' //Finalizado
)->whereIn('id', $idSpedido)->exists();

if( !$check_pedidos ) {
$req->session()->flash('mensagem-falha', 'Pedido não localizado para cancelamento!');
return redirect()->route('pedido.compras');
}

/* $check_produtos = ItensPedido::where([
'pedido_id' => $idpedido,
'status'    => 'RE' //Finalizado
])->whereIn('id', $idSpedido)->exists();

if( !$check_produtos ) {
$req->session()->flash('mensagem-falha', 'Produtos do pedido não encontrados!');
return redirect()->route('pedido.compras');
}*/

$idrequest = ItensPedido::where([
'pedido_id' => $idSpedido,
'tipo' => 'R',
'status'    => 'GE' //Gerado
])->pluck('request_id');

if (isset($idrequest)) {

Pedido::where(
// 'id'      => $idpedido,
//'user_id' => $idusuario,
'status', '!=', 'FI'
)->whereIn('id', $idSpedido)->update([
'status' => 'CA' //Cancelado
// 'prod_preco_padrao' => 0.00
]);


/*   Frete::where([
// 'id'      => $idpedido,
'user_id' => $idusuario,
'boolean' => 'Y'
//  'status'  => 'RE' // Finalizado
])->whereIn('pedido_id', $idSpedido)->update([
'entrega' => 'CA', //Cancelado

]);*/


OpycosRequest::where([
// 'id'      => $idpedido,
// 'user_id' => $idusuario,
'ativo' => 's'
//  'status'  => 'RE' // Finalizado
])->whereIn('id', $idrequest)->update([
'status' => 'FI', //Cancelado

]);

$req->session()->flash('mensagem-sucesso', 'Pedidos Cancelados!');



return redirect()->route('pedido.compras');


} else {


Pedido::where(
// 'id'      => $idpedido,
//'user_id' => $idusuario,
'status', '!=', 'FI' // Finalizado
)->whereIn('id', $idSpedido)->update([
'status' => 'CA' //Cancelado
// 'prod_preco_padrao' => 0.00
]);


Frete::where([
// 'id'      => $idpedido,
//'user_id' => $idusuario,
'boolean' => 'Y'
//  'status'  => 'RE' // Finalizado
])->whereIn('pedido_id', $idSpedido)->update([
'entrega' => 'CA', //Cancelado

]);




$req->session()->flash('mensagem-sucesso', 'Pedidos Cancelados!');



return redirect()->route('pedido.compras');





}




}


public function descontoPedido($id)
{

$this->middleware('VerifyCsrfToken');
$req = Request();
$idpedido  = $req->input('idpedido');
$idproduto = $req->input('idproduto');
$iditem = $req->input('iditem');
//dd($idproduto);
$idrequest = $req->input('idrequest');

//$t_comissao = $req->input('t_comissao');
//dd($comissao);
//dd($idproduto);
//  $quantidade =  ($idproduto)->count();






$desconto_percent_prod = $req->input('desconto_produto'); //Desconto Produto %               
$desconto_percent_prod = str_replace( '%', '', $desconto_percent_prod);

$getcomissao = Pedido::find($id);
$percent = $getcomissao->percentual_comissao;


$getvendedor = Pedido::find($id);
$idvendedor = $getcomissao->vendedor_id;

$id_vendedor = Vendedor::find($idvendedor);
$comissao = $id_vendedor->comissao;



//Percentual de comissão.

//dd($desconto_percent_prod);

if (isset($idproduto)){

	$desconto_reais_prod = $req->input('desconto_produto_reais');  //Desconto Produto R$
$desconto_reais_prod = str_replace( ',', '.', $desconto_reais_prod );

	if (($desconto_percent_prod == NULL) && ($desconto_reais_prod == NULL))
{

$req->session()->flash('mensagem-falha', 'Informe o desconto.');
  return redirect()->route('pedidos/{id}/edit', $id)->withInput();

}

	if ($desconto_percent_prod > 100)
        {
          $req->session()->flash('mensagem-falha', 'Não é Permitido desconto acima de 100%!');
         return redirect()->route('pedidos/{id}/edit', $id)->withInput();
        }


// $idusuario      = Auth::id();

/*if (empty($desconto_percent_prod)){

$req->session()->flash('mensagem-falha', 'Não foram localizados os valores para desconto!');
return redirect()->route('pedidos/{id}/edit', $id);
}*/





/*if ($desconto_percent_prod == NULL)
{
$desconto_percent_prod = '0';
}*/

/*if ($desconto_percent_prod == NULL)
        {
          $desconto_percent_prod = '0';
        } */

$check_desconto_prod =  ItensPedido::where([
'pedido_id' => $idpedido
])->where('produto_id', $idproduto)->exists();

if(!$check_desconto_prod) {
$req->session()->flash('mensagem-falha', 'Produto não localizado!');
return redirect()->route('pedidos/{id}/edit', $id);

} else {

/*$id_produtos =  ItensPedido::where([
'pedido_id' => $idpedido,

])->where('produto_id', $idproduto)->pluck('produto_id');*/

$produto = Produto::find($idproduto);//Query ID Produto 

$preco = $produto->prod_preco_padrao;

if ($desconto_percent_prod > 0)
{
$desconto_produtos = ($preco * $desconto_percent_prod)/100;
$desconto_produtos = number_format($desconto_produtos, 2, '.', '');	
//dd($desconto_produtos);
}

elseif ($desconto_reais_prod > 0)
{
$desconto_produtos = $desconto_reais_prod;
//dd($desconto_produtos);
}
//dd($desconto_reais_prod);

elseif (($desconto_percent_prod == "") && ($desconto_reais_prod == ""))
{
//dd($desconto_reais_prod);	
$desconto_produtos = '0.00';

} else {

$desconto_produtos = '0.00';
	
}


         if( $preco <= $desconto_produtos ) {
          $req->session()->flash('mensagem-falha', 'Erro! Valor do desconto igual ou superior ao valor do Produto!');
          return redirect()->route('pedidos/{id}/edit', $id);
          }


$pencent_desconto = ($desconto_produtos/$preco)*100;
if ($pencent_desconto > 15)
{
$comissao = $comissao / 2;
ItensPedido::where([
'pedido_id'	=> $idpedido,
'produto_id' => $idproduto,
'id' => $iditem
])->update([
'prod_desconto' => $desconto_produtos,
'comissao' => $comissao
]);
}

ItensPedido::where([
'pedido_id'	=> $idpedido,
'produto_id' => $idproduto,
'id' => $iditem
])->update([
'prod_desconto' => $desconto_produtos,
'comissao' => $comissao
]);




//Verifica Estado atual do pedido
$pedidossearch = Pedido::select('id')->where([
'id' => $id
])->pluck('id');

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$soma_produtos  = $produtossearch->sum('prod_preco_padrao');

$desconto_produtos = $produtossearch->sum('prod_desconto');

$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

$geral = $soma_produtos - $desconto;

$total_preco = $geral;


$pedidossearch = Pedido::where([
'id' => $id
])->pluck('id');

$requestsearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'R')->get();

$conteR = ($requestsearch)->count();
$requestsearchc  = ItensPedido::select('comissao', 'request_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'R')->get();
$d_request = $requestsearchc->pluck('request_desconto');
$v_request = $requestsearchc->pluck('prod_preco_padrao');
$r_comissao = $requestsearchc->pluck('comissao');	


$produtossearchP  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->get();

$conte = ($produtossearchP)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');


$produtossearchc  = ItensPedido::select('comissao', 'prod_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'P')->get();


$p_comissao = $produtossearchc->pluck('comissao');

$v_produto = $produtossearchc->pluck('prod_preco_padrao');
$d_produto = $produtossearchc->pluck('prod_desconto');

            $t_comissao = 0;
            $contador = 0;
            while($conte > $contador)
            {
            $v_comissao = 
            ($p_comissao[$contador]/100) * (($v_produto[$contador]) - ($d_produto[$contador]));
            $t_comissao += $v_comissao;
            $contador++;
            
            }
            $t_comissaoR = 0;
            $conta = 0;
            while($conteR > $conta)
            {
            $v_comissaoR = 
            ($r_comissao[$conta]/100) * (($v_request[$conta]) - ($d_request[$conta]));
            $t_comissaoR += $v_comissaoR;
            $conta++;
            
            }

            $t_comissao = $t_comissao + $t_comissaoR;



Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $t_comissao,
//'percentual_comissao' => $percent
]);




$req->session()->flash('mensagem-sucesso', 'Desconto Aplicado com sucesso! Comissão Recalculada!.');

return redirect()->route('pedidos/{id}/edit', $id);

}





}

$desconto_percent_req = $req->input('desconto_request');
//Desconto Requisição %    
$desconto_percent_req = str_replace( '%', '', $desconto_percent_req); 


$desconto_reais_req = $req->input('desconto_request_reais');  //Desconto Produto R$
$desconto_reais_req = str_replace( ',', '.', $desconto_reais_req );



if (isset($idrequest)) {

if ($desconto_percent_req > 100)
        {
          $req->session()->flash('mensagem-falha', 'Não é Permitido desconto acima de 100%!');
         return redirect()->route('pedidos/{id}/edit', $id)->withInput();
        }


/*if ($desconto_percent_req == NULL)
{
$desconto_percent_req = '0';

}*/

// $idusuario      = Auth::id();

/*if (empty($desconto_percent_req)){

$req->session()->flash('mensagem-falha', 'Não foram localizados os valores para desconto!');
return redirect()->route('pedidos/{id}/edit', $id);
}*/

$check_desconto_request =  ItensPedido::where([
'pedido_id' => $idpedido
])->where('request_id', $idrequest)->exists();

if(!$check_desconto_request) {
$req->session()->flash('mensagem-falha', 'Requisição não localizada!');
return redirect()->route('pedidos/{id}/edit', $id);

} else {

/*$id_produtos =  ItensPedido::where([
'pedido_id' => $idpedido,

])->where('produto_id', $idproduto)->pluck('produto_id');*/

 $request = OpycosRequest::find($idrequest);//Query ID Produto 

$preco = $request->request_valor;

if ($desconto_percent_req > 0)
{
$desconto_request = ($preco * $desconto_percent_req)/100;
$desconto_request =  number_format($desconto_request, 2, '.', '');
//dd($desconto_produtos);
}

elseif ($desconto_reais_req > 0)
{
$desconto_request = $desconto_reais_req;
//dd($desconto_produtos);
}
//dd($desconto_reais_prod);

elseif (($desconto_percent_req == "") && ($desconto_reais_req == ""))
{
//dd($desconto_reais_prod);	
$desconto_request = '0.00';

} else {

$desconto_request = '0.00';

}
        

         if( $preco <= $desconto_request ) {
          $req->session()->flash('mensagem-falha', 'Erro! Valor do desconto igual ou superior ao valor da Requisição!');
          return redirect()->route('pedidos/{id}/edit', $id);
          }



ItensPedido::where([
'pedido_id'	=> $idpedido,
'request_id'=> $idrequest
])->update([
'request_desconto' => $desconto_request

]);

//Verifica Estado atual do pedido
$pedidossearch = Pedido::select('id')->where([
'id' => $id
])->pluck('id');

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$soma_produtos  = $produtossearch->sum('prod_preco_padrao');

$desconto_produtos = $produtossearch->sum('prod_desconto');

$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

$geral = $soma_produtos - $desconto;

$total_preco = $geral;


$requestsearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'R')->get();

$conteR = ($requestsearch)->count();
$requestsearchc  = ItensPedido::select('comissao', 'request_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'R')->get();
$d_request = $requestsearchc->pluck('request_desconto');
$v_request = $requestsearchc->pluck('prod_preco_padrao');
$r_comissao = $requestsearchc->pluck('comissao');	


$produtossearchP  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->get();

$conte = ($produtossearchP)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');


$produtossearchc  = ItensPedido::select('comissao', 'prod_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'P')->get();


$p_comissao = $produtossearchc->pluck('comissao');

$v_produto = $produtossearchc->pluck('prod_preco_padrao');
$d_produto = $produtossearchc->pluck('prod_desconto');

            $t_comissao = 0;
            $contador = 0;
            while($conte > $contador)
            {
            $v_comissao = 
            ($p_comissao[$contador]/100) * (($v_produto[$contador]) - ($d_produto[$contador]));
            $t_comissao += $v_comissao;
            $contador++;
            
            }
            $t_comissaoR = 0;
            $conta = 0;
            while($conteR > $conta)
            {
            $v_comissaoR = 
            ($r_comissao[$conta]/100) * (($v_request[$conta]) - ($d_request[$conta]));
            $t_comissaoR += $v_comissaoR;
            $conta++;
            
            }

            $t_comissao = $t_comissao + $t_comissaoR;



Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $t_comissao,
//'percentual_comissao' => $percent
]);


$req->session()->flash('mensagem-sucesso', 'Desconto Aplicado com sucesso! Comissão Recalculada!.');

return redirect()->route('pedidos/{id}/edit', $id);

}



}

}


public function descontoPedidoConsig($id)
{

$this->middleware('VerifyCsrfToken');
$req = Request();
$idpedido  = $req->input('idpedido');
$idproduto = $req->input('idproduto');
//dd($idproduto);
$idrequest = $req->input('idrequest');
//dd($idproduto);
//  $quantidade =  ($idproduto)->count();


$desconto_percent_prod = $req->input('desconto_produto'); //Desconto Produto %               
$desconto_percent_prod = str_replace( '%', '', $desconto_percent_prod);

$getcomissao = Pedido::find($id);
$percent = $getcomissao->percentual_comissao;//Percentual de comissão.

//dd($desconto_percent_prod);

if (isset($idproduto)){

	if ($desconto_percent_prod > 100)
        {
          $req->session()->flash('mensagem-falha', 'Não é Permitido desconto acima de 100%!');
         return redirect()->route('pedidos/{id}/consig/edit', $id)->withInput();
        }


$desconto_reais_prod = $req->input('desconto_produto_reais');  //Desconto Produto R$
$desconto_reais_prod = str_replace( ',', '.', $desconto_reais_prod );

// $idusuario      = Auth::id();

/*if (empty($desconto_percent_prod)){

$req->session()->flash('mensagem-falha', 'Não foram localizados os valores para desconto!');
return redirect()->route('pedidos/{id}/edit', $id);
}*/

/*if ($desconto_percent_prod == NULL)
{
$desconto_percent_prod = '0';
}*/

/*if ($desconto_percent_prod == NULL)
        {
          $desconto_percent_prod = '0';
        } */

$check_desconto_prod =  ItensPedido::where([
'pedido_id' => $idpedido
])->where('produto_id', $idproduto)->exists();

if(!$check_desconto_prod) {
$req->session()->flash('mensagem-falha', 'Produto não localizado!');
return redirect()->route('pedidos/{id}/consig/edit', $id);

} else {

/*$id_produtos =  ItensPedido::where([
'pedido_id' => $idpedido,

])->where('produto_id', $idproduto)->pluck('produto_id');*/

		$produto = Produto::find($idproduto);//Query ID Produto 

			$preco = $produto->prod_preco_padrao;

if ($desconto_percent_prod > 0)
{
$desconto_produtos = ($preco * $desconto_percent_prod)/100;
$desconto_produtos = number_format($desconto_produtos, 2, '.', '');	
//dd($desconto_produtos);
}

elseif ($desconto_reais_prod > 0)
{
$desconto_produtos = $desconto_reais_prod;
//dd($desconto_produtos);
}
//dd($desconto_reais_prod);

elseif (($desconto_percent_prod == "") && ($desconto_reais_prod == ""))
{
//dd($desconto_reais_prod);	
$desconto_produtos = '0.00';

} else {

$desconto_produtos = '0.00';
	
}
        		

        		 if( $preco <= $desconto_produtos ) {
        			  $req->session()->flash('mensagem-falha', 'Erro! Valor do desconto igual ou superior ao valor do Produto!');
         			 return redirect()->route('pedidos/{id}/consig/edit', $id);
         		 }

					ItensPedido::where([
					'pedido_id'	=> $idpedido,
					'produto_id' => $idproduto
					])->update([
					'prod_desconto' => $desconto_produtos
					]);

					//Verifica Estado atual do pedido
$pedidossearch = Pedido::select('id')->where([
'id' => $id
])->pluck('id');

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$soma_produtos  = $produtossearch->sum('prod_preco_padrao');

$desconto_produtos = $produtossearch->sum('prod_desconto');

$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

$geral = $soma_produtos - $desconto;

$total_preco = $geral;

$Calculopercent = $percent * $total_preco/100;
$Calculopercent = number_format($Calculopercent, 2, '.', '');


Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $Calculopercent,
//'percentual_comissao' => $percent
]);

						$req->session()->flash('mensagem-sucesso', 'Desconto Aplicado com sucesso! Comissão Recalculada!');

							return redirect()->route('pedidos/{id}/consig/edit', $id);

}





}

$desconto_percent_req = $req->input('desconto_request');
//Desconto Requisição %    
$desconto_percent_req = str_replace( '%', '', $desconto_percent_req); 



if (isset($idrequest)) {

if ($desconto_percent_req > 100)
        {
          $req->session()->flash('mensagem-falha', 'Não é Permitido desconto acima de 100%!');
         return redirect()->route('pedidos/{id}/consig/edit', $id)->withInput();
        }



$desconto_reais_req = $req->input('desconto_request_reais');  //Desconto Produto R$
$desconto_reais_req = str_replace( ',', '.', $desconto_reais_req );

// $idusuario      = Auth::id();

/*if (empty($desconto_percent_req)){

$req->session()->flash('mensagem-falha', 'Não foram localizados os valores para desconto!');
return redirect()->route('pedidos/{id}/consig/edit', $id);
}*/

/*if ($desconto_percent_req == NULL)
{
$desconto_percent_req = '0';
}*/

$check_desconto_request =  ItensPedido::where([
'pedido_id' => $idpedido
])->where('request_id', $idrequest)->exists();

if(!$check_desconto_request) {
$req->session()->flash('mensagem-falha', 'Requisição não localizada!');
return redirect()->route('pedidos/{id}/consig/edit', $id);

} else {

/*$id_produtos =  ItensPedido::where([
'pedido_id' => $idpedido,

])->where('produto_id', $idproduto)->pluck('produto_id');*/

 $request = OpycosRequest::find($idrequest);//Query ID Produto 

$preco = $request->request_valor;

if ($desconto_percent_req > 0)
{
$desconto_request = ($preco * $desconto_percent_req)/100;
$desconto_request =  number_format($desconto_request, 2, '.', '');
//dd($desconto_produtos);
}

elseif ($desconto_reais_req > 0)
{
$desconto_request = $desconto_reais_req;
//dd($desconto_produtos);
}
//dd($desconto_reais_prod);

elseif (($desconto_percent_req == "") && ($desconto_reais_req == ""))
{
//dd($desconto_reais_prod);	
$desconto_request = '0.00';

} else {

$desconto_request = '0.00';

}
        
         if( $request->request_valor <= $desconto_request ) {
          $req->session()->flash('mensagem-falha', 'Erro! Valor do desconto igual ou superior ao valor da Requisição!');
          return redirect()->route('pedidos/{id}/consig/edit', $id);
          }



ItensPedido::where([
'pedido_id'	=> $idpedido,
'request_id' => $idrequest
])->update([
'request_desconto' => $desconto_request

]);

//Verifica Estado atual do pedido
$pedidossearch = Pedido::select('id')->where([
'id' => $id
])->pluck('id');

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$soma_produtos  = $produtossearch->sum('prod_preco_padrao');

$desconto_produtos = $produtossearch->sum('prod_desconto');

$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

$geral = $soma_produtos - $desconto;

$total_preco = $geral;

$Calculopercent = $percent * $total_preco/100;
$Calculopercent = number_format($Calculopercent, 2, '.', '');


Comissao::where([
'pedido_id'	=> $id,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
//'status'  => 'PE' // Pendente

])->update([
//'status' => 'PE',	
//'cliente_id' => $idcliente,
//'obs_comissao' => $obs_comissao,
'valor_comissao' => $Calculopercent,
//'percentual_comissao' => $percent
]);


$req->session()->flash('mensagem-sucesso', 'Desconto Aplicado com sucesso! Comissão Recalculada!');

return redirect()->route('pedidos/{id}/consig/edit', $id);

}



}

}








public function finalizar($id)
{
$this->middleware('VerifyCsrfToken');

$req = Request();
//$idpedido       = $req->input('pedido_id');
//$idspedido_prod = $req->input('id');
$idSpedido = $req->input('id');
//dd($idSpedido);
$note = $req->input('note');
$idusuario      = Auth::id();

if( empty($idSpedido) ) {
$req->session()->flash('mensagem-falha', 'Nenhum Item selecionado para pagamento!');
return redirect()->route('pedidos/{id}/consig/edit', $id)->withInput();
}

/*$check_pedido = Pedido::where([
'id'      => $idpedido,
'user_id' => $idusuario,
'status'  => 'RE' // Finalizado
])->exists();*/

$check_pedidos = ItensPedido::where([
'pedido_id' => $id,
//'user_id' => $idusuario,
'status' => 'GE' //GE
])->whereIn('id', $idSpedido)->exists();

if( !$check_pedidos ) {
$req->session()->flash('mensagem-falha', 'Itens não localizados para Pagamento!');
return redirect()->route('pedidos/{id}/consig/edit', $id);
}


ItensPedido::where([
'pedido_id' => $id,
'status' => 'GE'
])->whereIn('id', $idSpedido)->update([
'status' => 'FI', //Cancelado
'note' => $note
// 'prod_preco_padrao' => 0.00
]);


$req->session()->flash('mensagem-sucesso', 'Itens selecionados foram pagos!');


return redirect()->route('pedidos/{id}/consig/edit', $id);


}




public function show($id) {
//
}

public function edit($id) {
$registros = Produto::where([
'ativo' => 's'
])->get();

$list_requisitions= OpycosRequest::where('status','=','FI')->get();

$pedidos = Pedido::findOrFail($id);




$retiradaBalcPF = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPF',
//   'entrega' => 'BPF',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();


$retiradaBalcPJ = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPJ',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();



$pedidossearch = Pedido::where([
'id' => $id
])->pluck('id');

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->get();

$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');

$requestsearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'R')->get();

$desconto_request = $requestsearch->sum('request_desconto');

$soma_request  = $requestsearch->sum('prod_preco_padrao');

$produtossearchP  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->get();

$conte = ($produtossearchP)->count();
//dd($conte);
//$Calculopercent = $percent * $total_preco/100;
//$Calculopercent = number_format($Calculopercent, 2, '.', '');


$produtossearchc  = ItensPedido::select('id', 'comissao', 'prod_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'P')->get();


$p_comissao = $produtossearchc->pluck('comissao');

$v_produto = $produtossearchc->pluck('prod_preco_padrao');
$d_produto = $produtossearchc->pluck('prod_desconto');

$requestsearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'R')->get();
$conteR = ($requestsearch)->count();
$requestsearchc  = ItensPedido::select('comissao', 'request_desconto', 'prod_preco_padrao')->where('pedido_id', '=', $pedidossearch)->where('tipo', '=', 'R')->get();
$d_request = $requestsearchc->pluck('request_desconto');
$v_request = $requestsearchc->pluck('prod_preco_padrao');
$r_comissao = $requestsearchc->pluck('comissao');	


// dd($retirada);

$freteB_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPF',
'boolean' => 'N'

])->get();

$freteB_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPJ',
'boolean' => 'N'
])->get();


$freteC_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPF',
'boolean' => 'N'
])->get();

$freteC_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPJ',
'boolean' => 'N'
])->get();

$valorFrete = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
// 'user_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'N'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
// 'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'N'
])->get();

$itenspedidoP = ItensPedido::where('pedido_id', '=', $id)->where('tipo', '=', 'P')->first();

$itenspedidoR = ItensPedido::where('pedido_id', '=', $id)->where('tipo', '=', 'R')->first();


return view('admin.pedidoResource.alter-pedido', compact('pedidos', 'registros', 'itenspedidoP', 'itenspedidoR', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions', 'conte', 'p_comissao','v_produto', 'd_produto', 'soma_produtos','soma_request' ,'desconto_produtos', 'desconto_request', 'conteR','d_request', 'v_request', 'r_comissao'));
}


public function editConsignado($id) {
$registros = Produto::where([
'ativo' => 's'
])->get();

$list_requisitions= OpycosRequest::where('status','=','FI')->get();


$pedidos = Pedido::findOrFail($id);

$retiradaBalcPF = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPF',
//   'entrega' => 'BPF',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();

// $dadosClientes= Frete::where('status', '!=', 'A')->get();
// dd($retiradaBalcPF);

$retiradaBalcPJ = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPJ',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();



// dd($retirada);

$freteB_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPF',
'boolean' => 'N'

])->get();

$freteB_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPJ',
'boolean' => 'N'
])->get();


$freteC_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPF',
'boolean' => 'N'
])->get();

$freteC_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPJ',
'boolean' => 'N'
])->get();

$valorFrete = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
// 'user_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'N'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
// 'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'N'
])->get();





$itenspedido = ItensPedido::where($id);


return view('admin.pedidoResource.alter-pedido-consig', compact('pedidos', 'registros', 'itenspedido', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions'));
}

public function editConsignadoRelatorio($id) {
$registros = Produto::where([
'ativo' => 's'
])->get();

$list_requisitions= OpycosRequest::where('status','=','FI')->get();


$pedidos = Pedido::findOrFail($id);

$retiradaBalcPF = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPF',
//   'entrega' => 'BPF',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();

// $dadosClientes= Frete::where('status', '!=', 'A')->get();
// dd($retiradaBalcPF);

$retiradaBalcPJ = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPJ',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();



// dd($retirada);

$freteB_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPF',
'boolean' => 'N'

])->get();

$freteB_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPJ',
'boolean' => 'N'
])->get();


$freteC_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPF',
'boolean' => 'N'
])->get();

$freteC_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPJ',
'boolean' => 'N'
])->get();

$valorFrete = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
// 'user_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'N'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
// 'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'N'
])->get();





$itenspedido = ItensPedido::where($id);


return view('admin.pedidoResource.relatorio-pedido-consig', compact('pedidos', 'registros', 'itenspedido', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions'));
}


public function info($id) {
$registros = Produto::where([
'ativo' => 's'
])->get();

$list_requisitions= OpycosRequest::where('status','=','FI')->get();


$pedidos = Pedido::findOrFail($id);

$retiradaBalcPF = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPF',
//   'entrega' => 'BPF',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();

// $dadosClientes= Frete::where('status', '!=', 'A')->get();
// dd($retiradaBalcPF);

$retiradaBalcPJ = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPJ',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();



// dd($retirada);

$freteB_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPF',
'boolean' => 'N'

])->get();

$freteB_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPJ',
'boolean' => 'N'
])->get();


$freteC_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPF',
'boolean' => 'N'
])->get();

$freteC_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPJ',
'boolean' => 'N'
])->get();

$valorFrete = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'N'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'N'
])->get();



$itenspedido = ItensPedido::where($id);


return view('admin.pedidoResource.info-pedido', compact('pedidos', 'registros', 'itenspedido', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions'));
}


public function infoConsig($id) {
$registros = Produto::where([
'ativo' => 's'
])->get();

$list_requisitions= OpycosRequest::where('status','=','FI')->get();


$pedidos = Pedido::findOrFail($id);

$retiradaBalcPF = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPF',
//   'entrega' => 'BPF',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();

// $dadosClientes= Frete::where('status', '!=', 'A')->get();
// dd($retiradaBalcPF);

$retiradaBalcPJ = Frete::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPJ',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();



// dd($retirada);

$freteB_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPF',
'boolean' => 'N'

])->get();

$freteB_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPJ',
'boolean' => 'N'
])->get();


$freteC_PF = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPF',
'boolean' => 'N'
])->get();

$freteC_PJ = Frete::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPJ',
'boolean' => 'N'
])->get();

$valorFrete = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'N'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'N'
])->get();





$itenspedido = ItensPedido::where($id);


return view('admin.pedidoResource.info-pedido-consig', compact('pedidos', 'registros', 'itenspedido', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions'));
}


public function update($id) {

$this->middleware('VerifyCsrfToken');

$pedidos = Pedido::findOrFail($id);

$this->middleware('VerifyCsrfToken');

$req = Request();
$obspedido = $req->input('obs_pedido');
$idcliente = $req->input('id_cliente');
$idproduto = $req->input('id');


$produto = Produto::find($idproduto);
if( empty($produto->id) ) {
$req->session()->flash('mensagem-falha', 'Produto não encontrado em nossa loja!');
return redirect()->route('pedidos.index');
}

$idusuario = Auth::id();
$idpedido = Pedido::consultaId([
'id' => $id,
'status'  => 'RE' // Reservada
]);

if( isset($idpedido) ) {
Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id,

'status'  => 'RE'
])->update([

'status' => 'AG' //Alterado
]);

}



ItensPedido::create([
'pedido_id'  => $id,
'produto_id' => $idproduto,
'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $produto->prod_preco_padrao,
'prod_preco_prof' => $produto->prod_preco_prof,
//'cliente_id' => $idcliente,
//'vendedor_id' => $idvendedor,
'status'     => 'RE'  //Reservado


]);

$req->session()->flash('mensagem-sucesso', 'Produto adicionado ao pedido com sucesso!');

return redirect()->route('pedidos.edit', $id);



}




public function updateFrete($id) {

$this->middleware('VerifyCsrfToken');

$pedidos = Pedido::findOrFail($id);

$this->middleware('VerifyCsrfToken');

$req = Request();
$obspedido = $req->input('obs_pedido');
$idcliente = $req->input('id_cliente');
$idproduto = $req->input('id');
$retirada = $req->input('balcao');
$frete = $req->input('entrega');
$status = $req->input('status');
$valor = $req->input('valor');
$valor = str_replace( ',', '.', $valor );



/*  $produto = Produto::find($idproduto);
if( empty($produto->id) ) {
$req->session()->flash('mensagem-falha', 'Produto não encontrado em nossa loja!');
return redirect()->route('pedidos.index');
}*/

$idusuario = Auth::id();
$idpedido = Pedido::consultaId([
'id' => $id,
'status'  => 'RE' // Reservada
]);

if( isset($idpedido) ) {
Pedido::where([
//'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id,

'status'  => 'RE'
])->update([

'status' => 'AG' //Alterado
]);

}

if ($retirada == NULL && $frete == NULL)
{
$req->session()->flash('mensagem-falha', 'Informe o tipo de frete');
return redirect()->route('pedidos.edit', $id);

}

if ($frete == "BPF") {


if ($valor == NULL) {
$req->session()->flash('mensagem-falha', 'Informar o custo do frete!');
return redirect()->route('pedidos.edit', $id);


}

Frete::Where([
'pedido_id'  => $id,
// 'produto_id' => $idproduto,

])->update([

'balcao' => NULL,
'entrega' => 'BPF',
'valor' => $valor,
'status' => 'EMB'

]);

$req->session()->flash('mensagem-sucesso', 'Frete alterado com sucesso!');

return redirect()->route('pedidos.edit', $id);



}


if ($frete == "CPF") {

if ($valor == NULL) {
$req->session()->flash('mensagem-falha', 'Informar o custo do frete!');
return redirect()->route('pedidos.edit', $id);


}



Frete::Where([
'pedido_id'  => $id,

// 'produto_id' => $idproduto,



])->update([

'balcao' => NULL,
'entrega' => 'CPF',
'valor' => $valor,
'status' => 'EC'



]);

$req->session()->flash('mensagem-sucesso', 'Frete alterado com sucesso!');

return redirect()->route('pedidos.edit', $id);


}


if ($frete == "BPJ") {

Frete::Where([
'pedido_id'  => $id,
// 'produto_id' => $idproduto,



])->update([

'balcao' => NULL,
'entrega' => 'BPJ',
'status' => 'EMB'

]);

$req->session()->flash('mensagem-sucesso', 'Frete alterado com sucesso!');

return redirect()->route('pedidos.edit', $id);



}



if ($frete == "CPJ") {

Frete::Where([
'pedido_id'  => $id,
// 'produto_id' => $idproduto,



])->update([

'balcao' => NULL,
'entrega' => 'CPJ',
'status' => 'EC'

]);

$req->session()->flash('mensagem-sucesso', 'Frete alterado com sucesso!');

return redirect()->route('pedidos.edit', $id);



}


if ($frete == NULL && $retirada == 'YPF' ) {

Frete::Where([
'pedido_id'  => $id,
// 'produto_id' => $idproduto,



])->update([

'balcao' => 'YPF',
'entrega' => NULL,
'status' => 'AR'

]);

$req->session()->flash('mensagem-sucesso', 'Frete alterado com sucesso!');

return redirect()->route('pedidos.edit', $id);



}


if ($frete == NULL && $retirada == 'YPJ' ) {

Frete::Where([
'pedido_id'  => $id,
// 'produto_id' => $idproduto,



])->update([

'balcao' => 'YPJ',
'entrega' => NULL,
'status' => 'AR'

]);

$req->session()->flash('mensagem-sucesso', 'Frete alterado com sucesso!');

return redirect()->route('pedidos.edit', $id);



}



}


public function destroy($id) {
try
{
$pedidos = Pedido::findOrFail($id);
$pedidos->delete();
return redirect()->route('pedidos.index')->with('message', 'Pedido excluído com sucesso!');
} catch (QueryException $e) {
return redirect()
->route('pedido.allcompras')
->with('mensagem-falha', 'Não foi possível realizar a exclusão (Já existe lançamento de produto neste pedido!)');
}
}

public function searchPedido(Request $request, Pedido $pedido)
{



$id_cliente = $request->id_cliente;

$vendedor_id = $request->vendedor_id;

$ano = $request->updated_at;

$periodo = $request->periodo;

$status = $request->status;

$statusdefaut = $request->statusdefaut;

$pedido_id = $request->id;

$consignado = $request->consignado;

$cancelados = $request->cancelados;





if ((isset($id_cliente)) && (isset($status)) && (isset($vendedor_id)) && (isset($ano)) && (isset($periodo)) )
{
//dd($status);

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'id_cliente'  => $id_cliente,
'status'  => $status,
'vendedor_id'  => $vendedor_id,
'consignado' => $consignado,
'cancelados' => $cancelados
])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');



$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}

elseif ((isset($id_cliente)) && (isset($status)) && (isset($ano)) && (isset($periodo)) )
{
//dd($status);

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'id_cliente'  => $id_cliente,
'status'  => $status,
'consignado' => $consignado,
'cancelados' => $cancelados
])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');



$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}

elseif ((isset($vendedor_id)) && (isset($status)) && (isset($ano)) && (isset($periodo)) )
{
//dd($status);

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'status'  => $status,
'vendedor_id'  => $vendedor_id,
'consignado' => $consignado,
'cancelados' => $cancelados
])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');



$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}

elseif ((isset($status)) && (isset($periodo)) )
{
//dd($status);

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'status'  => $status,
'consignado' => $consignado,
'cancelados' => $cancelados
])->whereMonth('updated_at', $periodo)->pluck('id');



$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}

elseif ((isset($status)) && (isset($ano)) )
{
//dd($status);

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'status'  => $status,
'consignado' => $consignado,
'cancelados' => $cancelados
])->whereYear('updated_at', $ano)->pluck('id');



$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}

elseif ((isset($vendedor_id)) && (isset($ano)) && (isset($periodo)) ) {


//dd($consignado);

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'vendedor_id'  => $vendedor_id,
'consignado' => $consignado,
'cancelados' => $cancelados
])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');


//dd($pedidossearch);


$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;

}


elseif ((isset($vendedor_id)) && (isset($ano)) )
{
//dd($status);

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'vendedor_id'  => $vendedor_id,
'consignado' => $consignado,
'cancelados' => $cancelados
])->whereYear('updated_at', $ano)->pluck('id');



$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}

elseif ((isset($vendedor_id)) && (isset($periodo)) )
{
//dd($status);

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'vendedor_id'  => $vendedor_id,
'consignado' => $consignado,
'cancelados' => $cancelados
])->whereMonth('updated_at', $periodo)->pluck('id');



$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}

elseif ((isset($id_cliente)) && (isset($ano)) && (isset($periodo)) ) {

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'id_cliente'  => $id_cliente,
'consignado' => $consignado,
'cancelados' => $cancelados
])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');



$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;


}


elseif ((isset($id_cliente)) && (isset($ano)) )
{
//dd($status);

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'id_cliente'  => $id_cliente,
'consignado' => $consignado,
'cancelados' => $cancelados
])->whereYear('updated_at', $ano)->pluck('id');



$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}

elseif ((isset($id_cliente)) && (isset($periodo)) )
{
//dd($status);

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'id_cliente'  => $id_cliente,
'consignado' => $consignado,
'cancelados' => $cancelados
])->whereMonth('updated_at', $periodo)->pluck('id');



$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}
elseif ((isset($status)) && (isset($ano)) && (isset($periodo)) )
{
//dd($status);

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'status'  => $status,
'consignado' => $consignado,
'cancelados' => $cancelados
])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');



$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}

elseif ((isset($ano)) && (isset($periodo)) )
{
//dd($status);

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'consignado' => $consignado,
'cancelados' => $cancelados
])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');



$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}

elseif (isset($ano))
{
//dd($status);

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'consignado' => $consignado,
'cancelados' => $cancelados
])->whereYear('updated_at', $ano)->pluck('id');



$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}

elseif (isset($periodo))
{
//dd($status);

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'consignado' => $consignado,
'cancelados' => $cancelados
])->whereMonth('updated_at', $periodo)->pluck('id');



$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}


elseif ((isset($id_cliente)) && (isset($status)) && (isset($vendedor_id)))
{
//dd($status);

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'id_cliente'  => $id_cliente,
'status'  => $status,
'vendedor_id'  => $vendedor_id,
'consignado' => $consignado,
'cancelados' => $cancelados
])->pluck('id');

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}





elseif ((isset($id_cliente))  && (isset($vendedor_id)))
{


$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'id_cliente'  => $id_cliente,
'vendedor_id' => $vendedor_id,
'consignado' => $consignado,
'cancelados' => $cancelados
])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif  ((isset($id_cliente))  && (isset($status)))
{


$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'id_cliente'  => $id_cliente,
'status' => $status,
'consignado' => $consignado,
'cancelados' => $cancelados

])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}



elseif ((isset($vendedor_id))  && (isset($status)))
{


$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'vendedor_id'  => $vendedor_id,
'status' => $status,
'consignado' => $consignado,
'cancelados'  => $cancelados


])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif (isset($id_cliente))
{


$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'id_cliente'  => $id_cliente,
'cancelados' => $cancelados,
'consignado' => $consignado

])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif (isset($vendedor_id))
{


$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'vendedor_id'  => $vendedor_id,
'consignado' => $consignado,
'cancelados'  => $cancelados

])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif (isset($status))
{


$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'cancelados'  => $cancelados,
'status' => $status,
'consignado' => $consignado


])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif (isset($cancelados))
{


$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'cancelados'  => $cancelados,
'consignado' => $consignado


])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif (isse($pedido_id)) 
{

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'id' => $pedido_id,
'consignado' => $consignado,
'cancelados'  => $cancelados

])->pluck('id');

//dd($pedidossearch);

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

// dd($produtossearch);

// $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
// $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();



$frete_total = $frete->sum('valor');

$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;

}

else 

{

$pedidossearch = Pedido::where('status', '!=', 'GE')->select('id')->where([
'consignado' => $consignado,
'cancelados'  => $cancelados

])->pluck('id');

//dd($pedidossearch);

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

// dd($produtossearch);

// $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
// $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();



$frete_total = $frete->sum('valor');

$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;


}



$compras = Pedido::where(
'id', '!=', NULL
// 'user_id' => Auth::id()
)->orderBy('id', 'desc')->paginate(5);

$totalPage = ($compras)->count();


$dataForm = $request->except('_token');
$pedidos = $pedido->search($dataForm);


$allsearch = $pedido->searchTotal($dataForm);
$totalSearch = ($allsearch)->count();
$totalPageSearch = ($pedidos)->count();




$dadosClientes=DB::table('clientes')->get();
$dadosVendedores=DB::table('vendedores')->get();
$dadosGroupsProduct=DB::table('groups_product')->get();
$dadosProducts=DB::table('products')->get();
$total = Pedido::all()->count();


return view('admin.pedidoResource.compras', compact('dadosGroupsProduct','dataForm', 'dadosClientes', 'dadosVendedores', 'pedidos', 'compras', 'total', 'total_preco','totalSearch', 'id_cliente', 'vendedor_id', 'status', 'totalPageSearch', 'totalPage', 'soma_produtos', 'desconto', 'frete_total', 'cancelados', 'periodo', 'ano'));

}


public function searchConsignado(Request $request, Pedido $pedido)
{





$id_cliente = $request->id_cliente;

$vendedor_id = $request->vendedor_id;

$status = $request->status;

$pedido_id = $request->id;

$consignado = $request->consignado;

$cancelados = $request->cancelados;






if ( (isset($cancelados)) && (isset($status)) && (isset($vendedor_id)) && (isset($id_cliente)))

{
//dd($status);

$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente,
'consignado' => $consignado

])->pluck('id');




$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}



elseif ((isset($id_cliente)) && (isset($status)) && (isset($vendedor_id)))
{
//dd($status);

$pedidossearch = Pedido::select('id')->where([
'id_cliente'  => $id_cliente,
'status'  => $status,
'vendedor_id'  => $vendedor_id,
'consignado' => $consignado
//'cancelados' => $cancelados
])->pluck('id');

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos  = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;


// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}



elseif  ((isset($id_cliente))  && (isset($status)))
{


$pedidossearch = Pedido::select('id')->where([
'id_cliente'  => $id_cliente,
'status' => $status,
'consignado' => $consignado
//'cancelados' => $cancelados

])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif ((isset($id_cliente))  && (isset($vendedor_id)))
{


$pedidossearch = Pedido::select('id')->where([
'id_cliente'  => $id_cliente,
'vendedor_id' => $vendedor_id,
'consignado' => $consignado

])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif ((isset($id_cliente))  && (isset($cancelados)))
{


$pedidossearch = Pedido::select('id')->where([
'id_cliente'  => $id_cliente,
'cancelados' => $cancelados,
'consignado' => $consignado

])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif  (isset($id_cliente))
{


$pedidossearch = Pedido::select('id')->where([
'id_cliente'  => $id_cliente,
'consignado' => $consignado
//'cancelados' => $cancelados

])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif ((isset($vendedor_id))  && (isset($status))  && (isset($cancelados)))
{


$pedidossearch = Pedido::select('id')->where([
'vendedor_id'  => $vendedor_id,
'status'  => $status,
'cancelados' => $cancelados,
'consignado' => $consignado


])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif ((isset($vendedor_id))  && (isset($cancelados)))
{


$pedidossearch = Pedido::select('id')->where([
'vendedor_id'  => $vendedor_id,
'cancelados' => $cancelados,
'consignado' => $consignado


])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif ((isset($vendedor_id))  && (isset($status)))
{


$pedidossearch = Pedido::select('id')->where([
'vendedor_id'  => $vendedor_id,
'status' => $status,
'consignado' => $consignado


])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif (isset($vendedor_id))
{


$pedidossearch = Pedido::select('id')->where([
'vendedor_id'  => $vendedor_id,
'consignado' => $consignado


])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif ((isset($cancelados)) && (isset($status)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados'  => $cancelados,
'status' => $status,
'consignado' => $consignado


])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif (isset($cancelados))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados'  => $cancelados,
'consignado' => $consignado


])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}

elseif (isset($status))
{


$pedidossearch = Pedido::select('id')->where([
'status'  => $status,
'consignado' => $consignado


])->pluck('id');

//dd($pedidossearch);

// $produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

//$total_produtos = $produtossearch->sum('prod_preco_padrao');

//  $total_preco = $total_produtos;





//$produtossearch = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;




}



else 

{

$pedidossearch = Pedido::select('id')->where([
'id'	=> $pedido_id,
'consignado' => $consignado

])->pluck('id');

//dd($pedidossearch);



$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

// dd($produtossearch);

// $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
// $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();



$frete_total = $frete->sum('valor');

$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;


}




$compras = Pedido::where(
'id', '!=', NULL
// 'user_id' => Auth::id()
)->orderBy('id', 'desc')->paginate(5);

$totalPage = ($compras)->count();



$dataForm = $request->except('_token');
$pedidos = $pedido->search($dataForm);


$allsearch = $pedido->searchTotal($dataForm);
$totalSearch = ($allsearch)->count();
$totalPageSearch = ($pedidos)->count();




$dadosClientes=DB::table('clientes')->get();
$dadosVendedores=DB::table('vendedores')->get();
$dadosGroupsProduct=DB::table('groups_product')->get();
$dadosProducts=DB::table('products')->get();
$total = Pedido::all()->count();


return view('admin.pedidoResource.consignado', compact('dadosGroupsProduct', 'dataForm', 'dadosClientes', 'dadosVendedores', 'pedidos', 'compras', 'total', 'total_preco', 'totalSearch', 'id_cliente', 'vendedor_id', 'status', 'totalPageSearch', 'totalPage', 'soma_produtos', 'desconto', 'frete_total', 'cancelados'));

}




public function searchComissao(Request $request, Pedido $pedido)
{

$id_cliente = $request->id_cliente;

$vendedor_id = $request->vendedor_id;

$comissao = $request->comissao;

$status = $request->status;

$pedido_id = $request->id;

$ano = $request->updated_at;

$periodo = $request->periodo;

$consignado = $request->consignado;

//dd($consignado);

$cancelados = $request->cancelados;
   

//dd($ano);

if ((isset($cancelados)) && (isset($status)) && (isset($vendedor_id)) && (isset($id_cliente)) && (isset($comissao)) && (isset($ano)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente,
'comissao' => $comissao
])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($cancelados)) && (isset($status)) && (isset($vendedor_id)) && (isset($id_cliente)) && (isset($ano)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente



])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($cancelados)) && (isset($status)) && (isset($vendedor_id)) && (isset($id_cliente)) && (isset($comissao)) && (isset($ano)))

{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente,
'comissao' => $comissao

])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($cancelados)) && (isset($status)) && (isset($vendedor_id)) && (isset($id_cliente)) && (isset($ano)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente

])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($cancelados)) && (isset($status)) && (isset($vendedor_id)) && (isset($id_cliente)) && (isset($comissao)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente,
'comissao' => $comissao



])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ( (isset($cancelados)) && (isset($status)) && (isset($vendedor_id)) && (isset($id_cliente)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente



])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente)) && (isset($status)) && (isset($vendedor_id)) && (isset($comissao)) && (isset($ano)) && (isset($periodo)))
	
{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente,
'comissao' => $comissao

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente)) && (isset($status)) && (isset($vendedor_id)) && (isset($ano)) && (isset($periodo)))

{

$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente
])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente)) && (isset($status)) && (isset($vendedor_id)) && (isset($comissao)) && (isset($ano)))

{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente,
'comissao' => $comissao



])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente)) && (isset($status)) && (isset($vendedor_id)) && (isset($ano)))
{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente

])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente)) && (isset($status)) && (isset($vendedor_id)) && (isset($comissao))  && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente,
'comissao' => $comissao

])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente)) && (isset($status)) && (isset($vendedor_id))  && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente

])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente))  && (isset($status)) && (isset($comissao)) && (isset($ano)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'id_cliente'  => $id_cliente,
'comissao' => $comissao

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente))  && (isset($status)) && (isset($ano)) && (isset($periodo)))
{

$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'id_cliente'  => $id_cliente

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente))  && (isset($status))  && (isset($comissao)) && (isset($ano)))
{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'id_cliente'  => $id_cliente,
'comissao' => $comissao

])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente))  && (isset($status)) && (isset($ano)))
{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'id_cliente'  => $id_cliente

])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($id_cliente))  && (isset($status))  && (isset($comissao)) && (isset($periodo)))	
{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'id_cliente'  => $id_cliente,
'comissao' => $comissao

])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente))  && (isset($status)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'id_cliente'  => $id_cliente

])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($id_cliente))  && (isset($vendedor_id)) && (isset($comissao)) && (isset($ano)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente,
'comissao' => $comissao

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente))  && (isset($vendedor_id)) && (isset($ano)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([

'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente))  && (isset($vendedor_id)) && (isset($comissao)) && (isset($ano)))
{

$pedidossearch = Pedido::select('id')->where([

'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente,
'comissao' => $comissao

])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente))  && (isset($vendedor_id)) && (isset($ano)))
{


$pedidossearch = Pedido::select('id')->where([

'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente

])->whereYear('updated_at', $ano)->pluck('id');


$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente))  && (isset($vendedor_id)) && (isset($comissao)) && (isset($periodo)))
{

$pedidossearch = Pedido::select('id')->where([

'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente,
'comissao' => $comissao

])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente)) && (isset($vendedor_id)) && (isset($periodo)))

{


$pedidossearch = Pedido::select('id')->where([

'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente

])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}		

elseif ((isset($id_cliente))  && (isset($cancelados)) && (isset($comissao)) && (isset($ano)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'id_cliente'  => $id_cliente,
'comissao' => $comissao

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente))  && (isset($cancelados)) && (isset($ano)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'id_cliente'  => $id_cliente

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($id_cliente))  && (isset($cancelados)) && (isset($comissao)) && (isset($ano)))
{

$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'id_cliente'  => $id_cliente,
'comissao' => $comissao

])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente))  && (isset($cancelados)) && (isset($ano)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'id_cliente'  => $id_cliente

])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($id_cliente))  && (isset($cancelados))  && (isset($comissao)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'id_cliente'  => $id_cliente,
'comissao' => $comissao



])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($id_cliente))  && (isset($cancelados)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'id_cliente'  => $id_cliente

])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($id_cliente)) && (isset($ano)) && (isset($comissao)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([

'id_cliente'  => $id_cliente,
'comissao' => $comissao

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($id_cliente)) && (isset($ano)) && (isset($periodo)))
{

$pedidossearch = Pedido::select('id')->where([
'id_cliente'  => $id_cliente

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente)) && (isset($comissao)) && (isset($ano)))
{

$pedidossearch = Pedido::select('id')->where([

'id_cliente'  => $id_cliente,
'comissao' => $comissao



])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($id_cliente)) && (isset($ano)))
{

$pedidossearch = Pedido::select('id')->where([

'id_cliente'  => $id_cliente


])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente)) && (isset($comissao)) (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([

'id_cliente'  => $id_cliente,
'comissao' => $comissao

])->whereMonth('updated_at', $periodo)->pluck('id');


$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($id_cliente)) && (isset($periodo)))
{

$pedidossearch = Pedido::select('id')->where([

'id_cliente'  => $id_cliente

])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id))  && (isset($status)) && (isset($comissao))  && (isset($cancelados)) && (isset($ano)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id,
'comissao' => $comissao

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id))  && (isset($status))  && (isset($cancelados)) && (isset($ano)) && (isset($periodo)))
{

$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id
])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($vendedor_id))  && (isset($status)) && (isset($comissao))  && (isset($cancelados)) && (isset($ano)))
{

$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id,
'comissao' => $comissao

])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id))  && (isset($status))  && (isset($cancelados)) && (isset($ano)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id

])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id))  && (isset($status)) && (isset($comissao))  && (isset($cancelados)) && (isset($periodo)))
{

$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id,
'comissao' => $comissao

])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id))  && (isset($status))  && (isset($cancelados)) && (isset($periodo)))
{

$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id

])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id))  && (isset($cancelados)) && (isset($comissao)) && (isset($ano)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'vendedor_id' => $vendedor_id,
'comissao' => $comissao

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id))  && (isset($cancelados)) && (isset($ano)) && (isset($periodo)))
{

$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'vendedor_id' => $vendedor_id


])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($vendedor_id))  && (isset($cancelados)) && (isset($comissao)) && (isset($ano)))
{

$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'vendedor_id' => $vendedor_id,
'comissao' => $comissao


])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($vendedor_id))  && (isset($cancelados)) && (isset($ano)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'vendedor_id' => $vendedor_id


])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id))  && (isset($cancelados)) && (isset($comissao)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'vendedor_id' => $vendedor_id,
'comissao' => $comissao

])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($vendedor_id))  && (isset($cancelados)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'vendedor_id' => $vendedor_id

])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($vendedor_id))  && (isset($status))  && (isset($ano)) && (isset($comissao)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'vendedor_id' => $vendedor_id,
'comissao' => $comissao,
'consignado' => "N"

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete

$contarpedido = count($pedidossearch);

if ((isset($vendedor_id)) && (isset($ano)) && (isset($periodo)) && ($status == 'FI') && ($comissao == 'PE') && ($contarpedido > 0))   {

$request->session()->flash('mensagem-sucesso', 'Foi gerado um novo lote para pagamento de comissão, clique no botão no canto direito da tela e conclua o pagamento para liberar o relatório.');	
}



}

elseif ((isset($vendedor_id))  && (isset($status))  && (isset($ano)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'vendedor_id' => $vendedor_id

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');


//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

//dd($produtossearch);

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id))  && (isset($status)) && (isset($comissao))  && (isset($ano)))
{



$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'vendedor_id' => $vendedor_id,
'comissao' => $comissao

])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($vendedor_id))  && (isset($status))  && (isset($ano)))
{



$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'vendedor_id' => $vendedor_id

])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

//dd($pedidossearch);

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id))  && (isset($status)) && (isset($comissao)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'vendedor_id' => $vendedor_id,
'comissao' => $comissao


])->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id))  && (isset($status)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'vendedor_id' => $vendedor_id

])->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id)) && (isset($ano)) && (isset($comissao)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $vendedor_id,
'comissao' => $comissao
])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');


//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id)) && (isset($ano)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $vendedor_id

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id)) && (isset($comissao)) && (isset($ano)))
{



$pedidossearch = Pedido::select('id')->where([

'vendedor_id' => $vendedor_id,
'comissao' => $comissao

])->whereYear('updated_at', $ano)->pluck('id');

//dd($pedidossearch);


$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id)) && (isset($ano)))
{



$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $vendedor_id

])->whereYear('updated_at', $ano)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id)) && (isset($periodo)) && (isset($comissao)))
{



$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $vendedor_id,
'comissao' => $comissao,
//'updated_at' => $periodo


])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($vendedor_id)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $vendedor_id

])->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($cancelados)) && (isset($status)) && (isset($comissao)) && (isset($ano)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'comissao' => $comissao,
'consignado' => "N"

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($cancelados)) && (isset($status)) && (isset($ano)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'consignado' => "N"

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($cancelados)) && (isset($status)) && (isset($ano)) && (isset($comissao)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'comissao' => $comissao,
'consignado' => "N"

])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($cancelados)) && (isset($status)) && (isset($ano)) )
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'consignado' => "N"

])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($cancelados)) && (isset($status)) && (isset($comissao)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'comissao' => $comissao,
'consignado' => "N"

])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($cancelados)) && (isset($status)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'consignado' => "N"

])->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($cancelados)) && (isset($comissao)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'comissao' => $comissao,
'consignado' => "N"

])->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($cancelados)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'consignado' => "N"
])->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($status)) && (isset($comissao))  && (isset($ano)) && (isset($periodo)))

{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'comissao' => $comissao,
'consignado' => "N"

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($status)) && (isset($ano)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'consignado' => "N"
])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($status)) && (isset($comissao))  && (isset($ano)))
{



$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'comissao' => $comissao,
'consignado' => "N"

])->whereYear('updated_at', $ano)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($status)) && (isset($comissao))  && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'comissao' => $comissao,
'consignado' => "N"
])->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($cancelados)) && (isset($ano)) && (isset($comissao)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'comissao' => $comissao,
'consignado' => "N"

])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($cancelados)) && (isset($ano)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'consignado' => "N"
])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($cancelados)) && (isset($comissao)) && (isset($ano)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'comissao' => $comissao,
'consignado' => "N"


])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}
elseif ((isset($cancelados)) && (isset($ano)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'consignado' => "N"


])->whereYear('updated_at', $ano)->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($status))  && (isset($ano)))
{



$pedidossearch = Pedido::select('id')->where([

'status' => $status,
'consignado' => "N"

])->whereYear('updated_at', $ano)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($ano)) && (isset($periodo)) && (isset($comissao)))
{

$pedidossearch = Pedido::select('id')->where([
'comissao' => $comissao,
'consignado' => "N"
])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete


}

elseif ((isset($periodo)) && (isset($status)))

{



$pedidossearch = Pedido::select('id')->where(['status' => $status, 'consignado' => "N"])->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);
//whereYear
//whereMonth

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}



elseif ((isset($ano)) && (isset($comissao)))

{
	

$pedidossearch = Pedido::select('id')->where([
'comissao'  => $comissao,
'consignado' => "N"
])->whereYear('updated_at', $ano)->pluck('id');

//dd($pedidossearch);


$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

// dd($produtossearch);

// $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
// $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete


}

elseif ((isset($periodo)) && (isset($comissao)))

{
	

$pedidossearch = Pedido::select('id')->where([
//'updated_at' => $periodo,
'comissao'  => $comissao,
'consignado' => "N"
])->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);


$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

// dd($produtossearch);

// $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
// $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete


}


elseif ((isset($periodo)) && (isset($ano)))
{



$pedidossearch = Pedido::select('id')->where(['consignado' => "N"])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

//dd($pedidossearch);

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete


}




elseif (isset($ano))

{


//$pedidossearch = Pedido::select('id')->where('updated_at', 'like', $ano.'%')->pluck('id');
$pedidossearch = Pedido::select('id')->where(['consignado' => "N"])->whereYear('updated_at', $ano)->pluck('id');

//$pedidossearch = DB::table('pedidos')->where('updated_at', 'like', '2020%')->get();

//dd($pedidossearch);


$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

// dd($produtossearch);

// $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
// $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete


}


elseif (isset($periodo))

{


$pedidossearch = Pedido::select('id')->where(['consignado' => "N"])->whereMonth('updated_at', $periodo)->pluck('id');

//$pedidossearch = DB::table('pedidos')->where('updated_at', 'like', '2020%')->get();

//dd($pedidossearch);


$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

// dd($produtossearch);

// $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
// $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete


}


elseif ( (isset($cancelados)) && (isset($status)) && (isset($comissao)) && (isset($vendedor_id)) && (isset($id_cliente)))

{
//dd($status);

$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente,
'comissao' => $comissao


])->pluck('id');




$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete


}

elseif ( (isset($cancelados)) && (isset($status)) && (isset($vendedor_id)) && (isset($id_cliente)))

{
//dd($status);


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente


])->pluck('id');



$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete


}

elseif ((isset($id_cliente)) && (isset($comissao))  && (isset($status)) && (isset($vendedor_id)))

	{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente,
'comissao' => $comissao


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete



}


elseif ((isset($id_cliente)) && (isset($status)) && (isset($vendedor_id))) 
{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete



}


elseif  ((isset($id_cliente))  && (isset($status)) && (isset($comissao)))

	{



$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'id_cliente'  => $id_cliente,
'comissao' => $comissao


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete


}




elseif  ((isset($id_cliente))  && (isset($status)))
{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'id_cliente'  => $id_cliente


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete


}

elseif ((isset($id_cliente))  && (isset($vendedor_id)) && (isset($comissao)))

{


$pedidossearch = Pedido::select('id')->where([

'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente,
'comissao' => $comissao

])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($id_cliente))  && (isset($vendedor_id)))
{



$pedidossearch = Pedido::select('id')->where([

'vendedor_id' => $vendedor_id,
'id_cliente'  => $id_cliente


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($id_cliente))  && (isset($cancelados)) && (isset($comissao)))

{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'id_cliente'  => $id_cliente,
'comissao' => $comissao


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete


}

elseif ((isset($id_cliente))  && (isset($cancelados)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'id_cliente'  => $id_cliente


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete


}

elseif  ((isset($id_cliente)) && (isset($comissao)))

{


$pedidossearch = Pedido::select('id')->where([

'id_cliente'  => $id_cliente,
'comissao' => $comissao

])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete

}

elseif  (isset($id_cliente))
{



$pedidossearch = Pedido::select('id')->where([

'id_cliente'  => $id_cliente


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete



}

elseif ((isset($vendedor_id))  && (isset($status))  && (isset($cancelados)) && (isset($comissao)))

	{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id,
'comissao' => $comissao


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete

}


elseif ((isset($vendedor_id))  && (isset($status))  && (isset($cancelados)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'vendedor_id' => $vendedor_id


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete



}
elseif ((isset($vendedor_id))  && (isset($cancelados)) && (isset($comissao)))

{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'vendedor_id' => $vendedor_id,
'comissao' => $comissao

])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete

}

elseif ((isset($vendedor_id))  && (isset($cancelados)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'vendedor_id' => $vendedor_id


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($vendedor_id))  && (isset($status)) && (isset($comissao)))
	{


$pedidossearch = Pedido::select('id')->where([

'status' => $status,
'vendedor_id' => $vendedor_id,
'comissao' => $comissao,
'consignado' => "N"


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete

}



elseif ((isset($vendedor_id))  && (isset($status)))
{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'vendedor_id' => $vendedor_id,
'consignado' => "N"

])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete



}

elseif ((isset($vendedor_id)) && (isset($comissao)))

{


$pedidossearch = Pedido::select('id')->where([

'vendedor_id' => $vendedor_id,
'comissao' => $comissao,
'consignado' => "N"


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete

}

elseif (isset($vendedor_id))
{



$pedidossearch = Pedido::select('id')->where([

'vendedor_id' => $vendedor_id,
'consignado' => "N"


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete



}

elseif ((isset($cancelados)) && (isset($status)) && (isset($comissao)))

{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
'comissao' => $comissao


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete

}

elseif ((isset($cancelados)) && (isset($status)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status


])->pluck('id');

//dd($pedidossearch);
$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete




}

elseif ((isset($cancelados)) && (isset($comissao)))

{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'comissao' => $comissao


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete

}

elseif (isset($cancelados))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados

])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete

}


elseif ((isset($status)) && (isset($comissao)))
{


$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'comissao' => $comissao,
'consignado' => "N"


])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete

}



elseif (isset($comissao))
{


$pedidossearch = Pedido::select('id')->where([
'comissao' => $comissao,
'consignado' => "N"
])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete

}

elseif (isset($status))
{



$pedidossearch = Pedido::select('id')->where([
'status' => $status,
'consignado' => "N"

])->pluck('id');

$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete


}



else 
{

$pedidossearch = Pedido::select('id')->where([
'id'	=> $pedido_id,
'consignado' => "N"


])->pluck('id');

//dd($pedidossearch);



$comissaosearch  = Comissao::whereIn('pedido_id', $pedidossearch)->get();

$total_comissao = $comissaosearch->sum('valor_comissao');

$geral = $total_comissao;

$total_preco = $geral;

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();
$total_produtos = $produtossearch->sum('prod_preco_padrao');

$total_preco_prod = $total_produtos;  //Total Produto

$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request; //Total Desconto

$frete  = Frete::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+Frete


}



$compras = Pedido::where(
'id', '!=', NULL
// 'user_id' => Auth::id()
)->orderBy('id', 'desc')->paginate(5);

$totalPage = ($compras)->count();

$dataForm = $request->except('_token');

$pedidos = $pedido->search($dataForm);

//dd($pedidos);

$allsearch = $pedido->searchTotal($dataForm);
$totalSearch = ($allsearch)->count();
$totalPageSearch = ($pedidos)->count();



$dadosPedidos=DB::table('pedidos')->get();

$dadosClientes=DB::table('clientes')->get();
$dadosVendedores=DB::table('vendedores')->get();
$dadosGroupsProduct=DB::table('groups_product')->get();
$dadosProducts=DB::table('products')->get();



return view('admin.pedidoResource.comissoes', compact('dadosGroupsProduct', 'dataForm', 'dadosClientes', 'dadosVendedores', 'pedidos', 'compras', 'total', 'total_preco','totalSearch', 'id_cliente', 'vendedor_id', 'comissao', 'totalPageSearch', 'totalPage', 'total_preco_prod', 'desconto', 'dadosPedidos', 'status', 'ano', 'periodo', 'frete_total'));

}

public function searchItens(Request $request, ItensPedido $iten)
{

$tipo = $request->tipo;

$item_id = $request->item_id;

$id_cliente = $request->id_cliente;

$vendedor_id = $request->vendedor_id;

$comissao = $request->comissao;

$status = $request->status;

$pedido_id = $request->id;

$ano = $request->ano;

$periodo = $request->periodo;

$consignado = $request->consignado;

if (empty($consignado))
{
$consignado = 'N';
}
else
{
$consignado = $request->consignado;
}


//$cancelados = $request->cancelados;

$cliente = 'Cliente';
$vendedor = 'Vendedor';

if (empty($tipo))
{
$tipo = 'P';
}
else
{
$tipo = $request->tipo;
}

if($tipo == 'P')
{
$pedidos=DB::table('pedidos')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereNotNull('produto_id')
->get();

$dadosClientes = Pedido::where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereNotNull('produto_id')
->select(\DB::raw('id_cliente as id_cliente'))
->groupBy('id_cliente')
->orderBy('id_cliente', 'asc')
->get();

$dadosVendedores = Pedido::where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereNotNull('produto_id')
->select(\DB::raw('vendedor_id as vendedor_id'))
->groupBy('vendedor_id')
->orderBy('vendedor_id', 'asc')
->get();

}
else
{

$pedidos=DB::table('pedidos')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereNotNull('request_id')
->get();

$dadosClientes = Pedido::where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereNotNull('request_id')
->select(\DB::raw('id_cliente as id_cliente'))
->groupBy('id_cliente')
->orderBy('id_cliente', 'asc')
->get();

$dadosVendedores = Pedido::where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereNotNull('request_id')
->select(\DB::raw('vendedor_id as vendedor_id'))
->groupBy('vendedor_id')
->orderBy('vendedor_id', 'asc')
->get();

}

$pedidossearch = Pedido::select('id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->pluck('id');


if ((isset($ano)) && (isset($periodo)) && (isset($id_cliente)))

{

$pedidossearch = Pedido::select('id', 'id_cliente', 'vendedor_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->whereMonth('updated_at', $periodo)
->where('id_cliente', '=', $id_cliente)
->get(); 


if($tipo == 'P')
{

$pedidos=DB::table('pedidos')
->whereNotNull('produto_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->whereMonth('updated_at', $periodo)
->where('id_cliente', '=', $id_cliente)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
->orderBy('quantidade', 'desc')->get();
}
else
{
$pedidos=DB::table('pedidos')
->whereNotNull('request_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->whereMonth('updated_at', $periodo)
->where('id_cliente', '=', $id_cliente)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('request_id, sum(prod_preco_padrao) as total, sum(request_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('request_id')	
->orderBy('quantidade', 'desc')->get();

}

$cliente = Cliente::where('id', '=', $id_cliente)->get();


$pedidossearch = Pedido::select('id', 'id_cliente', 'vendedor_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->whereMonth('updated_at', $periodo)
->get(); 

$searchCliente = $pedidossearch->pluck('id');


$dadosClientes = Pedido::whereIn('id', $searchCliente)
->select(\DB::raw('id_cliente as id_cliente'))
->groupBy('id_cliente')	
->orderBy('id_cliente', 'desc')
->get();

$searchVendedor = $pedidossearch->pluck('vendedor_id');

$vendedor = Vendedor::where('id', '=', $searchVendedor)->get();


$searchVendedor = $pedidossearch->pluck('id');

$dadosVendedores = Pedido::whereIn('id', $searchVendedor)
->select(\DB::raw('vendedor_id as vendedor_id'))
->groupBy('vendedor_id')	
->orderBy('vendedor_id', 'desc')
->get();

}

elseif ((isset($ano)) && (isset($id_cliente)))
{

$pedidossearch = Pedido::select('id', 'id_cliente', 'vendedor_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->where('id_cliente', '=', $id_cliente)
->get(); 


if($tipo == 'P')
{

$pedidos=DB::table('pedidos')
->whereNotNull('produto_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->where('id_cliente', '=', $id_cliente)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
->orderBy('quantidade', 'desc')->get();
}
else
{
$pedidos=DB::table('pedidos')
->whereNotNull('request_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->where('id_cliente', '=', $id_cliente)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('request_id, sum(prod_preco_padrao) as total, sum(request_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('request_id')	
->orderBy('quantidade', 'desc')->get();

}

$cliente = Cliente::where('id', '=', $id_cliente)->get();


$pedidossearch = Pedido::select('id', 'id_cliente', 'vendedor_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->get(); 

$searchCliente = $pedidossearch->pluck('id');


$dadosClientes = Pedido::whereIn('id', $searchCliente)
->select(\DB::raw('id_cliente as id_cliente'))
->groupBy('id_cliente')	
->orderBy('id_cliente', 'desc')
->get();

$searchVendedor = $pedidossearch->pluck('vendedor_id');

$vendedor = Vendedor::where('id', '=', $searchVendedor)->get();


$searchVendedor = $pedidossearch->pluck('id');

$dadosVendedores = Pedido::whereIn('id', $searchVendedor)
->select(\DB::raw('vendedor_id as vendedor_id'))
->groupBy('vendedor_id')	
->orderBy('vendedor_id', 'desc')
->get();

}

elseif ((isset($periodo)) && (isset($id_cliente)))

{

$pedidossearch = Pedido::select('id', 'id_cliente', 'vendedor_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereMonth('updated_at', $periodo)
->where('id_cliente', '=', $id_cliente)
->get(); 


if($tipo == 'P')
{

$pedidos=DB::table('pedidos')
->whereNotNull('produto_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereMonth('updated_at', $periodo)
->where('id_cliente', '=', $id_cliente)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
->orderBy('quantidade', 'desc')->get();
}
else
{
$pedidos=DB::table('pedidos')
->whereNotNull('request_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereMonth('updated_at', $periodo)
->where('id_cliente', '=', $id_cliente)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('request_id, sum(prod_preco_padrao) as total, sum(request_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('request_id')	
->orderBy('quantidade', 'desc')->get();

}

$cliente = Cliente::where('id', '=', $id_cliente)->get();


$pedidossearch = Pedido::select('id', 'id_cliente', 'vendedor_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereMonth('updated_at', $periodo)
->get(); 

$searchCliente = $pedidossearch->pluck('id');


$dadosClientes = Pedido::whereIn('id', $searchCliente)
->select(\DB::raw('id_cliente as id_cliente'))
->groupBy('id_cliente')	
->orderBy('id_cliente', 'desc')
->get();

$searchVendedor = $pedidossearch->pluck('vendedor_id');

$vendedor = Vendedor::where('id', '=', $searchVendedor)->get();


$searchVendedor = $pedidossearch->pluck('id');

$dadosVendedores = Pedido::whereIn('id', $searchVendedor)
->select(\DB::raw('vendedor_id as vendedor_id'))
->groupBy('vendedor_id')	
->orderBy('vendedor_id', 'desc')
->get();
}

elseif ((isset($ano)) && (isset($periodo)) && (isset($vendedor_id)))

{

$pedidossearch = Pedido::select('id', 'id_cliente', 'vendedor_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->whereMonth('updated_at', $periodo)
->where('vendedor_id', '=', $vendedor_id)
->get(); 


//dd($pedidossearch);

if($tipo == 'P')
{

$pedidos=DB::table('pedidos')
->whereNotNull('produto_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->whereMonth('updated_at', $periodo)
->where('vendedor_id', '=', $vendedor_id)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
->orderBy('quantidade', 'desc')->get();
}
else
{
$pedidos=DB::table('pedidos')
->whereNotNull('request_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->whereMonth('updated_at', $periodo)
->where('vendedor_id', '=', $vendedor_id)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('request_id, sum(prod_preco_padrao) as total, sum(request_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('request_id')	
->orderBy('quantidade', 'desc')->get();

}

//$cliente = Cliente::where('id', '=', $id_cliente)->get();


$pedidossearch = Pedido::select('id', 'id_cliente', 'vendedor_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->whereMonth('updated_at', $periodo)
->get(); 

$searchCliente = $pedidossearch->pluck('id');


$dadosClientes = Pedido::whereIn('id', $searchCliente)
->select(\DB::raw('id_cliente as id_cliente'))
->groupBy('id_cliente')	
->orderBy('id_cliente', 'desc')
->get();

$vendedor = Vendedor::where('id', '=', $vendedor_id)->get();

$searchVendedor = $pedidossearch->pluck('id');

$dadosVendedores = Pedido::whereIn('id', $searchVendedor)
->select(\DB::raw('vendedor_id as vendedor_id'))
->groupBy('vendedor_id')	
->orderBy('vendedor_id', 'desc')
->get();


}

elseif ((isset($ano)) && (isset($vendedor_id)))

{

$pedidossearch = Pedido::select('id', 'id_cliente', 'vendedor_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->where('vendedor_id', '=', $vendedor_id)
->get(); 


//dd($pedidossearch);

if($tipo == 'P')
{

$pedidos=DB::table('pedidos')
->whereNotNull('produto_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->where('vendedor_id', '=', $vendedor_id)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
->orderBy('quantidade', 'desc')->get();
}
else
{
$pedidos=DB::table('pedidos')
->whereNotNull('request_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->where('vendedor_id', '=', $vendedor_id)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('request_id, sum(prod_preco_padrao) as total, sum(request_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('request_id')	
->orderBy('quantidade', 'desc')->get();

}

//$cliente = Cliente::where('id', '=', $id_cliente)->get();


$pedidossearch = Pedido::select('id', 'id_cliente', 'vendedor_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->get(); 

$searchCliente = $pedidossearch->pluck('id');


$dadosClientes = Pedido::whereIn('id', $searchCliente)
->select(\DB::raw('id_cliente as id_cliente'))
->groupBy('id_cliente')	
->orderBy('id_cliente', 'desc')
->get();

$vendedor = Vendedor::where('id', '=', $vendedor_id)->get();

$searchVendedor = $pedidossearch->pluck('id');

$dadosVendedores = Pedido::whereIn('id', $searchVendedor)
->select(\DB::raw('vendedor_id as vendedor_id'))
->groupBy('vendedor_id')	
->orderBy('vendedor_id', 'desc')
->get();


}

elseif ((isset($periodo)) && (isset($vendedor_id)))
{
$pedidossearch = Pedido::select('id', 'id_cliente', 'vendedor_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
//->whereYear('updated_at', $ano)
->whereMonth('updated_at', $periodo)
->where('vendedor_id', '=', $vendedor_id)
->get(); 


//dd($pedidossearch);

if($tipo == 'P')
{

$pedidos=DB::table('pedidos')
->whereNotNull('produto_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
//->whereYear('updated_at', $ano)
->whereMonth('updated_at', $periodo)
->where('vendedor_id', '=', $vendedor_id)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
->orderBy('quantidade', 'desc')->get();
}
else
{
$pedidos=DB::table('pedidos')
->whereNotNull('request_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
//->whereYear('updated_at', $ano)
->whereMonth('updated_at', $periodo)
->where('vendedor_id', '=', $vendedor_id)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('request_id, sum(prod_preco_padrao) as total, sum(request_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('request_id')	
->orderBy('quantidade', 'desc')->get();

}

//$cliente = Cliente::where('id', '=', $id_cliente)->get();


$pedidossearch = Pedido::select('id', 'id_cliente', 'vendedor_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
//->whereYear('updated_at', $ano)
->whereMonth('updated_at', $periodo)
->get(); 

$searchCliente = $pedidossearch->pluck('id');


$dadosClientes = Pedido::whereIn('id', $searchCliente)
->select(\DB::raw('id_cliente as id_cliente'))
->groupBy('id_cliente')	
->orderBy('id_cliente', 'desc')
->get();

$vendedor = Vendedor::where('id', '=', $vendedor_id)->get();

$searchVendedor = $pedidossearch->pluck('id');

$dadosVendedores = Pedido::whereIn('id', $searchVendedor)
->select(\DB::raw('vendedor_id as vendedor_id'))
->groupBy('vendedor_id')	
->orderBy('vendedor_id', 'desc')
->get();


}

elseif ((isset($ano)) && (isset($periodo)))
{

//dd($consignado);

$pedidossearch = Pedido::select('id', 'vendedor_id')
->whereYear('updated_at', $ano)
->whereMonth('updated_at', $periodo)
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->get(); 


if($tipo == 'P')
{
$pedidos=DB::table('pedidos')
->whereNotNull('produto_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->whereMonth('updated_at', $periodo)->get();

$searchItem = $pedidossearch->pluck('id');



$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
->orderBy('quantidade', 'desc')
->get();

$searchCliente = $pedidossearch->pluck('id');


$dadosClientes = Pedido::whereIn('id', $searchCliente)
->select(\DB::raw('id_cliente as id_cliente'))
->groupBy('id_cliente')	
->orderBy('id_cliente', 'desc')
->get();


$searchVendedor = $pedidossearch->pluck('id');

$dadosVendedores = Pedido::whereIn('id', $searchVendedor)
->select(\DB::raw('vendedor_id as vendedor_id'))
->groupBy('vendedor_id')	
->orderBy('vendedor_id', 'desc')
->get();

}

else

{

$pedidos=DB::table('pedidos')
->whereNotNull('request_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->whereMonth('updated_at', $periodo)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('request_id, sum(prod_preco_padrao) as total, sum(request_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('request_id')	
->orderBy('quantidade', 'desc')->get();

$searchCliente = $pedidossearch->pluck('id');

$dadosClientes = Pedido::whereIn('id', $searchCliente)
->select(\DB::raw('id_cliente as id_cliente'))
->groupBy('id_cliente')	
->orderBy('id_cliente', 'desc')
->get();


$searchVendedor = $pedidossearch->pluck('id');

$dadosVendedores = Pedido::whereIn('id', $searchVendedor)
->select(\DB::raw('vendedor_id as vendedor_id'))
->groupBy('vendedor_id')	
->orderBy('vendedor_id', 'desc')
->get();

}


}


elseif (isset($ano))

{

$pedidossearch = Pedido::select('id', 'id_cliente', 'vendedor_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->get(); 

if($tipo == 'P')
{

$pedidos=DB::table('pedidos')
->whereNotNull('produto_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
->orderBy('quantidade', 'desc')->get();
}
else
{
$pedidos=DB::table('pedidos')
->whereNotNull('request_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereYear('updated_at', $ano)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereYear('updated_at', $ano)
->select(\DB::raw('request_id, sum(prod_preco_padrao) as total, sum(request_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('request_id')	
->orderBy('quantidade', 'desc')->get();

}


}

elseif (isset($periodo))

{

$pedidossearch = Pedido::select('id', 'id_cliente', 'vendedor_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereMonth('updated_at', $periodo)
->get(); 

if($tipo == 'P')
{
$pedidos=DB::table('pedidos')
->whereNotNull('produto_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereMonth('updated_at', $periodo)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
->orderBy('quantidade', 'desc')->get();
}
else
{
$pedidos=DB::table('pedidos')
->whereNotNull('request_id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereMonth('updated_at', $periodo)
->get();

$searchItem = $pedidossearch->pluck('id');

$produtos = ItensPedido::whereIn('pedido_id', $searchItem)
->where('tipo', '=', $tipo)
//->whereMonth('updated_at', $periodo)
->select(\DB::raw('request_id, sum(prod_preco_padrao) as total, sum(request_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('request_id')	
->orderBy('quantidade', 'desc')->get();

}



}


elseif (isset($pedido_id)) 

{

if($tipo == 'P')
{

$produtos = ItensPedido::whereIn('pedido_id', $pedidossearch)
->where('tipo', '=', $tipo)
->where('pedido_id', $pedido_id)
->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
->orderBy('quantidade', 'desc')->get();
}
else
{

$produtos = ItensPedido::whereIn('pedido_id', $pedidossearch)
->where('tipo', '=', $tipo)
->where('pedido_id', $pedido_id)
->select(\DB::raw('request_id, sum(prod_preco_padrao) as total, sum(request_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('request_id')	
->orderBy('quantidade', 'desc')->get();
}

//dd($dadosVendedores);

}


elseif (isset($id_cliente)) {

$pedidossearch = Pedido::where('id_cliente', '=', $id_cliente)
->select('id')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->pluck('id'); 

$cliente = Cliente::where('id', '=', $id_cliente)->get();

if ($tipo == 'P')
{
$produtos = ItensPedido::whereIn('pedido_id', $pedidossearch)
->where('tipo', '=', $tipo)
->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
->orderBy('quantidade', 'desc')->get();

}
else
{
$produtos = ItensPedido::whereIn('pedido_id', $pedidossearch)
->where('tipo', '=', $tipo)
->select(\DB::raw('request_id, sum(prod_preco_padrao) as total, sum(request_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('request_id')	
->orderBy('quantidade', 'desc')->get();

}


}

elseif (isset($vendedor_id))

{

$pedidossearch = Pedido::where('vendedor_id', '=', $vendedor_id)
->select('id')
->where('consignado', '=', 'N')
->where('status', '=', 'FI')
->pluck('id'); 

//dd($pedidossearch);
$vendedor = Vendedor::where('id', '=', $vendedor_id)->get();
//dd($cliente);

if ($tipo == 'P')
{
$produtos = ItensPedido::whereIn('pedido_id', $pedidossearch)
->where('tipo', '=', $tipo)
->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
->orderBy('quantidade', 'desc')->get();
}
else
{
$produtos = ItensPedido::whereIn('pedido_id', $pedidossearch)
->where('tipo', '=', $tipo)
->select(\DB::raw('request_id, sum(prod_preco_padrao) as total, sum(request_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('request_id')	
->orderBy('quantidade', 'desc')->get();
}

}

elseif (isset($item_id)) 

{

if ($tipo == 'P')
{
$produtos = ItensPedido::whereIn('pedido_id', $pedidossearch)
->where('tipo', '=', $tipo)
->where('produto_id', '=', $item_id)
->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
->orderBy('quantidade', 'desc')->get();
}
else
{
$produtos = ItensPedido::whereIn('pedido_id', $pedidossearch)
->where('tipo', '=', $tipo)
->where('request_id', '=', $item_id)
->select(\DB::raw('request_id, sum(prod_preco_padrao) as total, sum(request_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('request_id')	
->orderBy('quantidade', 'desc')->get();
}


}

else {


if($tipo == 'P')
{
$pedidos=DB::table('pedidos')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereNotNull('produto_id')
->get();

$produtos = ItensPedido::whereIn('pedido_id', $pedidossearch)
->where('tipo', '=', $tipo)
->select(\DB::raw('produto_id, sum(prod_preco_padrao) as total, sum(prod_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('produto_id')	
->orderBy('quantidade', 'desc')->get();
}
else
{

$pedidos=DB::table('pedidos')
->where('consignado', '=', $consignado)
->where('status', '=', 'FI')
->whereNotNull('request_id')
->get();

$produtos = ItensPedido::whereIn('pedido_id', $pedidossearch)
->where('tipo', '=', $tipo)
->select(\DB::raw('request_id, sum(prod_preco_padrao) as total, sum(request_desconto) as totalDesconto, count(1) as quantidade'))
->groupBy('request_id')
->orderBy('quantidade', 'desc')->get();
}


}

$dataForm = $request->except('_token');

$itenspedidos = $iten->search($dataForm);

$dadosPedidos=DB::table('pedidos')->where('consignado', '=', 'N')->where('status', '=', 'FI')->get();


$dadosGroupsProduct=DB::table('groups_product')->get();

$dadosProducts = Produto::all();



return view('admin.pedidoResource.itens-pedidos', compact('dadosGroupsProduct', 'dataForm', 'dadosClientes', 'dadosVendedores', 'pedido_id', 'consignado', 'tipo', 'produtos', 'vendedor', 'cliente', 'pedidos', 'id_cliente', 'vendedor_id', 'comissao', 'dadosPedidos', 'status', 'ano', 'periodo', 'item_id'));

}


public function findProductName(Request $request)
{

$data=Produto::select('prod_desc', 'id')->where('grup_cod', $request->id)->take(100)->get();

//if our chosen id and products table prod_cat_id
// $request->id here is the id of our chosen option id

return response()->json($data);
}

public function findProductCod(Request $request)

{

$p=Produto::select('prod_cod')->where('id', $request->id)->first();



return response()->json($p);
}


public function findVendId(Request $request)

{

$p=Cliente::select('vendedor_id')->where('id', $request->id)->first();



return response()->json($p);
}


public function findVendC(Request $request)

{

$data=Vendedor::select('comissao')->where('id', $request->id)->first();


return $data->comissao;
}

public function findVendName(Request $request)

{

$p=Cliente::select('vendedor_id')->where('id', $request->id)->pluck('vendedor_id');

$v=Vendedor::select('name')->where('id', $p)->first();

// $v=Vendedor::select('name')->where('id', $v)->first();



return response()->json($v);
}






}
