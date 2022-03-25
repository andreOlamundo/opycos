<?php

namespace App\Http\Controllers;


use App\Entities\Frete;
use App\Entities\ItensPedido;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use DB;




class FretePedidoController extends Controller
{
    
    
    public function index() {
        $frete = Frete::paginate(10);        
       // $total = Frete::pluck('valor')->sum();       
        return view('admin.pedidoResource.include-pedido', compact('itenspedido'));
    }

    /*public function create() {

        $dadosClientes=DB::table('clientes')->get();
        $dadosVendedores=DB::table('vendedores')->get();
        $dadosProducts=DB::table('products')->get();
        $itenspedido = ItensPedido::paginate(10);
        //$list_clientes = \App\Entities\Cliente::pluck('name', 'id')->all();
        //$list_vendedores = \App\Entities\Vendedor::pluck('name', 'id')->all();
        return view('admin.pedidoResource.include-pedido', compact('itenspedido', 'dadosClientes', 'dadosVendedores', 'dadosProducts'));
    }

    public function store(Request $request) {
      $itempedido = new ItensPedido;
      $itempedido->numero_pedido = $request->numero_pedido;
      $itempedido->$prod_cod = $request->prod_cod;
      $itempedido->$grup_cod = $request->grup_cod;
      $itempedido->$grup_categ_cod = $request->grup_categ_cod;
      $itempedido->$quantidade = $request->quantidade;
      $itempedido->$prod_desc = $request->prod_desc;
      $itempedido->$product->prod_preco_padrao = str_replace(",",".", str_replace('.', '', $request->prod_preco_padrao));
      //$pedidos->tipo_pedido_id = $request->tipo_pedido_id;//balcão/profissionais/Padrão 
      $itempedido->save();
      return redirect()->route('includeItens.index')->with('message', 'Pedido gerado com sucesso!');
    }

    public function show($id) {
        //
    }

    public function edit($id) {
        $itenspedido = ItensPedido::findOrFail($id);
        return view('admin.pedidoResource.alter-pedido', compact('itenspedido'));
    }

    public function update(Request $request, $id) {

      $itempedido = ItensPedido::findOrFail($id); 
      $itempedido->numero_pedido = $request->numero_pedido;
      $itempedido->$prod_cod = $request->prod_cod;
      $itempedido->$grup_cod = $request->grup_cod;
      $itempedido->$grup_categ_cod = $request->grup_categ_cod;
      $itempedido->$quantidade = $request->quantidade;
      $itempedido->$prod_desc = $request->prod_desc;
      $itempedido->$product->prod_preco_padrao = str_replace(",",".", str_replace('.', '', $request->prod_preco_padrao));
      $itempedido->save();
        return redirect()->route('includeItens.index')->with('message', 'Produto Incluído!');
    }

    public function destroy($id) {
        $itempedido = ItensPedido::findOrFail($id);
        $itempedido->delete();
        return redirect()->route('includeItens.index')->with('message', 'Produto excluído com sucesso!');
    }

   public function searchProdutoPedido(Request $request, Produto $product)
    {          
       // $produtos = \App\Entities\Produto::pluck('prod_cod', 'prod_desc', 'prod_preco_padrao')->all();
        
        $dataForm = $request->except('_token');        
        $products = $product->search($dataForm);
        $dadosClientes=DB::table('clientes')->get();
        $dadosVendedores=DB::table('vendedores')->get();
        $dadosProducts=DB::table('products')->get();


        $total = Pedido::pluck('prod_preco_padrao')->sum();     
         
        
        return view('admin.pedidoResource.include-pedido', compact('products', 'total', 'dadosProducts', 'dataForm', 'dadosClientes', 'dadosVendedores'));

    }*/

}
