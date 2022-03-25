<?php

namespace App\Http\Controllers;

use App\Entities\Pedido;

use App\Entities\ProdutoInter;
use App\Entities\OpycosRequestInter;
use App\Entities\FreteInter;
use App\Entities\Comissao;
use App\Entities\Vendedor;
use App\Entities\ClienteInter;
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

class PedidoInterController extends Controller
{

public function __construct()
{
$this->middleware('auth');
}

public function index() {




$registros = ProdutoInter::where([
'ativo' => 's'
])->get();

$list_requisitions= OpycosRequestInter::where('status','=','FI')->get();


$pedidos = Pedido::where([
'status' => 'GE',
'vendedor_id' => Auth::id()
])->get();


$idvendedor = Auth::id();


$pedidos_produto = Pedido::where([
'status' => 'GE',
'vendedor_id' => Auth::id()
])->where( 'produto_id', '!=', NULL)->get();


//dd($pedidos_produto);

$pedidos_request=Pedido::select('id','id_cliente', 'request_id','status')->where('request_id', '!=', NULL)->where(['status' => 'GE',  'vendedor_id' => Auth::id()])->take(100)->get();


$dadosClientes= ClienteInter::where('status', '!=', 'A')->get();


$retiradaBalcPF = FreteInter::where([
'status' => 'AR',
'balcao' => 'Y',
'boolean' => 'Y',
'vendedor_id' => Auth::id()
])->get();




$requisitions = ItensPedido::where([
'status' => 'GE', //Gerado
'tipo' => 'R',
//    'vendedor_id' => Auth::id()
])->get();


$produtos = ItensPedido::where([
'status' => 'GE', //Gerado
'tipo' => 'P',
//   'vendedor_id' => Auth::id()
])->get();





// dd($retirada);

$freteB_PF = FreteInter::where([
'vendedor_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'B',
'boolean' => 'Y'
])->get();




$freteC_PF = FreteInter::where([
'vendedor_id' => Auth::id(),
'status' => 'C',
'entrega' => 'C',
'boolean' => 'Y'
])->get();



$valorFrete = DB::table('fretes')->select('valor')->where([
'vendedor_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'Y'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
'vendedor_id' => Auth::id(),
'status' => 'C',
'boolean' => 'Y'
])->get();

$e_pedido = Pedido::where([
'status' => 'GE',
'vendedor_id' => Auth::id()
])->first();



$pedidossearch = Pedido::where([
'status' => 'GE',
'vendedor_id' => Auth::id()
])->pluck('id');

$produtossearchP  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'P')->get();

$requestsearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->where('tipo', '=', 'R')->get();
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
}

return view('vendedor.pedidoResource.index', compact('registros', 'pedidos', 'dadosClientes', 'retiradaBalcPF', 'freteB_PF', 'freteC_PF', 'valorFrete','valorFreteC', 'list_requisitions', 'requisitions', 'produtos', 'pedidos_request', 'pedidos_produto', 'idvendedor', 'p_comissao', 'v_produto','d_produto', 'd_request', 'v_request', 'r_comissao', 'conte', 'conteR'));
}







public function adicionar()
{

$this->middleware('VerifyCsrfToken');
$req = Request();
$obspedido = $req->input('obs_pedido');
$idcliente = $req->input('id_cliente');
$idproduto = $req->input('id');
//$comissao = $req->input('comissao');

//$retirada = $req->input('balcao');
//$frete = $req->input('entrega');
$idrequest = $req->input('request_cod');
//dd($idrequest);
$quantidade_prod = $req->input('quantidade_produto');
$quantidade_req = $req->input('quantidade_request');
//$valor = $req->input('valor');
//  $valor = str_replace( ',', '.', $valor );
$tip = $req->input('boolean');

$validator = validator($req->all(),
[
'id_cliente' => 'required'
]);

if ( $validator->fails()){


$req->session()->flash('mensagem-falha', 'É preciso escolher um cliente.');
return redirect()->route('indexint')->withInput();
}

if (($idrequest == NULL) && ($idproduto == NULL))
{

$req->session()->flash('mensagem-falha', 'Escolha algum item.');
return redirect()->route('indexint')->withInput();

}


if (isset($idrequest) && isset($idproduto))
{

$req->session()->flash('mensagem-falha', 'Escolha um item por vez');
return redirect()->route('indexint')->withInput();


}


if ($idrequest == NULL)
{

$produto = ProdutoInter::find($idproduto);

if( empty($produto->id)) {
$req->session()->flash('mensagem-falha', 'Produto não pode ser Localizado');
return redirect()->route('indexint');
}

$idusuario = Auth::id();

$getcomissao = Vendedor::find($idusuario);
$comissao = $getcomissao->comissao;



$idpedido = Pedido::consultaId([
'vendedor_id' => $idusuario,
'status'  => 'GE' // Reservado
]);

if( empty($idpedido) ) {
$pedido_novo = Pedido::create([
'vendedor_id' => $idusuario,
'produto_id' => $idproduto,
'obs_pedido' => $obspedido,
'id_cliente' => $idcliente,
//'percentual_comissao' => $percent,

'status'  => 'GE' // Gerado
]);

$idpedido = $pedido_novo->id;

}


Pedido::where([
'id' => $idpedido,
'vendedor_id' => $idusuario
])->update([
'obs_pedido' => $obspedido,
'produto_id' => $idproduto
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
'comissao' => $comissao,
'status'     => 'GE'

]);
$contador++;
}



$req->session()->flash('mensagem-sucesso', 'Produtos adicionados!');

return redirect()->route('indexint');

} else {

ItensPedido::create([
'pedido_id'  => $idpedido,
'produto_id' => $idproduto,
'tipo' => 'P',
'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $produto->prod_preco_padrao,
'prod_preco_prof' => $produto->prod_preco_prof,
'comissao' => $comissao,
'status'     => 'GE'

]);

$req->session()->flash('mensagem-sucesso', 'Produto adicionado!');

return redirect()->route('indexint');


}





}

if ($idproduto == NULL)
{

$request = OpycosRequestInter::find($idrequest);

if( empty($request->id)) {
$req->session()->flash('mensagem-falha', 'Requisição não pode ser Localizada');
return redirect()->route('indexint');
}


$check_request = OpycosRequestInter::where([
'id' => $idrequest,
'id_cliente' => $idcliente
//'status' => 'FI'
])->exists();

if( !$check_request) {

$req->session()->flash('mensagem-falha', 'Requisição não pertence ao cliente');
return redirect()->route('indexint');

}



$idusuario = Auth::id();

$getcomissao = Vendedor::find($idusuario);
$comissao = $getcomissao->comissao;


$idpedido = Pedido::consultaId([
'vendedor_id' => $idusuario,
'status'  => 'GE' // Reservado
]);

if( empty($idpedido) ) {
$pedido_novo = Pedido::create([
'vendedor_id' => $idusuario,
//'pedido_cod' => $codpedido,
'obs_pedido' => $obspedido,
'id_cliente' => $idcliente,
'request_id' => $request->id,
'request_valor' => $request->request_valor,
'request_desc' => $request->req_desc,
//'percentual_comissao' => $percent,
'status'  => 'GE' // Gerado
]);

$idpedido = $pedido_novo->id;

}


Pedido::where([
'id' => $idpedido,
'vendedor_id' => $idusuario
])->update([
'obs_pedido' => $obspedido,
'request_id' => $idrequest
]);



OpycosRequestInter::where([
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
'pedido_id'  => $idpedido,
'request_id' => $idrequest,
'tipo' => 'R',
//'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $request->request_valor,
'comissao' => $comissao,
// 'prod_preco_prof' => $produto->prod_preco_prof,
'status'     => 'GE'

]);

$contador++;
}



