<?php

namespace App\Http\Controllers;

use App\Entities\ProdutoInter;
use App\Entities\GroupProducts;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;



class ProdutoInterController extends Controller
{

    /*    public function __construct()
    {
        $this->middleware('auth');
    }*/
    
    public function index() {

        //$list_groupsProd = \App\Entities\GroupProducts::pluck('id','grup_desc')->all();
        $list_groupsProd = \App\Entities\GroupProducts::all();
        $products = ProdutoInter::orderBy('id', 'desc')->paginate(5);       
        //$produtos = Produto::all();
        $total = ProdutoInter::all()->count();

        $registros = ProdutoInter::where([
            'ativo' => 's'
            ])->get();
        

        return view('vendedor.produtoResource.list-produtos', compact('products', 'total','registros', 'list_groupsProd' ));
    }


        public function vendedorindex() {

        //$list_groupsProd = \App\Entities\GroupProducts::pluck('id','grup_desc')->all();
        $list_groupsProd = \App\Entities\GroupProducts::all();
        $products = ProdutoInter::orderBy('id', 'desc')->paginate(5);       
        //$produtos = Produto::all();
        $total = ProdutoInter::all()->count();

        $registros = ProdutoInter::where([
            'ativo' => 's'
            ])->get();
        

        return view('vendedor.produtoResource.list-produtos', compact('products', 'total','registros', 'list_groupsProd' ));
    }

    public function create() {

        //$list_GroupProducts = \App\Entities\GroupProducts::pluck('grup_desc', 'grup_cod')->all();
        //$list_GroupCategory = \App\Entities\GroupProducts::pluck('grup_desc_categ', 'grup_categ_cod')->all();
        $list_CategProd = GroupProducts::all();
        $list_groups = GroupProducts::all();
        //dd($list_CategProd);



        return view('vendedor.produtoResource.include-produto', compact('list_CategProd', 'list_groups'));
    }

    public function store(Request $request) {
        $product = new ProdutoInter;
        $product->prod_desc = $request->prod_desc;
        $product->prod_cod = $request->prod_cod;
        $product->grup_cod = $request->grup_cod;
        $product->grup_categ_cod = $request->grup_cod;
        $product->prod_preco_padrao = str_replace(",",".", str_replace('.', '', $request->prod_preco_padrao));
        $product->prod_preco_prof = str_replace(",",".",  str_replace('.', '', $request->prod_preco_prof));
        $product->prod_preco_balcao = str_replace(",",".",  str_replace('.', '', $request->prod_preco_balcao));
        $product->save();       
        return redirect()->route('productinter.index')->with('message', 'Produto criado com sucesso!');

    }

    public function show($id) {
        //
    }

    public function edit($id) {
        $product = ProdutoInter::findOrFail($id);
        $list_CategProd = GroupProducts::all();
        
        return view('vendedor.produtoResource.alter-produto', compact('product', 'list_CategProd'));
    }

    public function update(Request $request, $id) {
        $product = ProdutoInter::findOrFail($id); 
        $product->prod_desc = $request->prod_desc;
        $product->prod_cod = $request->prod_cod;
        $product->grup_cod = $request->grup_cod;
        $product->grup_categ_cod = $request->grup_cod;
        $product->prod_preco_padrao = str_replace(",",".", str_replace('.', '', $request->prod_preco_padrao));
        $product->prod_preco_prof = str_replace(",",".",  str_replace('.', '', $request->prod_preco_prof));
        $product->prod_preco_balcao = str_replace(",",".",  str_replace('.', '', $request->prod_preco_balcao));
        //$product->prod_preco_padrao = $request->prod_preco_padrao;
        //$product->prod_preco_prof = $request->prod_preco_prof;
        //$product->prod_preco_balcao =  $request->prod_preco_balcao;

        $product->save();
        return redirect()->route('productinter.index')->with('message', 'Produto alterado com sucesso!');
 
    }

    public function destroy($id) {
        $product = ProdutoInter::findOrFail($id);
        $product->delete();
        return redirect()->route('productinter.index')->with('message', 'Produto excluído com sucesso!');
    }


    public function searchProduto(Request $request, ProdutoInter $product)
    {
        $list_groupsProd = \App\Entities\GroupProducts::all();
        $dataForm = $request->except('_token');        
        $products = $product->search($dataForm);
        $total = ProdutoInter::all()->count();            
        
        return view('vendedor.produtoResource.list-produtos', compact('products', 'total', 'dataForm', 'list_groupsProd' ));

    }


    public function findProductName(Request $request)
    {   
        
      $data=GroupProducts::select('grup_cod', 'id')->where('grup_categ_cod', $request->id)->take(100)->get();

        //if our chosen id and products table prod_cat_id
       // $request->id here is the id of our chosen option id

        return response()->json($data);
    }

            public function findProductCod(Request $request)
    {   
        
      $p=Produto::select('prod_cod')->where('id', $request->id)->first();

       

        return response()->json($p);
    }


}