$req->session()->flash('mensagem-sucesso', 'Requisições adicionadas!');

return redirect()->route('indexint');

} else {

ItensPedido::create([
'pedido_id'  => $idpedido,
'request_id' => $idrequest,
'tipo' => 'R',
//'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $request->request_valor,
'comissao' => $comissao,
// 'prod_preco_prof' => $produto->prod_preco_prof,
'status'     => 'GE'

]);

$req->session()->flash('mensagem-sucesso', 'Requisição adicionado!');

return redirect()->route('indexint');


}





}


}


public function adicionarEdit($id)
{

$this->middleware('VerifyCsrfToken');
$req = Request();
$obspedido = $req->input('obs_pedido');
$idcliente = $req->input('id_cliente');
$idproduto = $req->input('id');
//$retirada = $req->input('balcao');
//$frete = $req->input('entrega');
$idrequest = $req->input('request_cod');
//$quantidade =  $req->input('quantidade');
$quantidade_prod = $req->input('quantidade_produto');
$quantidade_req = $req->input('quantidade_request');
$idusuario = Auth::id();

$getcomissao = Vendedor::find($idusuario);
$comissao = $getcomissao->comissao;//Percentual de comissão.


//$valor = $req->input('valor');
//  $valor = str_replace( ',', '.', $valor );
$tip = $req->input('boolean');
$dataRegistro = $req->input('dataRegistro');
$dataAtual = $req->input('dataAtual');

if ($dataAtual > $dataRegistro){
$req->session()->flash('mensagem-falha', 'Pedido não pode mais ser alterado!');
return redirect()->route('pedidosint/{id}/edit', $id);
}

$validator = validator($req->all(),
[
'id_cliente' => 'required'
]);

if ( $validator->fails()){
$req->session()->flash('mensagem-falha', 'É preciso escolher um cliente.');
return redirect()->route('pedidosint/{id}/edit', $id)->withInput();
}

if (($idrequest == NULL) && ($idproduto == NULL))
{

$req->session()->flash('mensagem-falha', 'Escolha algum item.');
return redirect()->route('pedidosint/{id}/edit', $id)->withInput();

}


if (isset($idrequest) && isset($idproduto))
{

$req->session()->flash('mensagem-falha', 'Escolha um item por vez');
return redirect()->route('pedidosint/{id}/edit', $id)->withInput();

}


if ($idrequest == NULL)
{

$produto = ProdutoInter::find($idproduto);

if( empty($produto->id)) {
$req->session()->flash('mensagem-falha', 'Produto não pode ser Localizado');
return redirect()->route('pedidosint/{id}/edit', $id);
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



$req->session()->flash('mensagem-sucesso', 'Produtos adicionados!');

return redirect()->route('pedidosint/{id}/edit', $id);

} else {

ItensPedido::create([
'pedido_id'  => $id,
'produto_id' => $idproduto,
'tipo' => 'P',
'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $produto->prod_preco_padrao,
'prod_preco_prof' => $produto->prod_preco_prof,
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


$req->session()->flash('mensagem-sucesso', 'Produto adicionado!');

return redirect()->route('pedidosint/{id}/edit', $id);


}





}

if ($idproduto == NULL)
{

$request = OpycosRequestInter::find($idrequest);

if( empty($request->id)) {
$req->session()->flash('mensagem-falha', 'Requisição não pode ser Localizada');
return redirect()->route('pedidosint/{id}/edit', $id);
}


$check_request = OpycosRequestInter::where([
'id' => $idrequest,
'id_cliente' => $idcliente
//'status' => 'FI'
])->exists();

if( !$check_request) {

$req->session()->flash('mensagem-falha', 'Requisição não pertence ao cliente');
return redirect()->route('pedidosint/{id}/edit', $id);

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



OpycosRequestInter::where([
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
'comissao' => $comissao,
// 'prod_preco_prof' => $produto->prod_preco_prof,
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



$req->session()->flash('mensagem-sucesso', 'Requisições adicionadas!');

return redirect()->route('pedidosint/{id}/edit', $id);

} else {

ItensPedido::create([
'pedido_id'  => $id,
'request_id' => $idrequest,
'tipo' => 'R',
//'prod_preco_balcao' => $produto->prod_preco_balcao,
'prod_preco_padrao' => $request->request_valor,
'comissao' => $comissao,
// 'prod_preco_prof' => $produto->prod_preco_prof,
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

$req->session()->flash('mensagem-sucesso', 'Requisição adicionada!');

return redirect()->route('pedidosint/{id}/edit', $id);


}




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
return redirect()->route('pedidointer.compras');
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
return redirect()->route('pedidosint/{id}/edit', $id);


}


if( $status == 'AP' ) {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status

]);

$req->session()->flash('mensagem-sucesso', 'Pedido Aguardando confirmação de pagamento.');
return redirect()->route('pedidointer.compras');


}

if( $status == 'EL' ) {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status

]);

$req->session()->flash('mensagem-sucesso', 'Pedido Encaminhado ao Laboratório.');
return redirect()->route('pedidointer.compras');


}


if( $status == 'EC' ) {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status

]);

$req->session()->flash('mensagem-sucesso', 'Pedido Enviado ao cliente.');
return redirect()->route('pedidointer.compras');


}




if( $status == 'FI') {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status

]);

$req->session()->flash('mensagem-sucesso', 'Pedido Entregue ao cliente. Finalizado com sucesso!');
return redirect()->route('pedidointer.compras');


}

if( $status == 'CA') {

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id
// 'status'  => 'GE'
])->update([
'status' => $status

]);

$req->session()->flash('mensagem-sucesso', 'Pedido Cancelado pelo Cliente.');
return redirect()->route('pedidointer.compras');


}




}





public function removerEdit($id)
{

$this->middleware('VerifyCsrfToken');

$req = Request();
// $idpedido           = $req->input('pedido_id');
$idrequest          = $req->input('request_cod');
$idproduto          = $req->input('produto_id');
$dataRegistro = $req->input('dataRegistro');
$dataAtual = $req->input('dataAtual');
// dd($idproduto);
$remove_apenas_item = (boolean)$req->input('item');
$idusuario          = Auth::id();

$idpedido = Pedido::findOrFail($id);

$getcomissao = Pedido::find($id);
$percent = $getcomissao->percentual_comissao;//Percentual de comissão.

/* if( empty($idpedido) ) {
$req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
return redirect()->route('pedidos/{id}/edit', $id);
}*/

// dd($dataAtual);

if ($dataAtual > $dataRegistro){
$req->session()->flash('mensagem-falha', 'Pedido não pode mais ser alterado!');
return redirect()->route('pedidosint/{id}/edit', $id);
}


if ($idrequest == NULL)
{

$where_produto = [
'pedido_id'  => $id,
'produto_id' => $idproduto
];

$produto = ItensPedido::where($where_produto)->orderBy('id', 'desc')->first();
if( empty($produto->id) ) {
$req->session()->flash('mensagem-falha', 'Produto não encontrado no pedido!');
return redirect()->route('pedidosint/{id}/edit', $id);
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

$req->session()->flash('mensagem-sucesso', 'Produto removido!  Comissão Recalculada!.');

return redirect()->route('pedidosint/{id}/edit', $id);


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
return redirect()->route('pedidosint/{id}/edit', $id);
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

OpycosRequestInter::where([
'id' => $idrequest

])->update([
'status' => 'FI' // Finalizado
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

$req->session()->flash('mensagem-sucesso', 'Requisição removida! Comissão Recalculada!.');

return redirect()->route('pedidosint/{id}/edit', $id);


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
'vendedor_id' => $idusuario,
'status'  => 'GE' // Reservada
]);

if( empty($idpedido) ) {
$req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
return redirect()->route('indexint');
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
return redirect()->route('indexint');
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

$req->session()->flash('mensagem-sucesso', 'Produto removido!');

return redirect()->route('indexint');


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
return redirect()->route('indexint');
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

OpycosRequestInter::where([
'id' => $idrequest

])->update([
'status' => 'FI' // Finalizado
]);

$req->session()->flash('mensagem-sucesso', 'Requisição removida!');

return redirect()->route('indexint');


}


}



public function detalhes($id) {



$pedidos = Pedido::findOrFail($id);
$this->middleware('VerifyCsrfToken');
$req = Request();

$local = $req->input('local');

$cep = $req->input('cep');
$endereço = $req->input('endereço');
$numero = $req->input('numero');
$bairro = $req->input('bairro');
$complemento = $req->input('complemento');
$cidade = $req->input('cidade');
$estado = $req->input('estado');
$comissao = $req->input('comissao');



$obspedido = $req->input('obs_pedido');
$idcliente = $req->input('id_cliente');
$idvendedor = Auth::id();
$prazoentrega = $req->input('prazo_entrega');
$prazoentrega = str_replace( ' ', '', str_replace('Dias', '', $prazoentrega));
//$prazoentrega = $prazoentrega + 2;
$cdservico = $req->input('cdservico');
// $idusuario = $req->input('vendedor_id');
// $idproduto = $req->input('id');
$retirada = $req->input('balcao');
$frete = $req->input('entrega');
// $status = $req->input('status');
$valor = $req->input('valor');
$valor = str_replace( ',', '.', $valor );
$pagamento = $req->input('pagamento');
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



/* $produto = ProdutoInter::find($idproduto);
if( empty($produto->id) ) {
$req->session()->flash('mensagem-falha', 'ProdutoInter não encontrado em nossa loja!');
return redirect()->route('pedidos.index');
}*/


$check_pedido = Pedido::where([
'id'      => $id,
'vendedor_id' => $idusuario,
'status'  => 'GE' // Gerado
])->exists();

if( !$check_pedido ) {
$req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
return redirect()->route('indexint');
}


$idpedido = Pedido::consultaId([
'id' => $id,
'status'  => 'GE' // GERADO
]);

if( isset($idpedido) ) {

if( empty($pagamento) ) {
//  dd($pagamento);
$req->session()->flash('message', 'Preencha a forma de Pagamento!');
return redirect()->route('indexint')->withInput();
}

Pedido::where([
'vendedor_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id,
'status'  => 'GE'
])->update([
'pagamento' => $pagamento,
'vendedor_id' => $idvendedor,
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
'valor_comissao' => $comissao
//'percentual_comissao' => $percent
//'obs_comissao'
//'user_id' => $idusuario
]);

$idcomissao = $gera_comissao->id;

}


Comissao::where([
'id' => $idcomissao,
'vendedor_id' => $idvendedor
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
return redirect()->route('indexint')->withInput();

}




if ($frete == "B")
{

if ($valor == NULL) {
$req->session()->flash('message', 'Informar o custo do frete!');
return redirect()->route('indexint')->withInput();
}


if (isset($local))
{

FreteInter::create([
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
'vendedor_id' =>   $idusuario,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'B', //Boy "Moto Booy"
'valor' => $valor,
//  'prazo_entrega' => $prazoentrega,
'status' => 'EMB' //Entrega moto boy

]);

Pedido::where([
'vendedor_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id,
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);



$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedidointer.compras');

}

FreteInter::create([
'pedido_id'  => $id,
// 'produto_id' => $idproduto,
'id_cliente' =>  $idcliente,
'vendedor_id' =>   $idusuario,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'B', //Boy "Moto Booy"
'valor' => $valor,
//  'prazo_entrega' => $prazoentrega,
'status' => 'EMB' //Entrega moto boy

]);

Pedido::where([
'vendedor_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id,
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);




$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedidointer.compras');



}


if ($frete == "C")
{

if ($valor == NULL) {
$req->session()->flash('message', 'Informar o custo do frete!');
return redirect()->route('indexint')->withInput();


}


if (isset($local))

{

FreteInter::create([
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
'vendedor_id' =>   $idusuario,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'B', //Boy "Moto Booy"
'valor' => $valor,
'prazo_entrega' => $prazoentrega,
'serviço_correio' => $cdservico,
'status' => 'EMB' //Entrega moto boy

]);

Pedido::where([
'vendedor_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id,
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);

$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedidointer.compras');

}



FreteInter::create([
'pedido_id'  => $id,

// 'produto_id' => $idproduto,

'id_cliente' =>  $idcliente,
'vendedor_id' =>   $idusuario,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'C', //Correios
'valor' => $valor,
'prazo_entrega' => $prazoentrega,
'serviço_correio' => $cdservico,
'status' => 'EC' //Entrega Correios

]);

Pedido::where([
'vendedor_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id,
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);

$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedidointer.compras');


}






if ($frete == NULL && $retirada == 'Y' ) {

FreteInter::create([
'pedido_id'  => $id,
// 'produto_id' => $idproduto,
'id_cliente' =>  $idcliente,
'vendedor_id' =>   $idusuario,
'boolean' => 'Y',
'balcao' => 'Y',
'entrega' => NULL,
'status' => 'AR' //Aguardando Retirada

]);

Pedido::where([
'vendedor_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id,
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);


$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedidointer.compras');



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
// $idvendedor = $req->input('vendedor_id');

$idvendedor = Auth::id();
// $idusuario = $req->input('user_id');

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
$dataAtual = $req->input('dataAtual');
$dataRegistro = $req->input('dataRegistro');
// $idusuario = Auth::id();
// dd($valor);
// $cepOrigem = "09090520"; //CEP de Origem!!!!!!!!!


/* $produto = Produto::find($idproduto);
if( empty($produto->id) ) {
$req->session()->flash('mensagem-falha', 'Produto não encontrado em nossa loja!');
return redirect()->route('pedidos.index');
}*/




//  $check_cep = Cliente::select('cep')->where('id', '=', $idcliente)->get(); //CEP de destino!!!!!!!!!

if ($dataAtual > $dataRegistro){
$req->session()->flash('mensagem-falha', 'Pedido não pode mais ser alterado!');
return redirect()->route('pedidosint/{id}/edit', $id);
}




$check_pedido = Pedido::where([

'id'      => $id,
'vendedor_id' => $idvendedor,
//  'user_id' => $idusuario,
'status'  => 'RE' // Reservado
])->exists();

if( !$check_pedido ) {
$req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
return redirect()->route('pedidosint/{id}/edit', $id);
}


$idpedido = Pedido::consultaId([
'id' => $id,
'status'  => 'RE' // GERADO
]);

if( isset($idpedido) ) {

if( empty($pagamento) ) {
//  dd($pagamento);
$req->session()->flash('message', 'Preencha a forma de Pagamento!');
return redirect()->route('pedidosint/{id}/edit', $id)->withInput();
}

Pedido::where([
// 'user_id' => $idusuario,
//  'pedido_cod' => $codpedido,
'id' => $id,
'status'  => 'RE'
])->update([
'pagamento' => $pagamento,
//'vendedor_id' => $idvendedor,
'obs_pedido' => $obspedido

]);

} /*END ISSET PEDIDO*/





if ($retirada == NULL && $frete == NULL)
{
$req->session()->flash('message', 'Informe o tipo de frete!');
return redirect()->route('pedidosint/{id}/edit', $id)->withInput();

}




if ($frete == "B")
{

if ($valor == NULL) {
$req->session()->flash('message', 'Informar o custo do frete!');
return redirect()->route('pedidosint/{id}/edit', $id)->withInput();
}


if (isset($local))
{

FreteInter::where([
'pedido_id'  => $id

])->update([
// 'produto_id' => $idproduto,
'local' => $local,
'cep' =>        $cep,
'endereço' =>   $endereço,
'numero' =>  $numero,
'bairro' =>  $bairro,
'complemento' =>  $complemento,
'vendedor_id' => $idvendedor,
'cidade' =>  $cidade,
'estado' =>  $estado,
'id_cliente' =>  $idcliente,
'user_id' =>   NULL,

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

return redirect()->route('pedidointer.compras');

}

FreteInter::where([
'pedido_id'  => $id

])->update([
// 'produto_id' => $idproduto,
'id_cliente' =>  $idcliente,
'user_id' =>   NULL,
'vendedor_id' => $idvendedor, //Verificar
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

return redirect()->route('pedidointer.compras');



}


if ($frete == "C")
{

if ($valor == NULL) {
$req->session()->flash('message', 'Informar o custo do frete!');
return redirect()->route('indexint')->withInput();


}


if (isset($local))

{

FreteInter::where([
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
'vendedor_id' => $idvendedor,
'user_id' =>   NULL,
'boolean' => 'Y',
'balcao' => NULL,
'entrega' => 'C', //Correios 
'valor' => $valor,
'prazo_entrega' => $prazoentrega,
'serviço_correio' => $cdservico,
'status' => 'EC' //Entrega Correios


]);

Pedido::where([
'vendedor_id' => $idvendedor,
//  'pedido_cod' => $codpedido,
'id' => $id,
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);

$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedidointer.compras');

}



FreteInter::where([
'pedido_id'  => $id,


])->update([
// 'produto_id' => $idproduto,

'id_cliente' =>  $idcliente,
'user_id' =>   NULL,
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
'vendedor_id' => $idvendedor,
//  'pedido_cod' => $codpedido,
'id' => $id,
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);

$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedidointer.compras');


}






if ($frete == NULL && $retirada == 'Y' ) {

FreteInter::where([
'pedido_id'  => $id

])->update([
// 'produto_id' => $idproduto,
'id_cliente' =>  $idcliente,
'user_id' =>   NULL,
'vendedor_id' => $idvendedor,
'boolean' => 'Y',
'balcao' => 'Y',
'entrega' => NULL,
'valor' => NULL,
'status' => 'AR' //Aguardando Retirada



]);

Pedido::where([
'vendedor_id' => $idvendedor,
//  'pedido_cod' => $codpedido,
'id' => $id,
// 'status'  => 'GE'
])->update([
'status' => 'RE' //REservado
]);


$req->session()->flash('mensagem-sucesso', 'Pedido Salvo com sucesso!');

return redirect()->route('pedidointer.compras');



}






}


public function infoFrete(Request $request) {




$idpedido =  $request->pedido_id_load;


$cep_alt =  $request->cep_alter;

$cdservico =  $request->cdservico_alt;

if (empty($cdservico)){

$cdservico_vazio = "Preencha o Serviço!";


return $cdservico_vazio;

}

$idcliente = Pedido::select('id_cliente')->where('id', '=', $idpedido)->pluck('id_cliente');

if (empty($cep_alt)){

$cepdestino = ClienteInter::select('cep')->where('id', '=', $idcliente)->pluck('cep');

$cepdestino = str_replace('-', '',  $cepdestino);

$cepdestino = preg_replace("/[^0-9]/", "", $cepdestino);




} else {


$cepdestino =  $cep_alt;

$cepdestino = str_replace('-', '',  $cepdestino);

$cepdestino = preg_replace("/[^0-9]/", "", $cepdestino);


}

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



$url = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=16148185&sDsSenha=05304588&sCepOrigem=".$ceporigem."&sCepDestino=".$cepdestino."&nVlPeso=".$peso."&nCdFormato=".$formato."&nVlComprimento=".$comprimento."&nVlAltura=".$altura."&nVlLargura=".$largura."&sCdMaoPropria=".$maopropria."&nVlValorDeclarado=".$valordeclarado."&sCdAvisoRecebimento=".$avisorecebimento."&nCdServico=".$cdservico."&nVlDiametro=".$diametro."&StrRetorno=".$tiporetorno."&nIndicaCalculo=".$indicacalculo;






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

$cepdestino = ClienteInter::select('cep')->where('id', '=', $idcliente)->pluck('cep');

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




return $xml->cServico->PrazoEntrega.' Dias';// $xml->cServico->PrazoEntrega;


}

// $data1 = $xml->cServico->MsgErro
//   return response()->json($data);

// return response()->json($url);

//return response()->file($pathToFile);


// return redirect()->away($url);








}


public function concluir()
{
$this->middleware('VerifyCsrfToken');

$req = Request();
$idpedido  = $req->input('pedido_id');
$idusuario = Auth::id();
$idvendedor = $req->input('vendedor_id');
$local = $req->input('localIsset');
$cep = $req->input('cep');
$endereço = $req->input('endereço');
$numero = $req->input('numero');
$bairro = $req->input('bairro');
$complemento = $req->input('complemento');
$cidade = $req->input('cidade');
$estado = $req->input('estado');
$pagamentoIsset = $req->input('pagamentoIsset');




$check_pedido = Pedido::where([
'id'      => $idpedido,
'vendedor_id' => $idusuario,
'status'  => 'GE' // Reservada
])->exists();

if( !$check_pedido ) {
$req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
return redirect()->route('indexint');
}

/* $check_produtos = ItensPedido::where([
'pedido_id' => $idpedido
])->exists();

if(!$check_produtos) {
$req->session()->flash('mensagem-falha', 'ProdutoInters do pedido não encontrados!');
return redirect()->route('index');
}*/


if( $pagamentoIsset == NULL ) {
$req->session()->flash('mensagem-falha', 'Preencha a forma de Pagamento!');
return redirect()->route('indexint')->withInput();
}

/* ItensPedido::where([
'pedido_id' => $idpedido
])->update([
'status' => 'RE' //Reservado
]);*/


Pedido::where([
'id' => $idpedido
// 'obs_pedido' => $obs_pedido
])->update([
'pagamento' => $pagamentoIsset,
'vendedor_id' => $idvendedor,
'status' => 'RE' //Reservado

]);

FreteInter::where([
'pedido_id' => $idpedido,
'vendedor_id' => $idusuario,
// 'obs_pedido' => $obs_pedido
])->update([

'boolean' => 'N'
//'status' => 'RE' //Reservado
]);

$req->session()->flash('mensagem-sucesso', 'Pedido gerado com sucesso!');

return redirect()->route('pedidointer.compras');
}

public function compras()
{


$dadosVendedores=DB::table('vendedores')->get();




$compras = Pedido::where([
//  'status'  => 'RE'
'vendedor_id' => Auth::id()
])->take(100)->get();

$totalPage = ($compras)->count();

$idvendedor = Auth::id();

$pedidoVendedor = Pedido::select('id')->where('vendedor_id', '=', $idvendedor)->pluck('id');

$produtos = ItensPedido::select('prod_preco_padrao')->whereIn('pedido_id', $pedidoVendedor)->get();

$desconto_produtos = DB::table('itens_pedidos')->select('prod_desconto')->whereIn('pedido_id', $pedidoVendedor)->get();

$desconto_request = DB::table('itens_pedidos')->select('request_desconto')->whereIn('pedido_id', $pedidoVendedor)->get();

$total_desconto_prod = $desconto_produtos->sum('prod_desconto');

$total_desconto_req = $desconto_request->sum('request_desconto');

$desconto = $total_desconto_prod + $total_desconto_req;

$soma_produtos = $produtos->sum('prod_preco_padrao');

$frete  = FreteInter::whereIn('pedido_id', $pedidoVendedor)->get();

$frete_total = $frete->sum('valor');

$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;





$pedidos = Pedido::where([
'vendedor_id' => Auth::id(),
'consignado' => 'N'
])->where('status', '!=', 'GE')->get();
/*$pedidos = Pedido::where([
'vendedor_id' => Auth::id()
])->orderBy('id', 'desc')->take(100)->get();*/

$dadosClientes = ClienteInter::where('status', '!=', 'A')->get();


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
'total_preco', 'desconto', 'frete_total', 'soma_produtos'));

}


public function comissoes()
{

$dadosClientes=DB::table('clientes')->get();

//$dadosVendedores=DB::table('vendedores')->get();

$dadosPedidos=DB::table('pedidos')->get();

$idvendedor = Auth::id();


$compras = Pedido::where([
//  'status'  => 'RE'
'user_id' => Auth::id()
])->take(100)->get();

$totalPageSearch = ($compras)->count();

$ano = date("Y");

$periodo = date("m");


$pedidossearch = Pedido::select('id')->where(['vendedor_id' => Auth::id()])->where('status', '!=', 'GE')->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();

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
$pedidos = Pedido::where([
'vendedor_id' => Auth::id()
])->where('status', '!=', 'GE')->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->get();
//$pedidos = Pedido::where('status', '!=', 'GE')->get();
//$pedidos = Pedido::all();

//  $dadosClientes= Cliente::where('status', '!=', 'A')->get();

// dd($retirada);
$valorFreteInter = DB::table('fretes')->select('valor')->where([
//  'user_id' => Auth::id(),
'status' => 'EMB',
'entrega'   => 'B',
'boolean' => 'Y'
])->get();

$valorFreteInterC = DB::table('fretes')->select('valor')->where([
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

return view('vendedor.pedidoResource.comissoes', compact('compras',
'dadosPedidos',
'valorFreteInter',
'valorFreteInterC',
'ano',
'periodo',
'pedidos',
'idvendedor',
'cancelados',
'dadosClientes',
'totalPageSearch',
'total_preco', 'total_preco_prod', 'desconto', 'total', 'frete_total'));

}

public function allcomissoes()
{

$dadosClientes=DB::table('clientes')->get();

//$dadosVendedores=DB::table('vendedores')->get();

$dadosPedidos=DB::table('pedidos')->get();

$idvendedor = Auth::id();


$compras = Pedido::where([
//  'status'  => 'RE'
'user_id' => Auth::id()
])->take(100)->get();

$totalPageSearch = ($compras)->count();



$pedidossearch = Pedido::select('id')->where(['vendedor_id' => Auth::id()])->where('status', '!=', 'GE')->pluck('id');

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();

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
$pedidos = Pedido::where([
'vendedor_id' => Auth::id()
])->where('status', '!=', 'GE')->get();
//$pedidos = Pedido::where('status', '!=', 'GE')->get();
//$pedidos = Pedido::all();

//  $dadosClientes= Cliente::where('status', '!=', 'A')->get();

// dd($retirada);
$valorFreteInter = DB::table('fretes')->select('valor')->where([
//  'user_id' => Auth::id(),
'status' => 'EMB',
'entrega'   => 'B',
'boolean' => 'Y'
])->get();

$valorFreteInterC = DB::table('fretes')->select('valor')->where([
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

return view('vendedor.pedidoResource.comissoes', compact('compras',
'dadosPedidos',
'valorFreteInter',
'valorFreteInterC',
'pedidos',
'idvendedor',
'cancelados',
'dadosClientes',
'totalPageSearch',
'total_preco', 'total_preco_prod', 'desconto', 'total', 'frete_total'));

}


public function pedidoPdf($id)
{
$registros = ProdutoInter::where([
'ativo' => 's'
])->get();

$list_requisitions= OpycosRequestInter::where('status','=','FI')->get();


$pedidos = Pedido::findOrFail($id);

$retiradaBalcPF = FreteInter::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPF',
//   'entrega' => 'BPF',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();

// $dadosClientes= Frete::where('status', '!=', 'A')->get();
// dd($retiradaBalcPF);

$retiradaBalcPJ = FreteInter::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPJ',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();



// dd($retirada);

$freteB_PF = FreteInter::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPF',
'boolean' => 'N'

])->get();

$freteB_PJ = FreteInter::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPJ',
'boolean' => 'N'
])->get();


$freteC_PF = FreteInter::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPF',
'boolean' => 'N'
])->get();

$freteC_PJ = FreteInter::where([
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


return PDF::loadView('vendedor.pedidoResource.pdf-compras', compact('pedidos', 'registros', 'itenspedido', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions'))->stream('PedidosOpycos.pdf');



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
return redirect()->route('pedidointer.compras');
}

/*$check_pedido = Pedido::where([
'id'      => $idpedido,
'vendedor_id' => $idusuario,
'status'  => 'RE' // Finalizado
])->exists();*/

$check_pedidos = Pedido::where([
//'pedido_id' => $idpedido,
'vendedor_id' => $idusuario,
'status'    => 'RE' //Finalizado
])->whereIn('id', $idSpedido)->exists();

if( !$check_pedidos ) {
$req->session()->flash('mensagem-falha', 'Pedido não localizado para cancelamento!');
return redirect()->route('pedidointer.compras');
}

/* $check_produtos = ItensPedido::where([
'pedido_id' => $idpedido,
'status'    => 'RE' //Finalizado
])->whereIn('id', $idSpedido)->exists();

if( !$check_produtos ) {
$req->session()->flash('mensagem-falha', 'ProdutoInters do pedido não encontrados!');
return redirect()->route('pedidointer.compras');
}*/

/*ItensPedido::where([
'pedido_id' => $idpedido,
'status'    => 'RE' //Finalizado
])->whereIn('id', $idSpedido)->update([
'status' => 'CA' //Cancelado
// 'prod_preco_padrao' => 0.00
]);*/


$idrequest = ItensPedido::where([
'pedido_id' => $idSpedido,
'tipo' => 'R',
'status'    => 'GE' //Gerado
])->pluck('request_id');

if (isset($idrequest)) {


Pedido::where([
// 'id'      => $idpedido,
'vendedor_id' => $idusuario,
'status'  => 'RE' // Finalizado
])->whereIn('id', $idSpedido)->update([
'status' => 'CA' //Cancelado
// 'prod_preco_padrao' => 0.00
]);

FreteInter::where([
// 'id'      => $idpedido,
'vendedor_id' => $idusuario,
'boolean' => 'Y'
//  'status'  => 'RE' // Finalizado
])->whereIn('pedido_id', $idSpedido)->update([
'entrega' => 'CA', //Cancelado

]);


OpycosRequestInter::where([
// 'id'      => $idpedido,
// 'user_id' => $idusuario,
'ativo' => 's'
//  'status'  => 'RE' // Finalizado
])->whereIn('id', $idrequest)->update([
'status' => 'FI', //Cancelado

]);



$req->session()->flash('mensagem-sucesso', 'Pedidos Cancelados!');



return redirect()->route('pedidointer.compras');


} else {


Pedido::where([
// 'id'      => $idpedido,
'vendedor_id' => $idusuario,
'status'  => 'RE' // Finalizado
])->whereIn('id', $idSpedido)->update([
'status' => 'CA' //Cancelado
// 'prod_preco_padrao' => 0.00
]);

FreteInter::where([
// 'id'      => $idpedido,
'vendedor_id' => $idusuario,
'boolean' => 'Y'
//  'status'  => 'RE' // Finalizado
])->whereIn('pedido_id', $idSpedido)->update([
'entrega' => 'CA', //Cancelado

]);

$req->session()->flash('mensagem-sucesso', 'Pedidos Cancelados!');



return redirect()->route('pedidointer.compras');




}


}





public function finalizar()
{
$this->middleware('VerifyCsrfToken');

$req = Request();
//$idpedido       = $req->input('pedido_id');
//$idspedido_prod = $req->input('id');
$idSpedido = $req->input('id');
//dd($idSpedido);
$idusuario      = Auth::id();

if( empty($idSpedido) ) {
$req->session()->flash('mensagem-falha', 'Nenhum pedido selecionado para finalização!');
return redirect()->route('pedidointer.compras');
}

/*$check_pedido = Pedido::where([
'id'      => $idpedido,
'vendedor_id' => $idusuario,
'status'  => 'RE' // Finalizado
])->exists();*/

$check_pedidos = Pedido::where([
//'pedido_id' => $idpedido,
'vendedor_id' => $idusuario,
'status'    => 'RE' //Finalizado
])->whereIn('id', $idSpedido)->exists();

if( !$check_pedidos ) {
$req->session()->flash('mensagem-falha', 'Pedido não localizado para finalização!');
return redirect()->route('pedidointer.compras');
}

/* $check_produtos = ItensPedido::where([
'pedido_id' => $idpedido,
'status'    => 'RE' //Finalizado
])->whereIn('id', $idSpedido)->exists();

if( !$check_produtos ) {
$req->session()->flash('mensagem-falha', 'ProdutoInters do pedido não encontrados!');
return redirect()->route('pedidointer.compras');
}*/

/*ItensPedido::where([
'pedido_id' => $idpedido,
'status'    => 'RE' //Finalizado
])->whereIn('id', $idSpedido)->update([
'status' => 'CA' //Cancelado
// 'prod_preco_padrao' => 0.00
]);*/

Pedido::where([
// 'id'      => $idpedido,
'vendedor_id' => $idusuario,
'status'  => 'RE' // Finalizado
])->whereIn('id', $idSpedido)->update([
'status' => 'FI' //Finalizado
// 'prod_preco_padrao' => 0.00
]);

FreteInter::where([
// 'id'      => $idpedido,
'vendedor_id' => $idusuario,
'boolean' => 'Y'
//  'status'  => 'RE' // Finalizado
])->whereIn('pedido_id', $idSpedido)->update([
'entrega' => 'FI' //Finalizado
// 'prod_preco_padrao' => 0.00
]);



$req->session()->flash('mensagem-sucesso', 'Pedidos Finalizados!');



return redirect()->route('pedidointer.compras');
}




public function show($id) {
//
}

public function edit($id) {
$registros = ProdutoInter::where([
'ativo' => 's'
])->get();

$list_requisitions= OpycosRequestInter::where('status','=','FI')->get();


$pedidos = Pedido::findOrFail($id);

$retiradaBalcPF = FreteInter::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPF',
//   'entrega' => 'BPF',
'boolean' => 'N',
'vendedor_id' => Auth::id()
])->get();

// $dadosClientes= FreteInter::where('status', '!=', 'A')->get();
// dd($retiradaBalcPF);

$retiradaBalcPJ = FreteInter::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPJ',
'boolean' => 'N',
'vendedor_id' => Auth::id()
])->get();



// dd($retirada);

$freteB_PF = FreteInter::where([
'pedido_id' => $id,
'vendedor_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPF',
'boolean' => 'N'

])->get();

$freteB_PJ = FreteInter::where([
'pedido_id' => $id,
'vendedor_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPJ',
'boolean' => 'N'
])->get();


$freteC_PF = FreteInter::where([
'pedido_id' => $id,
'vendedor_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPF',
'boolean' => 'N'
])->get();

$freteC_PJ = FreteInter::where([
'pedido_id' => $id,
'vendedor_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPJ',
'boolean' => 'N'
])->get();

$valorFrete = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'vendedor_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'N'
])->get();

$valorFreteC = DB::table('fretes')->select('valor')->where([
'pedido_id' => $id,
'vendedor_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'N'
])->get();

$itenspedido = ItensPedido::where($id);


return view('vendedor.pedidoResource.alter-pedido', compact('pedidos', 'registros', 'itenspedido', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions'));
}




public function info($id) {
$registros = ProdutoInter::where([
'ativo' => 's'
])->get();

$list_requisitions= OpycosRequestInter::where('status','=','FI')->get();


$pedidos = Pedido::findOrFail($id);

$retiradaBalcPF = FreteInter::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPF',
//   'entrega' => 'BPF',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();

// $dadosClientes= Frete::where('status', '!=', 'A')->get();
// dd($retiradaBalcPF);

$retiradaBalcPJ = FreteInter::where([
'pedido_id' => $id,
'status' => 'AR',
'balcao' => 'YPJ',
'boolean' => 'N',
'user_id' => Auth::id()
])->get();



// dd($retirada);

$freteB_PF = FreteInter::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPF',
'boolean' => 'N'

])->get();

$freteB_PJ = FreteInter::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EMB',
'entrega' => 'BPJ',
'boolean' => 'N'
])->get();


$freteC_PF = FreteInter::where([
'pedido_id' => $id,
'user_id' => Auth::id(),
'status' => 'EC',
'entrega' => 'CPF',
'boolean' => 'N'
])->get();

$freteC_PJ = FreteInter::where([
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


return view('vendedor.pedidoResource.info-pedido', compact('pedidos', 'registros', 'itenspedido', 'retiradaBalcPF', 'retiradaBalcPJ','freteB_PF','freteB_PJ','freteC_PF','freteC_PJ','valorFrete','valorFreteC', 'list_requisitions'));
}

public function update($id) {

$this->middleware('VerifyCsrfToken');

$pedidos = Pedido::findOrFail($id);

$this->middleware('VerifyCsrfToken');

$req = Request();
$obspedido = $req->input('obs_pedido');
$idcliente = $req->input('id_cliente');
$idproduto = $req->input('id');


$produto = ProdutoInter::find($idproduto);
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
'vendedor_id' => $idusuario,
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




public function updateFreteInter($id) {

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



/*  $produto = ProdutoInter::find($idproduto);
if( empty($produto->id) ) {
$req->session()->flash('mensagem-falha', 'ProdutoInter não encontrado em nossa loja!');
return redirect()->route('pedidos.index');
}*/

$idusuario = Auth::id();
$idpedido = Pedido::consultaId([
'id' => $id,
'status'  => 'RE' // Reservada
]);

if( isset($idpedido) ) {
Pedido::where([
'vendedor_id' => $idusuario,
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

FreteInter::Where([
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



FreteInter::Where([
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

FreteInter::Where([
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

FreteInter::Where([
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

FreteInter::Where([
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

FreteInter::Where([
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

// $produtos = \App\Entities\ProdutoInter::pluck('prod_cod', 'prod_desc', 'prod_preco_padrao')->all();

/*  $valorFreteC = DB::table('fretes')->select('valor')->where([
'vendedor_id' => Auth::id(),
'status' => 'EC',
'boolean' => 'Y'
])->get();

$valorFrete = DB::table('fretes')->select('valor')->where([
'vendedor_id' => Auth::id(),
'status' => 'EMB',
'boolean' => 'Y'
])->get();*/

$id_cliente = $request->id_cliente;
$idvendedor = Auth::id();
//$vendedor_id = $request->vendedor_id;

$status = $request->status;
$pedido_id = $request->id;

if (isset($id_cliente) && isset($status)){
//dd($status);

$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $idvendedor,
'status'  => $status,
'id_cliente'  => $id_cliente
])->pluck('id');

$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');
$desconto = $desconto_produtos + $desconto_request;

// $frete = DB::table('fretes')->select('valor')->get();
/*  $fretesearch = Frete::select('id')->where([
'pedido_id'  => $pedidossearch
])->pluck('id');*/

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);


$frete_total = $frete->sum('valor');
// dd($frete_total);
$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;



}



elseif (isset($id_cliente)){

$pedidossearch = Pedido::select('id')->where(['vendedor_id' => $idvendedor, 'id_cliente'  => $id_cliente])->pluck('id');
// dd($pedidossearch);

/*    $pedidossearch = Pedido::select('id')->where([
'id_cliente'  => $id_cliente
])->pluck('id');*/

//dd($pedidossearch);


$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

// dd($produtossearch);

// $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
// $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;


/* $fretesearch = FreteInter::select('id')->where([
'vendedor_id'  => $idvendedor
])->pluck('id');*/

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();

$frete_total = $frete->sum('valor');

$geral = $soma_produtos + $frete_total -  $desconto;


$total_preco = $geral;

}

elseif ((isset($pedido_id)) && (isset($status)))

{


$pedidossearch = Pedido::select('id')->where(['vendedor_id' => $idvendedor, 'status'  => $status, 'id' => $pedido_id,])->pluck('id');

//dd($pedidossearch);


$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

// dd($produtossearch);

// $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
// $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;


$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();



$frete_total = $frete->sum('valor');

$geral = $soma_produtos + $frete_total -  $desconto;

$total_preco = $geral;


}

elseif (isset($status))
{

/*$pedidossearch = Pedido::select('id')->where([
'status'  => $status
])->pluck('id');*/
//dd($pedidossearch);
$pedidossearch = Pedido::select('id')->where(['vendedor_id' => $idvendedor, 'status'  => $status])->pluck('id');


$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

// dd($produtossearch);

// $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
// $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;


$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();

$frete_total = $frete->sum('valor');

$geral = $soma_produtos + $frete_total -  $desconto;



$total_preco = $geral;



}


else
{

$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $idvendedor,
'id'  => $pedido_id

])->pluck('id');

//dd($pedido_id);


$produtossearch  = ItensPedido::whereIn('pedido_id', $pedidossearch)->get();

// dd($produtossearch);

// $produtos = ItensPedido::select('prod_preco_padrao')->where(['pedido_id' => $pedidossearch])->get();
// $produtos = DB::table('itens_pedidos')->select('prod_preco_padrao')->get();
$soma_produtos = $produtossearch->sum('prod_preco_padrao');
$desconto_produtos = $produtossearch->sum('prod_desconto');
$desconto_request = $produtossearch->sum('request_desconto');

$desconto = $desconto_produtos + $desconto_request;

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();



$frete_total = $frete->sum('valor');

$geral = $soma_produtos + $frete_total - $desconto;

$total_preco = $geral;




}



$compras = Pedido::where([
'vendedor_id' => Auth::id()
])->orderBy('id', 'desc')->paginate(7);

$totalPage = ($compras)->count();



$dataForm = $request->except('_token');
$pedidos = $pedido->search($dataForm);


/*   if (!$pedidos) {

$req->session()->flash('mensagem-falha', 'Nenhum Pedido Localizado!');



return redirect()->route('pedidointer.compras');

}*/
// dd($pedidos);


$allsearch = $pedido->searchTotal($dataForm);
$totalSearch = ($allsearch)->count();
$totalPageSearch = ($pedidos)->count();




$dadosClientes=DB::table('clientes')->get();
$dadosVendedores=DB::table('vendedores')->get();
$dadosGroupsProduct=DB::table('groups_product')->get();
$dadosProducts=DB::table('products')->get();
$total = Pedido::where('vendedor_id', '=', $idvendedor)->count();


return view('vendedor.pedidoResource.compras', compact('dadosGroupsProduct','products', 'dataForm', 'dadosClientes', 'dadosVendedores', 'pedidos', 'compras', 'total', 'total_preco', 'valorFreteC', 'valorFrete', 'totalSearch', 'id_cliente', 'vendedor_id', 'status', 'totalPageSearch', 'totalPage', 'idvendedor', 'soma_produtos', 'desconto', 'frete_total'));

}

public function searchComissao(Request $request, Pedido $pedido)
{


$idvendedor = Auth::id();

$id_cliente = $request->id_cliente;

$vendedor_id = $request->vendedor_id;

$comissao = $request->comissao;

$status = $request->status;

$pedido_id = $request->id;

$ano = $request->updated_at;

$periodo = $request->periodo;

$consignado = $request->consignado;

$cancelados = $request->cancelados;




//dd($cancelados);

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}

elseif ((isset($vendedor_id))  && (isset($status))  && (isset($ano)) && (isset($comissao)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter

$contarpedido = count($pedidossearch);

/*if ((isset($vendedor_id)) && (isset($ano)) && (isset($periodo)) && ($status == 'FI') && ($comissao == 'PE') && ($contarpedido > 0))   {
$request->session()->flash('mensagem-sucesso', 'Foi gerado um novo lote para pagamento de comissão, clique no botão no canto direito da tela e conclua o pagamento para liberar o relatório.');	
}*/



}

elseif ((isset($vendedor_id))  && (isset($status))  && (isset($ano)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}
elseif ((isset($vendedor_id)) && (isset($periodo)) && (isset($comissao)))
{



$pedidossearch = Pedido::select('id')->where([
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}
elseif ((isset($cancelados)) && (isset($status)) && (isset($comissao)) && (isset($ano)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}
elseif ((isset($cancelados)) && (isset($status)) && (isset($ano)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}

elseif ((isset($cancelados)) && (isset($status)) && (isset($ano)) && (isset($comissao)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}
elseif ((isset($cancelados)) && (isset($status)) && (isset($ano)) )
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}

elseif ((isset($cancelados)) && (isset($status)) && (isset($comissao)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status,
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}
elseif ((isset($cancelados)) && (isset($status)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
'status' => $status

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}

elseif ((isset($cancelados)) && (isset($comissao)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}
elseif ((isset($cancelados)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}
elseif ((isset($status)) && (isset($comissao))  && (isset($ano)) && (isset($periodo)))

{

$pedidossearch = Pedido::select('id')->where(['vendedor_id' => $idvendedor, 'status' => $status, 'comissao' => $comissao])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}

elseif ((isset($status)) && (isset($ano)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where(['vendedor_id' => $idvendedor, 'status' => $status])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}
elseif ((isset($status)) && (isset($comissao))  && (isset($ano)))
{


$pedidossearch = Pedido::select('id')->where(['vendedor_id' => $idvendedor, 'status' => $status, 'comissao' => $comissao])->whereYear('updated_at', $ano)->pluck('id');

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}

elseif ((isset($status)) && (isset($comissao))  && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $idvendedor,	
'status' => $status,
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}

elseif ((isset($cancelados)) && (isset($ano)) && (isset($comissao)) && (isset($periodo)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}
elseif ((isset($cancelados)) && (isset($ano)) && (isset($periodo)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}

elseif ((isset($cancelados)) && (isset($comissao)) && (isset($ano)))
{



$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados,
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}
elseif ((isset($cancelados)) && (isset($ano)))
{


$pedidossearch = Pedido::select('id')->where([
'cancelados' => $cancelados


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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}

elseif ((isset($status))  && (isset($ano)))
{



$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $idvendedor,
'status' => $status

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}

elseif ((isset($ano)) && (isset($periodo)) && (isset($comissao)))
{

$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $idvendedor,
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

elseif ((isset($periodo)) && (isset($status)))

{



$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $idvendedor,	
'status' => $status])->whereMonth('updated_at', $periodo)->pluck('id');

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}



elseif ((isset($ano)) && (isset($comissao)))

{
	

$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $idvendedor,	
'comissao'  => $comissao
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter


}

elseif ((isset($periodo)) && (isset($comissao)))

{
	

$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $idvendedor,
'comissao'  => $comissao
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter


}


elseif ((isset($periodo)) && (isset($ano)))
{



$pedidossearch = Pedido::select('id')->where(['vendedor_id' => $idvendedor])->whereYear('updated_at', $ano)->whereMonth('updated_at', $periodo)->pluck('id');

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter


}




elseif (isset($ano))

{


//$pedidossearch = Pedido::select('id')->where('updated_at', 'like', $ano.'%')->pluck('id');
$pedidossearch = Pedido::select('id')->where(['vendedor_id' => $idvendedor])->whereYear('updated_at', $ano)->pluck('id');

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter


}


elseif (isset($periodo))

{


$pedidossearch = Pedido::select('id')->where(['vendedor_id' => $idvendedor])->whereMonth('updated_at', $periodo)->pluck('id');

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter


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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter


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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter


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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter



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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter



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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter


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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter


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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter


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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter


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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter



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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter



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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




}

elseif ((isset($vendedor_id))  && (isset($status)) && (isset($comissao)))
	{


$pedidossearch = Pedido::select('id')->where([

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter

}



elseif ((isset($vendedor_id))  && (isset($status)))
{


$pedidossearch = Pedido::select('id')->where([
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter



}

elseif ((isset($vendedor_id)) && (isset($comissao)))

{


$pedidossearch = Pedido::select('id')->where([

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter

}

elseif (isset($vendedor_id))
{



$pedidossearch = Pedido::select('id')->where([

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter



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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter




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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter

}


elseif ((isset($status)) && (isset($comissao)))
{


$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $idvendedor,	
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter

}

elseif (isset($comissao))
{


$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $idvendedor,	
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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter

}

elseif (isset($status))
{



$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $idvendedor,	
'status' => $status

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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter


}



else 
{

$pedidossearch = Pedido::select('id')->where([
'vendedor_id' => $idvendedor,
'id'	=> $pedido_id


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

$frete  = FreteInter::whereIn('pedido_id', $pedidossearch)->get();
//dd($frete);
$frete_total = $frete->sum('valor');

$total = $total_produtos - $desconto; //Total Produto - desconto+FreteInter


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



return view('vendedor.pedidoResource.comissoes', compact('dadosGroupsProduct', 'dataForm', 'dadosClientes', 'dadosVendedores', 'pedidos', 'compras', 'total', 'total_preco', 'totalSearch', 'id_cliente', 'vendedor_id', 'idvendedor', 'comissao', 'totalPageSearch', 'totalPage', 'total_preco_prod', 'desconto', 'dadosPedidos', 'status', 'ano', 'periodo', 'frete_total'));

}

public function findProductName(Request $request)
{

$data=ProdutoInter::select('prod_desc', 'id')->where('grup_cod', $request->id)->take(100)->get();

//if our chosen id and products table prod_cat_id
// $request->id here is the id of our chosen option id

return response()->json($data);
}

public function findProductCod(Request $request)

{

$p=ProdutoInter::select('prod_cod')->where('id', $request->id)->first();



return response()->json($p);
}






}
